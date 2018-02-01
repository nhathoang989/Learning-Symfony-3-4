<?php

namespace OgilvyBundle\Action\AdminMenu;


use OgilvyBundle\CoreAdminController;
use OgilvyBundle\Entity\Menu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminMenuEditAction
{

    /**
     * @param \OgilvyBundle\CoreAdminController $_this
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $menuId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public static function all(
      CoreAdminController $_this,
      Request $request,
      $menuId
    ) {
        $_this->_setPageTitle('Admin - Menu Edit');
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('admin_login_page');
        }


        $data = [];
        if ($entity = $_this->_getEntityByID('OgilvyBundle:Menu', $menuId)) {

        } else {
            $entity = new Menu();
            $entity->setCreatedAt(time());
            $entity->setStatus(1);
            $entity->setWeight(0);
        }

        $form = $_this->createForm(
          AdminMenuEditForm::class,
          $entity
        );

        $form->handleRequest($request);


        if ($form->isValid()) {

            $entity = $_this->_saveEntity($entity);

            //$adminSettingForm->get('email')->addError(new FormError('Email is exist!'));
            $_this->addFlash('success', 'Updated! Id = '.$entity->getId());

            return $_this->redirectToRoute('admin_menu_page');
        }
        $data['admin_menu_edit_form'] = $form->createView();

        return $_this->render(
          '@admin/admin_menu/admin_menu_edit_page.html.twig',
          [
            'data' => $data,
          ]
        );
    }
}


class AdminMenuEditForm extends AbstractType
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('name', TextType::class, ['required' => true])
          ->add('code', TextType::class, ['required' => true])
          ->add('weight', IntegerType::class, ['required' => true])
          ->add(
            'status',
            ChoiceType::class,
            [
              'choices' => [
                1 => 'Publish',
                0 => 'Unpublish',
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
            'data_class' => 'OgilvyBundle\Entity\Menu',
          ]
        );
    }
}