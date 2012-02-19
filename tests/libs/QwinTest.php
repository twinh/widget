<?php
require_once dirname(__FILE__) . '/../../libs/Qwin.php';

/**
 * Test class for Qwin.
 * Generated by PHPUnit on 2012-01-16 at 08:33:55.
 */
class QwinTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Qwin
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     * @covers Qwin::getInstance
     * @covers Qwin::__construct
     */
    protected function setUp() {
        $GLOBALS['q'] = 'my var q';
        $this->object = Qwin::getInstance(array(
            'Qwin' => array(
                'autoloadDirs' => array(
                    './not/found/paths',
                ),
            ),
        ));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {

    }

    /**
     * @covers Qwin::autoload
     * @covers Qwin_Get::__construct
     */
    public function testAutoload() {
        if (class_exists('Qwin_Get', false)) {
            $this->markTestSkipped();
        }
        $this->assertEquals(class_exists('Qwin_Get', false), false, 'Class "Qwin_Get" not found.');

        $this->object->autoload('Qwin_Get');

        $this->assertEquals(class_exists('Qwin_Get'), true, 'Class "Qwin_Get" found.');

        $this->assertFalse($this->object->autoload('Class not found.'));
    }

    /**
     * @covers Qwin::widget
     * @covers Qwin_Get::__construct
     */
    public function testWidget() {
        $get = $this->object->get;

        $this->assertEquals(get_class($get), 'Qwin_Get', 'Class of Widget "get" is "Qwin_Get"');

        // for code cover
        $get2 = $this->object->get->get;

        $this->assertEquals(get_class($get2), 'Qwin_Get');

        $this->setExpectedException('Qwin_Exception');

        $this->object->_widgetNotFound;
    }

    /**
     * @covers Qwin::__invoke
     */
    public function test__invoke() {
        $std = $this->object->__invoke('stdClass');

        $this->assertEquals(get_class($std), 'stdClass', 'Init class stdClass');

        $this->assertEquals($std, $this->object->__invoke('stdClass'), 'Class has been called');

        $this->assertFalse($this->object->__invoke('Class not found'));

        $this->assertEquals('MySingleton', get_class($this->object->__invoke('MySingleton')), 'Singleton mode');

        $this->assertEquals('MyOneParam', get_class($this->object->__invoke('MyOneParam', array(1))), 'Pass one param');

        $this->assertEquals('MyTwoParams', get_class($this->object->__invoke('MyTwoParams', array(1, 2))), 'Pass two params');

        $this->assertEquals('MyThreeParams', get_class($this->object->__invoke('MyThreeParams', array(1, 2, 3))), 'Pass three params');

        $this->assertEquals('MyFourParams', get_class($this->object->__invoke('MyFourParams', array(1, 2, 3, 4))), 'Pass four params');

        $this->assertEquals('MyFourParams2', get_class($this->object->__invoke('MyFourParams2', array(1, 2, 3, 4))), 'Pass four params and class with constructor');

        $this->assertEquals('MyFourParams3', get_class($this->object->__invoke('MyFourParams3', array(1, 2, 3, 4))), 'Pass four params and class with php4 constructor');
    }

    /**
     * @covers Qwin::config
     */
    public function testConfig() {
        // clean all config
        $this->object->config(array());

        $this->assertEmpty($this->object->config(), 'Config is empty');

        $config = $this->object->config('user/my/username', 'twin');

        $this->assertEquals('twin', $config, 'Set config');

        $config2 = $this->object->config('user/my/username');

        $this->assertEquals('twin', $config2, 'Get config');

        $this->assertEmpty($this->object->config(new stdClass()));
    }

    /**
     * @covers Qwin::getInstance
     * @covers Qwin::__construct
     */
    public function testGetInstance() {
        $widget = $this->object;

        $this->assertEquals(Qwin::getInstance(), $this->object, 'Class only has one instance');

        // get instance and append array config
        $config = array(
            '_key1' => 'value1',
            '_key2' => 'value2',
        );

        Qwin::getInstance($config);

        $this->assertEquals('value1', $widget->config('_key1'), 'get instance and append array config');

        // get instance and append config file
        $file = './_qwin_config.php';
        file_put_contents($file, '<?php return array("_key3" => "value3");');

        Qwin::getInstance($file);

        $this->assertEquals('value3', $widget->config('_key3'), 'get instance and append config file');

        unlink($file);

        // invalid parameter
        $this->setExpectedException('Qwin_Exception');

        Qwin::getInstance('file should not found.');
    }

    /**
     * @covers Qwin::__construct
     */
    public function test__construct()
    {
        try {
            $origPath = get_include_path();
            set_include_path('.');
            $q = new Qwin();
        } catch (Qwin_Exception $e) {

        }
        set_include_path($origPath);

        // one instance only
        $this->setExpectedException('Qwin_Exception');

        new Qwin();
    }

    /**
     * @covers Qwin::invokeWidget
     */
    public function testInvokeWidget() {
        $widget = $this->object;

        $name = $this->object->invokeWidget($this->object, 'post', array('name'));

        $this->assertEquals(class_exists('Qwin_Post'), true, 'Class "Qwin_Post" found.');

        $this->assertTrue($widget->isNull(null), 'check if is null');

        $this->setExpectedException('Qwin_Exception');

        /* @var $var Qwin_ClassWithoutCallMethod */
        $var = $widget->classWithoutCallMethod();
    }

    /**
     * Covers function qiwn and q
     */
    /*public function testFunctionQwin()
    {
        $this->assertInstanceOf('Qwin_Widget', qwin());
        $this->assertInstanceOf('Qwin_Widget', q());
    }*/

    /**
     * @covers Qwin::setAutoloadOption
     */
    public function testSetAutoloadOption()
    {
        $widget = $this->object;

        $widget->setAutoloadOption(false);

        $this->assertNotContains(array($widget, 'autoload'), spl_autoload_functions());

        $widget->setAutoloadOption(true);

        $this->assertContains(array($widget, 'autoload'), spl_autoload_functions());
    }

    /**
     * @covers Qwin::setAutoloadDirsOption
     */
    public function testSetAutoloadDirsOption()
    {
        $widget = $this->object;

        $widget->setAutoloadDirsOption(array());

        $reflection = new ReflectionClass('Qwin');

        // autoload paths contain directory separator
        $dir = dirname($reflection->getFileName()) . DIRECTORY_SEPARATOR;

        $customDir = dirname(__FILE__) . DIRECTORY_SEPARATOR;

        $widget->setAutoloadDirsOption(array(
            $customDir
        ));

        $this->assertContains($customDir, $widget->option('autoloadDirs'), 'custom directory');

        $this->assertContains($dir, $widget->option('autoloadDirs'), '"Qwin" class directory');
    }
}

// class for test
class MySingleton
{
    protected static $_instance;

    protected function __construct()
    {

    }

    public static function getInstance()
    {
        if (self::$_instance) {
            return self::$_instance;
        }
        return self::$_instance = new MySingleton();
    }
}

class MyOneParam
{

}

class MyTwoParams
{

}

class MyThreeParams
{

}

class MyFourParams
{

}

class MyFourParams2
{
    public function __construct()
    {

    }
}

 class MyFourParams3
 {
     public function MyFourParams3()
     {

     }
 }

 class Qwin_ClassWithoutCallMethod
 {

 }
