<?php

// In TransactionService.php

namespace App\Services;

// In TransactionService.php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function createTransaction(array $data)
    {

        $transaction = new Transaction([
            'amount' => $data['amount'],
            'user_id' => $data['user_id'],
            'due_on' => $data['due_on'],
            'vat' => $data['vat'],
            'is_vat_inclusive' => $data['is_vat_inclusive'],
        ]);

        // Save the transaction
        $transaction->save();

        // Calculate and set the transaction status
        $transaction->calculateStatus();

        return $transaction;
    }

    public function recordPayment(array $data)
    {
        // Create a new payment
        $payment = new Payment([
            'transaction_id' => $data['transaction_id'],
            'amount' => $data['amount'],
            'paid_on' => $data['paid_on'],
            'details' => $data['details'] ?? null,
        ]);

        // Save the payment
        $payment->save();

        // Update the transaction status based on the payment
        $transaction = Transaction::find($data['transaction_id']);
        $transaction->calculateStatus();

        return $payment;
    }

    public function getAllTransactions()
    {
        return Transaction::paginate();
    }

    public function getUserTransactions(User $user)
    {
        return $user->transactions;
    }

    public function generateMonthlyReport()
    {
        // Use Eloquent to retrieve aggregated data for the monthly report without date filtering
        $report = Transaction::select(
            DB::raw('MONTH(due_on) as month'),
            DB::raw('YEAR(due_on) as year'),
            DB::raw('SUM(CASE WHEN status = "Paid" THEN amount ELSE 0 END) as paid'),
            DB::raw('SUM(CASE WHEN status = "Outstanding" THEN amount ELSE 0 END) as outstanding'),
            DB::raw('SUM(CASE WHEN status = "Overdue" THEN amount ELSE 0 END) as overdue')
        )
            ->groupBy(DB::raw('YEAR(due_on), MONTH(due_on)'))
            ->get();

        // Format the response structure
        $formattedReport = $report->map(function ($item) {
            return [
                'month' => $item->month,
                'year' => $item->year,
                'paid' => number_format($item->paid, 2),
                'outstanding' => number_format($item->outstanding, 2),
                'overdue' => number_format($item->overdue, 2),
            ];
        });

        return $formattedReport;
    }
}
