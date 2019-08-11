<?php

namespace NI\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('NIPlatformBundle:Default:index.html.twig');
    }
}
