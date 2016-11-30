<?php namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\CsvHelper;

class BaseObserver
{
    /**
     * Write the csv file
     *
     * @param Model $oModel
     */
    protected function generateCsv(Model $oModel)
    {
        // Generate CSV file
        $sCsvFilename = CsvHelper::getCsvFilename(
            $oModel->getAttribute('id'),
            class_basename($oModel)
        );

        if (file_exists($sCsvFilename)) unlink($sCsvFilename);

        $rCsvFile = fopen($sCsvFilename, 'w');
        $aData = $oModel->toArray();
        fputcsv($rCsvFile, array_keys($aData));

        // Would be nice to have a Presenter class to handle Carbon date formats
        if (isset($aData['start_dt'])) $aData['start_dt'] = $aData['start_dt']->format('Y-m-d H:i:s');
        if (isset($aData['end_dt'])) $aData['end_dt'] = $aData['end_dt']->format('Y-m-d H:i:s');
        $aData['created_at'] = $aData['created_at']->format('Y-m-d H:i:s');
        $aData['updated_at'] = $aData['updated_at']->format('Y-m-d H:i:s');

        fputcsv($rCsvFile, $aData);
        fclose($rCsvFile);

    }
}