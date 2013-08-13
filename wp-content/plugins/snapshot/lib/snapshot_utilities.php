<?php
/**
 * Utility function to grab the array of database tables for the site. This function
 * is multisite aware in that is only grabs tables within the site's table prefix
 * for example if on a multisite install the table prefix is wp_2_ then all other
 * tables 'wp_' and 'wp_x_' will be ignores.
 *
 * The functions builds a multi array. On node of the array [wp]  will be the
 * core WP tables. Another node [non] will be tables within that site which
 * are not core tables. This could be table created by other plugins.
 *
 * @since 1.0.0
 * @see wp_upload_dir()
 *
 * @param none
 * @return array $tables multi-dimensional array of tables.
 */
function snapshot_utility_get_database_tables($blog_id=0, $site_id=1) {

	global $wpdb;

	if ((!$blog_id) || ($blog_id == 0))
		$blog_id = $wpdb->blogid;

	if (is_multisite()) {
		if ( $blog_tables = get_site_transient( 'snapshot-blog-tables-'. $blog_id ) ) {
			return $blog_tables;
		}
	}

	$tables 			= array();
	$tables['wp'] 		= array();
	$tables['non'] 		= array();
	$tables['other'] 	= array();
	$tables['error']	= array();
	$tables_all 		= array();

	$blog_prefixes 			= array();
	$blog_prefixes_lengths 	= array();

	$old_blog_id = $wpdb->blogid;

	if (($blog_id) && ($blog_id != $wpdb->blogid)) {
		$wpdb->set_blog_id($blog_id);
	}

	if ( is_multisite() ) {

		$db_name = snapshot_utility_get_db_name();
		if (!$db_name) $db_name = DB_NAME;
		$wpdb_prefix = str_replace('_', '\_', $wpdb->prefix);

		if ($wpdb->prefix == $wpdb->base_prefix) {
			// Under Multisite and when the prefix and base prefox match we assume this is the primary site.
			// For example the default base prefix is 'wp_' and for the primary site the tables all start as 'wp_???'
			// for secondary site the prefix will be something like 'wp_2_', 'wp_3_'. So on the primary site tables
			// we cannot simply look for all tables starting with 'wp_' because this will include all sites. So
			// we use some MySQL REGEX to exclude matches.
			/*
			$show_all_tables_sql = "SELECT table_name FROM information_schema.tables
									WHERE table_schema = '". $db_name ."'
									AND table_name LIKE '". $wpdb->prefix ."%'
									AND table_name NOT REGEXP '^". $wpdb_prefix ."[[:digit:]]+\_'";
			*/
			$show_all_tables_sql = "SELECT table_name FROM information_schema.tables
									WHERE table_schema = '". $db_name ."'
									AND table_name NOT REGEXP '^". $wpdb_prefix ."[[:digit:]]+\_'";

		} else {

			$show_all_tables_sql = "SELECT table_name FROM information_schema.tables
									WHERE table_schema = '". $db_name ."'
									AND table_name LIKE '". $wpdb_prefix ."%'";
		}
	} else {
		$db_name = DB_NAME;
		$wpdb_prefix = str_replace('_', '\_', $wpdb->prefix);

/*
		$show_all_tables_sql = "SELECT table_name FROM information_schema.tables
								WHERE table_schema = '". $db_name ."'
								AND table_name LIKE '". $wpdb_prefix ."%'";
*/

		$show_all_tables_sql = "SELECT table_name FROM information_schema.tables
								WHERE table_schema = '". $db_name ."'";
	}

	if ((isset($show_all_tables_sql)) && (strlen($show_all_tables_sql))) {
		//echo "show_all_tables_sql=[". $show_all_tables_sql ."]<br />";
		$tables_all_rows = $wpdb->query($show_all_tables_sql);
		if ($tables_all_rows) {
			foreach($wpdb->last_result as $table_set) {
				foreach($table_set as $table_name) {
					$sql_describe = "DESCRIBE `". $table_name ."`; ";
					$table_structure = $wpdb->query($sql_describe);
					if ((!$table_structure) || (count($table_structure) == 0)) {
						continue;
					}
					$tables_all[$table_name] = $table_name;
				}
			}
		}
	}

	if (count($tables_all)) {

		// Get a list of all WordPress known tables for the selected blog_id
		$tables_wp = $wpdb->tables('all');
		//echo "tables_wp<pre>"; print_r($tables_wp); echo "</pre>";

		// The 'non' tables are the difference between the all and wp table sets
		$tables['non'] = array_diff($tables_all, $tables_wp);
		$tables['wp'] = array_intersect($tables_all, $tables_wp);

		foreach($tables['non'] as $_idx => $table) {
			if (substr($table, 0, 3) != "wp_") {
				$tables['other'][$_idx] = $table;
				unset($tables['non'][$_idx]);
			}
		}
		//echo "tables<pre>"; print_r($tables); echo "</pre>";
	}

	// Now for each set set want to strip off the table prefix from the name
	// so when they are displayed they take up less room.

	if (isset($tables['wp']))
	{
		if (count($tables['wp'])) {
			ksort($tables['wp']);
		}
	}

	if (isset($tables['non']))
	{
		if (count($tables['non'])) {
			ksort($tables['non']);
		}
	}

	if (isset($tables['other']))
	{
		if (count($tables['other'])) {
			ksort($tables['other']);
		}
	}

	if ($old_blog_id != $wpdb->blogid) {
		$wpdb->set_blog_id($old_blog_id);
	}

	if (is_multisite()) {
		set_transient('snapshot-blog-tables-'. $blog_id, $tables, 300);
	}
	return $tables;
}


/**
 * Utility function to determine all blogs under a Multisite install
 *
 * @since 1.0.2
 * @see
 *
 * @param none
 * @return array of blog information
 */
