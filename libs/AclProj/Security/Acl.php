<?php

namespace AclProj\Security;

use Nette\Security\Permission;


class Acl extends Permission
{

	public function __construct()
	{
		//roles
		$this->addRole('guest');
		$this->addRole('member', 'guest');
		$this->addRole('editor', 'member');
		$this->addRole('admin');

		// resources
		$this->addResource('Admin:Default');
		$this->addResource('Admin:Page');
		$this->addResource('Admin:User');

		// privileges
		$this->allow('member',	'Admin:Default',	Permission::ALL);
		$this->allow('editor',	'Admin:Page',		Permission::ALL);
		$this->allow('admin',	Permission::ALL,	Permission::ALL);
	}

}
