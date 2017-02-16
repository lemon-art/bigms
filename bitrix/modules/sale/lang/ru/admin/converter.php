<?php
$MESS['SALE_CONVERTER_STEP_BY_STEP_MANAGER'] = "Мастер перехода на новую систему Управления заказами";
$MESS['SALE_CONVERTER_MESSAGE_TITLE'] = "Пошаговая конвертация Интернет-магазина";
$MESS['SALE_CONVERTER_MODULE_NOT_INSTALL'] = "Модуль Интернет-магазина не установлен";
$MESS['SALE_CONVERTER_ENTRY'] = "Для перехода на новую версию Интернет-магазина необходима конвертация данных.<br><br>
	Новая версия включает грандиозные изменения, позволяющие удобно управлять заказами и экономить время менеджера на взаимодействие с клиентом. Менеджер выглядит более компетентным в глазах клиента.<br><br>
	Технология управления заказами доработана и усовершенствована. Вы можете частично отгрузить заказ без его разделения или принять оплату по частям, в одном заказе, несколькими способами.<br><br>
	Менеджер видит все скидки по заказу, легко управляет ими на странице заказа и может в любое время внести изменения или пересчитать итоговую сумму.<br><br>
	Новые возможности в Управление заказами позволят удобно управлять заказом на всех стадиях. Быстро перемещаться даже по огромным заказам, состоящим из десятков документов отгрузок и оплат, содержащим большое количество наименований. Самая важная информация о заказе всегда перед глазами, в «шапке» документа. Это важно в работе интернет-магазина.";

