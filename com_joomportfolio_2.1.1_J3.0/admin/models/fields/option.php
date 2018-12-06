<?php

/**
* JoomPortfolio component for Joomla 2.5
* @package JoomPortfolio
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldOption extends JFormField
{
	protected $type = 'Option';

	function getInput() {
		$input = '';
		
		if (!$this->value) {
			$this->value[] = '';
			$this->value[] = '';
			$this->value[] = '';
			$this->value[] = '';
		}
		
		for ($i=0, $n=count($this->value);$i<$n;$i++) {
			$value = $this->value[$i];
			$input .= '<label id="'.$this->id.'_'.$i.'-lbl" for="'.$this->id.'_'.$i.'" class="option-label" >'.JText::_((string)$this->element['label']).'</label>';
			$input .= '<input id="'.$this->id.'_'.$i.'" type="text" name="'.$this->name.'" value="'.$value.'" />';
			if ($i != 0) {
				$input .= '<img onclick="removeOption(this);" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAABl0RVh0U29mdHdhcmUAUGFpbnQuTkVUIHYzLjUuODc7gF0AAAJuSURBVDhPjVNLSxthFE1AqI9QXDTbQiEiKIL9MXad31DI1m3dlG6qpmCdyXyTydOYmETznGiiec4kk0T3HReuGij0B5ze+xWEkAr94C7mcc8959zzeT0vnJ2dHb/X6/UB8Mxms59PT0+/X/r3n+81TUuPRiP0+n0Eg8GPLzZvbGy8iUQiQuVSVaEoivh+eipKpbLb7fZwe3cH+m6Fw9/E8XFYHB0dia+Hh2J/f/+zz+db8mxubr57eHjA/f09JpMpxuMxRo6D4XAIy7LRHwzQ7fXQ7nQJrI1m6xbXN00oasQlgBWP3+9/HQqFvpimiXq9jlqtjmq1ikqlinK5AmKCq6sSLi8vUSwWkS8UENG0X3t7H0Jra2tLUtru7vsAs+BiBsxkOp2CPXDomcuyLGI1kswymczj+vr68rMv29vbAZbAVaAJjUaDQCbI5S5gmg0CcqjpHDViaNk20mdnj6urq/MAPHFCdXGRl1IcZ4zz8yxJqsGmqan0mZTFnqRS6UUAnjgeT2QTe8D009RUrlQk/XgiKf3o9fpIJJOLAKzdIfe5qVQuw7aHSFATG9jvDxA1DBTJyA5tIx5PzANsbW0FWCeblEwmyfErDGiqYcSk87xGTRPI5wu4o1UasdgiAOu0yaBYLCaN5AQKIaQn7U4HiqIim8uhRTnQo8YiAIdmMLCgC126zymkZCKbzco0npycyE3cUIiE0BcBWCcbZNIKm60W2u0OKmRm4/pGPrMXtbpJ3685SPMAgUDgbTwe/0H0XdLtRqOGq+tRV+i6qwnhUoNLd8VVVNU9VRT308FBl3Lwau6CLf89K/9ZzyH6A+inGXSQm6+fAAAAAElFTkSuQmCC" />';
			}
			if ($i!=$n-1) {
				$input .= '</li><li>';
			}
		}
		return $input;
	}
	
	function getLabel() {
		return '';
	}
}
