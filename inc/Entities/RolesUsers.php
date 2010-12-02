<?php

namespace Entities;

/**
 * RolesUsers
 *
 * @Table(name="roles_users")
 * @Entity
 */
class RolesUsers
{
    /**
     * @var integer $userId
     *
     * @Column(name="user_id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="NONE")
     */
    private $userId;

    /**
     * @var integer $roleId
     *
     * @Column(name="role_id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="NONE")
     */
    private $roleId;

}