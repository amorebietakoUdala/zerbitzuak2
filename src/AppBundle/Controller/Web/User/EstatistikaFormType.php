<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller\Web\User;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

use AppBundle\Repository\EnpresaRepository;
use AppBundle\Entity\Enpresa;
use AppBundle\Entity\Estatistika;
use AppBundle\Repository\EstatistikaRepository;

/**
 * Description of StatsFormType
 *
 * @author ibilbao
 */
class EstatistikaFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $urteak = $options['urteak'];
	$builder
            ->add('urtea', ChoiceType::class, [
		    'choices' => $urteak,
		    'translation_domain' => false
	    ])
	    ->add('enpresa', EntityType::class,[
		    'placeholder'=> 'messages.hautatu_enpresa',
		    'class' => Enpresa::class,
		    'query_builder' => function (EnpresaRepository $repo) {
			    return $repo->createOrderedQueryBuilder();
			}
		    ])
	    ;
    }

    public function configureOptions(OptionsResolver $resolver) {
	$resolver->setDefaults([
	    'urteak' => [],
	    'csrf_protection' => true,
	]);
    }

}
