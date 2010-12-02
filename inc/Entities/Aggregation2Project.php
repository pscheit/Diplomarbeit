<?php

namespace Entities;

/**
 * AggregationsProjects
 *
 * @Table(name="aggregations_projects")
 * @Entity
 */
class Aggregation2Project
{
    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Project")
     * @JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;
    
    /**
     * @ManyToOne(targetEntity="Aggregation")
     * @JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $aggregation;

    /**
     * @var integer $share
     *
     * @Column(name="share", type="integer", nullable=false)
     */
    private $share;

    /**
     * @var integer $seconds
     *
     * @Column(name="seconds", type="integer", nullable=false)
     */
    private $seconds;


  public function getProject() {
    return $this->project;
  }


  public function getAggregation() {
    return $this->aggregation;
  }
  
  public function getSeconds() { return $this->seconds; }
  public function getShare() { return $this->share; }
}