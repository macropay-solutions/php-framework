<?php

namespace App\Models;

use MacropaySolutions\Kernel\Auth\Authenticatable;
use MacropaySolutions\Kernel\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use MacropaySolutions\Kernel\Contracts\Auth\Authenticatable as AuthenticatableContract;
use MacropaySolutions\Kernel\Database\Obvious\Factories\HasFactory;
use MacropaySolutions\Kernel\Database\Obvious\Model;
use MacropaySolutions\Framework\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable;
    use Authorizable;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
    ];
}
