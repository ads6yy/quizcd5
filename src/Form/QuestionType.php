<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Quiz;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', TextType::class, [
                'label' => 'Type'
            ])
            ->add('question', TextType::class, [
                'label' => 'Question'
            ])
            ->add('answer', TextType::class, [
                'mapped' => false,
                'label' => 'Réponse'
            ])
            ->add('quiz', EntityType::class, [
                'mapped' => false,
                'class' => Quiz::class,
                'choice_label' => 'title'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
