<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Thread;
use App\Entity\Technology;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ThreadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'attr' => array(
                    'placeholder' => 'Title of your thread'
                )
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Content',
                'attr' => array(
                    'placeholder' => 'Your request here'
                )
            ])
            ->add('technology', EntityType::class, [
                'class' => Technology::class,
                'choice_label' => 'name',
                'mapped' => false
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
            ])
            ->add('postdate', DateType::class, [
                'label' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'data' => new \DateTime("now"),
                'attr' => [
                    'class' => 'invisible'
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Active' => 'Active',
                    'Closed' => 'Closed',
                    'Modarate' => 'Modarate'
                ],
                'data' => 'Active',
                'attr' => [
                    'class' => 'invisible'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Thread::class,
        ]);
    }
}
