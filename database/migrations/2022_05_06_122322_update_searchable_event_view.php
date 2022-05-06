<?php

use App\Models\StationaryEvent;
use App\Models\Webinar;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\MySqlConnection;
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

class UpdateSearchableEventView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropView('searchable_events');

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
                WHERE status='published' AND (yt_id IS NOT NULL AND yt_stream_key IS NOT NULL AND yt_stream_url IS NOT NULL)
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropView('searchable_events');

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
}
