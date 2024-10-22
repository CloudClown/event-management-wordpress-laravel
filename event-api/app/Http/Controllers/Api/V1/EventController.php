<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class EventController extends Controller
{
    public function index()
    {
        try {
            $events = Event::with('images')->get();
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
                'primary_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed', $validator->errors()->toArray());
                return response()->json(['errors' => $validator->errors()], 422);
            }

            Log::info('Validation passed, creating event');

            $eventData = $request->except('primary_image', 'gallery_images');

            if ($request->hasFile('primary_image')) {
                $imageName = time() . '.' . $request->primary_image->extension();
                $request->primary_image->storeAs('public/images', $imageName);
                $eventData['primary_image'] = $imageName;
            }
        
            $event = Event::create($eventData);
        
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                    $image->storeAs('public/images', $imageName);
                    $event->images()->create(['image_data' => $imageName]);
                }
            }

            Log::info('Event created successfully', $event->toArray());
            return response()->json([
                'message' => 'Event created successfully',
                'event' => $event->load('images')
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



    private function convertImageToBase64($image)
    {
        $imageData = file_get_contents($image->getRealPath());
        $mimeType = $image->getMimeType();
        return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
    }


    public function show($id)
    {
        try {
            $event = Event::with('images')->findOrFail($id);
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
                'primary_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $eventData = $request->except('primary_image', 'gallery_images');

            if ($request->hasFile('primary_image')) {
                // Delete old primary image
                if ($event->primary_image) {
                    Storage::disk('public')->delete($event->primary_image);
                }
                $primaryImage = $request->file('primary_image');
                $primaryImagePath = $primaryImage->store('event_images', 'public');
                $eventData['primary_image'] = $primaryImagePath;
            }

            $event->update($eventData);

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $imagePath = $image->store('event_images', 'public');
                    $event->images()->create(['image_path' => $imagePath]);
                }
            }

            return response()->json($event->load('images'));
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

            // Delete primary image
            if ($event->primary_image) {
                Storage::disk('public')->delete($event->primary_image);
            }

            // Delete gallery images
            foreach ($event->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

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
            $events = Event::where('category', $category)->with('images')->get();
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
