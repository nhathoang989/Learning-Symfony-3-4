<?php

namespace OgilvyBundle\Action\User;


use OgilvyBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class UserDetailAction
{

    /**
     * @param \OgilvyBundle\CoreFrontController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $userId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function all(
      CoreFrontController $_this,
      Request $request,
      $userId
    ) {
        $data = [];

        return $_this->render(
          '@front/user/user_detail_page.html.twig',
          [
            'data' => $data,
          ]
        );
    }
}