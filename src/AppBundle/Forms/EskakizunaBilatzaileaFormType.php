<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Forms;

use AppBundle\Entity\Egoera;
use AppBundle\Entity\Enpresa;
use AppBundle\Entity\Zerbitzua;
use AppBundle\Repository\EnpresaRepository;
use AppBundle\Repository\ZerbitzuaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

/**
 * Description of EskakizunaFormType.
 *
 * @author ibilbao
 */
class EskakizunaBilatzaileaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $options['data']['locale'];
        $builder
            ->add('lep')
        ->add('noiztik', DateTimeType::class, [
        'widget' => 'single_text',
        'html5' => 'false',
        'format' => 'yyyy-MM-dd HH:mm',
        'attr' => ['class' => 'js-datepicker'],
        ])
        ->add('nora', DateTimeType::class, [
        'widget' => 'single_text',
        'html5' => 'false',
        'format' => 'yyyy-MM-dd HH:mm',
        'attr' => ['class' => 'js-datepicker'],
        ])
        ->add('egoera', EntityType::class, [
        'placeholder' => 'messages.hautatu_egoera',
        'class' => Egoera::class,
        'choice_label' => function ($egoera) use ($locale) {
            if ('es' === $locale) {
                return $egoera->getDeskripzioaEs();
            } else {
                return $egoera->getDeskripzioaEu();
            }
        },
        ])
        ->add('kalea', TextType::class, [
        ]);
        if (in_array('ROLE_ADMIN', $options['data']['role']) || in_array('ROLE_ARDURADUNA', $options['data']['role'])) {
            $builder->add('enpresa', EntityType::class, [
            'placeholder' => 'messages.hautatu_enpresa',
            'class' => Enpresa::class,
            'query_builder' => function (EnpresaRepository $repo) {
                return $repo->createOrderedQueryBuilder();
            },
            ])
            ->add('zerbitzua', EntityType::class, [
            'placeholder' => 'messages.hautatu_zerbitzua',
            'class' => Zerbitzua::class,
            'group_by' => 'enpresa',
            'choice_label' => function ($zerbitzua) use ($locale) {
                if ('es' === $locale) {
                    return $zerbitzua->getIzenaEs();
                } else {
                    return $zerbitzua->getIzenaEu();
                }
            },
            'query_builder' => function (ZerbitzuaRepository $repo) {
                return $repo->createOrderedQueryBuilder();
            },
        ]);
        } else {
            $builder->add('zerbitzua', EntityType::class, [
            'placeholder' => 'messages.hautatu_zerbitzua',
            'class' => Zerbitzua::class,
            'query_builder' => function (ZerbitzuaRepository $repo) use ($options) {
                $enpresa = null;
                if (array_key_exists('enpresa', $options['data'])) {
                    $enpresa = $options['data']['enpresa'];
                }

                return $repo->createZerbitzuakQueryBuilder($enpresa);
            }, ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        'csrf_protection' => true,
        'data_class' => null,
    ]);
    }
}
