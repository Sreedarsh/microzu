/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var $k =jQuery.noConflict();
$k(document).ready(function(){
//alert('d');
 //alert($k('.nav-container .nav-inner').css('width'));
	//$k('#menu22, #popup22, #menu3, #popup3').hover(function() {
        $k('.nav-inner #advancedmenu .menu:nth-child(1), .nav-inner #advancedmenu .megnor-advanced-menu-popup:nth-child(2), .nav-inner #advancedmenu .menu:nth-child(3), .nav-inner #advancedmenu .megnor-advanced-menu-popup:nth-child(4),.nav-inner #advancedmenu .menu:nth-child(5), .nav-inner #advancedmenu .megnor-advanced-menu-popup:nth-child(6)').hover(function() {
	   $k('.hire-prof').hide();
          $k('#advancedmenu div.megnor-advanced-menu-popup').css('width',$k('.nav-container .nav-inner').css('width'));
          //$k('.nav-container .nav-inner #advancedmenu div.megnor-advanced-menu-popup').attr('style', 'left: 0px !important');

	},function() {
	  $k('.hire-prof').show();
	});



/*alert($k('#menu3').width());
$k('.hire-prof').css('left',$k('#menu3').width()-8);*/

});

