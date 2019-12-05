<?php
abstract class AbstractDAOOVH {

    protected $pdo; // For Singleton pattern

    // All classes that will inherit from this abstract class will have to define these methods.
    abstract public function deactivate($object);
    abstract public function delete($object);
    abstract public function findAll();
    abstract public function findById($searchedId);
    abstract public function insert($object);
    abstract public function save($object);
    abstract public function update($object);
}
?>