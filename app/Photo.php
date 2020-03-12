<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Photo extends Model
{
    use SoftDeletes;
    protected $primaryKey='id';
    protected $fillable = [
        'name','path'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['link_url'];

    //

    public function product(){
        return $this->belongsTo('App\Product','product_id','id')->withDefault();
    }


    public function usercreated(){
        return $this->belongsTo('App\User','user_created','id')->withDefault();
    }


    /**
     * Get the administrator flag for the user.
     *
     * @return bool
     */
    public function getLinkUrlAttribute()
    {
        return url('/showfile/' . $this->path);
    }


    /**
     * Get the administrator flag for the user.
     *
     * @return bool
     */
    public function getUserNameAttribute()
    {
        return $this->usercreated->name;
    }



}
