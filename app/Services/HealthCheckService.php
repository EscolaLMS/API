<?php

namespace App\Services;

use App\Services\Contracts\HealthCheckServiceContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class HealthCheckService implements HealthCheckServiceContract
{

    public function getHealthData(): array
    {
        return [
            'db_status' => $this->getDbStatus(),
            'redis_status' => $this->getRedisStatus(),
            'cpu_usage' => $this->getCpuUsage(),
            'disk' => $this->getDiskSpace(),
        ];
    }

    private function getDbStatus(): string
    {
        try {
            DB::connection()->getPdo();
            return 'OK';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function getRedisStatus(): string
    {
        try {
            $response = Redis::connection()->client()->ping();
            return (string)$response === 'PONG' ? 'OK' : 'Unexpected response: ' . $response;
        } catch (\Exception $e) {
            return 'Connection failed: ' . $e->getMessage();
        }
    }

    private function getCpuUsage(): string
    {
        try {
            return sys_getloadavg()[0];
        } catch (\Exception $e) {
            return 'Failed to get CPU usage: ' . $e->getMessage();
        }
    }

    private function getDiskSpace(): array
    {
        try {
            return [
                'free_space' => disk_free_space('/'),
                'total_space' => disk_total_space('/'),
                'used_space' => disk_total_space('/') - disk_free_space('/'),
                'percentage_usage' => round((disk_total_space('/') - disk_free_space('/')) / disk_total_space('/') * 100, 2) . '%',
                'status' => 'OK'
            ];
        } catch (\Exception $e) {
            return ['status' => 'Failed to get disk space: ' . $e->getMessage()];
        }
    }
}
