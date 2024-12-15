<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $roles = $user->roles()->with('actions')->get();

        return view('action-panel.index', compact('roles'));
    }
}
