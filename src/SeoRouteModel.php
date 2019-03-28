<?php

namespace Lihatskiy\SeoRouting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeoRouteModel extends Model
{
    use SoftDeletes;

    public $table = 'seo_routes';

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    protected $fillable = array(
        'domain',
        'real_path',
        'seo_url'
    );
}