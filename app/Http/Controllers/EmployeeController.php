<?php

namespace App\Http\Controllers;

use App\Models\Reimbursement;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index() {
        $userId = auth()->id(); // hanya data user yang login

        $reimbursements = Reimbursement::where('employee_id', $userId)->latest()->paginate(10);
        $total = $reimbursements->count();
        $pending = $reimbursements->where('status', 'pending')->count();
        $approved = $reimbursements->where('status', 'approved')->count();
        $rejected = $reimbursements->where('status', 'rejected')->count();

        return view('employee.dashboard', [
            'reimbursements' => $reimbursements,
            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
        ]);
    }
}
