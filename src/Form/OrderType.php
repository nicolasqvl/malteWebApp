<?php

namespace App\Form;

use App\Entity\Kit;
use App\Entity\Order;
use App\Repository\KitRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $kitId = $options['kitId'];
        $user = $options['user'];

        $builder
            ->add('kit', EntityType::class, [
                'label' => 'Choisissez le lot *',
                'required' => false,
                'class' => Kit::class,
                // Request to display kit of Qr-code
                'query_builder' => function (KitRepository $kitRepository) use ($kitId, $user){
                    return $kitRepository->createQueryBuilder('kit')
                    ->andWhere('kit.id = :kitId')
                    ->andWhere('kit.unit = :user')
                    ->setParameter('user', $user)
                    ->setParameter('kitId', $kitId);
                }
            ])
            ->add('declarer_name', TextType::class, [
                'label' => 'Nom du déclarant *',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Indiquez votre identité'
                ]
            ])
            ->add('declarer_phone', TelType::class, [
                'label' => 'Numéro de téléphone *',
                'required' => false,
                'attr' => [
                    'placeholder' => '+33'
                ]
            ])
            // ->add('interventionNumber', TextType::class, [
            //     'label' => 'N° d\'intervention',
            //     'attr' => ['placeholder' => 'Facultatif'],
            //     'required' => false
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'kitId' => null,
            'user' => null,
        ]);
    }
}
