<?php

namespace Tests\Api;

use App\Enum\EventOrderByEnum;
use App\Models\StationaryEvent;
use EscolaLms\Webinar\Enum\WebinarStatusEnum;
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
            ->assertJsonFragment([
                'id' => $this->webinar->getKey(),
                'name' => $this->webinar->name,
            ])->assertJsonFragment([
                'id' => $this->stationaryEvent->getKey(),
                'name' => $this->stationaryEvent->name,
            ])->assertJsonMissing([
                'id' => $this->pastWebinar->getKey(),
                'name' => $this->pastWebinar->name,
            ])->assertJsonMissing([
                'id' => $this->pastStationaryEvent->getKey(),
                'name' => $this->pastStationaryEvent->name,
            ]);

        $this->getJson('/api/events?order_by=' . EventOrderByEnum::PAST)
            ->assertOk()
            ->assertJsonFragment([
                'id' => $this->pastWebinar->getKey(),
                'name' => $this->pastWebinar->name,
            ])->assertJsonFragment([
                'id' => $this->pastStationaryEvent->getKey(),
                'name' => $this->pastStationaryEvent->name,
            ])->assertJsonMissing([
                'id' => $this->webinar->getKey(),
                'name' => $this->webinar->name,
            ])->assertJsonMissing([
                'id' => $this->stationaryEvent->getKey(),
                'name' => $this->stationaryEvent->name,
            ]);
    }
}
