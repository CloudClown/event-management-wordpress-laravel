<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function index()
    {
        try {
            $events = Event::all();
            return response()->json($events);
        } catch (\Exception $e) {
            Log::error('Failed to fetch events', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Failed to fetch events'], 500);
        }
    }

    public function store(Request $request)
    {
        Log::info('Store method called');
        Log::info('Received POST request for event creation', $request->all());

        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
                'location' => 'required|string|max:255',
                'category' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed', $validator->errors()->toArray());
                return response()->json(['errors' => $validator->errors()], 422);
            }

            Log::info('Validation passed, creating event');
            $event = Event::create($request->all());
            Log::info('Event created successfully', $event->toArray());
            return response()->json([
                'message' => 'Event created successfully',
                'event' => $event
            ], 201);
        } catch (\Exception $e) {
            Log::error('Exception caught', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Failed to create event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $event = Event::findOrFail($id);
            return response()->json($event);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Event not found', ['id' => $id]);
            return response()->json(['message' => 'Event not found'], 404);
        } catch (\Exception $e) {
            Log::error('Failed to fetch event', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Failed to fetch event'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $event = Event::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title' => 'string|max:255',
                'description' => 'string',
                'date' => 'date',
                'time' => 'date_format:H:i',
                'location' => 'string|max:255',
                'category' => 'string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $event->update($request->all());
            return response()->json($event);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Event not found for update', ['id' => $id]);
            return response()->json(['message' => 'Event not found'], 404);
        } catch (\Exception $e) {
            Log::error('Failed to update event', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Failed to update event'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->delete();
            return response()->json(null, 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Event not found for deletion', ['id' => $id]);
            return response()->json(['message' => 'Event not found'], 404);
        } catch (\Exception $e) {
            Log::error('Failed to delete event', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Failed to delete event'], 500);
        }
    }

    public function getEventsByCategory($category)
    {
        try {
            $events = Event::where('category', $category)->get();
            return response()->json($events);
        } catch (\Exception $e) {
            Log::error('Failed to fetch events by category', [
                'category' => $category,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Failed to fetch events by category'], 500);
        }
    }
}