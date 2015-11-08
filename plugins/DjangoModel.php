<?php
	//function moreTypes();
	//function toString(SuperUnit $su);

class DjangoModel
{
	protected function toDjangoModelDef($type, $name, $value)
	{
		$s = '';
		switch($type) {
			case 'id':
				$s = sprintf("%s = models.AutoField() # this is not required", $name);
				break;
			case 'password':
				$s = sprintf("%s = models.CharField(max_length=120)", $name);
				break;
			case 'color':
				$s = sprintf("%s = models.CharField(max_length=7)", $name);
				break;
			case 'email':
				$s = sprintf("%s = models.EmailField()", $name);
				break;
			case 'url':
				$s = sprintf("%s = models.URLField()", $name);
				break;
			case 'datetime':
				$s = sprintf("%s = models.DateTimeField()", $name);
				break;
			case 'date':
				$s = sprintf("%s = models.DateField()", $name);
				break;
			case 'time':
				$s = sprintf("%s = models.TimeField()", $name);
				break;
			case 'choice':
				$s = sprintf("%s = models.SmallIntegerField()", $name);
				break;
			case 'multi-choice':
				$s = sprintf("%s = models.CharField(max_length=250)", $name);
				break;
			case 'bool':
				$s = sprintf("%s = models.BooleanField()", $name);
				break;
			case 'int':
				$s = sprintf("%s = models.IntegerField()", $name);
				break;
			case 'float':
				$s = sprintf("%s = models.FloatField()", $name);
				break;
			case 'string':
				$s = sprintf("%s = models.CharField(max_length=255)", $name);
				break;
			case 'text':
				$s = sprintf("%s = models.TextField()", $name);
				break;
		}
		return $s;
	}

	public function toString($su)
	{
		$ret = sprintf("%'-45s%'-35s\n", 'Django Model', '');
		foreach($su->ExTp as $name => $tp) {
			$ret .= sprintf("%s\n", $this->toDjangoModelDef($tp, $name, $su->Ex[$name]));
		}
		$ret .= "\n";
		return $ret;
	}
}

/*
https://docs.djangoproject.com/en/1.8/ref/models/fields/#field-types
*/
