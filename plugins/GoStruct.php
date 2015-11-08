<?php
	//function moreTypes();
	//function toString(SuperUnit $su);

class GoStruct
{
	protected function toGoStructDef($type, $name, $value)
	{
		$s = '';
		switch($type) {
			case 'id':
				$s = sprintf("%s int", $name);
				break;
			case 'password':
			case 'color':
			case 'email':
			case 'url':
				$s = sprintf("%s string", $name);
				break;
			case 'datetime':
			case 'date':
			case 'time':
				$s = sprintf("%s time.Time // import \"time\"", $name);
				break;
			case 'choice':
				$s = sprintf("%s uint8", $name);
				break;
			case 'multi-choice':
				$s = sprintf("%s []uint8", $name);
				break;
			case 'bool':
				$s = sprintf("%s bool", $name);
				break;
			case 'int':
				$s = sprintf("%s int", $name);
				break;
			case 'float':
				$s = sprintf("%s float64", $name);
				break;
			case 'string':
			case 'text':
				$s = sprintf("%s string", $name);
				break;
		}
		return $s;
	}

	public function toString($su)
	{
		$ret = sprintf("%'-44s%'-36s\n", 'Go Struct', '');
		foreach($su->ExTp as $name => $tp) {
			$ret .= sprintf("%s\n", $this->toGoStructDef($tp, $name, $su->Ex[$name]));
		}
		$ret .= "\n";
		return $ret;
	}
}

/*
https://golang.org/ref/spec#Types

Boolean types
A boolean type represents the set of Boolean truth values denoted by the predeclared constants true and false. The predeclared boolean type is bool.

Numeric types
A numeric type represents sets of integer or floating-point values.

String types

A string type represents the set of string values. A string value is a (possibly empty) sequence of bytes. Strings are immutable: once created, it is impossible to change the contents of a string. The predeclared string type is string.

Array types

An array is a numbered sequence of elements of a single type, called the element type. The number of elements is called the length and is never negative.

Slice types

A slice is a descriptor for a contiguous segment of an underlying array and provides access to a numbered sequence of elements from that array. A slice type denotes the set of all slices of arrays of its element type. The value of an uninitialized slice is nil.

...
*/
