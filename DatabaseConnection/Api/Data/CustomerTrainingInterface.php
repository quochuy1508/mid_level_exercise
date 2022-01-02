<?php

namespace Magenest\DatabaseConnection\Api\Data;

interface CustomerTrainingInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID = 'entity_id';
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const ADDRESS = 'address';
    const CITY = 'city';
    const AGE = 'age';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get identifier
     *
     * @return string
     */
    public function getFirstName();

    /**
     * Get title
     *
     * @return string|null
     */
    public function getLastName();

    /**
     * Get page layout
     *
     * @return string|null
     */
    public function getAddress();

    /**
     * Get meta title
     *
     * @return string|null
     * @since 101.0.0
     */
    public function getCity();

    /**
     * Get meta keywords
     *
     * @return int|null
     */
    public function getAge();

    /**
     * Set page layout
     *
     * @param int $id
     * @return \Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterface
     */
    public function setId($id);

    /**
     * Set page layout
     *
     * @param string $firstName
     * @return \Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterface
     */
    public function setFirstName($firstName);

    /**
     * Set page layout
     *
     * @param string $lastName
     * @return \Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterface
     */
    public function setLastName($lastName);

    /**
     * Set page layout
     *
     * @param string $address
     * @return \Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterface
     */
    public function setAddress($address);

    /**
     * Set page layout
     *
     * @param string $city
     * @return \Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterface
     */
    public function setCity($city);

    /**
     * Set page layout
     *
     * @param int $age
     * @return \Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterface
     */
    public function setAge($age);
}
