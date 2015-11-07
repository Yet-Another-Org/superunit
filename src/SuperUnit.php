<?php
/**
 * @file SuperUnit.php
 * fast code generator
 *
 * @author Yarco <yarco.wang@gmail.com>
 * @since 2015-11-06
 */
/* vim: set tabstop=2 shiftwidth=2 softtabstop=2 noexpandtab ai si: */

namespace SuperUnit;

class SuperUnit
{
	public $Ex = [];
	public $ExTp = [];

	private $_sut;

	public static function splitString($string, $sep = '\s+:\s+')
	{
		if (preg_match_all('#\b([_a-zA-Z][_a-zA-Z0-9\-]*)' . $sep . '(.*?)(?=(?:(?:[_a-zA-Z][_a-zA-Z0-9\-]*' . $sep .')|\z))#ms', $string, $matched) === false) {
			return [];
		}

		$ret = [];
		foreach($matched[1] as $k => $name) {
			$ret[trim($name)] = trim($matched[2][$k]);
		}
		return $ret;
	}

	public function __construct()
	{
		$this->_sut = new SuperUnitType;
	}

	public function fromString($string, $sep = '\s+:\s+')
	{
		$this->Ex = self::splitString($string, $sep);
		return $this;
	}

	public function fromHtml($html)
	{
		return $this->fromString(strip_tags($html));
	}

	public function determine(array $ex = [])
	{
		if (!empty($ex)) {
			$this->Ex = $ex;
		}

		$this->ExTp = [];
		foreach($this->Ex as $k => $v) {
			$this->ExTp[$k] = $this->_sut->determine($k, $v);
		}

		return $this;
	}

	/** check whether the target string as the same format as in Ex {{{
	 *
	 * @param string text should be compared with Ex
	 * @param string regular expression for determining the seperator between the key and value
	 * @return true/array true for valid, [key=>,want=>,get=>] for failture
	 */
	public function validate($string, $sep = '\s+:\s+')
	{
		$ex = self::splitString($string, $sep);
		foreach($this->ExTp as $k => $v) {
			if (!isset($ex[$k])) {
				return ['key' => $k, 'want' => $v, 'get' => 'undefined'];
			}

			$tp = $this->_sut->determine($k, $ex[$k]);
			if ($tp !== $v) {
				return ['key' => $k, 'want' => $v, 'get' => $tp];
			}
		}

		return true;
	}
	/*}}}*/

	// ---- following functions based on SuperUnitType ----
	public function form($isEdit = false)
	{
		foreach($this->Ex as $name => $value) {
			yield $this->_sut->form($this->ExTp[$name], $name, $value, $isEdit);
		}
	}

	public function mysql()
	{
		foreach($this->Ex as $name => $value) {
			yield $this->_sut->mysql($this->ExTp[$name], $name, $value);
		}
	}
}
