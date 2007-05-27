<?php
/*
 *  $Id$
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.phpdoctrine.com>.
 */

/**
 * Doctrine_Query_Cache_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.phpdoctrine.com
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Query_Cache_TestCase extends Doctrine_UnitTestCase 
{
    public function testParserCacheAddsQueriesToCache()
    {
        $q = new Doctrine_Query();

        $cache = new Doctrine_Cache_Array();
        $q->setOption('parserCache', $cache);
        $q->select('u.name')->from('User u');

        $q->getQuery();

        $this->assertEqual($cache->count(), 1);
    }
    public function testResultSetCacheAddsResultSetsIntoCache()
    {
        $q = new Doctrine_Query();

        $cache = new Doctrine_Cache_Array();
        $q->setOption('resultSetCache', $cache);
        $q->select('u.name')->from('User u');
        $coll = $q->execute();

        $this->assertEqual($cache->count(), 1);
        $this->assertTrue($coll instanceof Doctrine_Collection);
        $this->assertEqual($coll->count(), 8);

        $coll = $q->execute();

        $this->assertEqual($cache->count(), 1);
        $this->assertTrue($coll instanceof Doctrine_Collection);
        $this->assertEqual($coll->count(), 8);
    }
    public function testResultSetCacheAddsResultSetsIntoCache2()
    {
        $q = new Doctrine_Query();

        $cache = new Doctrine_Cache_Array();
        $q->setOption('resultSetCache', $cache);
        $q->select('u.name')->from('User u')->leftJoin('u.Phonenumber p');
        $coll = $q->execute();

        $this->assertEqual($cache->count(), 1);
        $this->assertTrue($coll instanceof Doctrine_Collection);
        $this->assertEqual($coll->count(), 8);

        $coll = $q->execute();

        $this->assertEqual($cache->count(), 1);
        $this->assertTrue($coll instanceof Doctrine_Collection);
        $this->assertEqual($coll->count(), 8);
    }
}
