<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiResponse;
use App\Http\Requests\GovernorateRequest;
use App\Models\Governorate;
use Illuminate\Http\JsonResponse;
use Throwable;

class GovernorateController extends ApiResponse
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->handleResponse(Governorate::with('cities')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GovernorateRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(GovernorateRequest $request): JsonResponse
    {
        $governorate = new Governorate($request->all());
        if ($governorate->saveOrFail()) {
            return $this->handleResponse($governorate, 'Governorate Created Successfully');
        }
        return $this->handleError('Failed To Create Governorate', ['Failed to create governorate']);
    }

    /**
     * Display the specified resource.
     *
     * @param Governorate $governorate
     * @return JsonResponse
     */
    public function show(Governorate $governorate): JsonResponse
    {
        return $this->handleResponse($governorate->with('cities')->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param GovernorateRequest $request
     * @param Governorate $governorate
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(GovernorateRequest $request, Governorate $governorate): JsonResponse
    {
        if ($governorate->updateOrFail($request->all())) {
            return $this->handleResponse($governorate, 'Governorate Updated Successfully');
        }
        return $this->handleError('Failed to Update Governorate', ['Failed to Update Governorate'], 402);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Governorate $governorate
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(Governorate $governorate): JsonResponse
    {

        if ($governorate->deleteOrFail()) {
            return $this->handleResponse('', 'Governorate Deleted Successfully');
        }
        return $this->handleError('Failed to Delete Governorate', ['Failed to Delete Governorate'], 402);
    }

}
