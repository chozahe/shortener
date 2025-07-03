<?php

namespace App\Form;

use App\Entity\Link;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('originalUrl', null, [
                'label' => 'Оригинальная ссылка',
                'error_bubbling' => true,
                'invalid_message' => 'введите корректный адрес!'
            ])
            ->add('isDisposable', ChoiceType::class, [
                'label' => 'Одноразовая ссылка',
                'error_bubbling' => true,
                'invalid_message' => 'Одноразови?',
                'choices' => [
                    'Да' => true,
                    'Нет' => false,
                ],
                'expanded' => true,  // true — радио-кнопки, false — селект
                'multiple' => false,
                'required' => true
            ])
            ->add('expiresAt', null, [
                'label' => 'Дата истечения',
                'widget' => 'single_text',
                'required' => false,
                'error_bubbling' => true,
                'invalid_message' => 'Укажите корректную дату!!!(нельзя в прошлое перемещаться)'
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Link::class,
        ]);
    }
}
