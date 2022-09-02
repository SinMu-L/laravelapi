<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';

    protected $fillable = [
        'name',
        'description'
    ];

    // 隐藏某个字段
    protected $hidden = [
        'id'
    ];


    // 自定义解析逻辑
    public function resolveRouteBinding($value, $field = null)
    {

        return $this->where('uuid', $value)->firstOrFail();
    }

}