function snapshot_utility_get_blogs($show_counts_only = false) {

	global $wpdb;


	if ($show_counts_only == true)
	{
			$sql_str = "SELECT count(blog_id) as blogs_count FROM $wpdb->blogs WHERE archived = '0' AND spam = 0 AND deleted = 0";
			$result = $wpdb->get_row( $sql_str );
			if (isset($result->blogs_count))
				return $result->blogs_count;
			else
				return 0;
	} else {
		$blogs = wp_cache_get('snapshot-blogs', 'snapshot-plugin');
		if ($blogs) {
			return $blogs;
		}

		if ( (is_multisite()) && (is_network_admin()) ) {

			$sql_str = "SELECT blog_id FROM $wpdb->blogs WHERE archived = '0' AND spam = 0 AND deleted = 0";
			//echo "sql_str=[". $sql_str ."]<br />";
			$blog_ids = $wpdb->get_col( $sql_str );
			//echo "blog_ids<pre>"; print_r($blog_ids); echo "</pre>";
			if ($blog_ids) {
				$blogs = array();
				foreach($blog_ids as $blog_id) {
					$blogs[$blog_id] = get_blog_details($blog_id);
				}
				wp_cache_set('snapshot-blogs', $blogs, 'snapshot-plugin');
				return $blogs;
			}
		}
	}
}


/**
 * Utility function to generate an 8 character checksum for a filename. This is to make the filename unique.
 *
 * @since 1.0.2
 * @see
 *
 * @param blog_id
 * @return none
 */
function snapshot_utility_get_file_checksum($file) {

	$checksum = '';
	if (function_exists('sha1_file')) {
		$checksum = sha1_file($file);
	} else {
		$checksum = rand(8, 8);
	}

	if (!$checksum)
		$checksum = "00000000";

	if (($checksum) && (strlen($checksum) > 8)) {
		$checksum = substr($checksum, 0, 8);
	}

	return $checksum;
}


/**
 * Utility function to get the blog site current theme. Works for single and Multisite
 *
 * @since 1.0.0
 * @see
 *
 * @param int $blog_id only used if multisite
 * @return array of allowed themes
 */

function snapshot_utility_get_current_theme($blog_id=0) {

	if (is_multisite()) {

		$current_theme = get_blog_option( $blog_id, 'current_theme' );

	} else {

		$current_theme =  get_option('current_theme');
	}
	return $current_theme;
}


/**
 * Utility function to get a list of allowed/active theme for the site.
 *
 * @since 1.0.0
 * @see
 *
 * @param int $blog_id only used if multisite
 * @return array of allowed themes
 */
function snapshot_utility_get_blog_active_themes($blog_id=0) {

	// Get All themes in the system.
	$themes_all = wp_get_themes();

	/* The get_themes returns an unusable array. So we need to rework it to be able to
	   compare to the array returned from allowedthemes */
	foreach($themes_all as $themes_all_key => $themes_all_set) {
		unset($themes_all[$themes_all_key]);
		$themes_all[$themes_all_set['Stylesheet']] = $themes_all_set['Name'];
	}

	if (is_multisite()) {

		//$allowed_themes = wpmu_get_blog_allowedthemes( $blog_id );
		$allowed_themes = WP_Theme::get_allowed_on_site( $blog_id );

		$themes_blog = get_blog_option( $blog_id, 'allowedthemes' );
		if (!$themes_blog)
			$themes_blog = array();

		//$site_allowed_themes = get_site_allowed_themes();
		$site_allowed_themes = WP_Theme::get_allowed_on_network();
		if (!$site_allowed_themes)
			$site_allowed_themes = array();

		$themes_blog = array_merge($themes_blog, $site_allowed_themes);

		if ((isset($themes_blog)) && (isset($themes_all))) {
			foreach($themes_all as $themes_all_key => $themes_all_name) {
				if (!isset($themes_blog[$themes_all_key]))
					unset($themes_all[$themes_all_key]);
			}
			//echo "themes_all<pre>"; print_r($themes_all); echo "</pre>";
			asort($themes_all);
			return $themes_all;
		}

	} else {
		return $themes_all;
	}
}


/**
 * Utility function to grab the user's name from various options from
 * the user_id. From the code note we try the display_name field. If
 * not set we try the user_nicename. If also not set we simply use the
 * user's login name.
 *
 * @since 1.0.0
 * @see
 *
 * @param int $user_id The user's ID
 * @return string $user_name represents the user's name
 */
function snapshot_utility_get_user_name($user_id) {

	$user_name = get_the_author_meta('display_name', intval($user_id));

	if (!$user_name)
		$user_name = get_the_author_meta('user_nicename', intval($user_id));

	if (!$user_name)
		$user_name = get_the_author_meta('user_login', intval($user_id));

	return $user_name;
}


/**
 * Utility function to check if we can adjust the server PHP timeout
 *
 * @since 1.0.0
 * @see
 *
 * @param none
 * @return true if success, false if not able to adjust timeout.
 */
function snapshot_utility_check_server_timeout() {
	$current_timeout = ini_get('max_execution_time');
	$current_timeout = intval($current_timeout);
	//echo "current_timeout=[". $current_timeout ."]<br />";

	// If the max execution time is zero (means no timeout). We leave it.
	if ($current_timeout === 0) return true;

	// Else we try to set the timeout to some other value. If success we are golden
	@set_time_limit(((int)$current_timeout) * 3);
	$new_timeout = ini_get('max_execution_time');
	$new_timeout = intval($new_timeout);
	//echo "new_timeout=[". $new_timeout ."]<br />";

	if ($new_timeout === ($current_timeout*3))
		return true;

	// Finally, if we cannot adjust the timeout and the current timeout is less than 30 seconds we throw a warning to the user.
	if ($current_timeout < 30)
		return false;
}


/**
 * Utility function to build the display of a timestamp into the date time format.
 *
 * @since 1.0.0
 * @see
 *
 * @param int UNIX timestamp from time()
 * @return none
 */
function snapshot_utility_show_date_time($timestamp, $format='') {

	if (!$format)
		$format = get_option('date_format') .' '. get_option('time_format');

	return date_i18n($format, $timestamp + ( get_option( 'gmt_offset' ) * 3600));
}


/**
 * Utility function recursively scan a folder and build an array of it's contents
 *
 * @since 1.0.2
 * @see
 *
 * @param string $base Base directory to start.
 * @return array $data array of files/folder found
 */
