<?php

namespace Drupal\clr\Controller;

use Drupal\Core\Controller\ControllerBase;

class CLRController extends ControllerBase {
    public function content() {
        // Get terms from taxonomy
        $vid = 'phase';
        $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
        foreach($terms as $term) {
            $term_data[] = array(
                'id' => $term->tid,
                'name' => $term->name,
                'phasen_id' => $term->weight,
            );
        }
        var_dump($term_data);
        return ['#markup' => 'CLR Test'];
    }


}