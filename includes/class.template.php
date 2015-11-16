<?

#**************************************************************************#
#   Very Simple Template Class
#   -------------------------------------------------------------------
#   By: ToToDoDo Email: ToToDoDo#GmAiL.com
#   Homepage: www.h4cker.org
#**************************************************************************#

class verySimpleTemplate{

	var $template = "";
	var $assigned = array();

	//-----------------------------------------
	// deal with the template and assign
	//-----------------------------------------

	function deal( $templatePath, $arrayAssigned = array() )
	{

		$this->template = $templatePath;
		$this->assigned = $arrayAssigned;

		$this->_loadTemplate();
		$this->_parseTemplate();

	}

	//-----------------------------------------
	// load template from file
	//-----------------------------------------

	function _loadTemplate()
	{

		if ( file_exists( $this->template ) )
		{
			if ( $FH = fopen( $this->template, 'r') )
			{
				$this->template = fread( $FH, filesize( $this->template ) );
				fclose( $FH );
			}
			else
			{
				echo "Couldn't open the template file";
			}
		}
		else
		{
			echo "Template file does not exist";
		}
 
	}

	//-----------------------------------------
	// parse the array assign
	//-----------------------------------------

	function _parseTemplate()
	{
 
		foreach( $this->assigned as $word => $replace)
		{
			$this->template = preg_replace( "/\{$word\}/i", "$replace", $this->template );
		}
 
	}

	//-----------------------------------------
	// output
	//-----------------------------------------
 
	function output()
	{

		echo $this->template;

	}

}
