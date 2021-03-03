<?php

namespace Database\Factories\EscolaSoft\EscolaLms\Models;

use EscolaSoft\EscolaLms\Models\Attachment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttachmentFactory extends Factory
{
    protected $model = Attachment::class;

    public function definition(): array
    {
        $filename = $this->faker->word . '.pdf';

        return [
            'path' => $this->faker->word . '/' . $filename,
            'filename' => $filename
        ];
    }
}
