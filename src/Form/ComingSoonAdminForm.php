<?php

/**
 * @file
 * Contains \Drupal\coming_soon\Form\ComingSoonAdminForm.
 */

namespace Drupal\coming_soon\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Datetime\DateTimePlus;

class ComingSoonAdminForm extends ConfigFormBase {

 /**
  * {@inheritdoc}
  */
 public function getFormId() {
  return 'coming_soon_admin_form';
}

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['coming_soon.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('coming_soon.settings');

    $form['coming_soon_heading'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Heading'),
      '#default_value' => $config->get('coming_soon_heading'),
      '#description' => $this->t('Heading to display in the coming soon page, will default to the site name if omitted.'),
      );

    $form['coming_soon_body'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Body'),
      '#default_value' => $config->get('coming_soon_body'),
      '#description' => $this->t('The body text of the page.'),
      );

    $form['coming_soon_notification'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable guests notification'),
      '#description' => $this->t('Enable users to sign up for notification on website launch.'),
      '#default_value' => $config->get('coming_soon_notification'),
      );

    $form['coming_soon_end_date'] = array(
      '#type' => 'date',
      '#title' => $this->t('End date'),
      '#description' => $this->t('When to stop displaying the coming soon page.'),
      '#default_value' => $config->get('coming_soon_end_date'),
      );

    $form['coming_soon_bg'] = array(
      '#type' => 'managed_file',
      '#name' => 'coming_soon_bg',
      '#title' => $this->t('Background image'),
      '#default_value' => $config->get('coming_soon_bg'),
      '#description' => t("Background image, if omitted, a default background image will be used."),
      '#upload_location' => 'public://coming_soon/',
      );

    $form['coming_soon_copyrights'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Copyrights'),
      '#default_value' => $config->get('coming_soon_copyrights'),
      '#description' => $this->t('Copyrights text.'),
      );

    $form['coming_soon_social'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Social networks'),
    );

    $form['coming_soon_social']['coming_soon_facebook'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Facebook'),
      '#default_value' => $config->get('coming_soon_facebook'),
      '#description' => $this->t('Facebook link.'),
      );

    $form['coming_soon_social']['coming_soon_twitter'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Twitter'),
      '#default_value' => $config->get('coming_soon_twitter'),
      '#description' => $this->t('Twitter link.'),
      );

    $form['coming_soon_social']['coming_soon_googleplus'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Google+'),
      '#default_value' => $config->get('coming_soon_googleplus'),
      '#description' => $this->t('Google+ link.'),
      );

    $form['coming_soon_social']['coming_soon_linkedin'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Linkedin'),
      '#default_value' => $config->get('coming_soon_linkedin'),
      '#description' => $this->t('Linkedin link.'),
      );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    
    $this->config('coming_soon.settings')
    ->set('coming_soon_heading', $values['coming_soon_heading'])
    ->set('coming_soon_body', $values['coming_soon_body'])
    ->set('coming_soon_notification', $values['coming_soon_notification'])
    ->set('coming_soon_end_date', $values['coming_soon_end_date'])
    ->set('coming_soon_bg', $values['coming_soon_bg'])
    ->set('coming_soon_copyrights', $values['coming_soon_copyrights'])
    ->set('coming_soon_facebook', $values['coming_soon_facebook'])
    ->set('coming_soon_twitter', $values['coming_soon_twitter'])
    ->set('coming_soon_googleplus', $values['coming_soon_googleplus'])
    ->set('coming_soon_linkedin', $values['coming_soon_linkedin'])
    ->save();
  }

}