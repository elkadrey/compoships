<?php

namespace Elkadrey\Compoships\Tests\Factories;

use Elkadrey\Compoships\Database\Eloquent\Factories\ComposhipsFactory;
use Elkadrey\Compoships\Tests\Models\Allocation;
use Illuminate\Database\Eloquent\Factories\Factory;

class AllocationFactory extends Factory
{
    use ComposhipsFactory;

    protected $model = Allocation::class;

    public function definition(): array
    {
        return [
            'booking_id' => $this->faker->randomNumber(6),
            'vehicle_id' => $this->faker->randomNumber(6),
        ];
    }
}
