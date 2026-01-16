<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800">
        <span class="text-blue-600"><?= esc($title) ?></span>의 변경 이력
    </h2>
    <p class="text-gray-500 mt-2">이 문서의 모든 수정 기록입니다.</p>
</div>

<div class="bg-white shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">날짜</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">수정 요약</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">작업</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($revisions as $rev): ?>
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <?= $rev['created_at'] ?>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                    <?= esc($rev['comment'] ?: '수정 요약 없음') ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="<?= site_url('revision/' . urlencode($rev['id'])) ?>" class="text-blue-600 hover:text-blue-900">보기</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="mt-6">
    <a href="<?= site_url('view/' . urlencode($title)) ?>" class="text-gray-600 hover:underline">← 문서로 돌아가기</a>
</div>
<?= $this->endSection() ?>