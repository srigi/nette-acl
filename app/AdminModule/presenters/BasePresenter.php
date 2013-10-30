<?php

namespace AdminModule;

use Nette\Security\User;

abstract class BasePresenter extends \BasePresenter
{
	public function startup()
	{
		parent::startup();

		if ($this->name != 'Admin:Auth') {
			if (!$this->user->isLoggedIn()) {
				if ($this->user->getLogoutReason() === User::INACTIVITY) {
					$this->flashMessage('Session timeout, you have been logged out');
				}

				$this->redirect('Auth:login', array(
					'backlink' => $this->storeRequest()
				));

			} else {
				if (!$this->user->isAllowed($this->name, $this->action)) {
					$this->flashMessage('Access denied');
					$this->redirect('Default:');
				}
			}
		}
	}


	/**
	 * Logout user
	 */
	public function handleLogout()
	{
		$this->user->logOut();
		$this->flashMessage('You were logged off.');
		$this->redirect('this');
	}

}