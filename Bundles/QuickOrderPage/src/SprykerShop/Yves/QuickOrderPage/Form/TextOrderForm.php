<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerShop\Yves\QuickOrderPage\Form\Constraint\TextOrderCorrectConstraint;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageFactory getFactory()
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig getConfig()
 */
class TextOrderForm extends AbstractType
{
    public const FIELD_TEXT_ORDER = 'textOrder';
    public const SUBMIT_BUTTON_VERIFY = 'verifyTextOrder';
    public const FIELD_TEXT_ORDER_PLACEHOLDER = 'quick-order.paste-order.input-placeholder.copy-paste-order';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addTextOrderField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTextOrderField(FormBuilderInterface $builder): FormTypeInterface
    {
        $builder->add(static::FIELD_TEXT_ORDER, TextareaType::class, [
            'label' => false,
            'attr' => ['placeholder' => static::FIELD_TEXT_ORDER_PLACEHOLDER],
            'constraints' => [
                new NotBlank(),
                new TextOrderCorrectConstraint([
                    TextOrderCorrectConstraint::OPTION_BUNDLE_CONFIG => $this->getFactory()->getBundleConfig(),
                ]),
            ],
        ]);

        return $this;
    }
}
