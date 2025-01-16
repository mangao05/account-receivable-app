<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel Import Result</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <h1 class="text-2xl font-bold mb-6">Imported Excel Data</h1>
    @foreach($data as $fileData)
        <div class="mb-8 p-6 bg-white shadow rounded-lg">
            <h2 class="text-xl font-semibold mb-4">{{ $fileData['filename'] }}</h2>
            <table class="min-w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        @foreach($fileData['rows'][0] ?? [] as $header)
                            <th class="border border-gray-300 px-4 py-2">{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach(array_slice($fileData['rows'], 1) as $row)
                        <tr class="hover:bg-gray-50">
                            @foreach($row as $cell)
                                <td class="border border-gray-300 px-4 py-2">{{ $cell }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</body>
</html>
