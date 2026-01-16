<?php

if (! function_exists('display_kst')) {
    /**
     * UTC 시간을 한국 시간(UTC+9)으로 변환하여 표시
     * * @param string|null $utc_time DB에서 가져온 UTC 시간 문자열
     * @param string $format 출력할 시간 형식
     * @return string 변환된 시간 문자열
     */
    function display_kst($utc_time, $format = 'Y-m-d H:i:s')
    {
        if (empty($utc_time)) {
            return '기록 없음';
        }

        try {
            // 1. UTC 기준 DateTime 객체 생성
            $date = new \DateTime($utc_time, new \DateTimeZone('UTC'));
            
            // 2. 시간대를 서울(Asia/Seoul, UTC+9)로 변경
            $date->setTimezone(new \DateTimeZone('Asia/Seoul'));
            
            // 3. 지정된 형식으로 반환
            return $date->format($format);
        } catch (\Exception $e) {
            return $utc_time; // 변환 실패 시 원본 반환
        }
    }
}