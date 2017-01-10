	
	/**
     * 根据条件更新表中数据
     * @param array $where 查询条件数组
     * @param array $data 待插入数据数组
     */	
	public function updateInfo($where=array(), $data=array()){
		return $this->dao->updateInfo($where, $data);
	}
	