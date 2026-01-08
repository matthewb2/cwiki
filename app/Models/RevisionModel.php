<?php

namespace App\Models;

use CodeIgniter\Model;

class RevisionModel extends Model
{
    protected $table = 'revisions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['page_id', 'content', 'comment', 'user_id'];

    // 특정 페이지의 최신 버전을 가져오는 사용자 정의 함수
    public function getLatestRevision($pageId)
    {
        return $this->where('page_id', $pageId)
                    ->orderBy('created_at', 'DESC')
                    ->first();
    }
}