<?php

/*
    Strategy
    
    Facade

    Abstract Factory

    Singleton
*/

/*
    Шаблон Strategy (композиция)

    Проблема:   
        Абстрактный класс Lesson на рис. 8.1 моделирует занятие в колледже. Он определяет абстрактные методы cost() и chargeType(). На диаграмме показаны два реализующих их класса, FixedPriceLesson и TimedPriceLesson, которые обеспечивают разные механизмы оплаты занятий. Но что произойдет. если нужно будет ввести новый набор специализаций? Предположим, нам нужно работать с такими элементами, как лекции и семинары. Поскольку они подразумевают разные способы регистрации учащихся и создания рабочих материалов к занятиям, для них нужны отдельные классы. Поэтому теперь у нас есть две движущие силы проекта: нам нужно работать со стратегиями оплаты и разделить лекции и семинары. (см. димаграмму на стр. 183) Однако есть решение, но оно в итоге сводиться к дублированию кода.
*/
abstract class Lesson
{
    abstract function cost();
    abstract function charge_type();
}

class Lecture extends Lesson
{
    // Реализация
}

class Seminar extends Lesson
{
    // Реализация
}

// Проблема в дублировании больших кусков
class TimedCostStrategy extends Lecture
{
    // Реализация
}

class FixedCostStrategy extends Lecture
{
    // Реализация
}

class TimedCostStrategy extends Seminar
{
    // Реализация
}

class FixedCostStrategy extends Seminar
{
    // Реализация
}

/*
    Решение проблемы (Схема на стр. 185):
*/
abstract class Lesson
{
    private $duration;
    private $cost_strategy;

    public function __construct($duration, CostStrategy $strategy)
    {
        $this->duration = $duration;
        $this->cost_strategy = $strategy;
    }

    public function cost()
    {
        // Делегирование - явный вызов метода другого объекта для выполнения запроса
        return $this->cost_strategy->cost($this);
    }

    public function charge_type()
    {
        return $this->cost_strategy->charge_type();
    }

    public function get_duration()
    {
        return $this->duration();
    }

    // other methods this class
}

class Lecture extends Lesson
{
    // Реализация
}

class Seminar extends Lesson
{
    // Реализация
}

abstract class CostStrategy
{
    abstract function cost(Lesson $lesson);
    abstract function charge_type();
}

class TimedCostStrategy extends CostStrategy
{
    public function cost(Lesson $lesson)
    {
        return ($lesson->get_duration() * 5);
    }

    public function charge_type()
    {
        return 'почасовая оплата';
    }
}

class FixedCostStrategy extends CostStrategy
{
    public function cost(Lesson $lesson)
    {
        return 30;
    }

    public function charge_type()
    {
        return 'фиксированная оплата';
    }
}

$lessons[] = new Seminar(4, new TimedCostStrategy());
$lessons[] = new Lecture(4, new FixedCostStrategy());

foreach ($lessons as $lesson) {
    print 'Плата за занятие: ' . $lesson->cost();
    print 'Тип оплаты: ' . $lesson->charge_type();
}

/*
    Разделение и ослабление связи. Имеет смысл создавать независимые компоненты, поскольку систему. состоящую из зависимых классов, как правило, гораздо труднее сопровождать.

    Проблема:
        Повторное использование - одна из основных целей объектно-ориентированного проектирования, и тесная связь- враг этой цели. 

        Тесная связь имеет место, когда изменение в одном компоненте системы ведет к необходимости вносить множество изменений повсюду.

        В качестве примера представьте себе, что в нашу систему автоматизации учебного процесса нужно включить регистрационный компонент, в задачи которого входит добавление к системе новых занятий. Процедура регистрации должна предусматривать рассылку уведомлений администратору после добавления нового занятия. При этом пользователи вашей системы никак не могут решить, в каком виде эти уведомления должны рассылаться - по электронной почте или в виде коротких текстовых сообщений. По сути, они так любят спорить, что вы вполне можете ожидать от них изменения формы коммуникаций в недалеком будущем.

    Решение (Ниже приведен фрагмент кода, в котором детали реализации конкретной системы уведомления скрыты от кода, который ее использует):
*/
class RegistrationMgr
{
    function register(Lesson $lesson)
    {
        // скрытие реализации конкретной системы уведомления от кода, который ее использует
        $notifier = Notifier::get_notifier();
        $notifier->inform('Новое занятие ' . 'стоимость: ' . $lesson->cost());
    }
}

////////////////////////////////////////////////////////////////////////////////////

/*
    Шаблон Facade.

    Как правило, первый уровень отвечает за логику приложения, второй - за взаимодействие с базой данных, третий - за представление данных и т.п. Вы должны стремиться поддерживать эти уровни независимыми один от другого, насколько это возможно, чтобы изменение в одной части проекта минимально отражалось на других частях. Если код одного уровня тесно интегрирован в код другого уровня, то трудно будет достичь этой цели.

    Несмотря на простоту шаблона Facade, очень легко забыть воспользоваться им, особенно если вы знакомы с подсистемой, с которой работаете. Но, конечно, тут необходимо найти нужный баланс. С одной стороны, преимущества создания простых интерфейсов для сложных систем очевидны. С другой стороны, можно необдуманно разделить системы, а затем разделить разделения.

    Плохой код:
*/
$lines = getProductFileLines('text.txt');

$object = array();

foreach ($lines as $line) {
    $id = getIdFromLine($line);
    $name = getNameFromLine($line);
    $objects[$id] = getProductObjectFromId($id, $name);
}

/*
    Хороший код:
*/
class CPU
{
    public function freeze() {}
    public function jump($position) {}
    public function execute() {}
}

