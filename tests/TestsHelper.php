<?php

namespace Tests;


use App\{Post,User};

trait TestsHelper
{

    /**
     * @var User
     */
    protected $defaultUser;


    public function defaultUser(array $attributes = [])
    {
        if($this->defaultUser){
            return $this->defaultUser;
        }

        return $this->defaultUser = factory(User::class)->create($attributes);

    }


    public function createPost(array $attributes = []){
        return factory(Post::class)->create($attributes);
    }

}