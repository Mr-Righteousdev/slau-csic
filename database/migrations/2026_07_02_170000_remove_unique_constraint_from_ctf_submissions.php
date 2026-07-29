<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite requires table recreation to drop a unique constraint
        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement('
            CREATE TABLE ctf_submissions_v2 (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                ctf_challenge_id INTEGER NOT NULL,
                user_id INTEGER NOT NULL,
                submitted_flag VARCHAR NOT NULL,
                is_correct TINYINT(1) NOT NULL,
                points_awarded INTEGER NOT NULL DEFAULT 0,
                attempt_number INTEGER NOT NULL DEFAULT 1,
                ip_address VARCHAR(45) DEFAULT NULL,
                submitted_at DATETIME NOT NULL,
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                FOREIGN KEY (ctf_challenge_id) REFERENCES ctf_challenges(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ');

        DB::statement('INSERT INTO ctf_submissions_v2 SELECT * FROM ctf_submissions');
        DB::statement('DROP TABLE ctf_submissions');
        DB::statement('ALTER TABLE ctf_submissions_v2 RENAME TO ctf_submissions');

        DB::statement('CREATE INDEX ctf_submissions_ctf_challenge_id_is_correct_index ON ctf_submissions (ctf_challenge_id, is_correct)');
        DB::statement('CREATE INDEX ctf_submissions_user_id_is_correct_index ON ctf_submissions (user_id, is_correct)');

        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement('
            CREATE TABLE ctf_submissions_v2 (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                ctf_challenge_id INTEGER NOT NULL,
                user_id INTEGER NOT NULL,
                submitted_flag VARCHAR NOT NULL,
                is_correct TINYINT(1) NOT NULL,
                points_awarded INTEGER NOT NULL DEFAULT 0,
                attempt_number INTEGER NOT NULL DEFAULT 1,
                ip_address VARCHAR(45) DEFAULT NULL,
                submitted_at DATETIME NOT NULL,
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                FOREIGN KEY (ctf_challenge_id) REFERENCES ctf_challenges(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                UNIQUE(ctf_challenge_id, user_id)
            )
        ');

        DB::statement('INSERT INTO ctf_submissions_v2 SELECT * FROM ctf_submissions');
        DB::statement('DROP TABLE ctf_submissions');
        DB::statement('ALTER TABLE ctf_submissions_v2 RENAME TO ctf_submissions');

        DB::statement('CREATE INDEX ctf_submissions_ctf_challenge_id_is_correct_index ON ctf_submissions (ctf_challenge_id, is_correct)');
        DB::statement('CREATE INDEX ctf_submissions_user_id_is_correct_index ON ctf_submissions (user_id, is_correct)');

        DB::statement('PRAGMA foreign_keys = ON');
    }
};
