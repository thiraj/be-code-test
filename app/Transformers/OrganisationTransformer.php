<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Organisation;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

/**
 * Class OrganisationTransformer
 * @package App\Transformers
 */
class OrganisationTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'user'
    ];

    /**
     * @param Organisation $organisation
     *
     * @return array
     */
    public function transform(Organisation $organisation): array
    {
        return [
            'id' => $organisation->id,
            'name' => $organisation->name,
            'subscribed' => $organisation->subscribed,
            'trial_end' => Carbon::parse($organisation->trial_end)->unix(),
            'created_at' => Carbon::parse($organisation->created_at)->unix(),
        ];
    }


    /**
     * @param Organisation $organisation
     * @return mixed
     */
    public function includeUser(Organisation $organisation)
    {
        return $this->item($organisation->owner, new UserTransformer());
    }
}
