<?php

namespace OgilvyBundle\Controller\Admin;

use OgilvyBundle\Action\AdminMenu\AdminMenuAction;
use OgilvyBundle\Action\AdminMenu\AdminMenuDetailAction;
use OgilvyBundle\Action\AdminMenu\AdminMenuEditAction;
use OgilvyBundle\Action\AdminMenu\AdminMenuLinkAction;
use OgilvyBundle\Action\AdminMenu\AdminMenuLinkEditAction;
use OgilvyBundle\Controller\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminMenuController extends CoreAdminController
{

    /**
     * @Route("/admin/menu", name="admin_menu_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminMenuAction(Request $request)
    {
        return AdminMenuAction::all($this, $request);
    }

    /**
     * @Route("/admin/menu/{menuId}", name="admin_menu_detail_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $menuId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminMenuDetailAction(Request $request, $menuId)
    {
        return AdminMenuDetailAction::all($this, $request, $menuId);
    }

    /**
     * @Route("/admin/menu/{menuId}/edit", name="admin_menu_edit_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $menuId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminMenuEditAction(Request $request, $menuId)
    {
        return AdminMenuEditAction::all($this, $request, $menuId);
    }

    /**
     * @Route("/admin/menu/{menuId}/link", name="admin_menu_link_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $menuId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminMenuLinkAction(Request $request, $menuId)
    {
        return AdminMenuLinkAction::all($this, $request, $menuId);
    }

    /**
     * @Route("/admin/menu/{menuId}/link/{linkId}/edit", name="admin_menu_link_edit_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $menuId
     * @param $linkId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminMenuLinkEditAction(Request $request, $menuId, $linkId)
    {
        return AdminMenuLinkEditAction::all($this, $request, $menuId, $linkId);
    }

}
