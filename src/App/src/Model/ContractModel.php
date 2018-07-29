<?php

namespace App\Model;
use App\Entity\ContractEntity;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;

class ContractModel
{
    private $table;
    private $adapter;
    private $propertyModel;

    public function __construct(AdapterInterface $adapter, PropertyModel $propertyModel)
    {
        $this->adapter = $adapter;
        $this->propertyModel = $propertyModel;
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
            throw new \DomainException('Contract not found');
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
    public function createContract($set) //@todo: redirect to resource
    {
        if (!isset($set['number'])) {
            throw new \InvalidArgumentException('Number is a required field');
        }
        if (!isset($set['type'])) {
            throw new \InvalidArgumentException('Type is a required field');
        }
        if (!isset($set['start_date'])) {
            throw new \InvalidArgumentException('Start Date is a required field');
        }
        if (!isset($set['end_date'])) {
            throw new \InvalidArgumentException('End Date is a required field');
        }

        $contract = null;
        try {
            $contract = $this->getContract($set['number']);
        } catch (\DomainException $exception) {}
        catch (\Exception $exception) {}

        if($contract instanceof ContractEntity) {
            throw new \InvalidArgumentException('Contract with this number already exists');
        }

        $this->ValidateContract($set);

        $set['start_date'] = date("Y-m-d H:i:s", strtotime($set['start_date']));
        $set['end_date'] = date("Y-m-d H:i:s", strtotime($set['end_date']));

        $result = $this->table->insert($set);

        return ($result === 1) ? $set['number'] : null;
    }

    /**
     * @param $set array
     * @param $number string
     * @return null|string
     */
    public function UpdateContract($set, $number)
    {
        try {
            $this->getContract($number);
        } catch (\DomainException $exception) {
            throw new \InvalidArgumentException('Contract with this number does not exist');
        }
        catch (\Exception $exception) {}

        $this->ValidateContract($set);

        $result = $this->table->update($set, ['number = ?' => $number]);

        return ($result === 1) ? $number : null;
    }

    /**
     * @param $number string
     * @return null|string
     */
    public function DeleteContract($number)
    {
        try {
            $this->getContract($number);
        } catch (\DomainException $exception) {
            throw new \InvalidArgumentException('Contract with this number does not exist');
        }
        catch (\Exception $exception) {}

        $this->table->delete(['number = ?' => $number]);

        return $number;
    }

    public function AddProperty($set)
    {
        if (!isset($set['number'])) {
            throw new \InvalidArgumentException('Number is a required field');
        }

        if (!isset($set['property_id'])) {
            throw new \InvalidArgumentException('Property_id is a required field');
        }

        try {
            $this->getContract($set['number']);
        } catch (\DomainException $exception) {
            throw new \InvalidArgumentException('Contract with this number does not exist');
        }
        catch (\Exception $exception) {}

        try {
            $this->propertyModel->getProperty($set['property_id']);
        } catch (\DomainException $exception) {
            throw new \InvalidArgumentException('Property with this id does not exist');
        }
        catch (\Exception $exception) {}

        $sql    = new Sql($this->adapter);
        $properties = $sql->insert('contracts_properties')
            ->values([
                'contract_number' => $set['number'],
                'property_id' => $set['property_id'],
            ]);

        $statement = $sql->prepareStatementForSqlObject($properties);
        $results = $statement->execute();

        return $results;
    }

    public function DeleteContractProperty($number, $propertyId)
    {
        try {
            $this->getContract($number);
        } catch (\DomainException $exception) {
            throw new \InvalidArgumentException('Contract with this number does not exist');
        }
        catch (\Exception $exception) {}

        try {
            $this->propertyModel->getProperty($propertyId);
        } catch (\DomainException $exception) {
            throw new \InvalidArgumentException('Property with this id does not exist');
        }
        catch (\Exception $exception) {}

        $sql    = new Sql($this->adapter);
        $properties = $sql->delete('contracts_properties')
            ->where(['contract_number = ?' => $number, 'property_id' => $propertyId]);

        $statement = $sql->prepareStatementForSqlObject($properties);
        $results = $statement->execute();

        return $results;
    }

    private function ValidateContract($set)
    {
        if (isset($set['price']) && !is_numeric($set['price'])) {
            throw new \InvalidArgumentException('Price is a numeric field');
        }

        if (isset($set['rent']) && !is_numeric($set['rent'])) {
            throw new \InvalidArgumentException('Rent is a numeric field');
        }

        if(isset($set['type'])) {

            $set['type'] = intval($set['type']);
            if(!in_array($set['type'], ContractEntity::TYPES)) {
                throw new \InvalidArgumentException('Invalid type');
            }

            if($set['type'] === ContractEntity::TYPES[ContractEntity::TYPE_OWNERSHIP]) {
                if (!isset($set['price'])) {
                    throw new \InvalidArgumentException('Price is a required field');
                }
            }

            if($set['type'] === ContractEntity::TYPES[ContractEntity::TYPE_RENT]) {
                if (!isset($set['rent'])) {
                    throw new \InvalidArgumentException('Rent is a required field');
                }
            }
        }
    }
}