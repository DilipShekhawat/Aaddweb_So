<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Request;
use App\Services\DataStreamProcessorService;
use App\Http\Requests\DataStreamProcessorRequest;

class DataStreamProcessorController extends Controller
{
    use ApiResponse;
    private $datStreamProcessService;

    public function __construct(DataStreamProcessorService $datStreamProcessService)
    {
        $this->datStreamProcessService = $datStreamProcessService;
    }
    /**
     * Display a listing of the resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DataStreamProcessorRequest $request)
    {
        $result = $this->datStreamProcessService->stream($request->all());
        return response()->json(['data' => $result]);
    }
}
