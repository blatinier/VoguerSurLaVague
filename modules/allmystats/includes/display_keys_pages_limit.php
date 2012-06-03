<?php
//Quikly Todo ratio
		$switch_short_complete_list = true;

		$submit_limit_keywords = $_POST['submit_limit_keywords'];
		$limit_keywords = $_POST['limit_keywords'];
		$submit_limit_pages = $_POST['submit_limit_pages'];
		$limit_pages = $_POST['limit_pages'];

		$val_limit_keywords = $first_limit_keywords+1;
		$val_complete_list_limit_keywords = $complete_list_limit_keywords+1;

		if (isset($submit_limit_keywords)) {
			if ($limit_keywords == " " || $limit_keywords == 'LIMIT '.$val_complete_list_limit_keywords) {
				$limit_keywords = 'LIMIT '.$val_limit_keywords;
			} else {
				$limit_keywords = 'LIMIT '.$val_complete_list_limit_keywords;
			}
		}
		
		if(!$limit_keywords) { //1ere affichage
			$limit_keywords = 'LIMIT '.$val_limit_keywords;
		}

		if($limit_keywords == " " || $limit_keywords == 'LIMIT '.$val_complete_list_limit_keywords) {
			$value_button_keywords = MSG_SHORTLIST;	
$switch_short_complete_list = false;
		} else {
			$value_button_keywords = MSG_COMPLETE_LIST ;
		}

		//-----------------------------------------------------
		
		if (isset($submit_limit_pages)) {
			if ($limit_pages == " " || $limit_pages == 'LIMIT '.$complete_list_limit_pages) {
				$limit_pages = 'LIMIT '.$first_limit_pages;
			} else {
				$limit_pages = 'LIMIT '.$complete_list_limit_pages;
			}
		}
		
		if(!$limit_pages) { //1ere affichage
			$limit_pages = 'LIMIT '.$first_limit_pages;
		}
		

		if($limit_pages == " " || $limit_pages == 'LIMIT '.$complete_list_limit_pages) {
			$value_button_pages = MSG_SHORTLIST;	
		} else {
			$value_button_pages = MSG_COMPLETE_LIST ;
		}

?>