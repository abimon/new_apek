<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Inertia\Inertia;
use Inertia\Response;

class AdminAuditLogController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/audit-logs/index', [
            'logs' => AuditLog::query()
                ->with('actor:id,name')
                ->latest('occurred_at')
                ->paginate(50, [
                    'id',
                    'user_id',
                    'event',
                    'subject_type',
                    'subject_id',
                    'route',
                    'method',
                    'status',
                    'http_status',
                    'ip_address',
                    'occurred_at',
                ]),
        ]);
    }
}
