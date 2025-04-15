<?php

function get_language_shortcode() {
	return apply_filters( 'wpml_current_language', null );
}

function get_translated_link($permalink = '',$code = '') {
	return apply_filters( 'wpml_permalink',$permalink, $code );
}

function language_selector(){
    $languages = icl_get_languages('skip_missing=0');
	$msg = '';
    
    if(!empty($languages)){
        $i = 0;
        $class = "";
       
        foreach($languages as $l){
            if($l['active']){
				$msg .= '<button class="language-toggler  transition hover-secondary default-btn " type="button" data-toggle="collapse" data-target="#language-menu" aria-controls="language-menu" aria-expanded="false" aria-label="Toggle language navigation" x-on:click.stop="languageMenuClosed = !languageMenuClosed" tabindex="0" >';
                $msg .=  strtoupper($l['code']) .'</button>';
            }
        }

        $msg .= '<ul class="wpml-ls-sub-menu">';

        foreach($languages as $l){

            if($l['active']){

            }
            else
            {
            	$msg .=  '<li class="wpml-ls-item-'.$l['language_code'].' ">';
				$msg .= '<a href="'.$l['url'].'" class="wpml-ls-link hover-secondary">'.strtoupper($l['code']) .'</a>';
				$msg .=  '</li>';
            }
            
        }
        $msg .=  '</ul>';
    }

	return $msg;
}