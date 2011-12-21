<?php

namespace AdminModule;

use Nette\Http\User;


abstract class SecuredPresenter extends BasePresenter
{

	public function startup()
	{
		parent::startup();

		if (!$this->user->isLoggedIn()) {
			if ($this->user->getLogoutReason() === User::INACTIVITY) {
				$this->flashMessage('Session timeout, you have been logged out', 'danger');
			}

			$backlink = $this->getApplication()->storeRequest();
			$this->redirect('Auth:login', array('backlink' => $backlink));

		} else {
			if (!$this->user->isAllowed($this->name, $this->action)) {
				$this->flashMessage('Access diened. You don\'t have permissions to view that page.', 'danger');
				$this->redirect('Default:');
			}
		}
	}

}
