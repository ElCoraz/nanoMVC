<?php
//********************************************************************************** */
class View
{
	//****************************************************************************** */
	function generate($templateView, $params = null, $data = null)
	{
		include 'mvc/views/' . $templateView;
	}
	//****************************************************************************** */
}
//********************************************************************************** */