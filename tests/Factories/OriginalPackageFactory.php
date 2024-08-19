<?php

namespace Elkadrey\Compoships\Tests\Factories;

use Elkadrey\Compoships\Database\Eloquent\Factories\ComposhipsFactory;
use Elkadrey\Compoships\Tests\Models\Allocation;
use Elkadrey\Compoships\Tests\Models\OriginalPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

class OriginalPackageFactory extends Factory
{
    use ComposhipsFactory;

    protected $model = OriginalPackage::class;

    public function definition(): array
    {
        return [
            'allocation_id' => Allocation::factory(),
        ];
    }
}
