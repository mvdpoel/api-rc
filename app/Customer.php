<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Customer
 * @package App
 *
 * @property int id
 * @property string title
 * @property string description
 * @property float budget
 * @property bool reminder
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Customer extends Model
{
    /**
     * @var string
     */
    protected $table = 'customers';
    protected $dateFormat = 'U';
    protected $dates = [];

    public function getDates() {return [];}
}
