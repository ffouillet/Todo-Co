<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class TaskEditType extends TaskType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('author',TextType::class,
                array(
                    'data' => $options['data']->getAuthor() != null ? $options['data']->getAuthor() : 'Utilisateur anonyme',
                    'label' => 'Auteur',
                    'disabled' => true,
                ));
    }

}
