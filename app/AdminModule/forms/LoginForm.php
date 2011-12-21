<?php

namespace AdminModule\Forms;

use Nette\Application\UI\Form as AppForm,
	Nette\Forms\Form,
	Nette\Security\AuthenticationException;


class LoginForm extends AppForm
{

	public function __construct(Nette\IComponentContainer $parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);

		$this->addProtection('Token timeout. Please send form again.');

		$this->addText('login', 'Email:')
				->addRule(Form::FILLED, 'Enter login email')
				->addRule(Form::EMAIL, 'Filled value is not valid email');

		$this->addPassword('password', 'Password:')
				->addRule(Form::FILLED, 'Enter password');

		$this->addSubmit('send', 'Log in!');
		$this->onSuccess[] = array($this, 'processSuccess');
	}


	public function processSuccess(AppForm $form)
	{
		try {
			$user = $this->presenter->user;
			$user->login($form['login']->value, $form['password']->value);

			$this->presenter->application->restoreRequest($this->presenter->backlink);
			$this->presenter->redirect('Default:default');
		} catch (AuthenticationException $e) {
			$form->addError($e->getMessage());
		}
	}

}