function snapshot_utility_scandir($base='') {
	if ((!$base) || (!strlen($base)))
		return array();

	if (!file_exists($base)) {
		return array();
	}

	$data = array_diff(scandir($base), array('.', '..'));

	$subs = array();
	foreach($data as $key => $value) :
		if ( is_dir($base . '/' . $value) ) :
			unset($data[$key]);
			$subs[] = snapshot_utility_scandir($base . '/' . $value);
		elseif ( is_file($base . '/' . $value) ) :
			$data[$key] = $base . '/' . $value;
		endif;
	endforeach;

	if (count($subs)) {
		foreach ( $subs as $sub ) {
			$data = array_merge($data, $sub);
		}
	}

	return $data;
}


/**
 * Utility function to break up a given table rows into segments based on the Settings size for Segments.
 *
 * Given a database table with 80,000 rows and a segment size of 1000 the returned $table_set will
 * be an array of nodes. Each node will contain information about the stating row and number of
 * segment (itself). Also total rows and total segments for this table.
 *
 * @since 1.0.2
 * @see
 *
 * @param string $table_name The Database full table name
 * @param int $segmentSize The segment size.
 * @return array $table set
 */
function snapshot_utility_get_table_segments($table_name, $segmentSize=1000) {

	global $wpdb;

	$table_set = array();
	$table_set['table_name'] = $table_name;
	$table_set['rows_total'] = 0;
	$table_set['segments'] = array();

	$segment_set = array();

	$table_data = $wpdb->get_row("SELECT count(*) as total_rows FROM `". $table_name ."`; ");
	if ((isset($table_data->total_rows)) && intval($table_data->total_rows)) {

		$last_rows = 0;
		$segment_set['rows_start'] = 0;
		$segment_set['rows_end'] = 0;

		$total_rows = intval($table_data->total_rows);
		$table_set['rows_total'] = $total_rows;

		while($total_rows > 0) {

			if ($total_rows < $segmentSize) {
				$segment_set['rows_start'] 	= intval($last_rows);
				$segment_set['rows_end'] 	= intval($total_rows);
				$table_set['segments'][] 	= $segment_set;

				break;
			}

			$segment_set['rows_start'] 	= intval($last_rows);
			$segment_set['rows_end'] 	= $segmentSize;
			$last_rows = $last_rows+$segmentSize;

			$table_set['segments'][] = $segment_set;

			$total_rows -= $segmentSize;
		}
	}
	return $table_set;
}

/**
 * Similar to the function snapshot_utility_get_table_segments this utility take a single file and will
 * build the segments array needed based on the Settings for segment size. This function is mostly used
 * for pre-1.0.2 archive where the database content for all tables was contained in a single file.
 *
 * @since 1.0.2
 * @see
 *
 * @param string $backupFile Filename to the database input file.
 * @return array $table set
 */

function snapshot_utility_get_table_segments_from_single($backupFile) {

	$table_segments = array();

	if (!file_exists($backupFile))
		return $table_segments;

	$backupFolderFull = dirname($backupFile);

	$fp = fopen($backupFile, 'r');
	if ($fp) {

		$table_buffer = array();
		$table_name = "";
		$table_line_count = 0;

	 	while (($line = fgets($fp, 4096)) !== false) {

			$line = trim($line);
			if (!strlen($line))
				continue;

			if ($line[0] == "#")
				continue;

	        if (substr($line, 0, strlen('TRUNCATE')) == "TRUNCATE") {

				if (isset($out_fp)) {

					fflush($out_fp);

					if (isset($table_segments_item)) {
						$table_segments_item['ftell_after'] = ftell($out_fp);

						$table_segments_item['rows_end'] 	= $table_line_count;
						$table_segments_item['rows_total']	= $table_line_count;

						$table_segments[] = $table_segments_item;
					}
					$table_line_count = 0;

					fclose($out_fp);
				}

				$table_names = explode('`', $line);
				if (isset($table_names[1])) {
					$table_name = trim($table_names[1]);

					$outFile = trailingslashit($backupFolderFull) . $table_name . ".sql";
					$out_fp = fopen($outFile, 'w+');

					if (isset($table_segments_item))
						unset($table_segments_item);

					$table_segments_item = array();
					$table_segments_item['ftell_after'] = 0;
					$table_segments_item['ftell_before'] = 0;
					$table_segments_item['rows_end'] = 0;
					$table_segments_item['rows_start'] = 0;
					$table_segments_item['rows_total'] = 0;
					$table_segments_item['segment_idx'] = 0;
					$table_segments_item['segment_total'] = 0;
					$table_segments_item['table_name'] = $table_name;
				}

			} else {
				$table_line_count += 1;
			}
			fwrite($out_fp, $line ."\r\n");
	    }

		if (isset($out_fp)) {

			fflush($out_fp);

			if (isset($table_segments_item)) {
				$table_segments_item['ftell_after'] = ftell($out_fp);

				$table_segments_item['rows_end'] 	= $table_line_count;
				$table_segments_item['rows_total']	= $table_line_count;

				$table_segments[] = $table_segments_item;
			}
			$table_line_count = 0;

			fclose($out_fp);
		}

	    fclose($fp);
	}
	return $table_segments;
}


/**
 * Utility function to add some custom schedule intervals to the default WordPress schedules from
 *
 * @since 1.0.2
 * @see
 *
 * @param array $schedules Passed in by WordPress. The current array of schedules
 * @return array $schedules And updated list containing our custom items.
 */
