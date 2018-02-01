<?php

namespace OgilvyBundle\Action\AdminManager;


use OgilvyBundle\CoreAdminController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminManagerChangePasswordAction
{

    /**
     * @param \OgilvyBundle\CoreAdminController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $mangerId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public static function all(
      CoreAdminController $_this,
      Request $request,
      $mangerId
    ) {

        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('admin_login_page');
        }
        $currentUser = $_this->getUser();
        $currentUserId = $currentUser->getId();

        //access denied
        //if (!$_this->isGranted('ROLE_SUPER_ADMIN')) {
        if ($mangerId != $currentUserId) {
            return $_this->_adminError403Action($request);
        }

        $data = [];
        $entity = new AdminManagerChangePasswordEntity();


        $selectedUser = $_this->_getEntityByID(
          'OgilvyBundle:AdminUser',
          $mangerId
        );
        if ($selectedUser) {
            $entity->setEmail($selectedUser->getEmail());
            //$entity->setUsername($selectedUser->getUsername());
        }

        $form = $_this->createForm(
          AdminManagerChangePasswordForm::class,
          $entity
        );

        $form->handleRequest($request);


        $data['admin_manager_change_password_form'] = $form->createView();

        if ($form->isValid()) {
            $selectedUser = $_this->_getEntityByID(
              'OgilvyBundle:AdminUser',
              $mangerId
            );
            if ($selectedUser) {
                $newPassword = $entity->getPassword();
                $email = $entity->getEmail();
                if ($newPassword) {
                    $selectedUser->setPassword(
                      $_this->encodeAdminPassword($email, $newPassword)
                    );
                    $selectedUser = $_this->_saveEntity($selectedUser);
                    $_this->addFlash('success', 'Updated password!');
                } else {
                    $_this->addFlash('info', 'Did nothing!');
                }

                //return $_this->redirectToRoute('admin_manager_page', array());
            }


        }
        $_this->_setPageTitle('Manager - Change password');

        return $_this->render(
          '@admin/admin_manager/admin_manager_change_password_page.html.twig',
          [
            'page_title' => 'Admin - Manager - Change Password',
            'data' => $data,
          ]
        );
    }
}

class AdminManagerChangePasswordForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add(
            'password',
            RepeatedType::class,
            [
              'type' => PasswordType::class,
              'invalid_message' => 'The password fields must match.',
              'options' => ['attr' => []],
              'required' => true,
              'first_options' => ['label' => 'Password'],
              'second_options' => ['label' => 'Repeat Password'],
            ]
          );

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
          [
            'data_class' => 'OgilvyBundle\Action\AdminManager\AdminManagerChangePasswordEntity',
          ]
        );
    }
}

class AdminManagerChangePasswordEntity
{

    private $password;

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }


    private $email;

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }


}