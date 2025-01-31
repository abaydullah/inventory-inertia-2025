<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request, Tenant $tenant)
    {
        $users = \App\Models\User::all();
        return Inertia::render('Dashboard', ['users' => $users]);
    }
}
