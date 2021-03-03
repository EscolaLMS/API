<?php

namespace App\Services;

use App\Services\Contracts\VideoServiceContract;

class VideoService implements VideoServiceContract
{
    private string $ffmpeg_path;
    private string $file_path;
    private string $file_name;
    private array $data;

    public function __construct()
    {
        $this->setDriver();
        $this->data = ['split_duration' => 10, 'size' => '255x215'];
    }

    public function setCredentials(string $file, string $name)
    {
        $this->file_path = $file;
        $this->file_name = $name;
    }

    public function getDuration(): string
    {
        return "";// TODO fix this

        $cmd = shell_exec("$this->ffmpeg_path -i \"{$this->file_path}\" 2>&1");
        preg_match('/Duration: (.*?),/', $cmd, $matches);

        if (!isset($matches[1])) {
            return '';
        }

        $duration = explode('.', $matches[1]);
        return $duration[0] ?? '';
    }

    public function convertImages($video_image_path = '')
    {
        return "";// TODO fix this

        $cmd = "$this->ffmpeg_path -i \"{$this->file_path}\" -an -ss " . $this->data['split_duration'] . " -s {$this->data['size']} $video_image_path";
        return !shell_exec($cmd);
    }

    public function convertVideos($given_type)
    {
        return "";// TODO fix this
        $this->mp4Conversion();
        $this->webMConversion();
        $this->oggConversion();
    }

    private function oggConversion()
    {
        return "";// TODO fix this
        $cmd = "$this->ffmpeg_path -i $this->file_path -acodec libvorbis -b:a 128k -vcodec libtheora -b:v 400k -f ogg ./uploads/videos/{$this->file_name}.ogv";
        return !shell_exec($cmd);
    }

    private function webMConversion()
    {
        return "";// TODO fix this
        $cmd = "$this->ffmpeg_path -i $this->file_path -acodec libvorbis -b:a 128k -ac 2 -vcodec libvpx -b:v 400k -f webm ./uploads/videos/{$this->file_name}.webm";
        return !shell_exec($cmd);
    }

    private function mp4Conversion()
    {
        return "";// TODO fix this
        $cmd = "$this->ffmpeg_path -i $this->file_path -acodec aac -strict experimental ./uploads/videos/{$this->file_name}.mp4";
        return !shell_exec($cmd);
    }

    private function setDriver(): void
    {
        return; // TODO fix this
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $this->ffmpeg_path = base_path() . '\resources\assets\ffmpeg\ffmpeg_win\ffmpeg';
        } else {
            $this->ffmpeg_path = base_path() . '/resources/assets/ffmpeg/ffmpeg_lin/ffmpeg.exe';
        }
    }
}
