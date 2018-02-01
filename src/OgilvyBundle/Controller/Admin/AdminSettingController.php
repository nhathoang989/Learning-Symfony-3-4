<?php

namespace OgilvyBundle\Controller\Admin;

use OgilvyBundle\Action\AdminSetting\AdminSettingAction;
use OgilvyBundle\Controller\CoreAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminSettingController extends CoreAdminController
{

    /**
     * @Route("/admin/setting", name="admin_setting_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminSettingAction(Request $request)
    {
        return AdminSettingAction::all(
          $this,
          $request
        );
    }

    /**
     * @Route("/admin/setting/{settingId}/edit", name="admin_setting_edit_page")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $settingId
     *
     * @return
     */
    public function adminSettingEditAction(Request $request, $settingId)
    {
        return AdminSettingEditAction::all(
          $this,
          $request,
          $settingId
        );
    }

}
