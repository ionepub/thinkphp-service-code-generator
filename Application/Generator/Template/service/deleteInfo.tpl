	
	/**
     * 根据条件删除表中数据
     * @param array $where 查询条件数组
     */	
	public function deleteInfo($where=array()){
		return $this->dao->deleteInfo($where);
	}
	