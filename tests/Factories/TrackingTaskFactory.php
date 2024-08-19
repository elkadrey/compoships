<?php

namespace Elkadrey\Compoships\Tests\Factories;

use Elkadrey\Compoships\Database\Eloquent\Factories\ComposhipsFactory;
use Elkadrey\Compoships\Tests\Models\TrackingTask;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrackingTaskFactory extends Factory
{
    use ComposhipsFactory;

    protected $model = TrackingTask::class;

    public function definition(): array
    {
        return [
            'booking_id' => $this->faker->randomNumber(6),
            'vehicle_id' => $this->faker->randomNumber(6),
        ];
    }
}
