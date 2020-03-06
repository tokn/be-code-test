<?php

declare(strict_types=1);

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Organisation
 *
 * @property int         id
 * @property string      name
 * @property int         owner_user_id
 * @property Carbon      trial_end
 * @property bool        subscribed
 * @property Carbon      created_at
 * @property Carbon      updated_at
 * @property Carbon|null deleted_at
 *
 * @package App
 */
class Organisation extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
      'name',
      'owner_user_id',
      'trial_end',
      'subscribed'
    ];


    /**
     * @var array
     */
    protected $dates = [
        'trial_end',
        'deleted_at',
    ];

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    /**
     * getTrialEndAttribute Return a UNIX timestamp if field is set
     * @param mixed $value System attribute value - may be null
     * @return mixed
     */
    public function getTrialEndAttribute($value) {
      if (is_null($value)) {
        return $value;
      }
      return Carbon::create($value)->timestamp;
    }
}
