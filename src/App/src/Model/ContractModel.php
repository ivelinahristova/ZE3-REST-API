<?php

namespace App\Model;
use App\Entity\ContractEntity;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;

class ContractModel
{
    private $table;

    public function __construct(AdapterInterface $adapter)
    {
        $resultSet = new HydratingResultSet();
        $resultSet->setObjectPrototype(new ContractEntity());
        $this->table = new TableGateway('contracts', $adapter, null, $resultSet);
    }

    /**
     * @param $number
     * @return ContractEntity
     * @throws \Exception
     */
    public function getContract($number)
    {

        $contract = $this->table->select([ 'number' => $number ]);

        $result = $contract->current();

        if (! $result instanceof ContractEntity) {
            throw new \Exception('Contract not found');
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getContracts()
    {
        $contracts = $this->table->select();
        $contractsArray = $contracts->toArray();

        return $contractsArray;
    }

    /**
     * @param $set
     * @return int|null
     */
    public function createContract($set)
    {
        $result = $this->table->insert($set);

        return ($result === 1) ? $this->table->lastInsertValue : null;
    }
}