<?php

namespace Drupal\epub\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class EPubController.
 */
class EPubController extends ControllerBase {

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function hello($name) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: hello with parameter(s): $name'),
    ];
  }

}
