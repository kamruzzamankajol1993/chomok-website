<?php

namespace App\Providers;

use App\Models\Branch;
use App\Models\HomepageContent;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Shared website-wide services can be registered here when needed.
    }

    public function boot(): void
    {
        $setting = null;
        $homepageContent = null;
        $siteBranches = collect();

        try {
            $setting = Setting::query()->first();
            $homepageContent = HomepageContent::query()->first();
            $siteBranches = Branch::query()->where('status', 'active')->orderBy('name')->get();
        } catch (Throwable) {
            // Keep artisan/install commands usable before the shared DB is available.
        }

        $adminBaseUrl = rtrim((string) ($setting?->admin_panel_url ?: env('ADMIN_PANEL_URL', '')), '/');
        $adminAssetUrl = static function (?string $path) use ($adminBaseUrl): string {
            if (! $path) {
                return '';
            }
            if (preg_match('~^https?://~i', $path)) {
                return $path;
            }
            return $adminBaseUrl.'/'.ltrim($path, '/');
        };

        $whatsappNumber = preg_replace('/\D+/', '', (string) ($setting?->phone ?? ''));
        if (str_starts_with($whatsappNumber, '00')) {
            $whatsappNumber = substr($whatsappNumber, 2);
        } elseif ($whatsappNumber !== '' && str_starts_with($whatsappNumber, '0')) {
            $whatsappNumber = '88'.$whatsappNumber;
        }
        $whatsappUrl = $whatsappNumber !== '' ? 'https://wa.me/'.$whatsappNumber : null;

        $websiteBaseUrl = rtrim((string) ($setting?->website_url ?: config('app.url')), '/');
        $siteLinkUrl = static function (?string $link, ?string $fallback = null) use ($websiteBaseUrl): string {
            $link = trim((string) $link);
            if ($link === '') {
                return (string) ($fallback ?: $websiteBaseUrl.'/');
            }
            if (preg_match('~^(?:https?://|mailto:|tel:|#)~i', $link)) {
                return $link;
            }

            $legacy = [
                'index.php' => '/',
                'menu.php' => '/menu',
                'about.php' => '/about',
                'contact.php' => '/contact',
                'branch.php' => '/branch',
                'shop.php' => '/branch',
                'book.php' => '/menu',
            ];
            [$path, $fragment] = array_pad(explode('#', ltrim($link, '/'), 2), 2, null);
            $path = $legacy[$path] ?? '/'.ltrim($path, '/');
            $url = $websiteBaseUrl.($path === '/' ? '/' : $path);

            return $fragment ? $url.'#'.$fragment : $url;
        };

        View::share([
            'siteSetting' => $setting,
            'whatsappUrl' => $whatsappUrl,
            'adminPanelBaseUrl' => $adminBaseUrl,
            'adminAssetUrl' => $adminAssetUrl,
            'siteLinkUrl' => $siteLinkUrl,
            'siteSeoDescription' => trim((string) ($homepageContent?->about_paragraph_text ?: 'Chomok Restaurant - fresh food, online ordering and delivery.')),
            'siteBranches' => $siteBranches,
            'globalHomepageContent' => $homepageContent,
        ]);

        View::composer('*', function ($view): void {
            $cart = session('cart', []);
            $view->with('globalCartCount', collect($cart)->sum(fn ($row) => (int) ($row['quantity'] ?? 0)));
        });
    }
}
