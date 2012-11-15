<?php

	/* Begin Database connect if parameters are filled */
	if($login){
		$link = mysql_connect($server, $login, $password);
		if (!$link) {
		    die('Could not connect: ' . mysql_error());
		}
		
		/* Generate dynamic list of databases associated with user */
		$select_list = '';
		$result = mysql_query("SHOW DATABASES");
	  while($row = mysql_fetch_array( $result )){
		  $select_list .= "<option "; 
		  if($db == $row[0]){
			  $select_list .= "selected";
		  }
		  $select_list .= " value='". $row[0] ."'>". $row[0] ."</option>";
		}  
	}
	
	/* If database selected then generate list of tables and SQL output */
	if($db){
		/* empty temporary holding variables */
		$tbl_out = '';
		$sql_out = '';
		$pri_out = '';
		$uni_out = '';
		$csv_out = '';

		/* Begin generating output */		
		$result2 = mysql_query("SHOW TABLES IN $db");
		$sql_out .= 'DROP SCHEMA '.$schema.';<br>';
		$sql_out .= 'CREATE SCHEMA '.$schema.';<br>';
    $sql_out .= 'SET SCHEMA '.$schema.';<br><br>';
		
		$j = 0;
		while($row2 = mysql_fetch_array( $result2 )) {
			$csv_out .= "<li>mysqldump -u ".$login." -p".$password." ".$db." ".$row2[0]." --add-locks=false --add-drop-table=false --comments=false --no-create-db --no-create-info --set-charset=false --extended-insert=false --comments=false | sed 's/\`/\"/g'> ".$db."_".$row2[0].".sql</li>";
		  $tbl_out .= '<br> Table ('.$j++.')="'.$row2[0].'"<br>';
		  $sql_out .= 'DROP TABLE "'.$row2[0].'";<br>';
		  $result3 = mysql_query("SHOW COLUMNS FROM $row2[0] FROM $db ");
		  $k = 0;
		  $tbl_out .= '<table rules="all" frame="box" cellpadding="2">
		        <tr>
		           <td>Descriptions:</td>
		           <td>Field</td>
		           <td>Type</td>
		           <td>Null</td>
		           <td>Key</td>
		           <td>Default</td>
		           <td>Extra</td>
		         </tr>';
		  $sql_out .= 'CREATE COLUMN TABLE "'.$row2[0].'" (<br>';
		  $k = 0;
		  while($row3 = mysql_fetch_array( $result3 )){  
			  if($k > 0){
				  $sql_out .= ',<br>';
			  }
			  $sql_out .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row3[0].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

				/* Check for data types */	
			  if(strpos($row3[1],'int') !== false || strpos($row3[1],'enum') !== false){
				  if(strpos($row3[1],'int') !== false){
				  	$sql_out .= 'integer    ';
				  }
				  if(strpos($row3[1],'enum') !== false){
				  	$sql_out .= 'varchar(10)    ';
				  }
			  }else{
			  	$sql_out .= $row3[1].'    ';
			  }
			  			  
			  /* Check for NULL */
			  if($row3[2] == 'NO'){
					$sql_out .= ' NOT NULL';
			  }
			  
			  if($row3[4] != ''){
			  	$tmp = "'".$row3[4]."'";
					$sql_out .= ' DEFAULT '.$tmp;
			  }

			  if($row3[3] == 'PRI'){
			  	if($pri_out != ''){ 
			  		$pri_out .= ','; 
			  	}
			  	$pri_out .= $row3[0];
			  }
		    
			  if($row3[3] == 'UNI'){
			  	$sql_out .= ' unique';
			  }

		    $tbl_out .= '<tr>
		             <td>  Column ('.$k++.')=</td>
		             <td> '. $row3[0] .'</td>
		             <td> '. $row3[1] .'</td>
		             <td> '. $row3[2] .'</td>
		             <td> '. $row3[3] .'</td>
		             <td> '. $row3[4] .'</td>
		             <td> '. $row3[5] .'</td>
		          </tr>';
		  }
		  if($pri_out != ''){
			  $sql_out .= ',<br>';
			  $sql_out .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PRIMARY KEY ('.$pri_out.')';
		  }
		  $pri_out = '';
		  $uni_out = '';
		  
	    $sql_out .= '<br>);<br><br><br>';
		  $tbl_out .= '</table><br>';
		}
		mysql_close($link);
	}	
?>