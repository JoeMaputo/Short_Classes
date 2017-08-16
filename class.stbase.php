<?php

/*
* @autor Martynuk Andrew <j-map@mail.ru>
*/

class StBase 
{
	private static $host='localhost';
	private static $base='имя_базы_данных';
	private static $user='имя_пользователя';
	private static $pass='пароль';
	private static $msql;
	
	public static function fetch_r($r) {
		if(is_object($r)){
			$i = 0;
			$arr = array();
			while ($r->data_seek($i)) {
				$arr[$i] = $r->fetch_row();
				$i++;
			}
			mysqli_next_result(self::$msql);
			return $arr;
		} else {
			return $r;	
		}
	}
	
	public static function fnc($f, $p) {
		if(self::$msql === NULL) {
			self::$msql = mysqli_connect(self::$host, self::$user, self::$pass, self::$base);
			mysqli_query(self::$msql, 'SET NAMES UTF8');
		}
		mysqli_multi_query(self::$msql, "CALL $f($p)");
		$result = mysqli_store_result(self::$msql);
		$r = self::fetch_r($result);
		return $r;			
	}
	
	public static function sel($tb_name, $column, $wh) {
		if(self::$msql === NULL) {
			self::$msql = mysqli_connect(self::$host, self::$user, self::$pass, self::$base);
			mysqli_query(self::$msql, 'SET NAMES UTF8');
		}
		$result = mysqli_query(self::$msql, "SELECT $column FROM $tb_name $wh");
		$r = self::fetch_r($result);
		return $r;
	}
	
	public static function ins($tb_name, $column, $values) {
		if(self::$msql === NULL) {
			self::$msql = mysqli_connect(self::$host, self::$user, self::$pass, self::$base);
			mysqli_query(self::$msql, 'SET NAMES UTF8');
		}
		mysqli_query(self::$msql, "INSERT INTO $tb_name ($column) VALUES ($values)");
		$result = mysqli_insert_id(self::$msql);
		return $result;
	}
	
	public static function upd($tb_name, $set, $wh) {
		if(self::$msql === NULL) {
			self::$msql = mysqli_connect(self::$host, self::$user, self::$pass, self::$base);
			mysqli_query(self::$msql, 'SET NAMES UTF8');
		}
		mysqli_query(self::$msql, "UPDATE $tb_name SET $set WHERE $wh");
	}
	
	public static function dlt($tb_name, $wh) {
                if(self::$msql === NULL) {
			self::$msql = mysqli_connect(self::$host, self::$user, self::$pass, self::$base);
			mysqli_query(self::$msql, 'SET NAMES UTF8');
		}
                mysqli_query(self::$msql, "DELETE FROM $tb_name WHERE $wh");
	}
        
	public static function trtbl($tb_name){
		if(self::$msql === NULL) {
			self::$msql = mysqli_connect(self::$host, self::$user, self::$pass, self::$base);
			mysqli_query(self::$msql, 'SET NAMES UTF8');
		}
		mysqli_query(self::$msql, "TRUNCATE TABLE $tb_name");
	}
}

/* ИНСТРУКЦИЯ
*
* В данном классе реализован минимальный функционал для работы с базой данных MySQL
* Для его использования достаточно внедрить данный файл директивой require_once в ваш скрипт
* 
* После чего можно производить следующие операции:
*
* ВЫБОРКА ДАННЫХ
*
* $result = StBase::sel('имя_таблицы', 'список требуемых атрибутов через запятую', 'условие выборки, сортировка, ограничение и т.п.');
* 
* Данный метод возвращает двумерный индексный массив. Первый индекс указывает на строку результата, второй на столбец результата
* Пример:
* $result = StBase::sel('users', 'name, age, email', 'WHERE first_name = "Петров" ORDER BY name LIMIT 0,30');
*
*
* ВНЕСЕНИЕ ДАННЫХ
*
* $result = StBase::ins('имя_таблицы', 'список атрибутов через запятую', 'список значений в том же порядке, что и атрибуты, через запятую и не забывайте про кавычки для строковых и т.п. значений');
*
* Возвращаемое значение - числовой индекс последней вставленной строки
* Пример:
* $result1 = StBase::ins('users', 'first_name, name, age, email', '"Иванов", "Иван", 23, "vano@mail.ru"');
* 
* $result2 = StBase::ins('users', 'first_name, name, age, email', '"Сидоров", "Сидор", 45, "sidor@bk.ru"),("Петров", "Петр", 27, "info@petrov.ru"');
*
* ОБНОВЛЕНИЕ ДАННЫХ
*
* StBase::upd('имя_таблицы', 'атрибут1=значение, атрибут2=значение ...', 'условие');
*
* Данный метод не возвращает никакого значения
* Пример:
* StBase::upd('users','age=24, email="vano24@mail.ru"','id=1234');
*
* 
* ВЫЗОВ ПРОЦЕДУР
*
* $result = StBase::fnc('имя процедуры','список параметров через запятую');
* 
* Данный метод изначально предназначен для выполнения процедур выборки данных, поэтому возвращаемый результат - двумерный индексный массив как и в методе sel
* Пример:
* $result = StBase::fnc('show_user_messsages','"Иванов","Иван"');
*
*
* УДАЛЕНИЕ СТРОКИ ТАБЛИЦЫ
* 
* StBase::dlt('имя таблицы', 'условие');
*
* Метод не возвращает значения
* Пример:
* StBase::dlt('users', 'id=1234');
*
* 
* ОЧИСТКА ТАБЛИЦЫ
*
* StBase::trtbl('имя таблицы');
* 
* Метод не возвращает значения
* Пример:
* StBase::trtbl('user_messages');
*/
