<?php

use EscolaLms\StationaryEvents\Models\StationaryEvent;
use EscolaLms\Webinar\Models\Webinar;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\MySqlConnection;
use Illuminate\Support\Facades\DB;
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

class CreateSearchableEventsView extends Migration
{
    public function up()
    {
        $stationaryEventClass = StationaryEvent::class;
        $webinarClass = Webinar::class;

        if (DB::connection() instanceof MySqlConnection) {
            $stationaryEventClass = str_replace('\\', '\\\\', $stationaryEventClass);
            $webinarClass = str_replace('\\', '\\\\', $webinarClass);
        }

        $query = "SELECT webinars.id as event_id,
                '{$webinarClass}' as event_type,
                webinars.active_from as start_date,
                webinars.active_to as end_date,
                created_at
                FROM webinars
                WHERE status='published'
                UNION
                SELECT
                stationary_events.id as event_id,
                '{$stationaryEventClass}' as event_class,
                stationary_events.started_at as start_date,
                stationary_events.finished_at as end_date,
                created_at
                FROM stationary_events
                ORDER BY created_at desc";

        Schema::createView('searchable_events', $query);
    }

    public function down()
    {
        Schema::dropView('searchable_events');
    }
}