function snapshot_utility_add_cron_schedules( $schedules ) {

	if (!isset($schedules['snapshot-5minutes'])) {
	    $schedules['snapshot-5minutes'] = array(
	        'interval' => 60*5,
	        'display'  => __( 'Every 5 Minutes', SNAPSHOT_I18N_DOMAIN ),
	    );
	}

	if (!isset($schedules['snapshot-15minutes'])) {
	    $schedules['snapshot-15minutes'] = array(
	        'interval' => 60*15,
	        'display'  => __( 'Every 15 Minutes', SNAPSHOT_I18N_DOMAIN ),
	    );
	}

	if (!isset($schedules['snapshot-30minutes'])) {
	    $schedules['snapshot-30minutes'] = array(
	        'interval' => 60*30,
	        'display'  => __( 'Every 30 Minutes', SNAPSHOT_I18N_DOMAIN ),
	    );
	}

	if (!isset($schedules['snapshot-hourly'])) {
	    $schedules['snapshot-hourly'] = array(
	        'interval' => 60*60,
	        'display'  => __( 'Once Hourly', SNAPSHOT_I18N_DOMAIN ),
	    );
	}

	if (!isset($schedules['snapshot-daily'])) {
	    $schedules['snapshot-daily'] = array(
	        'interval' => 1*24*60*60, 				//	86,400
	        'display'  => __( 'Once Daily', SNAPSHOT_I18N_DOMAIN ),
	    );
	}

	if (!isset($schedules['snapshot-twicedaily'])) {
	    $schedules['snapshot-twicedaily'] = array(
	        'interval' => 1*12*60*60, 				// 43,200
	        'display'  => __( 'Twice Daily', SNAPSHOT_I18N_DOMAIN ),
	    );
	}

	if (!isset($schedules['snapshot-weekly'])) {
	    $schedules['snapshot-weekly'] = array(
	        'interval' => 7*24*60*60, 				// 604,800
	        'display'  => __( 'Once Weekly', SNAPSHOT_I18N_DOMAIN ),
	    );
	}

	if (!isset($schedules['snapshot-twiceweekly'])) {
	    $schedules['snapshot-twiceweekly'] = array(
	        'interval' => 7*12*60*60, 				// 302,400
	        'display'  => __( 'Twice Weekly', SNAPSHOT_I18N_DOMAIN ),
	    );
	}

	if (!isset($schedules['snapshot-monthly'])) {
	    $schedules['snapshot-monthly'] = array(
	        'interval' => 30*24*60*60, 				// 2,592,000
	        'display'  => __( 'Once Monthly', SNAPSHOT_I18N_DOMAIN ),
	    );
	}

	if (!isset($schedules['snapshot-twicemonthly'])) {
	    $schedules['snapshot-twicemonthly'] = array(
	        'interval' => 15*24*60*60, 				// 1,296,000
	        'display'  => __( 'Twice Monthly', SNAPSHOT_I18N_DOMAIN ),
	    );
	}
    return $schedules;
}

/**
 * Utility function to get the pretty display text for a WordPress schedule interval
 *
 * @since 1.0.2
 * @see
 *
 * @param string $sched_key Key to item in wp_get_schedules array
 * @return string Display text for the scheduled item. If found.
 */
function snapshot_utility_get_sched_display($sched_key) {

	$scheds = (array) wp_get_schedules();

	if (isset($scheds[$sched_key]))
		return $scheds[$sched_key]['display'];

}

function snapshot_utility_get_sched_interval($sched_key) {

	$scheds = (array) wp_get_schedules();

	if (isset($scheds[$sched_key]))
		return $scheds[$sched_key]['interval'];

}

function snapshot_utility_calculate_interval_offset_time($interval='', $interval_offset='') {

	if ((empty($interval)) || (empty($interval_offset))) {
		return 0;
	}

	$current_timestamp = time() + ( get_option( 'gmt_offset' ) * 3600 );
	$current_localtime = localtime( $current_timestamp, true );

	$diff_timestamp = $current_timestamp;

	$_offset_seconds = 0;

	if ( ($interval == "snapshot-hourly") && (isset($interval_offset['snapshot-hourly'])) ) {
		$_offset = $interval_offset['snapshot-hourly'];

	} else if ((($interval == "snapshot-daily") || ($interval == "snapshot-twicedaily")) && (isset($interval_offset['snapshot-daily']))) {
		$_offset = $interval_offset['snapshot-daily'];
	} else if ((($interval == "snapshot-weekly") || ($interval == "snapshot-twiceweekly")) && (isset($interval_offset['snapshot-weekly']))) {
		$_offset = $interval_offset['snapshot-weekly'];
	} else if ((($interval == "snapshot-monthly") || ($interval == "snapshot-twicemonthly")) && (isset($interval_offset['snapshot-monthly']))) {
		$_offset = $interval_offset['snapshot-monthly'];
	} else {
		return $_offset_seconds;
	}

	//echo "offset<pre>"; print_r($_offset); echo "</pre>";

	if (isset($_offset['tm_min'])) {

		$_tm_min = intval($_offset['tm_min']) - $current_localtime['tm_min'];
		//echo "_tm_min=[". $_tm_min ."]<br />";
		if ($_tm_min > 0) {
			$_offset_seconds += intval($_tm_min)*60;
		} else if ($_tm_min < 0) {
			$_offset_seconds -= abs($_tm_min)*60;
		}
	}

	if (isset($_offset['tm_hour'])) {

		$_tm_hour = intval($_offset['tm_hour']) - $current_localtime['tm_hour'];
		//echo "_tm_hour=[". $_tm_hour ."]<br />";

		if ($_tm_hour > 0) {
			$_offset_seconds += intval($_tm_hour)*60*60;
		} else if ($_tm_hour < 0) {
			$_offset_seconds -= abs($_tm_hour )*60*60;
		}
	}

	if (isset($_offset['tm_wday'])) {

		$_tm_wday = intval($_offset['tm_wday']) - $current_localtime['tm_wday'];
		//echo "_tm_wday=[". $_tm_wday ."]<br />";

		if ($_tm_wday > 0) {
			$_offset_seconds += intval($_tm_wday)*24*60*60;
		} else if ($_tm_wday < 0) {
			$_offset_seconds -= abs($_tm_wday)*24*60*60;
		}
	}

	if (isset($_offset['tm_mday'])) {

		$_tm_mday = intval($_offset['tm_mday']) - $current_localtime['tm_mday'];
		//echo "_tm_mday=[". $_tm_mday ."]<br />";

		if ($_tm_mday > 0) {
			$_offset_seconds += intval($_tm_mday)*24*60*60;
		} else if ($_tm_mday < 0) {
			$_offset_seconds -= abs($_tm_mday)*24*60*60;
		}
	}

	if ($_offset_seconds < 0) {

		$_sched_interval = snapshot_utility_get_sched_interval($interval);
		$_offset_seconds += $_sched_interval;
	}

	//echo "next data: ". date('Y-m-d h:i', $current_timestamp + $_offset_seconds) ."<br />";
	//return $current_timestamp + $_offset_seconds;
	return $_offset_seconds;
}

