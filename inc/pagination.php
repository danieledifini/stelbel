<?php

function getPaginationHTMX($total = 0,$per_page = 0,$cur_page = 1, $total_pages = 0,$method = ''){
	$msg = '';

	if($total >= $per_page & $total_pages > 1)
	{
		$first    = get_field("first","options");
		$previous = get_field("previous","options");
		$page     = get_field("page","options");
		$of       = get_field("of","options");
		$next     = get_field("next","options");
		$last     = get_field("last","options");

		$msg .= '<nav class="pagination m-t-double foc fs-12"  aria-label="Select page"> <ul class="dis-flex align-center jus-center">';
			
		$class = '';
		$disabled = '';

		if($cur_page == 1)
		{
			$class = 'disabled';
			$disabled = 'disabled';
		}	

		$msg .= '<li><button '.$disabled .' role="menu-item" class="page-btn default-btn transition '.$class.'  standard-link " hx-get="/api/'.$method.'" hx-target="#items-wrapper" data-page="1">'.$first.'</button></li>';
		$msg .= '<li><button '.$disabled .' role="menu-item"  class="page-btn default-btn  transition '.$class.' standard-link " hx-get="/api/'.$method.'" data-page="'.($cur_page - 1).'"  hx-target="#items-wrapper">'.$previous.'</button></li>';
		$msg .= '<li aria-current="page"><span> '.$page.'&nbsp;'.$cur_page.' / '.$total_pages.'</span></li>';
		$class = '';
		$disabled = '';

		if($cur_page == $total_pages){
			$class = 'disabled';
			$disabled = 'disabled';
		}	

		$msg .= '<li><button '.$disabled .' role="menu-item" class="page-btn default-btn  transition '.$class.' standard-link " hx-get="/api/'.$method.'"  data-page="'.($cur_page + 1).'"  hx-target="#items-wrapper">'.$next.'</button></li>';
		$msg .= '<li><button '.$disabled .' role="menu-item" class="page-btn default-btn   transition '.$class.' standard-link " hx-get="/api/'.$method.'" data-page="'.($total_pages).'"   hx-target="#items-wrapper">'.$last.'</button></li>';
		$msg .= ' </ul></nav><!-- pagination --> ';
	}  

	return $msg;
}