<?php

namespace Drupal\recommender_log\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the recommender log entity edit forms.
 */
class RecommenderLogForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->getEntity();
    $result = $entity->save();
    $link = $entity->toLink($this->t('View'))->toRenderable();

    $message_arguments = ['%label' => $this->entity->label()];
    $logger_arguments = $message_arguments + ['link' => render($link)];

    if ($result == SAVED_NEW) {
      $this->messenger()->addStatus($this->t('New recommender log %label has been created.', $message_arguments));
      $this->logger('recommender_log')->notice('Created new recommender log %label', $logger_arguments);
    }
    else {
      $this->messenger()->addStatus($this->t('The recommender log %label has been updated.', $message_arguments));
      $this->logger('recommender_log')->notice('Updated new recommender log %label.', $logger_arguments);
    }

    $form_state->setRedirect('entity.recommender_log.canonical', ['recommender_log' => $entity->id()]);
  }

}
