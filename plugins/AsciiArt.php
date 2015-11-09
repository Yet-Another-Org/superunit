<?php
	//function moreTypes();
	//function toString(SuperUnit $su);

class AsciiArt
{
	protected function toAsciiArtDef($type, $name, $value)
	{
		$s = ''; $name = strtoupper($name);
		switch($type) {
			case 'id':
				$s = sprintf("%14s: [ %d ]", $name, $value);
				break;
			case 'password':
			case 'color':
			case 'email':
			case 'url':
			case 'datetime':
			case 'date':
			case 'time':
				$s = sprintf("%14s: [ %s ]", $name, $value);
				break;
			case 'choice':
				$t = [];
				foreach(explode('!', $value) as $k => $v) {
					$t[] = $k === 0 ? sprintf('@ %s', $v) : sprintf('O %s', $v);
				}
				$s = sprintf("%14s: %s", $name, implode('  ', $t));
				break;
			case 'multi-choice':
				$t = [];
				foreach(explode('|', $value) as $k => $v) {
					$t[] = $k === 0 ? sprintf('[x] %s', $v) : sprintf('[] %s', $v);
				}
				$s = sprintf("%14s: %s", $name, implode('  ', $t));
				break;
			case 'bool':
				$value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
				$s = sprintf("%14s: [%s]", $name, $value ? 'x' : ' ');
				break;
			case 'int':
				$s = sprintf("%14s: [ %d ]", $name, $value);
				break;
			case 'float':
				$s = sprintf("%14s: [ %f ]", $name, $value);
				break;
			case 'string':
				$s = sprintf("%14s: [ %s ]", $name, $value);
				break;
			case 'text':
				$value = array(substr($value, 0, 20), substr($value, 20, 17) . '...');
				$s = sprintf("%14s: %'-24s\n%16s| %20s |\n%16s| %20s |\n%16s%'-24s", $name, '', '', $value[0], '', $value[1], '', '');
				break;
		}
		return $s;
	}

	public function toString($su)
	{
		$ret = sprintf("%'-46s%'-34s\n", 'Ascii Art', '');
		foreach($su->ExTp as $name => $tp) {
			$ret .= sprintf("%s\n", $this->toAsciiArtDef($tp, $name, $su->Ex[$name]));
		}
		$ret .= "\n";
		return $ret;
	}
}

