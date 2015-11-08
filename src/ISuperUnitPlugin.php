<?php
/**
 * @file ISuperUnitPlugin.php
 *
 * @author Yarco <yarco.wang@gmail.com>
 * @since 2015-11-08
 */
/* vim: set tabstop=2 shiftwidth=2 softtabstop=2 noexpandtab ai si: */

namespace SuperUnit;

/**
 * @interface ISuperUnitPlugin
 *
 * just a place holder, you don't have to implement this interface
 * if the class has below methods, it should work
 *
 */
interface ISuperUnitPlugin
{
	function moreTypes();
	function toString(SuperUnit $su);
}
