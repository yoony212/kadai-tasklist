<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\task;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\task>
 */
class taskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $content = ['html', 'css', 'JS', 'PHP', 'laraval', 'vue.js', 'react'];
        $status = ['bad', 'not bad', 'so-so', 'good', 'fine', 'Excellent', 'yeees'];

        $content = $content[rand(0, count($content) - 1)];
        $status = $status[rand(0, count($status) - 1)];
        return [
            'content' => $content,
            'status' => $status,
        ];
    }
}
