<?php
/**
 * @desc db class
 */
class DB {
    /**
     * global variables
     */
    public $dbhost = 'localhost';            // default database host
    public $dblogin;                         // database login name
    public $dbpass;                          // database login password
    public $dbname;                          // database name
    public $port = '3306';
    public $dblink;                          // database link identifier
    public $queryid;                         // database query identifier
    public $error = array();                 // storage for error messages
    public $record = array();                // database query record identifier
    public $totalrecords;                    // the total number of records received from a select statement
    public $last_insert_id;                  // last incremented value of the primary key
    public $previd = 0;                      // previus record id. [for navigating through the db]
    public $transactions_capable = false;    // does the server support transactions?
    public $begin_work = false;              // sentinel to keep track of active transactions

    /**
     * get and set type methods for retrieving properties.
     */

    public function get_dbhost() {
        return $this->dbhost;

    } // end function

    public function get_dblogin(){
        return $this->dblogin;

    } // end function

    public function get_dbpass() {
        return $this->dbpass;

    } // end function

    public function get_dbname(){
        return $this->dbname;

    } // end function
    
    public function get_port(){
    	return $this->port;
    }

    public function set_dbhost($value){
        return $this->dbhost = $value;

    } // end function

    public function set_dblogin($value) {
        return $this->dblogin = $value;

    } // end function

    public function set_dbpass($value){
        return $this->dbpass = $value;

    } // end function

    public function set_dbname($value){
        return $this->dbname = $value;

    } // end function
    
    public function set_port($value){
    	return $this->port = $value;
    }

    public function get_errors(){
        return $this->error;

    } // end function

    /**
     * End of the Get and Set methods
     */

    /**
     * Constructor
     *
     * @param      String $dblogin, String $dbpass, String $dbname
     * @return     void
     * @access     public
     */
    public function __construct($dblogin, $dbpass, $dbname, $dbhost = null, $port = null) {
        $this->set_dblogin($dblogin);
        $this->set_dbpass($dbpass);
        $this->set_dbname($dbname);
		//echo $dbname."<br/>";
        if ($dbhost != null) {
            $this->set_dbhost($dbhost);
        }
        if ($port != null) {
        	$this->set_port($port);
        }

    } // end function

    /**
     * Connect to the database and change to the appropriate database.
     *
     * @param      none
     * @return     database link identifier
     * @access     public
     * @scope      public
     */
    public function connect(){
        $this->dblink = mysqli_connect($this->dbhost, $this->dblogin, $this->dbpass, $this->dbname, $this->port);

        if (!$this->dblink) {
            $this->return_error('Unable to connect to the database.');
        }

        $t = mysqli_select_db($this->dblink, $this->dbname);
		mysqli_query($this->dblink, "SET NAMES 'utf8'");
        if (!$t) {
            $this->return_error('Unable to change databases.');
        }
		
        if ($this->serverHasTransaction()) {
            $this->transactions_capable = true;
        }

        return $this->dblink;

    } // end function

    /**
     * Disconnect from the mySQL database.
     *
     * @param      none
     * @return     void
     * @access     public
     * @scope      public
     */
    public function disconnect() {
        $test = @mysqli_close($this->dblink);

        if (!$test) {
            $this->return_error('Unable to close the connection.');
        }

        unset($this->dblink);

    } // end function

    /**
     * Stores error messages
     *
     * @param      String $message
     * @return     String
     * @access     private
     * @scope      public
     */
    public function return_error($message) {
        //return $this->error[] = $message.' '.mysqli_error($this->dblink).'.';

    } // end function

    /**
     * Show any errors that occurred.
     *
     * @param      none
     * @return     void
     * @access     public
     * @scope      public
     */
    public function showErrors(){
        if ($this->hasErrors()) {
            reset($this->error);

            $errcount = count($this->error);    //count the number of error messages

            echo "<p>Error(s) found: <b>'$errcount'</b></p>\n";

            // print all the error messages.
            while (list($key, $val) = each($this->error)) {
                echo "+ $val<br>\n";
            }

            $this->resetErrors();
        }

    } // end function

    /**
     * Checks to see if there are any error messages that have been reported.
     *
     * @param      none
     * @return     boolean
     * @access     private
     */
    public function hasErrors(){
        if (count($this->error) > 0) {
            return true;
        } else {
            return false;
        }

    } // end function

