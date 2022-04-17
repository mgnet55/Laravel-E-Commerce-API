<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
       'name',
       'governorate_id',
    ];

    protected $hidden=[
        'deleted_at',

    ];
    protected $appends=[
        'governorate_name'
    ];
    protected $with=[

    ];

    protected $casts=[

    ];

    public function governorate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }

    public function getGovernorateNameAttribute(){
        $gov= $this->governorate()->get('name');
        return $gov[0]->name;
    }



}
