<?php

namespace Drupal\clr\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\recommender_log\Entity\RecommenderLog;


/**
 * Provided a CLR Recommender
 * 
 * @RestResource(
 *   id = "clr_recommender",
 *   label = @Translation("CLR Recommender"),
 *   uri_paths = {
 *     "canonical" = "/clr/clr_recommender"
 *   }
 * )
 */
class CLRRecommenderLog extends ResourceBase {
/**
   * Responds to entity GET requests
   * @return \Drupal\Rest\ResourceResponse
   */
  public function get() {

    $database = \Drupal::database();
    $query = $database->query("SELECT * FROM recommender_log");
    $result = $query->fetchAll();

    $rlids = \Drupal::entityQuery('recommender_log')->sort('field_timestamp', 'DESC')->execute();
    $recommenderLogs = RecommenderLog::loadMultiple($rlids);


    foreach ($recommenderLogs as $recommenderLog) {
        $data[] = [
            'id' => $recommenderLog->id(),
            'timestamp' => $recommenderLog->get('field_timestamp')->value,
            'event' => $recommenderLog->get('field_event')->value,
            'conceptmap_id' => $recommenderLog->get('field_conceptmap_id')->value,
            'conceptmap_name' => $recommenderLog->get('field_conceptmap_name')->value,
            'concepts' => $recommenderLog->get('field_concepts')->value,

        ];
    }

    $groupedData = $this->groupArray('conceptmap_id', $data);
    
    $response = new ResourceResponse($groupedData);
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