    /**
     * Clears all the error messages.
     *
     * @param      none
     * @return     void
     * @access     public
     */
    public function resetErrors(){
        if ($this->hasErrors()) {
            unset($this->error);
            $this->error = array();
        }

    } // end function

    /**
     * Performs an SQL query.
     *
     * @param      String $sql
     * @return     int query identifier
     * @access     public
     * @scope      public
     */
    public function query($sql){    	
        if (empty($this->dblink)) {
            // check to see if there is an open connection. If not, create one.
            $this->connect();
        }
        $sql = $this->stripsql($sql);
        $this->queryid = mysqli_query($this->dblink, $sql);

        if (!$this->queryid) {
            /*
        	if ($this->begin_work) {
                $this->rollbackTransaction();
            }
			*/
            $this->return_error('Unable to perform the query <b>' . $sql . '</b>.');
        }

        $this->previd = 0;
        return $this->queryid;

    } // end function    
    
    public function fetchResult($row = 0){
    	if (isset($this->queryid)) {
    		return  $this->mysqli_result($this->queryid,$row);
    	} else {
    		$this->return_error('No query specified.');
    	}
    }
    
    /**
     * Grabs the records as a array.
     * [edited by MoMad to support movePrev()]
     *
     * @param      none
     * @return     array of db records
     * @access     public
     */
    public function fetchRow(){
        if (isset($this->queryid)) {
            $this->previd++;
            return $this->record = mysqli_fetch_array($this->queryid,MYSQLI_ASSOC);
        } else {
            $this->return_error('No query specified.');
        }

    } // end function

    public function fetchRows(){
    	  if (isset($this->queryid)) {
            //$this->previd++;
            return $this->record = @mysqli_fetch_array($this->queryid);
        } else {
            $this->return_error('No query specified.');
        }
    	
    }
    public function fetchRowAll(){
    	$res = array();
        if (isset($this->queryid)) {
            $this->previd++;
            while($row = @mysqli_fetch_array($this->queryid,MYSQLI_ASSOC))
            {
                $res[] = $row;
            }
        } else {
            $this->return_error('No query specified.');
        }
        return $res;
    }
    /**
     * Moves the record pointer to the first record
     * Contributed by MoMad
     *
     * @param      none
     * @return     array of db records
     * @access     public
     */
    public function moveFirst(){
        if (isset($this->queryid)) {
            $t = @mysqli_data_seek($this->queryid, 0);

            if ($t) {
                $this->previd = 0;
                return $this->fetchRow();
            } else {
                $this->return_error('Cant move to the first record.');
            }
        } else {
            $this->return_error('No query specified.');
        }

    } // end function

    /**
     * Moves the record pointer to the last record
     * Contributed by MoMad
     *
     * @param      none
     * @return     array of db records
     * @access     public
     */
    public function moveLast(){
        if (isset($this->queryid)) {
            $this->previd = $this->resultCount()-1;

            $t = @mysqli_data_seek($this->queryid, $this->previd);

            if ($t) {
                return $this->fetchRow();
            } else {
                $this->return_error('Cant move to the last record.');
            }
        } else {
            $this->return_error('No query specified.');
        }

    } // end function

    /**
     * Moves to the next record (internally, it just calls fetchRow() function)
     * Contributed by MoMad
     *
     * @param      none
     * @return     array of db records
     * @access     public
     */
    public function moveNext(){
        return $this->fetchRow();

    } // end function

    /**
     * Moves to the previous record
     * Contributed by MoMad
     *
     * @param      none
     * @return     array of db records
     * @access     public
     */
    public function movePrev(){
        if (isset($this->queryid)) {
            if ($this->previd > 1) {
                $this->previd--;

                $t = @mysqli_data_seek($this->queryid, --$this->previd);

                if ($t) {
                    return $this->fetchRow();
                } else {
                    $this->return_error('Cant move to the previous record.');
                }
            } else {
                $this->return_error('BOF: First record has been reached.');
            }
        } else {
            $this->return_error('No query specified.');
        }

    } // end function


