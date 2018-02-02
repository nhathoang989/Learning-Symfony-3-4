<?php

namespace OgilvyBundle\Action\Admin;


use OgilvyBundle\Controller\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminAction
{

    /**
     * @param \OgilvyBundle\Controller\CoreAdminController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public static function all(CoreAdminController $_this, Request $request)
    {

        $siteHomePage = $_this->_variableGet('SITE_ADMIN_PAGE');
        if ($siteHomePage) {
            try {
                $router = $_this->get('router');
                $arr = $router->match($siteHomePage);

                $_route = $arr['_route'];
                $_controller = $arr['_controller'];

                $arrParams = [];
                foreach ($arr as $key => $value) {
                    if ($key != '_controller' && $key != '_route') {
                        $arrParams[$key] = $value;
                    }
                }
                if ($_route != 'admin_page') {
                    return $_this->forward(
                      $_controller,
                      $arrParams,
                      $request->query->all()
                    );
                }
            } catch (\Exception $e) {
            }
        }

/*
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('admin_login_page');
        }
*/
        $_this->_setPageTitle('Admin - Dashboard');

        $data = [];

        return $_this->render(
          '@admin/admin/admin_page.html.twig',
          [
            'data' => $data,
          ]
        );
    }
}