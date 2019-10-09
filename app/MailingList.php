<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailingList extends Model
{
    /**
     * @var string
     */
    protected $table = 'mailing-list';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'email'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
