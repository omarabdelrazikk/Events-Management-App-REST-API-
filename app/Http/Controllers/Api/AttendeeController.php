<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate as FacadesGate;

class AttendeeController extends Controller
{
    use CanLoadRelationships;
    private array $relations = ['user', 'attendees', 'attendees.user'];
    public function __construct()
    {
        $this->authorizeResource(Event::class, 'event');
        $this->middleware('throttle:60,1')->only(['store','destroy']);
        //$this->middleware('auth:sanctum')->except(['index','show','update']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        $attendees = $this->loadRelationships($event->attendees()->latest());

        return AttendeeResource::collection(
            $attendees->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $attendee = $event->attendees()->create(
            [
                'user_id' => $request->user()->id
            ]
        );
        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResource($attendee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function destroy(Event $event, Attendee $attendee)
    {
        /*
        if (FacadesGate::denies('delete-attendee', [$event, $attendee])) {
            abort(403, "you're not authorized to delete this attendee!");
        }*/
        $attendee->delete();
        return response(status: 204);

    }
}


