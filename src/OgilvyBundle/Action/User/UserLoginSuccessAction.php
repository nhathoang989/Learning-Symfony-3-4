<?php

namespace OgilvyBundle\Action\User;

use OgilvyBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserLoginSuccessAction
{

    /**
     * @param \OgilvyBundle\CoreFrontController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public static function all(CoreFrontController $_this, Request $request)
    {
        /*$_username = $_this->_getArrayValue($_POST, '_username', '');
        $_password = $_this->_getArrayValue($_POST, '_password', '');
        $loginLog = $_this->_getEntityByConditions('OgilvyBundle:LoginLog', array(
            'username' => $_username
        ));
        if($loginLog) {
            $loginLog->setUpdatedAt(time());
            $loginLog->setFailTotal(0);
            $_this->_saveEntity($loginLog);
        }*/


        $useAjax = $_this->getContainerParameter('ajax_user_login_page');
        if ($useAjax) {
            $returnData = [];
            $returnData['status'] = 1;
            $returnData['message'] = 'Success';

            return new JsonResponse($returnData);
        } else {
            return $_this->redirectToRoute(
              'user_login_page',
              ['type' => 'success']
            );
        }
    }
}