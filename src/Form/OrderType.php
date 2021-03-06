<?php
/**
 * Order type.
 */

namespace App\Form;

use App\Entity\Order;
use App\Entity\ShippingMethod;
use App\Entity\PaymentMethod;
use App\Repository\PaymentMethodRepository;
use App\Repository\ShippingMethodRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class OrderType.
 */
class OrderType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'orderProducts',
            CollectionType::class,
            [
                'required' => false,
                'entry_type' => OrderProductsType::class,
                'allow_delete' => true,
                'label' => 'form.products',
            ]
        );
        $builder->add(
            'shipping_method',
            EntityType::class,
            [
                'class' => ShippingMethod::class,
                'query_builder' => function (ShippingMethodRepository $repository) {
                    return $repository->queryAll();
                },
                'choice_label' => function (ShippingMethod $method) {
                    return $method->getType();
                },
                'label' => 'form.shipping',
            ]
        );
        $builder->add(
            'payment',
            EntityType::class,
            [
                'class' => PaymentMethod::class,
                'query_builder' => function (PaymentMethodRepository $repository) {
                    return $repository->queryAll();
                },
                'choice_label' => function (PaymentMethod $method) {
                    return $method->getType();
                },
                'label' => 'form.payment',
            ]
        );
        $builder->add(
            'sum',
            MoneyType::class,
            [
                'divisor' => 100,
                'currency' => 'PLN',
                'disabled' => true,
                'label' => 'form.sum',
                'mapped' => false,
                'required' => false,
            ]
        );
        $builder->add(
            'order',
            SubmitType::class,
            [
                'label' => 'button.order',
                'attr' => [
                    'class' => 'btn-success',
                ],
            ]
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Order::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'order_products';
    }
}
