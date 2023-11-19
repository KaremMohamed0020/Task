<?php

namespace App\Http\Requests;

// In PaymentRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'transaction_id' => 'required|exists:transactions,id',
            'amount' => 'required|numeric',
            'paid_on' => 'required|date',
            'details' => 'nullable|string',
        ];
    }
}
