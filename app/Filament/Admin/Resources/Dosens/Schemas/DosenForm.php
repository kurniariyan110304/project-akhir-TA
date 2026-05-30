<?php

namespace App\Filament\Admin\Resources\Dosens\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DosenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nidn')
                    ->label('NIDN')
                    ->required()
                    ->maxLength(20),

                TextInput::make('nama')
                    ->label('Nama Dosen')
                    ->required()
                    ->maxLength(50),

                TextInput::make('gelar_depan')
                    ->label('Gelar Depan')
                    ->maxLength(10),

                TextInput::make('gelar_belakang')
                    ->label('Gelar Belakang')
                    ->maxLength(10),

                Select::make('jk')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->required(),

                TextInput::make('tmp_lahir')
                    ->label('Tempat Lahir')
                    ->maxLength(30),

                DatePicker::make('tgl_lahir')
                    ->label('Tanggal Lahir'),

                Select::make('prodi_id')
                    ->label('Program Studi')
                    ->relationship('prodi', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('user_id')
                    ->label('Akun Login Dosen')
                    ->options(function ($record) {
                        return User::query()
                            ->where('role', 'dosen')
                            ->where(function ($query) use ($record) {
                                $query->whereDoesntHave('dosen');

                                if ($record?->user_id) {
                                    $query->orWhere('id', $record->user_id);
                                }
                            })
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}