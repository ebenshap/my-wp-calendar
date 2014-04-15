<?php
/**
 * @package eben_calendar
 * @version 1
 */
/*
Plugin Name: Eben's Simple Calendar
Description: This is a really simple calendar that only marks the current date.
Author: Eben Shapiro
Version: 1
Author URI: http://ebenshapiro.com/
*/

class ebenCalendar{

	public $firstOfMonth;
	public $tomonth;
	public $toyear;
	public $today;
	public $daysInMonth;
	public $dayOfTheWeek;

	function __construct($time=null){
		if($time==null){
			$time=time();
		}
	
		$today= date('d-m-Y', $time);
		
		//Month as string
		$this->tomonth=date( 'F', $time);

		//full four numeral year
		$this->toyear=date('Y', $time);
	
		$length=strpos($today, '-');
		//get today's day number
		$this->today= (int)substr($today, 0,$length);
		$this->firstOfMonth= '01'.substr($firstOfMonth, $length);
		
		//convert the date of the first of the month to figure out how many days are in the month and
		//the day of the week that the month starts on
		$firstInTime=strtotime($this->firstOfMonth);
		$this->daysInMonth=date('t', $firstInTime);
		
		$this->startDayOfTheWeek= date('w',$firstInTime);
	}

	function drawCalendar(){
	
		echo '<div id="eb-calendar">';
		echo '<table>';
		echo '<caption>'.$this->tomonth.' '.$this->toyear.'</caption>';
		//print the header row
		echo '<tr><td>Su</td>
				<td>Mo</td>
				<td>Tu</td>
				<td>We</td>
				<td>Th</td>
				<td>Fr</td>
				<td>Sa</td>
				</tr>';
		$weekCount=0;
		//start from negative the day num of the week the month starts on
		//these negative numbers will print blanks
		//if i is less than the number of days in the week
		//or weekCount is not 0, then keep going
		//weekCount keeps track of how many days of a week have been printed
		for($i=(-($this->startDayOfTheWeek))+1; $i<$this->daysInMonth || $weekCount!=0; $i++){
			if($weekCount==0){
				echo '<tr>';
			}
				//mark the cell that contains today's date
				if($i==$this->today){
					echo '<td class="today">';
				}else{
					echo '<td>';
				}
				//print a number if i still matches the number of days in a month
				if ($i>0 &&$i<=$this->daysInMonth){
					echo ($i);
				}
				echo '</td>';
			if($weekCount==6){
				echo '</tr>';
				//finished printing a week
				$weekCount=0;
				continue;
			}
			$weekCount++;
		}
		echo '</table>';
		echo '</div>';
	}
}



class eben_calendar_widget extends WP_Widget{
	function eben_calendar_widget(){
		$widget_ops= array('classname'=>'eben_calendar_widget','description'=>'A calendar that displays a date');
		$this->WP_Widget('eben_calendar_widget', 'Eben\'s Calendar Widget', $widget_ops);
	}
	
	function form($instance){
		$defaults=array('title'=>'Eben\'s Calendar Widget');
	}
	
	function update($new_instance, $old_instance){
		$instance=$old_instance;
		return $instance;
	}
	
	function widget($args, $instance){
		extract($args);
		echo $before_widget;
		$calendar=new ebenCalendar();
		$calendar->drawCalendar();	
		echo $after_widget;
	}	
}

 add_action('wp_print_styles', 'add_my_stylesheet');

function add_my_stylesheet() {
        $myStyleUrl = plugins_url('ebenCalendar.css', __FILE__); 
        $myStyleFile = WP_PLUGIN_DIR . '/ebenCalendar/ebenCalendar.css';
        
        if ( file_exists($myStyleFile) ) {
       
            wp_register_style('myStyleSheets', $myStyleUrl);
            wp_enqueue_style( 'myStyleSheets');
        }
    }


add_action('widgets_init', 'eben_calendar_register_widget');

function eben_calendar_register_widget(){
	register_widget('eben_calendar_widget');
}


?>
