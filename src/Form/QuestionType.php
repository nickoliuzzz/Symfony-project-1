<?php

namespace App\Form;

use App\Entity\Answer;
use App\Entity\Question;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextType::class,array(
                'label' => 'Question: '))
            ->add('answers', CollectionType::class, array (
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => AnswerType::class,
                'entry_options' =>  array('label' => false),
                'by_reference' => false,
            ))
//            ->add('answers', EntityType::class, array(
//            // looks for choices from this entity
//            'class' => Answer::class,
//            'query_builder' => function (EntityRepository $er) {
//                return $er->createQueryBuilder('u')
//                    ->orderBy('u.text', 'ASC');
//            },
//            // uses the User.username property as the visible option string
//            'choice_label' => 'text',
//            ))
            ->add('submit', SubmitType::class, array (
                'label' => 'Save'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Question::class,
        ]);
    }


    public function getBlockPrefix()
    {
        return 'QuestionType';
    }
}
