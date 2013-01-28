<?php

namespace AdminModule;

use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;

class AuthPresenter extends BasePresenter
{
	/** @persistent */
	public $backlink;


	/**
	 * Login form factory
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentLoginForm()
	{
		$form = new Form;
		$form->addText('name', 'Name:')
			->addRule(Form::FILLED, 'Enter login');
		$form->addPassword('password', 'Password:')
			->addRule(Form::FILLED, 'Enter password');
		$form->addSubmit('send', 'Log in');

		$form->onSuccess[] = $this->processLoginForm;
		return $form;
	}


	/**
	 * Process login form and login user
	 */
	public function processLoginForm($form)
	{
		$values = $form->getValues(TRUE);
		try {
			$this->user->login($values['name'], $values['password']);
			$this->restoreRequest($this->backlink);
			$this->redirect('Default:default');

		} catch (AuthenticationException $e) {
			$form->addError($e->getMessage());
		}
	}

}