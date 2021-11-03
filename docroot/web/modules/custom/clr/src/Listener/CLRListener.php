<?php

namespace Drupal\clr\Listener;

use Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Serialization\Xml;
use Drupal\Component\Serialization\Json;

/**
 * Class ExampleLoginListener.
 *
 * @package Drupal\example
 */
class CLRListener implements EventSubscriberInterface
{

  /**
   * @var path.current service
   */
  private $currentPath;
 

  /**
   * Constructor with dependency injection
   */
  public function __construct($currentPath) {
    $this->currentPath = $currentPath;
  }

  /**
   * Add JWT access token to user login API response
   */
  public function onHttpLoginResponse(FilterResponseEvent $event) {
    // Halt if not user login request
    if ($this->currentPath->getPath() !== '/user/login') {
      return;
    }
    // Get response
    $response = $event->getResponse();
    // Ensure not error response
    if ($response->getStatusCode() !== 200) {
      return;
    }
    // Get request
    $request = $event->getRequest();
    // Just handle JSON format for now
    if ($request->query->get('_format') !== 'json') {
      return;
    }
    // Decode and add data
    if ($content = $response->getContent()) {
      if ($decoded = Json::decode($content)) {
        // Add JWT access_token
        $data = [
          'roles' => $this->getUserRole(),
          'mail' => $this->getUserMail(),
          'matrikelnumber' => $this->getMatrikelNumber(),
          'fullname' => $this->getFullname(),
          'uuid' => $this->getUUID(),
          ];
        $decoded['access_token'] = $data;
        // Set new response JSON
        $response->setContent(Json::encode($decoded));
        $event->setResponse($response);
      }
    }
  }

  public function getUserRole() {
    $current_user = \Drupal::currentUser();
    $roles = $current_user->getRoles();
    return $roles;
  }

  public function getUserMail() {
    $current_user = \Drupal::currentUser();
    $email = $current_user->getEmail();
    return $email;
  }

  public function getMatrikelNumber() {
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $matrikel_number = $user->field_matrikelnummer->value;
    return $matrikel_number;
  }

  public function getFullname() {
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $fullname = $user->field_fullname->value;
    return $fullname;
  }

  public function getUUID() {
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $uuid = $user->uuid();
    return $uuid;
  }

  /**
   * The subscribed events.
   */
  public static function getSubscribedEvents() {
    $events = [];
    $events[KernelEvents::RESPONSE][] = ['onHttpLoginResponse'];
    return $events;
  }

}