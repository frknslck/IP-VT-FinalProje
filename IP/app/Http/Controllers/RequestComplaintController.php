<?php

namespace App\Http\Controllers;

use App\Models\RequestComplaint;
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
        $request->validate([
            'RorC' => 'required|in:Request,Complaint',
            'subject' => 'required|string|max:255',
            'category' => 'required|in:Category,Brand,Product,User,Review,Order,Campaign,Other',
            'message' => 'required|string',
        ]);

        RequestComplaint::create([
            'user_id' => Auth::id(),
            'RorC' => $request->RorC,
            'subject' => $request->subject,
            'category' => $request->category,
            'message' => $request->message,
            'status' => 'Pending',
        ]);

        if($request->RorC == 'Request'){
            return redirect()->route('requests-complaints.index')->with('success', 'Your request has been submitted.');
        }else{
            return redirect()->route('requests-complaints.index')->with('success', 'Your complaint has been submitted.');
        }
    }

    public function review($id)
    {
        $complaint = RequestComplaint::findOrFail($id);
        $typefortoast = $complaint->RorC;
        $complaint->status = 'Reviewed';
        $complaint->save();

        if($typefortoast == 'Request'){
            return back()->with('success', 'Request marked as reviewed.');
        }else{
            return back()->with('success', 'Complaint marked as reviewed.');
        }
    }

    public function resolve($id)
    {
        $complaint = RequestComplaint::findOrFail($id);
        $typefortoast = $complaint->RorC;
        $complaint->status = 'Resolved';
        $complaint->save();

        if($typefortoast == 'Request'){
            return back()->with('success', 'Request resolved.');
        }else{
            return back()->with('success', 'Complaint resolved.');
        }
    }
}
