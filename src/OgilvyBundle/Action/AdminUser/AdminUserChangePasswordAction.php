<?php

namespace OgilvyBundle\Action\AdminUser;


use OgilvyBundle\CoreAdminController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserChangePasswordAction
{

    /**
     * @param \OgilvyBundle\CoreAdminController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $userId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public static function all(
      CoreAdminController $_this,
      Request $request,
      $userId
    ) {
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('admin_login_page');
        }
        $currentUser = $_this->getUser();
        $currentUserId = $currentUser->getId();

        //access denied
        /*if (!$_this->isGranted('ROLE_SUPER_ADMIN')) {
            return $_this->_adminError403Action($request);
        }*/

        $data = [];
        $entity = new AdminUserChangePasswordEntity();


        $selectedUser = $_this->_getEntityByID(
          'OgilvyBundle:WebsiteUser',
          $userId
        );
        if ($selectedUser) {
            $entity->setEmail($selectedUser->getEmail());
            //$entity->setUsername($selectedUser->getUsername());
        } else {
            return $_this->_adminError404Action($request);
        }

        $form = $_this->createForm(
          AdminUserChangePasswordForm::class,
          $entity
        );

        $form->handleRequest($request);

        // Get user data
        $userData = [
          'email' => $selectedUser->getEmail(),
        ];
        $data['user_data'] = $userData;

        $data['admin_user_change_password_form'] = $form->createView();

        if ($form->isValid()) {
            $selectedUser = $_this->_getEntityByID(
              'OgilvyBundle:WebsiteUser',
              $userId
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

                //return $_this->redirectToRoute('admin_user_page', array());
            }


        }
        $_this->_setPageTitle('User - Change password');

        return $_this->render(
          '@admin/admin_user/admin_user_change_password_page.html.twig',
          [
            'page_title' => 'Admin - User - Change Password',
            'data' => $data,
          ]
        );
    }
}

class AdminUserChangePasswordForm extends AbstractType
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
            'data_class' => 'OgilvyBundle\Action\AdminUser\AdminUserChangePasswordEntity',
          ]
        );
    }
}

class AdminUserChangePasswordEntity
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
