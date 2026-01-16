<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">편집 중: <span class="text-blue-600"><?= esc($title) ?></span>
        </h2>
        <p class="text-sm text-gray-500 mt-1">마크다운 형식을 사용하여 문서를 작성하세요.</p>
    </div>

    <form action="<?= site_url('save') ?>" method="post" class="space-y-6">
    <?php if (isset($page['id'])): ?>
        <input type="hidden" name="id" value="<?= $page['id'] ?>">
    <?php endif; ?>
        <?= csrf_field() ?>
        <input type="hidden" name="title" value="<?=esc($title)?>">
        
        <div>
            <textarea name="content" 
                      class="w-full h-[500px] p-4 font-mono text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none shadow-inner"
                      placeholder="내용을 입력하세요..."><?= esc($latest_revision['content'] ?? '') ?></textarea>
        </div>
        
        <div class="bg-blue-50 p-4 rounded-md">
            <label class="block text-sm font-medium text-blue-800 mb-1">수정 요약 (Revision Comment)</label>
            <input type="text" name="comment" 
                   class="w-full px-3 py-2 border border-blue-200 rounded focus:outline-none focus:border-blue-500"
                   placeholder="무엇이 바뀌었나요?">
        </div>
        
        <div class="flex justify-between items-center pt-4 border-t">
            <a href="<?= site_url('view/' . urlencode($title)) ?>" class="text-gray-600 hover:text-gray-900 font-medium">취소</a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-bold transition-colors">
                저장하기
            </button>
        </div>
    </form>
<?= $this->endSection() ?>