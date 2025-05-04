<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ServiceResource;
use App\Http\Requests\Api\CreateServiceRequest;
use App\Http\Requests\Api\UpdateServiceRequest;

class ServiceController extends Controller
{
    use ApiResponseTrait;

    public function __construct()
    {
        if (Auth::user()->is_admin != true) {
            abort(403, 'Unauthorized action.');  
        }
    }

    /**
     * Retrieve and return a list of all available services.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $services = Service::where('availability', true)->get();
        return $this->apiResponse('Service retrieved successfully.', 200, ServiceResource::collection($services));
    }

    /**
     * Create a new service using validated input.
     *
     * @param CreateServiceRequest $request Validated service creation data
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateServiceRequest $request): JsonResponse
    {
        $service = Service::create($request->validated());
        return $this->apiResponse('Service created successfully.', 201, new ServiceResource($service));
    }

    /**
     * Display a single service by its ID.
     *
     * @param string $id Service ID
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show(string $id): JsonResponse
    {
        $service = Service::findOrFail($id);
        return $this->apiResponse('Service returned successfully.', 200, new ServiceResource($service));
    }

    /**
     * Update an existing service with validated data.
     *
     * @param UpdateServiceRequest $request Validated update data
     * @param string $id Service ID
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(UpdateServiceRequest $request, string $id): JsonResponse
    {
        $service = Service::findOrFail($id);

        $service->update($request->validated());
        return $this->apiResponse('Service updated successfully.', 200,new ServiceResource($service));
    }

    /**
     * Delete the specified service by its ID.
     *
     * @param string $id Service ID
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function destroy(string $id): JsonResponse
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return $this->apiResponse( 'Service deleted successfully.', 200);
    }
}

