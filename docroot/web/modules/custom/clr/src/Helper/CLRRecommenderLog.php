<?php

namespace Drupal\clr\Helper;

use Drupal\recommender_log\Entity\RecommenderLog;


class CLRRecommenderLog {
  public function get() {

    $rlids = \Drupal::entityQuery('recommender_log')->sort('field_timestamp', 'DESC')->execute();
    $recommenderLogs = RecommenderLog::loadMultiple($rlids);


    foreach ($recommenderLogs as $recommenderLog) {
       
        $data[] = [
            'id' => intval($recommenderLog->id()),
            'timestamp' => intval($recommenderLog->get('field_timestamp')->value),
            'event' => $recommenderLog->get('field_event')->value,
            'user_id' => intval($recommenderLog->get('field_user_id')->value),
            'conceptmap_id' => intval($recommenderLog->get('field_conceptmap_id')->value),
            'conceptmap_name' => $recommenderLog->get('field_conceptmap_name')->value,
            'conceptmap_tags' => !is_null($recommenderLog->get('field_conceptmap_tags')->value) ? explode(',', $recommenderLog->get('field_conceptmap_tags')->value) : [],
            'concepts' => !is_null($recommenderLog->get('field_concepts')->value) ? json_decode($recommenderLog->get('field_concepts')->value) : [],
            'recommender_concept' => !is_null($recommenderLog->get('field_recommender_concept')->value) ? $recommenderLog->get('field_recommender_concept')->value : "",
        ]; 
    }

    $groupedData = $this->groupArray('conceptmap_id', $data);

    foreach ($groupedData as $foo) {
        $bar[] = $foo[0];
    }
    
    $response = $bar;
    return $response;
  }


  public function getConceptMapData($concept_map_id) {
    //Get all concept map data
    $all_concept_map_data = $this->get();

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