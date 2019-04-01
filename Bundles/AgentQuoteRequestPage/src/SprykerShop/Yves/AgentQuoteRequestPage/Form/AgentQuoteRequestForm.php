<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Form;

use DateTime;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageFactory getFactory()
 * @method \SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageConfig getConfig()
 */
class AgentQuoteRequestForm extends AbstractType
{
    public const SUBMIT_BUTTON_SAVE = 'save';
    public const SUBMIT_BUTTON_SEND_TO_CUSTOMER = 'sendToCustomer';

    public const OPTION_IS_DEFAULT_PRICE_MODE_GROSS = 'option_is_default_price_mode_gross';

    protected const LABEL_QUOTE_REQUEST_IS_LATEST_VERSION_HIDDEN = 'quote_request_page.quote_request.labels.hide_latest_version';
    protected const GLOSSARY_KEY_DATE_VIOLATION = 'quote_request_page.quote_request.violations.invalid_date';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuoteRequestTransfer::class,
        ]);
        $resolver->setRequired([static::OPTION_IS_DEFAULT_PRICE_MODE_GROSS]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addLatestVersionForm($builder, $options)
            ->addValidUntilField($builder)
            ->addIsHiddenLatestVersionField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addLatestVersionForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            QuoteRequestTransfer::LATEST_VERSION,
            AgentQuoteRequestVersionSubForm::class,
            [
                AgentQuoteRequestForm::OPTION_IS_DEFAULT_PRICE_MODE_GROSS => $options[AgentQuoteRequestForm::OPTION_IS_DEFAULT_PRICE_MODE_GROSS],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidUntilField(FormBuilderInterface $builder)
    {
        $builder->add(QuoteRequestTransfer::VALID_UNTIL, DateTimeType::class, [
            'label' => false,
            'widget' => 'single_text',
            'required' => false,
            'attr' => [
                'class' => 'datepicker safe-datetime',
            ],
        ]);

        $builder->get(QuoteRequestTransfer::VALID_UNTIL)
            ->addModelTransformer($this->createDateTimeModelTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsHiddenLatestVersionField(FormBuilderInterface $builder)
    {
        $builder->add(QuoteRequestTransfer::IS_LATEST_VERSION_HIDDEN, CheckboxType::class, [
            'label' => static::LABEL_QUOTE_REQUEST_IS_LATEST_VERSION_HIDDEN,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createDateTimeModelTransformer(): CallbackTransformer
    {
        return new CallbackTransformer(
            function ($value) {
                if ($value !== null) {
                    $value = new DateTime($value);
                }
                return $value;
            },
            function ($value) {
                if ($value instanceof DateTime) {
                    $value = $value->format('Y-m-d H:i:s.u');
                }
                return $value;
            }
        );
    }
}
