<?php
ob_start();
require_once dirname(__FILE__) . '/../../../libs/Qwin.php';
require_once dirname(__FILE__).'/../../../libs/Qwin/Event.php';

/**
 * Test class for Qwin_Event.
 * Generated by PHPUnit on 2012-02-02 at 08:56:29.
 */
class Qwin_EventTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Qwin_Event
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = Qwin::getInstance()->event;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Qwin_Event::getType
     * @covers Qwin_Event::__construct
     */
    public function testGetType()
    {
        $e = new Qwin_Event('test');

        $this->assertEquals('test', $e->getType());
    }

    /**
     * @covers Qwin_Event::getTimeStamp
     */
    public function testGetTimeStamp()
    {
        $time = microtime(true);

        $e = new Qwin_Event('test');

        $this->assertInternalType('float', $e->getTimeStamp());

        $this->assertGreaterThan($time, $e->getTimeStamp());

        $this->assertLessThan(microtime(true), $e->getTimeStamp());
    }

    /**
     * @covers Qwin_Event::call
     */
    public function testCall()
    {
        $widget = $this->object;

        // remove all envets at first
        $widget->remove();

        $widget->trigger('no such event');

        $widget->bind('test', array($this, 'setTextState'));

        $widget->bind('test', array($this, 'returnFalse'));

        $widget->bind('test', array($this, 'nothingToDo'));

        // reset state
        $this->_state = null;

        $widget->trigger('test', array(
            'state' => 'testing',
        ));

        $this->assertEquals('testing', $this->_state);
    }

    /**
     * variable for testCall method
     *
     * @var string
     */
    protected $_state;

    public function setTextState($event, $state)
    {
        $this->_state = $state;
    }

    public function nothingToDo()
    {

    }

    public function returnFalse()
    {
        return false;
    }

    /**
     * @covers Qwin_Event::add
     */
    public function testAdd()
    {
        $widget = $this->object;

        $widget->remove();

        $widget->bind('test', array($this, 'setTextState'));

        $widget->bind('test', array($this, 'nothingToDo'));

        $widget->trigger('test', array(
            'state' => 'testing',
        ));

        $this->assertEquals('testing', $this->_state);

        // test param error
        ob_start();

        $widget->error->option('exit', false);

        $widget->bind('test', 'can not be called');

        $output = ob_get_contents();
        $output && ob_end_clean();

        $this->assertContains('Parameter 2 should be a valid callback', $output);
    }

    /**
     * @covers Qwin_Event::remove
     * @covers Qwin_Event::has
     */
    public function testRemove()
    {
        $widget = $this->object;

        $widget->bind('none', array($this, 'nothingToDo'));

        $this->assertTrue($widget->has('none'));

        $widget->remove('none');

        $this->assertFalse($widget->has('none'));

        $widget->remove('test');

        $this->assertFalse($widget->has('test'));

        // remove all
        $widget->remove();
    }
}