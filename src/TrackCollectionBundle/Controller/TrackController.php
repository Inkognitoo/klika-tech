<?php

namespace TrackCollectionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class TrackController extends Controller
{
    public function getAction()
    {
        return new JsonResponse(array('name' => 'Test'));
    }

}
