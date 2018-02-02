<?php

namespace OgilvyBundle\Action\AdminUser;


use OgilvyBundle\Controller\CoreAdminController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserEditAction
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

        $data = [];
        $entity = new AdminUserEditEntity();


        $selectedUser = $_this->_getEntityByID(
          'OgilvyBundle:WebsiteUser',
          $userId
        );
        if ($selectedUser) {
            //$entity->setEmail($selectedUser->getEmail());
            $entity->setUsername($selectedUser->getUsername());
            $entity->setStatus($selectedUser->getStatus());
        }

        $form = $_this->createForm(
          new AdminUserEditForm(),
          $entity
        );

        $form->handleRequest($request);


        $data['admin_user_edit_form'] = $form->createView();

        if ($form->isValid()) {
            $selectedUser = $_this->_getEntityByID(
              'OgilvyBundle:WebsiteUser',
              $userId
            );
            if ($selectedUser) {
                //                $newPassword = $entity->getPassword();
                //                $email = $entity->getEmail();
                $status = $entity->getStatus();

                //                if ($newPassword) {
                //                    $selectedUser->setPassword($_this->encodeAdminPassword($email, $newPassword));
                //                    $_this->addFlash('success', 'Updated password! User Id = '.$selectedUser->getId());
                //                } else {
                //                    $_this->addFlash('info', 'No update password!');
                //                }

                $selectedUser->setStatus($status);
                $selectedUser = $_this->_saveEntity($selectedUser);

                return $_this->redirectToRoute('admin_manager_page', []);
            }
        }

        $_this->_setPageTitle('User - Edit');

        return $_this->render(
          '@admin/admin_user/admin_user_edit_page.html.twig',
          [
            'page_title' => 'Admin - User - Edit',
            'data' => $data,
          ]
        );
    }
}

class AdminUserEditForm extends AbstractType
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add(
            'username',
            TextType::class,
            [
              'required' => true,
              'attr' => [
                'readonly' => true,
              ],
            ]
          )
          /*->add(
              'email',
              EmailType::class,
              array(
                  'required' => true,
                  'attr' => array(
                      'readonly' => true,
                  ),
              )
          )*/
          //            ->add(
          //                'password',
          //                RepeatedType::class,
          //                array(
          //                    'type' => PasswordType::class,
          //                    'invalid_message' => 'The password fields must match.',
          //                    'options' => array('attr' => array()),
          //                    'required' => false,
          //                    'first_options' => array('label' => 'Password'),
          //                    'second_options' => array('label' => 'Repeat Password'),
          //                )
          //            )
          ->add(
            'status',
            ChoiceType::class,
            [
              'choices' => [
                '1' => 'Publish',
                '0' => 'Unpublish',
              ],
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
            'data_class' => 'OgilvyBundle\Action\AdminUser\AdminUserEditEntity',
          ]
        );
    }
}

class AdminUserEditEntity
{

    private $username;

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    //    private $password;
    //
    //    public function setPassword($password)
    //    {
    //        $this->password = $password;
    //
    //        return $this;
    //    }

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

    private $status;

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

}