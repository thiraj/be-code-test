<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\OrganisationCreated;
use App\Organisation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

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
        $organisation = new Organisation();
        $organisation->name = $attributes['name'] ?? '';
        $organisation->owner_user_id = $attributes['request_user']->id ?? '';
        $organisation->subscribed = 0;
        $organisation->trial_end = Carbon::now()->addDays(30)->toDateTimeString();
        $organisation->save();

        Mail::to($attributes['request_user'])->send(new OrganisationCreated($organisation));

        return $organisation;
    }

    public function getOrganisations(array $filters = [])
    {
        $statusArr = ['subbed', 'trial'];

        return Organisation::where(
            function ($organisation) use ($filters, $statusArr) {
                if (!empty($filters['status']) && in_array($filters['status'], $statusArr)) {
                    if ($filters['status'] == 'subbed') {
                        $organisation->where('subscribed', 1);
                    }

                    if ($filters['status'] == 'trial') {
                        $organisation->where('subscribed', 0)->whereDate('trial_end', '>=', Carbon::now()->toDateTimeString());
                    }
                }
            }
        )->get();
    }
}
