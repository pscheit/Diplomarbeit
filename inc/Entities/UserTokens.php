<?php

namespace Entities;

/**
 * UserTokens
 *
 * @Table(name="user_tokens")
 * @Entity
 */
class UserTokens
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
     * @var string $userAgent
     *
     * @Column(name="user_agent", type="string", length=40, nullable=false)
     */
    private $userAgent;

    /**
     * @var string $token
     *
     * @Column(name="token", type="string", length=32, nullable=false)
     */
    private $token;

    /**
     * @var integer $created
     *
     * @Column(name="created", type="integer", nullable=false)
     */
    private $created;

    /**
     * @var integer $expires
     *
     * @Column(name="expires", type="integer", nullable=false)
     */
    private $expires;

}