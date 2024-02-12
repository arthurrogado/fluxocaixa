<?php

// $where = [
//     "id" => 1,
//     "OR" => [
//         "nome" => "João",
//         "AND" => [
//             "idade" => [">" => 21],
//             "email" => "joao@example.com"
//         ]
//     ]
// ];

// $where = [
//    "nome" => "João",
//    "OR" => ["idade" => 21, "idade" => 22],
//    "AND" => [
//        "OR" => ["id_escritorio" => 1, "id_escritorio" => 2],
//    ]
// ];

$where = [
    "OR" => [
        "nome" => "João",
        "idade" => "21",
    ],
    "AND" => [
        // "OR" => ["id_escritorio" => 1, "id_escritorio" => 2],
        "id_escritorio" => [1, 2],
        "idade" => "22",
    ]
];

$query = "SELECT * FROM usuarios";

if (!empty($where)) {
    $query .= " WHERE ";

    $conditions = [];
    foreach ($where as $key => $value) {
        // Verifica se é uma condição composta com operadores lógicos
        if (is_array($value)) {
            $subconditions = [];
            foreach ($value as $subkey => $subvalue) {
                $subconditions[] = "$subkey = :$subkey";
            }
            $conditions[] = "(" . implode(" OR ", $subconditions) . ")";
        } else {
            $conditions[] = "$key = :$key";
        }
    }

    $query .= implode(" AND ", $conditions);
}

echo $query;