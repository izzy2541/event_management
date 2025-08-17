<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

             /** creating a one to many relationship between the user and events,
            *which means that the user can be the owner or administrator for many events
            *this means that the column specifying the relationship goes into the events table, so the user owns events,
            * foreignIdFor needs to specify at least one argument which is the model that this relates to,
            * this would create both the column that will hold the relationship and add a foreign key for that columns*/

            $table->foreignIdFor(User::class);
            $table->string('name');
            //nullable = column can contain nullable value (is optional)
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
