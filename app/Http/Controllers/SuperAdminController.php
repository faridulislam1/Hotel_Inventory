<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Super Admin Dashboard',
        ]);
    }

    
}
