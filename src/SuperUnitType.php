<?php
/**
 * @file SuperUnitType.php
 * types
 *
 * @author Yarco <yarco.wang@gmail.com>
 * @since 2015-11-06
 */
/* vim: set tabstop=2 shiftwidth=2 softtabstop=2 noexpandtab ai si: */

namespace SuperUnit;

class SuperUnitType
{
	protected static $_defs = [];

	public static $DefIdName = 'id';
	public static $DefEdgeStringText = 24;

	public static function sanitizeName($name)
	{
		return preg_replace('|[^a-zA-Z_0-9]|', '', $name);
	}

	public static function sanitizeValue($value)
	{
		return htmlspecialchars(trim($value));
	}

	/** install default types {{{
	 */
	protected static function installDefaults()
	{
		$DefIdName = & self::$DefIdName;
		$DefEdgeStringText = & self::$DefEdgeStringText;

		// id type {{{
		self::$_defs['id'] = [
			'description' => function() use($DefIdName) {
				return "the name should be `$DefIdName`, and the value should be integer";
			},
			'determine' => function($name, $value) {
				return $name === self::$DefIdName && filter_var($value, FILTER_VALIDATE_INT);
			},
			'form' => function($name, $value, $isEdit = false) {
				return $isEdit ? sprintf('<input type="hidden" name="%s" value="%s" />', self::sanitizeName($name), self::sanitizeValue($value)) : '';
			},
			'mysql' => function($name, $value) {
				return sprintf("`%s` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY", self::sanitizeName($name));
			},
		];
		// }}}
		// int type {{{
		self::$_defs['int'] = [
			'description' => function() {
				return "the value should be integer";
			},
			'determine' => function($name, $value) {
				return filter_var($value, FILTER_VALIDATE_INT);
			},
			'form' => function($name, $value, $isEdit = false) {
				return sprintf('<input type="number" name="%s" value="%s" />', self::sanitizeName($name), $isEdit ? self::sanitizeValue($value) : '');
			},
			'mysql' => function($name, $value) {
				return sprintf("`%s` INTEGER NOT NULL DEFAULT 0", self::sanitizeName($name));
			},
		];
		// }}}
		// float type {{{
		self::$_defs['float'] = [
			'description' => function() {
				return "the value should be float";
			},
			'determine' => function($name, $value) {
				return filter_var($value, FILTER_VALIDATE_FLOAT);
			},
			'form' => function($name, $value, $isEdit = false) {
				return sprintf('<input type="text" name="%s" value="%s" />', self::sanitizeName($name), $isEdit ? self::sanitizeValue($value) : '');
			},
			'mysql' => function($name, $value) {
				return sprintf("`%s` DOUBLE NOT NULL DEFAULT 0.0", self::sanitizeName($name));
			},
		];
		// }}}
		// password type {{{
		self::$_defs['password'] = [
			'description' => function() {
				return "the value should be `******`";
			},
			'determine' => function($name, $value) {
				return $value === '******';
			},
			'form' => function($name, $value, $isEdit = false) {
				return sprintf('<input type="password" name="%s" value="" />', self::sanitizeName($name));
			},
			'mysql' => function($name, $value) {
				return sprintf("`%s` VARCHAR(120) NOT NULL DEFAULT ''", self::sanitizeName($name));
			},
		];
		// }}}
		// color type {{{
		self::$_defs['color'] = [
			'description' => function() {
				return "the value should match `^#[0-9a-fA-F]{6}$`";
			},
			'determine' => function($name, $value) {
				return filter_var($value, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '|^#[0-9a-fA-F]{6}$|']]);
			},
			'form' => function($name, $value, $isEdit = false) {
				return sprintf('<input type="color" name="%s" value="%s" />', self::sanitizeName($name), $isEdit ? self::sanitizeValue($value) : '');
			},
			'mysql' => function($name, $value) {
				return sprintf("`%s` CHAR(7) NOT NULL DEFAULT ''", self::sanitizeName($name));
			},
		];
		// }}}
		// email type {{{
		self::$_defs['email'] = [
			'description' => function() {
				return "the value should be email";
			},
			'determine' => function($name, $value) {
				return filter_var($value, FILTER_VALIDATE_EMAIL);
			},
			'form' => function($name, $value, $isEdit = false) {
				return sprintf('<input type="email" name="%s" value="%s" />', self::sanitizeName($name), $isEdit ? self::sanitizeValue($value) : '');
			},
			'mysql' => function($name, $value) {
				return sprintf("`%s` VARCHAR(80) NOT NULL DEFAULT ''", self::sanitizeName($name));
			},
		];
		// }}}
		// url type {{{
		self::$_defs['url'] = [
			'description' => function() {
				return "the value should be url";
			},
			'determine' => function($name, $value) {
				return filter_var($value, FILTER_VALIDATE_URL);
			},
			'form' => function($name, $value, $isEdit = false) {
				return sprintf('<input type="url" name="%s" value="%s" />', self::sanitizeName($name), $isEdit ? self::sanitizeValue($value) : '');
			},
			'mysql' => function($name, $value) {
				return sprintf("`%s` VARCHAR(250) NOT NULL DEFAULT ''", self::sanitizeName($name));
			},
		];
		// }}}
		// date type {{{
		self::$_defs['date'] = [
			'description' => function() {
				return "the value should either match `^\d{2}/\d{2}/\d{4}$` or match `^\d{4}-\d{2}-\d{2}$`";
			},
			'determine' => function($name, $value) {
				return filter_var($value, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '|^\d{2}/\d{2}/\d{4}$|']]) ||
					filter_var($value, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '|^\d{4}-\d{2}-\d{2}$|']]);
			},
			'form' => function($name, $value, $isEdit = false) {
				return sprintf('<input type="date" name="%s" value="%s" />', self::sanitizeName($name), $isEdit ? self::sanitizeValue($value) : '');
			},
			'mysql' => function($name, $value) {
				return sprintf("`%s` DATE NOT NULL DEFAULT '0000-00-00'", self::sanitizeName($name));
			},
		];
		// }}}
		// time type {{{
		self::$_defs['time'] = [
			'description' => function() {
				return "the value should either match `^\d{2}:\d{2}:\d{2}$` or match `^\d{2}:\d{2}$`";
			},
			'determine' => function($name, $value) {
				return filter_var($value, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '|^\d{2}:\d{2}:\d{2}$|']]) ||
					filter_var($value, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '|^\d{2}:\d{2}$|']]);
			},
			'form' => function($name, $value, $isEdit = false) {
				return sprintf('<input type="time" name="%s" value="%s" />', self::sanitizeName($name), $isEdit ? self::sanitizeValue($value) : '');
			},
			'mysql' => function($name, $value) {
				return sprintf("`%s` TIME NOT NULL DEFAULT '00:00:00'", self::sanitizeName($name));
			},
		];
		// }}}
		// datetime type {{{
		self::$_defs['datetime'] = [
			'description' => function() {
				return "the value should match `^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}(?::\d{2})?$`";
			},
			'determine' => function($name, $value) {
				return filter_var($value, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '|^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}(?::\d{2})?$|']]);
			},
			'form' => function($name, $value, $isEdit = false) {
				return sprintf('<input type="datetime-local" name="%s" value="%s" />', self::sanitizeName($name), $isEdit ? self::sanitizeValue($value) : '');
			},
			'mysql' => function($name, $value) {
				return sprintf("`%s` TIMESTAMP NOT NULL DEFAULT 0", self::sanitizeName($name));
			},
		];
		// }}}		
		// choice type {{{
		self::$_defs['choice'] = [
			'description' => function() {
				return "the value should match `^[^!]*(?:![^!]+)+$`";
			},
			'determine' => function($name, $value) {
				return filter_var($value, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '#^[^!]*(?:![^!]+)+$#']]);
			},
			'form' => function($name, $value, $isEdit = false) {
				$value = explode('!', $value);
				array_walk($value, function(& $item, $k) {
					$item = sprintf('<option value="%d"%s>%s</option>', $k, $k === 0 ? ' checked' : '', self::sanitizeValue($item));
				});
				
				return sprintf('<select name="%s">%s</select>', self::sanitizeName($name), implode('', $value));
			},
			'mysql' => function($name, $value) {
				return sprintf("`%s` TINYINT NOT NULL DEFAULT 0", self::sanitizeName($name));
			},
		];
		// }}}
		// multi-choice type {{{
		self::$_defs['multi-choice'] = [
			'description' => function() {
				return "the value should match `^[^|]*(?:\|[^|]+)+$`";
			},
			'determine' => function($name, $value) {
				return filter_var($value, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '#^[^|]*(?:\|[^|]+)+$#']]);
			},
			'form' => function($name, $value, $isEdit = false) {
				$value = explode('|', $value);
				array_walk($value, function(& $item, $k) {
					$item = sprintf('<option value="%d">%s</option>', $k + 1, self::sanitizeValue($item));
				});
				
				return sprintf('<select name="%s" multiple size="%d">%s</select>', self::sanitizeName($name), count($value), implode('', $value));
			},
			'mysql' => function($name, $value) {
				return sprintf("`%s` VARCHAR(250) NOT NULL DEFAULT ''", self::sanitizeName($name));
			},
		];
		// }}}
		// string type {{{
		self::$_defs['string'] = [
			'description' => function() use($DefEdgeStringText) {
				return "the value length should less than $DefEdgeStringText";
			},
			'determine' => function($name, $value) {
				return mb_strlen($value, 'utf-8') <= self::$DefEdgeStringText;
			},
			'form' => function($name, $value, $isEdit = false) {
				return sprintf('<input type="text" name="%s" value="%s" />', self::sanitizeName($name), $isEdit ? self::sanitizeValue($value) : '');
			},
			'mysql' => function($name, $value) {
				return sprintf("`%s` VARCHAR(250) NOT NULL DEFAULT ''", self::sanitizeName($name));
			},
		];
		// }}}
		// text type {{{
		self::$_defs['text'] = [
			'description' => function() {
				return "if not match above types, it will be text";
			},
			'determine' => function($name, $value) {
				return true;
			},
			'form' => function($name, $value, $isEdit = false) {
				return sprintf('<textarea name="%s">%s</textarea>', self::sanitizeName($name), $isEdit ? self::sanitizeValue($value) : '');
			},
			'mysql' => function($name, $value) {
				return sprintf("`%s` TEXT", self::sanitizeName($name));
			},
		];
		// }}}
	}
	/*}}}*/

	public function __construct()
	{
		self::installDefaults();
	}

	/** install types {{{
	 */
	public function install($mix, array $defs = [])
	{
		$type = gettype($mix);
		switch ($type) {
			case 'object':
				if (!($mix instanceof ISuperUnitType)) {
					throw new SuperUnitTypeException("Type $type must implement ISuperUnitType");
				}
				self::$_defs[get_class($mix)] = $mix;
				break;
			case 'string':
				$methods = [];
				$r = new \ReflectionClass('\SuperUnit\ISuperUnitType');
				foreach($r->getMethods() as $method) {
					$methods[] = $method->name;
				}
				if (count(array_intersect($methods, array_keys($defs))) != count($methods)) {
					throw new SuperUnitTypeException("Second argument in install should have " . implode(',', $methods) . " fields");
				}
				self::$_defs[$mix] = $defs;
				break;
			default:
				throw new SuperUnitTypeException("Unknown type $type");
		}
	}
	/*}}}*/

	public function getDefs()
	{
		return self::$_defs;
	}

	public function determine($name, $value)
	{
		foreach(self::$_defs as $tp => $mix) {
			$callback = is_object($mix) ? [$mix, __FUNCTION__] : $mix[__FUNCTION__];
			$ret = call_user_func($callback, $name, $value);
			if ($ret) {
				return $tp;
			}
		}

		return 'text';
	}

	public function __call($method, $args)
	{
		if (count($args) < 1) {
			throw new SuperUnitTypeException('Argument{0} must be one of the types: ' . implode(',', array_keys(self::$_defs)));
		}

		$tp = array_shift($args);
		$callback = is_object(self::$_defs[$tp]) ? [self::$_defs[$tp], $method] : self::$_defs[$tp][$method];
		return call_user_func_array($callback, $args);
	}
}

