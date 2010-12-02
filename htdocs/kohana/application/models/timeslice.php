<?php

class Timeslice_Model extends ORM {


	protected $has_and_belongs_to_many = array('projects');
	protected $belongs_to = array('aggregation', 'user');

	/**
	 * Liefert timeslices für einen user die als Wert für end NULL haben. Ein Vergleich auf NULL is imo mit Kohana nur zu Fuß möglich.
	 */
	public function getRunningSlice($user_id) {

		$res = $this->db->query(
		"SELECT `timeslices`.*
		FROM `timeslices`
		WHERE `user_id` = ".$user_id."
		AND `end` IS NULL
		ORDER BY `timeslices`.`id` ASC
		LIMIT 0, 1"
		);

		$obj = new ORM_Iterator($this, $res);

		return $obj;

	}

	public function getMostRecentStoppedSlice($user_id) {

		$res = $this->db->query(
			"SELECT `timeslices`.*
			FROM `timeslices`
			WHERE `user_id` = ".$user_id."
			AND `end` IS NOT NULL
			ORDER BY `timeslices`.`start` DESC
			LIMIT 0, 1"
			);

			$obj = new ORM_Iterator($this, $res);
				
				
			return $obj;

	}
	
	/**
	 * returns all slices belonging to last aggregation which is NOT closed
	 * used in today's listing
	 */
	public function getNonAggregatedSlices($user_id) {

		$most_recent_slice = $this->getMostRecentStoppedSlice($user_id);

		if($most_recent_slice->valid()) {
				
			// if aggregation of most recent slice is not closed, get all slices with same aggregation
			if (!$most_recent_slice->current()->aggregation->closed) {

				$res = $this->db->query(
				"SELECT `timeslices`.*
				FROM `timeslices`
				WHERE `user_id` = ".$user_id."
				AND `aggregation_id` = ".$most_recent_slice->current()->aggregation_id."
				AND `end` IS NOT NULL
				ORDER BY `timeslices`.`start` ASC"
				);

				return new ORM_Iterator($this, $res);

			}

		}

		return false;
	}

	
	
	
	/**
	 * return an array holding data about a day (feierabend to feierabend)
	 */
	public function getAggregationData($aggregation_id) {

		$slices = $this->where(array('aggregation_id'=>$aggregation_id))->orderby(array('start'=>'ASC'))->find_all();

		$data = array('time_sum'=>0, 'id'=>$aggregation_id, 'start'=>NULL, 'end'=>NULL );

		$first = true;

		//$it = new ArrayIterator($array);

		while ($slices->valid()) {

			$slice = $slices->current();

			$slices->next();

			//erster?
			if ($first) {
				$data['start'] = $slice->start;
				$first = false;
			}

			// letzter?
			if (!$slices->valid()) {
				$data['end'] = $slice->end;
				$data['user_id'] = $slice->user_id;
			}

			$data['time_sum'] += (strtotime($slice->end) - strtotime($slice->start));

		}
		
		$data['time_sum'] = $data['time_sum'];// - ($data['time_sum']%(60*15));
		
		return $data;

	}


}
?>
