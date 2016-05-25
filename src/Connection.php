<?php

namespace Air\Database;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class Connection implements ConnectionInterface
{
    /**
     * @var array $connectionParams An array of connection parameters.
     */
    private $connectionParams;


    /**
     * @var Connection $connection A database connection.
     */
    private $connection;


    /**
     * Constructor to collect required database credentials.
     *
     * @param string $host The hostname.
     * @param string $username The database username.
     * @param string $password The database password.
     * @param string $database The name of the database.
     * @param string $driver The database driver, defaults to pdo_mysql.
     * @param array $options The driver options passed to the pdo connection.
     */
    public function __construct(
        $host,
        $username,
        $password,
        $database,
        $driver = 'pdo_mysql',
        array $options = []
    ) {
        $this->connectionParams = array(
            'dbname' => $database,
            'user' => $username,
            'password' => $password,
            'host' => $host,
            'driver' => $driver,
            'driverOptions' => $options
        );
    }


    /**
     * Returns a Doctrine query builder object.
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        $connection = $this->getConnection();

        return $connection->createQueryBuilder();
    }


    /**
     * Returns the connection object.
     *
     * @return Connection
     */
    private function getConnection()
    {
        if (!isset($this->connection)) {
            $config = new Configuration();

            $this->connection = DriverManager::getConnection($this->connectionParams, $config);
        }

        return $this->connection;
    }


    /**
     * Sets a timezone.
     *
     * @param string $timezone The timezone you wish to set.
     */
    public function setTimezone($timezone)
    {
        $smt = $this->getConnection()->prepare('SET time_zone = ?');
        $smt->bindValue(1, $timezone);
        $smt->execute();
    }
}
