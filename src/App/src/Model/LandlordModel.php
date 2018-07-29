<?php

namespace App\Model;
use App\Entity\LandlordEntity;
use App\Entity\PropertyEntity;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;

class LandlordModel
{
    private $table;
    private $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $resultSet = new HydratingResultSet();
        $resultSet->setObjectPrototype(new LandlordEntity());
        $this->table = new TableGateway('landlords', $adapter, null, $resultSet);
    }

    /**
     * @param $id int
     * @return LandlordEntity
     * @throws \Exception
     */
    public function getLandlord($id)
    {

        $contract = $this->table->select([ 'id' => $id ]);

        $result = $contract->current();

        if (! $result instanceof LandlordEntity) {
            throw new \DomainException('Landlord not found');
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getLandlords()
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
    public function createLandlord($set)
    {
        if (!isset($set['name'])) {
            throw new \InvalidArgumentException('Name is a required field');
        }

        if (!isset($set['phone'])) {
            throw new \InvalidArgumentException('Phone is a required field');
        }

        if (!isset($set['personal_id'])) {
            throw new \InvalidArgumentException('Personal_id is a required field');
        }

        $result = $this->table->insert($set);

        return ($result === 1) ? $this->table->lastInsertValue : null;
    }


    /**
     * @param $set array
     * @param $id string
     * @return null|string
     */
    public function UpdateLandlord($set, $id)
    {
        try {
            $this->getLandlord($id);
        } catch (\DomainException $exception) {
            throw new \InvalidArgumentException('Landlord with this id does not exist');
        }
        catch (\Exception $exception) {}

        $result = $this->table->update($set, ['id = ?' => $id]);

        return ($result === 1) ? $id : null;
    }

    /**
     * @param $id int
     * @return null|string
     */
    public function DeleteLandlord($id)
    {
        try {
            $this->getLandlord($id);
        } catch (\DomainException $exception) {
            throw new \InvalidArgumentException('Landlord with this id does not exist');
        }
        catch (\Exception $exception) {}

        $this->table->delete(['id = ?' => $id]);

        return $id;
    }
}