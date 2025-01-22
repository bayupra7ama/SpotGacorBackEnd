<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Lokasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('lokasi.update', $lokasi->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nama Tempat -->
                    <div class="mb-4">
                        <label for="nama_tempat" class="block text-gray-700">Nama Tempat</label>
                        <input type="text" name="nama_tempat" id="nama_tempat" 
                               value="{{ old('nama_tempat', $lokasi->nama_tempat) }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm" required>
                        @error('nama_tempat')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="mb-4">
                        <label for="alamat" class="block text-gray-700">Alamat</label>
                        <textarea name="alamat" id="alamat" 
                                  class="w-full border-gray-300 rounded-md shadow-sm" required>{{ old('alamat', $lokasi->alamat) }}</textarea>
                        @error('alamat')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Latitude -->
                    <div class="mb-4">
                        <label for="lat" class="block text-gray-700">Latitude</label>
                        <input type="text" name="lat" id="lat" 
                               value="{{ old('lat', $lokasi->lat) }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm" required>
                        @error('lat')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Longitude -->
                    <div class="mb-4">
                        <label for="long" class="block text-gray-700">Longitude</label>
                        <input type="text" name="long" id="long" 
                               value="{{ old('long', $lokasi->long) }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm" required>
                        @error('long')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Perlengkapan -->
                    <div class="mb-4">
                        <label for="perlengkapan" class="block text-gray-700">Perlengkapan</label>
                        <input type="text" name="perlengkapan" id="perlengkapan" 
                               value="{{ old('perlengkapan', $lokasi->perlengkapan) }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm">
                        @error('perlengkapan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rute -->
                    <div class="mb-4">
                        <label for="rute" class="block text-gray-700">Rute</label>
                        <input type="text" name="rute" id="rute" 
                               value="{{ old('rute', $lokasi->rute) }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm">
                        @error('rute')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Umpan -->
                    <div class="mb-4">
                        <label for="umpan" class="block text-gray-700">Umpan</label>
                        <input type="text" name="umpan" id="umpan" 
                               value="{{ old('umpan', $lokasi->umpan) }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm">
                        @error('umpan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Ikan -->
                    <div class="mb-4">
                        <label for="jenis_ikan" class="block text-gray-700">Jenis Ikan</label>
                        <input type="text" name="jenis_ikan" id="jenis_ikan" 
                               value="{{ old('jenis_ikan', $lokasi->jenis_ikan) }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm">
                        @error('jenis_ikan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
