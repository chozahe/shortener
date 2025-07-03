<?php

namespace App\Form;

use App\Entity\Link;
use Symfony\Component\Form\AbstractType;
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
                'invalid_message' => 'Пожалуйста, введите действительный URL-адрес (http или https).'
            ])
            ->add('isDisposable', null, [
                'label' => 'Одноразовая ссылка',
                'error_bubbling' => true,
                'invalid_message' => 'Пожалуйста, укажите, является ли ссылка одноразовой.'
            ])
            ->add('expiresAt', null, [
                'label' => 'Дата истечения',
                'widget' => 'single_text',
                'required' => false,
                'error_bubbling' => true,
                'invalid_message' => 'Пожалуйста, укажите корректную дату истечения (должна быть в будущем).'
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
