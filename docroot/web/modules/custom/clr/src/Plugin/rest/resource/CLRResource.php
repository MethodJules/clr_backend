<?php

namespace Drupal\clr\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;


/**
 * Provided a CLR Resource
 * 
 * @RestResource(
 *   id = "clr_resource",
 *   label = @Translation("CLR Resource"),
 *   uri_paths = {
 *     "canonical" = "/clr/clr_resource/{role}"
 *   }
 * )
 */
class CLRResource extends ResourceBase {
  
  /**
   * Responds to entity GET requests
   * @return \Drupal\Rest\ResourceResponse
   */
  public function get($role) {

      //Get all users
      $userStorage = \Drupal::entityTypeManager()->getStorage('user');
      $query = $userStorage->getQuery();
      $uids = $query
        ->condition('status', '1')
        ->condition('roles', $role)
        ->execute();
      $users = $userStorage->loadMultiple($uids);
      $response = [
          "users" => $users,
          ];
      return new ResourceResponse($response);

  }
}