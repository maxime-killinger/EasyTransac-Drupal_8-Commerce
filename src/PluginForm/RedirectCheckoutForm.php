<?php

namespace Drupal\easytransac\PluginForm;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use Drupal\commerce_payment\PluginForm\PaymentOffsiteForm;
use Drupal\Core\Form\FormStateInterface;

class RedirectCheckoutForm extends PaymentOffsiteForm {

  function easytransac__add_transaction($received_data, $order = NULL, $payment_instance = NULL)
  {
      $api_key = NULL;
      extract(easytransac__expose_settings());
      if (empty($api_key)) {
          drupal_set_message(t('EasyTransac module not configured.'), 'error');
          return;
      }
  
      /* #var $response\EasyTransac\Entities\Notification $response*/
      /* @var $response \EasyTransac\Entities\DoneTransaction */
  
      if (is_array($received_data)) {
          try {
              $response = \EasyTransac\Core\PaymentNotification::getContent($received_data, $api_key);
              if (!$response) {
                  throw new Exception ('empty response');
              }
          } catch (Exception $exc) {
              watchdog('easytransac', $exc->getMessage(), array(), WATCHDOG_ERROR);
              return;
          }
      } else {
          $response = $received_data;
      }
  
      // Retrieve payment instance.
      if (is_null($payment_instance)) {
          $payment_instance = commerce_payment_method_instance_load("easytransac|commerce_payment_easytransac");
      }
  
      if (empty($payment_instance)) {
          watchdog('easytransac', 'No payment instance found.', array(), WATCHDOG_ERROR);
          return;
      }
  
      // Retrieve orders.
      if (is_null($order)) {
          $order = commerce_order_load($response->getOrderId());
          if (!$order) {
              watchdog('easytransac', 'Unknown order ID received.', array(), WATCHDOG_ERROR);
              return;
          }
      }
  
      if (empty($order)) {
          watchdog('easytransac', 'No order found.', array(), WATCHDOG_ERROR);
          return;
      }
  
      $payments = commerce_payment_transaction_load_multiple(array(), array(
          'order_id' => $order->order_id,
      ));
  
      $count_succeeded_payments = 0;
  
      // Verify if the received data has already been processed.
      foreach ($payments as $payment) {
          if ($payment->remote_id == $response->getTid()) {
              if ($payment->status != COMMERCE_PAYMENT_STATUS_PENDING) {
                  // Transaction already processed.
                  watchdog('easytransac', 'Transaction already processed.', array(), WATCHDOG_NOTICE);
                  return;
              }
              // Existing transaction will be updated.
              break;
          } elseif ($payment->status == COMMERCE_PAYMENT_STATUS_SUCCESS) {
              $count_succeeded_payments++;
          }
      }
  
      // Verify data consistency between local order and remote data.
      if ($order->order_id != $response->getOrderId()) {
          watchdog('easytransac', 'Order id does not match the remote one.', array(), WATCHDOG_ERROR);
          return;
      } elseif ($order->uid != $response->getUid()) {
          watchdog('easytransac', 'Uid does not match the remote one.', array(), WATCHDOG_ERROR);
          return;
      }
  
      // Create a new payment transaction for the order.
      $transaction = commerce_payment_transaction_new('easytransac', $order->order_id);
      $transaction->instance_id = $payment_instance['instance_id'];
  
      $transaction->remote_id = $response->getTid();
      $transaction->amount = commerce_currency_decimal_to_amount($response->getAmount(), 'EUR');
      $transaction->currency_code = 'EUR';
  
      $transaction->remote_status = $response->getStatus();
  
      $transaction->data['RequestId'] = $response->getRequestId();
  
      $is_multiple_payments = is_a($response, '\EasyTransac\Entities\DoneTransaction')
          && $response->getMultiplePayments() === 'yes';
  
      switch ($response->getStatus()) {
          case 'failed':
              $transaction->status = COMMERCE_PAYMENT_STATUS_FAILURE;
              $transaction->message = $response->getMessage();
              commerce_order_status_update($order, 'canceled', FALSE, NULL, t('Payment failure.'));
              drupal_set_message(t('Le paiement a échoué.'), 'error');
              break;
  
          case 'captured':
  
              // Saves ClientId
              $user = user_load($response->getUid());
              $user->is_new = false;
              $edit = array();
              $edit['data']['easytransac-clientid'] = $response->getClient()->getId();
              user_save($user, $edit);
  
              // Saves status
              $transaction->status = COMMERCE_PAYMENT_STATUS_SUCCESS;
              $multiple_payments_info = ($is_multiple_payments ? (' Payment ' . ($count_succeeded_payments + 1) . ' of 3.') : '');
              $test_info = (is_a($response, '\EasyTransac\Entities\Notification') && $response->getTest() === 'yes') ? '[TEST] - ' : '';
              $transaction->message = $test_info . $response->getMessage() . '.' . $multiple_payments_info;
              commerce_order_status_update($order, 'completed');
              break;
  
          case 'pending':
              $transaction->status = COMMERCE_PAYMENT_STATUS_PENDING;
              $transaction->message = $received_data['Message'];
              break;
  
          case 'refunded':
              // No refunded statuts available, so fallback to failure status.
              $transaction->status = COMMERCE_PAYMENT_STATUS_FAILURE;
              $transaction->message = $received_data['Message'];
              break;
      }
      // Save the transaction information.
      commerce_payment_transaction_save($transaction);
  }

  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    easytransac__add_transaction(NULL, NULL, NULL);
    header('Location: ' . "http://google.com", true);
    exit;
  }
}