$MESS['SALE_CONVERTER_AJAX_STEP_ORDER'] = "Изменение структуры таблицы заказов";
$MESS['SALE_CONVERTER_AJAX_STEP_PROPS_VALUE'] = "Изменение структуры таблицы значений свойств заказа";
$MESS['SALE_CONVERTER_AJAX_STEP_PROPS'] = "Изменение структуры таблицы свойств заказа";
$MESS['SALE_CONVERTER_AJAX_STEP_4'] = "Изменение структуры существующих таблиц";
$MESS['SALE_CONVERTER_AJAX_STEP_5'] = "Создание новых таблиц";
$MESS['SALE_CONVERTER_AJAX_STEP_DISCOUNT'] = "Скидки";
$MESS['SALE_CONVERTER_AJAX_STEP_OTHER_ALTERS'] = "Изменение структуры таблиц остальных сущностей";
$MESS['SALE_CONVERTER_AJAX_STEP_BIZVAL'] = "Физические смыслы";
$MESS['SALE_CONVERTER_AJAX_STEP_DELIVERY'] = "Службы доставок и отгрузки";
$MESS['SALE_CONVERTER_AJAX_STEP_OTHER_CREATE'] = "Создание таблиц для оставшихся сущностей";
$MESS['SALE_CONVERTER_AJAX_STEP_COPY_FILES'] = "Копирование файлов модуля";
$MESS['SALE_CONVERTER_AJAX_STEP_DELIVERY_CONVERT'] = "Конвертация служб доставок";
$MESS['SALE_CONVERTER_AJAX_STEP_STATUS_CONVERT'] = "Миграция свойств и статусов";
$MESS['SALE_CONVERTER_AJAX_STEP_PAY_SYSTEM_INNER'] = "Создание платежной системы: 'Внутренний счет'";
$MESS['SALE_CONVERTER_AJAX_STEP_PAY_SYSTEM_INNER_ERROR'] = "Ошибка при создании внутреннего счета";
$MESS['SALE_CONVERTER_AJAX_STEP_INSTALLER'] = "Инсталлятор";
$MESS['SALE_CONVERTER_AJAX_STEP_INSERT_PAYMENT'] = "Заполнение таблицы частичных оплат";
$MESS['SALE_CONVERTER_AJAX_STEP_INSERT_SHIPMENT'] = "Заполнение таблицы отгрузок";
$MESS['SALE_CONVERTER_AJAX_STEP_INSERT_SHIPMENT_BASKET'] = "Заполнение таблицы корзины отгрузок";
$MESS['SALE_CONVERTER_AJAX_STEP_UPDATE_SHIPMENT_BASKET_BARCODE'] = "Перепривязка штрихкодов";
$MESS['SALE_CONVERTER_AJAX_STEP_UPDATE_ORDER_PAYMENT'] = "Обновление оплаченных заказов";
$MESS['SALE_CONVERTER_AJAX_STEP_TRANSACT'] = "Изменение структуры таблицы транзакций";
$MESS['SALE_CONVERTER_AJAX_STEP_ORDER_CHANGE'] = "Изменение структуры таблицы истории заказа";
$MESS['SALE_CONVERTER_AJAX_STEP_FINAL'] = "Мастер конвертации завершил свою работу<br><br>Проверьте наличие доступных обновлений и обновитесь <br>до последней версии.";
$MESS['SALE_CONVERTER_AJAX_STEP_FINAL_MESSAGE'] = "Конвертация окончена";
$MESS['SALE_CONVERTER_BUTTON_START_AJAX'] = "Начать конвертацию";
$MESS['SALE_CONVERTER_BUTTON_START'] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Далее&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$MESS['SALE_CONVERTER_BUTTON_NEXT'] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Далее&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$MESS['SALE_CONVERTER_BUTTON_REPEAT'] = "Повторить шаг";
$MESS['SALE_CONVERTER_STEP_1_MESSAGE'] = "Внимание!";
$MESS['SALE_CONVERTER_STEP_1_DETAILS'] = "<b>Внимание!</b><br><br>
	<b style='color:red; font-size:15px;'>Перед запуском процесса настоятельно рекомендуем сделать резервную копию магазина!</b><br><br>
	Во время перехода на новую систему Управления заказами интернет-магазин будет не доступен. При большом количестве заказов процесс перехода может занять продолжительное время. Рекомендуется провести процедуру в часы наименьшей нагрузки на магазин.<br><br>
	Перед запуском процесса настоятельно рекомендуем сделать <b>резервную копию</b> магазина.<br><br>
	После завершения, документы частичной оплаты и отгрузки не будут выгружаться в 1С. Поддержка изменений в нашем модуле 1С будет добавлена чуть позже, в очередном обновлении, до выхода стабильной версии магазина.<br><br>
	Модуль для 1С можно будет найти по ссылке: <a href=\"http://1c.1c-bitrix.ru/ecommerce/download.php\">http://1c.1c-bitrix.ru/ecommerce/download.php</a><br><br>
	Во время перехода к новой системе Управления заказами будет закрыта публичная часть. В случае ошибок, можно открыть ее в ручном режиме:<br>
	<i>Настройки->Настройка модулей->Главный модуль->Открыть публичную часть</i>";

$MESS['SALE_CONVERTER_STEP_2_MESSAGE'] = "Внимание!";
$MESS['SALE_CONVERTER_STEP_2_DETAILS'] = "<b>Внимание!</b><br><br>После конвертации магазина обработчики указанных ниже событий вызываться не будут. Переходите на следующий шаг только если вы <b>полностью</b> уверены, что это не изменит логику работы вашего магазина. Для разъяснений обратитесь к разработчикам магазина или в службу технической поддержки.";
$MESS['SALE_CONVERTER_STEP_3_MESSAGE'] = "Конвертация местоположений";
$MESS['SALE_CONVERTER_STEP_3_DETAILS'] = "Новая система Управления заказами требует переход к местоположениям 2.0.<br><br>
	Для полного пошагового контроля выполнения перехода на новые местоположения перейдите на страницу <a href='sale_location_migration.php?lang=ru'>Переход на местоположения 2.0.</a> После установки местоположений, повторно запустите Мастер перехода на новую систему Управления заказами.<br><br>
	Для автоматического выполнения перехода к новым местоположениям нажмите \"Далее &gt;&gt;\"";

