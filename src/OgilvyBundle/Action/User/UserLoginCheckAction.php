<?php

namespace OgilvyBundle\Action\User;

use OgilvyBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserLoginCheckAction
{

    /**
     * @param \OgilvyBundle\CoreFrontController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public static function all(CoreFrontController $_this, Request $request)
    {
        $data = [];

        return new JsonResponse(['status' => 1, 'error' => '']);
    }
}