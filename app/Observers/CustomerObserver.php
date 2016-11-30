<?php

namespace App\Observers;

use App\Customer;
use App\Helpers\CsvHelper;

class CustomerObserver extends BaseObserver
{
    /**
     * Listen to the Customer created event.
     *
     * @param  Customer $oCustomer
     * @return void
     */
    public function created(Customer $oCustomer)
    {
        $this->generateCsv($oCustomer);
    }

    /**
     * Listen to the Customer updated event.
     *
     * @param  Customer $oCustomer
     * @return void
     */
    public function updated(Customer $oCustomer)
    {
        $this->generateCsv($oCustomer);
    }

}