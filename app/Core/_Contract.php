<?php

namespace App\Core;

/*
*	When the JPEG file is converted to text there is a text file.
*   The issue here is that not every text file is the same.
*   Sometimes certain methods will work, and other times a 
*	different approach is needed.  Each Core class therefore, 
*	tests a method.  If the method failed to get the value,
*   another method is tried.
*/


interface _Contract{

	# First method attempted
	public function parseLevel1();


	# First method is tested
	public function testLevel1($result);


	# Second method attemtped
	public function parseLevel2();


}