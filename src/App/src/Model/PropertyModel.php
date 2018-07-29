<?php

namespace App\Model;
use App\Entity\PropertyEntity;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;

class PropertyModel
{
    private $table;

    public function __construct(AdapterInterface $adapter)
    {
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
}