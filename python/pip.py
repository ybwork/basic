Команды.

    pip freeze -l > requirements.txt создаем requirements.txt в корне проекта (для всех установленных уже
    библиотек; находимся в виртуальном окружении при выполнении команды, иначе создаст список из питоньих библиотек, стоящих в системе):

    pip freeze -l посмотреть пакеты (-l - локальные)

    pip show django посмотреть версию пакета django

    pip install -r requirements.txt установить пакеты

    pip install -U -r requirements.txt обновить (переустановить) пакеты

    pip install --upgrade pip обновить сам pip

    pip install -e <path> установить пакет для разработки (создает симлинк)