<?php

namespace App\Filament\Admin\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class EditPasswordAdmin extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-key';

    protected static ?string $title = 'Edit Password';

    protected static ?string $slug = 'edit-password-admin';

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.admin.pages.edit-password-admin';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('current_password')
                    ->label('Password Lama')
                    ->password()
                    ->revealable()
                    ->required(),

                TextInput::make('password')
                    ->label('Password Baru')
                    ->password()
                    ->revealable()
                    ->minLength(8)
                    ->required(),

                TextInput::make('password_confirmation')
                    ->label('Konfirmasi Password Baru')
                    ->password()
                    ->revealable()
                    ->same('password')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $user = auth()->user();

        if (! Hash::check($data['current_password'], $user->password)) {
            Notification::make()
                ->title('Password lama salah')
                ->danger()
                ->send();

            return;
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        $this->form->fill();

        Notification::make()
            ->title('Password berhasil diubah')
            ->success()
            ->send();
    }
}