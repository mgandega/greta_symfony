<?php

namespace App\Form;

use App\Form\ImageType;
use App\Entity\Categorie;
use App\Entity\Competence;
use App\Entity\Conference;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConferenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('lieu')
            // ->add('date', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('ajout', SubmitType::class)
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'multiple' => false,
                'expanded' => true
            ])
        
            ->add('competence', EntityType::class, [
                'class' => Competence::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('image',ImageType::class,['validation_groups'=>$options['validation_groups']])
            ->add('save', SubmitType::class,['label' => $options['button_label']])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conference::class,
            'button_label'=>'Submit',
            'validation_groups'=>['Default']
        ]);
    }
}
