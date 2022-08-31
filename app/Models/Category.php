<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';


    // 自定义解析逻辑
    public function resolveRouteBinding($value, $field = null)
    {
        if(!$field){
            $field = 'uuid';
        }
        return $this->where($field, $value)->firstOrFail();
    }

}
