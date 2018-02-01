<?php

namespace OgilvyBundle\Action\User;


use OgilvyBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class UserActiveDetailAction
{

    /**
     * @param \OgilvyBundle\CoreFrontController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $active_code
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public static function all(
      CoreFrontController $_this,
      Request $request,
      $active_code
    ) {
        $currentUser = $_this->getUser();
        if ($currentUser) {
            return $_this->redirectToRoute('user_page');
        }

        $data = [];

        return $_this->render(
          '@front/user/user_active_detail_page.html.twig',
          [
            'data' => $data,
          ]
        );
    }
}