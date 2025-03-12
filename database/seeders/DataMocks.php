<?php

namespace Database\Seeders;

class DataMocks
{
    public static function getEmployees(): array
    {
        return [
            ['id' => 3, 'email' => 'employee1@email.com', 'unit_id' => 1],
            ['id' => 4, 'email' => 'employee2@email.com', 'unit_id' => 2],
            ['id' => 5, 'email' => 'employee3@email.com', 'unit_id' => 2],
        ];
    }

    public static function getEmployeesIdList(): array
    {
        return array_column(self::getEmployees(), 'id');
    }

    public static function getCompanyId(): int
    {
        return 1;
    }

    public static function getUnitIds(): array
    {
        return [1, 2];
    }
}
