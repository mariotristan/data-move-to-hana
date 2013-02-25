<?php
	/**
	 * Copyright 2012 Craig Cmehil.
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may
	 * not use this file except in compliance with the License. You may obtain
	 * a copy of the License at
	 *
	 *     http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software
	 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
	 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
	 * License for the specific language governing permissions and limitations
	 * under the License.
	 * 
	 * Although written by an employee of the SAP(R) AG, this source code is not affliated
	 * nor endorsed by SAP as an official product or support.
	 *
	 */
 	
	/* Constants */
	$debug = false;
	
	/* Parameters */
	$engine = $_POST['engine'];
	$server = $_POST['server'];
	$login = $_POST['login'];
	$password = $_POST['password'];		
	$db = $_POST['db'];
	$schema = $_POST['schema'];
	if($schema == ''){
		$schema = $db;
	}

	include('lib/'.$engine.'.php');
?>

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>MySQL to HANA</title> 
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
	<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
</head>

<div data-role="page" class="type-interior" data-theme="b">

	<div data-role="header" data-theme="b">
		<?php if(!$login){ ?>
		<h1>Database Migration</h1>
		<?php } else { ?>
		<h1>Listing Tables</h1>
		<?php } ?>
	</div><!-- /header -->

	<div data-role="content">
		<div class="ui-body ui-body-b">	
			<form action="#" method="post"> 	
				
				<label for="engine" class="select">Database Engine:</label>
				<select name="engine" id="engine">
				   <option value=""></option>
				   <option value="mysql" selected>mysql</option>
				</select>

				<label for="server">Server:</label>
		    <input type="text" name="server" id="server" value="<?php if($server){ echo $server; } else { echo 'localhost'; } ?>"  />

				<label for="login">User Name:</label>
		    <input type="text" name="login" id="login" value="<?php if($login){ echo $login; } ?>"  />
		
				<label for="password">Password:</label>
		    <input type="password" name="password" id="password" value="<?php if($password){ echo $password; } ?>"  />

		    <?php if($select_list){ ?>
				<label for="schema">Desired SAP HANA Schema:</label>
		    <input type="text" name="schema" id="schema" value="<?php if($schema){ echo $schema; }else{ echo $db; } ?>"  />

				<label for="db" class="select">Database Name:</label>
				<select name="db" id="db">
				   <option value=""></option>
				   <?php echo $select_list; ?>
				</select>
				<?php } ?>

				<button type="submit" data-theme="b" name="submit" value="submit-value" data-inline="true">
					<?php if($select_list){ echo "Access Tables"; } else { echo "Access Databases"; } ?>
				</button>
			</form>	
		</div>

		<div class="ui-body ui-body-e">
			<div data-role="collapsible-set" data-inset="true" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d">

				<?php if($debug){ ?> 
				<div data-role="collapsible" data-collapsed="false">
				<h3>Debug Information</h3>
				<p><?php if($tbl_out != ''){ echo $tbl_out; } ?></p>
				</div>
				<?php } ?>
			
				<?php if($sql_out != ''){ ?>
				
				<div data-role="collapsible" data-collapsed="true">
				<h3>Step 1: SAP HANA generated SQL</h3>
				<p><?php if($sql_out != ''){ echo $sql_out; } ?></p>
				</div>

				<div data-role="collapsible" data-collapsed="true">
				<h3>Step 2: Command Data Export to CSV</h3>
				<p>In order to properly transfer your data, as well as easily now into SAP HANA you will need to switch to the command line 
				of your MySQL server and execute the following command(s). This will generate a CSV formatted file of all your data that can then be 
				imported  into your SAP HANA system via the SAP HANA Studio.<br><br>
				<ul>
				<?php if($csv_out != ''){ echo $csv_out; } ?></p>
				</ul>
				<p>For CSV style files, these exports are stored in /tmp.<br><br>
				<ul>
				<?php if($csv1_out != ''){ echo $csv1_out; } ?></p>
				</ul>
				</div>				

				<div data-role="collapsible" data-collapsed="true">
				<h3>Step 3: Grant Privileges</h3>
				<p>Once you have your new SCHEMA you may need to grant certain privileges in order to create the various views.<br><br>
				<ul>
				<?php echo "GRANT SELECT on SCHEMA ".$schema." to _SYS_REPO WITH GRANT OPTION;"; ?></p> 
				</ul>
				</div>				
				
				<?php } ?>				
			</div>
		</div>
		
	</div><!-- /content -->
	
	<div data-role="footer" data-id="foo1" data-position="fixed" data-theme="b">
		<div data-role="navbar">
		<p>
			<ul>
				<li><div align="center">MySQL to SAP HANA&reg; v0.02 beta</div></li>
			</ul>
		</p>
		</div><!-- /navbar -->
	</div><!-- /footer -->
</div><!-- /page one -->

</html>