    /**
     * If the last query performed was an 'INSERT' statement, this method will
     * return the last inserted primary key number. This is specific to the
     * MySQL database server.
     *
     * @param        none
     * @return        int
     * @access        public
     * @scope        public
     * @since        version 1.0.1
     */
    public function fetchLastInsertId(){
        $this->last_insert_id = @mysqli_insert_id($this->dblink);

        if (!$this->last_insert_id) {
            $this->return_error('Unable to get the last inserted id from MySQL.');
        }

        return $this->last_insert_id;

    } // end function

    /**
     * Counts the number of rows returned from a SELECT statement.
     *
     * @param      none
     * @return     Int
     * @access     public
     */
    public function resultCount() {
        $this->totalrecords = @mysqli_num_rows($this->queryid);

        if (!$this->totalrecords) {
            $this->return_error('Unable to count the number of rows returned');
        }

        return $this->totalrecords;

    } // end function

    /**
     * Checks to see if there are any records that were returned from a
     * SELECT statement. If so, returns true, otherwise false.
     *
     * @param      none
     * @return     boolean
     * @access     public
     */
    public function resultExist(){
        if (isset($this->queryid) && ($this->resultCount() > 0)) {
            return true;
        }

        return false;

    } // end function

    /**
     * Clears any records in memory associated with a result set.
     *
     * @param      Int $result
     * @return     void
     * @access     public
     */
    public function clear($result = 0) {
        if ($result != 0) {
            $t = @mysqli_free_result($result);

            if (!$t) {
                $this->return_error('Unable to free the results from memory');
            }
        } else {
            if (isset($this->queryid)) {
                $t = @mysqli_free_result($this->queryid);

                if (!$t) {
                    $this->return_error('Unable to free the results from memory (internal).');
                }
            } else {
                $this->return_error('No SELECT query performed, so nothing to clear.');
            }
        }

    } // end function

    /**
     * Checks to see whether or not the MySQL server supports transactions.
     *
     * @param      none
     * @return     bool
     * @access     public
     */
    public function serverHasTransaction(){
        $this->query('SHOW VARIABLES');

        if ($this->resultExist()) {
            while ($this->fetchRow()) {
                if ($this->record['Variable_name'] == 'have_bdb' && $this->record['Value'] == 'YES') {
                    $this->transactions_capable = true;
                    return true;
                }

                if ($this->record['Variable_name'] == 'have_gemini' && $this->record['Value'] == 'YES') {
                    $this->transactions_capable = true;
                    return true;
                }

                if ($this->record['Variable_name'] == 'have_innodb' && $this->record['Value'] == 'YES') {
                    $this->transactions_capable = true;
                    return true;
                }
            }
        }

        return false;

    } // end function

    /**
     * Start a transaction.
     *
     * @param   none
     * @return  void
     * @access  public
     */
    public function beginTransaction(){
        if ($this->transactions_capable) {
            $this->query('BEGIN');
            $this->begin_work = true;
        }

    } // end function

    /**
     * Perform a commit to record the changes.
     *
     * @param   none
     * @return  void
     * @access  public
     */
    public function commitTransaction(){
        if ($this->transactions_capable) {
            if ($this->begin_work) {
                $this->query('COMMIT');
                $this->begin_work = false;
            }
        }
    }

    /**
     * Perform a rollback if the query fails.
     *
     * @param   none
     * @return  void
     * @access  public
     */
    public function rollbackTransaction(){
        if ($this->transactions_capable) {
            if ($this->begin_work) {
                $this->query('ROLLBACK');
                $this->begin_work = false;
            }
        }

    } // end function
    
    /**
     * 取一条缓存数据
     * 
     * @param string $sql
     * @param boolean $cache
     * @param int $expire
     * @param int $type 1文件缓存
     * @return array
     */
    public function fetchRowCache($sql, $cache=true, $expire=0, $type=1) {
    	if (empty($sql)) { return false; }
    	$sql = $this->stripsql($sql);    	
    	$data = array();
    	if ($type == 1) { //file
    		$filemd5 = md5($sql);
    		$file = D_CACHE.'/_'.$filemd5.'.txt';
    		if ($cache) {
    			$expire = empty($expire) ? CACHE_EXPIRE : $expire;
    			if (file_exists($file) && filemtime($file) > (time() - $expire)) {
    				$data = unserialize(file_get_contents($file));
    			} else {
    				$this->query($sql);
    				$data = $this->fetchRow();
    				$this->clear();
    				$OUTPUT = serialize($data);
    				$fp = fopen($file,"w");
    				fputs($fp, $OUTPUT);
    				fclose($fp);
    			}
    		} else {
    			$this->query($sql);
    			$data = $this->fetchRow();
    			$this->clear();
    		}
    	} elseif ($type == 2) { //memcache
    		
    	}
    	return $data;
    }
    
