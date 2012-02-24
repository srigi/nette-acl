<?php

namespace AdminModule;

use Nette\Security\User;


abstract class BasePresenter extends \BasePresenter
{

	public function startup()
	{
		parent::startup();

		if (!$this->user->isLoggedIn() && !$this->user->isAllowed($this->name, $this->action)) {
			if ($this->user->getLogoutReason() === User::INACTIVITY) {
				$this->flashMessage('Session timeout, you have been logged out', 'danger');
			}

			$backlink = $this->getApplication()->storeRequest();
			$this->redirect($this->context->params['authAction'], array('backlink' => $backlink));

		} else {
			if (!$this->user->isAllowed($this->name, $this->action)) {
				$this->flashMessage('Access denied. You don\'t have permissions to view that page.', 'danger');
				$this->redirect('Default:');
			}
		}
	}

}
