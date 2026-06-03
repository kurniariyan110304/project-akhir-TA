<x-filament-panels::page>
    <div class="mx-auto max-w-5xl px-6 py-8">
        <x-filament::section>
            <div class="space-y-8 p-4">
                {{-- Header Profil --}}
                <div class="flex items-center gap-5">
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-gray-900 text-2xl font-bold text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            {{ auth()->user()->name }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ auth()->user()->email }}
                        </p>

                        <span class="mt-3 inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                            {{ strtoupper(auth()->user()->role) }}
                        </span>
                    </div>
                </div>

                {{-- Table Profil --}}
                <div class="overflow-hidden rounded-xl border border-gray-200">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-200 px-6 py-4 text-left font-semibold text-gray-700">
                                    Field
                                </th>
                                <th class="border border-gray-200 px-6 py-4 text-left font-semibold text-gray-700">
                                    Data Admin
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="border border-gray-200 bg-gray-50 px-6 py-4 font-medium text-gray-600">
                                    User ID
                                </td>
                                <td class="border border-gray-200 px-6 py-4 text-gray-900">
                                    {{ auth()->user()->id }}
                                </td>
                            </tr>

                            <tr>
                                <td class="border border-gray-200 bg-gray-50 px-6 py-4 font-medium text-gray-600">
                                    Nama
                                </td>
                                <td class="border border-gray-200 px-6 py-4 text-gray-900">
                                    {{ auth()->user()->name }}
                                </td>
                            </tr>

                            <tr>
                                <td class="border border-gray-200 bg-gray-50 px-6 py-4 font-medium text-gray-600">
                                    Email
                                </td>
                                <td class="border border-gray-200 px-6 py-4 text-gray-900">
                                    {{ auth()->user()->email }}
                                </td>
                            </tr>

                            <tr>
                                <td class="border border-gray-200 bg-gray-50 px-6 py-4 font-medium text-gray-600">
                                    Role
                                </td>
                                <td class="border border-gray-200 px-6 py-4">
                                    <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                        {{ strtoupper(auth()->user()->role) }}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td class="border border-gray-200 bg-gray-50 px-6 py-4 font-medium text-gray-600">
                                    Tanggal Dibuat
                                </td>
                                <td class="border border-gray-200 px-6 py-4 text-gray-900">
                                    {{ auth()->user()->created_at?->format('d M Y H:i') ?? '-' }}
                                </td>
                            </tr>

                            <tr>
                                <td class="border border-gray-200 bg-gray-50 px-6 py-4 font-medium text-gray-600">
                                    Terakhir Update
                                </td>
                                <td class="border border-gray-200 px-6 py-4 text-gray-900">
                                    {{ auth()->user()->updated_at?->format('d M Y H:i') ?? '-' }}
                                </td>
                            </tr>

                            <tr>
                                <td class="border border-gray-200 bg-gray-50 px-6 py-4 font-medium text-gray-600">
                                    Password
                                </td>
                                <td class="border border-gray-200 px-6 py-4">
                                    <x-filament::button
                                        tag="a"
                                        href="{{ \App\Filament\Admin\Pages\EditPasswordAdmin::getUrl() }}"
                                        icon="heroicon-o-key"
                                    >
                                        Edit Password
                                    </x-filament::button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>