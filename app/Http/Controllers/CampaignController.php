<?php

namespace App\Http\Controllers;

use App\Campaign;
use Validator;
use Illuminate\Http\Request;
use App\CampaignTransformer;
use App\Helpers\CsvHelper;

class CampaignController extends Controller
{

    public function __construct(Campaign $oModel)
    {
        $this->setModel($oModel);
    }

}
