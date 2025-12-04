<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // return a route to the admin dashboard view
        return route('admin.dashboard', absolute: false);
    }
}
