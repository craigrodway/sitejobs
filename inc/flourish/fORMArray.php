<?php
/**
 * Adds Array functionality to fActiveRecord and fRecordSet
 *
 * Recordsets/records in Flourish are a PITA to work with, if all you want 
 * is a simple array of keys => values to manipulate/loop through/add keys to;
 * but retain all the magic of having queries built for you.
 *
 * Essentially the same code as fORMJSON; except it just returns the array of data instead.
 * 
 * @copyright  Copyright (c) 2008-2009 Will Bond
 * @author     Will Bond [wb] <will@flourishlib.com>
 * @license    http://flourishlib.com/license
 * 
 * @package    Flourish
 * 
 * @version    1.0.0
 */
class fORMArray
{
	// The following constants allow for nice looking callbacks to static methods
	const extend          = 'fORMArray::extend';
	const reflect         = 'fORMArray::reflect';
	const toArray          = 'fORMArray::toArray';
	const toArrayRecordSet = 'fORMArray::toArrayRecordSet';
	
	
	/**
	 * Adds the method `toArray()` to fActiveRecord and fRecordSet instances
	 * 
	 * @return void
	 */
	static public function extend()
	{
		fORM::registerReflectCallback(
			'*',
			self::reflect
		);
		
		fORM::registerActiveRecordMethod(
			'*',
			'toArray',
			self::toArray
		);
		
		fORM::registerRecordSetMethod(
			'toArray',
			self::toArrayRecordSet
		);
	}
	
	
	/**
	 * Adjusts the fActiveRecord::reflect() signatures of columns that have been added by this class
	 * 
	 * @internal
	 * 
	 * @param  string  $class                 The class to reflect
	 * @param  array   &$signatures           The associative array of `{method name} => {signature}`
	 * @param  boolean $include_doc_comments  If doc comments should be included with the signature
	 * @return void
	 */
	static public function reflect($class, &$signatures, $include_doc_comments)
	{
		$signature = '';
		if ($include_doc_comments) {
			$signature .= "/**\n";
			$signature .= " * Converts the values from the record into an array\n";
			$signature .= " * \n";
			$signature .= " * @return string  The Array representation of this record\n";
			$signature .= " */\n";
		}
		$signature .= 'public function toArray()';
		
		$signatures['toArray'] = $signature;
	}
	
	
	/**
	 * Returns an array representation of the record
	 * 
	 * @internal
	 * 
	 * @param  fActiveRecord $object            The fActiveRecord instance
	 * @param  array         &$values           The current values
	 * @param  array         &$old_values       The old values
	 * @param  array         &$related_records  Any records related to this record
	 * @param  array         &$cache            The cache array for the record
	 * @param  string        $method_name       The method that was called
	 * @param  array         $parameters        The parameters passed to the method
	 * @return array  The Array that represents the values of this record
	 */
	static public function toArray($object, &$values, &$old_values, &$related_records, &$cache, $method_name, $parameters)
	{
		$output = array();
		foreach ($values as $column => $value) {
			if (is_object($value) && is_callable(array($value, '__toString'))) {
				$value = $value->__toString();
			} elseif (is_object($value)) {
				$value = (string) $value;	
			}
			$output[$column] = $value;
		}
		
		return $output;
	}
	
	
	/**
	 * Returns an array object representation of a record set
	 * 
	 * @internal
	 * 
	 * @param  fRecordSet $record_set  The fRecordSet instance
	 * @param  string     $class       The class of the records
	 * @param  array      &$records    The fActiveRecord objects
	 * @return array  The array that represents an array of all of the fActiveRecord objects
	 */
	static public function toArrayRecordSet($record_set, $class, &$records)
	{
		return $record_set->call('toArray');
	}
	
	
	/**
	 * Forces use as a static class
	 * 
	 * @return fORMArray
	 */
	private function __construct() { }
}



/**
 * Copyright (c) 2008-2009 Will Bond <will@flourishlib.com>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */