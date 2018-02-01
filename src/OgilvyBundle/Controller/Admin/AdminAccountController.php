<?php

namespace OgilvyBundle\Controller\Admin;

use OgilvyBundle\Action\AdminAccount\AdminAccountAction;
use OgilvyBundle\Action\AdminAccount\AdminAccountDetailAction;
use OgilvyBundle\Action\AdminAccount\AdminAccountEditAction;
use OgilvyBundle\Controller\CoreAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminAccountController extends CoreAdminController
{

    /**
     * @Route("/admin/account", name="admin_account_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminAccountAction(
      Request $request
    ) {
        return AdminAccountAction::all(
          $this,
          $request
        );
    }

    /**
     * @Route("/admin/account/{accountId}", name="admin_account_defail_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $accountId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminAccountDetailAction(
      Request $request,
      $accountId
    ) {
        return AdminAccountDetailAction::all(
          $this,
          $request,
          $accountId
        );
    }

    /**
     * @Route("/admin/account/{accountId}/edit", name="admin_account_edit_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $accountId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminAccountEditAction(
      Request $request,
      $accountId
    ) {
        return AdminAccountEditAction::all(
          $this,
          $request,
          $accountId
        );
    }

}
