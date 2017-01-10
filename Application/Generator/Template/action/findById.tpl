	
	/**
     * 根据主键id查询单条数据
     * @param int $id 查询条件
     */
	public function findById($id=0){
		return $this->service->findById($id);
	}
	