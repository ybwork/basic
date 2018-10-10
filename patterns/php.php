<?php

/*
    Порождающие шаблоны. (Singleton - Одиночка)

    У класса есть только один экземпляр, и он предоставляет к нему глобальную точку доступа. При попытке создания данного объекта он создаётся только в том случае, если ещё не существует, в противном случае возвращается ссылка на уже существующий экземпляр и нового выделения памяти не происходит.
*/
class Singleton
{
    private $props = array();
    private static $instance;

    private function __construct() {}

    public static function get_instance()
    {
        if (empty(self::$instance)) {
            self::$instance = new Singleton();
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

$singleton = Singleton::get_instance();


/*
    Структурные шаблоны. (Facade - Фасад)

    Это шаблон проектирования, позволяющий скрыть сложность системы путём сведения всех возможных внешних вызовов к одному объекту.

    Пример: включение компьютера для пользователя происходит при нажатии кнопки пуск. Но после нажатия этой кнопки происходят другие операции, которые скрыты от пользователя.
*/
class Computer
{
    public function getElectricShock()
    {
        
    }

    public function makeSound()
    {
    
    }

    public function showLoadingScreen()
    {

    }

    public function ready()
    {

    }

    public function closeEverything()
    {

    }

    public function sooth()
    {

    }

    public function pullCurrent()
    {

    }
}

class ComputerFacade
{
    protected $computer;

    public function __construct(Computer $computer)
    {
        $this->computer = $computer;
    }

    public function turnOn()
    {
        $this->computer->getElectricShock();
        $this->computer->makeSound();
        $this->computer->showLoadingScreen();
        $this->computer->bam();
    }

    public function turnOff()
    {
        $this->computer->closeEverything();
        $this->computer->pullCurrent();
        $this->computer->sooth();
    }
}

$computer = new ComputerFacade(new Computer());
$computer->turnOn();
$computer->turnOff();

/*
    Поведенческие шаблоны. (Strategy - Стратегия)

    Шаблон стратегия позволяет переключаться между алгоритмами или стратегиями в зависимости от ситуации.
*/
interface SortStrategy
{
    public function sort(array $dataset): array;
}

class BubbleSortStrategy implements SortStrategy
{
    // Обычная ортировка
    public function sort(array $dataset): array
    {
        return $dataset;
    }
}

class QuickSortStrategy implements SortStrategy
{
    // Быстрая сортировка
    public function sort(array $dataset): array
    {
        return $dataset;
    }
}

class Sorter
{
    protected $sorter;

    public function __construct(SortStrategy $sorter)
    {
        $this->sorter = $sorter;
    }

    public function sort(array $dataset): array
    {
        return $this->sorter->sort($dataset);
    }
}

$dataset = [1, 5, 4, 3, 2, 8];

$sorter = new Sorter(new BubbleSortStrategy());
$sorter->sort($dataset);

$sorter = new Sorter(new QuickSortStrategy());
$sorter->sort($dataset);
