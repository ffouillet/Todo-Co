<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

/**
 * @author Echarbeto
 */
class RolesType extends AbstractType {

    private $roles = [];

    public function __construct(RoleHierarchyInterface $roleHierarchy) {

        $roles = array();

        array_walk_recursive($roleHierarchy, function($val) use (&$roles) {
            $roles[$val] = $val;
        });

        ksort($roles);

        $this->roles = array_unique($roles);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'choices' => $this->roles,
            'choice_label' => function($choiceValue) {
                switch($choiceValue) {
                    case 'ROLE_ADMIN' :
                        return 'Administrateur';
                    case 'ROLE_USER' :
                        return 'Utilisateur';
                }
            },
            'attr' => array(
                'class' => 'form-control',
                'aria-hidden' => 'true',
                'ref' => 'input',
                'multiple' => '',
                'tabindex' => '-1'
            ),
            'required' => true,
            'multiple' => true,
            'empty_data' => null,
            'label_attr' => array(
                'class' => 'control-label'
            )
        ));
    }

    public function getParent() {
        return ChoiceType::class;
    }

}