    /**
     * @desc 设置缓存列表
     * @param string $key
     * @param int $type
     * @return array
     */
    public function fetchRowsCache($sql, $cache=true, $page=true, $expire=0, $type=1) {
    	$sql = $this->stripsql($sql);
    	if (empty($sql)) { return false; }
    	$data = array();
    	if ($type == 1) { //file
    		$filemd5 = md5($sql);
    		$file = D_CACHE.'/_'.$filemd5.'.txt';
    		if ($cache) {
    			$expire = empty($expire) ? CACHE_EXPIRE : $expire;
    			if (file_exists($file) && filemtime($file) > (time() - $expire)) {
    				$data = unserialize(file_get_contents($file));
    			} else {
    				if ($page) {
    					$sqlcount = preg_replace('/order by .*/i', '', $sql);
    					$sqlcount = preg_replace('/limit .*/i', '', $sqlcount);
    					$sqlcount = strtolower($sqlcount);
    
    					//子查询
    					$strcount = substr_count($sqlcount, 'select');
    					if ($strcount >= 2) {
    						$sqlarr = explode('select', $sqlcount);
    						$sqlcount = 'select '.$sqlarr[1];
    						$sqlcount = preg_replace('/select (.*) from/i', 'select count(1) from ', $sqlcount);
    						for($i=2; $i<=$strcount; $i++) {
    							$sqlcount .= "select ".$sqlarr[$i];
    						}
    					} else {
    						$sqlcount = preg_replace('/(select.* from)/i', 'SELECT COUNT(1) FROM ', $sqlcount);
    					}
    					if (preg_match('/group by/i', $sqlcount)){
    						$this->query($sqlcount);
    						$tmp = $this->fetchRowAll();
    						$count = count($tmp);
    					} else {
    						$this->query($sqlcount);
    						$count = $this->fetchResult();
    					}
    					$data['count'] = $count;
    				}
    
    				$this->query($sql);
    				$data['data'] = $this->fetchRowAll();
    				$data['curcount'] = count($data['data']);    				
    				$this->clear();
    				$OUTPUT = serialize($data);
    				$fp = fopen($file,"w");
    				fputs($fp, $OUTPUT);
    				fclose($fp);
    			}    
    		} else {
    			$sqlcount = preg_replace('/order by .*/i', '', $sql)."\n\r";
    			$sqlcount = preg_replace('/limit .*/i', '', $sqlcount);
    			$sqlcount = strtolower($sqlcount);
    
    			//子查询
    			$strcount = substr_count($sqlcount, 'select');
    			if ($strcount >= 2) {
    				$sqlarr = explode('select', $sqlcount);
    				$sqlcount = 'select '.$sqlarr[1];
    				$sqlcount = preg_replace('/select (.*) from/i', 'select count(1) from ', $sqlcount);
    				for($i=2; $i<=$strcount; $i++) {
    					$sqlcount .= "select ".$sqlarr[$i];
    				}
    			} else {
    				$sqlcount = preg_replace('/(select .* from)/i', 'SELECT COUNT(1) FROM ', $sqlcount);
    			}
    			if (preg_match('/group by/i', $sqlcount)){
    				$this->query($sqlcount);
    				$tmp = $this->fetchRowAll();
    				$count = count($tmp);
    			} else {
    				$this->query($sqlcount);
    				$count = $this->fetchResult();
    			}
    			$data['count'] = $count;
    
    			$this->query($sql);
    			$data['data'] = $this->fetchRowAll();
    			$data['curcount'] = count($data['data']);    	
    			$this->clear();
    		}    
    	} elseif ($type == 2) { //memcache
    		    
    	}
    	return $data;    
    }
    