function snapshot_utility_form_show_minute_selector_options($minute_value = 0) {
	$_minute = 0;

	while($_minute < 60) {
		?><option value="<?php echo $_minute ?>" <?php
		if ($_minute == $minute_value) { echo ' selected="selected" '; } ?>><?php
		echo sprintf("%02d", $_minute) ?></option><?php
		$_minute += 1;
	}
}

function snapshot_utility_form_show_hour_selector_options($hour_value = 0) {

	$_hour = 0;

	while($_hour < 24) {

		if ($_hour == 0)
			$_hour_label = __("Midnight", SNAPSHOT_I18N_DOMAIN);
		else if ($_hour == 12)
			$_hour_label = __("Noon", SNAPSHOT_I18N_DOMAIN);
		else if ($_hour < 13)
			$_hour_label = $_hour. __("am", SNAPSHOT_I18N_DOMAIN);
		else
			$_hour_label = ($_hour - 12). __("pm", SNAPSHOT_I18N_DOMAIN);

		?><option value="<?php echo $_hour ?>" <?php
			if ($_hour == $hour_value) { echo ' selected="selected" ';}
			?>><?php echo $_hour_label; ?></option><?php
		$_hour += 1;
	}
}

function snapshot_utility_form_show_mday_selector_options($mday_value = 0) {

	$_dom = 1;

	while($_dom < 32) {
		?><option value="<?php echo $_dom ?>" <?php
		if ($_dom == $mday_value) { echo ' selected="selected" '; }
		?>><?php echo $_dom ?></option><?php
		$_dom += 1;
	}
}


function snapshot_utility_form_show_wday_selector_options($wday_value = 0) {

	$_dow = array(
		'0'	=>	__('Sunday', SNAPSHOT_I18N_DOMAIN),
		'1'	=>	__('Monday', SNAPSHOT_I18N_DOMAIN),
		'2'	=>	__('Tuesday', SNAPSHOT_I18N_DOMAIN),
		'3'	=>	__('Wednesday', SNAPSHOT_I18N_DOMAIN),
		'4'	=>	__('Thursday', SNAPSHOT_I18N_DOMAIN),
		'5'	=>	__('Friday', SNAPSHOT_I18N_DOMAIN),
		'6'	=>	__('Saturday', SNAPSHOT_I18N_DOMAIN),
	);

	foreach($_dow as $_key => $_label) {
		?><option value="<?php echo $_key ?>"<?php
		if ($_key == $wday_value) { echo ' selected="selected" '; }
		?>><?php echo $_label ?></option><?php
	}
}

/**
 * Utility function to display the AJAX information elements above the
 * Add New and Restore forms.
 *
 * @since 1.0.2
 * @see
 *
 * @param none
 * @return none
 */

function snapshot_utility_form_ajax_panels() {

	?>
	<div id="snapshot-ajax-warning" class="updated fade" style="display:none"></div>
	<div id="snapshot-ajax-error" class="error snapshot-error" style="display:none"></div>
	<div id="snapshot-progress-bar-container" style="display: none" class="hide-if-no-js"></div>
	<?php
}


/**
 * Utility function to parse the snapshot entry backup log file into an array. The array break points
 * are based on the string '-------------' which divides the different backup attempts.
 *
 * @since 1.0.2
 * @see
 *
 * @param none
 * @return none
 */
function snapshot_utility_get_archive_log_entries($backupLogFileFull) {

	if (file_exists($backupLogFileFull)) {

		$log_content = file($backupLogFileFull);
		if ($log_content) {

			$log_entries = array();
			$log_content_tmp = array();
			foreach($log_content as $log_content_line) {

				$log_content_line = trim($log_content_line);
				if (!strlen($log_content_line)) continue;
				if (strstr($log_content_line, "----------") !== false) {
					$log_entries[$snapshot_filename] = $log_content_tmp;
					unset($log_content_tmp);
					$log_content_tmp = array();
					continue;
				}

				$log_content_tmp[] = $log_content_line;

				if (strstr($log_content_line, "finish:") !== false) {
					$pos_col = strrpos($log_content_line, ':');
					$snapshot_filename = substr($log_content_line, $pos_col+1);
					$snapshot_filename = trim($snapshot_filename);
				}
			}
			//echo "log_entries<pre>"; print_r($log_entries); echo "</pre>";
			if (count($log_entries))
				return $log_entries;
		}
	}
}

/**
 * Utility function to recursively remove directories.
 *
 * @since 1.0.3
 * @see
 *
 * @param none
 * @return none
 */
function snapshot_utility_recursive_rmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);

		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir")
					snapshot_utility_recursive_rmdir($dir."/".$object);
				else unlink($dir."/".$object);
			}
		}
     	reset($objects);
		rmdir($dir);
	}
}

/**
 * Utility function to access the latest item's data set.
 *
 * @since 1.0.4
 * @see
 *
 * @param none
 * @return none
 */
function snapshot_utility_latest_data_item($data_items) {
	krsort($data_items);
	foreach($data_items as $data_key => $data_item) {
		//echo "data_item<pre>"; print_r($data_item); echo "</pre>";
		return $data_item;
	}
}

/**
 * Utility function Add index.php and .htaccess files to archive folders
 *
 * @since 1.0.5
 * @see
 *
 * @param string $folder Destination folder to apply security files to
 * @return none
 */
function snapshot_utility_secure_folder($folder) {

	if (!file_exists(trailingslashit($folder) ."index.php")) {
		$fp = fopen(trailingslashit($folder) ."index.php", "w+");
		if ($fp) {
			fwrite($fp, "<?php // Silence is golden. ?>");
			fclose($fp);
		}
	}
	if (!file_exists(trailingslashit($folder) .".htaccess")) {
		$fp = fopen(trailingslashit($folder) .".htaccess", "w+");
		if ($fp) {
			fwrite($fp, "IndexIgnore *\r\n");
			fclose($fp);
		}
	}

	if (!file_exists(trailingslashit($folder) ."CACHEDIR.TAG")) {
		$fp = fopen(trailingslashit($folder) ."CACHEDIR.TAG", "w+");
		if ($fp) {
			//fwrite($fp, "This file exclide IndexIgnore *\r\n");
			fclose($fp);
		}
	}

}

