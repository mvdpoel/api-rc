<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Campaign
 * @package App
 *
 * @property int id
 * @property string title
 * @property string description
 * @property float budget
 * @property bool reminder
 * @property string start_dt
 * @property string end_dt
 * @property string created_at
 * @property string updated_at
 */
class Campaign extends Model
{
    /**
     * @var string
     */
    protected $table = 'campaigns';
    protected $dateFormat = 'U';
    protected $dates = [];

    protected $fillable = [
        'title',
        'description',
        'budget',
        'start_dt',
        'end_dt',
        'reminder',
    ];

    public function getDates() {return [];}

}
