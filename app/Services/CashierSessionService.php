<?php

namespace App\Services;

use App\Models\CashierSession;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\Invoice;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashierSessionService
{
    /**
     * Get active cashier session or throw exception if cashier is not clocked in
     */
    public static function getActiveSession(int $userId): ?CashierSession
    {
        return CashierSession::where('user_id', $userId)
            ->where('status', 'open')
            ->latest('opened_at')
            ->first();
    }

    /**
     * Open a new shift session
     */
    public static function openSession(int $userId, float $openingFloat = 0.00): CashierSession
    {
        $existing = self::getActiveSession($userId);
        if ($existing) {
            return $existing;
        }

        $sessionCount = CashierSession::count() + 1;
        $sessionNumber = 'SESS-' . date('Ymd') . '-' . str_pad($sessionCount, 4, '0', STR_PAD_LEFT);

        return CashierSession::create([
            'session_number' => $sessionNumber,
            'user_id' => $userId,
            'opening_float' => $openingFloat,
            'status' => 'open',
            'opened_at' => now(),
        ]);
    }

    /**
     * Close Shift Session with Blind Cash Count & Variance Logging
     */
    public static function closeSession(int $sessionId, float $actualCash, ?string $varianceReason = null): CashierSession
    {
        return DB::transaction(function () use ($sessionId, $actualCash, $varianceReason) {
            $session = CashierSession::lockForUpdate()->findOrFail($sessionId);

            if ($session->status === 'closed') {
                throw new Exception('This shift session is already closed.');
            }

            // Tally all payments received under this session
            $expectedCash = $session->opening_float + Payment::where('cashier_session_id', $session->id)
                ->where('payment_method', 'Cash')
                ->where('status', 'completed')
                ->sum('amount');

            $expectedMpesa = Payment::where('cashier_session_id', $session->id)
                ->where('payment_method', 'M-PESA')
                ->where('status', 'completed')
                ->sum('amount');

            $expectedCard = Payment::where('cashier_session_id', $session->id)
                ->where('payment_method', 'Card')
                ->where('status', 'completed')
                ->sum('amount');

            $expectedInsurance = Payment::where('cashier_session_id', $session->id)
                ->where('payment_method', 'Insurance')
                ->where('status', 'completed')
                ->sum('amount');

            $varianceCash = $actualCash - $expectedCash;

            $session->update([
                'closing_cash_actual' => $actualCash,
                'expected_cash' => $expectedCash,
                'expected_mpesa' => $expectedMpesa,
                'expected_card' => $expectedCard,
                'expected_insurance' => $expectedInsurance,
                'variance_cash' => $varianceCash,
                'variance_reason' => $varianceReason,
                'status' => 'closed',
                'closed_at' => now(),
            ]);

            return $session;
        });
    }

    /**
     * Generate Next Sequential Receipt Number without race conditions
     */
    public static function issueSequentialReceipt(Payment $payment, Invoice $invoice, float $previousBalance, float $newBalance): Receipt
    {
        // Lock the receipts table row calculation
        $lastReceipt = Receipt::lockForUpdate()->latest('id')->first();
        $nextId = $lastReceipt ? ($lastReceipt->id + 1) : 1;
        $receiptNumber = 'RCT-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

        return Receipt::create([
            'receipt_number' => $receiptNumber,
            'payment_id' => $payment->id,
            'invoice_id' => $invoice->id,
            'patient_id' => $invoice->encounter->patient_id ?? $payment->invoice->patient_id,
            'cashier_id' => Auth::id(),
            'amount_paid' => $payment->amount,
            'previous_balance' => $previousBalance,
            'new_balance' => $newBalance,
        ]);
    }
}
