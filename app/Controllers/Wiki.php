<?php

namespace App\Controllers;

// 아래 두 줄이 반드시 있어야 합니다!
use App\Models\PageModel;
use App\Models\RevisionModel;
use Parsedown; // 이 줄을 추가하세요!

class Wiki extends BaseController
{
    public function index()
    {
        // 메인 페이지 접속 시 'Home' 문서로 리다이렉트하거나 목록 표시
        return $this->view('Home');
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
        $page = $pageModel->where('title', $title)->first();

        if (!$page) {
            // 페이지가 없을 경우 안내 문구와 함께 데이터를 넘김
            $data = [
                'title'   => $title,
                'page'    => null,
                'content' => "<p class='text-red-500'>존재하지 않는 문서입니다. <a href='/edit/" . urlencode($title) . "' class='text-blue-600 underline'>새로 작성</a>하시겠습니까?</p>"
            ];
        } else {
            $latest = $revisionModel->getLatestRevision($page['id']);
            $rawContent = $latest ? $latest['content'] : '내용이 없습니다.';
            
            // 마크다운을 HTML로 변환
            $data = [
                'title'   => $page['title'],
                'page'    => $page,
                'content' => $parser->text($rawContent) 
            ];
        }

        return view('wiki/view', $data);
    }

    public function edit($title = 'Home')
    {
        $data = [
            'title' => urldecode($title),
            'page'  => ['id' => 1, 'title' => urldecode($title)],
            'latest_revision' => ['content' => "# " . urldecode($title) . "\n내용을 입력하세요."]
        ];

        return view('wiki/edit', $data);
    }
}