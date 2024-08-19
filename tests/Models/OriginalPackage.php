<?php

namespace Elkadrey\Compoships\Tests\Models;

use Elkadrey\Compoships\Compoships;
use Elkadrey\Compoships\Tests\Factories\OriginalPackageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $name
 * @property int    $allocation_id
 * @property-read string $pcid
 * @property-read Allocation $allocation
 * @property-read ProductCode $productCode
 * @property-read ProductCode $productCode2
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class OriginalPackage extends Model
{
    use Compoships;
    use HasFactory;

    public $timestamps = false;

    /**
     * @return \Elkadrey\Compoships\Database\Eloquent\Relations\BelongsTo
     */
    public function allocation()
    {
        return $this->belongsTo(Allocation::class);
    }

    /**
     * @return \Elkadrey\Compoships\Database\Eloquent\Relations\BelongsTo
     */
    public function productCode()
    {
        return $this->belongsTo(ProductCode::class, 'pcid', 'pcid');
    }

    /**
     * @return \Elkadrey\Compoships\Database\Eloquent\Relations\BelongsTo
     */
    public function productCode2()
    {
        return $this->belongsTo(ProductCode::class, ['pcid'], ['pcid']);
    }

    protected static function newFactory()
    {
        return OriginalPackageFactory::new();
    }
}
