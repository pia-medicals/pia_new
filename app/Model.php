<?php 
/**
* 
*/
class Model
{
	
	function __construct()
	{
		# code...
	}

	public function insert($table, $data, $exclude = array()) {
		
	    $fields = $values = array();
	    if( !is_array($exclude) ) $exclude = array($exclude);
	    foreach( array_keys($data) as $key ) {
	        if( !in_array($key, $exclude) ) {
	            $fields[] = "`$key`";
	            $values[] = "'" . mysqli_real_escape_string($this->getInstance(),$data[$key]) . "'";
	        }
	    }
	    $fields = implode(",", $fields);
	    $values = implode(",", $values);
	    if( query("INSERT INTO `$table` ($fields) VALUES ($values)") ) {
	        return array( "mysql_error" => false,
	                      "mysql_insert_id" => mysql_insert_id(),
	                      "mysql_affected_rows" => mysql_affected_rows(),
	                      "mysql_info" => mysql_info()
	                    );
	    } else {
	        return array( "mysql_error" => mysql_error() );
	    }
	}
	public function pagination($current_page,$total_pages_sql,$page_url,$key='')
	{

	$item_per_page = 10;
	$offset = ($current_page-1) * $item_per_page; 
	$result = $this->mysqli->query($total_pages_sql);
	$total_records = mysqli_num_rows($result);
	$total_pages = ceil($total_records / $item_per_page);

	if($key != ''){
		$keys = "&key=".$key;
	} else {
		$keys = "";
	}
	 

	    $pagination = '';	
	        
	    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
	        $pagination .= '<ul class="pagination">';
	        
	        $right_links    = $current_page + 3; 
	        $previous       = $current_page - 3; //previous link 
	        $next           = $current_page + 1; //next link
	        $first_link     = true; //boolean var to decide our first link
	        
	        if($current_page > 1){
	            $previous_link = ($previous==0)?1:$previous;
	            $pagination .= '<li class="first"><a href="'.$page_url.'?page=1'.$keys.'" title="First">&laquo;</a></li>'; //first link


	            //$pagination .= '<li><a href="'.$page_url.'?page='.$previous_link.'" title="Previous">&lt;</a></li>'; //previous link


	                for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
	                    if($i > 0){
	                        $pagination .= '<li><a href="'.$page_url.'?page='.$i.$keys.'">'.$i.'</a></li>';
	                    }
	                }   
	            $first_link = false; //set first link to false
	        }
	        
	        if($first_link){ //if current active page is first link
	            $pagination .= '<li class="first active"><a href="javascript:void(0);">'.$current_page.'</a></li>';
	        }elseif($current_page == $total_pages){ //if it's the last active link
	            $pagination .= '<li class="last active"><a href="javascript:void(0);">'.$current_page.'</a></li>';
	        }else{ //regular current link
	            $pagination .= '<li class="active"><a href="javascript:void(0);">'.$current_page.'</a></li>';
	        }
	                
	        for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
	            if($i<=$total_pages){
	                $pagination .= '<li><a href="'.$page_url.'?page='.$i.$keys.'">'.$i.'</a></li>';
	            }
	        }
	        if($current_page < $total_pages){ 
	                $next_link = ($i > $total_pages)? $total_pages : $i;


	                //$pagination .= '<li><a href="'.$page_url.'?page='.$next_link.'" >&gt;</a></li>'; //next link


	                $pagination .= '<li class="last"><a href="'.$page_url.'?page='.$total_pages.$keys.'" title="Last">&raquo;</a></li>'; //last link
	        }
	        
	        $pagination .= '</ul>'; 
	    }
	    return $pagination; //return pagination links
	}



	
}