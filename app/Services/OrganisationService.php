<?php

declare(strict_types=1);

namespace App\Services;

use Auth;
use App\Organisation;
use Carbon\Carbon;

/**
 * Class OrganisationService
 * @package App\Services
 */
class OrganisationService
{
    /**
     * @param array $attributes
     *
     * @return Organisation
     */
    public function createOrganisation(array $attributes): Organisation
    {
        $user = Auth::user();

        $organisation = Organisation::create([
          'name' => $attributes['name'],
          'subscribed' => (bool) $attributes['subscribed'],
          'trial_end' => ($attributes['subscribed'] == 'true') ? null : Carbon::now()->add(30, 'days'),
          'owner_user_id' => $user->id
        ]);

        return $organisation;
    }
}
