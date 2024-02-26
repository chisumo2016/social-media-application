<?php

namespace App\Http\Enum;

enum GroupUserRole: string
{
    case ADMIN = 'admin';
    case USER  = 'user';
}
