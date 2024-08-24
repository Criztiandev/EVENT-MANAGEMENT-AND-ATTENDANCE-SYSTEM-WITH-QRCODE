<?php

namespace lib\Mangoose;

use lib\ORM\Prizzle;

class Model
{
    public $table;
    public $database;
    public $conditions = [];
    public $selections = "*";
    public $queryType = 'SELECT';
    public $limit = null;

    private $valid_operator;

    public function __construct($table)
    {
        $this->valid_operator = ["#or", "#and"];
        $this->table = $table;
        $this->database = Mangoose::getInstance();
    }

    public function findOne($params, $options = [], $separator = "AND")
    {
        $query = $this->buildQuery($params, $options);
        $payload = $this->extractClausePayload($params);


        $statement = $this->executeQuery($query, $payload);
        return $statement->fetch();
    }


    public function find(array $params = [], array $options = [])
    {
        $query = $this->buildQuery($params, $options);
        $statement = $this->executeQuery($query, $params);
        return $statement->fetchAll();
    }

    public function createOne($params)
    {

        $query = $this->buildInsertQuery($params);
        return $this->executeQuery($query, $params);
    }

    public function updateOne(array $update, $condition)
    {
        $query = $this->buildUpdateQuery($update, $condition);
        return $this->executeQuery($query, array_merge($update, $condition));
    }

    public function deleteOne(array $conditions)
    {
        $query = $this->buildDeleteQuery($conditions);
        return $this->executeQuery($query, $conditions);
    }


    public function deleteMany(array $conditions)
    {
        if (empty($conditions)) {
            throw new \InvalidArgumentException("No conditions provided for deletion.");
        }

        $whereClause = $this->buildCondition($conditions);
        $query = "DELETE FROM " . $this->table . " WHERE $whereClause";
        $this->executeQuery($query, $conditions);
    }

    /**
     * Actions Class
     */

    protected function buildQuery($params, $options = [], )
    {

        $selectedField = $options["select"] ?? $this->selections;
        $limit = isset($options['limit']) ? "LIMIT " . (int) $options['limit'] : '';

        $whereClause = $this->buildCondition($params);

        return "SELECT $selectedField FROM " . $this->table . " WHERE" . " " . $whereClause . " " . $limit;
    }


    protected function buildInsertQuery($params)
    {
        $fields = array_keys($params);
        $placeholders = array_map(fn($fields) => ":$fields", $fields);

        $fieldClause = implode(", ", $fields);
        $placeholderClause = implode(", ", $placeholders);

        return "INSERT INTO " . $this->table . " ($fieldClause) VALUES ($placeholderClause)";
    }

    protected function buildUpdateQuery($params, $conditions)
    {
        $setters = $this->buildSetClause($params);
        $wheres = $this->buildCondition($conditions);

        return "UPDATE " . $this->table . " SET $setters WHERE $wheres";
    }

    protected function buildDeleteQuery(array $conditions): string
    {
        $whereClause = $this->buildCondition($conditions);

        return "DELETE FROM " . $this->table . " WHERE $whereClause";
    }



    /**
     * Heloper Class
     */
    protected function buildCondition(array $conditions, $separator = "AND")
    {
        if (empty($conditions)) {
            return 1;
        }

        $clauses = [];

        foreach ($conditions as $key => $value) {
            if (!in_array($key, $this->valid_operator) && !is_string($key)) {
                throw new \InvalidArgumentException("Invalid condition key: $key");
            }

            if ($key === "#or" || $key === "#and") {

                if (!is_array($value)) {
                    throw new \InvalidArgumentException("The value for $key must be an array of conditions.");
                }

                $subClauses = array_map(fn($fields) => $this->buildCondition($fields, $key === "#or" ? "OR" : "AND"), $conditions);
                $clauses[] = '(' . implode(' ' . strtoupper($key) . ' ', $subClauses) . ')';
            } else {

                if (!is_scalar($value) && !is_null($value)) {
                    throw new \InvalidArgumentException("Invalid condition value for $key: must be a scalar or null.");
                }
                $clauses[] = "$key = :$key";
            }
        }

        return implode(" $separator ", $clauses);
    }


    public function extractClausePayload(array $params)
    {
        $payload = [];

        foreach ($params as $key => $value) {
            if (!in_array($key, $this->valid_operator) && !is_string($key)) {
                throw new \InvalidArgumentException("Invalid condition key: $key");
            }

            if ($key === "#or" || $key === "#and") {
                $payload = $value;
            } else {
                $payload[$key] = $value;
            }
        }
        return $payload;
    }



    protected function buildSetClause(array $params): string
    {
        return implode(', ', array_map(fn($field) => "$field = :$field", array_keys($params)));
    }

    public function executeQuery(string $query, array $params)
    {

        try {
            $statement = $this->database->conn->prepare($query);
            $statement->execute($params);
            return $statement;
        } catch (\PDOException $e) {
            // Handle the error appropriately (log it, rethrow it, etc.)
            // For example, log the error message
            error_log("Query failed: " . $e->getMessage());

            // Optionally, rethrow or return a custom error response
            throw new \RuntimeException($e->getMessage());
        }
    }






}

