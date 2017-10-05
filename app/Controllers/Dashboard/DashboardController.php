<?php

namespace App\Controllers\Dashboard;

use App\Controllers\Controller;

class DashboardController extends Controller
{
    public function index($request, $response, $args)
    {
        $user = $this->auth->user();

        return $this->view->render($response, 'dashboard/index.twig', compact('user', 'orders'));
    }
}

