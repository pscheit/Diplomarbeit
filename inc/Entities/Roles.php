<?php

namespace Entities;

/**
 * Roles
 *
 * @Table(name="roles")
 * @Entity
 */
class Roles
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
     * @var string $name
     *
     * @Column(name="name", type="string", length=32, nullable=false)
     */
    private $name;

    /**
     * @var string $description
     *
     * @Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var integer $sortnum
     *
     * @Column(name="sortnum", type="integer", nullable=false)
     */
    private $sortnum;

}