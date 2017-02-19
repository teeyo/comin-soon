<?php

/**
 * @file
 * Contains \Drupal\coming_soon\Controller\ComingSoonController.
 */

namespace Drupal\coming_soon\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the Example module.
 */
class ComingSoonController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function index() {
    $form = \Drupal::formBuilder()->getForm('Drupal\coming_soon\Form\ComingSoonSubscribersForm');
    $config = $this->config('coming_soon.settings');
    $bg = $config->get('coming_soon_bg');
    $bg = !empty($config->get('coming_soon_bg')) && (is_array($config->get('coming_soon_bg'))) ? array_shift($config->get('coming_soon_bg')) : NULL;
    $bg = \Drupal\file\Entity\File::load($bg);
    $logo = theme_get_setting('logo.url');

    return array(
      '#theme' => 'coming_soon_predefined_page',
      '#config' => $config,
      '#background' => $bg,
      '#form' => $form,
      '#logo' => $logo,
      );
  }

}