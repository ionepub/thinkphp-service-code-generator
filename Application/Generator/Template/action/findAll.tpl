	
	/**
     * 根据条件查询表中数据
     * @param array $where 查询条件数组
     * @param int $pagesize 分页大小，为0则不分页
     * @param int $page 当前页码
     * @param string $order 排序方式
     */	
	public function findAll($where=array(), $pagesize=0, $page=0, $order=""){
		return $this->service->findAll($where, $pagesize, $page, $order);
	}
	