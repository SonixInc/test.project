<?php


namespace App\Form;

use App\Entity\Application;
use App\Entity\Summary;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class RespondFormType
 *
 * @package App\Form
 */
class RespondFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('summary', EntityType::class, [
                'class'        => Summary::class,
                'choice_label' => 'category',
                'choices'      => $options['user_summaries'],
                'constraints'  => [
                    new NotBlank(),
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'     => Application::class,
            'user_summaries' => null
        ]);
    }
}