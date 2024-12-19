<?php

namespace App\Http\Controllers;

use App\Models\RequestComplaint;
use App\Models\ActionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestComplaintController extends Controller
{
    public function index()
    {
        $complaints = RequestComplaint::where('user_id', Auth::id())->get();
        return view('requests-complaints.index', compact('complaints'));
    }

    public function create()
    {
        return view('requests-complaints.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'RorC' => 'required|in:Request,Complaint',
            'subject' => 'required|string|max:255',
            'category' => 'required|in:Category,Brand,Product,User,Review,Order,Campaign,Other',
            'message' => 'required|string',
        ]);

        $complaint = RequestComplaint::create([
            'user_id' => Auth::id(),
            'RorC' => $validatedData['RorC'],
            'subject' => $validatedData['subject'],
            'category' => $validatedData['category'],
            'message' => $validatedData['message'],
            'status' => 'Pending',
        ]);

        ActionLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'target' => 'request_complaint',
            'status' => 'success',
            'ip_address' => request()->ip(),
            'details' => $validatedData['RorC'].' submitted. ID: ' . $complaint->id,
        ]);

        if ($validatedData['RorC'] == 'Request') {
            return redirect()->route('requests-complaints.index')->with('success', 'Your request has been submitted.');
        } else {
            return redirect()->route('requests-complaints.index')->with('success', 'Your complaint has been submitted.');
        }
    }

    public function review($id)
    {
        $complaint = RequestComplaint::findOrFail($id);
        $typefortoast = $complaint->RorC;
        $complaint->status = 'Reviewed';
        $complaint->save();

        ActionLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'target' => 'request_complaint',
            'status' => 'success',
            'ip_address' => request()->ip(),
            'details' => $typefortoast.' marked as reviewed. ID: ' . $complaint->id,
        ]);

        if ($typefortoast == 'Request') {
            return back()->with('success', 'Request marked as reviewed.');
        } else {
            return back()->with('success', 'Complaint marked as reviewed.');
        }
    }

    public function resolve($id)
    {
        $complaint = RequestComplaint::findOrFail($id);
        $typefortoast = $complaint->RorC;
        $complaint->status = 'Resolved';
        $complaint->save();

        ActionLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'target' => 'request_complaint',
            'status' => 'success',
            'ip_address' => request()->ip(),
            'details' => $typefortoast.' resolved. ID: ' . $complaint->id,
        ]);

        if ($typefortoast == 'Request') {
            return back()->with('success', 'Request resolved.');
        } else {
            return back()->with('success', 'Complaint resolved.');
        }
    }
}
