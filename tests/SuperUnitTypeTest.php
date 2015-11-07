<?php
use SuperUnit\ISuperUnitType;
use SuperUnit\SuperUnitType;

/*{{{*/
class DummyType implements ISuperUnitType
{
	public function description()
	{
		return 'dummy';
	}

	public function determine($name, $value)
	{
		return 'dummy';
	}

	public function form($name, $value, $isEdit = false)
	{
		return sprintf("<input type=\"text\" name=\"%s\" value=\"%s\" />", $name, $value);
	}

	public function mysql($name, $value)
	{
		return sprintf("`%s` VARCHAR(200) NOT NULL DEFAULT ''", $name);
	}
}
/*}}}*/

class SuperUnitTypeTest extends PHPUnit_Framework_TestCase
{
	public function testSanitize()
	{
		$this->assertEquals(SuperUnitType::sanitizeName('a _ !!Name (I) % Don\'t Know'), 'a_NameIDontKnow');
		$this->assertEquals(SuperUnitType::sanitizeValue('<ok>'), '&lt;ok&gt;');
	}

	/** 
	 * @expectedException \SuperUnit\SuperUnitTypeException
	 * @expectedExceptionMessage Type object must implement ISuperUnitType
	 */
	public function testInstallObject()
	{
		$sut = new SuperUnitType;
		$dummy = new DummyType;
		$sut->install($dummy);
		$defs = $sut->getDefs();
		$this->assertEquals($defs['DummyType'], $dummy);
		$obj = new StdClass;
		$sut->install($obj);
	}

	/**
	 * @expectedException \SuperUnit\SuperUnitTypeException
	 * @expectedExceptionMessageRegExp #Second.*#
	 */
	public function testInstallString()
	{
		$sut = new SuperUnitType;
		$sut->install('dummy', ['description' => null, 'determine' => null, 'form' => null, 'mysql' => null]);
		$defs = $sut->getDefs();
		$this->assertEquals($defs['dummy'], ['description' => null, 'determine' => null, 'form' => null, 'mysql' => null]);
		$sut->install('dummy', ['determine' => null, 'form' => null]);
	}


	public function testDefaultTypes()
	{
		$sut = new SuperUnitType;

		$tests = [];
		$tests['email'] = 'yarco.wang@gmail.com';
		$tests['float'] = 15.8;
		$tests['id'] = 1;
		$tests['int'] = 15;
		$tests['url'] = 'http://www.google.com';
		$tests['password'] = '******';
		$tests['date'] = '1970-01-01';
		$tests['time'] = '15:20';
		$tests['datetime'] = '1970-01-01 20:20:20';
		$tests['color'] = '#FFFFFF';
		$tests['choice'] = '!Male! Female';
		$tests['multi-choice'] = 'Apple|IBM|Microsoft';
		$tests['string'] = str_repeat('a', SuperUnitType::$DefEdgeStringText - 1);
		$tests['text'] = str_repeat('a', SuperUnitType::$DefEdgeStringText + 1);
		foreach($tests as $tp => $v) {
			$this->assertEquals($tp, $sut->determine($tp, $v));
		}

		$asserts = [];
		$asserts['email'] = '<input type="email" name="email" value="yarco.wang@gmail.com" />';
		$asserts['float'] = '<input type="text" name="float" value="15.8" />';
		$asserts['id'] = '<input type="hidden" name="id" value="1" />';
		$asserts['int'] = '<input type="number" name="int" value="15" />';
		$asserts['url'] = '<input type="url" name="url" value="http://www.google.com" />';
		$asserts['password'] = '<input type="password" name="password" value="" />';
		$asserts['date'] = '<input type="date" name="date" value="1970-01-01" />';
		$asserts['time'] = '<input type="time" name="time" value="15:20" />';
		$asserts['datetime'] = '<input type="datetime-local" name="datetime" value="1970-01-01 20:20:20" />';
		$asserts['color'] = '<input type="color" name="color" value="#FFFFFF" />';
		$asserts['choice'] = '<select name="choice"><option value="0" checked></option><option value="1">Male</option><option value="2">Female</option></select>';
		$asserts['multi-choice'] = '<select name="multichoice" multiple size="3"><option value="1">Apple</option><option value="2">IBM</option><option value="3">Microsoft</option></select>';
		$asserts['string'] = '<input type="text" name="string" value="aaaaaaaaaaaaaaaaaaaaaaa" />';
		$asserts['text'] = '<textarea name="text">aaaaaaaaaaaaaaaaaaaaaaaaa</textarea>';
		foreach($asserts as $tp => $assert) {
			$this->assertEquals($assert, $sut->form($tp, $tp, $tests[$tp], true));
		}

		$asserts = [];
		$asserts['email'] = '`email` VARCHAR(80) NOT NULL DEFAULT \'\'';
		$asserts['float'] = '`float` DOUBLE NOT NULL DEFAULT 0.0';
		$asserts['id'] = '`id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$asserts['int'] = '`int` INTEGER NOT NULL DEFAULT 0';
		$asserts['url'] = '`url` VARCHAR(250) NOT NULL DEFAULT \'\'';
		$asserts['password'] = '`password` VARCHAR(120) NOT NULL DEFAULT \'\'';
		$asserts['date'] = '`date` DATE NOT NULL DEFAULT \'0000-00-00\'';
		$asserts['time'] = '`time` TIME NOT NULL DEFAULT \'00:00:00\'';
		$asserts['datetime'] = '`datetime` TIMESTAMP NOT NULL DEFAULT 0';
		$asserts['color'] = '`color` CHAR(7) NOT NULL DEFAULT \'\'';
		$asserts['choice'] = '`choice` TINYINT NOT NULL DEFAULT 0';
		$asserts['multi-choice'] = '`multichoice` VARCHAR(250) NOT NULL DEFAULT \'\'';
		$asserts['string'] = '`string` VARCHAR(250) NOT NULL DEFAULT \'\'';
		$asserts['text'] = '`text` TEXT';
		foreach($asserts as $tp => $assert) {
			$this->assertEquals($assert, $sut->mysql($tp, $tp, $tests[$tp]));
		}
	}
}
