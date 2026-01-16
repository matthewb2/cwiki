<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'CI4 Wiki' ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* 위키 본문 마크다운 스타일링을 위한 팁 */
        .wiki-content h1 {
            @apply text-3xl font-bold mb-4 pb-2 border-b;
        }

        .wiki-content p {
            @apply mb-4 leading-7;
        }

        .wiki-content ul {
            @apply list-disc ml-6 mb-4;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 font-sans">
    <nav class="bg-slate-800 text-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="<?= site_url() ?>" class="text-xl font-bold tracking-tight">C<span class="text-blue-400">Wiki</span></a>

            <div class="flex flex-col items-end gap-1">
                <form action="<?= site_url('search/') ?>" method="get" class="relative" id="search-form">
                    <button type="submit" class="absolute inset-y-0 left-0 pl-3 flex items-center hover:text-blue-400 transition-colors">
                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>

                    <input type="text" name="q" placeholder="문서 검색..."
                        class="bg-slate-700 text-sm rounded-full py-1.5 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-blue-500 w-48 md:w-64">
                </form>

                <div class="flex gap-3 text-xs text-slate-400 px-2">
                    <a href="<?= site_url('recent') ?>" class="hover:text-blue-400 transition-colors">최근 바뀜</a>
                    <a href="<?= site_url('random') ?>" class="hover:text-blue-400 transition-colors">임의 문서</a>
                    <a href="#" class="hover:text-blue-400 transition-colors">미디어 관리자</a>
                    <a href="#" class="hover:text-blue-400 transition-colors">사이트맵</a>
                </div>
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
                            <a href="<?= site_url('edit/' . urlencode($page['title'] ?? 'Home')) ?>" class="text-blue-600 hover:underline">📝 이 문서 편집</a>
                            <a href="<?= isset($title) ? site_url('view/' . urlencode($title)) : '#' ?>" class="text-blue-600 hover:underline">📄 문서 보기</a>

                        </nav>
                    </div>
                </div>
            </aside>
        </div>
    </div>

</body>

</html>