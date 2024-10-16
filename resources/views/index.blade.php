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

        .primary-color-border {
            border-color: #D943CB;
        }
    </style>
</head>
<body class="">
<div class="container mx-auto mt-10 rounded-md sm:p-4">
    <h2 class="mb-3 text-3xl font-semibold leading-tight primary-color">PAN - Analytics</h2>
    <fieldset class="w-full space-y-1 text-gray-800 mb-3">
        <form>
            <label for="Search" class="hidden">Search</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                    <button type="button" title="search" class="p-1 focus:outline-none focus:ring">
                        <svg fill="currentColor" viewBox="0 0 512 512" class="w-4 h-4 text-gray-800">
                            <path
                                d="M479.6,399.716l-81.084-81.084-62.368-25.767A175.014,175.014,0,0,0,368,192c0-97.047-78.953-176-176-176S16,94.953,16,192,94.953,368,192,368a175.034,175.034,0,0,0,101.619-32.377l25.7,62.2L400.4,478.911a56,56,0,1,0,79.2-79.195ZM48,192c0-79.4,64.6-144,144-144s144,64.6,144,144S271.4,336,192,336,48,271.4,48,192ZM456.971,456.284a24.028,24.028,0,0,1-33.942,0l-76.572-76.572-23.894-57.835L380.4,345.771l76.573,76.572A24.028,24.028,0,0,1,456.971,456.284Z"></path>
                        </svg>
                    </button>
                </span>
                <input type="search" name="q" placeholder="Search..."
                       value="{{ request()->get('q') }}"
                       class="w-2/3 py-2 pl-10 text-sm rounded-md focus:outline-none bg-gray-100 text-gray-800 focus:bg-gray-50 focus:border-sky-600">
            </div>
        </form>
    </fieldset>
    <div class="flex flex-row justify-start space-x-8">
        @foreach($analytics as $key => $analytic)
            <div class="w-3/12 p-3 rounded-lg border-2 primary-color-border">
                <div class="flex justify-between space-x-8">
                    <div class="flex flex-col items-center">
                        <h1 class="text-xl font-semibold primary-color">{!!  $analytic[1] !!}</h1>
                    </div>
                </div>
                <div class="flex justify-between mt-2 space-x-4 text-gray-600">
                    <div class="flex flex-col items-center space-y-1">
                        <span class="uppercase">Id</span>
                        <span>{!! $analytic[0] !!}</span>
                    </div>
                    <div class="flex flex-col items-center space-y-1">
                        <span class="uppercase">Impressions</span>
                        <span>{!! $analytic[2] !!}</span>
                    </div>
                    <div class="flex flex-col items-center space-y-1">
                        <span class="uppercase">Hovers</span>
                        <span>{!! $analytic[3] !!}</span>
                    </div>
                    <div class="flex flex-col items-center space-y-1">
                        <span class="uppercase">Clicks</span>
                        <span>{!! $analytic[4] !!}</span>
                    </div>
                </div>
            </div>
        @endforeach

        @if(empty($analytics))
            No analytics have been recorded yet. Get started collecting analytics by adding the [data-pan="my-button"]
            attribute to your HTML elements.
        @endif

    </div>
</div>
</body>
</html>
