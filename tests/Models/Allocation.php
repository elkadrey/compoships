<?php

namespace Elkadrey\Compoships\Tests\Models;

use Elkadrey\Compoships\Compoships;
use Elkadrey\Compoships\Tests\Factories\AllocationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int    $id
 * @property int    $user_id
 * @property int    $booking_id
 * @property int    $vehicle_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read TrackingTask[]|Collection $trackingTasks
 * @property-read OriginalPackage[]|Collection $originalPackages
 * @property-read Space $space
 * @property-read User $user
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Allocation extends Model
{
    use Compoships;
    use HasFactory;

    // NOTE: we need this because Laravel 7 uses Carbon's method toJSON() instead of toDateTimeString()
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * @return \Elkadrey\Compoships\Database\Eloquent\Relations\HasMany
     */
    public function trackingTasks()
    {
        return $this->hasMany(TrackingTask::class, ['booking_id', 'vehicle_id'], ['booking_id', 'vehicle_id']);
    }

    /**
     * @return \Elkadrey\Compoships\Database\Eloquent\Relations\HasMany
     */
    public function originalPackages()
    {
        return $this->hasMany(OriginalPackage::class);
    }

    /**
     * @return \Elkadrey\Compoships\Database\Eloquent\Relations\HasOne
     */
    public function originalPackagesOneOfMany()
    {
        return $this->hasOne(OriginalPackage::class)->ofMany();
    }

    /**
     * @return \Elkadrey\Compoships\Database\Eloquent\Relations\HasOne
     */
    public function space()
    {
        return $this->hasOne(Space::class, 'booking_id', 'booking_id');
    }

    /**
     * @return \Elkadrey\Compoships\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, ['user_id', 'booking_id'], ['id', 'booking_id']);
    }

    /**
     * @return \Elkadrey\Compoships\Database\Eloquent\Relations\HasOne
     */
    public function smallerTrackingTask()
    {
        $rel = $this->hasOne(TrackingTask::class, ['booking_id', 'vehicle_id'], ['booking_id', 'vehicle_id']);

        return getLaravelVersion() < 8 ? $rel : $rel->ofMany('id', 'min');
    }

    /**
     * @return \Elkadrey\Compoships\Database\Eloquent\Relations\HasOne
     */
    public function latestTrackingTask()
    {
        $rel = $this->hasOne(TrackingTask::class, ['booking_id', 'vehicle_id'], ['booking_id', 'vehicle_id']);

        return getLaravelVersion() < 8 ? $rel : $rel->latestOfMany();
    }

    /**
     * @return \Elkadrey\Compoships\Database\Eloquent\Relations\HasOne
     */
    public function oldestTrackingTask()
    {
        $rel = $this->hasOne(TrackingTask::class, ['booking_id', 'vehicle_id'], ['booking_id', 'vehicle_id']);

        return getLaravelVersion() < 8 ? $rel : $rel->oldestOfMany();
    }

    protected static function newFactory()
    {
        return AllocationFactory::new();
    }
}
