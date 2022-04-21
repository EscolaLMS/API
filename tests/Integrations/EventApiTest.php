<?php

namespace Tests\Integrations;

use App\Enum\EventOrderByEnum;
use EscolaLms\StationaryEvents\Enum\StationaryEventStatusEnum;
use EscolaLms\StationaryEvents\Http\Resources\StationaryEventResource;
use EscolaLms\StationaryEvents\Models\StationaryEvent;
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
            'status' => StationaryEventStatusEnum::PUBLISHED,
            'started_at' => now()->addDays(3)
        ])->create();

        $this->pastWebinar = Webinar::factory([
            'status' => WebinarStatusEnum::PUBLISHED,
            'active_from' => now()->subDays(5)
        ])->create();

        $this->pastStationaryEvent = StationaryEvent::factory([
            'status' => StationaryEventStatusEnum::PUBLISHED,
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
                $this->webinarToArray($this->webinar->refresh())
            )->assertJsonFragment([
                $this->stationaryEventToArray($this->stationaryEvent->refresh())
            ])->assertJsonMissing([
                $this->webinarToArray($this->pastWebinar->refresh())
            ])->assertJsonMissing([
                $this->stationaryEventToArray($this->pastStationaryEvent->refresh())
            ]);

        $this->getJson('/api/events?order_by=' . EventOrderByEnum::PAST)
            ->assertOk()
            ->assertJsonFragment([
                $this->webinarToArray($this->pastWebinar)
            ])->assertJsonFragment([
                $this->stationaryEventToArray($this->pastStationaryEvent)
            ])->assertJsonMissing([
                $this->webinarToArray($this->webinar)
            ])->assertJsonMissing([
                $this->stationaryEventToArray($this->stationaryEvent)
            ]);
    }

    private function webinarToArray($webinar): array
    {
        return array_merge(WebinarSimpleResource::make($webinar)->toArray(null), [
            'model' => Webinar::class,
        ]);
    }

    private function stationaryEventToArray($stationaryEvent): array
    {
        return array_merge(StationaryEventResource::make($stationaryEvent)->toArray(null), [
            'model' => StationaryEvent::class,
        ]);
    }
}
