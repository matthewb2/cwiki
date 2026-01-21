<?php

namespace App\Controllers;

// 아래 줄을 추가하세요
use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends BaseController
{
    public function login()
    {
        $data = [
            'title' => '로그인 - CWiki'
        ];
        return view('auth/login', $data);
    }
    public function authenticate()
    {
        $session = session();
        $userModel = new \App\Models\UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // 1. 사용자 찾기
        $user = $userModel->where('username', $username)->first();

        if ($user) {
            // 2. 비밀번호 해시 비교 (PHP 내장 함수 password_verify 사용)
            if (password_verify($password, $user['password'])) {
                // 3. 로그인 성공: 세션 데이터 설정
                $sessionData = [
                    'user_id'   => $user['id'],
                    'username'  => $user['username'],
                    'isLoggedIn' => true,
                ];
                $session->set($sessionData);

                return redirect()->to('/')->with('message', $username . '님, 환영합니다!');
            }
        }

        // 4. 로그인 실패
        return redirect()->back()->with('error', '아이디 또는 비밀번호가 올바르지 않습니다.');
    }

    public function register()
    {
        $data = [
            'title' => '회원가입 - CWiki'
        ];
        return view('auth/register', $data);
    }

    public function store()
    {
        // 1. 유효성 검사 규칙
        $rules = [
            'username' => [
                'rules'  => 'required|min_length[3]|max_length[20]|is_unique[users.username]',
                'errors' => [
                    'is_unique' => '이미 사용 중인 아이디입니다.',
                    'required'  => '아이디는 필수 입력 사항입니다.'
                ]
            ],
            'email'    => [
                'rules'  => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'is_unique' => '이미 등록된 이메일입니다.',
                    'valid_email' => '올바른 이메일 형식이 아닙니다.'
                ]
            ],
            'password' => [
                'rules'  => 'required|min_length[8]',
                'errors' => ['min_length' => '비밀번호는 최소 8자 이상이어야 합니다.']
            ],
            'password_confirm' => [
                'rules'  => 'matches[password]',
                'errors' => ['matches' => '비밀번호가 일치하지 않습니다.']
            ]
        ];

        if (!$this->validate($rules)) {
            // 검증 실패 시 입력값과 에러를 가지고 뒤로 가기
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. 데이터 저장
        $userModel = new UserModel();

        $userData = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'), // 모델의 hashPassword에서 자동 해싱됨
        ];

        if ($userModel->save($userData)) {
            return redirect()->to('/login')->with('message', '회원가입이 완료되었습니다. 로그인해 주세요!');
        } else {
            return redirect()->back()->withInput()->with('errors', ['db' => '데이터 저장 중 오류가 발생했습니다.']);
        }
    }
    // 프로필 수정 페이지 표시
    public function profile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find(session()->get('user_id'));

        $data = [
            'title' => '프로필 수정 - CWiki',
            'user'  => $user
        ];
        return view('auth/profile', $data);
    }

    // 프로필 수정 처리
    public function update()
    {
        $userModel = new \App\Models\UserModel();
        $userId = session()->get('user_id');

        $rules = [
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]",
        ];

        // 비밀번호를 입력한 경우에만 검증 규칙 추가
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[8]';
            $rules['password_confirm'] = 'matches[password]';
        }

        $updateData = [
            'id'    => $userId,
            'email' => $this->request->getPost('email'),
        ];

        // 비밀번호가 입력되었다면 데이터에 추가 (모델의 hashPassword가 처리)
        if ($this->request->getPost('password')) {
            $updateData['password'] = $this->request->getPost('password');
        }

        $userModel->save($updateData);
        return redirect()->to('auth/profile')->with('message', '프로필 정보가 업데이트되었습니다.');
    }
}
