<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    public function view(?User $user, Post $post): bool
    {
        if ($post->status === 'published') {
            return true;
        }

        if (! $user) {
            return false;
        }

        return $user->id === $post->user_id || $user->isEditor();
    }

    public function create(User $user): bool
    {
        return $user->isAuthor();
    }

    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->isEditor();
    }

    public function publish(User $user, ?Post $post = null): bool
    {
        return $user->canPublish();
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->isEditor();
    }

    public function restore(User $user, Post $post): bool
    {
        return $user->isEditor();
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return $user->isAdmin();
    }
}
