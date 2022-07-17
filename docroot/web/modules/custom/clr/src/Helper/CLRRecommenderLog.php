<?php

namespace Drupal\clr\Helper;

use Drupal\rest\ResourceResponse;
use Drupal\recommender_log\Entity\RecommenderLog;


class CLRRecommenderLog {
/**
   * Responds to entity GET requests
   * @return \Drupal\Rest\ResourceResponse
   */
  public function get() {

    $rlids = \Drupal::entityQuery('recommender_log')->sort('field_timestamp', 'DESC')->execute();
    $recommenderLogs = RecommenderLog::loadMultiple($rlids);


    foreach ($recommenderLogs as $recommenderLog) {
       
        $data[] = [
            'id' => $recommenderLog->id(),
            'timestamp' => $recommenderLog->get('field_timestamp')->value,
            'event' => $recommenderLog->get('field_event')->value,
            'user_id' => $recommenderLog->get('field_user_id')->value,
            'conceptmap_id' => $recommenderLog->get('field_conceptmap_id')->value,
            'conceptmap_name' => $recommenderLog->get('field_conceptmap_name')->value,
            'conceptmap_tags' => $recommenderLog->get('field_conceptmap_tags')->value,
            'concepts' => $recommenderLog->get('field_concepts')->value,
            'recommender_concept' => $recommenderLog->get('field_recommender_concept')->value,
        ]; 
    }

    $groupedData = $this->groupArray('conceptmap_id', $data);

    foreach ($groupedData as $foo) {
        $bar[] = $foo[0];
    }
    
    $response = new ResourceResponse($bar);
    return $response;
  }

  /**
    * This function groups an array by its provided property.
    *
    * @param string $property
    *   The property which will be grouped by.
    * @param array $data
    *   The data that should be grouped.
    */
    public function groupArray($property, array $data) {
        $grouped_array = [];
    
        foreach ($data as $value) {
            if (array_key_exists($property, $value)) {
                $grouped_array[$value[$property]][] = $value;
            }
            else {
                $grouped_array[""][] = $value;
            }
        }
        return $grouped_array;
    }

}