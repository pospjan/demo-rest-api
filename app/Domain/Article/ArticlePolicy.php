<?php declare(strict_types = 1);

namespace App\Domain\Article;

use App\Domain\User\Role;
use App\Domain\User\User;

class ArticlePolicy
{

	public function canCreate(User $user): bool
	{
		return in_array($user->role, [Role::Author, Role::Admin], true);
	}

	public function canDelete(User $user, Article $article): bool
	{
		if ($user->role === Role::Admin) {
			return true;
		}

		return $user->role === Role::Author && $article->getAuthorId() === $user->id;
	}

	public function canEdit(User $user, Article $article): bool
	{
		return $this->canDelete($user, $article);
	}

}
