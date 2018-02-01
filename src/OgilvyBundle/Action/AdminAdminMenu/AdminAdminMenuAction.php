<?php

namespace OgilvyBundle\Action\AdminAdminMenu;


use OgilvyBundle\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminAdminMenuAction
{

    /**
     * @param \OgilvyBundle\CoreAdminController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public static function all(CoreAdminController $_this, Request $request)
    {
        $_this->_setPageTitle('Admin - Admin Menu');
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('admin_login_page');
        }

        $perPage = 10;
        $perPage = intval($request->query->get('length', $perPage));
        $currentPage = intval($request->query->get('page', 1));
        $dql = 'SELECT u.id, u.code, u.name, u.status
        FROM OgilvyBundle:AdminMenu u
         ORDER BY u.weight ASC';
        $result = $_this->_executePagerDQL(
          $dql,
          [],
          $perPage,
          $currentPage
        );


        $dql = 'SELECT COUNT(u.id) AS total
        FROM OgilvyBundle:AdminMenu u';
        $result2 = $_this->_executeDQL(
          $dql,
          []
        );

        $arr = $result;
        $total = $result2[0]['total'];
        $items = [];
        foreach ($arr as $key => $value) {
            $tmp = [];

            $tmp['id'] = $value['id'];
            $tmp['name'] = $value['name'];
            $tmp['code'] = $value['code'];
            $tmp['status'] = $value['status'];
            $action_links = [];
            $action_links[] = [
              'title' => 'Edit',
              'link' => $_this->generateUrl(
                'admin_admin_menu_edit_page',
                ['menuId' => $value['id']]
              ),
            ];
            $action_links[] = [
              'title' => 'Add New Link',
              'link' => $_this->generateUrl(
                'admin_admin_menu_link_edit_page',
                ['menuId' => $value['id'], 'linkId' => 0]
              ),
            ];

            $dql02 = "SELECT ml.id, ml.title, ml.path, ml.friendlyPath, ml.type
            FROM OgilvyBundle:AdminMenuLink ml
            WHERE ml.menuId = :menuId AND ml.parentLinkId = 0
            ORDER BY ml.weight ASC, ml.id ASC";
            $arr02 = $_this->_executeDQL(
              $dql02,
              [
                'menuId' => $value['id'],
              ]
            );
            $menuList = [];
            foreach ($arr02 as $value02) {
                $tmp02 = [];
                $tmp02['title'] = $value02['title'];
                $tmp02['link'] = $_this->_getSymfonyUrl($value02['path']);
                $tmp02['edit_link'] = $_this->generateUrl(
                  'admin_admin_menu_link_edit_page',
                  [
                    'menuId' => $value['id'],
                    'linkId' => $value02['id'],
                  ]
                );
                $dql03 = "SELECT ml.id, ml.title, ml.path, ml.friendlyPath, ml.type
                FROM OgilvyBundle:AdminMenuLink ml
                WHERE ml.menuId = :menuId AND ml.parentLinkId = :parentLinkId
                ORDER BY ml.weight ASC, ml.id ASC";
                $arr03 = $_this->_executeDQL(
                  $dql03,
                  [
                    'menuId' => $value['id'],
                    'parentLinkId' => $value02['id'],
                  ]
                );

                $subMenuList = [];
                foreach ($arr03 as $value03) {
                    $tmp03 = [];
                    $tmp03['title'] = $value03['title'];
                    $tmp03['link'] = $_this->_getSymfonyUrl($value03['path']);
                    $tmp03['edit_link'] = $_this->generateUrl(
                      'admin_admin_menu_link_edit_page',
                      [
                        'menuId' => $value['id'],
                        'linkId' => $value03['id'],
                      ]
                    );
                }
                $tmp02['sub_menu_list'] = $subMenuList;
                $menuList[] = $tmp02;
            }

            $tmp['menu_list'] = $menuList;
            $tmp['action_link_list'] = $action_links;
            $items[] = $tmp;

        }

        $queries = [];
        $pager = $_this->_getPager(
          $currentPage,
          ceil($total / $perPage),
          'admin_menu_page',
          $queries
        );

        $data = [
          'currentPage' => $currentPage,
          'totalPages' => ceil($total / $perPage),
          'pager' => $pager,
          'items' => $items,
        ];

        return $_this->render(
          '@admin/admin_admin_menu/admin_admin_menu_page.html.twig',
          [
            'data' => $data,
          ]
        );
    }
}