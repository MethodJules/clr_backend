<?php

namespace Drupal\clr\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\clr\Helper\CLRRecommenderLog;
use Symfony\Component\HttpFoundation\JsonResponse;

class CLRController extends ControllerBase {
    public function content() {
        // Get terms from taxonomy
        $vid = 'phase';
        $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
        foreach($terms as $term) {
            $term_data[] = array(
                'id' => $term->tid,
                'name' => $term->name,
                'phasen_id' => $term->weight, // phasen nummer
            );
        }
        // var_dump($term_data);

        // hole Dozententools
        $nids = \Drupal::entityQuery('node')->condition('type','lecturertools')->execute();
        $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);

        foreach($nodes as $node) {
            $terms2 = $node->get('field_phase_term')->referencedEntities();
            foreach($terms2 as $term2) {
                 $termy = $term2;
                 $tid = $termy->tid->value;
            }
            // var_dump($node->title->value);
        }

        // Hole dozenten input dateien
        $nids = \Drupal::entityQuery('node')->condition('type','lecturerfiles')->execute();
        $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);

        foreach($nodes as $node) {
            $file = $node->field_documentid->entity;
            $file_id = $file->id();
        }

        // test 
        $this->createFile('Test', 2694);

        //get phase id
        $phase_id = $this->getPhaseId(10);
        // getLecturerTools By termid
        $nodes = $this->getLecturerTools(10);
        foreach ($nodes as $node){
            $node_type = $node->getType();
        }
        // create tool 
        $x = 2;

        return ['#markup' => 'CLR Test (2964)'];
    }

    public function createTool($title, $phasenid) {
        $tool = \Drupal\node\Entity\Node::create(['type' => 'tools']);
        $tool->title = $title;
        $tool->field_benutzt = ['value' => '0'];
        $tool->field_phasenid = 2694;
        $tool->save();
    }

    public function createFile($title, $phasenid) {
        $tool = \Drupal\node\Entity\Node::create(['type' => 'inputdateien']);
        $tool->title = $title;
        $tool->field_documentid = 354;
        $tool->field_phasenid = $phasenid;
        $tool->save();
    }

    public function getLecturerTools($tid) {
        $nodes =  \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['field_phase_term' => $tid]);
        return $nodes;
    }

    public function getPhaseId($tid) {
        $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tid);
        $termy = $term;
        return $termy->getWeight();
    }

    public function predictConceptMap($concept_map_id) {
        // Just get the data from recommender log
        $clrRecommenderLog = new CLRRecommenderLog();
        $data = $clrRecommenderLog->get();
        // $concept_map_id = 8680;

        $concept_map_data = array_filter($data, function($value) use ($concept_map_id) {
            if($value["conceptmap_id"] == $concept_map_id) {
                return $value;
            };
        });

        $concept_map_data = array_values($concept_map_data);
        $concept_map_data = $concept_map_data;

        // $client =  \Drupal::httpClient();
        //$request = $client->post("http://147.172.178.30:5000/concept_recommender/training/" . "1", ['json' => $data]);
        // $response = json_decode($request->getBody());
        
        return new JsonResponse($concept_map_data[0]);
    }

}