<?php
namespace Module\Catalog\Model;

use Core\Library\Database\Model;

class Products extends Model
{
    protected $fillable = ['name', 'status', 'price', 'category_id'];

    public function category()
    {
        return $this->hasOne(Categories::class, 'id', 'category_id');
    }
}