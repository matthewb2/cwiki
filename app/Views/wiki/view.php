<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <article class="prose prose-slate max-w-none">
        <?= $content ?>
    </article>
    
    <div class="mt-8 pt-4 border-t border-gray-100 text-xs text-gray-400">
        최근 수정: 2026-01-08
    </div>
<?= $this->endSection() ?>