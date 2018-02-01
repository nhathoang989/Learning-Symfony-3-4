<?php

namespace OgilvyBundle\Action\AdminUser;


use OgilvyBundle\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminUserAction
{

    /**
     * @param \OgilvyBundle\CoreAdminController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public static function all(CoreAdminController $_this, Request $request)
    {
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('admin_login_page');
        }

        $reservation = $request->query->get('reservation', '');
        $email = $request->query->get('email', '');

        $reservation_data = $_this->_getReservationTime(
          $reservation,
          time() - 365 * 24 * 60 * 60,
          time() + 1 * 24 * 60 * 60
        );

        $perPage = 10;
        $perPage = intval($request->query->get('length', $perPage));
        $currentPage = intval($request->query->get('page', 1));
        $dql = 'SELECT u.id, u.email, u.createdAt, u.status
        FROM OgilvyBundle:WebsiteUser u
        WHERE u.createdAt > :from
        AND u.createdAt < :to
        AND u.email LIKE :email
        ORDER BY u.createdAt DESC';
        $result = $_this->_executePagerDQL(
          $dql,
          [
            'from' => $reservation_data->from,
            'to' => $reservation_data->to,
            'email' => '%'.$email.'%',
          ],
          $perPage,
          $currentPage
        );


        $dql = 'SELECT COUNT(u.id) AS total
        FROM OgilvyBundle:WebsiteUser u
        WHERE u.createdAt > :from
        AND u.createdAt < :to
        AND u.email LIKE :email';
        $result2 = $_this->_executeDQL(
          $dql,
          [
            'from' => $reservation_data->from,
            'to' => $reservation_data->to,
            'email' => '%'.$email.'%',
          ]
        );

        $arr = $result;
        $total = $result2[0]['total'];
        $items = [];
        foreach ($arr as $key => $value) {
            $tmp = [];

            $tmp['id'] = $value['id'];
            $tmp['email'] = $value['email'];
            $tmp['createdAt'] = date('Y-m-d H:i:s', $value['createdAt']);
            $tmp['status'] = $value['status'];
            $items[] = $tmp;

        }

        $queries = [];
        $queries['reservation'] = $reservation;
        $queries['email'] = $email;
        $pager = $_this->_getPager(
          $currentPage,
          ceil($total / $perPage),
          'admin_user_page',
          $queries
        );

        $data = [
          'reservation' => date('m/d/Y', $reservation_data->from).' - '.date(
              'm/d/Y',
              $reservation_data->to
            ),
          'currentPage' => $currentPage,
          'totalPages' => ceil($total / $perPage),
          'pager' => $pager,
          'items' => $items,
        ];

        $_this->_setPageTitle('Users');

        return $_this->render(
          '@admin/admin_user/admin_user_page.html.twig',
          [
            'data' => $data,
          ]
        );
    }
}