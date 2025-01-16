<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Excel Files</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-100 via-blue-300 to-blue-500 min-h-screen flex items-center justify-center">
    <div class="max-w-lg w-full bg-white p-10 rounded-xl shadow-xl">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Import Excel Files</h1>
        <form action="{{ route('excel.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label for="files" class="block text-lg font-medium text-gray-700 mb-2">Select Excel Files</label>
                <input type="file" name="files" id="files" class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg p-3 bg-gray-50 shadow-sm focus:ring-blue-500 focus:border-blue-500" multiple accept=".xlsx, .xls, .csv">
                @error('files')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-5 py-3 bg-gradient-to-r from-green-400 to-blue-500 hover:from-green-500 hover:to-blue-600 text-white font-semibold rounded-lg shadow-md transform hover:scale-105 transition-transform">Import Files</button>
            </div>
        </form>
    </div>
</body>
</html>