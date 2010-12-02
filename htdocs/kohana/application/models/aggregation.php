<?php
class Aggregation_Model extends ORM {
  protected $has_many = array('projects'=>'aggregations_projects', 'timeslices');
  protected $belongs_to = array('timeslice', 'aggregations_project', 'project');

  protected $cacheStart;
  protected $cacheEnd;
  protected $cacheTimeSum;

  /**
   * 
   * werden verwendet um start / end / timesum zu berechnen
   * @var array
   */
  public $cacheTimeslices = NULL;

  /**
   * 
   * @var array
   */
  public $cachePivotProjects = array();
  
  /**
   * 
   * @var array
   */
  public $cacheProjects = array();

	public function getUnasignedAggregations($user_id){

		$slices = $this->timeslice_model->getPastSlices($user_id);

		$curr_aggregation_id = NULL;

		$open_aggregations = array();

		$last_slice = NULL;

		foreach ($slices as $slice) {

			// aggregation picked for the first time and no project assigned
			if($slice->aggregation_id != $curr_aggregation_id && $slice->aggregation->projects->count() == 0) {
					
				array_push($open_aggregations, $this->timeslice_model->getAggregationData($slice->aggregation_id));

				$curr_aggregation_id = $slice->aggregation_id;

			}

		}

		return $open_aggregations;

	}

	/**
	 * used in "past" listing
	 */
	public function getUserAggregationsData($user_id = 0, $timespan = NULL, $orderby = array()){

		$where_arr = array();

		if( $user_id != 0 ) {

			$where_arr['user_id'] = $user_id;

		}

		if( !empty($timespan) ) {


			$where_arr = array_merge($where_arr, $timespan);

		}
		if( !empty($orderby) ) {


			$orderby = array_merge(array('start'=>'ASC'),$orderby);  // hier vertauscht, damit man start auch überschreiben kann (abwärtskompatibel)

		}

    $where_arr['aggregation.closed'] = '1';
		$slices = ORM::factory('timeslice')->with('aggregation')->where($where_arr)->orderby($orderby)->find_all();

		//var_dump($slices);
		
		$curr_aggregation_id = NULL;

		$open_aggregations = array();


		foreach ($slices as $slice) {

			// aggregation picked for the first time and closed
			if($slice->aggregation_id != $curr_aggregation_id) { // closed steht jetzt oben in der where bedingung
					
				array_push($open_aggregations, ORM::factory('timeslice')->getAggregationData($slice->aggregation_id));

				$curr_aggregation_id = $slice->aggregation_id;

			}

		}


		return $open_aggregations;

	}

	/* used in report to check wether an aggregation does include a certain project*/
	public function doesInclude($aggre_id, $project_id) {

		foreach (ORM::factory('aggregation', $aggre_id)->aggregations_projects as $pivot) {

			if($pivot->project_id == $project_id) {
				return TRUE;
			}
		}

		return FALSE;
			
	}


  public function getStart() {
    if (!isset($this->cacheStart))
      $this->loadCache();

    return $this->cacheStart;
  }

  public function getEnd() {
    if (!isset($this->cacheEnd))
      $this->loadCache();

    return $this->cacheEnd;
  }

  public function getTimeSum() {
    if (!isset($this->cacheTimeSum))
      $this->loadCache();

    return $this->cacheTimeSum;
  }


  /**
	 * Initialisiert
   * 
   * start / end / timesum für das Objekt
	 */
	protected function loadCache() {

    if (!isset($this->cacheTimeslices)) {
      $this->cacheTimeslices = ORM::factory('timeslice')->where(array('aggregation_id'=>$this->id))->orderby(array('start'=>'ASC'))->find_all()->as_array();
    }

		$first = true;
    $this->cacheTimeSum = 0;

    $slices = new ArrayIterator($this->cacheTimeslices);

		while ($slices->valid()) {

			$slice = $slices->current();

			$slices->next();

			//erster?
			if ($first) {
				$this->cacheStart = $slice->start;
				$first = false;
			}

			// letzter?
			if (!$slices->valid()) {
				$this->cacheEnd = $slice->end;
			}

			$this->cacheTimeSum += (strtotime($slice->end) - strtotime($slice->start));

		}
    return $this;
	}


	public function getClosed() {
		return $this->closed;
	}
}
?>