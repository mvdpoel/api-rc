<?php namespace App\Formatters;

use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Arrayable;
use Dingo\Api\Http\Response\Format\Format;

class Xml extends Format
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
        $oXml = new \SimpleXMLElement('<response/>');
        return $this->asXml($oModel->toArray(), $oXml);
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
            return $this->asXml([]);
        }

        return $this->asXml($oCollection->toArray(), $oXml);
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
        $oXml = new \SimpleXMLElement('<response/>');
        return $this->asXml($aData, $oXml);
    }

    /**
     * Get the response content type.
     *
     * @return string
     */
    public function getContentType()
    {
        return 'application/xml';
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
     * Encode the content to its XML representation.
     *
     * @param array $aData
     * @param \SimpleXmlElement $oXml
     * @return string
     */
    protected function asXml(array $aData, &$oXml = null)
    {
        if (is_null($oXml)) $oXml = new \SimpleXMLElement('<response/>');
        array_walk($aData, [$this, 'processData'], $oXml);
        return $oXml->asXML();
    }

    /**
     * @param mixed $mValue
     * @param string $sKey
     * @param \SimpleXMLElement $oXml
     * @return mixed
     */
    private function processData($mValue, $sKey, \SimpleXMLElement &$oXml) {

        if( is_numeric($sKey) ) {
            $sKey = 'item-index-' . $sKey;
        }

        if( is_array($mValue)) {

            if (!is_numeric($sKey)) {

                $oSubNode = $oXml->addChild("$sKey");
                $this->asXml($mValue, $oSubNode);

            } else {

                $this->asXml($mValue, $oXml);
            }

        } else {

            $oXml->addChild("$sKey", htmlspecialchars("$mValue"));
        }

    }
}
