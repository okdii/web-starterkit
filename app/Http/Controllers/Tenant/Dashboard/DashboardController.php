<?php

namespace App\Http\Controllers\Tenant\Dashboard;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index() : Response 
    {
        return Inertia::render('Tenant/Dashboard/Dashboard');
    }
}