<?php
class Project_Model extends ORM {
	protected $has_and_belongs_to_many = array('aggregations'=>'aggregations_projects');

	protected $belongs_to = array('user','aggregations_project');

  /* ein cache für user informationen
     wird z.B. genutzt, wenn getProjectsTotalHours getProjectTotalHours aufruft, 
     damit getProjectTotalHours nicht x-fach ein neuen user select macht
  */
  public static $cacheUsers = NULL;

  public $seconds = 0;

  /**
   * So wie cacheUsers
   */
  protected static $cacheProjects = NULL;

	/**
	 * Returns an array holding all projects and assigned seconds during certain period of time
	 *
   * psc: diese methode könnte auch static sein, weil sie eigentlich genau das tut
	 * @param $timespan
	 * @return unknown_type
	 */
	public function getProjectsTotalHours($timespan = NULL, $user_id = 0) {
    ini_set('max_execution_time',0);

		$projects_data = array();

    /* wir erstellen den usercache der dann auch fon getProjectTotalHours benutzt werden kann */
    if (!isset(self::$cacheUsers)) {
      foreach (ORM::factory('user')->find_all() as $user) {
        self::$cacheUsers[$user->id] = $user;
      }
    }

    self::$cacheProjects = ORM::factory('project')->find_all();

		foreach (self::$cacheProjects as $project) {

			array_push($projects_data, $project->getProjectTotalHours($timespan, $user_id));
		}

		usort ($projects_data, create_function('$a, $b', 'if ($a[\'seconds\'] == $b[\'seconds\']) return 0; return $a[\'seconds\'] > $b[\'seconds\'] ? -1 : 1; '));

    /* 
     * foreach (Database::$benchmarks as $entry) {
     *   print $entry['query']."<br />\n";
     * }
     */

		return $projects_data;

	}


	/**
	 * Returns the total seconds aggregated on a project during a certain period of time
	 *
	 *
   * psc: project_id macht hier keinen sinn, wir können ja $this nehmen
	 * @param $timespan
	 * @param $orderby
	 * @return unknown_type
	 */
	public function getProjectTotalHours($timespan = NULL, $user_id = 0){

		$project_data = array();

		$pivot = ORM::factory('aggregations_project')
        ->with('aggregation')
        ->with('aggregation:timeslice')
        ->where(array('project_id' => $this->id))
        ->groupby('aggregation:id');

    /* optimierung für nur einen user */
    if ($user_id != 0)
      $pivot->where(array('user_id' => $user_id));

    $pivot = $pivot->find_all();

		$project_data['name'] = $this->name;
		$project_data['seconds'] = 0;
		$project_data['users'] = array();

    /* wir erstellen den usercache, wenn es den noch nicht gibt */
    if (!isset(self::$cacheUsers)) {
      foreach (ORM::factory('user')->find_all() as $user) {
        self::$cacheUsers[$user->id] = $user;
      }
    }

		foreach ($pivot as $relation) {

			$aggregation = $relation->aggregation;

      // hier wird nur ein timeslice genommen (hat frederik auch schon gemacht)
      $timeslice = $aggregation->timeslice;

			// if the timeslices of this aggregation are among the selected timespan
			if(
        $timeslice->start >=  $timespan['start']
        && $timeslice->end <= $timespan['end'] . ' 23:59:59'
			) {

        $project_data['seconds'] += $relation->seconds;

        // user einzeln listen
        if ($user_id == 0) {
					
          if( !isset ($project_data['users'][$timeslice->user_id]) ){
						$project_data['users'][$timeslice->user_id] = array('username'=>self::$cacheUsers[ $timeslice->user_id ]->username, 'seconds'=>0);
							
					}
					$project_data['users'][$timeslice->user_id]['seconds'] += $relation->seconds;
				}
			}
		}

		if( count($project_data['users']) > 0) {
			usort ($project_data['users'], create_function('$a, $b', 'if ($a[\'seconds\'] == $b[\'seconds\']) return 0; return $a[\'seconds\'] > $b[\'seconds\'] ? -1 : 1; '));
		}
		return $project_data;
	}




	/**
	 * Returns the total seconds aggregated on a project during a certain period of time
	 *
	 *
	 * @param $project_id
	 * @param $timespan
	 * @param $orderby
	 * @return unknown_type
	 */
	public function getProjectTotalHoursNonPerformance($project_id = 0, $timespan = NULL, $user_id = 0){

		$project_data = array();

		$pivot = ORM::factory('aggregations_project')->where(array('project_id' => $project_id))->find_all();

		$project = ORM::factory('project', $project_id);

		$project_data['name'] = $project->name;

		$project_data['seconds'] = 0;

		$project_data['users'] = array();

		foreach ($pivot as $relation) {

			$aggregation = ORM::factory('aggregation', $relation->aggregation_id);

			$timeslices = $aggregation->timeslices;

			// if the timeslices of this aggregation are among the selected timespan
			if(
			$timeslices->current()->start >=  $timespan['start']
			&& $timeslices->current()->end <= $timespan['end'] . ' 23:59:59'
			) {

				// filter by users too?
				if($user_id != 0) {


					if($timeslices->current()->user_id == $user_id) {
						$project_data['seconds'] += $relation->seconds;
						//$project_data['users'] = NULL;

					}


					// include all users
				} else {

					$project_data['seconds'] += $relation->seconds;

					if( !isset ($project_data['users'][$timeslices->current()->user_id]) ){
						$project_data['users'][$timeslices->current()->user_id] = array('username'=>ORM::factory('user', $timeslices->current()->user_id)->username, 'seconds'=>0);
							
					}
					$project_data['users'][$timeslices->current()->user_id]['seconds'] += $relation->seconds;
				}
			}
		}

		if( count($project_data['users']) > 0) {
			usort ($project_data['users'], create_function('$a, $b', 'if ($a[\'seconds\'] == $b[\'seconds\']) return 0; return $a[\'seconds\'] > $b[\'seconds\'] ? -1 : 1; '));
		}
		return $project_data;
	}


  public function setContingent($string) {
    if (preg_match('/([0-9]+)(:([0-9]+))?/u',$string,$match) > 0) {
      $pt = 0;
      $ps = 0;
      if (count($match) == 4) {
        $pt = (int) $match[1];
        $ps = (int) $match[3];
      } else {
        $pt = (int) $match[1];
      }

      $this->contingent = $pt * 8 + $ps;
      return $this;
    }
    
    throw new Exception('Fehlerhafter String bei setContingent');
  }

  /**
   * Gibt das Kontingent in Sekunden zurück
   * 
   * @param string $format s für sekunden pts für personentage/stunden-string wie er bei setContingent benutzt wird
   * @return int
   */
  public function getContingent($format = 's') {
    if ($format == 'pts') {
      $string = '00:0';
      
      if ($this->contingent > 0) {
        $string = floor($this->contingent / 8);
        $string .= ':'.($this->contingent%8);
        
      }
      
      return $string;
    } else {
      return $this->contingent * 60 * 60;
    }
  }


  public function __toString() {
    return $this->name;
  }

  public function getName() {
   return $this->name;
  }

  public function getListvisible() {
   return $this->listvisible;
  }
}
?>
