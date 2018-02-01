<?php

namespace OgilvyBundle\Controller\Admin;

use OgilvyBundle\Action\AdminAdminMenu\AdminAdminMenuAction;
use OgilvyBundle\Action\AdminAdminMenu\AdminAdminMenuEditAction;
use OgilvyBundle\Action\AdminAdminMenu\AdminAdminMenuLinkEditAction;
use OgilvyBundle\Controller\CoreAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminAdminMenuController extends CoreAdminController
{

    /**
     * @Route("/admin/admin-menu", name="admin_admin_menu_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminAdminMenuAction(
      Request $request
    ) {
        return AdminAdminMenuAction::all(
          $this,
          $request
        );
    }

    /**
     * @Route("/admin/admin-menu/{menuId}/edit", name="admin_admin_menu_edit_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $menuId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminAdminMenuEditAction(
      Request $request,
      $menuId
    ) {
        return AdminAdminMenuEditAction::all(
          $this,
          $request,
          $menuId
        );
    }

    /**
     * @Route("/admin/admin-menu/{menuId}/link/{linkId}/edit", name="admin_admin_menu_link_edit_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $menuId
     * @param $linkId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminAdminMenuLinkEditAction(
      Request $request,
      $menuId,
      $linkId
    ) {
        return AdminAdminMenuLinkEditAction::all(
          $this,
          $request,
          $menuId,
          $linkId
        );
    }

}
