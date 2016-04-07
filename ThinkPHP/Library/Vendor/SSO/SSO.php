<?php
/**
 * SSO phpclient
 * wang baoqing
 * 2014-09-09
 */
include('Client.php');

class PHPSSO extends Client
{
	public function __construct($configfile=null)
	{
		parent::__construct($configfile);
	}
}