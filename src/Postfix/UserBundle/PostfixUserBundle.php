<?php

namespace Postfix\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PostfixUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}