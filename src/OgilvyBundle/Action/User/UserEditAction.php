<?php

namespace OgilvyBundle\Action\User;


use OgilvyBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class UserEditAction
{

    /**
     * @param \OgilvyBundle\CoreFrontController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $userId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public static function all(
      CoreFrontController $_this,
      Request $request,
      $userId
    ) {
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('user_login_page');
        }
        // get User
        $currentUser = $_this->getUser();
        $currentUserId = $currentUser->getId();
        // if current Id is not Edited Id
        if ($currentUserId != $userId) {
            return $_this->_error403Action($request);
        }

        $data = [];

        return $_this->render(
          '@front/user/user_edit_page.html.twig',
          [
            'data' => $data,
          ]
        );
    }
}
