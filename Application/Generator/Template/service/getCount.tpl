	
	/**
     * 根据条件获取表中数据总数
     * @param array $where 查询条件数组
     */	
	public function getCount($where=array()){
		return $this->dao->getCount($where);
	}
	