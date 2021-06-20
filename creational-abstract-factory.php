<?php

/**
 * This is AbstractFactory interface
 * 2nees.com
 */
interface FurnitureFactory
{
    public function createChair(): Chair;
    public function createSofa(): Sofa;
    public function createServiceTable(): ServiceTable;
}

/**
 * Produce the Object Family - Modern Family
 * 2nees.com
 */
class ModernDesign implements FurnitureFactory
{
    public function createChair(): Chair
    {
        return new ModernChair();
    }
    public function createSofa(): Sofa
    {
        return new ModernSofa();
    }
    public function createServiceTable(): ServiceTable
    {
        return new ModernServiceTable();
    }
}

/**
 * Produce the Object Family - Classical Family
 * 2nees.com
 */
class ClassicalDesign implements FurnitureFactory
{
    public function createChair(): Chair
    {
        return new ClassicalChair();
    }
    public function createSofa(): Sofa
    {
        return new ClassicalSofa();
    }
    public function createServiceTable(): ServiceTable
    {
        return new ClassicalServiceTable();
    }
}

/**
 * Interface Chair, Sofa, ServiceTable is the Abstract Product
 ** and all variants of the product must implement this interface
 * 2nees.com
 */
interface Chair {
    public function getChairName() : string;
}

interface Sofa {
    public function getSofaName() : string;
}

interface ServiceTable {
    public function getServiceTableName() : string;
}

/**
 * Classes ModernChair, ModernSofa, ModernServiceTable, ClassicalChair, ClassicalSofa, ClassicalServiceTable
 * Various implementations of abstract products
 * 2nees.com
 */
class ModernChair implements Chair {

    public function getChairName(): string
    {
        return "Modern Chair Name";
    }
}

class ModernSofa implements Sofa {

    public function getSofaName(): string
    {
        return "Modern Sofa Name";
    }
}

class ModernServiceTable implements ServiceTable {

    public function getServiceTableName(): string
    {
        return "Modern Service Table Name";
    }
}

class ClassicalChair implements Chair {

    public function getChairName(): string
    {
        return "Classical Chair Name";
    }
}

class ClassicalSofa implements Sofa {

    public function getSofaName(): string
    {
        return "Classical Sofa Name";
    }
}

class ClassicalServiceTable implements ServiceTable {

    public function getServiceTableName(): string
    {
        return "Classical Service Table Name";
    }
}

/**
 * Client Function to print test result code
 * @param FurnitureFactory $factory
 */
function clientCode(FurnitureFactory $factory){
    echo "  
            {$factory->createChair()->getChairName()}\n
            {$factory->createSofa()->getSofaName()}\n
            {$factory->createServiceTable()->getServiceTableName()}\n
            =================================================== \n
         ";
}

clientCode(new ModernDesign());
clientCode(new ClassicalDesign());