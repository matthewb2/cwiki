<?= $this->extend('layout/main') ?> <?= $this->section('content') ?>
<h1 class="text-2xl font-bold mb-6"><?= esc($title) ?></h1>

<div class="bg-white shadow overflow-hidden sm:rounded-md">
    <ul class="divide-y divide-gray-200">
        <?php if (!empty($recentPages) && is_array($recentPages)): ?>
            <?php foreach ($recentPages as $page): ?>
                <li class="p-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <a href="<?= base_url('view/' . urlencode($page['slug'])) ?>" class="text-blue-600 font-medium hover:underline">
                            <?= esc($page['title']) ?>
                        </a>

                        <span class="text-sm text-gray-500">
                            <?= isset($page['updated_at']) ? display_kst($page['updated_at']) : '기록 없음' ?>
                        </span>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li class="p-4 text-center text-gray-500">최근 변경된 문서가 없습니다.</li>
        <?php endif; ?>
    </ul>
</div>

<div class="mt-6">
    <a href="<?= base_url() ?>" class="text-sm text-gray-600 hover:text-blue-500">← 홈으로 돌아가기</a>
</div>
<?= $this->endSection() ?>