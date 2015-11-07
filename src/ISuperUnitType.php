<?php
/**
 * @file ISuperUnitType.php
 * interface
 *
 * @author Yarco <yarco.wang@gmail.com>
 * @since 2015-11-06
 */
/* vim: set tabstop=2 shiftwidth=2 softtabstop=2 noexpandtab ai si: */

namespace SuperUnit;

interface ISuperUnitType
{
	// description
	function description();

	// determine the type by name and value
	function determine($name, $value);

	// generate form code
	function form($name, $value, $isEdit = false);

	// generate sql code
	function mysql($name, $value);
}
