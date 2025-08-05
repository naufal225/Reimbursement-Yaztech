<?php

namespace App\Http\Controllers;

use App\Models\Reimbursement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index()
    {
        $userId = auth()->id(); // hanya data user yang login

        $reimbursements = Reimbursement::where('employee_id', $userId)->latest()->paginate(perPage: 10);
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

    public function create()
    {
        // Get dummy approvers data (you can replace this with actual data from database)
        $approvers = User::where('role', 'approver')->get();

        return view('employee.reimbursement', compact('approvers'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'total' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Remove formatting and convert to integer
                    $cleanValue = (int) str_replace(['.', ','], '', $value);
                    if ($cleanValue < 1000) {
                        $fail('The total amount must be at least Rp 1,000.');
                    }
                    if ($cleanValue > 10000000) {
                        $fail('The total amount must not exceed Rp 10,000,000.');
                    }
                },
            ],
            'agenda' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'invoice' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
            'approver_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('id', '!=', Auth::id());
                }),
            ],
        ], [
            // Custom error messages
            'total.required' => 'Please enter the total amount.',
            'agenda.required' => 'Please enter the expense category/agenda.',
            'agenda.max' => 'The agenda field must not exceed 255 characters.',
            'description.max' => 'The description field must not exceed 1000 characters.',
            'invoice.required' => 'Please upload an invoice or receipt.',
            'invoice.file' => 'The invoice must be a valid file.',
            'invoice.mimes' => 'The invoice must be a JPG, JPEG, PNG, or PDF file.',
            'invoice.max' => 'The invoice file size must not exceed 5MB.',
            'approver_id.required' => 'Please select an approver.',
            'approver_id.exists' => 'The selected approver is invalid.',
        ]);

        try {
            // Clean and convert total amount
            $cleanTotal = (int) str_replace(['.', ','], '', $validated['total']);

            // Handle file upload
            $invoicePath = null;
            if ($request->hasFile('invoice')) {
                $file = $request->file('invoice');
                $fileName = time() . '_' . Auth::id() . '_' . $file->getClientOriginalName();

                // Store in storage/app/public/invoices
                $invoicePath = $file->storeAs('invoices', $fileName, 'public');
            }

            // Create reimbursement record
            $reimbursement = Reimbursement::create([
                'employee_id' => Auth::id(),
                'approver_id' => $validated['approver_id'],
                'total' => $cleanTotal,
                'agenda' => $validated['agenda'],
                'description' => $validated['description'] ?? null,
                'invoice_path' => $invoicePath,
                'status' => 'pending',
            ]);

            // Optional: Send notification to approver (you can implement this later)
            // $this->notifyApprover($reimbursement);

            return redirect()
                ->route('employee.dashboard')
                ->with('success', 'Reimbursement request submitted successfully! Your request is now pending approval.');

        } catch (\Exception $e) {
            // Log the error
            Log::error('Reimbursement submission failed: ' . $e->getMessage());

            // Delete uploaded file if it exists and there was an error
            if (isset($invoicePath) && Storage::disk('public')->exists($invoicePath)) {
                Storage::disk('public')->delete($invoicePath);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to submit reimbursement request. Please try again.');
        }
    }
}
