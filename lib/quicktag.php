<?php

class QuickTag
{
	private $qtString; // The QuickTag formatted string we're passing
	private $idRegEx = '[a-z][a-z0-9\-_]*'; // regular expression to find ids
	private $classRegEx = '[a-z\-_][a-z0-9\-_]*'; // regular expression to find classes
	private $charset = '[a-z0-9\-_]'; // characters for classes and ids, aware this isn't strict enough, resons are in docs
	private $execTime = 0;
	
	/**
	 * Constructor, interprets the given string and outputs valid HTML
	 */
	public function __construct($qtString)
	{
		// profiling
		$this->execTime = microtime(true);
		
		// save the QuickTag formatted string
		$this->qtString = $qtString;
		
		// run the interpreter
		$this->parseQtString();
		
		$this->execTime = microtime(true) - $this->execTime;
	}
	
	/**
	 * interpret a qt string
	 */
	private function parseQtString()
	{
		// ids first
		$this->findIds();
		
		$this->findClasses();
	}
	
	/**
	 * Find element ids
	 */
	private function findIds()
	{
		// ids can be before, after or even between classes (eg: <a.class#my-id | <div#my-id.class1.class_2 | <p.class_1#my-id.class-2
		// so just look for something starting with a # and ending with either a . or a space
		$this->qtString = preg_replace('/<([a-z]+)((?:\.'.$this->charset.'*)*)#('.$this->charset.'+)((?:\.'.$this->charset.'*)*)/i', '<$1$2$4 id="$3"', $this->qtString);
	}
	
	/**
	 * Find classes ids
	 */
	private function findClasses()
	{		
		// regex string to find ids		
		$matches = array();
		
		// run reg ex search and replace
		preg_match_all('/<[a-z]+((?:\.'.$this->charset.'*)+)/i', $this->qtString, $matches);
		
		// loop though matches and replace them
		foreach($matches[1] as $i=>$match)
		{
			// text to replace will be $i in the previous array
			$search = $matches[0][$i];
			
			// remove everything after the dot to extact the tag name
			$tag = array_shift(explode('.', $search));
			
			// classes will be in format .class-one.class-two, this needs to be class-one class-two
			// remove first dot and switch all other dots for spaces
			$classes = str_replace('.', ' ', substr($match, 1));
			
			$this->qtString = str_replace($search, $tag.' class="'.$classes.'"', $this->qtString);
		}
	}
	
	/**
	 * Get HTML formatted output
	 */
	public function getHTML()
	{
		return $this->qtString;
	}
	
	public function getExecTime()
	{
		return $this->execTime;
	}
}