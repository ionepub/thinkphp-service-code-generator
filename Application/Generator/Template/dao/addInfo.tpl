
	/**
     * 向表中插入数据
     * @param array $data 待插入数据数组
     */
	public function addInfo($data=array()){
		return $this->model->data($data)->add();
	}
	