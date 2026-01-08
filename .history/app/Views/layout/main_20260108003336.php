<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'CI4 Wiki' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* 위키 본문 마크다운 스타일링을 위한 팁 */
        .wiki-content h1 { @apply text-3xl font-bold mb-4 pb-2 border-b; }
        .wiki-content p { @apply mb-4 leading-7; }
        .wiki-content ul { @apply list-disc ml-6 mb-4; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 font-sans">

    <nav class="bg-slate-800 text-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="/" class="text-xl font-bold tracking-tight">CI4 <span class="text-blue-400">Wiki</span></a>
            <div class="flex items-center gap-4">
                <form action="/search" method="get" class="relative">
                    <input type="text" name="q" placeholder="문서 검색..." 
                           class="bg-slate-700 text-sm rounded-full py-1.5 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 w-48 md:w-64">
                </form>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <main class="w-full md:w-3/4 bg-white p-8 rounded-lg shadow-sm border border-gray-200">
                <?= $this->renderSection('content') ?>
            </main>

            <aside class="w-full md:w-1/4">
                <div class="sticky top-8">
                    <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                        <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">도구</h3>
                        <nav class="flex flex-col gap-2 text-sm">
                            <a href="/edit/<?= $page['title'] ?? '' ?>" class="text-blue-600 hover:underline">📝 이 문서 편집</a>
                            <a href="/history/<?= $page['title'] ?? '' ?>" class="text-blue-600 hover:underline">📜 변경 이력</a>
                            <hr class="my-2">
                            <a href="/random" class="text-gray-600 hover:underline">🎲 임의 문서로</a>
                        </nav>
                    </div>
                </div>
            </aside>
        </div>
    </div>

</body>
</html>