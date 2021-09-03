<?php

return [
    'metrics' => [
        \EscolaLms\Reports\Metrics\CoursesMoneySpentMetric::class,
        \EscolaLms\Reports\Metrics\CoursesPopularityMetric::class,
        \EscolaLms\Reports\Metrics\CoursesSecondsSpentMetric::class,
        \EscolaLms\Reports\Metrics\TutorsPopularityMetric::class,
    ],
    /**
     * For each Metric class you can specify settings:
     * @param bool history - should this metric be automatically measured (default: true)
     * @param int limit    - how many data points should be saved in database and/or retrieved in api call (default: 10)
     * @param string cron  - cron expression determining how often this metric will be measured and saved in DB (default: midnight every day)
     */
    'metric_configuration' => [
        \EscolaLms\Reports\Metrics\CoursesMoneySpentMetric::class => [
            'limit' => 10,
            'history' => false,
            'cron' => '0 0 * * *',
        ],
        \EscolaLms\Reports\Metrics\CoursesPopularityMetric::class => [
            'limit' => 10,
            'history' => false,
            'cron' => '0 0 * * *',
        ],
        \EscolaLms\Reports\Metrics\CoursesSecondsSpentMetric::class => [
            'limit' => 10,
            'history' => false,
            'cron' => '0 0 * * *',
        ],
        \EscolaLms\Reports\Metrics\TutorsPopularityMetric::class => [
            'limit' => 10,
            'history' => false,
            'cron' => '0 0 * * *',
        ],
    ]
];