function snapshot_utility_get_blog_upload_path($blog_id=0) {

	if (is_multisite()) {
		//error_log("snapshot ". __FUNCTION__ .": blog_id=[". $blog_id ."]");

		switch_to_blog($blog_id);

		$uploads = wp_upload_dir();
		if (isset($uploads['basedir'])) {
			$upload_path = str_replace(ABSPATH, '', $uploads['basedir']);
		}
		restore_current_blog();

	} else {
		$uploads = wp_upload_dir();
		if (isset($uploads['basedir'])) {
			$upload_path = str_replace(ABSPATH, '', $uploads['basedir']);
		}
	}

	if (!$upload_path) {
		$upload_path = trailingslashit(WP_CONTENT_DIR) . "uploads";
		$upload_path = str_replace(ABSPATH, '', $uploads['basedir']);
	}

	return $upload_path;
}

function snapshot_utility_get_tables_sections_display($item) {

	$tables_sections_out = array();
	$tables_sections_out['click'] 	= '';
	$tables_sections_out['hidden']	= '';

	//echo "item<pre>"; print_r($item); echo "</pre>";

	if (isset($item['tables-option'])) {
		$tables_sections_out['click'] .= __('Tables:', SNAPSHOT_I18N_DOMAIN) ." (". $item['tables-option'] .")";

		if (isset($item['tables-sections'])) {

			foreach($item['tables-sections'] as $section_key => $section_tables) {

				if ($section_key == "wp")
					$section_label = __('core', SNAPSHOT_I18N_DOMAIN);
				else if ($section_key == "non")
					$section_label = __('non-core', SNAPSHOT_I18N_DOMAIN);
				else if ($section_key == "other")
					$section_label = __('other', SNAPSHOT_I18N_DOMAIN);
				else if ($section_key == "error")
					$section_label = __('error', SNAPSHOT_I18N_DOMAIN);

				if (count($section_tables)) {
					if (strlen($tables_sections_out['click']))
						$tables_sections_out['click'] .= ", ";
					$tables_sections_out['click'] .= '<a class="snapshot-list-table-'. $section_key.'-show" href="#">'. sprintf('%d %s',
						count($section_tables), $section_label) .'</a>';

					$tables_sections_out['hidden'] .= '<p style="display: none" class="snapshot-list-table-'. $section_key.'-container">'.
							implode(', ', $section_tables) .'</p>';
				}
			}
		}
	}
	return $tables_sections_out;
}


function snapshot_utility_get_files_sections_display($data_item) {

	$files_sections_out = array();
	$files_sections_out['click'] 	= '';
	$files_sections_out['hidden']	= '';

	if (isset($data_item['files-option'])) {
		$files_sections_out['click'] .= __('Files:', SNAPSHOT_I18N_DOMAIN) .' (';
		$files_sections_out['click'] .= $data_item['files-option'] .") ";

		//if ((isset($data_item['files-count'])) && (intval($data_item['files-count']))) {
		//	$files_sections_out['click'] .= ' '. $data_item['files-count'] .' ';
		//}

		if (isset($data_item['files-sections'])) {
			$sections_str = '';
			foreach($data_item['files-sections'] as $section) {
				if (strlen($sections_str)) $sections_str .= ", ";
				$sections_str .= ucwords($section);
			}
			$files_sections_out['click'] .= $sections_str;
		}
	}
	return $files_sections_out;
}

// Read a file and display its content chunk by chunk
function snapshot_utility_file_output_stream_chunked($filename, $retbytes = TRUE) {

	$CHUNK_SIZE = 1024*1024; // Size (in bytes) of tiles chunk

	$buffer = '';
	$cnt =0;

	// $handle = fopen($filename, 'rb');
    $handle = fopen($filename, 'rb');
    if ($handle === false) {
		return false;
	}

	while (!feof($handle)) {
		$buffer = fread($handle, $CHUNK_SIZE);
		echo $buffer;
		flush();
		if ($retbytes) {
			$cnt += strlen($buffer);
		}
	}

	$status = fclose($handle);
	if ($retbytes && $status) {
		return $cnt; // return num. bytes delivered like readfile() does.
	}
	return $status;
}

function snapshot_utility_clean_folder($someFolder) {

	$someFolder = trailingslashit($someFolder);

	// Cleanup any files from a previous restore attempt
	if ($dh = opendir($someFolder)) {
		while (($file = readdir($dh)) !== false) {
			if (($file == '.') || ($file == '..'))
			continue;

			snapshot_utility_recursive_rmdir($someFolder . $file);
		}
		closedir($dh);
	}
}

function snapshot_utility_create_archive_manifest($manifest_array, $manifestFile) {
	if (!$manifest_array) return false;

	$fp = @fopen($manifestFile, 'w+');
	if ($fp) {
		foreach($manifest_array as $token => $token_data) {
			fwrite($fp, $token .":". maybe_serialize($token_data) . "\r\n");
		}
		fclose($fp);
		return true;
	}
}

function snapshot_utility_consume_archive_manifest($manifestFile) {

	$snapshot_manifest = array();
	$manifest_array = file($manifestFile);
	if ($manifest_array) {

		foreach($manifest_array as $file_line) {
			list($key, $value) = explode(':', $file_line, 2);
			$key = trim($key);

			if ($key == "TABLES") {
				if (is_serialized($value)) {
					$value = maybe_unserialize($value);
				} else {
					$table_values = explode(',', $value);

					foreach($table_values as $idx => $table_name) {
						$table_values[$idx] = trim($table_name);
					}

					$value = $table_values;
				}
			} else if (($key == "TABLES-DATA") || ($key == "ITEM") || ($key == "FILES-DATA")) {
				$value = maybe_unserialize($value);
			} else {
				$value = trim($value);
			}

			$snapshot_manifest[$key] = $value;
		}
		//echo "snapshot_manifest<pre>"; print_r($snapshot_manifest); echo "</pre>";

		if (isset($snapshot_manifest['VERSION'])) {
			if (($snapshot_manifest['VERSION'] == "1.0") && (!isset($snapshot_manifest['TABLES-DATA']))) {

				$backupFile = trailingslashit($sessionRestoreFolder) . 'snapshot_backups.sql';
				$table_segments = snapshot_utility_get_table_segments_from_single($backupFile);

				if ($table_segments) {
					$snapshot_manifest['TABLES-DATA'] = $table_segments;
					unlink($backupFile);
				}
			}
		}
		return $snapshot_manifest;
	}
}

