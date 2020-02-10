<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Question extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */


    protected $table = 'questions';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
        'image' => 'array'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function answer($crud = false)
    {
        return '<a class="btn btn-sm btn-link"  href="question/'.$this->id.'/edit" data-toggle="tooltip" title="Just a demo custom button."><i class="fa fa-eye"></i> Answer</a>';
    }
    public function edit($crud = false)
    {
        if(!isset($this->answer)){
            return '<a class="btn btn-sm btn-link"  href="question/'.$this->id.'/edit" data-toggle="tooltip" title="Just a demo custom button."><i class="fa fa-edit"></i> Edit</a>';
        }

    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function types()
    {
        return $this->belongsTo('App\Models\QuestionType','type','id');
    }
    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------

    */
    public function setImageAttribute($value)
    {
        $attribute_name = "image";
        $disk = config('backpack.base.root_disk_name');
         $destination_path = "public/uploads/document";
        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);

       // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field

        // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    }
}
