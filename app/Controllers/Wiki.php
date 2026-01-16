<?php

namespace App\Controllers;

// 아래 두 줄이 반드시 있어야 합니다!
use App\Models\PageModel;
use App\Models\RevisionModel;
use Parsedown; // 이 줄을 추가하세요!

class Wiki extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // 헬퍼 로드 (time_helper.php를 만들었다면 'time' 입력)
        helper(['url', 'form', 'time']);
    }

    public function index()
    {
        /*
        dd([
    'CI_Charset' => config('App')->charset,
    'MB_Enabled' => extension_loaded('mbstring'),
    'MB_Internal' => mb_internal_encoding(),
]);
*/
        $pageModel = new \App\Models\PageModel();
        $revisionModel = new \App\Models\RevisionModel();

        // 메인 페이지의 ID가 1이라고 가정합니다.
        $pageId = 1;
        $page = $pageModel->find($pageId);

        if (!$page) {
            // 페이지가 없을 경우의 처리
            return redirect()->to('/wiki/create');
        }

        // --- 이 부분이 추가되어야 합니다 ---
        $latestRevision = $revisionModel->where('page_id', $page['id'])
            ->orderBy('created_at', 'DESC')
            ->first();
        // -------------------------------

        $data = [
            'title'      => $page['title'],
            'content'    => $latestRevision ? $latestRevision['content'] : '내용이 없습니다.',
            'updated_at' => $page['updated_at'],
        ];
        return $this->view('Home', $data);
    }

    /**
     * 특정 과거 리비전을 보여주는 메서드
     */
    public function viewRevision($revisionId)
    {
        $pageModel = new PageModel();
        $revisionModel = new RevisionModel();

        // 1. 해당 리비전 데이터 가져오기
        $revision = $revisionModel->find($revisionId);
        if (!$revision) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("해당 리비전을 찾을 수 없습니다.");
        }

        // 2. 연결된 페이지 정보 가져오기 (제목 표시용)
        $page = $pageModel->find($revision['page_id']);

        // 3. 내용 렌더링 (공통 렌더링 메서드 호출)
        $finalContent = $this->renderWiki($revision['content']);

        $data = [
            'title'      => $page['title'],
            'page'       => $page,
            'content'    => $finalContent,
            'revision'   => $revision,
            'is_old_rev' => true // 뷰에서 노란색 알림창을 띄우기 위한 플래그
        ];

        return view('wiki/view', $data);
    }

    public function search()
    {
        $pageModel = new \App\Models\PageModel();
        $keyword = $this->request->getGet('q');

        if (!empty(trim($keyword))) {
            // pages와 revisions 테이블을 조인하여 검색
            $results = $pageModel->select('pages.*, revisions.content')
                ->join('revisions', 'revisions.page_id = pages.id')
                ->groupStart() // 괄호 시작: (title LIKE OR content LIKE)
                ->like('pages.title', $keyword)
                ->orLike('revisions.content', $keyword)
                ->groupEnd()
                // 가장 최신 리비전만 가져오기 위해 정렬 및 그룹화 (데이터 구조에 따라 조정 필요)
                ->orderBy('revisions.id', 'DESC')
                ->groupBy('pages.id')
                ->findAll();
        } else {
            $results = [];
        }

        $data = [
            'results' => $results,
            'keyword' => $keyword,
            'title'   => $keyword ? "'{$keyword}' 검색 결과" : "검색어를 입력하세요"
        ];
        // CodeIgniter 4의 내장 view(파일경로, 데이터배열) 함수
        return view('wiki/search_results', $data);
    }
    /**
     * 마크다운 변환 및 [[링크]] 치환 공통 로직
     */
    private function renderWiki($rawContent)
    {
        $pageModel = new PageModel();
        $parser = new Parsedown();
        $parser->setSafeMode(true);

        // 1. 마크다운 -> HTML
        $htmlContent = $parser->text($rawContent);

        // 2. [[문서이름]] -> <a> 태그
        return preg_replace_callback('/\[\[(.*?)\]\]/', function ($matches) use ($pageModel) {
            $linkTitle = trim($matches[1]);
            $exists = $pageModel->where('title', $linkTitle)->first();

            if ($exists) {
                $url = site_url('view/' . urlencode($linkTitle));
                return '<a href="' . $url . '" class="text-blue-600 hover:underline font-medium">' . esc($linkTitle) . '</a>';
            } else {
                $url = site_url('edit/' . urlencode($linkTitle));
                return '<a href="' . $url . '" class="text-red-500 hover:underline font-medium" title="새 문서 만들기">' . esc($linkTitle) . '</a>';
            }
        }, $htmlContent);
    }


    public function view($title = 'Home')
    {
        $pageModel = new PageModel();
        $revisionModel = new RevisionModel();
        $parser = new Parsedown();

        // 보안을 위해 HTML 태그 이스케이프 설정을 켤 수 있습니다 (선택사항)
        $parser->setSafeMode(true);

        // 제목으로 페이지 찾기 (URL 디코딩 포함)
        $title = urldecode($title);
        $slug = str_replace(' ', '_', trim($title));
        $page = $pageModel->where('slug', $slug)->first();

        if (!$page) {
            $data = [
                'title'   => $title,
                'slug'       => $slug,
                'content' => "<div class='text-center py-10'>
                            <p class='text-gray-500 mb-4'>'{$title}' 문서가 아직 존재하지 않습니다.</p>
                            <a href='" . site_url('edit/' . urlencode($title)) . "' class='bg-blue-500 text-white px-4 py-2 rounded'>첫 내용 작성하기</a>
                          </div>",
                'updated_at' => null,
            ];
            return view('wiki/view', $data);
        }

        /** CodeIgniter 4의 내장 view(파일경로, 데이터배열) 함수
         *  첫 번째 인자 ('wiki/view'): 어떤 파일을 보여줄지 정합니다. app/Views/wiki/view.php 파일을 찾으라는 뜻입니다.
         *  두 번째 인자 ($data): 컨트롤러에서 만든 변수들을 뷰 파일로 전달합니다.
         */
        // 3. 문서를 찾았을 때 리비전 가져오기
        $latestRevision = $revisionModel->where('page_id', $page['id'])
            ->orderBy('created_at', 'DESC')
            ->first();

        $data = [
            'title'      => $page['title'],
            'page'       => $page,
            'content'    => $this->renderWiki($latestRevision ? $latestRevision['content'] : '내용이 없습니다.'),
            // 저장된 표준시를 한국시간으로 바꾸는 로직은 이미 view.php나 헬퍼에 있다고 가정합니다.
            'updated_at' => $latestRevision ? $latestRevision['created_at'] : $page['created_at'],
        ];

        return view('wiki/view', $data);
    }

    public function edit($title = 'Home')
    {
        $pageModel = new PageModel();
        $revisionModel = new RevisionModel();

        $title = urldecode($title);
        $page = $pageModel->where('title', $title)->first();

        // 페이지가 없으면 $page는 null, $latestRevision도 null이 되어 빈 에디터가 뜹니다.
        $latestRevision = $page ? $revisionModel->getLatestRevision($page['id']) : null;

        $data = [
            'title' => $title,
            'page'  => $page,
            'latest_revision' => $latestRevision // 이 데이터가 뷰의 textarea로 전달됩니다.
        ];

        return view('wiki/edit', $data);
    }

    public function history($title)
    {
        $pageModel = new \App\Models\PageModel();
        $revisionModel = new \App\Models\RevisionModel();

        $title = urldecode($title);
        $page = $pageModel->where('title', $title)->first();

        if (!$page) {
            return redirect()->to(site_url('view/' . urlencode($title)))
                ->with('error', '존재하지 않는 문서의 이력은 볼 수 없습니다.');
        }

        $revisions = $revisionModel->getHistory($page['id']);

        $data = [
            'title'     => $title,
            'revisions' => $revisions
        ];

        return view('wiki/history', $data);
    }
    public function save()
    {
        $pageModel = new \App\Models\PageModel();
        $revisionModel = new \App\Models\RevisionModel();

        // 1. 폼 데이터 수신
        $id = $this->request->getPost('id');
        $title = $this->request->getPost('title');
        $content = $this->request->getPost('content');
        //$comment = $this->request->getPost('comment') ?: '수정 요약 없음';

        // 데이터 검증 (기본)
        if (empty($title) || empty($content)) {
            return redirect()->back()->with('error', '제목과 내용은 필수입니다.');
        }

        // 한글 위키의 경우, 제목 자체가 URL이 되는 경우가 많습니다.
        // url_title()을 쓰면 한글이 사라질 수 있으므로, 공백만 언더바(_)로 바꾸는 방식을 추천합니다.
        $slug = str_replace(' ', '_', trim($title));

        // 2. 페이지 존재 확인 및 생성
        // 제목으로 검색하여 기존 페이지가 있는지 확인합니다.
        $page = $pageModel->where('title', $title)->first();

        $current_time = date('Y-m-d H:i:s'); // PHP 설정이 UTC이므로 표준시로 저장됨
        $data = [
            'title'      => $title,
            'slug'       => $slug,
            'content'    => $content,
            'updated_at' => $current_time
        ];

        if ($id) {
            // 기존 문서 수정
            $pageModel->update($id, $data);
            $pageId = $id;
        } else {
            // 새 문서 생성
            $pageId = $pageModel->insert($data);
        }

        // 변경 이력(Revision) 남기기
        $revisionModel->insert([
            'page_id'    => $pageId,
            'content'    => $content,
            'created_at' => $current_time // 수정 시점의 시간을 생성 시간으로 기록
        ]);

        return redirect()->to(base_url('view/' . $slug));
    }

    public function random()
    {
        $pageModel = new \App\Models\PageModel();

        // 데이터베이스에서 무작위로 1개의 행을 가져옵니다.
        $randomPage = $pageModel->orderBy('id', 'RANDOM')->first();

        if ($randomPage) {
            // 해당 문서의 슬러그를 이용하여 상세 페이지로 이동합니다.
            return redirect()->to(base_url('view/' . urlencode($randomPage['slug'])));
        }

        // 문서가 하나도 없을 경우 홈으로 보냅니다.
        return redirect()->to(base_url());
    }

    public function recent()
    {
        $pageModel = new \App\Models\PageModel();

        // 1. updated_at(수정 시각)을 기준으로 내림차순(DESC) 정렬하여 모든 문서를 가져옵니다.
        // 넉넉하게 최근 50개 정도만 보여주도록 설정할 수 있습니다.
        $recentPages = $pageModel->orderBy('updated_at', 'DESC')
            ->limit(50)
            ->findAll();

        $data = [
            'title'       => '최근 변경 내역',
            'recentPages' => $recentPages
        ];

        // 2. 결과를 보여줄 뷰 파일을 호출합니다.
        return view('wiki/recent', $data);
    }
}
