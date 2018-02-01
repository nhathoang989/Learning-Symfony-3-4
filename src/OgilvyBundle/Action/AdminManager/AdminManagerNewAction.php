<?php

namespace OgilvyBundle\Action\AdminManager;


use OgilvyBundle\CoreAdminController;
use OgilvyBundle\Entity\AdminUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminManagerNewAction
{

    /**
     * @param \OgilvyBundle\CoreAdminController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public static function all(
      CoreAdminController $_this,
      Request $request
    ) {
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('admin_login_page');
        }

        //access denied
        if (!$_this->isGranted('ROLE_SUPER_ADMIN')) {
            return $_this->_adminError403Action($request);
        }

        $data = [];

        $adminManagerNewEntity = new AdminManagerNewEntity();


        $adminManagerNewForm = $_this->createForm(
          AdminManagerNewForm::class,
          $adminManagerNewEntity
        );

        $adminManagerNewForm->handleRequest($request);


        $data['admin_manager_new_form'] = $adminManagerNewForm->createView();

        if ($adminManagerNewForm->isValid()) {
            $username = $adminManagerNewEntity->getUsername();
            $newPassword = $adminManagerNewEntity->getPassword();

            $selectedUser = $_this->_getEntityByConditions(
              'OgilvyBundle:AdminUser',
              [
                'username' => $username,
              ]
            );
            if ($selectedUser) {
                $_this->addFlash('info', 'Did nothing!');

            } else {
                $newUser = new AdminUser();
                $newUser->setUsername($username);
                $newUser->setEmail($username);
                $newUser->setPassword(
                  $_this->encodeAdminPassword($username, $newPassword)
                );
                $newUser->setStatus(1);
                $newUser->setCreatedAt(time());


                $newUser = $_this->_saveEntity($newUser);
                $_this->addFlash(
                  'success',
                  'Created new user! New Id = '.$newUser->getId()
                );

                return $_this->redirectToRoute('admin_manager_page', []);

            }


        }
        $_this->_setPageTitle('Manager - Add new');

        return $_this->render(
          '@admin/admin_manager/admin_manager_new_page.html.twig',
          [
            'page_title' => 'Admin - Add Manager',
            'data' => $data,
          ]
        );
    }
}

class AdminManagerNewForm extends AbstractType
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
                'readonly' => false,
              ],
            ]
          )
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
            'data_class' => 'OgilvyBundle\Action\AdminManager\AdminManagerNewEntity',
          ]
        );
    }
}

class AdminManagerNewEntity
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
}