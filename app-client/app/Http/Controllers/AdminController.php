<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Admin Controller
 *
 * Handles administrative functionality and dashboard operations.
 *
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    /**
     * Display the admin dashboard index page.
     *
     * @return \Illuminate\View\View The admin dashboard view
     */
    public function index()
    {
        return view('admin.index');
    }
}
