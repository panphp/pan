<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap"
          rel="stylesheet">

    <title>panphp - pan</title>
    <style>
        .primary-color {
            color: #D943CB;
        }

        body {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>
</head>
<body class="bg-black">
<div class="max-w-4xl p-2 mx-auto mt-20 rounded-md sm:p-4">
    <h2 class="mb-3 text-3xl font-semibold leading-tight primary-color">PAN - Analytics</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full text-xs">
            <thead class="rounded-t-lg bg-black text-lg">
            <tr class="text-right primary-color">
                @foreach($columns as $column)
                    <th class="p-3 text-left">{{ $column }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($analytics as $key => $analytic)
                <tr class="text-right bg-black text-white text-lg">
                    @for($i = 0; $i < count($analytic); $i++)
                        <td class="px-3 py-2 text-left">
                            {!! $analytic[$i] !!}
                        </td>
                    @endfor
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
