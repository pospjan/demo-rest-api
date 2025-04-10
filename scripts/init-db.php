#!/usr/bin/env php
<?php

declare(strict_types=1);

use App\Application\Dto\CreateUserDto;
use App\Application\Facade\AccessTokenFacade;
use App\Application\Facade\UserFacade;
use App\Domain\User\Role;

require __DIR__ . '/../vendor/autoload.php';


$bootstrap = new App\Bootstrap;
$container = $bootstrap->bootCli();

/** @var \Nette\Database\Connection $db */
$db = $container->getByType(\Nette\Database\Connection::class);


$db->query('PRAGMA foreign_keys = ON;');

$db->query(<<<SQL
    CREATE TABLE IF NOT EXISTS user (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password_hash TEXT NOT NULL,
        role TEXT NOT NULL
    )
SQL);

$db->query(<<<SQL
    CREATE TABLE IF NOT EXISTS article (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        content TEXT NOT NULL,
        author_id INT NOT NULL,
        created_at TEXT NOT NULL,
        updated_at TEXT NOT NULL,
        FOREIGN KEY(author_id) REFERENCES user(id)
    )
SQL);

$db->query(<<<SQL
    CREATE TABLE IF NOT EXISTS access_token (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        token TEXT NOT NULL,
        user_id TEXT NOT NULL,
        created_at TEXT NOT NULL,
        expires_at TEXT NOT NULL,
        FOREIGN KEY(user_id) REFERENCES user(id)
    )
SQL);


/**
 * @var UserFacade $userFacade
 */
$userFacade = $container->getByType(UserFacade::class);

$user = $userFacade->createUser(
    new CreateUserDto(
        "Admin",
        "admin@example.com",
        "Admin",
        Role::Admin
    )
);


/**
 * @var AccessTokenFacade $accessTokenFacade
 */
$accessTokenFacade = $container->getByType(AccessTokenFacade::class);

$accessTokenFacade->insertAccessTokenForUser(
    'admin-token',
    $user
);

echo "Database created + admin with an email admin@example.com and password Admin has been created.\n";
