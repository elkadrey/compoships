<?php

namespace Elkadrey\Compoships\Tests;

use Elkadrey\Compoships\Tests\Models\Allocation;
use Elkadrey\Compoships\Tests\Models\OriginalPackage;
use Elkadrey\Compoships\Tests\Models\PickupPoint;
use Elkadrey\Compoships\Tests\Models\PickupTime;
use Elkadrey\Compoships\Tests\Models\Space;
use Elkadrey\Compoships\Tests\Models\TrackingTask;
use Elkadrey\Compoships\Tests\Models\User;
use Elkadrey\Compoships\Tests\TestCase\TestCase;
use Illuminate\Database\Eloquent\Model;

/**
 * @covers \Elkadrey\Compoships\Compoships
 * @covers \Elkadrey\Compoships\Database\Query\Builder
 * @covers \Elkadrey\Compoships\Database\Eloquent\Relations\BelongsTo
 * @covers \Elkadrey\Compoships\Database\Eloquent\Relations\HasMany
 */
class ComposhipsTest extends TestCase
{
    /**
     * Test the save method on a relationship.
     */
    public function testSave()
    {
        Model::unguard();

        $allocation = new Allocation();
        $allocation->booking_id = 1;
        $allocation->vehicle_id = 1;
        $allocation->save();

        $allocation->trackingTasks()
            ->save(new TrackingTask());

        $this->assertNotNull($allocation->trackingTasks);
        $this->assertInstanceOf(Allocation::class, $allocation->trackingTasks->first()->allocation);
    }

    /**
     * @covers \Elkadrey\Compoships\Database\Eloquent\Relations\HasOne
     */
    public function testSaveModelNotUsingCompoships_onHasOne()
    {
        Model::unguard();

        $allocation = new Allocation();
        $allocation->booking_id = 1;
        $allocation->vehicle_id = 1;
        $allocation->save();

        $allocation->space()
            ->save(new Space());

        $this->assertNotNull($allocation->space);
    }

    /**
     * Test the save method on a relationship with a null value.
     */
    public function testSaveWithANullValue()
    {
        Model::unguard();

        $allocation = new Allocation();
        $allocation->booking_id = 1;
        $allocation->vehicle_id = null;
        $allocation->save();

        $allocation->trackingTasks()
            ->save(new TrackingTask());

        $this->assertNotNull($allocation->trackingTasks);
        $this->assertTrue($allocation->trackingTasks->isNotEmpty());
        $this->assertInstanceOf(Allocation::class, $allocation->trackingTasks->first()->allocation);
    }

    /**
     * Test a relationship with only null values is not supported.
     */
    public function testARelationshipWithOnlyNullValuesIsNotSupported()
    {
        Model::unguard();

        $allocation = new Allocation();
        $allocation->booking_id = null;
        $allocation->vehicle_id = null;
        $allocation->save();

        $allocation->trackingTasks()
            ->save(new TrackingTask());

        $this->assertNotNull($allocation->trackingTasks);
        $this->assertTrue($allocation->trackingTasks->isEmpty());
        $this->assertNull(TrackingTask::first()->allocation);
    }

    /**
     * Test a relationship with a foreign key is empty on a new instance.
     */
    public function testARelationshipWithAForeignKeyIsEmptyOnANewInstance()
    {
        Model::unguard();

        $allocation = new Allocation();
        $allocation->user_id = null;
        $allocation->save();

        $user = new User();

        $this->assertNotNull($user->allocations);
        $this->assertTrue($user->allocations->isEmpty());
    }

    /**
     * Test the create method on a relationship.
     */
    public function testCreate()
    {
        Model::unguard();

        $allocation = new Allocation();
        $allocation->booking_id = 1;
        $allocation->vehicle_id = 1;
        $allocation->save();

        $allocation->trackingTasks()
            ->create([]);

        $this->assertNotNull($allocation->trackingTasks);
        $this->assertInstanceOf(Allocation::class, $allocation->trackingTasks->first()->allocation);
    }

