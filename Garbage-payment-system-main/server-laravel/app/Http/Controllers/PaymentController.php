<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function me(): JsonResponse
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'UNAUTHENTICATED'], Response::HTTP_UNAUTHORIZED);
        }

        $rows = Payment::query()
            ->where('user_id', $user->id)
            ->orderByDesc('due_year')
            ->orderByDesc('due_month')
            ->orderByDesc('created_at')
            ->get();

        $summary = [
            'total' => $rows->count(),
            'paid' => $rows->where('status', 'paid')->count(),
            'unpaid' => $rows->where('status', 'unpaid')->count(),
            'pending' => $rows->where('status', 'pending')->count(),
            'amountPaid' => $rows->where('status', 'paid')->sum('amount'),
        ];

        return response()->json([
            'ok' => true,
            'rows' => $rows,
            'summary' => $summary,
        ]);
    }

    public function adminSummary(): JsonResponse
    {
        $user = Auth::user();

        if (! $user || $user->email !== 'admin02@example.com') {
            return response()->json(['error' => 'FORBIDDEN'], Response::HTTP_FORBIDDEN);
        }

        $rows = Payment::query()->get();
        $paid = $rows->where('status', 'paid');
        $unpaid = $rows->where('status', 'unpaid');
        $pending = $rows->where('status', 'pending');

        return response()->json([
            'ok' => true,
            'cards' => [
                'paidCount' => $paid->count(),
                'unpaidCount' => $unpaid->count(),
                'pendingCount' => $pending->count(),
                'paidAmount' => $paid->sum('amount'),
            ],
        ]);
    }
}
