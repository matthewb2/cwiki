<?php

namespace App\Controllers;

class Wiki extends BaseController
{
    public function index()
    {
        // 메인 페이지 접속 시 'Home' 문서로 리다이렉트하거나 목록 표시
        return $this->view('Home');
    }

    public function view($title = 'Home')
    {
        // 테스트용 가짜 데이터
        data = [
            'title' => urldecode($title),
            'page'  => ['id' => 1, 'title' => urldecode($title)],
            'content' => "<h1>안녕하세요!</h1><p>이것은 <strong>Tailwind</strong>로 만든 위키 테스트 페이지입니다.</p>"
        ];

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