$MESS['SALE_CONVERTER_STEP_4_MESSAGE'] = "Местоположения cконвертированы";
$MESS['SALE_CONVERTER_STEP_4_DETAILS'] = "Процесс перехода к местоположениям 2.0 успешно завершен";
$MESS['SALE_CONVERTER_STEP_FIND_GARBAGE'] = "<b>Внимание!</b><br><br>Обнаружены следы предыдущих попыток конвертации. База данных находится не в актуальном состоянии. Для исправления ситуации в автоматическом режиме, нажмите \"Далее &gt;&gt;\"";
$MESS['SALE_CONVERTER_STEP_CLEAR_GARBAGE'] = "Ошибки базы данных исправлены";
$MESS['SALE_CONVERTER_STEP_5_MESSAGE'] = "Выбор службы доставки по-умолчанию";
$MESS['SALE_CONVERTER_STEP_6_MESSAGE'] = "Конвертация Интернет-магазина";
$MESS['SALE_CONVERTER_STEP_5_DETAILS'] = "Ваш магазин использует складской учет. <br>В магазине присутствуют отгружненные заказы, однако в некоторых из них не выбрана служба доставки.<br> Для избежания потери данных выберите службу доставки, которая применится ко <br>всем заказам, без службы доставки";
$MESS['SALE_CONVERTER_STEP_6_DETAILS'] = "После нажатия кнопки \"Начать конвертацию\" запустится пошаговый процесс перехода к новой системе Управления заказами. <br><br>
	Обратите внимание! До полного завершения процесса интернет-магазин будет не доступен. При большом количестве заказов переход может занять продолжительное время. Рекомендуем провести процедуру в часы наименьшей нагрузки на магазин.<br><br>	
	Для технических специалистов, во время перехода будет выполнено: <br>
	<ul>
	<li>изменение структуры таблицы заказов,</li>
	<li>изменение структуры таблицы значений свойств заказа,</li>
	<li>изменение структуры таблицы свойств заказа,</li>
	<li>создание новых таблиц,</li>
	<li>обновление скидок,</li>
	<li>конвертация служб доставок и отгрузок,</li>
	<li>копирование файлов модуля,</li>
	<li>миграция свойств и статусов,</li>
	<li>миграция данных.</li>
	</ul>
	<b>Пожалуйста, дождитесь завершения процесса.</b>";

$MESS['SALE_CONVERTER_COPY_FILES_ERROR'] = 'Ошибка при копировании файлов из #FROM# в #TO#';
$MESS['SALE_CONVERTER_CHOOSE_DELIVERY_SERVICE'] = '';
$MESS['SALE_CONVERTER_MODULE_DO_NOT_SUPPORT'] = 'Модуль intranet временно не поддерживается';
$MESS['SALE_CONVERTER_DB_DO_NOT_SUPPORT'] = 'Базы данных Oracle и MSSQL временно не поддерживается';
$MESS['SALE_CONVERTER_AJAX_STEP_UPDATE_REPORT'] = 'Миграция отчетов';
$MESS['SALE_CONVERTER_EMPTY_DELIVERY_SERVICE'] = 'Без доставки';
$MESS['SALE_CONVERTER_TITLE'] = 'Мастер перехода на новую систему Управления заказами';
$MESS['SALE_CONVERTER_AJAX_STEP_UPDATE_BASKET'] = 'Изменение структуры таблицы: корзина';
$MESS['SALE_CONVERTER_ADMIN_NOTIFY_CONVERT_BASKET_DISCOUNT'] = "Необходимо сконвертировать информацию о купонах торгового каталога для существующих заказов. Для этого перейдите на страницу <a href=\"#LINK#\">настроек модуля</a> в раздел Служебные процедуры (закладка Конвертация данных)";
$MESS['SALE_CONVERTER_MODULE_NO_ACCESS'] = "Недостаточно прав для выполнения конвертации";