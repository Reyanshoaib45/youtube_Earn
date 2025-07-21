<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;

class PurchaseRequestController extends Controller
{
    public function index()
    {
        $requests = PurchaseRequest::with(['user', 'package'])->latest()->get();
        return view('manager.purchase_requests.index', compact('requests'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'nullable|string|max:255',
        ]);

        $purchaseRequest = PurchaseRequest::findOrFail($id);
        $purchaseRequest->status = $request->status;
        $purchaseRequest->rejection_reason = $request->status === 'rejected' ? $request->rejection_reason : null;
        $purchaseRequest->approved_at = $request->status === 'approved' ? now() : null;
        $purchaseRequest->save();

        return redirect()->back()->with('success', 'Status updated successfully.');
    }
}