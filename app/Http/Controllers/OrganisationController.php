<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganisation;
use App\Services\OrganisationService;
use Illuminate\Http\JsonResponse;

/**
 * Class OrganisationController
 * @package App\Http\Controllers
 */
class OrganisationController extends ApiController
{
    /**
     * @param StoreOrganisation $request
     * @param OrganisationService $service
     * @return JsonResponse
     */
    public function store(StoreOrganisation $request, OrganisationService $service): JsonResponse
    {
        $requestData = $request->all();
        $requestData['request_user'] = $request->user();

        $organisation = $service->createOrganisation($requestData);

        return $this
            ->transformItem('organisation', $organisation, ['user'])
            ->respond();
    }

    /**
     * @param OrganisationService $service
     * @return JsonResponse
     */
    public function listAll(OrganisationService $service): JsonResponse
    {
        $filters['status'] = request()->get('filter');
        $organisations = $service->getOrganisations($filters);
        return $this
            ->transformCollection(
                'organisation',
                $organisations,
                ['user']
            )
            ->respond();
    }
}
