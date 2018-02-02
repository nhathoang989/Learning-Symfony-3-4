<?php

namespace OgilvyBundle\Action\AdminAccount;


use OgilvyBundle\Controller\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminAccountEditAction
{

    /**
     * @param \OgilvyBundle\CoreAdminController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public static function all(CoreAdminController $_this, Request $request)
    {
        // access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('admin_login_page');
        }
        $data = [];

        return $_this->render(
          '@admin/admin_account/admin_account_edit_page.html.twig',
          [
            'data' => $data,
          ]
        );
    }
}