<?php
	//function moreTypes();
	//function toString(SuperUnit $su);

class EmberModel
{
	protected function toEmberModelDef($type, $name, $value)
	{
		$s = '';
		switch($type) {
			case 'id':
				$s = sprintf("%s: DS.attr('number')", $name);
				break;
			case 'password':
			case 'color':
			case 'email':
			case 'url':
				$s = sprintf("%s: DS.attr('string')", $name);
				break;
			case 'datetime':
			case 'date':
			case 'time':
				$s = sprintf("%s: DS.attr('date')", $name);
				break;
			case 'choice':
				$s = sprintf("%s: DS.attr('number')", $name);
				break;
			case 'multi-choice':
				$s = sprintf("%s: DS.attr('string')", $name);
				break;
			case 'bool':
				$s = sprintf("%s: DS.attr('boolean')", $name);
				break;
			case 'int':
				$s = sprintf("%s: DS.attr('number')", $name);
				break;
			case 'float':
				$s = sprintf("%s: DS.attr('number')", $name);
				break;
			case 'string':
			case 'text':
				$s = sprintf("%s: DS.attr('string')", $name);
				break;
		}
		return $s;
	}

	public function toString($su)
	{
		$ret = sprintf("%'-45s%'-35s\n", 'Ember Model', '');
		foreach($su->ExTp as $name => $tp) {
			$ret .= sprintf("%s\n", $this->toEmberModelDef($tp, $name, $su->Ex[$name]));
		}
		$ret .= "\n";
		return $ret;
	}
}

/*
Ember Data supports attribute types of string, number, boolean, and date
*/
