<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
        ' CREATE TRIGGER insert_role
        AFTER INSERT ON users
        FOR EACH ROW
        BEGIN
            IF NEW.email = "Admin@uae.ac.ma" THEN
                INSERT INTO role_users (user_id, role_id) VALUES (NEW.id, 1);
            ELSEIF NEW.apogee IS NULL THEN
                INSERT INTO role_users (user_id, role_id) VALUES (NEW.id, 2);
            ELSE
                INSERT INTO role_users (user_id, role_id) VALUES (NEW.id, 3);
            END IF;
        END;');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
