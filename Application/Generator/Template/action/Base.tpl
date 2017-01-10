<?php
namespace Common\%s\Action;
use Common\%s\Service\%sService;

/**
 * %sAction
 * @author %s
 */
class %sAction
{
	private $service = null;

	public function __construct(){
		$this->service = new %sService();
	}

	%s

}