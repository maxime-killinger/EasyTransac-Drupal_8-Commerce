<?php

namespace Drupal\easytransac\Plugin\Commerce\PaymentGateway;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\OffsitePaymentGatewayBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides the EasyTransac offsite Checkout payment gateway.
 *
 * @CommercePaymentGateway(
 *   id = "easytransac_redirect_checkout",
 *   label = @Translation("EasyTransac"),
 *   display_label = @Translation("EasyTransac"),
 *    forms = {
 *     "offsite-payment" = "Drupal\easytransac\PluginForm\RedirectCheckoutForm",
 *   },
 *   payment_method_types = {"credit_card"},
 *   credit_card_types = {
 *     "mastercard", "visa",
 *   },
 * )
 */
class RedirectCheckout extends OffsitePaymentGatewayBase
{
  public function defaultConfiguration()
  {
    return [
      'api_key' => '',
      'currency_code' => 'EUR',
      '3ds' => true,
      'debug_mode' => false,
    ] + parent::defaultConfiguration();
  }

  public function buildConfigurationForm(array $form, FormStateInterface $form_state)
  {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API key'),
      '#description' => $this->t('The API key for the same user as used in Agreement ID.'),
      '#default_value' => $this->configuration['api_key'],
      '#required' => true,
    ];

    $form['3ds'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('3D Secure'),
      '#description' => $this->t('3D Secure'),
      '#default_value' => $this->configuration['3ds'],
      '#required' => true,
    ];

    $form['debug_mode'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Debug Mode'),
      '#description' => $this->t('Debug Mode'),
      '#default_value' => $this->configuration['debug_mode'],
      '#required' => true,
    ];

    return $form;
  }

  public function submitConfigurationForm(array &$form, FormStateInterface $form_state)
  {
    parent::submitConfigurationForm($form, $form_state);
    $values = $form_state->getValue($form['#parents']);
    $this->configuration['api_key'] = $values['api_key'];
    $this->configuration['currency_code'] = $values['currency_code'];
    $this->configuration['3ds'] = $values['3ds'];
    $this->configuration['debug_mode'] = $values['debug_mode'];
  }
}