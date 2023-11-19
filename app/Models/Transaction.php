<?php

namespace App\Models;

// In Transaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaction extends Model
{
    protected $fillable = ['amount', 'user_id', 'due_on', 'vat', 'is_vat_inclusive', 'status'];

    public function calculateStatus()
    {
        $currentDateTime = Carbon::now();

        if ($this->due_on < $currentDateTime) {
            // Transaction is overdue
            $this->status = 'Overdue';
        } elseif ($this->amount == 0) {
            // Transaction has no payments and is outstanding
            $this->status = 'Outstanding';
        } elseif ($this->amount > 0 && $this->amount == $this->totalPaidAmount()) {
            // Transaction is fully paid
            $this->status = 'Paid';
        } else {
            // Transaction is still outstanding
            $this->status = 'Outstanding';
        }

        // Save the updated status to the database
        $this->save();

        return $this->status;
    }

    private function totalPaidAmount()
    {
        // Calculate the total amount paid
        return $this->payments()->sum('amount');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
