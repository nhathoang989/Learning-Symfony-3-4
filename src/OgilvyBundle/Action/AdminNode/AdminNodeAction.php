<?php

namespace OgilvyBundle\Action\AdminNode;


use OgilvyBundle\Controller\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminNodeAction
{

    /**
     * @param \OgilvyBundle\CoreAdminController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public static function all(CoreAdminController $_this, Request $request)
    {
        $_this->_setPageTitle('Admin - Content');
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('admin_login_page');
        }

        $perPage = 10;
        $perPage = intval($request->query->get('length', $perPage));
        $currentPage = intval($request->query->get('page', 1));
        $dql = 'SELECT n.id, n.title, n.status
        FROM OgilvyBundle:Node n
        ORDER BY n.updatedAt DESC';
        $result = $_this->_executePagerDQL(
          $dql,
          [],
          $perPage,
          $currentPage
        );


        $dql = 'SELECT COUNT(n.id) AS total
        FROM OgilvyBundle:Node n';
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
            $tmp['detail_link'] = '';//$_this->generateUrl()$value['id'];
            $tmp['title'] = $value['title'];
            $tmp['status'] = $value['status'];
            $action_links = [];
            $action_links[] = [
              'title' => 'Edit',
              'link' => $_this->generateUrl(
                'admin_node_edit_page',
                ['nodeId' => $value['id']]
              ),
            ];
            $tmp['action_link_list'] = $action_links;
            $items[] = $tmp;

        }

        $queries = [];
        $pager = $_this->_getPager(
          $currentPage,
          ceil($total / $perPage),
          'admin_node_page',
          $queries
        );

        $data = [
          'currentPage' => $currentPage,
          'totalPages' => ceil($total / $perPage),
          'pager' => $pager,
          'items' => $items,
        ];

        return $_this->render(
          '@admin/admin_node/admin_node_page.html.twig',
          [
            'data' => $data,
          ]
        );
    }
}