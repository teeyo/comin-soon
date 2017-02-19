<?php

namespace Drupal\coming_soon\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ComingSoonRedirectManager implements EventSubscriberInterface {

  public function checkForRedirection(GetResponseEvent $event) {

    // get the current user
    $current_user = \Drupal::currentUser();
    // get the coming_soon configuration
    $config = \Drupal::config('coming_soon.settings');
    $end_date = date_create($config->get('coming_soon_end_date'));
    $now = date_create(date('Y-m-d'));
    $diff = date_diff($end_date, $now);
    $comingsoon_url = \Drupal::getContainer()->get('url_generator')->generateFromRoute('coming_soon.index');
    // get login url
    $login_url = \Drupal::getContainer()->get('url_generator')->generateFromRoute('user.login');
    $current_path = \Drupal::service('path.current')->getPath();
    // check if the user is anonymous & the end date of the coming soon page is less than the current
    // date & the visited url is different then the login page
    if ((!empty($current_user->getRoles()) && in_array('anonymous', $current_user->getRoles())) && 
      $diff->days >= 0 && $login_url != $current_path && $comingsoon_url != $current_path) {
      $event->setResponse(new RedirectResponse($comingsoon_url, 301));
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('checkForRedirection');

    return $events;
  }

}