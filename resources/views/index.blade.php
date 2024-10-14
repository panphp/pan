<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>panphp - pan</title>
</head>
<body>
<div class="max-w-4xl p-2 mx-auto mt-20 rounded-md sm:p-4 text-gray-800">
    <h2 class="mb-3 text-2xl font-semibold leading-tight">PAN - Analytics</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full text-xs">
            <thead class="rounded-t-lg bg-gray-300">
            <tr class="text-right">
                <th title="#" class="p-3 text-left">#</th>
                <th title="Name" class="p-3 text-left">Name</th>
                <th title="Impressions" class="p-3">Impressions</th>
                <th title="Hovers" class="p-3">Hovers</th>
                <th title="Clicks" class="p-3">Clicks</th>
            </tr>
            </thead>
            <tbody>
            @foreach($analytics as $key => $analytic)
                <tr class="text-right border-b border-opacity-20 border-gray-300 bg-gray-100">
                    <td class="px-3 py-2 text-left">
                        {!! $analytic[0] !!}
                    </td>
                    <td class="px-3 py-2 text-left">
                        {!! $analytic[1] !!}
                    </td>
                    <td class="px-3 py-2">
                        {!! $analytic[2] !!}
                    </td>
                    <td class="px-3 py-2">
                        {!! $analytic[3] !!}
                    </td>
                    <td class="px-3 py-2">
                        {!! $analytic[4] !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
