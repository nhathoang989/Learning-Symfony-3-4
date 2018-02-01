<?php

namespace OgilvyBundle\Action\AdminSetting;


use OgilvyBundle\CoreAdminController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminSettingAction
{

    /**
     * @param \OgilvyBundle\CoreAdminController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public static function all(CoreAdminController $_this, Request $request)
    {
        $_this->_setPageTitle('Settings');
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('admin_login_page');
        }

        //access denied
        if (!$_this->isGranted('ROLE_SUPER_ADMIN')) {
            return $_this->_adminError403Action($request);
        }

        $data = [];

        $adminSettingEntity = new AdminSettingEntity();
        $adminSettingEntity->setSiteTitle($_this->_variableGet('SITE_TITLE'));
        $adminSettingEntity->setSiteMetaDescription(
          $_this->_variableGet('SITE_META_DESCRIPTION')
        );
        $adminSettingEntity->setSiteHomePage(
          $_this->_variableGet('SITE_HOME_PAGE')
        );
        $adminSettingEntity->setSiteAdminPage(
          $_this->_variableGet('SITE_ADMIN_PAGE')
        );
        //        $adminSettingEntity->setEmailSentFrom(
        //          $_this->_variableGet('EMAIL_SENT_FROM')
        //        );
        //        $adminSettingEntity->setSentFrom($_this->_variableGet('SENT_FROM'));
        //        $adminSettingEntity->setPeertalProfileId(
        //          $_this->_variableGet('PEERTAL_PROFILE_ID')
        //        );
        //
        //        $adminSettingEntity->setFullContactApiKey(
        //          $_this->_variableGet('FULL_CONTACT_API_KEY')
        //        );
        //
        //        $adminSettingEntity->setSendEmailWhenCommenting(
        //          (bool) $_this->_variableGet('SEND_EMAIL_WHEN_COMMENTING')
        //        );

        $adminSettingForm = $_this->createForm(
          AdminSettingForm::class,
          $adminSettingEntity
        );

        $adminSettingForm->handleRequest($request);


        if ($adminSettingForm->isValid()) {

            $siteTitle = $adminSettingEntity->getSiteTitle();
            $siteMetaDescription = $adminSettingEntity->getSiteMetaDescription(
            );
            $siteHomePage = $adminSettingEntity->getSiteHomePage();
            $siteAdminPage = $adminSettingEntity->getSiteAdminPage();

            $_this->_variableSet('SITE_TITLE', $siteTitle);
            $_this->_variableSet('SITE_META_DESCRIPTION', $siteMetaDescription);
            $_this->_variableSet('SITE_HOME_PAGE', $siteHomePage);
            $_this->_variableSet('SITE_ADMIN_PAGE', $siteAdminPage);

            //$adminSettingForm->get('email')->addError(new FormError('Email is exist!'));
            $_this->addFlash('success', 'Updated!');
        }
        $data['admin_setting_form'] = $adminSettingForm->createView();

        return $_this->render(
          '@admin/admin_setting/admin_setting_page.html.twig',
          [
            'data' => $data,
          ]
        );
    }
}

class AdminSettingForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('siteTitle', TextType::class, ['required' => true])
          ->add('siteMetaDescription', TextType::class, ['required' => false])
          ->add('siteHomePage', TextType::class, ['required' => false])
          ->add('siteAdminPage', TextType::class, ['required' => false]);

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
          [
            'data_class' => 'OgilvyBundle\Action\AdminSetting\AdminSettingEntity',
          ]
        );
    }
}

class AdminSettingEntity
{

    private $siteTitle;

    public function setSiteTitle($siteTitle)
    {
        $this->siteTitle = $siteTitle;

        return $this;
    }

    public function getSiteTitle()
    {
        return $this->siteTitle;
    }

    private $siteMetaDescription;

    public function setSiteMetaDescription($siteMetaDescription)
    {
        $this->siteMetaDescription = $siteMetaDescription;

        return $this;
    }

    public function getSiteMetaDescription()
    {
        return $this->siteMetaDescription;
    }

    private $siteHomePage;

    public function setSiteHomePage($siteHomePage)
    {
        $this->siteHomePage = $siteHomePage;

        return $this;
    }

    public function getSiteHomePage()
    {
        return $this->siteHomePage;
    }

    private $siteAdminPage;

    public function setSiteAdminPage($siteAdminPage)
    {
        $this->siteAdminPage = $siteAdminPage;

        return $this;
    }

    public function getSiteAdminPage()
    {
        return $this->siteAdminPage;
    }
    //
    //    private $sentFrom;
    //
    //    public function setSentFrom($sentFrom)
    //    {
    //        $this->sentFrom = $sentFrom;
    //
    //        return $this;
    //    }
    //
    //    public function getSentFrom()
    //    {
    //        return $this->sentFrom;
    //    }
    //
    //
    //    private $peertalProfileId;
    //
    //    public function setPeertalProfileId($peertalProfileId)
    //    {
    //        $this->peertalProfileId = $peertalProfileId;
    //
    //        return $this;
    //    }
    //
    //    public function getPeertalProfileId()
    //    {
    //        return $this->peertalProfileId;
    //    }
    //
    //    private $fullContactApiKey;
    //
    //    public function setFullContactApiKey($fullContactApiKey)
    //    {
    //        $this->fullContactApiKey = $fullContactApiKey;
    //
    //        return $this;
    //    }
    //
    //    public function getFullContactApiKey()
    //    {
    //        return $this->fullContactApiKey;
    //    }
    //
    //    private $sendEmailWhenCommenting;
    //
    //    public function setSendEmailWhenCommenting($sendEmailWhenCommenting)
    //    {
    //        $this->sendEmailWhenCommenting = $sendEmailWhenCommenting;
    //
    //        return $this;
    //    }
    //
    //    public function getSendEmailWhenCommenting()
    //    {
    //        return $this->sendEmailWhenCommenting;
    //    }

}


