<?php
/**
 * @file Autoloader.php
 *
 * @author Yarco <yarco.wang@gmail.com>
 * @since 2015-11-07
 */
/* vim: set tabstop=2 shiftwidth=2 softtabstop=2 noexpandtab ai si: */

namespace SuperUnit;

class Autoloader
{
	private $_srcDir;
	private $_prefix;
	private $_prefixLen;

	public function __construct()
	{
		$this->_srcDir = __DIR__;
		$this->_prefix = __NAMESPACE__ . '\\';
		$this->_prefixLen = strlen($this->_prefix);
	}

	public function autoload($class)
	{
		if (substr($class, 0, $this->_prefixLen) === $this->_prefix) {
			$classfile = $this->_srcDir . '/' . str_replace('\\', '/', substr($class, $this->_prefixLen)) . '.php';
			if (file_exists($classfile)) {
				require_once $classfile;
			}
		}
	}

	public static function register()
	{
		spl_autoload_register(array(new self(), 'autoload'));
	}
}