class Memory
{
    const BOOT_ADDRESS = 0x0005;
    public function load($position, $data) {}
}

class HardDrive
{
    const BOOT_SECTOR = 0x001;
    const SECTOR_SIZE = 64;
    public function read($lba, $size) {}
}

class Computer
{
    protected $cpu;
    protected $memory;
    protected $hardDrive;

    public function __construct()
    {
        $this->cpu = new CPU();
        $this->memory = new Memory();
        $this->hardDrive = new HardDrive();
    }

    public function startComputer()
    {
        $cpu = $this->cpu;
        $memory = $this->memory;
        $hardDrive = $this->hardDrive;

        $cpu->freeze();
        $memory->load(
            $memory::BOOT_ADDRESS,
            $hardDrive->read($hardDrive::BOOT_SECTOR, $hardDrive::SECTOR_SIZE)
        );

        $cpu->jump($memory::BOOT_ADDRESS);
        $cpu->execute();
    }
}

$computer = new Computer();
$computer->startComputer();

/////////////////////////////////////////////////////////////////////////////////////////////////

/*
    Шаблон Abstract Factory

    В больших приложениях вам. возможно, понадобятся фабрики, которые генерируют связанные наборы классов. Именно эту проблему решает данный шаблон.

    Давайте еще раз рассмотрим пример с реализацией личного дневника. Мы написали код для двух форматов, BloggsCal и MegaCal. Мы можем легко нарастить эту структуру в горизонтальном направлении, добавив дополнительные форматы для кодирования. Но как нам нарастить ее вертикально, добавив кодировщики для различных типов объектов дневника? На самом деле мы уже работаем по этому шаблону. Параллельные семейства продуктов, с которыми нам предстоит работать это - встречи (Appt), "что сделать"' (Ttd) и контакты (Contact).

    Решение:
*/
abstract class ApptEncoder 
{
    abstract function encode();
}

class BloggsApptEncoder extends ApptEncoder 
{
    function encode()
    {

    }
}

class BloggsTtdEncoder extends ApptEncoder 
{
    function encode()
    {

    }
}

class BloggsTtdEncoder extends ApptEncoder 
{
    function encode()
    {

    }
}

class getContactEncoder extends ApptEncoder 
{
    function encode()
    {

    }
}

abstract class CommsManager
{
    abstract function getHeaderText();
    abstract function getApptEncoder();
    abstract function getFooterText();
    abstract function getTtdEncoder();
    abstract function getContactEncoder();
    abstract function getFooterText();
}

class BloggsCommsManager extends CommsManager
{
    function getHeaderText()
    {
        return "BloggsCal верхний колонтитул";
    }

    function getApptEncoder()
    {
        return new BloggsApptEncoder();
    }

    function getTtdEncoder()
    {
        return new BloggsTtdEncoder();
    }

    function getContactEncoder()
    {
        return new BloggsContactEncoder();
    }

    function getFooterText()
    {
        return "BloggsCal нижний колонтитул";
    }
}
/*
    Так что дает нам шаблон Abstract Factory?

    Во-первых. мы отделили нашу систему от деталей реализации. Мы можем добавлять или удалять любое количество кодирующих форматов в нашем примере, не опасаясь каких-либо проблем.

    Во-вторых, мы ввели в действие группировку функционально связанных элементов нашей системы. Поэтому при использовании BloggsCommsManager есть гарантия, что мы будем работать только с классами, связанными с BloggsCal.

    Используя шаблон Factory Method, мы определяем четкий интерфейс и заставляем все конкретные объекты фабрики подчиняться ему.

    Этот шаблон управляет созданием объектов, но они откладывают решение о том, какой объект (или группа объектов) должен быть создан.
*/

////////////////////////////////////////////////////////////////////////////////////////////////

/*
    Шаблон Singleton.

    глобальная переменная - это один из самых больших источников проблем для программиста, использующего ООП. Глобальные переменные привязывают классы к их контексту, подрывая основы инкапсуляции. Если в классе используется глобальная переменная. то его невозможно извлечь из одного приложения и применить в другом, не убедившись сначала, что в новом приложении определяются такие же глобальные переменные. Мы уже видели. что РНР уязвим к конфликтам между именами классов, но это гораздо хуже. РНР не предупредит вас. когда произойдет конфликт глобальных переменных. Вы узнаете об этом только тогда. когда сценарий начнет вести себя не так. как обычно. А еще хуже, если вы вообще не заметите никаких проблем при разработке кода.

    Задачи:
        - Объект Preferences должен быть доступен для любого объекта в системе.

        - Объект Preferences не должен сохраняться в глобальной переменной, значение которой может быть случайно затерто.

        В системе не должно быть больше одного объекта Preferences. Это означает. что объект У устанавливает свойство в объекте Preferences, а объект Z извлекает то же самое значение этого свойства. причем они не связываются один с другим непосредственно (мы предполагаем. что оба объекта имеют доступ к объекту Preferences)
*/
class Prefereces
{
    private $props = array();
    private static $instance;

    // закрываем возможность создать объект
    private function __construct() {}

    public static function get_instance()
    {
        if (empty(self::$instance)) {
            // создаём объект через посредника
            self::$instance = new Preferences();
        }

        return self::$instance;
    }

    public function set_property($key, $val)
    {
        $this->props[$key] = $val;
    }

    public function get_property($key)
    {
        return $this->props[$key];
    }
}

$preferences = Prefereces::get_instance();
/*
    Классы Singleton должны использоваться редко и очень осторожно. Шаблоны Singleton - это шаг вперед по сравнению с использованием глобальных переменных в объектно-ориентированном контексте. Вы не сможете затереть объекты Singleton неправильными данными.
*/

