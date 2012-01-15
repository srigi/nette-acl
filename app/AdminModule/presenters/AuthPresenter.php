<?php

namespace AdminModule;

use AdminModule\Forms\LoginForm;


final class AuthPresenter extends BasePresenter
{

	/** @persistent */
	public $backlink = '';


	public function actionLogout()
	{
		$this->user->logOut();
		$this->redirect('login');
	}


	protected function createComponentLoginForm($name)
	{
		return new LoginForm();
	}

}
