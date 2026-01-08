<?php

namespace App\Controllers;

use App\Models\PageModel;
use App\Models\RevisionModel;

class Wiki extends BaseController
{
    public function view($title = 'Home')
    {
        $pageModel = new PageModel();
        $revisionModel = new RevisionModel();

        // 1. 제목으로 페이지 찾기
        $page = $pageModel->where('title', urldecode($title))->first();

        if (!$page) {
            // 페이지가 없으면 생성 안내 또는 404 처리 (여기서는 편집 페이지로 유도 가능)
            return "페이지가 존재하지 않습니다. <a href='/edit/".esc($title)."'>새로 만들기</a>";
        }

        // 2. 해당 페이지의 최신 리비전 가져오기
        $latest = $revisionModel->getLatestRevision($page['id']);

        $data = [
            'title'   => $page['title'],
            'page'    => $page,
            'content' => $latest ? $latest['content'] : '내용이 없습니다.'
        ];

        return view('wiki/view', $data);
    }
}