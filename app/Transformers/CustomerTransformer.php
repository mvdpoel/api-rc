<?php namespace App;

use League\Fractal\TransformerAbstract;

class CustomerTransformer extends TransformerAbstract {

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [

    ];

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
    ];

    /**
     * @param Customer $oCustomer
     *
     * @return array
     */
    public function transform(Customer $oCustomer)
    {
        // This is just to illustrate how transformers work
        $aOutput = $oCustomer->attributesToArray();

        $aOutput['links'] = [
            'rel' => 'self',
            'uri' => '/api/Customers/' . $oCustomer->id
        ];

        return $aOutput;

    }

}
