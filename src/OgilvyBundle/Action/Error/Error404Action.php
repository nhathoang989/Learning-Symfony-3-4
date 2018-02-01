<?php

namespace OgilvyBundle\Action\Error;

use OgilvyBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class Error404Action
{

    /**
     * @param \OgilvyBundle\CoreFrontController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function all(CoreFrontController $_this, Request $request)
    {
        $_this->_setPageTitle('Page is not found!');

        $data = [];
        $data['description'] = 'Page is not found';

        $response = $_this->render(
          '@front/error/error_404_page.html.twig',
          [
            'data' => $data,
          ]
        );
        $response->setStatusCode(404);

        return $response;
    }
}