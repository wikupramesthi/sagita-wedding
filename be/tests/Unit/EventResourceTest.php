<?php

namespace Tests\Unit;

use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventResourceTest extends TestCase
{
    public function test_missing_event_image_is_omitted_and_valid_images_are_preserved(): void
    {
        Storage::fake('public');
        $event = new Event;
        $request = Request::create('/');

        $event->gambar = 'events/missing.jpg';
        $this->assertNull((new EventResource($event))->resolve($request)['gambar']);

        Storage::disk('public')->put('events/available.jpg', 'image');
        $event->gambar = 'events/available.jpg';
        $this->assertSame(
            asset('storage/events/available.jpg'),
            (new EventResource($event))->resolve($request)['gambar'],
        );

        $event->gambar = 'https://example.test/events/external.jpg';
        $this->assertSame(
            'https://example.test/events/external.jpg',
            (new EventResource($event))->resolve($request)['gambar'],
        );
    }
}
