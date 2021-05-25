<h3><strong>Описание:</h3></strong>
1 Скрипт выводит список сущностей с постраничной пагинацией.</br>
get-параметры: </br>limit - кол-во сущностей на страницу;
</br>page  - номер страницы;
</br>2 Реализован поиск по словам в описании сущности. В поисковой строке перечислить нужное кол-во слов через пробел.</br>(<em><strong>Совпадения будут найдены для каждого слова отдельно</strong></em>)
</br>3 Добавление сущностей через модальное окно.
<h3><strong>Понадобится для запуска:</h3></strong>
Apahce + mysql + php7.4
<h3><strong>Настройка:</h3></strong>
1. Добавить данные для подключения к БД в файл конфигурации App/Config/database.php
   </br>(<strong> Название таблицы в статическом свойстве класса App\Models\Entity </strong>)

![Alt text](https://i.imgur.com/TlIOhMD.png "Конфиг БД")

2. Запустить миграцию бд в консоли
<pre>
    <code>
     php bin/migrations/task_migration.php up
    </code>
</pre>
Дропнуть таблицу
<pre>
    <code>
    php bin/migrations/task_migration.php down
    </code>
</pre>

3. Заполнить таблицу тестовыми данными. Аргумент - число сущностей для создания
   </br>(<strong> При >200 faker может создать дубли - это приведёт к ошибке и остановке выполнения скрипта </strong>)
<pre>
    <code>
    php bin/migrations/task_migration.php seed 200
    </code>
</pre>