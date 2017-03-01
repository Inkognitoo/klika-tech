<?php

namespace TrackCollectionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function indexAction()
    {
        return $this->render('TrackCollectionBundle:Page:index.html.twig');
    }
}
