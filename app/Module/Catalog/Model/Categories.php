<?php
namespace Module\Catalog\Model;

use Core\Library\Database\Model;
use Core\Library\Validator\ValidatorTrait;

class Categories extends Model
{
    use ValidatorTrait;

    protected $fillable = ['id', 'name', 'category_parent_id'];

    protected $rules = [
        'name' => 'required',
    ];

    public function parent()
    {
        return $this->belongsTo(Categories::class, 'category_parent_id', 'id');
    }
}