    public function quote($str, $noarray = false) {
    	if (is_string($str))
    		return '\'' . ($str) . '\'';//mysql_escape_string mysql_real_escape_string
    
    	if (is_int($str) or is_float($str))
    		return '\'' . $str . '\'';
    
    	if (is_array($str)) {
    		if($noarray === false) {
    			foreach ($str as &$v) {
    				$v = $this->quote($v, true);
    			}
    			return $str;
    		} else {
    			return '\'\'';
    		}
    	}
    
    	if (is_bool($str))
    		return $str ? '1' : '0';
    
    	return '\'\'';
    }
    
    public function quote_field($field) {
    	if (is_array($field)) {
    		foreach ($field as $k => $v) {
    			$field[$k] = $this->quote_field($v);
    		}
    	} else {
    		if (strpos($field, '`') !== false)
    			$field = str_replace('`', '', $field);
    		$field = '`' . $field . '`';
    	}
    	return $field;
    }
    
    public function implode($array, $glue = ',') {
    	$sql = $comma = '';
    	$glue = ' ' . trim($glue) . ' ';
    	foreach ($array as $k => $v) {
    		$sql .= $comma . $this->quote_field($k) . '=' . $this->quote($v);
    		$comma = $glue;
    	}
    	return $sql;
    }
    
    /**
     * 数据添加
     *
     * @param string $table
     * @param array $data
     * @param string $return_insert_id
     * @param string $replace
     * @return number
     */
    public function insert($table, $data, $return_insert_id = false, $replace = false) {
    	$sql = $this->implode($data);
    	$cmd = $replace ? 'REPLACE INTO' : 'INSERT INTO';
    	//echo "$cmd $table SET $sql";
    	$queryid =  $this->query("$cmd $table SET $sql");
    	return $return_insert_id ? $this->fetchLastInsertId() : $queryid;
    }
    
    /**
     * 更新数据
     *
     * @param string $table
     * @param array $data
     * @param string $condition
     * @param string $unbuffered
     * @param string $low_priority
     * @return boolean|number
     */
    public function update($table, $data, $condition, $unbuffered = false, $low_priority = false) {
    	$sql = $this->implode($data);
    	if (empty($sql)) { return false; }
    	$cmd = "UPDATE " . ($low_priority ? 'LOW_PRIORITY' : '');
    	$where = '';
    	if (empty($condition)) {
    		$where = '1';
    	} elseif (is_array($condition)) {
    		$where = $this->implode($condition, ' AND ');
    	} else {
    		$where = $condition;
    	}
    	$res = $this->query("$cmd $table SET $sql WHERE $where");
    	return $res;
    }
    
    /**
     * @desc remove blank & enter
     * @param unknown $sql
     * @return Ambigous <string, mixed>
     */
    public function stripsql($sql) {
    	$sql = trim($sql);
    	//return preg_replace('/(\s)+/s', ' ', $sql);    
    	//check
    	if (preg_match("/\r|\n|\t/s", $sql)) {
    		//save
    		if (preg_match("/['|\"].*?[\r|\n|\t].*?/s", $sql)) {
    			preg_match_all('/(\'|").*?(\'|")/s', $sql, $match);
    		}    
    		//strip
    		$sql = preg_replace('/(\s)+/s', ' ', $sql);    
    		//revert
    		if (!empty($match)) {
    			preg_match_all('/(\'|").*?(\'|")/s', $sql, $pmatch);
    			if ($match != $pmatch) {
    				foreach ($pmatch['0'] as $key => $val) {
    					$sql = str_replace($val, $match['0'][$key], $sql);
    				}
    			}
    			unset($match, $pmatch);
    		}
    	}
    	return $sql;
    } // end function

    public function mysqli_result($result,$row,$field=0) {
        if ($result===false) return false;
        if ($row>=mysqli_num_rows($result)) return false;
        if (is_string($field) && !(strpos($field,".")===false)) {
            $t_field=explode(".",$field);
            $field=-1;
            $t_fields=mysqli_fetch_fields($result);
            for ($id=0;$id<mysqli_num_fields($result);$id++) {
                if ($t_fields[$id]->table==$t_field[0] && $t_fields[$id]->name==$t_field[1]) {
                    $field=$id;
                    break;
                }
            }
            if ($field==-1) return false;
        }
        mysqli_data_seek($result,$row);
        $line=mysqli_fetch_array($result);
        return isset($line[$field])?$line[$field]:false;
    }

} // end class


?>
