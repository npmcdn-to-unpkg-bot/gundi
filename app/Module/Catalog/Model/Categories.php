<?php
namespace Module\Catalog\Model;

use Core\Library\Database\Model;

class Categories extends Model
{
    protected $fillable = ['id', 'name', 'category_parent_id'];
    public function parent()
    {
        return $this->belongsTo(Categories::class, 'category_parent_id', 'id');
    }
}