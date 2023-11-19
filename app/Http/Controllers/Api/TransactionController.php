<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MonthlyReportRequest;
use App\Http\Requests\PaymentRequest;
use Illuminate\Http\Request;
use App\Services\TransactionService;
use App\Http\Requests\TransactionRequest;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function createTransaction(TransactionRequest $request)
    {
        $data = $request->validated();
        $transaction = $this->transactionService->createTransaction($data);
        return response()->json(['message' => 'Transaction added successfully'], 200);
    }


    public function recordPayment(PaymentRequest $request)
    {
        $data = $request->validated();
        $this->transactionService->recordPayment($data);
        return response()->json(['message' => 'Payment added successfully'], 200);
    }

    public function viewTransactions()
    {
        $user = auth()->user();

        // Retrieve transactions based on user type (admin or customer)
        $transactions = $user->isAdmin() ? $this->transactionService->getAllTransactions() : $this->transactionService->getUserTransactions($user);

        // Return the transactions or use them as needed
        return response()->json(['transactions' => $transactions]);
    }

    public function generateMonthlyReport()
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        // Call a method in the TransactionService to get the monthly report
        $report = $this->transactionService->generateMonthlyReport();

        return response()->json(['report' => $report]);
    }
}
