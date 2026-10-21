<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuditActivity
{
    public function record(Request $request, string $event, string $status = 'attempted', array $metadata = [], ?object $subject = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => $request->user()?->getAuthIdentifier(),
            'event' => $event,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject && method_exists($subject, 'getKey') ? $subject->getKey() : null,
            'route' => $request->route()?->getName(),
            'method' => $request->method(),
            'status' => $status,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 500, ''),
            'metadata' => $metadata ?: null,
            'occurred_at' => now(),
        ]);
    }

    public function loginIdentifierFingerprint(string $identifier): string
    {
        return hash_hmac('sha256', mb_strtolower(trim($identifier)), (string) config('app.key'));
    }
}
