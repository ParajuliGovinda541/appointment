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

        $post->update(['status' => 'Active']);

        return [
            'success' => true,
            'message' => 'Post activated successfully.'
        ];
    }

    public function deactivate($id)
    {
        $post = $this->findById($id);

        if ($post->officers()->where('status', 'Active')->exists()) {
            return [
                'success' => false,
                'message' => 'Cannot deactivate this post because it has active officers.'
            ];
        }

        // Deactivate post
        $post->update(['status' => 'Inactive']);

        return [
            'success' => true,
            'message' => 'Post deactivated successfully.'
        ];
    }
}
