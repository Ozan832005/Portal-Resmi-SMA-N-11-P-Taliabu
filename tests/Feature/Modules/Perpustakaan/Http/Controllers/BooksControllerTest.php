<?php

namespace Tests\Feature\Modules\Perpustakaan\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Modules\Perpustakaan\Entities\Book;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class BooksControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createBookTables();
    }

    public function test_librarian_can_create_a_book_with_a_publicly_accessible_cover(): void
    {
        Storage::fake('public');
        $librarian = $this->createLibrarian();
        $bookDetails = $this->createBookDetails();

        $response = $this->actingAs($librarian)->post(route('books.store'), [
            ...$bookDetails,
            'name' => 'Buku Baru',
            'thumbnail' => UploadedFile::fake()->image('sampul.png'),
        ]);

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', [
            'name' => 'Buku Baru',
            'publication_year' => 2026,
        ]);

        $book = Book::query()->where('name', 'Buku Baru')->firstOrFail();
        Storage::disk('public')->assertExists($book->thumbnail);
    }

    public function test_librarian_can_edit_a_book_without_reuploading_its_cover(): void
    {
        Storage::fake('public');
        $librarian = $this->createLibrarian();
        $book = $this->createBook('Buku Lama', 'images/thumbnail/sampul-lama.png');
        Storage::disk('public')->put($book->thumbnail, 'cover');

        $this->actingAs($librarian)
            ->get(route('books.edit', $book))
            ->assertOk()
            ->assertSee('Edit Buku')
            ->assertSee(route('katalog.perpustakaan.sampul', $book), false);

        $response = $this->put(route('books.update', $book), [
            ...$this->createBookDetails(),
            'name' => 'Buku Diperbarui',
            'description' => 'Deskripsi yang diperbarui.',
        ]);

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'name' => 'Buku Diperbarui',
            'thumbnail' => 'images/thumbnail/sampul-lama.png',
        ]);
        Storage::disk('public')->assertExists('images/thumbnail/sampul-lama.png');
    }

    public function test_librarian_can_delete_a_book_without_borrowing_history(): void
    {
        Storage::fake('public');
        $librarian = $this->createLibrarian();
        $book = $this->createBook('Buku untuk dihapus', 'images/thumbnail/sampul-hapus.png');
        Storage::disk('public')->put($book->thumbnail, 'cover');

        $this->actingAs($librarian)
            ->get(route('books.index'))
            ->assertOk()
            ->assertSee(route('books.edit', $book), false)
            ->assertSee(route('books.destroy', $book), false)
            ->assertSee('data-delete-message', false);

        $this->delete(route('books.destroy', $book))
            ->assertRedirect(route('books.index'));

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
        Storage::disk('public')->assertMissing('images/thumbnail/sampul-hapus.png');
    }

    public function test_librarian_cannot_delete_a_book_with_borrowing_history(): void
    {
        $librarian = $this->createLibrarian();
        $book = $this->createBook('Buku yang pernah dipinjam');

        DB::table('borrowings')->insert([
            'borrow_code' => 'BORROW-00001',
            'member_id' => 1,
            'book_id' => $book->id,
            'borrow_date' => '2026-01-01',
            'return_date' => '2026-01-08',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($librarian)
            ->delete(route('books.destroy', $book))
            ->assertRedirect(route('books.index'))
            ->assertSessionHas('error', 'Buku tidak dapat dihapus karena memiliki riwayat peminjaman.');

        $this->assertDatabaseHas('books', ['id' => $book->id]);
    }

    private function createLibrarian(): User
    {
        $role = Role::query()->create([
            'name' => 'Perpustakaan',
            'guard_name' => 'web',
        ]);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $librarian = User::query()->create([
            'name' => 'Petugas Perpustakaan',
            'username' => 'petugas-perpustakaan',
            'email' => 'perpustakaan@example.test',
            'role' => 'Perpustakaan',
            'status' => 'Aktif',
            'password' => bcrypt('password'),
        ]);
        $librarian->assignRole($role);

        return $librarian;
    }

    /**
     * @return array{category_id: int, publisher_id: int, author_id: int, publication_year: int, isbn: string, stock: int, description: string}
     */
    private function createBookDetails(): array
    {
        $categoryId = DB::table('categories')->insertGetId(['name' => 'Umum']);
        $publisherId = DB::table('publishers')->insertGetId(['name' => 'Penerbit Sekolah']);
        $authorId = DB::table('authors')->insertGetId(['name' => 'Penulis Sekolah']);

        return [
            'category_id' => $categoryId,
            'publisher_id' => $publisherId,
            'author_id' => $authorId,
            'publication_year' => 2026,
            'isbn' => '9781234567890',
            'stock' => 5,
            'description' => 'Deskripsi buku untuk pengujian.',
        ];
    }

    private function createBook(string $name, string $thumbnail = 'sampul.png'): Book
    {
        $bookDetails = $this->createBookDetails();

        return Book::query()->create([
            ...$bookDetails,
            'book_code' => 'BOOK-00001',
            'name' => $name,
            'thumbnail' => $thumbnail,
        ]);
    }

    private function createBookTables(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('role');
            $table->string('status');
            $table->string('foto_profile')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('footers', function (Blueprint $table): void {
            $table->id();
            $table->string('logo')->nullable();
        });

        Schema::create('settings', function (Blueprint $table): void {
            $table->id();
            $table->boolean('isEmail')->default(false);
            $table->string('email')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });

        Schema::create('model_has_roles', function (Blueprint $table): void {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        foreach (['authors', 'categories', 'publishers'] as $tableName) {
            Schema::create($tableName, function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }

        Schema::create('books', function (Blueprint $table): void {
            $table->id();
            $table->string('book_code')->unique();
            $table->string('name')->unique();
            $table->text('description');
            $table->string('thumbnail');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('publisher_id');
            $table->integer('publication_year');
            $table->string('isbn', 50);
            $table->integer('stock');
            $table->boolean('is_available')->default(false);
            $table->timestamps();
        });

        Schema::create('borrowings', function (Blueprint $table): void {
            $table->id();
            $table->string('borrow_code')->unique();
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('book_id');
            $table->date('borrow_date');
            $table->date('return_date');
            $table->date('lateness')->nullable();
            $table->timestamps();
        });

        Schema::create('site_visit_statistics', function (Blueprint $table): void {
            $table->id();
            $table->date('visit_date');
            $table->string('browser', 30);
            $table->unsignedBigInteger('visits')->default(0);
            $table->unique(['visit_date', 'browser']);
        });
    }
}
