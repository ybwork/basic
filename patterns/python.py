'''
    Порождающие шаблоны. (Singleton - Одиночка)

    У класса есть только один экземпляр, и он предоставляет к нему глобальную точку доступа. При попытке создания данного объекта он создаётся только в том случае, если ещё не существует, в противном случае возвращается ссылка на уже существующий экземпляр и нового выделения памяти не происходит.
'''
class OnlyOne:
    class __OnlyOne:
        def __init__(self, arg):
            self.val = arg

        def __str__(self):
            return repr(self) + self.val

    instance = None

    def __init__(self, arg):
        if not OnlyOne.instance:
            OnlyOne.instance = OnlyOne.__OnlyOne(arg)
        else:
            OnlyOne.instance.val = arg

    def __getattr__(self, name):
        return getattr(self.instance, name)

x = OnlyOne('sausage')
print(x)
y = OnlyOne('eggs')
print(y)
z = OnlyOne('spam')
print(z)
print(x)
print(y)
print(`x`)
print(`y`)
print(`z`)

'''
    Структурные шаблоны. (Facade - Фасад)

    Это шаблон проектирования, позволяющий скрыть сложность системы путём сведения всех возможных внешних вызовов к одному объекту.

    Пример: включение компьютера для пользователя происходит при нажатии кнопки пуск. Но после нажатия этой кнопки происходят другие операции, которые скрыты от пользователя.
'''
class CPU(object):
    def __init__(self):
        pass

    def freeze(self):
        pass

    def jump(self, address):
        pass

    def execute(self):
        pass

class Memory(object):
    def __init__(self):
        pass

    def load(self, position, data):
        pass

class HardDrive(object):
    def __init__(self):
        pass

    def read(self, lba, size):
        pass

# Фасад
class Computer(object):
    def __init__(self):
        self._cpu = CPU()
        self._memory = Memory()
        self._hardDrive = HardDrive()

    def startComputer(self):
        self._cpu.freeze()
        self._memory.load()
        self._cpu.jump()
        self._cpu.execute()

# Клиентская часть
if __name__ == "__main__":
    facade = Computer()
    facade.startComputer()


'''
    Поведенческие шаблоны. (Strategy - Стратегия)

    Шаблон стратегия позволяет переключаться между алгоритмами или стратегиями в зависимости от ситуации.
'''
class StrategyExample:
    def __init__(self, func=None):
        self.name = 'Strategy Example 0'
        if func is not None:
            self.execute = types.MethodType(func, self)

    def execute(self):
        print(self.name)


def execute_replacement1(self):
    print(self.name + ' from execute 1')


def execute_replacement2(self):
    print(self.name + ' from execute 2')


if __name__ == '__main__':
    strat0 = StrategyExample()

    strat1 = StrategyExample(execute_replacement1)
    strat1.name = 'Strategy Example 1'

    strat2 = StrategyExample(execute_replacement2)
    strat2.name = 'Strategy Example 2'

    strat0.execute() # Strategy Example 0
    strat1.execute() # Strategy Example 1 from execute 1
    strat2.execute() # Strategy Example 2 from execute 2