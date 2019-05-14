<?php


namespace App\Form;

use App\Entity\SettingsEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GlobalRefreshTimeType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options): void {
		$builder
			->add('globalRefreshTime', TimeType::class, [
				'input' => 'datetime',
				'widget' => 'text',
				'label' => 'Refresh time',
				'required' => false
			])
			->add('submit', SubmitType::class, [
				'label' => 'Update'
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void {
		$resolver->setDefaults([
			'data_class' => SettingsEntity::class,
		]);
	}

}