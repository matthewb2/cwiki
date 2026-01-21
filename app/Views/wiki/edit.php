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
    <input type="hidden" name="title" value="<?= esc($title) ?>">

    <div>
        <div class="flex items-center flex-wrap gap-0.5 border border-gray-300 bg-gray-100 p-1 rounded-t-md shadow-sm">
            <button type="button" onclick="insertWikiTag('**', '**')" class="w-8 h-8 flex items-center justify-center hover:bg-gray-200 border border-transparent hover:border-gray-400 rounded text-gray-700 font-bold" title="굵게">B</button>
            <button type="button" onclick="insertWikiTag('*', '*')" class="w-8 h-8 flex items-center justify-center hover:bg-gray-200 border border-transparent hover:border-gray-400 rounded text-gray-700 italic" title="기울임">I</button>
            <button type="button" onclick="insertWikiTag('<u>', '</u>')" class="w-8 h-8 flex items-center justify-center hover:bg-gray-200 border border-transparent hover:border-gray-400 rounded text-gray-700 underline" title="밑줄">U</button>
            <button type="button" onclick="insertWikiTag('<code>', '</code>')" class="w-8 h-8 flex items-center justify-center hover:bg-gray-200 border border-transparent hover:border-gray-400 rounded text-gray-700 font-mono" title="타자기체">TT</button>
            <button type="button" onclick="insertWikiTag('~~', '~~')" class="w-8 h-8 flex items-center justify-center hover:bg-gray-200 border border-transparent hover:border-gray-400 rounded text-gray-700 line-through" title="취소선">S</button>

            <div class="h-6 border-l border-gray-300 mx-1"></div>

            <button type="button" onclick="insertWikiTag('[[', ']]')" class="w-8 h-8 flex items-center justify-center hover:bg-gray-200 border border-transparent hover:border-gray-400 rounded text-blue-600 font-bold" title="내부 링크">[[ ]]</button>
            <button type="button" onclick="insertWikiTag('[', ']')" class="w-8 h-8 flex items-center justify-center hover:bg-gray-200 border border-transparent hover:border-gray-400 rounded text-gray-700" title="외부 링크">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.172 13.828a4 4 0 005.656 0l4-4a4 4 0 10-5.656-5.656l-1.102 1.101" />
                </svg>
            </button>

            <div class="h-6 border-l border-gray-300 mx-1"></div>

            <button type="button" onclick="insertWikiTag('\n```\n', '\n```\n')" class="w-8 h-8 flex items-center justify-center hover:bg-gray-200 border border-transparent hover:border-gray-400 rounded text-gray-700" title="코드 블록">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
            </button>
            <button type="button" onclick="insertWikiTag('-- ~~~~', '')" class="w-8 h-8 flex items-center justify-center hover:bg-gray-200 border border-transparent hover:border-gray-400 rounded text-gray-700" title="서명">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </button>
            <button type="button" onclick="document.getElementById('file-upload-input').click()" class="w-8 h-8 flex items-center justify-center hover:bg-gray-200 border border-transparent hover:border-gray-400 rounded text-gray-700" title="파일 업로드">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
            </button>

            <input type="file" id="file-upload-input" class="hidden" onchange="uploadFile(this)">
        </div>
        <textarea id="wiki-editor" name="content"
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
        <div class="flex items-center gap-2">
            <button type="button" onclick="togglePreview()"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md font-bold transition-colors flex items-center gap-1">

                미리보기
            </button>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-bold transition-colors">
                저장하기
            </button>
        </div>
    </div>
    <div id="preview-container" class="hidden mt-6">
        <h3 class="text-lg font-bold mb-2 text-gray-700 flex items-center gap-2">
            <span class="w-1 h-5 bg-blue-500 rounded-full"></span>
            미리보기 화면
        </h3>
        <div id="preview-area" class="w-full min-h-[100px] p-6 border border-gray-200 rounded-lg bg-white prose max-w-none shadow-inner">
        </div>
    </div>

</form>

<script>
    function togglePreview() {
        const editor = document.getElementById('wiki-editor');
        const container = document.getElementById('preview-container');
        const area = document.getElementById('preview-area');

        if (container.classList.contains('hidden')) {
            // 1. 에디터 내용 가져오기
            let content = editor.value;

            // 2. marked 라이브러리를 사용하여 마크다운 변환
            // [[]] 같은 위키 링크는 marked가 지원하지 않으므로 별도 처리 필요
            let html = marked.parse(content);
            //console.log(html);

            // 3. 위키 문법 전용 처리 (선택 사항)
            html = html.replace(/\[\[([^\]|]+)(?:\|([^\]]+))?\]\]/g, function(match, page, text) {
                const linkText = text || page;
                return `<a href="#" class="text-blue-600 underline">${linkText}</a>`;
            });

            // 4. 결과 출력 및 화면 표시
            area.innerHTML = html || '<span class="text-gray-400">내용이 없습니다.</span>';
            container.classList.remove('hidden');

            container.scrollIntoView({
                behavior: 'smooth'
            });
        } else {
            container.classList.add('hidden');
        }
    }

    function insertWikiTag(startTag, endTag) {
        const textarea = document.getElementById('wiki-editor');
        const start = textarea.selectionStart; // 선택 시작 지점
        const end = textarea.selectionEnd; // 선택 종료 지점
        const text = textarea.value; // 전체 텍스트

        // 선택된 텍스트가 있다면 가져옴
        const selectedText = text.substring(start, end);

        // 새로운 텍스트 구성 (앞부분 + 시작태그 + 선택내용 + 끝태그 + 뒷부분)
        const newText = text.substring(0, start) + startTag + selectedText + endTag + text.substring(end);

        textarea.value = newText;

        // 포커스를 다시 에디터로 돌림
        textarea.focus();

        // 커서 위치 조정 (삽입된 태그 이후로 이동)
        const newCursorPos = start + startTag.length + selectedText.length + endTag.length;
        textarea.setSelectionRange(newCursorPos, newCursorPos);
    }
</script>

<script>
    async function uploadFile(input) {
        if (!input.files || input.files[0] == null) return;

        const file = input.files[0];
        const formData = new FormData();
        formData.append('file', file);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>'); // CSRF 보호

        // 버튼 상태 변경 (업로드 중 표시)
        const btn = input.previousElementSibling;
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<span class="animate-spin text-xs">...</span>';
        btn.disabled = true;

        try {
            const uploadUrl = '<?= site_url('wiki/upload') ?>';
            const response = await fetch(uploadUrl, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                // 이미지인지 확인하여 태그 결정
                const isImage = file.type.startsWith('image/');
                const tag = isImage ? `![${file.name}](${result.url})` : `[${file.name}](${result.url})`;

                // 에디터에 삽입
                insertWikiTag(tag, '');
            } else {
                alert('업로드 실패: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('서버와 통신 중 오류가 발생했습니다.');
        } finally {
            btn.innerHTML = originalContent;
            btn.disabled = false;
            input.value = ''; // 선택 초기화
        }
    }
</script>
<?= $this->endSection() ?>