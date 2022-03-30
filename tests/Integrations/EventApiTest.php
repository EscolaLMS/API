<?php

namespace Tests\Integrations;

use App\Enum\EventOrderByEnum;
use App\Models\StationaryEvent;
use EscolaLms\StationaryEvents\Http\Resources\StationaryEventResource;
use EscolaLms\Webinar\Enum\WebinarStatusEnum;
use EscolaLms\Webinar\Http\Resources\WebinarSimpleResource;
use EscolaLms\Webinar\Models\Webinar;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EventApiTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->webinar = Webinar::factory([
            'status' => WebinarStatusEnum::PUBLISHED,
            'active_from' => now()->addDays(2)
        ])->create();

        $this->stationaryEvent = StationaryEvent::factory([
            'started_at' => now()->addDays(3)
        ])->create();

        $this->pastWebinar = Webinar::factory([
            'status' => WebinarStatusEnum::PUBLISHED,
            'active_from' => now()->subDays(5)
        ])->create();

        $this->pastStationaryEvent = StationaryEvent::factory([
            'started_at' => now()->subDays(3)
        ])->create();
    }

    public function testEventsList(): void
    {
        $archivedWebinar = Webinar::factory(['status' => WebinarStatusEnum::ARCHIVED])->create();

        $this->assertTrue(true);
        $this->getJson('/api/events')
            ->assertOk()
            ->assertJsonMissing([
                'id' => $archivedWebinar->getKey(),
                'name' => $archivedWebinar->name,
            ]);
    }

    public function testEventsListOrderBy(): void
    {
        $this->assertTrue(true);
        $this->getJson('/api/events?order_by=' . EventOrderByEnum::NEXT)
            ->assertOk()
            ->assertJsonFragment(
                WebinarSimpleResource::make($this->webinar->refresh())->toArray(null)
            )->assertJsonFragment([
                StationaryEventResource::make($this->stationaryEvent->refresh())->toArray(null)
            ])->assertJsonMissing([
                WebinarSimpleResource::make($this->pastWebinar->refresh())->toArray(null)
            ])->assertJsonMissing([
                StationaryEventResource::make($this->pastStationaryEvent->refresh())->toArray(null)
            ]);

        $this->getJson('/api/events?order_by=' . EventOrderByEnum::PAST)
            ->assertOk()
            ->assertJsonFragment([
                WebinarSimpleResource::make($this->pastWebinar)->toArray(null)
            ])->assertJsonFragment([
                StationaryEventResource::make($this->pastStationaryEvent)->toArray(null)
            ])->assertJsonMissing([
                WebinarSimpleResource::make($this->webinar)->toArray(null)
            ])->assertJsonMissing([
                StationaryEventResource::make($this->stationaryEvent)->toArray(null)
            ]);
    }
}
