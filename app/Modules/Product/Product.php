<?php

namespace App\Modules\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Route;

use App\Modules\User\User;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Product extends Model implements HasMedia
{
    use HasMediaTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'position', 'balance',
    ];

    /**
     * User relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param mixed $value Route value.
     *
     * @return Model|null
     */
    public function resolveRouteBinding($value)
    {
        if (Route::currentRouteName() === 'products.show')
            return $this->where($this->getRouteKeyName(), $value)
                ->with('user:id,first_name,last_name')
                ->first();

        return parent::resolveRouteBinding($value);
    }

    /**
     * Register the media conversions.
     *
     * @param Media|null $media
     *
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this
            ->addMediaConversion('thumb-120')
            ->fit(Manipulations::FIT_CROP, 120, 120);
        $this
            ->addMediaConversion('thumb-40')
            ->fit(Manipulations::FIT_CONTAIN, 40, 40);
    }
}
