<?php

namespace OgilvyBundle\Action\User;


use OgilvyBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class UserActiveAction
{

    /**
     * @param \OgilvyBundle\CoreFrontController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public static function all(CoreFrontController $_this, Request $request)
    {
        $currentUser = $_this->getUser();
        if ($currentUser) {
            return $_this->redirectToRoute('user_page');
        }

        $data = [];

        return $_this->render(
          '@front/user/user_active_page.html.twig',
          [
            'data' => $data,
          ]
        );
    }
}