function snapshot_utility_archives_import_proc($restoreFile, $restoreFolder) {
	global $wpmudev_snapshot;

	$wpmudev_snapshot->load_config();
	$wpmudev_snapshot->set_backup_folder();
	$wpmudev_snapshot->set_log_folders();

	$CONFIG_CHANGED = false;

//	echo "restoreFile=[". $restoreFile ."]<br />";
//	echo "restoreFolder=[". $restoreFolder ."]<br />";
//	echo "before items<pre>"; print_r($wpmudev_snapshot->config_data['items']); echo "</pre>";

	$error_status = array();
	$error_status['errorStatus'] 	= false;
	$error_status['errorText'] 		= "";
	$error_status['responseText'] 	= "";

	if (!defined('PCLZIP_TEMPORARY_DIR'))
		define('PCLZIP_TEMPORARY_DIR', trailingslashit($wpmudev_snapshot->snapshot_get_setting('backupBackupFolderFull')) . "_imports");
	if (!class_exists('class PclZip'))
		require_once(ABSPATH . '/wp-admin/includes/class-pclzip.php');

	$zip = new PclZip($restoreFile);
	$zip_contents = $zip->listContent();
	if (($zip_contents) && (!empty($zip_contents))) {
		//snapshot_utility_clean_folder($restoreFolder);
		snapshot_utility_recursive_rmdir($restoreFolder);
		$extract_files = $zip->extract($restoreFolder);
		if ($extract_files) {

			$snapshot_manifest = trailingslashit($restoreFolder) . 'snapshot_manifest.txt';
			if (file_exists($snapshot_manifest)) {

				$manifest_data = snapshot_utility_consume_archive_manifest($snapshot_manifest);
				if ( empty($manifest_data) ) {
					$error_status['errorStatus'] 	= true;
					$error_status['errorText'] 		= __("Manifest data not found in archive.", SNAPSHOT_I18N_DOMAIN);

					return $error_status;
				}

				if ( (!isset($manifest_data['ITEM'])) || (empty($manifest_data['ITEM'])) ) {
					$error_status['errorStatus'] 	= true;
					$error_status['errorText'] 		= __("Manifest data does not contain ITEM section.", SNAPSHOT_I18N_DOMAIN);

					return $error_status;
				}
				$item = $manifest_data['ITEM'];

				if ( (!isset($item['timestamp'])) || (empty($item['timestamp'])) ) {
					$error_status['errorStatus'] 	= true;
					$error_status['errorText'] 		= __("Manifest ITEM does not contain 'timestamp' item.", SNAPSHOT_I18N_DOMAIN);

					return $error_status;
				}
				$item_key = $item['timestamp'];

				if ( (!isset($item['data'])) || (!count($item['data'])) ) {
					$error_status['errorStatus'] 	= true;
					$error_status['errorText'] 		= __("Manifest ITEM does not contain 'data' section.", SNAPSHOT_I18N_DOMAIN);

					return $error_status;
				}

				// Now we check the manifest item against the config data.
				foreach($item['data'] as $data_item_key => $data_item) {

					if ((!isset($data_item['filename'])) || (empty($data_item['filename']))) {
						$item['data'][$data_item_key]['filename'] = basename($restoreFile);
					}

					if ((!isset($data_item['file_size'])) || (empty($data_item['file_size']))) {
						$item['data'][$data_item_key]['file_size'] = filesize($restoreFile);
					}
				}

				if (!isset($wpmudev_snapshot->config_data['items'][$item_key])) {
					$wpmudev_snapshot->config_data['items'][$item_key] = $item;
					$CONFIG_CHANGED = true;

				} else {
					foreach($item['data'] as $data_item_key => $data_item) {

						if (!isset($wpmudev_snapshot->config_data['items'][$item_key]['data'][$data_item_key])) {
							$wpmudev_snapshot->config_data['items'][$item_key]['data'][$data_item_key] = $data_item;
							$CONFIG_CHANGED = true;
						} else {
							$error_status['responseText'] 	= __("already present. not importing.", SNAPSHOT_I18N_DOMAIN);
						}
					}
				}
			}
		}
	}

	if ($CONFIG_CHANGED == true) {
		$wpmudev_snapshot->save_config();
	}
	return $error_status;
}

function snapshot_utility_destination_select_options_groups($all_destinations, $selected_destination = '', $destinationClasses='') {
	if ((isset($all_destinations)) && (count($all_destinations))) {

		$destinations = array();
		foreach($all_destinations as $key => $destination) {
			$destination['key'] = $key;

			$type = $destination['type'];
			if (!isset($destinations[$type]))
				$destinations[$type] = array();

			$name = $destination['name'];

			$destinations[$type][$name] = $destination;
		}

//		echo "destinations<pre>"; print_r($destinations); echo "</pre>";
		foreach($destinations as $type => $destination_items) {
			if (isset($destinationClasses[$type])) {
				$destinationClass = $destinationClasses[$type];
				$type_name = $destinationClass->name_display;
			} else {
				$type_name = $type;
			}
			?><optgroup label="<?php echo $type_name ?>"><?php
			foreach($destination_items as $key => $destination) {
				?><option value="<?php echo $destination['key']; ?>" <?php
 				if ($selected_destination == $destination['key'] ) { echo ' selected="selected" '; }
				?>><?php echo stripslashes($destination['name']); ?></option><?php
			}
			?></optgroup><?php
		}
	}
}


/**
* Convert bytes to human readable format
 *
 * @since 2.0.3
 * @param integer bytes Size in bytes to convert
 * @return string
 */
