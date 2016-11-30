<?php

namespace App\Observers;

use App\Campaign;
use App\Helpers\CsvHelper;

class CampaignObserver extends BaseObserver
{
    /**
     * Listen to the Campaign created event.
     *
     * @param  Campaign $oCampaign
     * @return void
     */
    public function created(Campaign $oCampaign)
    {
        $this->generateCsv($oCampaign);
    }

    /**
     * Listen to the Campaign updated event.
     *
     * @param  Campaign $oCampaign
     * @return void
     */
    public function updated(Campaign $oCampaign)
    {
        $this->generateCsv($oCampaign);
    }

}