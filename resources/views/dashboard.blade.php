<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <!-- Flash Message -->
                @if (session('success'))
                    <div class="mb-4 bg-green-500 text-white py-2 px-4 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-500 text-white py-2 px-4 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Header dan Tombol Tambah -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Data Lokasi</h3>
                    <a href="{{ route('lokasi.create') }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Tambah Lokasi
                    </a>
                </div>

                <!-- Tabel Data -->
                <table class="min-w-full bg-white border border-gray-300">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border">Nama Tempat</th>
                            <th class="px-4 py-2 border">Alamat</th>
                            <th class="px-4 py-2 border">Latitude</th>
                            <th class="px-4 py-2 border">Longitude</th>
                            <th class="px-4 py-2 border">Dibuat Oleh</th>
                            <th class="px-4 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lokasi as $item)
                            <tr>
                                <td class="px-4 py-2 border">{{ $item->nama_tempat }}</td>
                                <td class="px-4 py-2 border">{{ $item->alamat }}</td>
                                <td class="px-4 py-2 border">{{ $item->lat }}</td>
                                <td class="px-4 py-2 border">{{ $item->long }}</td>
                                <td class="px-4 py-2 border">{{ $item->created_by }}</td>
                                <td class="px-4 py-2 border text-center space-x-2">
                                    <a href="{{ route('lokasi.edit', $item->id) }}" 
                                       class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded">
                                        Edit
                                    </a>
                                    <form action="{{ route('lokasi.destroy', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-2 border text-center">Tidak ada data lokasi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Kontrol Paginasi -->
                <div class="mt-4">
                    {{ $lokasi->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
