<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRoleMatchesPanel
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Kalau belum login, biarkan Filament handle (redirect ke login panel)
        if (! $user) {
            return $next($request);
        }

        // Kadang panel bisa null di beberapa route non-filament
        $panelId = Filament::getCurrentPanel()?->getId();

        // Kalau bukan request Filament panel, lanjutkan saja
        if (! $panelId) {
            return $next($request);
        }

        // Mapping panel -> role
        $allowedRoleByPanel = [
            'admin'     => 'admin',
            'dosen'     => 'dosen',
            'mahasiswa' => 'mahasiswa',
            'asdos'     => 'asdos',
        ];

        // Kalau panel tidak dikenali
        if (! isset($allowedRoleByPanel[$panelId])) {
            abort(403);
        }

        // Kalau role tidak sesuai panel
        if ($user->role !== $allowedRoleByPanel[$panelId]) {
            Auth::logout();

            // Hindari error kalau session tidak tersedia
            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            // Redirect ke login panel yang sedang diakses
            return redirect()->to('/' . $panelId . '/login');
        }

        return $next($request);
    }
}