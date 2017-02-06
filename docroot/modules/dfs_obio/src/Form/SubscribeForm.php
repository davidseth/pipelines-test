<?php

namespace Drupal\dfs_obio\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageInterface;

/**
 * Provides a subscription sign up form.
 */
class SubscribeForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dfs_obio_subscribe_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = [];

    // Add some text prefixing the subscription form.
    $form['header'] = [
      '#markup' => '<h5 class="subscription-header row">' . t('Subscribe & Save') . '</h5>'
    ];

    $form['form'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['row']]
    ];

    $form['form']['email'] = [
      '#prefix' => '<div class="columns small-9">',
      '#suffix' =>'</div>',
      '#type' => 'textfield',
      '#attributes' => ['class' => ['email-form-textbox'], 'placeholder' => t('Enter Email Address')],
      '#size' => 80,
      '#maxlength' => 128,
      '#required' => TRUE,
    ];

    $form['form']['submit'] = [
      '#prefix' => '<div class="columns small-3">',
      '#suffix' =>'</div>',
      '#type' => 'submit',
      '#attributes' => ['class' => ['subscribe-submit']],
      '#value' => t('Sign Up'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Send an email message to the given address.
    $address = $form_state->getValue('email');

    /** @var \Drupal\Core\Mail\MailManager $mail_manager */
    $mail_manager = \Drupal::service('plugin.manager.mail');

    $message = $mail_manager->mail('dfs_obio', 'sign-up', $address, LanguageInterface::LANGCODE_NOT_APPLICABLE);

    // Check for success.
    if ($message['result']) {
      drupal_set_message(t('Thanks for signing up! An email confirmation has been sent to @address', ['@address' => $address]));
    }
  }

}
