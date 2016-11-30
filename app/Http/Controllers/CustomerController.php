<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;

class CustomerController extends Controller
{

    public function __construct(Customer $oModel)
    {
        $this->setModel($oModel);
    }

}
