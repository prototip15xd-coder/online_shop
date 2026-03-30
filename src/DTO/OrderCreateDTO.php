<?php

namespace DTO;

class OrderCreateDTO
{

// что за пустотая строка тут была?
    // PSR-12: Если параметры конструктора не помещаются в одну строку,
    // каждый параметр должен быть на отдельной строке с одним уровнем отступа (4 пробела),
    // а закрывающая скобка ) и открывающая { — на новой строке.
    // Текущее выравнивание по открывающей скобке — нарушение PSR-12.
    //
    // Правильный формат:
    // public function __construct(
    //     private string $contactName,
    //     private string $contactPhone,
    //     private string $comment,
    //     private string $address,
    //     private ?int $userId = null,
    // ) {
    // }
    //
    // PSR-12: Перед = в значении по умолчанию должен быть пробел: ?int $userId = null
    // PSR-12: Открывающая и закрывающая фигурные скобки метода должны быть на отдельных строках.
    // {}  в конце строки с параметрами — нарушение.
    public function __construct(private string $contactName,
                                private string $contactPhone,
                                private string $comment,
                                private string $address,
                                private ?int $userId= null){}


    public function getContactName(): string
    {
        return $this->contactName;
    }

    public function getContactPhone(): string
    {
        return $this->contactPhone;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    // Тип возвращаемого значения указан как int, но свойство $userId объявлено как ?int (nullable).
    // Если userId не задан (null), этот метод вызовет ошибку при возврате.
    // Исправь тип возвращаемого значения на ?int
    public function getUserId(): int
    {
        return $this->userId;
    }
}