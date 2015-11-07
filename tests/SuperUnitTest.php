<?php
use SuperUnit\SuperUnit;
use SuperUnit\SuperUnitType;

class SuperUnitTest extends PHPUnit_Framework_TestCase
{
	public function testSplitString()
	{
		$text =<<<EOF
Name=Yarco
Age=36
Sex=Male
Interests=Sleeping|Pretty Girl|Sunshine|Programming
EOF;
		$this->assertEquals('Name,Age,Sex,Interests', implode(',', array_keys(SuperUnit::splitString($text, '='))));
		$this->assertEquals('Yarco,36,Male,Sleeping|Pretty Girl|Sunshine|Programming', implode(',', array_values(SuperUnit::splitString($text, '='))));
	}

	public function testDetermineFields()
	{
		$su = new SuperUnit;
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
		$su->determine($tests);

		foreach($su->ExTp as $k => $tp) {
			$this->assertEquals($k, $tp);
		}

		return $su;
	}

	/**
	 * @depends testDetermineFields
	 */
	public function testValidate(SuperUnit $su)
	{
		$text =<<<EOF
email : nobody@nobody.com
EOF;
		$this->assertEquals(['key' => 'float', 'want' => 'float', 'get' => 'undefined'], $su->validate($text));
		$text =<<<EOF
email : nobody@nobody.com
float : 12.0
id : 2
int : 17
url : ftp://www.nobody.com
password : ******
date : 1980-01-01
time : 12:00
datetime : 1980-01-01 12:00
color : #000000
choice : Yes! No
multi-choice : Money|Girl|House
string : oh, yeah
text : you never know what could happen...you never know what could happen...
EOF;
		$this->assertTrue($su->validate($text));
	}

	/**
	 * @depends testDetermineFields
	 */
	public function testCommonFunctions(SuperUnit $su)
	{
		$s = '';
		foreach($su->form() as $el) {
			$s .= $el;
		}
		$this->assertEquals('<input type="email" name="email" value="" /><input type="text" name="float" value="" /><input type="number" name="int" value="" /><input type="url" name="url" value="" /><input type="password" name="password" value="" /><input type="date" name="date" value="" /><input type="time" name="time" value="" /><input type="datetime-local" name="datetime" value="" /><input type="color" name="color" value="" /><select name="choice"><option value="0" checked></option><option value="1">Male</option><option value="2">Female</option></select><select name="multichoice" multiple size="3"><option value="1">Apple</option><option value="2">IBM</option><option value="3">Microsoft</option></select><input type="text" name="string" value="" /><textarea name="text"></textarea>', $s);

		$s = '';
		foreach($su->mysql() as $el) {
			$s .= $el;
		}
		$this->assertEquals("`email` VARCHAR(80) NOT NULL DEFAULT ''`float` DOUBLE NOT NULL DEFAULT 0.0`id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY`int` INTEGER NOT NULL DEFAULT 0`url` VARCHAR(250) NOT NULL DEFAULT ''`password` VARCHAR(120) NOT NULL DEFAULT ''`date` DATE NOT NULL DEFAULT '0000-00-00'`time` TIME NOT NULL DEFAULT '00:00:00'`datetime` TIMESTAMP NOT NULL DEFAULT 0`color` CHAR(7) NOT NULL DEFAULT ''`choice` TINYINT NOT NULL DEFAULT 0`multichoice` VARCHAR(250) NOT NULL DEFAULT ''`string` VARCHAR(250) NOT NULL DEFAULT ''`text` TEXT", $s);
	}
}
