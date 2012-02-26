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
		$this->addProtection('Token timeout. Please send form again.');

		$this->addText('login')
				->addRule(Form::FILLED, 'Enter login email')
				->addRule(Form::EMAIL, 'Filled value is not valid email');

		$this->addPassword('password')
				->addRule(Form::FILLED, 'Enter password');

		$this->addCheckbox('remember', 'remember');

		$this->addSubmit('send', 'Log in!');


		$this->renderer->wrappers['controls']['container'] = NULL;

		$this->getElementPrototype()->class('well form-inline');

		$this['login']->getControlPrototype()
			->class('input')
			->placeholder('email');

		$this['password']->getControlPrototype()
			->class('input')
			->placeholder('password');

		$this['send']->getControlPrototype()
			->class('btn pull-right');
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
			$user->login($form['login']->value, $form['password']->value);
		} catch (AuthenticationException $e) {
			$form->addError($e->getMessage());
			return;
		}

		$this->presenter->application->restoreRequest($this->presenter->backlink);
		$this->presenter->redirect('Default:default');
	}

}
