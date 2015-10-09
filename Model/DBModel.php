<?php
/**
*数据操作中间类
*@连贯操作
*/
class DBModel
{
    private $db;
    private $table;
    private $_field = "*";
    private $_join  = null;
    private $_where = null;
    private $_order = null;
    private $_limit = null;
    private $_group = null;
	private $_bind_data = array();

    /**
     *获取数据库操作对象以及设置操作的表
     *@param $table string
     *@param $config array
     *@return null
     */
    public function setCluster($table,$config = array())
    {/*{{{*/
        $cmd_tag = isset($_SERVER['SERVER_ADDR']) ? 1 : 1;
        static $db_servers = array();
        $key = md5(serialize($config));

        if(isset($db_servers[$key]) && $cmd_tag == 1)
	{
            $this->db = $db_servers[$key];
	}else{
            $this->db = DB::getInstance($config);
            $db_servers[$key] = $this->db;
	}
	$this->table = $table;
    }/*}}}*/

    /**
     * 开启事务
     */
    public function startTrans()
    {/*{{{*/
        $this->db->startTrans();
    }/*}}}*/

    /**
     * 提交
     */
    public function commit()
    {/*{{{*/
        $this->db->commit();
    }/*}}}*/

    /**
     * 回滚
     */
	public function rollback()
    {/*{{{*/
		$this->db->rollback();
    }/*}}}*/

	/**
	 *查询单条记录
	 *@param null
	 *@return array
	 */
	public function one()
    {/*{{{*/
		$sql = $this->getSql(__FUNCTION__);
		$res = $this->db->getOne($sql,$this->_bind_data);
		$this->clear();
		return $res;
    }/*}}}*/

    /**
	 *查询
	 *@param null
	 *@return array
	 */
	public function select()
    {/*{{{*/
		$sql = $this->getSql(__FUNCTION__);
		$res = $this->db->getAll($sql,$this->_bind_data);
		$this->clear();
		return $res;
    }/*}}}*/

    /**
	 *更新
	 *@param $data array
	 *@return key
	 */
	public function update($data)
    {/*{{{*/
		$res = $this->db->update($this->table, $data, $this->_where,$this->_bind_data);
		$this->clear();
		return $res;
    }/*}}}*/

    /**
	 *删除
	 *@param null
	 *@return key
	 */
	public function delete()
    {/*{{{*/
		if(empty($this->_bind_data)){
			exit("Delete操作请使用bind安全模式!");
		}
		$res = $this->db->delete($this->table,$this->_where,$this->_bind_data);
		$this->clear();
		return $res;
    }/*}}}*/

    /**
	 *插入
	 *@param $data array
	 *@return data.key
	 */
	public function insert($data)
    {/*{{{*/
		if(!is_array($data))
		{
			exit("Param Error : The param of \"insert\" given must be Array!!!");
		}
		return $this->db->insert($this->table, $data);
    }/*}}}*/

	/**
	 *统计
	 *@param null
	 *@return string
	 */
	public function count()
    {/*{{{*/
		$sql = $this->getSql(__FUNCTION__);
		$res = $this->db->getRow($sql,$this->_bind_data);
		$this->clear();
		return $res['cnt'];
    }/*}}}*/

    public function field($field)
    {/*{{{*/
		$this->_field = $field;
		return $this;
    }/*}}}*/

	public function where($where)
    {/*{{{*/
		if(!is_array($where)){
			exit('Parame Type Error!$where must be array');
		}
		if(is_string($where['condition'])){
			if($where['condition'] != ""){
				$this->_where = "where ".$where['condition'];
			}
			if(!empty($where['data'])){
				$this->_bind_data = $where['data'];
			}
		}else{
			if(!empty($where['condition']))
			{
				if(!isset($where['logic'])){
					$where['logic'] = 'AND';
				}
				$this->_where = $this->buildWhere($where['condition'],$where['logic']);
			}
		}
		return $this;
    }/*}}}*/

	public function order($order)
    {/*{{{*/
		if(!is_string($order)){
			exit("Param Error : The param of \"order\" given must be String!!!");
		}
		$this->_order = "order by ".$order;
		return $this;
    }/*}}}*/

    public function limit($offset = 0,$len = 0)
    {/*{{{*/
		$this->_limit = 'limit ' . $offset . ',' . $len;
		return $this;
    }/*}}}*/

    public function group($group)
    {/*{{{*/
        if(!is_string($group)){
            exit("Param Error : The param of \"limit\" given must be String!!!");
        }
        $this->_group = "group by ".$group;
        return $this;
    }/*}}}*/

    public function join($join)
    {/*{{{*/
		if(!is_string($join)){
			exit("Param Error : The param of \"join\" given must be String!!!");
		}
		$this->_join = "JOIN ".$join;
		return $this;
    }/*}}}*/

	public function getAllBySql($sql,$bind_data = array())
	{/*{{{*/
		if(!is_string($sql)){
			exit('Error:parame type error!Usage:$this->sql(sql [string],data [array])');
		}
		$res = $this->db->getAll($sql,$bind_data);
		return $res;
	}/*}}}*/

	public function getOneBySql($sql,$bind_data = array())
	{/*{{{*/
		if(!is_string($sql)){
			exit('Error:parame type error!Usage:$this->sql(sql [string],data [array])');
		}
		$res = $this->db->getOne($sql,$bind_data);
		return $res;
	}/*}}}*/

	public function query($sql,$bind_data = array())
	{/*{{{*/
		if(!is_string($sql)){
			exit('Error:parame type error!Usage:$this->sql(sql [string],data [array])');
		}
		if(!strpos($sql,"?")){
			exit("SQL语句:[$sql],存在SQL注入漏洞隐患，请改用bind方式处理");
		}
		$res = $this->db->execute($sql,$bind_data);
		return $res;
	}/*}}}*/

	private function getSql($oprate)
    {/*{{{*/
		switch($oprate){
			case 'insert':;break;
			case 'one':;
			case 'select':
				return "select {$this->_field} from {$this->table} {$this->_join} {$this->_where} {$this->_group} {$this->_order} {$this->_limit}";
				break;
			case 'count':
				return "select count({$this->_field}) as cnt from {$this->table} {$this->_join} {$this->_group} {$this->_where}";
				break;
			case 'update':break;
			case 'delete':break;
		}
    }/*}}}*/

	private function buildWhere($where,$logic = 'AND')
    {/*{{{*/
		if(!is_array($where)){
			exit("Param Error : The param must be Array!!!");
		}
		$where_arr = array();

		foreach($where as $key=>$value)
		{
			if(!isset($value)) continue;

			if(is_array($value))
			{
				$where_arr[] = $key . $value[0] . '?';
				$this->_bind_data[] = $value[1];
			}else{
				$where_arr[] = $key . '=?';
				$this->_bind_data[] = $value;
			}
		}

		if(!empty($where_arr)) return "where ".implode(" ".$logic." ",$where_arr);
    }/*}}}*/

	static public function destroy($config = array())
    {/*{{{*/
		DB::getInstance($config)->destroyInstance($config);
    }/*}}}*/

	private function clear()
    {/*{{{*/
		$this->_field = "*";
		$this->_join  = null;
		$this->_where = null;
		$this->_order = null;
		$this->_limit = null;
		$this->_bind_data = array();
    }/*}}}*/


}
