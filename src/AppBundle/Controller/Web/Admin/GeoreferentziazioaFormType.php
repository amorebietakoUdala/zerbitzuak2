<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller\Web\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * Description of GeoreferentziazioaFormType
 *
 * @author ibilbao
 */
class GeoreferentziazioaFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
	$builder
	    ->add('longitudea',HiddenType::class)
	    ->add('latitudea',HiddenType::class)
	    ->add('google_address',HiddenType::class)
	    ->add('mapa_longitudea',HiddenType::class)
	    ->add('mapa_latitudea',HiddenType::class)
	;
    }

    public function configureOptions(OptionsResolver $resolver) {
	$resolver->setDefaults([
	    'data_class' => '\AppBundle\Entity\Georeferentziazioa'
	]);
    }

}
