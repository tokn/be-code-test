<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Auth;
use App\Organisation;
use App\Services\OrganisationService;
use App\Mail\OrganisationCreated;
use App\Transformers\OrganisationTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Class OrganisationController
 * @package App\Http\Controllers
 */
class OrganisationController extends ApiController
{
    /**
     * @param OrganisationService $service
     *
     * @return JsonResponse
     */
    public function store(OrganisationService $service): JsonResponse
    {
        $validated = $this->request->validate([
          'name' => 'required',
          'subscribed' => 'required|boolean'
        ]);

        $user = Auth::user();

        /** @var Organisation $organisation */
        $organisation = $service->createOrganisation($this->request->all());

        Mail::to($user)->send(new OrganisationCreated($organisation));

        return $this
            ->transformItem('organisation', $organisation, [$user])
            ->respond();
    }

    public function listAll(OrganisationService $service)
    {
        $filter = $_GET['filter'] ?? 'all';
        $Organisations = Organisation::all();

        if (isset($filter)) {
            if ($filter === 'subbed') {
                $Organisations = $Organisations->where('subscribed', true);
            } else if ($filter === 'trial') {
                $Organisations = $Organisations->where('subscribed', false);
            }
        }

        return fractal()
    		->collection($Organisations)
    		->transformWith(new OrganisationTransformer)
    		->toArray();

    }
}
