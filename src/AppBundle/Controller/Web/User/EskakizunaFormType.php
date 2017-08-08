<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller\Web\User;

use AppBundle\Controller\Web\Admin\EskatzaileaFormType;
use AppBundle\Controller\Web\Admin\GeoreferentziazioaFormType;
use AppBundle\Controller\Web\User\ErantzunaFormType;
use AppBundle\Entity\Eskakizuna;
use AppBundle\Entity\EskakizunMota;
use AppBundle\Entity\Eskatzailea;
use AppBundle\Entity\Georeferentziazioa;
use AppBundle\Entity\Jatorria;
use AppBundle\Entity\Zerbitzua;
use AppBundle\Repository\EskakizunMotaRepository;
use AppBundle\Repository\GeoreferentziazioaRepository;
use AppBundle\Repository\JatorriaRepository;
use AppBundle\Repository\ZerbitzuaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Description of EskakizunaFormType
 *
 * @author ibilbao
 */
class EskakizunaFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
	$readonly = $options['readonly'];
//        dump($options);die;
	$builder
            ->add('lep', null, [
		'disabled' => $readonly
	    ])
	    ->add('noiz', DateTimeType::class, [
		'disabled' => $readonly,
		'widget' => 'single_text',
		'html5' => 'false',
		'format' => 'yyyy-MM-dd HH:mm',
		'constraints' => [new NotBlank(),]
	    ])
	    ->add('kalea', null, [
		'disabled' => $readonly
	    ])
	    ->add('zerbitzua', EntityType::class,[
		'disabled' => $readonly,
		'placeholder'=> 'messages.hautatu_zerbitzua',
		'class' => Zerbitzua::class,
                'group_by' => 'enpresa',
		'query_builder' => function (ZerbitzuaRepository $repo) {
			return $repo->createOrderedQueryBuilder();
		    }
	        ])
	    ->add('argazkia', FileType::class,[
		'disabled' => $readonly,
		'data_class' => null,
	    ])
	    ->add('eskakizunMota', EntityType::class, [
		'disabled' => $readonly,
		'placeholder'=>'messages.hautatu_eskakizun_mota',
		'class' => EskakizunMota::class,
		'constraints' => [new NotBlank(),],
		'choice_attr' => ['class' => 'form-inline'],
		'expanded' => true,
		'multiple' => false,
		'choice_translation_domain' => null,
		'query_builder' => function (EskakizunMotaRepository $repo) {
		    return $repo->createOrderedQueryBuilder();
		}
	    ])
	    ->add('jatorria', EntityType::class, [
		'disabled' => $readonly,
		'placeholder'=>'messages.hautatu_jatorria',
		'class' => Jatorria::class,
		'constraints' => [new NotBlank(),],
		'expanded' => true,
		'multiple' => false,
		'choice_translation_domain' => null,
		'query_builder' => function (JatorriaRepository $repo) {
		    return $repo->createOrderedQueryBuilder();
		}
	    ])
	    ->add('erantzunak', CollectionType::class, [
	    'entry_type' => ErantzunaFormType::class,
//		'entry_options' => [
//		    'attr' => [
//			'class' => 'tinymce disabled',
//			]
//		    ],
	    'allow_add' => true,
	    'allow_delete' => true,
	    'by_reference' => false
	     ])
	    ->add('georeferentziazioa', GeoreferentziazioaFormType::class)
	    ;
	    if ( $options['editatzen'] === false ) {
		$builder->add('mamia', TextareaType::class,[
		    'attr' => ['class' => 'tinymce'],
		    'constraints' => [new NotBlank(),				    
				     ],
		]);
	    } else {
		$builder->add('mamia', TextareaType::class,[
		    'attr' => ['class' => 'tinymce readonly'],
		    'constraints' => [new NotBlank(),				    
				     ],
		]);
	    }
	    if (in_array('ROLE_ADMIN', $options['role']) or in_array('ROLE_ARDURADUNA', $options['role']) or in_array('ROLE_INFORMATZAILEA', $options['role'])) {
		$builder->add('eskatzailea', EskatzaileaFormType::class, [
		    'disabled' => $readonly
		]);
	    }
	
    }

    public function configureOptions(OptionsResolver $resolver) {
//	$resolver->setDefaults([
//	    'data_class' => '\AppBundle\Entity\Eskakizuna'
//	]);
	$resolver->setRequired(array(
            'editatzen',
	    'role'
        ));
	$resolver->setDefaults([
	    'csrf_protection' => false,
	    'readonly' => false,
	    'role' => []
	]);
    }

}