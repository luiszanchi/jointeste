<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateProdutosTable.
 */
class CreateProdutosTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('produtos', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descricao');
            $table->float('valor');
            $table->integer('codigo')->unique();

            $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('produtos');
	}
}
