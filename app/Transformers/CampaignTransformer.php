<?php namespace App;

use League\Fractal\TransformerAbstract;

class CampaignTransformer extends TransformerAbstract {

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
     * @param Campaign $oCampaign
     *
     * @return array
     */
    public function transform(Campaign $oCampaign)
    {
        // This is just to illustrate how transformers work
        $aOutput = $oCampaign->attributesToArray();

        $aOutput['links'] = [
            'rel' => 'self',
            'uri' => '/api/campaigns/' . $aOutput['id']
        ];

        return $aOutput;

    }

}
