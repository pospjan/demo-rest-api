<?php declare(strict_types = 1);

namespace App\Domain\User;

enum Role: string
{

	case Admin = 'Admin';
	case Author = 'Author';
	case Reader = 'Reader';

}
