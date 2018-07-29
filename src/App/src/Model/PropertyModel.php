<?php

namespace App\Model;
use App\Entity\PropertyEntity;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;

class PropertyModel
{
    private $table;
    private $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $resultSet = new HydratingResultSet();
        $resultSet->setObjectPrototype(new PropertyEntity());
        $this->table = new TableGateway('properties', $adapter, null, $resultSet);
    }

    /**
     * @param $id int
     * @return PropertyEntity
     * @throws \Exception
     */
    public function getProperty($id)
    {

        $contract = $this->table->select([ 'id' => $id ]);

        $result = $contract->current();

        if (! $result instanceof PropertyEntity) {
            throw new \DomainException('Property not found');
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        $properties = $this->table->select();
        $propertiesArray = $properties->toArray();

        return $propertiesArray;
    }

    /**
     * @param $number string
     * @return array
     */
    public function getPropertiesByContract($number)
    {
        $sql    = new Sql($this->adapter);
        $properties = $sql->select()
            ->from(['cp' => 'contracts_properties'])
            ->join(array('p' => 'properties'),
                'cp.property_id = p.id')
            ->where(['cp.contract_number = ?' => $number]);

        $statement = $sql->prepareStatementForSqlObject($properties);
        $results = $statement->execute();
        $propertiesArray = $results->getResource()->fetchAll(\PDO::FETCH_ASSOC);

        return $propertiesArray;
    }

    /**
     * @param $set
     * @return int|null
     */
    public function createProperty($set)
    {
        if (!isset($set['area'])) {
            throw new \InvalidArgumentException('Area is a required field');
        }

        if(!is_numeric($set['area'])) {
            throw new \InvalidArgumentException('Area must be numeric value');
        }

        $result = $this->table->insert($set);

        return ($result === 1) ? $this->table->lastInsertValue : null;
    }


    /**
     * @param $set array
     * @param $id string
     * @return null|string
     */
    public function UpdateProperty($set, $id)
    {
        try {
            $this->getProperty($id);
        } catch (\DomainException $exception) {
            throw new \InvalidArgumentException('Property with this id does not exist');
        }
        catch (\Exception $exception) {}

        if(isset($set['area']) && !is_numeric($set['area'])) {
            throw new \InvalidArgumentException('Area must be numeric value');
        }

        $result = $this->table->update($set, ['id = ?' => $id]);

        return ($result === 1) ? $id : null;
    }

    /**
     * @param $id int
     * @return null|string
     */
    public function DeleteProperty($id)
    {
        try {
            $this->getProperty($id);
        } catch (\DomainException $exception) {
            throw new \InvalidArgumentException('Property with this id does not exist');
        }
        catch (\Exception $exception) {}

        $this->table->delete(['id = ?' => $id]);

        return $id;
    }

    public function AddLandlord($set)
    {
        if (!isset($set['landlord_id'])) {
            throw new \InvalidArgumentException('Landlord_id is a required field');
        }

        if (!isset($set['property_id'])) {
            throw new \InvalidArgumentException('Property_id is a required field');
        }

        if (!isset($set['percent'])) {
            $set['percent'] = 100;
        }

        try {
            $this->getProperty($set['property_id']);
        } catch (\DomainException $exception) {
            throw new \InvalidArgumentException('Property with this id does not exist');
        }
        catch (\Exception $exception) {}

        $sql    = new Sql($this->adapter);
        $properties = $sql->insert('properties_landlords')
            ->values([
                'landlord_id' => $set['landlord_id'],
                'property_id' => $set['property_id'],
                'percent' => $set['percent']
            ]);

        $statement = $sql->prepareStatementForSqlObject($properties);
        $results = $statement->execute();

        return $results;
    }
}