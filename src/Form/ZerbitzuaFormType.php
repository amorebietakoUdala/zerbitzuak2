<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\Entity\Enpresa;
use App\Repository\EnpresaRepository;

/**
 * Description of ZerbitzuaFormType
 *
 * @author ibilbao
 */
class ZerbitzuaFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
	$builder
	    ->add('izena_es')
	    ->add('izena_eu')

//	    ->add('arduraduna', EntityType::class, [
//		'placeholder'=>'messages.hautatu_arduraduna',
//		'class' => Erabiltzailea::class,
//     		'query_builder' => function (ErabiltzaileaRepository $repo) {
//		    return $repo->findAllOrderedByOrdena();
//		}
//		])
            ->add('enpresa', EntityType::class, [
		'placeholder'=>'messages.hautatu_enpresa',
		'class' => Enpresa::class,
     		'query_builder' => function (EnpresaRepository $repo) {
		    return $repo->createOrderedQueryBuilder();
		}
		])
	    ->add('ordena')
	    ->add('aktibatua', CheckboxType::class, [
					 'data' => true,
					 'label' => 'messages.aktibatua',
					 'label_attr' => ['class' => 'checkbox-inline']
            ])
	;
    }

    public function configureOptions(OptionsResolver $resolver) {
	$resolver->setDefaults([
	    'data_class' => '\App\Entity\Zerbitzua'
	]);
    }

}
