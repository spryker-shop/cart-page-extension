<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage\Form;

use Closure;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShareCartCompanyUserShareEditForm extends AbstractType
{
    public const FIELD_ID_QUOTE_COMPANY_USER = 'idQuoteCompanyUser';
    public const FIELD_ID_COMPANY_USER = 'idCompanyUser';
    public const FIELD_CUSTOMER_NAME = 'customerName';
    public const FIELD_QUOTE_PERMISSION_GROUP = 'quotePermissionGroup';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(ShareCartForm::OPTION_PERMISSION_GROUPS);
        $resolver->setDefaults([
            'data_class' => ShareDetailTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addIdQuoteCompanyUserField($builder);
        $this->addIdCompanyUserField($builder);
        $this->addCustomerNameField($builder);
        $this->addQuotePermissionGroupField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdQuoteCompanyUserField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_ID_QUOTE_COMPANY_USER, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCompanyUserField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_ID_COMPANY_USER, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCustomerNameField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_CUSTOMER_NAME, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addQuotePermissionGroupField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_QUOTE_PERMISSION_GROUP, ChoiceType::class, [
            'choices' => $options[ShareCartForm::OPTION_PERMISSION_GROUPS],
            'expanded' => false,
            'required' => true,
            'label' => 'shared_cart.form.select_permissions',
        ]);

        $builder->get(static::FIELD_QUOTE_PERMISSION_GROUP)
            ->addModelTransformer($this->createModelTransformer());

        return $this;
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    protected function createModelTransformer(): DataTransformerInterface
    {
        return new CallbackTransformer(
            $this->createTransformCallback(),
            $this->createReverseTransformCallback()
        );
    }

    /**
     * @return \Closure
     */
    protected function createTransformCallback(): Closure
    {
        return function ($quotePermissionGroupTransfer) {
            if ($quotePermissionGroupTransfer) {
                return $quotePermissionGroupTransfer->getIdQuotePermissionGroup();
            }

            return '';
        };
    }

    /**
     * @return \Closure
     */
    protected function createReverseTransformCallback(): Closure
    {
        return function ($idQuotePermissionGroup) {
            if ($idQuotePermissionGroup) {
                return (new QuotePermissionGroupTransfer())
                    ->setIdQuotePermissionGroup($idQuotePermissionGroup);
            }

            return null;
        };
    }
}
