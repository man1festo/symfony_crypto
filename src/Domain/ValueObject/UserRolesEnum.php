<?php

namespace App\Domain\ValueObject;

enum UserRolesEnum: string
{
    case ROLE_ADMIN = 'ROLE_ADMIN';
    case ROLE_USER = 'ROLE_USER';
    case ROLE_APPROVED = 'ROLE_APPROVED';
    case ROLE_QA = 'ROLE_QA';
}
