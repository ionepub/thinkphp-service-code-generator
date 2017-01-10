<?php
namespace Common\%s\Service;
use Common\%s\Dao\%sDao;

/**
 * %sService
 * @author %s
 */
class %sService
{
	private $dao = null;

	public function __construct(){
		$this->dao = new %sDao();
	}

	%s

}