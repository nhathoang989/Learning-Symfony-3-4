<?php

namespace OgilvyBundle\Action\Page;

use OgilvyBundle\CoreFrontController;
use Symfony\Component\HttpFoundation\Request;

class IndexAction
{

    /**
     * @param \OgilvyBundle\CoreFrontController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function all(CoreFrontController $_this, Request $request)
    {
        $siteHomePage = $_this->_variableGet('SITE_HOME_PAGE');
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
                if ($_route != 'index_page') {
                    return $_this->forward(
                      $_controller,
                      $arrParams,
                      $request->query->all()
                    );
                }
            } catch (\Exception $e) {
            }
        }

        $_this->_setPageTitle('Home page');
        $_this->_setMetaTags(
          [
            'title' => 'Home page',
            'description' => '...',
          ]
        );

        $data = [];
        $data['description'] = 'ahihi';

        return $_this->render(
          '@front/page/index_page.html.twig',
          [
            'data' => $data,
          ]
        );
    }
}