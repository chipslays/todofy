<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Todo;

class TodoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todo', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->json('data')->nullable();
            $table->tinyInteger('status')->default(Todo::STATUS_ACTIVE);
            $table->tinyInteger('shared')->default(Todo::SHARE_PRIVATE);
            $table->boolean('pinned')->default(Todo::RESET);
            $table->boolean('collapsed')->default(Todo::RESET);
            $table->bigInteger('views')->default(0);
            $table->bigInteger('author_id');
            $table->bigInteger('category_id');
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
        Schema::dropIfExists('todo');
    }
}
