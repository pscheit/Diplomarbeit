<?php

namespace Entities;

/**
 * Timeslices
 *
 * @Table(name="timeslices")
 * @Entity
 */
class Timeslices
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
     * @var integer $userId
     *
     * @Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var integer $aggregationId
     *
     * @Column(name="aggregation_id", type="integer", nullable=false)
     */
    private $aggregationId;

    /**
     * @var datetime $start
     *
     * @Column(name="start", type="datetime", nullable=false)
     */
    private $start;

    /**
     * @var datetime $end
     *
     * @Column(name="end", type="datetime", nullable=true)
     */
    private $end;

}