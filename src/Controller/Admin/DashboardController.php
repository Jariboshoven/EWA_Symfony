<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class DashboardController
{

    /**
     * @Route("/admin/dashboard")
     */
    public function dashboard()
    {
        return new Response('Admin Page');
    }
}