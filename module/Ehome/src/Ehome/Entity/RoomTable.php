<?php
namespace Ehome\Entity;

use Zend\Db\TableGateway\TableGateway;

class RoomTable {
	
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway){
		$this->tableGateway = $tableGateway;
	}
	
	public function fetchAll(){
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}
	
	public function getRoom($id){
		//var_dump($id);
		$id = (int) $id;
		$rowset = $this->tableGateway->select( array(
				'id' => $id 
		));
		$row = $rowset->current();
		if (! $row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saveRoom(Room $room){
		$data = array (
				'name' => $room->getName(),
				'waterlevel' => $room->getWaterlevel(),
				'switch' => $room->getSwitch(),
		);
		$id = (int) $room->getId();
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getRoom($id)){
				$this->tableGateway->update($data, array (
						'id' => $id 
				) );
			} else {
				throw new \Exception('Room id does not exist');
			}
		}
	}
	
	public function deleteRoom($id){
		$this->tableGateway->delete(array(
				'id' => (int) $id 
		) );
	}
}