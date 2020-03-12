<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;
    protected $primaryKey='id';
    protected $fillable = [
        'name', 'description'
    ];

    //

    public function products()
    {
        return $this->hasMany('App\Product','page_id','id');
    }

    public function usercreated(){
        return $this->belongsTo('App\User','user_created','id')->withDefault();
    }

}