function snapshot_utility_size_format($bytes = 0, $precision = 2)
{
	$kilobyte = 1024;
	$megabyte = $kilobyte * 1024;
	$gigabyte = $megabyte * 1024;
	$terabyte = $gigabyte * 1024;

	if (($bytes >= 0) && ($bytes < $kilobyte)) {
		return $bytes . 'b';

	} elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
		return round($bytes / $kilobyte, $precision) . 'kb';

	} elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
		return round($bytes / $megabyte, $precision) . 'M';

	} elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
		return round($bytes / $gigabyte, $precision) . 'G';

	} elseif ($bytes >= $terabyte) {
		return round($bytes / $terabyte, $precision) . 'T';
	} else {
		return $bytes . 'b';
	}
}

function snapshot_utility_get_db_name() {

	global $wpdb;

	$db_class = get_class($wpdb);

	if ( $db_class ===  "m_wpdb") {

		$test_sql = "SELECT ID FROM ". $wpdb->prefix ."posts LIMIT 1";
		$query_data = $wpdb->analyze_query( $test_sql );
		if (isset($query_data['dataset'])) {

			global $db_servers;
			if (isset($db_servers[$query_data['dataset']][0]['name'])) {
				return $db_servers[$query_data['dataset']][0]['name'];
			}
		}
	} else {
		return DB_NAME;
	}
}

function snapshot_utility_set_error_reporting($errorReporting = null) {
	if (isset($errorReporting)) {
		$error_reporting_str = '';

		foreach($errorReporting as $er_key => $er_set) {

			if (isset($er_set['stop'])) {
				$error_reporting_str = $error_reporting_str | $er_key;
			}
		}
		$after_error_reporting = error_reporting($error_reporting_str);
	} else {
		error_reporting(0);
	}
}

function snapshot_utility_show_panel_messages() {

	global $wpmudev_snapshot;
	if (!isset($wpmudev_snapshot)) return;

	$session_save_path = session_save_path();
	//echo "session_save_path=[". $session_save_path ."]<br />";
	if (!file_exists($session_save_path)) {
		$wpmudev_snapshot->snapshot_admin_notices_proc("error", sprintf(__("<p>The session save path (%s) is not set to a valid directory. Check your PHP (php.ini) settings or contact your hosting provider.</p>", SNAPSHOT_I18N_DOMAIN), $session_save_path));

	} else if (!is_writable($session_save_path)) {
		$wpmudev_snapshot->snapshot_admin_notices_proc("error", sprintf(__("<p>The session_save_path (%s) is not writeable. Check your PHP (php.ini) settings or contact your hosting provider.</p>", SNAPSHOT_I18N_DOMAIN), $session_save_path));
	}
}

function snapshot_utility_item_get_lock_info($item_key='') {
	global $wpmudev_snapshot;
	if (!isset($wpmudev_snapshot)) return;
	if (!$item_key) return;

	$lock_info = array();
	$lock_info['locked'] = false;
	$lock_info['file'] = trailingslashit($wpmudev_snapshot->snapshot_get_setting('backupLockFolderFull')) . $item_key .".lock";
	if (file_exists($lock_info['file'])) {
		$lock_fp = fopen($lock_info['file'], 'r');
		if ($lock_fp) {

			// Try to obtain exclusive lock to prevent multiple processes.
			if (!flock($lock_fp, LOCK_EX | LOCK_NB)) {
				$lock_info['locked'] = true;
				flock($lock_fp, LOCK_UN);
			}
			$lock_info = fgets($lock_fp, 4096);
			if ($lock_info) {
				$lock_info = maybe_unserialize($lock_info);
			}
			fclose($lock_fp);
		}
	}
	return $lock_info;
}

function snapshot_utility_current_user_can( $cap ) {
	if (is_multisite()) {
		if (is_network_admin())
			return true;

	} else {
		return current_user_can( $cap );
	}
}

function snapshot_utility_data_item_file_processed_count($data_item) {
	if (!isset($data_item['destination-status'])) return 0;

	$_count = 0;
	foreach($data_item['destination-status'] as $_status) {
		if (isset($_status['syncFilesTotal']))
			$_count += intval($_status['syncFilesTotal']);
	}
	return intval($_count);
}

function ZipArchiveStatusString( $status )
{
    switch( (int) $status )
    {
        case ZipArchive::ER_OK           : return 'N No error';
        case ZipArchive::ER_MULTIDISK    : return 'N Multi-disk zip archives not supported';
        case ZipArchive::ER_RENAME       : return 'S Renaming temporary file failed';
        case ZipArchive::ER_CLOSE        : return 'S Closing zip archive failed';
        case ZipArchive::ER_SEEK         : return 'S Seek error';
        case ZipArchive::ER_READ         : return 'S Read error';
        case ZipArchive::ER_WRITE        : return 'S Write error';
        case ZipArchive::ER_CRC          : return 'N CRC error';
        case ZipArchive::ER_ZIPCLOSED    : return 'N Containing zip archive was closed';
        case ZipArchive::ER_NOENT        : return 'N No such file';
        case ZipArchive::ER_EXISTS       : return 'N File already exists';
        case ZipArchive::ER_OPEN         : return 'S Can\'t open file';
        case ZipArchive::ER_TMPOPEN      : return 'S Failure to create temporary file';
        case ZipArchive::ER_ZLIB         : return 'Z Zlib error';
        case ZipArchive::ER_MEMORY       : return 'N Malloc failure';
        case ZipArchive::ER_CHANGED      : return 'N Entry has been changed';
        case ZipArchive::ER_COMPNOTSUPP  : return 'N Compression method not supported';
        case ZipArchive::ER_EOF          : return 'N Premature EOF';
        case ZipArchive::ER_INVAL        : return 'N Invalid argument';
        case ZipArchive::ER_NOZIP        : return 'N Not a zip archive';
        case ZipArchive::ER_INTERNAL     : return 'N Internal error';
        case ZipArchive::ER_INCONS       : return 'N Zip archive inconsistent';
        case ZipArchive::ER_REMOVE       : return 'S Can\'t remove file';
        case ZipArchive::ER_DELETED      : return 'N Entry has been deleted';

        default: return sprintf('Unknown status %s', $status );
    }
}