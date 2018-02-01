<?php

namespace OgilvyBundle\Action\User;


use OgilvyBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class UserAction
{

    /**
     * @param \OgilvyBundle\CoreFrontController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public static function all(CoreFrontController $_this, Request $request)
    {
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('user_login_page');
        }
        // get User
        $currentUser = $_this->getUser();
        $currentUserId = $currentUser->getId();

        return $_this->redirectToRoute(
          'user_detail_page',
          ['userId' => $currentUserId]
        );
    }
}