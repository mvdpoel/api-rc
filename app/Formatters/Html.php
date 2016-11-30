<?php namespace App\Formatters;

use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Arrayable;
use Dingo\Api\Http\Response\Format\Format;

class Html extends Format
{
    /**
     * Format an Eloquent model.
     *
     * @param \Illuminate\Database\Eloquent\Model $oModel
     *
     * @return string
     */
    public function formatEloquentModel($oModel)
    {
        return $this->asHtml($oModel->toArray());
    }

    /**
     * Format an Eloquent collection.
     *
     * @param \Illuminate\Database\Eloquent\Collection $oCollection
     *
     * @return string
     */
    public function formatEloquentCollection($oCollection)
    {
        if ($oCollection->isEmpty()) {
            return $this->asHtml([]);
        }

        return $this->asHtml($oCollection->toArray());
    }

    /**
     * Format an array or instance implementing Arrayable.
     *
     * @param array|\Illuminate\Contracts\Support\Arrayable $aData
     *
     * @return string
     */
    public function formatArray($aData)
    {
        $aData = $this->morphToArray($aData);

        array_walk_recursive($aData, function (&$mValue) {
            $mValue = $this->morphToArray($mValue);
        });

        return $this->asHtml($aData);
    }

    /**
     * Get the response content type.
     *
     * @return string
     */
    public function getContentType()
    {
        return 'text/html';
    }

    /**
     * Morph a value to an array.
     *
     * @param array|\Illuminate\Contracts\Support\Arrayable $mValue
     *
     * @return array
     */
    protected function morphToArray($mValue)
    {
        return $mValue instanceof Arrayable ? $mValue->toArray() : $mValue;
    }

    /**
     * Encode the content to its Html representation.
     *
     * @param array $aData
     * @return string
     */
    protected function asHtml(array $aData)
    {
        $sResponse = '<ul>';
        foreach ($aData as $sKey => $mVal) {

            $sResponse .= '<li>';
            $sResponse .= (!is_array($mVal)) ?
                $sKey . ': ' . $mVal :
                $this->asHtml($mVal);
            $sResponse .= '</li>';
        }
        $sResponse .= '</ul>';
        return $sResponse;

    }
}
