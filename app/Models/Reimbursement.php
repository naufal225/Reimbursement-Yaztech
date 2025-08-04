<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reimbursement extends Model
{
    protected $table = "reimbursement";

    protected $guarded = ['id'];

    public function approver() {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function employee() {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
