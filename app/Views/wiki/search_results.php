<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto px-0 py-2">
    <div class="border-b-2 border-gray-100 pb-4 mb-8">
        <h2 class="text-3-xl font-bold text-gray-800">
            <span class="text-blue-600">"<?= esc($keyword) ?>"</span> 
            <span class="text-gray-500 font-medium text-lg ml-2">검색 결과</span>
        </h2>
    </div>

    <?php if (!empty($results)): ?>
        <ul class="space-y-8">
            <?php foreach ($results as $page): ?>
                <li class="group">
                    <a href="<?= base_url('view/' . $page['title']) ?>" 
                       class="text-xl font-semibold text-blue-700 group-hover:text-blue-900 group-hover:underline transition-colors duration-200 block mb-2">
                        <?= esc($page['title']) ?>
                    </a>
                    
                    <div class="bg-gray-50 border-l-4 border-gray-300 p-4 rounded-r-lg shadow-sm">
                        <p class="text-gray-600 leading-relaxed text-sm md:text-base">
                            <?php 
                                $plainText = strip_tags($page['content']);
                                $displayContent = mb_strimwidth($plainText, 0, 200, "...");
                                
                                if ($keyword) {
                                    // 검색어 하이라이트 처리를 위한 Tailwind 클래스 적용
                                    echo str_ireplace(
                                        esc($keyword), 
                                        '<span class="bg-yellow-200 font-bold px-1 rounded">' . esc($keyword) . '</span>', 
                                        $displayContent
                                    );
                                } else {
                                    echo $displayContent;
                                }
                            ?>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div class="text-center py-20 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <p class="text-xl font-medium text-gray-600">검색 결과가 없습니다.</p>
            <p class="text-gray-400 mt-2">다른 검색어를 입력해 보세요.</p>
        </div>
    <?php endif; ?>

    <div class="mt-12 pt-6 border-t border-gray-100">
        <a href="<?= base_url('/') ?>" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            홈으로 돌아가기
        </a>
    </div>
</div>
<?= $this->endSection() ?>