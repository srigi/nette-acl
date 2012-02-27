<?php

namespace AdminModule\Forms;

use Nette\Application\UI\Form as AppForm,
	Nette\Forms\Form,
	Nette\Utils\Html,
	Nette\Security\AuthenticationException;


class LoginForm extends AppForm
{

	public function __construct(Nette\IComponentContainer $parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);

		$this->onSuccess[] = array($this, 'processSuccess');

		$this->addText('email')
			->addRule(Form::FILLED, 'Enter login email')
			->addRule(Form::EMAIL, 'Filled value is not valid email');

		$this->addPassword('password')
			->addRule(Form::FILLED, 'Enter password');

		$this->addCheckbox('remember');

		$this->addSubmit('send', 'Log in!');
	}


	public function processSuccess(AppForm $form)
	{
		$user = $this->presenter->user;

		if (TRUE === $form->values['remember']) {
			$user->setExpiration('+30 days', $whenBrowserIsClosed = FALSE);
		} else {
			$user->setExpiration(0, $whenBrowserIsClosed = TRUE);
		}

		try {
			$user->login($form['email']->value, $form['password']->value);
		} catch (AuthenticationException $e) {
			$form->addError($e->getMessage());
			return;
		}

		$this->presenter->application->restoreRequest($this->presenter->backlink);
		$this->presenter->redirect('Default:default');
	}

}
