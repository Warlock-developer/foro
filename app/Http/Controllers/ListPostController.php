<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Illuminate\Http\Request;

class ListPostController extends Controller
{

    public function __invoke(Category $category = null, Request $request)
    {

        $routeName = $request->route()->getName();

        list($orderColumn, $orderDirection) = $this->getListOrder($request->get('orden'));

        $posts = Post::query()
            ->scopes($this->getListScopes($category,$routeName))
            ->orderBy($orderColumn, $orderDirection)
            ->paginate();

        $posts->appends(request()->intersect(['orden']));

        $categoryItems = $this->getCategoryItems($routeName);

        return view('posts.index',compact('posts','category','categoryItems'));
    }


    protected function getCategoryItems($routeName)
    {
        return Category::query()
            ->orderBy('name')
            ->get()
            ->map(function($category) use ($routeName) {
                return [
                    'title' => $category->name,
                    'full_url' => route($routeName, $category)
                ];
            })
            ->toArray();
        /*
        return Category::orderBy('name')->get()->map(function ($category) {
            return [
                'title' => $category->name,
                'full_url' => route('posts.index', $category)
            ];
        })->toArray();
        */
    }

    /**
     * @param Category $category
     * @param string $routeName
     * @return array
     */
    protected function getListScopes(Category $category, $routeName)
    {
        $scopes = [];

        if($category->exists) {
            $scopes['category'] = [$category];
        }


        if($routeName == 'posts.pending') {
            $scopes[] = 'pending';
        }

        if($routeName == 'posts.completed') {
            $scopes[] = 'completed';
        }

        return $scopes;
    }


    protected function getListOrder($order)
    {
        if($order == 'recientes') {
            return ['created_at', 'desc'];
        }


        if($order == 'antiguos') {
            return ['created_at', 'asc'];
        }

        return ['created_at', 'desc'];
    }

}