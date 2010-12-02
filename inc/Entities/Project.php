<?php

namespace Entities;

/**
 * Projects
 *
 * @Table(name="projects")
 * @Entity
 */
class Project
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
     * @Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var integer $contingent
     *
     * @Column(name="contingent", type="integer", nullable=true)
     */
    private $contingent;

    /**
     * @var boolean $listvisible
     *
     * @Column(name="listvisible", type="boolean", nullable=false)
     */
    private $listvisible;


  public function getName() {    return $this->name;   }
  public function getListvisible() { return $this->listvisible; }
  
}