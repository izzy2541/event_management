<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use Illuminate\Http\Request;
use App\Models\Event;


class AttendeeController extends Controller
{

    use CanLoadRelationships;
    /**
     * Display a listing of the resource.
     */

     private array $relations = ['user'];

    public function index(Event $event)
    {
        // Get the attendees related to the current $event
        // ->latest() orders them by the most recently created/updated first
        $attendees = $this->loadRelationships(
            $event->attendees()->latest()
        );

        // Return the attendees wrapped in an AttendeeResource collection
        // paginate() will break the results into pages (default 15 per page unless specified)
        return AttendeeResource::collection(
            $attendees->paginate()
        );
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $attendee = $this->loadRelationships(
            $event->attendees()->create([
                'user_id' => 1
            ])
        );

        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResource(
            $this->loadRelationships($attendee)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $event, Attendee $attendee)
    {
        $attendee->delete();

        return response(status: 204);
    }
}
