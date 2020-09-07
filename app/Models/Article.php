<?php

namespace App\Models;

use App\Models\Traits\HasSorts;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{

    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }


    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }


    public function scopeTitle(Builder $query, $value)
    {
       $query->where('title', 'LIKE', "%{$value}%");
    }

    public function scopeContent(Builder $query, $value)
    {
       $query->where('content', 'LIKE', "%{$value}%");
    }

    public function scopeYear(Builder $query, $value)
    {
        $query->whereYear('created_at',$value);
    }

    public function scopeMonth(Builder $query, $value)
    {
        $query->whereMonth('created_at',$value);
    }

    public function scopeSearch(Builder $query, $values)
    {
        foreach(Str::of($values)->explode(' ') as $value){
            $query->orWhere('title','LIKE',"%{$value}%")
                ->orWhere('content','LIKE',"%{$value}%");
        };

    }


}
