<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Dingo\Api\Routing\Helpers;

use Validator;

use App\Helpers\CsvHelper;
use League\Fractal\TransformerAbstract;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Helpers;

    /**
     * @var Model
     */
    protected $oModel;

    protected function setModel(Model $oModel)
    {
        $this->oModel = $oModel;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $oRequest
     * @Get("/{?page, limit}")
     * @Versions({"v1"})
     * @Parameters(
     *      @Parameter("page", description="The page of results to view.", default=1),
     *      @Parameter("limit", description="The amount of results per page.", default=50),
     * )
     * @return \Illuminate\Http\Response
     */
    public function index(Request $oRequest)
    {
        $oCollection = $this->oModel->paginate((int) $oRequest->input('limit', 10));
        return $this->response()->paginator($oCollection, $this->getTransformer(class_basename($this->oModel)));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        if (!is_numeric($id)) $this->response()->errorBadRequest('$id should be numeric');

        $sCsvFilename = CsvHelper::getCsvFilename($id, class_basename($this->oModel));
        if (!file_exists($sCsvFilename)) $this->response()->errorNotFound();

        $rCsvFile = fopen($sCsvFilename, 'r');

        $aHeaders = fgetcsv($rCsvFile);
        $aValues = fgetcsv($rCsvFile);
        $this->oModel->setAttribute('id', $id);
        $oResponse = $this->response()->item(
            $this->oModel->fill(array_combine($aHeaders, $aValues)),
            $this->getTransformer(class_basename($this->oModel))
        );
        fclose($rCsvFile);

        return $oResponse;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $oRequest
     * @return \Illuminate\Http\Response
     */
    public function store(Request $oRequest)
    {
        /** @var \Illuminate\Validation\Validator $oValidator */
        $oValidator = Validator::make(
            $oRequest->only($this->oModel->getFillable()),
            array_combine(
                $this->oModel->getFillable(),
                array_fill(
                    0,
                    count($this->oModel->getFillable()),
                    'required'
                )
            ));

        if ($oValidator->fails()) {
            $this->response()->error($oValidator->errors()->toJson(), 400);
        }

        try {

            // Save model to DB and trigger the model created observer to create a csv file
            $oModel = $this->oModel->create($oRequest->only($this->oModel->getFillable()));

            return $this->response()->created(
            // Return location of csv download in location response header
                app('Dingo\Api\Routing\UrlGenerator')
                    ->version('v1')
                    ->route(Str::plural(Str::lower(class_basename($this->oModel))), [$oModel->id]),
                // Output csv file body in response
                file_get_contents(
                    CsvHelper::getCsvFilename($oModel->id, class_basename($this->oModel))
                )
            );

        } catch (\Exception $e) {

            $this->response()->error($e->getMessage(), 500);
        }

    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function csv($id)
    {
        if (!is_numeric($id)) $this->response()->errorBadRequest('$id should be numeric');

        $sCsvFilename = CsvHelper::getCsvFilename($id, class_basename($this->oModel));
        if (!file_exists($sCsvFilename)) $this->response()->errorNotFound();

        return response()->make(file_get_contents($sCsvFilename), 200, [
            'Content-Type' 		=> 'application/csv',
            'Content-Description' 	=> 'File Transfer',
	    'Content-Disposition'	=> 'attachment; filename=' . basename($sCsvFilename),
            'Pragma' 			=> 'public'
        ]);
    }

    /**
     * @param $sModelName
     * @return TransformerAbstract
     */
    protected function getTransformer($sModelName)
    {
        $sTransformerName = '\App\\' . $sModelName . 'Transformer';
        return new $sTransformerName;
    }

}
