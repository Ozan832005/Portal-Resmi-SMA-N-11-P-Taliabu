<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserUsernameTest extends TestCase
{
    public function test_repeated_names_receive_distinct_usernames_when_a_previous_candidate_exists(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('username')->unique();
            $table->timestamps();
        });

        $randomStrings = ['fixed-random', 'fixed-random', 'next-random'];
        Str::createRandomStringsUsing(static function () use (&$randomStrings): string {
            return (string) array_shift($randomStrings);
        });

        try {
            $firstUsername = User::generateUsername('Nama Contoh');
            User::query()->create(['username' => $firstUsername]);
            $secondUsername = User::generateUsername('Nama Contoh');

            $this->assertNotSame($firstUsername, $secondUsername);
            $this->assertSame('nama-contoh-fixed-random', $firstUsername);
            $this->assertSame('nama-contoh-next-random', $secondUsername);
        } finally {
            Str::createRandomStringsNormally();
        }
    }
}
