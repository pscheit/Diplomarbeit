<?php

namespace Entities;

/**
 * Aggregation
 *
 * @Table(name="aggregations")
 * @Entity
 */
class Aggregation
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
     * @var string $closed
     *
     * @Column(name="closed", type="boolean", length=1, nullable=false)
     */
    private $closed;


  public function getClosed() {
    return $this->closed;
  }
}