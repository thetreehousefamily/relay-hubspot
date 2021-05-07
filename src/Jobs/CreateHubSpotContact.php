<?php

namespace TheTreehouse\Relay\HubSpot\Jobs;

use Illuminate\Database\Eloquent\Model;
use TheTreehouse\Relay\HubSpot\HubSpotRelay;

class CreateHubSpotContact extends AbstractJob
{
    /**
     * The contact model
     * 
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $contact;

    public function __construct(Model $contact)
    {
        $this->contact = $contact;
    }

    public function handle(HubSpotRelay $relay)
    {
        // ... blah blah, create HS contact

        $this->contact->{$relay->contactModelColumn()} = '201';
        $this->contact->saveQuietly();
    }
}
