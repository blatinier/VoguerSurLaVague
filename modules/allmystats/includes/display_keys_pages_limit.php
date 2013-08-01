<?php
//Quikly --> Todo optimize code

	$switch_short_complete_list = true;

	if(isset($_POST['submit_limit_keywords'])){
		$submit_limit_keywords = $_POST['submit_limit_keywords'];
	}
	if(isset($_POST['limit_keywords'])){
		$limit_keywords = $_POST['limit_keywords'];
	}

	if(isset($_POST['submit_limit_pages'])){
		$submit_limit_pages = $_POST['submit_limit_pages'];
	}
	if(isset($_POST['limit_pages'])){
		$limit_pages = $_POST['limit_pages'];
	}


		$val_limit_keywords = $small_limit_keywords+1;
		$val_complete_list_limit_keywords = $max_limit_keywords+1;

		// Click button small or max display keyword
		if (isset($submit_limit_keywords)) {
			if ($limit_keywords == 'LIMIT '.$val_complete_list_limit_keywords) {
				$limit_keywords = 'LIMIT '.$val_limit_keywords;
			} else {
				$limit_keywords = 'LIMIT '.$val_complete_list_limit_keywords;
			}
		}
		
		// Init : Only if first display
		if(!isset($limit_keywords)) { 
			$limit_keywords = 'LIMIT '.$val_limit_keywords;
		}

		// -------- for all views ---------------------
		if($limit_keywords == 'LIMIT '.$val_complete_list_limit_keywords) {
			$value_button_keywords = MSG_SHORTLIST;	
			$switch_short_complete_list = false;
		} else {
			$value_button_keywords = MSG_COMPLETE_LIST ;
		}
		// --------------------------------------------
		
		if (isset($submit_limit_pages)) {
			if ($limit_pages == " " || $limit_pages == 'LIMIT '.$max_limit_pages_view) {
				$limit_pages = 'LIMIT '.$small_limit_pages_view;
			} else {
				$limit_pages = 'LIMIT '.$max_limit_pages_view;
			}
		}
		
		if(!isset($limit_pages)) { //1ere affichage
			$limit_pages = 'LIMIT '.$small_limit_pages_view;
		}
		

		if($limit_pages == " " || $limit_pages == 'LIMIT '.$max_limit_pages_view) {
			$value_button_pages = MSG_SHORTLIST;	
		} else {
			$value_button_pages = MSG_COMPLETE_LIST ;
		}

?>