<?php
 
/**
 * @file
 * Contains \Drupal\easytransac\Form\EasyTransacConfigForm.
 */
 
namespace Drupal\easytransac\Form;
 
use Drupal\Core\Form\ConfigFormBase; 
use Drupal\Core\Form\FormStateInterface;
 
class EasyTransacConfigForm extends ConfigFormBase {
    /**
     * {@inheritdoc}
     * */
    
    public function getFormId() {
        return ('easytransac_config_form');
    }
 
    /**
     * {@inheritdoc}
     */

    public function buildForm(array $form, FormStateInterface $form_state) {
        $form = parent::buildForm($form, $form_state);
        $config = $this->config('easytransac.settings');
        $form['enable'] = array(
            '#type' => 'checkboxes',
            '#options' => array(TRUE => $this->t('Enable')),
            '#title' => $this->t('Enable/Disable'),
            '#default_value' => $config->get('easytransac.enable'),
        );
        $form['title'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Titre'),
            '#default_value' => $config->get('easytransac.title'),
            '#required' => TRUE,
        );
        $form['api_key'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('API Key'),
            '#default_value' => $config->get('easytransac.api_key'),
            '#required' => TRUE,
        );
        $form['3dsecure'] = array(
            '#type' => 'checkboxes',
            '#options' => array(TRUE => $this->t('Enable')),
            '#title' => $this->t('Enable/Disable 3D Secure payments'),
            '#default_value' => $config->get('easytransac.3dsecure'),
        );
        $form['oneclick'] = array(
            '#type' => 'checkboxes',
            '#options' => array(TRUE => $this->t('Enable')),
            '#title' => $this->t('Enable/Disable One Click payments'),
            '#default_value' => $config->get('easytransac.oneclick'),
        );
        $form['notifurl'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Notification URL'),
            '#default_value' => $config->get('easytransac.notifurl'),
            '#required' => TRUE,
        );
        $form['debug_mode'] = array(
            '#type' => 'checkboxes',
            '#options' => array(TRUE => $this->t('Enable')),
            '#title' => $this->t('Enable/Disable debug mode'),
            '#default_value' => $config->get('easytransac.debug_mode'),
        );
        return ($form);
    }

    /**
     * {@inheritdoc}
     */
    
    public function defaultConfiguration() {
        $default_config = \Drupal::config('easytransac.settings');
        return ([
            'enable' => $default_config->get('easytransac.enable'),
            'title' => $default_config->get('easytransac.title'),
            'api_key' => $default_config->get('easytransac.api_key'),
            '3dsecure' => $default_config->get('easytransac.3dsecure'),
            'oneclick' => $default_config->get('easytransac.oneclick'),
            'notifurl' => $default_config->get('easytransac.notifurl'),
            'debug_mode' => $default_config->get('easytransac.debug_mode'),
        ]);
    } 

    /**
     * {@inheritdoc}
     */
 
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $config = $this->config('easytransac.settings');
        $config->set('easytransac.enable', $form_state->getValue('enable'));
        $config->set('easytransac.title', $form_state->getValue('title'));
        $config->set('easytransac.api_key', $form_state->getValue('api_key'));
        $config->set('easytransac.3dsecure', $form_state->getValue('3dsecure'));
        $config->set('easytransac.oneclick', $form_state->getValue('oneclick'));
        $config->set('easytransac.notifurl', $form_state->getValue('notifurl'));
        $config->set('easytransac.debug_mode', $form_state->getValue('debug_mode'));
        $config->save();
        return (parent::submitForm($form, $form_state));
    }
 
    /**
     * {@inheritdoc}
     */
 
    protected function getEditableConfigNames() {
        return ([
            'easytransac.settings',
        ]);
    }
}