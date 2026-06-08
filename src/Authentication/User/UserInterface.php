<?php

declare(strict_types= 1);

namespace Marshal\Authentication\User;

interface UserInterface
{
    public const string USER_CREDENTIAL = "marshal::user-credential";
    public const string USER_NAME = "marshal::user-name";
    public const string USER_PASSWORD = "marshal::user-password";
    public const string USER_ROLES = "marshal::user-roles";
    public const string USER_STATUS = "marshal::user-status";
    public const string SESSION_CLAIM = 'marshal::session';

    public function getCredential(): string;
    public function getRoles(): array;
    public function getTag(): string;
    public function isLoggedIn(): bool;
    public function toArray(): array;
}
