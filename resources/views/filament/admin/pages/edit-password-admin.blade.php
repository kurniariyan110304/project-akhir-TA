<x-filament-panels::page>
    <div class="max-w-xl rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <form wire:submit="save" class="space-y-6">
            {{ $this->form }}

            <div class="flex justify-end gap-3">
                <x-filament::button
                    tag="a"
                    color="gray"
                    href="{{ \App\Filament\Admin\Pages\ProfilAdmin::getUrl() }}"
                >
                    Kembali
                </x-filament::button>

                <x-filament::button type="submit">
                    Simpan Password
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>