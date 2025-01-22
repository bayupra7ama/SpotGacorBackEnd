<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Lokasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('lokasi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="nama_tempat" class="block text-gray-700">Nama Tempat</label>
                        <input type="text" name="nama_tempat" id="nama_tempat" 
                               class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="alamat" class="block text-gray-700">Alamat</label>
                        <textarea name="alamat" id="alamat" 
                                  class="w-full border-gray-300 rounded-md shadow-sm" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="lat" class="block text-gray-700">Latitude</label>
                        <input type="text" name="lat" id="lat" 
                               class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="long" class="block text-gray-700">Longitude</label>
                        <input type="text" name="long" id="long" 
                               class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="perlengkapan" class="block text-gray-700">Perlengkapan</label>
                        <input type="text" name="perlengkapan" id="perlengkapan" 
                               class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="rute" class="block text-gray-700">Rute</label>
                        <input type="text" name="rute" id="rute" 
                               class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="umpan" class="block text-gray-700">Umpan</label>
                        <input type="text" name="umpan" id="umpan" 
                               class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="jenis_ikan" class="block text-gray-700">Jenis Ikan</label>
                        <input type="text" name="jenis_ikan" id="jenis_ikan" 
                               class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="mb-4">
                        <label for="images" class="block text-gray-700">Gambar</label>
                        <input type="file" name="images[]" id="images" 
                               class="w-full border-gray-300 rounded-md shadow-sm" multiple required>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if ($errors->any())
    <div class="mb-4 bg-red-500 text-white py-2 px-4 rounded">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="mb-4 bg-red-500 text-white py-2 px-4 rounded">
        {{ session('error') }}
    </div>
@endif

</x-app-layout>
