<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;
// TODO: テスト用のDBを用意したら有効化する
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;

class MeTest extends TestCase
{
    use WithFaker;
    // TODO: テスト用のDBを用意したら有効化する
    // use RefreshDatabase;

    public function testCreateUser()
    {
        $response = $this
            ->actingAsUser()
            ->graphQL('
            query {
                me {
                    id
                    name
                    name_kana
                    handle_name
                    handle_name_kana
                    email
                }
            }
        ');

        $data = $response->json('data.me');
        $expectUser = Arr::only($this->loginUser->toArray(), array_keys($data));
        $this->assertEquals($expectUser, $data);
    }
}