    /**
     * Test the make method on a relationship.
     */
    public function testMake()
    {
        Model::unguard();

        $allocation = new Allocation();
        $allocation->booking_id = 1;
        $allocation->vehicle_id = 1;
        $allocation->save();

        $trackingTask = $allocation->trackingTasks()
            ->make([]);

        $this->assertNotNull($trackingTask);
        $this->assertInstanceOf(Allocation::class, $trackingTask->allocation);
    }

    public function testHas()
    {
        if (getLaravelVersion() <= 5.6 && getPHPVersion() >= 7.3) {
            $this->markTestIncomplete('This test is broken on laravel 5.6 with PHP 7.3 and earlier!');
        }

        $allocations = Allocation::has('trackingTasks')
            ->get()
            ->toArray();

        $this->assertIsArray($allocations);
    }

    /**
     * A basic test example.
     */
    public function testWhereHas()
    {
        if (getLaravelVersion() <= 5.6 && getPHPVersion() >= 7.3) {
            $this->markTestIncomplete('This test is broken on laravel 5.6 with PHP 7.3 and earlier!');
        }

        $allocations = Allocation::wherehas('trackingTasks')
            ->get()
            ->toArray();

        $this->assertIsArray($allocations);

        $allocations = Allocation::query()
            ->has('trackingTasks')
            ->get()
            ->toArray();

        $this->assertIsArray($allocations);
    }

    public function testWhereHasCallback()
    {
        if (getLaravelVersion() <= 5.6 && getPHPVersion() >= 7.3) {
            $this->markTestIncomplete('This test is broken on laravel 5.6 with PHP 7.3 and earlier!');
        }

        $allocations = Allocation::wherehas('trackingTasks', function ($query) {
            $query->where('vehicle_id', 1);
        })
            ->get()
            ->toArray();

        $this->assertIsArray($allocations);
    }

    public function testMixedTypeCompositeKey()
    {
        Model::unguard();

        $pickupPoint = new PickupPoint();
        $pickupPoint->contract_number = 'AAA';
        $pickupPoint->pickup_index = 1;
        $pickupPoint->save();

        $pickupPoint->pickupTimes()
            ->create([
                'days'        => 'mon tue',
                'pickup_time' => '08:00:00',
            ]);

        $this->assertNotNull($pickupPoint->pickupTimes);
    }

    public function testHasForSelfRelation()
    {
        if (getLaravelVersion() <= 5.6 && getPHPVersion() >= 7.3) {
            $this->markTestIncomplete('This test is broken on laravel 5.6 with PHP 7.3 and earlier!');
        }

        $trackingTask = TrackingTask::has('subTasks')
            ->get()
            ->toArray();

        $this->assertIsArray($trackingTask);
    }

    public function testHasWithBelongsToRelation()
    {
        if (getLaravelVersion() <= 5.6 && getPHPVersion() >= 7.3) {
            $this->markTestIncomplete('This test is broken on laravel 5.6 with PHP 7.3 and earlier!');
        }

        $pickup_times = PickupTime::has('pickupPoint')
            ->get()
            ->toArray();

        $this->assertIsArray($pickup_times);
    }

    public function testAssociateOnbelongsTo()
    {
        Model::unguard();

        $user = new User();
        $user->booking_id = 1;
        $user->save();

        $allocation = new Allocation();
        $allocation->user()->associate($user);

        $this->assertNotNull($allocation->user);
    }

    public function testHasOneOrManyFactoryRelationship()
    {
        if (getLaravelVersion() < 8.0) {
            $this->markTestIncomplete('This test is broken on laravel 7.x and earlier!');
        }

        $allocation = Allocation::factory()
            ->has(TrackingTask::factory()->count(2)) // A Compoships relationship
            ->has(OriginalPackage::factory()->count(3)) // A standard relationship
            ->create();

        $trackingTasks = TrackingTask::query()
            ->whereIn('booking_id', $allocation->pluck('booking_id'))
            ->get();

        $originalPackages = OriginalPackage::query()
            ->where('allocation_id', $allocation->id)
            ->get();

        $this->assertCount(2, $trackingTasks);
        $this->assertCount(3, $originalPackages);
    }
}
