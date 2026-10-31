<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    // هاد السطر بيحكي للارافيل: روحي على الاتصال الثاني مش الرئيسي!
    protected $connection = 'mysql_logs';

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_name',
        'action',
        'details',
    ];
}
