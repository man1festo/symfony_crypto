<?php

namespace App\Controller\Form;

use App\Controller\Web\UserForm\v1\Input\UserFormDTO;
use App\Domain\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'login', TextType::class, [
                'label' => 'login',
                'attr' => [
                    'placeholder' => 'login',
                    'class' => 'form-control',
                    'data-time' => time()
                ]
            ]
        );
        if($options['isNew']){
            $builder->add('password', PasswordType::class, [
                'label' => 'password',
                'attr' => [
                    'placeholder' => 'password',
                    'class' => 'form-control',
                ]
            ]);
        }
        $builder->add('submit', SubmitType::class);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'isNew' => true,
            'data_class' => UserFormDTO::class,
            'empty_data' => new UserFormDTO(),
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'createUser';
    }
}
