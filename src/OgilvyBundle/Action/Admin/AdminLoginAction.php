<?php

namespace OgilvyBundle\Action\Admin;


use OgilvyBundle\Controller\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminLoginAction
{

    /**
     * @param \OgilvyBundle\Controller\CoreAdminController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public static function all(CoreAdminController $_this, Request $request, $currentUser = null)
    {

       // var_dump($_this);die;
        /*$currentUser = $_this->getUser();
        if ($currentUser!='anon.') {
            return $_this->redirectToRoute('admin_page');
        }*/

        //$authenticationUtils = $_this->get('security.authentication_utils');
        //// get the login error if there is one
        //$error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        //$lastUsername = $authenticationUtils->getLastUsername();

        return $_this->render(
          '@admin/admin/admin_login_page.html.twig',
          [
            'page_title' => 'Login',
            'last_username' => '',//$lastUsername,
            'error' => '',//$error,
          ]
        );


    }
}

