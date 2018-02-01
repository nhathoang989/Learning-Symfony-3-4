<?php

namespace OgilvyBundle\Action\Error;

use OgilvyBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class Error403Action
{

    /**
     * @param \OgilvyBundle\CoreFrontController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function all(CoreFrontController $_this, Request $request)
    {
        $_this->_setPageTitle('Access Denied!');

        $data = [];
        $data['description'] = 'Access Denied';

        $response = $_this->render(
          '@front/error/error_403_page.html.twig',
          [
            'data' => $data,
          ]
        );
        $response->setStatusCode(403);

        return $response;
    }
}