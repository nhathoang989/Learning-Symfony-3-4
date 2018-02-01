<?php

namespace OgilvyBundle\Action\Error;

use OgilvyBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class Error500Action
{

    /**
     * @param \OgilvyBundle\CoreFrontController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $error
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function all(
      CoreFrontController $_this,
      Request $request,
      $error = ''
    ) {
        $_this->_setPageTitle('500');

        $data = [];
        $data['description'] = 'Error 500';

        $response = $_this->render(
          '@front/error/error_500_page.html.twig',
          [
            'data' => $data,
          ]
        );
        $response->setStatusCode(500);

        return $response;
    }
}