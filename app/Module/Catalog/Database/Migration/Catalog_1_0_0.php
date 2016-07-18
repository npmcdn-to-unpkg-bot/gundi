<?php
namespace Module\Catalog\Database\Migration;

use Core\Library\Database\Migration;

Class Catalog_1_0_0 extends Migration
{

    public function up()
    {
        $this->schema()->dropIfExists('products');
        $this->schema()->dropIfExists('categories');

        $this->schema()->create('products', function ($table) {
            $table->string('name');
            $table->enum('status', ['enable', 'disable'])->index(); //products status
            $table->integer('category_id');
            $table->integer('price');
            $table->date('created_at');
            $table->date('updated_at');
            $table->increments('id');
        });

        $this->schema()->create('categories', function ($table) {
            $table->string('name');
            $table->integer('category_parent_id')->nullable();
            $table->date('created_at');
            $table->date('updated_at');
            $table->increments('id');
        });
    }

    public function down()
    {
        $this->schema()->drop('products');
        $this->schema()->drop('categories');
    }

}