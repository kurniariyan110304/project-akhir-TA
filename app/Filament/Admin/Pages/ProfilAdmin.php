<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class ProfilAdmin extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $title = 'Profil Admin';

    protected static ?string $slug = 'profil-admin';

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.admin.pages.profil-admin';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }
}