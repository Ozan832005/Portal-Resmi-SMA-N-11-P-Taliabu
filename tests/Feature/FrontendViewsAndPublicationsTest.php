<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Video;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class FrontendViewsAndPublicationsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createFrontendTables();
        $this->createSiteVisitStatisticsTable();
    }

    public function test_a_news_article_view_is_counted_once_per_session(): void
    {
        $article = new Berita;
        $article->title = 'Berita tes';
        $article->slug = 'berita-tes';
        $article->content = 'Isi berita';
        $article->kategori_id = null;
        $article->thumbnail = '';
        $article->is_active = '0';
        $article->created_by = null;
        $article->views = 0;
        $article->save();

        $this->get('/berita/berita-tes')->assertOk()->assertSee('1 dilihat');
        $this->get('/berita/berita-tes')->assertOk()->assertSee('1 dilihat');

        $this->assertDatabaseHas('beritas', [
            'id' => $article->id,
            'views' => 1,
        ]);

        $this->withSession(['viewed_articles' => []])
            ->get('/berita/berita-tes')
            ->assertOk()
            ->assertSee('2 dilihat');

        $this->assertDatabaseHas('beritas', [
            'id' => $article->id,
            'views' => 2,
        ]);
    }

    public function test_homepage_counts_active_guest_and_murid_accounts_as_students(): void
    {
        $this->createHomepageTables();

        DB::table('users')->insert([
            ['name' => 'Calon siswa aktif', 'role' => 'Guest', 'status' => 'Aktif'],
            ['name' => 'Siswa aktif', 'role' => 'Murid', 'status' => 'Aktif'],
            ['name' => 'Calon siswa nonaktif', 'role' => 'Guest', 'status' => 'Tidak Aktif'],
            ['name' => 'Guru aktif', 'role' => 'Guru', 'status' => 'Aktif'],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('data-target="2"', false);
    }

    public function test_homepage_defaults_to_indonesian_and_switches_all_static_copy_to_english(): void
    {
        $this->createHomepageTables();

        DB::table('videos')->insert([
            'title' => 'School event video',
            'desc' => 'School activity',
            'url' => 'https://www.youtube.com/watch?v=abcDEF12345',
            'is_active' => '0',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('<html class="no-js" lang="id">', false)
            ->assertSee('Beranda')
            ->assertSee('Kenali para pendidik yang berbagi ilmu dan membimbing masa depan siswa.');

        $this->from('/')
            ->post(route('language.switch', 'en'))
            ->assertRedirect('/')
            ->assertSessionHas('locale', 'en');

        $this->get('/')
            ->assertOk()
            ->assertSee('<html class="no-js" lang="en">', false)
            ->assertSee('Home')
            ->assertSee('Our Teachers')
            ->assertSee('Meet the educators who share knowledge and guide students toward the future.')
            ->assertSee('News &amp; Events', false)
            ->assertSee('Publications');
    }

    public function test_homepage_stat_widgets_show_live_database_totals(): void
    {
        $this->createHomepageTables();

        for ($index = 1; $index <= 5; $index++) {
            DB::table('users')->insert([
                'name' => 'Guru '.$index,
                'role' => 'Guru',
                'status' => 'Aktif',
            ]);
        }

        DB::table('users')->insert([
            'name' => 'Siswa aktif',
            'role' => 'Guest',
            'status' => 'Aktif',
        ]);

        DB::table('fasilitas')->insert([
            ['nama' => 'Laboratorium', 'is_active' => '0'],
            ['nama' => 'Perpustakaan', 'is_active' => '0'],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Guru terdaftar')
            ->assertSee('Siswa terdaftar')
            ->assertSee('Fasilitas sekolah')
            ->assertSee('data-target="5"', false)
            ->assertSee('data-target="1"', false)
            ->assertSee('data-target="2"', false);
    }

    public function test_homepage_shows_anonymous_visit_trends_after_publications_and_counts_once_per_session(): void
    {
        $this->createHomepageTables();

        DB::table('videos')->insert([
            'title' => 'Publikasi kegiatan sekolah',
            'desc' => 'Dokumentasi kegiatan.',
            'url' => 'https://www.youtube.com/watch?v=abcDEF12345',
            'is_active' => '0',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 Chrome/155.0.0.0 Safari/537.36',
        ])->get('/');

        $response
            ->assertOk()
            ->assertSee('AKSES PUBLIK')
            ->assertSee('Kunjungan situs')
            ->assertSee('Statistik dan tren kunjungan browser ke portal')
            ->assertSee('Total kunjungan')
            ->assertSee('Tren kunjungan')
            ->assertSee('Browser pengunjung')
            ->assertSee('Chrome')
            ->assertSee('data-visit-total="1"', false);

        $content = (string) $response->getContent();
        $publicationPosition = strpos($content, 'km-publication-wrap');
        $analyticsPosition = strpos($content, 'km-visit-panel');

        $this->assertNotFalse($publicationPosition);
        $this->assertNotFalse($analyticsPosition);
        $this->assertLessThan($analyticsPosition, $publicationPosition);

        $this->get('/')
            ->assertOk()
            ->assertSee('data-visit-total="1"', false);

        $this->assertDatabaseHas('site_visit_statistics', [
            'visit_date' => now()->toDateString(),
            'browser' => 'Chrome',
            'visits' => 1,
        ]);
    }

    public function test_homepage_renders_teacher_cards_with_subject_and_social_links(): void
    {
        $this->createHomepageTables();

        $teacherId = DB::table('users')->insertGetId([
            'name' => 'Guru Informatika',
            'email' => 'guru@example.test',
            'role' => 'Guru',
            'status' => 'Aktif',
            'foto_profile' => null,
        ]);
        DB::table('users_details')->insert([
            'user_id' => $teacherId,
            'role' => 'Guru',
            'mengajar' => 'Informatika',
            'website' => 'https://school.example.test',
            'instagram' => 'guru.sma11',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('km-teacher-card', false)
            ->assertSee('Guru Informatika')
            ->assertSee('Informatika')
            ->assertSee('SMA Negeri 11 Pulau Taliabu')
            ->assertSee('Website Guru Informatika')
            ->assertSee('Instagram Guru Informatika')
            ->assertSee('km-teacher-card__placeholder', false);
    }

    public function test_every_youtube_publication_is_available_for_inline_playback(): void
    {
        foreach ([
            ['Dokumentasi kegiatan utama', 'abcDEF12345'],
            ['Dokumentasi kegiatan kedua', 'bcdEFG23456'],
            ['Dokumentasi kegiatan ketiga', 'cdeFGH34567'],
            ['Dokumentasi kegiatan keempat', 'defGHI45678'],
        ] as $index => [$title, $videoId]) {
            $video = new Video;
            $video->title = $title;
            $video->desc = 'Kegiatan sekolah';
            $video->url = 'https://www.youtube.com/watch?v='.$videoId;
            $video->is_active = '0';
            $video->created_at = now()->addSeconds($index);
            $video->save();
        }

        $response = $this->get('/publikasi')
            ->assertOk()
            ->assertSee('km-widget-headline--static', false)
            ->assertSee('km-widget-headline--home-publication', false)
            ->assertSee('<h1 class="km-widget-headline__title">', false)
            ->assertSee('Dokumentasi kegiatan dan aktivitas sekolah.')
            ->assertDontSee('km-marquee', false)
            ->assertSee('Dokumentasi kegiatan utama')
            ->assertSee('Dokumentasi kegiatan kedua')
            ->assertSee('Dokumentasi kegiatan ketiga')
            ->assertSee('Dokumentasi kegiatan keempat')
            ->assertSee('data-embed-url="https://www.youtube.com/embed/defGHI45678?rel=0"', false)
            ->assertSee('data-embed-url="https://www.youtube.com/embed/abcDEF12345?rel=0"', false)
            ->assertSee('data-embed-url="https://www.youtube.com/embed/bcdEFG23456?rel=0"', false)
            ->assertSee('data-embed-url="https://www.youtube.com/embed/cdeFGH34567?rel=0"', false)
            ->assertSee('Putar video di situs', false)
            ->assertSee('Pemutar tidak tersedia? Buka di YouTube', false)
            ->assertDontSee('Video ini tidak tersedia untuk diputar', false)
            ->assertSee('Memuat SMA Negeri 11 Pulau Taliabu', false)
            ->assertSee('km-preloader-icon', false)
            ->assertSee('km-nav-search-toggle', false)
            ->assertSee('km-mobile-search-input', false);

        $this->assertSame(4, substr_count((string) $response->getContent(), 'class="km-publication-play-button"'));
        $this->assertSame(4, substr_count((string) $response->getContent(), 'data-embed-url='));
        $this->assertSame(0, substr_count((string) $response->getContent(), '<iframe'));
    }

    public function test_navigation_search_filters_news_by_title_and_content(): void
    {
        foreach ([
            ['Berita Hari Pendidikan', 'SMA merayakan hari pendidikan nasional.'],
            ['Prestasi Siswa', 'Tim sekolah berhasil meraih juara daerah.'],
        ] as [$title, $content]) {
            DB::table('beritas')->insert([
                'title' => $title,
                'slug' => Str::slug($title),
                'content' => $content,
                'is_active' => '0',
                'views' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->get('/berita?search=pendidikan')
            ->assertOk()
            ->assertSee('Hasil pencarian untuk')
            ->assertSee('Berita Hari Pendidikan')
            ->assertDontSee('Prestasi Siswa')
            ->assertSee('km-nav-search-toggle', false)
            ->assertSee('km-mobile-search-input', false);
    }

    public function test_publications_on_later_pages_remain_available_for_inline_playback(): void
    {
        for ($index = 0; $index < 13; $index++) {
            $video = new Video;
            $video->title = 'Dokumentasi '.$index;
            $video->desc = 'Kegiatan sekolah';
            $video->url = 'https://www.youtube.com/watch?v=abcDEF12345';
            $video->is_active = '0';
            $video->created_at = now()->addSeconds($index);
            $video->save();
        }

        $response = $this->get('/publikasi?page=2')
            ->assertOk()
            ->assertSee('Dokumentasi 0')
            ->assertSee('data-embed-url="https://www.youtube.com/embed/abcDEF12345?rel=0"', false)
            ->assertSee('Putar video di situs', false)
            ->assertDontSee('Video ini tidak tersedia untuk diputar', false);

        $this->assertSame(1, substr_count((string) $response->getContent(), 'class="km-publication-play-button"'));
        $this->assertSame(1, substr_count((string) $response->getContent(), 'data-embed-url='));
        $this->assertSame(0, substr_count((string) $response->getContent(), '<iframe'));
    }

    public function test_alumni_page_shows_active_alumni_cards_and_more_link(): void
    {
        DB::table('users')->insert([
            ['name' => 'Alumni Aktif', 'role' => 'Alumni', 'status' => 'Aktif', 'foto_profile' => 'alumni.jpg'],
            ['name' => 'Alumni Nonaktif', 'role' => 'Alumni', 'status' => 'Tidak Aktif', 'foto_profile' => null],
            ['name' => 'Siswa Aktif', 'role' => 'Murid', 'status' => 'Aktif', 'foto_profile' => null],
        ]);

        $response = $this->get('/alumni')
            ->assertOk()
            ->assertSee('Alumni SMA 11')
            ->assertSee('Kenali alumni kami')
            ->assertSee('Alumni Aktif')
            ->assertSee('Foto Alumni Aktif')
            ->assertSee('Lihat alumni lainnya')
            ->assertSee('href="'.route('alumni.semua').'"', false)
            ->assertDontSee('Alumni Nonaktif')
            ->assertDontSee('Siswa Aktif');

        $this->assertSame(1, substr_count((string) $response->getContent(), 'class="km-alumni-card"'));
        $this->assertSame(2, substr_count((string) $response->getContent(), 'data-alumni-scroll='));
    }

    public function test_all_alumni_page_paginates_active_alumni(): void
    {
        for ($index = 1; $index <= 13; $index++) {
            DB::table('users')->insert([
                'name' => 'Alumni '.str_pad((string) $index, 2, '0', STR_PAD_LEFT),
                'role' => 'Alumni',
                'status' => 'Aktif',
            ]);
        }

        $this->get('/alumni/semua?page=2')
            ->assertOk()
            ->assertSee('Daftar Alumni')
            ->assertSee('Alumni 13')
            ->assertDontSee('Alumni 12');
    }

    public function test_public_library_catalog_groups_books_and_links_checkout_to_login(): void
    {
        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('authors', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('publishers', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('books', function (Blueprint $table): void {
            $table->id();
            $table->string('book_code');
            $table->string('name');
            $table->text('description');
            $table->string('thumbnail')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('publisher_id');
            $table->integer('publication_year');
            $table->string('isbn');
            $table->integer('stock');
            $table->boolean('is_available')->default(false);
            $table->timestamps();
        });

        $novelCategoryId = DB::table('categories')->insertGetId([
            'name' => 'Novel',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $scienceCategoryId = DB::table('categories')->insertGetId([
            'name' => 'Sains',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $authorId = DB::table('authors')->insertGetId([
            'name' => 'Penulis Sekolah',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $publisherId = DB::table('publishers')->insertGetId([
            'name' => 'Penerbit Sekolah',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ([
            ['Novel Inspiratif', $novelCategoryId, 3],
            ['Sains untuk Semua', $scienceCategoryId, 0],
        ] as [$name, $categoryId, $stock]) {
            DB::table('books')->insert([
                'book_code' => strtolower(str_replace(' ', '-', $name)),
                'name' => $name,
                'description' => 'Koleksi bacaan perpustakaan sekolah.',
                'thumbnail' => '',
                'category_id' => $categoryId,
                'author_id' => $authorId,
                'publisher_id' => $publisherId,
                'publication_year' => 2025,
                'isbn' => '9781234567890',
                'stock' => $stock,
                'is_available' => $stock > 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->get('/katalog-perpustakaan')
            ->assertOk()
            ->assertSee('Novel')
            ->assertSee('Sains')
            ->assertSee('Novel Inspiratif')
            ->assertSee('Sains untuk Semua')
            ->assertSee('href="'.route('login').'"', false)
            ->assertSee('Pinjam buku')
            ->assertSee('Stok habis');
    }

    public function test_login_page_uses_school_branding(): void
    {
        DB::table('footers')->insert([
            'logo' => 'logo-sma11.png',
        ]);

        $this->get('/login')
            ->assertOk()
            ->assertSee('storage/images/logo/logo-sma11.png')
            ->assertSee('Logo SMA Negeri 11 Pulau Taliabu')
            ->assertSee('SMA 11');
    }

    public function test_an_event_view_is_counted_once_per_session(): void
    {
        $eventId = DB::table('events')->insertGetId([
            'title' => 'Acara tes',
            'desc' => 'Deskripsi acara',
            'slug' => 'acara-tes',
            'content' => 'Isi acara',
            'thumbnail' => '',
            'acara' => '2026-10-27 10:00:00',
            'lokasi' => 'Sekolah',
            'is_active' => '0',
            'views' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get('/event/acara-tes')->assertOk()->assertSee('1 dilihat');
        $this->get('/event/acara-tes')->assertOk()->assertSee('1 dilihat');

        $this->assertDatabaseHas('events', [
            'id' => $eventId,
            'views' => 1,
        ]);
    }

    private function createFrontendTables(): void
    {
        Schema::create('jurusans', function (Blueprint $table): void {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('slug')->nullable();
            $table->string('is_active')->default('0');
        });

        Schema::create('kegiatans', function (Blueprint $table): void {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('slug')->nullable();
            $table->string('is_active')->default('0');
        });

        Schema::create('footers', function (Blueprint $table): void {
            $table->id();
            $table->string('logo')->nullable();
        });

        Schema::create('footer_galleries', function (Blueprint $table): void {
            $table->id();
            $table->string('image')->nullable();
            $table->string('caption')->nullable();
            $table->integer('urutan')->default(0);
            $table->string('is_active')->default('0');
        });

        Schema::create('kategori_beritas', function (Blueprint $table): void {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('is_active')->default('0');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->string('role')->nullable();
            $table->string('status')->nullable();
            $table->string('foto_profile')->nullable();
        });

        Schema::create('beritas', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->text('content');
            $table->integer('kategori_id')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('is_active')->default('0');
            $table->integer('created_by')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
        });

        Schema::create('videos', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->text('desc');
            $table->string('url');
            $table->string('is_active')->default('0');
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->text('desc');
            $table->string('slug');
            $table->text('content');
            $table->string('thumbnail')->nullable();
            $table->dateTime('acara');
            $table->string('lokasi')->nullable();
            $table->string('is_active')->default('0');
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
        });
    }

    private function createHomepageTables(): void
    {
        Schema::create('abouts', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('desc');
            $table->string('image');
            $table->string('is_active')->default('0');
            $table->timestamps();
        });

        Schema::create('image_sliders', function (Blueprint $table): void {
            $table->id();
            $table->string('image');
            $table->string('desc')->nullable();
            $table->integer('urutan')->default(0);
            $table->string('is_active')->default('0');
            $table->timestamps();
        });

        Schema::create('fasilitas', function (Blueprint $table): void {
            $table->id();
            $table->string('nama');
            $table->text('desc')->nullable();
            $table->string('image')->nullable();
            $table->string('is_active')->default('0');
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('users_details', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('role')->nullable();
            $table->string('mengajar')->nullable();
            $table->string('website')->nullable();
            $table->string('linkedln')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
        });
    }

    private function createSiteVisitStatisticsTable(): void
    {
        Schema::create('site_visit_statistics', function (Blueprint $table): void {
            $table->id();
            $table->date('visit_date');
            $table->string('browser', 30);
            $table->unsignedBigInteger('visits')->default(0);
            $table->unique(['visit_date', 'browser']);
        });
    }
}
