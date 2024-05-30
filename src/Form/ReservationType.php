<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Conference;
use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content',TextType::class,['attr'=>['placeholder'=>"exemple: j'aimerai faire une réservation pour cette conférence"]])
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('conference', EntityType::class, [
            //     'class' => Conference::class,
            //     'choice_label' => 'id',
            // ])
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
            ->add('conference', HiddenType::class)
            ->add('user', HiddenType::class)
            ->add('reserver', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
