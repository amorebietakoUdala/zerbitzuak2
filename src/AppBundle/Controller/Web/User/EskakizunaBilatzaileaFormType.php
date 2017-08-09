<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller\Web\User;

use AppBundle\Entity\Egoera;
use AppBundle\Entity\Enpresa;
use AppBundle\Entity\Zerbitzua;
use AppBundle\Repository\EgoeraRepository;
use AppBundle\Repository\EnpresaRepository;
use AppBundle\Repository\ZerbitzuaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

/**
 * Description of EskakizunaFormType
 *
 * @author ibilbao
 */
class EskakizunaBilatzaileaFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
	$builder
            ->add('lep')
	    ->add('noiztik', DateTimeType::class, [
		'widget' => 'single_text',
		'html5' => 'false',
		'format' => 'yyyy-MM-dd HH:mm',
		'attr' => [ 'class' => 'js-datepicker'],
	    ])
	    ->add('nora', DateTimeType::class, [
		'widget' => 'single_text',
		'html5' => 'false',
		'format' => 'yyyy-MM-dd HH:mm',
		'attr' => [ 'class' => 'js-datepicker'],
	    ])
	    ->add('egoera', EntityType::class,[
		'placeholder'=> 'messages.hautatu_egoera',
		'class' => Egoera::class,
		'query_builder' => function (EgoeraRepository $repo) {
			return $repo->createOrderedQueryBuilder();
		    }
	    ])
	    ->add('kalea', TextType::class,[
	    ]);
	    if (in_array('ROLE_ADMIN', $options['data']['role']) || in_array('ROLE_INFORMATZAILEA', $options['data']['role']) || in_array('ROLE_ARDURADUNA', $options['data']['role'])) {
		$builder->add('enpresa', EntityType::class,[
		    'placeholder'=> 'messages.hautatu_enpresa',
		    'class' => Enpresa::class,
		    'query_builder' => function (EnpresaRepository $repo) {
			    return $repo->createOrderedQueryBuilder();
			}
		    ])
		    ->add('zerbitzua', EntityType::class,[
		    'placeholder'=> 'messages.hautatu_zerbitzua',
		    'class' => Zerbitzua::class,
		    'group_by' => 'enpresa',
		    'query_builder' => function (ZerbitzuaRepository $repo) {
			    return $repo->createOrderedQueryBuilder();
		    }
		]);
	    } else {
		$builder->add('zerbitzua', EntityType::class,[
		    'placeholder'=> 'messages.hautatu_zerbitzua',
		    'class' => Zerbitzua::class,
		    'query_builder' => function (ZerbitzuaRepository $repo) use ($options) {
			return $repo->createOrderedQueryBuilder($options['data']);
		}]);
	    }
    }

    public function configureOptions(OptionsResolver $resolver) {
	$resolver->setDefaults([
	    'csrf_protection' => true,
	]);
    }

}
