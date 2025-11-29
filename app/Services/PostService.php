<?php

namespace App\Services;

use App\Models\Post;

/**
 * Class PostService.
 */
class PostService
{



    public function getAll()
    {
        return Post::orderBy('id', 'desc')->get();
    }

    public function create(array $data)
    {
        return Post::create([
            'name' => $data['name'],
            'status' => 'Active',
        ]);
    }

    public function update(Post $post, array $data)
    {
        return $post->update([
            'name' => $data['name']
        ]);
    }

    public function findById($id)
    {
        return Post::findOrFail($id);
    }




    public function activate($id)
    {
        $post = $this->findById($id);
        return $post->update(['status' => 'Active']);
    }

    public function deactivate($id)
    {
        $post = $this->findById($id);

        if ($post->officers()->where('status', 'Active')->exists()) {
            return false;
        }

        return $post->update(['status' => 'Inactive']);
    }
}
