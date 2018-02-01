<?php

namespace OgilvyBundle\Action\User;

use OgilvyBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class AutoLoginAction
{

    /**
     * @param \OgilvyBundle\CoreFrontController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function all(CoreFrontController $_this, Request $request)
    {
        $data = [];

        return $_this->render(
          '@front/user/auto_login_page.html.twig',
          [
            'data' => $data,
          ]
        );
    }
}