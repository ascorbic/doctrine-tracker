<?php
require_once("UnitTestCase.php");

class Doctrine_BatchIteratorTestCase extends Doctrine_UnitTestCase {

    public function prepareTables() {
        $this->tables = array("Entity", "User","Group","Address","Phonenumber");
        parent::prepareTables();
    }

    public function testIterator() {
        $graph = new Doctrine_Query($this->connection);
        $entities = $graph->query("FROM Entity");
        $i = 0;
        foreach($entities as $entity) {
            $this->assertEqual(gettype($entity->name),"string");
            $i++;
        }
        $this->assertTrue($i == $entities->count());
        
        $user = $graph->query("FROM User");
        foreach($user[1]->Group as $group) {
            $this->assertTrue(is_string($group->name));
        }     
        
        $user = new User();
        $user->name = "tester";
        
        $user->Address[0]->address = "street 1";
        $user->Address[1]->address = "street 2";
        
        $this->assertEqual($user->name, "tester");
        $this->assertEqual($user->Address[0]->address, "street 1");
        $this->assertEqual($user->Address[1]->address, "street 2");

        foreach($user->Address as $address) {
            $a[] = $address->address;
        }
        $this->assertEqual($a, array("street 1", "street 2"));   

        $user->save();
        
        $user = $user->getTable()->find($user->obtainIdentifier());
        $this->assertEqual($user->name, "tester");
        $this->assertEqual($user->Address[0]->address, "street 1");
        $this->assertEqual($user->Address[1]->address, "street 2");
        
        $user = $user->getTable()->find($user->obtainIdentifier());
        $a = array();
        foreach($user->Address as $address) {
            $a[] = $address->address;
        }
        $this->assertEqual($a, array("street 1", "street 2"));                                    


        $user = $graph->query("FROM User");
    }

}
?>
