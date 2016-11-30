<?php namespace App\Helpers;

use Illuminate\Support\Str;

class CsvHelper {

    /**
     * @param $id
     * @param string $sEntity
     * @return string
     */
    public static function getCsvFilename($id, $sEntity)
    {
        return storage_path('csv') .
            DIRECTORY_SEPARATOR .
            Str::plural(Str::lower($sEntity)) .
            DIRECTORY_SEPARATOR .
            $id .
            '.csv';
    }

}