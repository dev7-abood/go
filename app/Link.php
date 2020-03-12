<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use SoftDeletes;
    protected $primaryKey='id';
    protected $fillable = [
        'url'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['category_name','page_name','link_url','user_name'];

    //

    public function Category(){
        return $this->belongsTo('App\Category','category_id','id')->withDefault();
    }

    public function Page(){
        return $this->belongsTo('App\Page','page_id','id')->withDefault();
    }


    public function usercreated(){
        return $this->belongsTo('App\User','user_created','id')->withDefault();
    }

    /**
     * Get the administrator flag for the user.
     *
     * @return bool
     */
    public function getCategoryNameAttribute()
    {
        return $this->Category->name;
    }

    /**
     * Get the administrator flag for the user.
     *
     * @return bool
     */
    public function getPageNameAttribute()
    {
        return $this->Page->name;
    }

    /**
     * Get the administrator flag for the user.
     *
     * @return bool
     */
    public function getLinkUrlAttribute()
    {
        return url('/data/json/' . $this->user_created . '/' . $this->url);
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
