<?php

namespace AclProj\Security;

use	Nette\Object,
	Nette\Database\Connection,
	Nette\Security\Identity,
	Nette\Security\IAuthenticator,
	Nette\Security\AuthenticationException;


class Authenticator extends Object implements IAuthenticator
{

	private $dbConnection;
	private $passwordSalt;


	public function __construct(Connection $dbConnection, $salt)
	{
		$this->dbConnection = $dbConnection;
		$this->passwordSalt = $salt;
	}


	public function authenticate(array $credentials)
	{
		$email    = $credentials[self::USERNAME];
		$password = sha1($credentials[self::PASSWORD] . $this->passwordSalt);

		$user = $this->dbConnection
			->table('user')
			->where('email=?', array($email))
			->fetch();

		if (!$user) {
			throw new AuthenticationException('User not found', self::IDENTITY_NOT_FOUND);
		}
		if ($user->password != $password) {
			throw new AuthenticationException('Wrong password', self::INVALID_CREDENTIAL);
		}

		$identity = new Identity($user->id, $user->role);
		$identity->name = $user->name;
		$identity->email = $user->email;

		return $identity;
	}

}
