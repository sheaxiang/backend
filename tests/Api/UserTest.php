<?php

namespace Tests\Api;

class UserTest extends BaseApiTestCase
{
    public function testGetAllUsers()
    {
        $this->withOAuthTokenTypePassword();
        $this->getApi('users');
        $this->printResponseData();
        $this->assertJsonStructure([
            'data' => [
                '*' => [
                    'user_id',
                    'name',
                ],
            ],
        ]);
    }

    public function testGetCurrentUser()
    {
        $this->withOAuthTokenTypePassword();
        $this->getApi('users/me');
        $this->printResponseData();
        $this->assertResponseOk();
    }

    public function testGetSingleUser()
    {
        $this->withOAuthTokenTypePassword();
        $this->getApi('users/1');
        $this->assertResponseOk();
    }

    public function testCreateUser()
    {
        $this->withOAuthTokenTypePassword();
        $this->postApi('users', [
            'name'     => 'Abc',
            'email'    => rand(100000, 999999).'abc@example.com',
            'password' => '12345678',
        ]);
        $this->printResponseData();
        $this->assertResponseOk();
    }

    public function testUpdateUser()
    {
        $this->withOAuthTokenTypePassword();
        $this->putApi('users/2', [
            'name' => 'Harry Potter',
        ]);
        $this->printResponseData();
        $this->assertResponseNoContent();
    }

    public function testDeleteUser()
    {
        $user = \Yeelight\Models\Foundation\User::find(3);
        if (!$user) {
            $user = factory(\Yeelight\Models\Foundation\User::class, 1)->make();
            $user->user_id = 3;
            $user->save();
        }

        $this->withOAuthTokenTypePassword();
        $this->deleteApi('users/3');
        $this->printResponseData();
        $this->assertResponseNoContent();
    }
}
