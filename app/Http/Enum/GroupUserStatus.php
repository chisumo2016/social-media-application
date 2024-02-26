<?php

namespace App\Http\Enum;

enum GroupUserStatus: string
{
   case PENDING = 'pending';
   case  APPROVED = 'approved';
}
