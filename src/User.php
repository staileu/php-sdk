<?php

namespace STAILEUAccounts;

/**
 * Represent a STAIL.EU user
 *
 * @package STAILEUAccounts
 */
class User {
    public $id;

    /**
     * The username of the user, this information is always not null
     *
     * @var string
     */
    public $username = '';

    /**
     * The email of the user in common RFC 5322 format
     * If null, it means that the user don't let the app access it via `read-email`
     *
     * @var null|string
     */
    public $email = NULL;

    /**
     * The birthday of the user
     * If null, it means that the user don't have provided a birthday or don't let the app to access it via `read-birthday`
     *
     * @var null|string
     */
    public $birthday = NULL;

    /**
     * The first name of the user
     * If null, it means that the user don't have provided a first name or don't let the app to access it via `read-real-name`
     *
     * @var null|string
     */
    public $firstName = NULL;

    /**
     * The last name of the user
     * If null, it means that the user don't have provided a last name or don't let the app to access it via `read-real-name`
     *
     * @var null|string
     */
    public $lastName = NULL;

    /**
     * The API Url of the avatar
     *
     * @var string
     */
    public $avatarUrl = NULL;
}
