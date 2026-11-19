<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Class\ClassModel;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/**
 * Aggregator data halaman untuk frontend (BFF).
 *
 * Satu request = semua data yang dibutuhkan satu halaman. Query selalu
 * dijalankan fresh (tanpa cache konten) supaya perubahan dari admin langsung
 * terlihat; ETag + no-cache hanya menghemat bandwidth saat isi tidak berubah.
 *
 * Implementasi memanggil action controller API yang sudah ada agar bentuk
 * respons tetap konsisten dengan endpoint individual (tanpa duplikasi query).
 */
class BatchController extends Controller
{
    public function show(Request $request, string $page): JsonResponse
    {
        try {
            $data = match ($page) {
                'home' => $this->home(),
                'about' => $this->about(),
                'classes' => $this->classesPage(),
                'class-detail' => $this->classDetail($request),
                'blog' => $this->blog(),
                'blog-detail' => $this->blogDetail($request),
                'packages' => $this->packagesPage($request),
                'package-detail' => $this->packageDetail($request),
                'instructors' => $this->instructorsPage(),
                'events' => $this->eventsPage($request),
                'gallery' => $this->gallery(),
                'contact' => $this->contactPage(),
                'page-detail' => $this->pageDetail($request),
                'schedules' => $this->schedules($request),
                default => null,
            };
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid request parameters',
                'errors' => $e->errors(),
                'data' => null,
            ], 422);
        } catch (HttpExceptionInterface $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() ?: 'Batch request failed',
                'data' => null,
            ], $e->getStatusCode());
        } catch (Throwable $e) {
            Log::error('Batch API error', [
                'page' => $page,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch page data',
                'data' => null,
            ], 500);
        }

        if ($data === null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unknown batch page',
                'data' => null,
            ], 404);
        }

        $response = response()->json([
            'status' => 'success',
            'message' => 'Batch data retrieved successfully',
            'page' => $page,
            'data' => $data,
        ], 200);

        // Realtime: klien selalu revalidasi. 304 hanya bila isi benar-benar sama.
        $response->setEtag(md5((string) $response->getContent()));
        $response->headers->set('Cache-Control', 'no-cache, must-revalidate');
        $response->isNotModified($request);

        return $response;
    }

    private function home(): array
    {
        return [
            'testimonials' => array_slice($this->data(
                app(TestimonialController::class)->index(),
                'testimonials',
            ) ?? [], 0, 3),
            'classes' => $this->classes(4),
            'packages' => $this->packages(['per_page' => 3])['packages'] ?? [],
            'events' => $this->events(['per_page' => 3])['events'] ?? [],
        ];
    }

    private function about(): array
    {
        return [
            'stats' => [
                'members' => User::role('user')->count(),
                'instructors' => User::role('instruktur')->count(),
                'classes' => ClassModel::query()->where('is_active', 'active')->count(),
            ],
            'articles' => array_slice($this->articles(), 0, 3),
        ];
    }

    private function classesPage(): array
    {
        return ['classes' => $this->classes(50)];
    }

    private function classDetail(Request $request): array
    {
        $slug = $this->slug($request);

        return [
            'class' => $this->requiredData(
                app(ClassController::class)->show($slug),
                'Class',
            ),
        ];
    }

    private function blog(): array
    {
        $articles = $this->articles();

        return [
            'articles' => $articles,
            'total' => count($articles),
        ];
    }

    private function blogDetail(Request $request): array
    {
        $slug = $this->slug($request);
        $post = $this->requiredData(
            app(ArticleController::class)->show($slug),
            'Article',
        );
        $all = $this->articles();
        $category = $post['category'] ?? null;

        $others = array_values(array_filter(
            $all,
            fn ($article) => ($article['slug'] ?? null) !== ($post['slug'] ?? null),
        ));
        $sameCategory = array_values(array_filter(
            $others,
            fn ($article) => $category && ($article['category'] ?? null) === $category,
        ));
        $rest = array_values(array_filter(
            $others,
            fn ($article) => !$category || ($article['category'] ?? null) !== $category,
        ));

        return [
            'post' => $post,
            'related' => array_slice(array_merge($sameCategory, $rest), 0, 3),
            'latest_classes' => array_slice($this->classes(3), 0, 3),
        ];
    }

    private function packagesPage(Request $request): array
    {
        return $this->packages(
            $request->only(['search', 'filter', 'sort', 'page', 'per_page']),
        );
    }

    private function packageDetail(Request $request): array
    {
        return [
            'package' => $this->requiredData(
                app(PackageController::class)->show($this->slug($request)),
                'Package',
            ),
        ];
    }

    private function instructorsPage(): array
    {
        $request = Request::create('/api/instructors', 'GET', ['per_page' => 20]);

        return $this->paginated(
            app(InstructorController::class)->index($request),
            'instructors',
        );
    }

    private function eventsPage(Request $request): array
    {
        return $this->events(
            $request->only([
                'search',
                'filter',
                'date_from',
                'date_to',
                'page',
                'per_page',
            ]),
        );
    }

    private function gallery(): array
    {
        $request = Request::create('/api/banners', 'GET', ['kategori' => 'galeri']);
        $banners = $this->data(app(BannerController::class)->index($request), 'banners') ?? [];

        return [
            'gallery' => array_values(array_filter(
                $banners,
                fn ($banner) => !empty($banner['gambar'])
                    && !str_contains((string) $banner['gambar'], 'default.png'),
            )),
        ];
    }

    private function contactPage(): array
    {
        return [
            'captcha' => $this->data(app(ContactController::class)->captcha(), 'captcha'),
            'faqs' => $this->data(app(FaqController::class)->index(), 'faqs') ?? [],
        ];
    }

    private function pageDetail(Request $request): array
    {
        return [
            'page' => $this->requiredData(
                app(PageController::class)->show($this->slug($request)),
                'Page',
            ),
        ];
    }

    private function schedules(Request $request): array
    {
        $date = (string) ($request->input('date') ?: now()->toDateString());

        // Daftar venue diambil tanpa filter venue/level/time agar
        // pilihan filter tidak saling menghilang saat user memilih salah satu.
        $allRequest = Request::create('/api/class-schedules', 'GET', [
            'date' => $date,
            'per_page' => 50,
        ]);
        $all = $this->data(
            app(ClassScheduleController::class)->index($allRequest),
            'schedules',
        ) ?? [];

        $venues = collect($all)
            ->pluck('venue')
            ->filter(fn ($venue) => is_array($venue) && !empty($venue['uuid']))
            ->unique('uuid')
            ->values()
            ->all();

        $filteredRequest = Request::create('/api/class-schedules', 'GET', [
            'date' => $date,
            'per_page' => 50,
        ] + $request->only(['level', 'time', 'venue_uuid']));
        $filtered = $this->paginated(
            app(ClassScheduleController::class)->index($filteredRequest),
            'schedules',
        );

        return [
            'date' => $date,
            'schedules' => $filtered['schedules'] ?? [],
            'venues' => $venues,
            'meta' => $filtered['meta'] ?? [],
        ];
    }

    private function classes(int $perPage): array
    {
        $request = Request::create('/api/classes', 'GET', [
            'per_page' => $perPage,
        ]);

        return $this->data(
            app(ClassController::class)->index($request),
            'classes',
        ) ?? [];
    }

    private function articles(): array
    {
        return $this->data(
            app(ArticleController::class)->index(),
            'articles',
        ) ?? [];
    }

    private function packages(array $params): array
    {
        $request = Request::create('/api/packages', 'GET', $params);

        return $this->paginated(
            app(PackageController::class)->index($request),
            'packages',
        );
    }

    private function events(array $params): array
    {
        $request = Request::create('/api/events', 'GET', $params);

        return $this->paginated(
            app(EventController::class)->index($request),
            'events',
        );
    }

    private function slug(Request $request): string
    {
        $validated = $request->validate([
            'slug' => ['required', 'string', 'max:255'],
        ]);

        return $validated['slug'];
    }

    private function data(JsonResponse $response, string $label): mixed
    {
        $body = $response->getData(true);

        if ($response->getStatusCode() >= 400) {
            abort(
                $response->getStatusCode(),
                $body['message'] ?? "{$label} request failed",
            );
        }

        return $body['data'] ?? null;
    }

    private function requiredData(JsonResponse $response, string $label): mixed
    {
        $data = $this->data($response, $label);

        if ($data === null) {
            abort(404, "{$label} not found");
        }

        return $data;
    }

    private function paginated(JsonResponse $response, string $key): array
    {
        $body = $response->getData(true);

        if ($response->getStatusCode() >= 400) {
            abort(
                $response->getStatusCode(),
                $body['message'] ?? 'Request failed',
            );
        }

        return [
            $key => $body['data'] ?? [],
            'meta' => $body['meta'] ?? [],
        ];
    }
}
