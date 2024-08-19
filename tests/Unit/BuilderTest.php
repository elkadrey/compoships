<?php

namespace Elkadrey\Compoships\Tests\Unit;

use Elkadrey\Compoships\Tests\Models\Allocation;
use Elkadrey\Compoships\Tests\TestCase\TestCase;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * @covers \Elkadrey\Compoships\Compoships::getAttribute
 * @covers \Elkadrey\Compoships\Database\Eloquent\Relations\HasMany::getResults
 * @covers \Elkadrey\Compoships\Database\Eloquent\Relations\HasOneOrMany::getForeignKeyName
 */
class BuilderTest extends TestCase
{
    /**
     * @covers \Elkadrey\Compoships\Compoships::newBaseQueryBuilder
     * @covers \Elkadrey\Compoships\Database\Eloquent\Concerns\HasRelationships::hasMany
     * @covers \Elkadrey\Compoships\Database\Eloquent\Concerns\HasRelationships::newHasMany
     * @covers \Elkadrey\Compoships\Database\Eloquent\Concerns\HasRelationships::sanitizeKey
     * @covers \Elkadrey\Compoships\Database\Eloquent\Relations\HasOneOrMany::addConstraints
     * @covers \Elkadrey\Compoships\Database\Eloquent\Relations\HasOneOrMany::getQualifiedParentKeyName
     * @covers \Elkadrey\Compoships\Database\Query\Builder::whereColumn
     */
    public function test_Illuminate_hasOneOrMany__Builder_whereColumn_on_relation_column()
    {
        if (getLaravelVersion() <= 5.6 && getPHPVersion() >= 7.3) {
            $this->markTestIncomplete('This test is broken on laravel 5.6 with PHP 7.3 and earlier!');
        }

        $allocationId1 = Capsule::table('allocations')->insertGetId([
            'booking_id' => 1,
            'vehicle_id' => 1,
        ]);
        $allocationId2 = Capsule::table('allocations')->insertGetId([
            'booking_id' => 2,
            'vehicle_id' => 2,
        ]);
        $package1 = Capsule::table('original_packages')->insertGetId([
            'name'          => 'name 1',
            'allocation_id' => 1,
        ]);
        $package2 = Capsule::table('original_packages')->insertGetId([
            'name'          => 'name 2',
            'allocation_id' => 1,
        ]);

        /** @var Allocation[] $allocations */
        $allocations = Allocation::query()->whereHas('originalPackages', function ($query) {
            $query->where('id', 123);
        })->get();
        $this->assertCount(0, $allocations);

        /** @var Allocation[] $allocations */
        $allocations = Allocation::query()->whereHas('originalPackages', function ($query) {
            $query->where('id', 2);
        })->get();
        $this->assertCount(1, $allocations);
        $this->assertCount(2, $allocations[0]->originalPackages);
    }
}
