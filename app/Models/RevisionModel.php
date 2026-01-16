<?php

namespace App\Models;

use CodeIgniter\Model;

class RevisionModel extends Model
{
    protected $table = 'revisions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['page_id', 'content', 'comment', 'user_id'];

    // 자동으로 시간 기록 활성화
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // 업데이트 필드는 사용하지 않음

    // 특정 페이지의 최신 버전을 가져오는 함수
    public function getLatestRevision($pageId)
    {
        return $this->where('page_id', $pageId)
                    ->orderBy('created_at', 'DESC')
                    ->first();
    }
    
    // 특정 페이지의 모든 이력을 가져오는 함수
    public function getHistory($pageId)
    {
        return $this->where('page_id', $pageId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
    public function getLatestContent(int $pageId)
    {
        $revision = $this->where('page_id', $pageId)
                         ->orderBy('created_at', 'DESC')
                         ->first();

        return $revision ? $revision['content'] : '';
    }
}