<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <article class="prose prose-slate max-w-none">
        <?= $content ?>
    </article>
</article>

<div class="mt-12 pt-4 border-t border-gray-200 text-sm text-gray-500 flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <span>        
        최근 수정: 
    <time datetime="<?= $updated_at ?>">
        <?php 
        if (!empty($updated_at)): 
            // 1. UTC 기준의 DateTime 객체 생성
            $date = new \DateTime($updated_at, new \DateTimeZone('UTC'));
            
            // 2. 타임존을 Seoul(UTC+9)로 변경
            $date->setTimezone(new \DateTimeZone('Asia/Seoul'));
            
            // 3. 포맷에 맞춰 출력
            echo $date->format('Y년 m월 d일 H:i');
        else: 
            echo '기록 없음';
        endif; 
        ?>
    </time>
    </span>
</div>
<?= $this->endSection() ?>