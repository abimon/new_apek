<?php

namespace App\Http\Middleware;

use App\Support\AuditActivity;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuditSensitiveActivity
{
    public function __construct(private readonly AuditActivity $auditActivity) {}

    public function handle(Request $request, Closure $next): Response
    {
        $routeName = (string) $request->route()?->getName();
        $isLoginAttempt = $request->isMethod('POST') && $request->is('login');
        $isLogout = $routeName === 'logout';
        $isFirstLoginPasswordChange = $routeName === 'password.first-login.update';
        $event = match (true) {
            $isLoginAttempt => 'auth.login',
            $isLogout => 'auth.logout',
            $isFirstLoginPasswordChange => 'auth.password.first_login',
            default => $this->adminMutationEvent($request, $routeName),
        };

        if ($event === null) {
            return $next($request);
        }

        $metadata = [];
        if ($isLoginAttempt) {
            $identifier = (string) $request->input('email', '');
            if ($identifier !== '') {
                $metadata['identifier_fingerprint'] = $this->auditActivity->loginIdentifierFingerprint($identifier);
            }
        }

        $subject = $this->subjectFromRoute($request);
        $log = $this->auditActivity->record($request, $event, metadata: $metadata, subject: $subject);

        try {
            $response = $next($request);
        } catch (Throwable $exception) {
            $log->update(['status' => 'failed']);
            throw $exception;
        }

        $newFlashData = $request->session()->get('_flash.new', []);
        $hasValidationErrors = is_array($newFlashData) && in_array('errors', $newFlashData, true);
        $failed = $response->getStatusCode() >= 400 || $hasValidationErrors;
        $auditSubject = $request->attributes->get('audit_subject') ?? $subject;
        $log->update([
            'user_id' => $request->user()?->getAuthIdentifier() ?? $log->user_id,
            'subject_type' => $auditSubject ? $auditSubject::class : null,
            'subject_id' => $auditSubject && method_exists($auditSubject, 'getKey') ? $auditSubject->getKey() : null,
            'status' => $failed ? 'failed' : 'succeeded',
            'http_status' => $response->getStatusCode(),
        ]);

        return $response;
    }

    private function adminMutationEvent(Request $request, string $routeName): ?string
    {
        if (! in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return null;
        }

        foreach (['admin.projects', 'admin.categories', 'admin.tech-stacks', 'admin.inquiries', 'admin.portfolio-users', 'admin.portfolio-projects', 'admin.users', 'portfolio.projects', 'portfolio.profile'] as $resource) {
            if (str_starts_with($routeName, $resource.'.')) {
                return str_replace('.', '_', $routeName);
            }
        }

        return null;
    }

    private function subjectFromRoute(Request $request): ?object
    {
        foreach ($request->route()?->parameters() ?? [] as $parameter) {
            if (is_object($parameter) && method_exists($parameter, 'getKey')) {
                return $parameter;
            }
        }

        return null;
    }
}
