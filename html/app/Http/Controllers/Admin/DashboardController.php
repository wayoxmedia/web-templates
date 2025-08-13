<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
  /**
   * Show the admin dashboard.
   */
  public function index(): View
  {
    // View reads user/roles directly from session for now.
    // Later, middleware will ensure auth/roles before reaching here.
    return view('admin.pages.dashboard');
  }
}
