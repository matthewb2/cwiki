<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'CWiki' ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

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
            <a href="<?= site_url() ?>" class="text-4xl font-bold tracking-tight">C<span class="text-blue-400">Wiki</span></a>

            <div class="flex flex-col items-end gap-1">
                <div class="text-white py-1 text-xs flex items-center gap-4">
                    <?php if (session()->get('isLoggedIn')): ?>
                        <span class="text-slate-200 font-bold flex items-center gap-1">
                            로그인한 사용자:
                            <?= session()->get('username') ?>님
                        </span>
                        <span class="text-slate-200 font-bold flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <a href="<?= site_url('auth/profile') ?>" class="flex items-center gap-1 text-slate-400 hover:text-white transition-colors">프로필 업데이트</a>
                        </span>
                        <a href="<?= site_url('auth/logout') ?>" class="flex items-center gap-1 text-slate-400 hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m-3-3l-3-3m0 0l-3 3m3-3v12.75" />
                            </svg>
                            로그아웃
                        </a>
                    <?php else: ?>
                        <a href="<?= site_url('register') ?>" class="flex items-center gap-1 text-slate-400 hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                            </svg>
                            등록
                        </a>

                        <a href="<?= site_url('login') ?>" class="flex items-center gap-1 text-slate-400 hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                            </svg>
                            로그인
                        </a>
                    <?php endif; ?>
                </div>

                <form action="<?= site_url('search/') ?>" method="get" class="relative" id="search-form">
                    <button type="submit" class="absolute inset-y-0 left-0 pl-3 flex items-center hover:text-white-400 transition-colors">
                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>

                    <input type="text" name="q" placeholder="문서 검색..."
                        class="bg-slate-700 text-sm rounded-full py-1.5 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-blue-500 w-48 md:w-64">
                </form>

                <div class="flex gap-3 text-xs text-slate-400 px-2 py-1.5">
                    <a href="<?= site_url('recent') ?>" class="hover:text-white-400 transition-colors">최근 바뀜</a>
                    <a href="<?= site_url('random') ?>" class="hover:text-white-400 transition-colors">임의 문서</a>
                    <a href="#" class="hover:text-white-400 transition-colors">미디어 관리자</a>
                    <a href="#" class="hover:text-white-400 transition-colors">사이트맵</a>
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