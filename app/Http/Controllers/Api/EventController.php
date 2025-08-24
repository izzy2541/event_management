<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\EventResource;


class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Event::query();
        $relations = ['user', 'attendees', 'attendees.user'];

        foreach ($relations as $relation) {
            //when first argument passed to when is true, it will run second argument
            $query->when(
                $this->shouldIncludeRelation($relation),
                fn($q) => $q->with($relation)
            );
        }

        // return \App\Models\Event::all();
        //loads all events together with the user relationship
        return EventResource::collection(
            $query->latest()->paginate()
            // Event::with('user')->paginate()
        );
    }

    protected function shouldIncludeRelation(string $relation): bool
    {
        // Get the 'include' query parameter from the request
        // Example: ?include=attendees,organiser
        $include = request()->query('include');

        // If no include query string is provided, don't include anything
        if (!$include) {
            return false;
        }

        // Convert the comma-separated string into an array of relations
        // 1. explode(',', $include) → splits the string into an array
        //    e.g. "attendees, organiser" → ["attendees", " organiser"]
        // 2. array_map('trim', ...) → removes extra spaces around each item
        //    → ["attendees", "organiser"]
        $relations = array_map('trim', explode(',', $include));

        // Check if the requested relation exists in that array
        // Returns true if the relation should be included
        return in_array($relation, $relations);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // Gate::authorize('create', Event::class);
        $event = Event::create([
            ...$request->validate([
                //value -> list of all validation restraints you want applied to that value
                'name' => 'required|string|max:25',
                'description' => 'nullable|string',
                'start_time'=> 'required|date',
                'end_time' => 'required|date|after:start_time'
            ]),
            'user_id' => 1
        ]);
        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     */
    //store is responsible for creating a new event
    public function show( Event $event)
    {
        // $event = Event::create([
        //     ...$request->validate([
        //         //value -> list of all validation restraints you want applied to that value
        //         'name' => 'required|string|max:25',
        //         'description' => 'nullable|string',
        //         'start_time'=> 'required|date',
        //         'end_time' => 'required|date|after:start_time'
        //     ]),
        // ]);
        $event->load('user', 'attendees');
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $event->update(
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time'
            ])
        );

        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response('', status: 204);
    }
}




