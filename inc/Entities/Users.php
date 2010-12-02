<?php

namespace Entities;

/**
 * Users
 *
 * @Table(name="users")
 * @Entity
 */
class Users
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
     * @var string $email
     *
     * @Column(name="email", type="string", length=127, nullable=false)
     */
    private $email;

    /**
     * @var string $username
     *
     * @Column(name="username", type="string", length=32, nullable=false)
     */
    private $username;

    /**
     * @var string $name
     *
     * @Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string $lastname
     *
     * @Column(name="lastname", type="string", length=255, nullable=false)
     */
    private $lastname;

    /**
     * @var string $password
     *
     * @Column(name="password", type="string", length=50, nullable=false)
     */
    private $password;

    /**
     * @var integer $logins
     *
     * @Column(name="logins", type="integer", nullable=false)
     */
    private $logins;

    /**
     * @var integer $lastLogin
     *
     * @Column(name="last_login", type="integer", nullable=true)
     */
    private $lastLogin;

    /**
     * @var string $freelancer
     *
     * @Column(name="freelancer", type="string", length=1, nullable=false)
     */
    private $freelancer;

    /**
     * @var boolean $emailweekly
     *
     * @Column(name="emailweekly", type="boolean", nullable=false)
     */
    private $emailweekly;

}