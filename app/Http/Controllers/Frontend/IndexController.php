<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Berita;
use App\Models\Curriculum;
use App\Models\Events;
use App\Models\Fasilitas;
use App\Models\Footer;
use App\Models\ImageSlider;
use App\Models\Jurusan;
use App\Models\KategoriBerita;
use App\Models\Kegiatan;
use App\Models\ProfileSekolah;
use App\Models\PublicationVideo;
use App\Models\User;
use App\Models\Video;
use App\Models\Visimisi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Perpustakaan\Entities\Book;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class IndexController extends Controller
{
    /**
     * Membuat kelas tiruan anonim yang kebal terhadap properti null apa pun
     * agar template blade tidak memicu error 'Attempt to read property on null'
     */
    private function mockEmptyObject()
    {
        return new class
        {
            public function __get($name)
            {
                return '';
            }

            public function __toString()
            {
                return '';
            }
        };
    }

    // Welcome
    public function index(): View
    {
        // Menu
        $jurusanM = Jurusan::where('is_active', '0')->get();
        $kegiatanM = Kegiatan::where('is_active', '0')->get();

        // Gambar Slider
        $slider = ImageSlider::where('is_Active', '0')->get();

        // About (Menggunakan objek tiruan jika data database kosong)
        $about = About::where('is_Active', '0')->first() ?: $this->mockEmptyObject();

        // Video (Menggunakan objek tiruan jika data database kosong)
        $latestVideo = Video::where('is_active', '0')->orderByDesc('created_at')->orderByDesc('id')->first();
        $video = $latestVideo;
        $videos = PublicationVideo::query()
            ->where('is_active', '0')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        // Pengajar
        $pengajar = User::with('userDetail')->where('status', 'Aktif')->where('role', 'Guru')->get();

        // Berita
        $berita = Berita::with(['kategori', 'user'])->where('is_active', '0')->orderBy('created_at', 'desc')->get();

        // Event
        $event = Events::where('is_active', '0')->orderBy('created_at', 'desc')->get();

        // Footer (Menggunakan objek tiruan jika data database kosong)
        $footer = Footer::first() ?: $this->mockEmptyObject();

        // Statistik ringkas + fasilitas sekolah
        $jumlahGuru = User::where('role', 'Guru')->where('status', 'Aktif')->count();
        $jumlahSiswa = User::whereIn('role', ['Guest', 'Murid'])->where('status', 'Aktif')->count();
        $fasilitas = Fasilitas::aktif();

        $today = now()->toDateString();
        $firstTrendDate = now()->subDays(6)->toDateString();
        $visitTotal = (int) DB::table('site_visit_statistics')->sum('visits');
        $visitsByDate = DB::table('site_visit_statistics')
            ->whereBetween('visit_date', [$firstTrendDate, $today])
            ->select('visit_date')
            ->selectRaw('SUM(visits) as visits')
            ->groupBy('visit_date')
            ->pluck('visits', 'visit_date');
        $dayNames = app()->getLocale() === 'en'
            ? ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
            : ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $visitTrend = collect(range(6, 0))->map(function (int $daysAgo) use ($dayNames, $visitsByDate): array {
            $date = now()->subDays($daysAgo);
            $dateString = $date->toDateString();

            return [
                'label' => $dayNames[$date->dayOfWeek],
                'date' => $dateString,
                'visits' => (int) ($visitsByDate[$dateString] ?? 0),
            ];
        });
        $visitLastSevenDays = (int) $visitTrend->sum('visits');
        $maxDailyVisits = max(1, (int) $visitTrend->max('visits'));
        $browserVisits = DB::table('site_visit_statistics')
            ->select('browser')
            ->selectRaw('SUM(visits) as visits')
            ->groupBy('browser')
            ->orderByDesc('visits')
            ->get()
            ->map(fn (object $browser): array => [
                'name' => $browser->browser,
                'visits' => (int) $browser->visits,
                'share' => $visitTotal > 0 ? (int) round(((int) $browser->visits / $visitTotal) * 100) : 0,
            ]);

        return view('frontend.welcome', compact('jurusanM', 'kegiatanM', 'slider', 'about', 'video', 'videos', 'pengajar', 'berita', 'event', 'footer', 'jumlahGuru', 'jumlahSiswa', 'fasilitas', 'visitTotal', 'visitLastSevenDays', 'maxDailyVisits', 'visitTrend', 'browserVisits'));
    }

    // Berita
    public function berita(Request $request)
    {
        // Menu
        $jurusanM = Jurusan::where('is_active', '0')->get();
        $kegiatanM = Kegiatan::where('is_active', '0')->get();

        // Footer
        $footer = Footer::first() ?: $this->mockEmptyObject();

        // Kategori
        $kategori = KategoriBerita::where('is_active', '0')->orderBy('created_at', 'desc')->get();

        // Berita
        $searchQuery = $request->query('search');
        $search = is_string($searchQuery) ? mb_substr(trim($searchQuery), 0, 100) : '';
        $kategoriQuery = $request->query('kategori');
        $activeKategoriId = is_numeric($kategoriQuery) ? (int) $kategoriQuery : 0;
        $berita = Berita::with(['kategori', 'user'])
            ->where('is_active', '0')
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('title', 'like', '%'.$search.'%')
                        ->orWhere('content', 'like', '%'.$search.'%');
                });
            })
            ->when($activeKategoriId > 0, function (Builder $query) use ($activeKategoriId): void {
                $query->where('kategori_id', $activeKategoriId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $activeKategori = $activeKategoriId > 0 ? $kategori->firstWhere('id', $activeKategoriId) : null;

        $featureItems = $search === '' && $activeKategoriId === 0 && $berita->currentPage() === 1
            ? $berita->getCollection()->take(3)
            : collect();

        return view('frontend.content.beritaAll', compact('berita', 'kategori', 'jurusanM', 'kegiatanM', 'footer', 'search', 'activeKategoriId', 'activeKategori', 'featureItems'));
    }

    // Show Detail Berita
    public function detailBerita($slug)
    {
        // Menu
        $jurusanM = Jurusan::where('is_active', '0')->get();
        $kegiatanM = Kegiatan::where('is_active', '0')->get();

        // Footer
        $footer = Footer::first() ?: $this->mockEmptyObject();

        // Kategori
        $kategori = KategoriBerita::where('is_active', '0')->orderBy('created_at', 'desc')->get();

        // Berita
        $beritaOther = Berita::where('is_active', '0')->orderBy('created_at', 'desc')->get();

        $berita = Berita::where('slug', $slug)->first();

        if (! $berita) {
            abort(404);
        }

        $viewedArticles = session()->get('viewed_articles', []);

        if (! in_array($berita->getKey(), $viewedArticles, true)) {
            $berita->increment('views');
            $viewedArticles[] = $berita->getKey();
            session()->put('viewed_articles', $viewedArticles);
        }

        $readCount = $berita->refresh()->views;

        return view('frontend.content.showBerita', compact('berita', 'kategori', 'beritaOther', 'jurusanM', 'kegiatanM', 'footer', 'readCount'));
    }

    // Events
    public function events()
    {
        // Menu
        $jurusanM = Jurusan::where('is_active', '0')->get();
        $kegiatanM = Kegiatan::where('is_active', '0')->get();

        // Footer
        $footer = Footer::first() ?: $this->mockEmptyObject();

        // Berita
        $berita = Berita::where('is_active', '0')->orderBy('created_at', 'desc')->get();

        $event = Events::where('is_active', '0')->orderBy('created_at', 'desc')->get();

        return view('frontend.content.event.eventAll', compact('event', 'berita', 'jurusanM', 'kegiatanM', 'footer'));
    }

    // Detail Event
    public function detailEvent($slug)
    {
        // Menu
        $jurusanM = Jurusan::where('is_active', '0')->get();
        $kegiatanM = Kegiatan::where('is_active', '0')->get();

        // Footer
        $footer = Footer::first() ?: $this->mockEmptyObject();

        // Berita
        $berita = Berita::where('is_active', '0')->orderBy('created_at', 'desc')->get();

        $event = Events::where('slug', $slug)->first();

        if (! $event) {
            abort(404);
        }

        $eventOther = Events::where('is_active', '0')->orderBy('created_at', 'desc')->get();
        $viewedEvents = session()->get('viewed_events', []);

        if (! in_array($event->getKey(), $viewedEvents, true)) {
            $event->increment('views');
            $viewedEvents[] = $event->getKey();
            session()->put('viewed_events', $viewedEvents);
        }

        $readCount = $event->refresh()->views;

        return view('frontend.content.event.detailEvent', compact('event', 'eventOther', 'berita', 'jurusanM', 'kegiatanM', 'footer', 'readCount'));
    }

    // Kurikulum
    public function kurikulum()
    {
        $jurusanM = Jurusan::where('is_active', '0')->get();
        $kegiatanM = Kegiatan::where('is_active', '0')->get();
        $footer = Footer::first() ?: $this->mockEmptyObject();

        $curriculum = Curriculum::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $pengajar = User::with('userDetail')->where('status', 'Aktif')->where('role', 'Guru')->get();

        return view('frontend.content.kurikulum', compact('jurusanM', 'kegiatanM', 'footer', 'curriculum', 'pengajar'));
    }

    public function publikasi()
    {
        $jurusanM = Jurusan::where('is_active', '0')->get();
        $kegiatanM = Kegiatan::where('is_active', '0')->get();
        $footer = Footer::first() ?: $this->mockEmptyObject();
        $videos = PublicationVideo::query()
            ->where('is_active', '0')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(12);

        return view('frontend.content.publikasi', compact('jurusanM', 'kegiatanM', 'footer', 'videos'));
    }

    public function alumni(): View
    {
        $kegiatanM = Kegiatan::where('is_active', '0')->get();
        $footer = Footer::first() ?: $this->mockEmptyObject();
        $alumni = User::query()
            ->where('role', 'Alumni')
            ->where('status', 'Aktif')
            ->orderBy('name')
            ->take(8)
            ->get();

        return view('frontend.content.alumni', compact('kegiatanM', 'footer', 'alumni'));
    }

    public function alumniSemua(): View
    {
        $kegiatanM = Kegiatan::where('is_active', '0')->get();
        $footer = Footer::first() ?: $this->mockEmptyObject();
        $alumni = User::query()
            ->where('role', 'Alumni')
            ->where('status', 'Aktif')
            ->orderBy('name')
            ->paginate(12);

        return view('frontend.content.alumni-all', compact('kegiatanM', 'footer', 'alumni'));
    }

    public function katalogPerpustakaan(): View
    {
        $kegiatanM = Kegiatan::where('is_active', '0')->get();
        $footer = Footer::first() ?: $this->mockEmptyObject();
        $booksByCategory = Book::with(['category', 'author', 'publisher'])
            ->orderBy('name')
            ->get()
            ->groupBy(static fn (Book $book): string => $book->category?->name ?? 'Kategori lainnya');

        return view('frontend.content.perpustakaan', compact('kegiatanM', 'footer', 'booksByCategory'));
    }

    public function sampulBuku(Book $book): BinaryFileResponse
    {
        $publicPath = $book->thumbnailStoragePath();

        if (Storage::disk('public')->exists($publicPath)) {
            return response()->file(Storage::disk('public')->path($publicPath));
        }

        $legacyPath = $book->legacyThumbnailStoragePath();

        if (Storage::disk('local')->exists($legacyPath)) {
            return response()->file(Storage::disk('local')->path($legacyPath));
        }

        abort(404);
    }

    // Profile Sekolah
    public function profileSekolah()
    {
        $jurusanM = Jurusan::where('is_active', '0')->get();
        $kegiatanM = Kegiatan::where('is_active', '0')->get();

        // Pengajar
        $pengajar = User::with('userDetail')->where('status', 'Aktif')->where('role', 'Guru')->get();

        // Footer
        $footer = Footer::first() ?: $this->mockEmptyObject();

        $profile = ProfileSekolah::first() ?: $this->mockEmptyObject();

        return view('frontend.content.profileSekolah', compact('profile', 'jurusanM', 'kegiatanM', 'pengajar', 'footer'));
    }

    // Visi dan Misi
    public function visimisi()
    {
        $jurusanM = Jurusan::where('is_active', '0')->get();
        $kegiatanM = Kegiatan::where('is_active', '0')->get();

        // Pengajar
        $pengajar = User::with('userDetail')->where('status', 'Aktif')->where('role', 'Guru')->get();

        // Footer
        $footer = Footer::first() ?: $this->mockEmptyObject();

        $visimisi = Visimisi::first() ?: $this->mockEmptyObject();

        return view('frontend.content.visimisi', compact('visimisi', 'jurusanM', 'kegiatanM', 'pengajar', 'footer'));
    }
}
