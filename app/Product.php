<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $fillable = [
        'name', 'description', 'price', 'prv_price', 'qte', 'color', 'taille'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['category_name',  'page_name', 'colors', 'tailles', 'image_urls'];

    //



    public function Category(){
        return $this->belongsTo('App\Category','category_id','id')->withDefault();
    }

    public function Page(){
        return $this->belongsTo('App\Page','page_id','id')->withDefault();
    }



    public function usercreated()
    {
        return $this->belongsTo('App\User', 'user_created', 'id')->withDefault();
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



    public function getColorsAttribute()
    {
        return explode(',', $this->getAttributeValue('color'));
    }


    public function getTaillesAttribute()
    {
        return explode(',', $this->getAttributeValue('taille'));
    }

    /**
     * Get the administrator flag for the user.
     *
     * @return bool
     */
    public function getImageUrlsAttribute()
    {
        if (request()->has('nourl'))
            return explode(',', $this->image);
        else {
            return array_map(function ($e) {
                return url('showfile/' . $e);
            }, explode(',', $this->image));
        }
    }


}
