<?php

/**
 * @file
 * Contains \Drupal\coming_soon\Form\ComingSoonSubscribersForm
 */

namespace Drupal\coming_soon\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\coming_soon\Entity\Subscriber;

/**
 * ComingSoonSubscribersForm form.
 */
class ComingSoonSubscribersForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'coming_soon_subscribers_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['#attributes'] = array('role' => 'form', 'class' => array('form-inline', 'signup'));

    $form['email'] = array(
      '#type' => 'email',
      '#title' => NULL,
      '#required' => TRUE,
      '#prefix' => '<div class="form-group">',
      '#suffix' => '</div>',
      '#theme_wrappers' => array(),
      '#attributes' => array(
        'class' => array('form-control'),
        'placeholder' => $this->t('Enter your email address'),
        ),
      );

    $form['submit'] = array(
      '#type' => 'button',
      '#value' => t('Get notified!'),
      '#attributes' => array(
        'class' => array('btn', 'btn-theme'),
        ),
      '#ajax' => array(
        'callback' => array($this, 'validateEmailAjax'),
        'event' => 'click',
        'progress' => array(
          'type' => 'none',
          ),
        ),
      '#suffix' => '<span class="cs-msg"></span>',
      );

    return $form;
  }

  /**
   * Ajax callback to validate the email field.
   */
  public function validateEmailAjax(array &$form, FormStateInterface $form_state) {

    $response = new AjaxResponse();
    $email = $form_state->getValue('email');
    $css = ['color' => 'red'];

    // check if the email is valid
    if (!\Drupal::service('email.validator')->isValid($email)) {
      $message = $this->t('Please provide a valid e-mail address.');
      $response->addCommand(new CssCommand('.cs-msg', $css));
      $response->addCommand(new HtmlCommand('.cs-msg', $message));
    }
    else {
      $query = \Drupal::database()->select('subscribers','s');
      $query->addField('s', 'email');
      $query->condition('s.email', $email);
      $result = $query->execute()->fetchField();

      if (!empty($result)) {
        $message = $this->t('You have already subscribed.');
        $response->addCommand(new CssCommand('.cs-msg', $css));
        $response->addCommand(new HtmlCommand('.cs-msg', $message));
      }
      else {
        $subscriber = Subscriber::create(array('email' => $email));
        $subscriber->save();

        $message = $this->t('You subscribed successfully, we will notify you as soon as the website is ready.');
        $css = ['color' => 'green'];
        $response->addCommand(new CssCommand('.cs-msg', $css));
        $response->addCommand(new HtmlCommand('.cs-msg', $message));
      }
    }

    return $response;
  }


  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!\Drupal::service('email.validator')->isValid($form_state->getValue('email'))) {
      $form_state->setErrorByName('email', $this->t("Please provide a valid e-mail address."));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    drupal_set_message('Thank you for subscribing, you will be the first to know when the website is ready');
  }
}