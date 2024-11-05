<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];
// Разбиение ФИО
// getPartsFromFullname принимает как аргумент одну строку — склеенное ФИО. Возвращает как результат массив из трёх элементов с ключами 'surname', 'name', 'patronomyc'.

function getPartsFromFullname($name) {
    $a = ['surname', 'name', 'patronomyc'];
    $b = explode(' ', $name);
    return array_combine($a, $b);
}

foreach ($example_persons_array as $value) {
    $name = $value['fullname'];
    print_r(getPartsFromFullname($name));
}
echo PHP_EOL;

// Объединение ФИО
// getFullnameFromParts принимает как аргумент три строки — фамилию, имя и отчество. Возвращает как результат их же, но склеенные через пробел.

$surname = 'Иванов';
$name = 'Иван';
$patronomyc = 'Иванович';

function getFullnameFromParts($surname, $name, $patronomyc) {
    return $surname . ' ' . $name . ' ' . $patronomyc;
}
echo (getFullnameFromParts($surname, $name, $patronomyc)) . PHP_EOL;
echo PHP_EOL;

// Сокращение ФИО
// Функция getShortName, принимает как аргумент строку, содержащую ФИО вида «Иванов Иван Иванович»
// и возвращающую строку вида «Иван И.», где сокращается фамилия и отбрасывается отчество.
// Для разбиения строки на составляющие используется функция getPartsFromFullname.

function getShortName($name) {
    $arr = getPartsFromFullname($name);
    $firstName = $arr['name'];
    $surname = $arr['surname'];
    return $firstName . ' ' . mb_substr($surname, 0, 1) . '.';
}

foreach ($example_persons_array as $value) {
    $name = $value['fullname'];
    echo getShortName($name) . PHP_EOL;
}
echo PHP_EOL;

// Функция определения пола по ФИО
// Функция getGenderFromName, принимает как аргумент строку, содержащую ФИО (вида «Иванов Иван Иванович»).
// Определение производится следующим образом:
// внутри функции делим ФИО на составляющие с помощью функции getPartsFromFullname;
// изначально «суммарный признак пола» считаем равным 0;
// если присутствует признак мужского пола — прибавляем единицу, если женского — отнимаем единицу.
// после проверок всех признаков, если «суммарный признак пола»:
// больше нуля — возвращаем 1 (мужской пол); меньше нуля — возвращаем -1 (женский пол); равен 0 — возвращаем 0 (неопределенный пол).
// Признаки мужского пола: отчество заканчивается на «ич»; имя заканчивается на «й» или «н»; фамилия заканчивается на «в».
// Признаки женского пола: отчество заканчивается на «вна»; имя заканчивается на «а»; фамилия заканчивается на «ва»;

function getGenderFromName($name) {
    $arr = getPartsFromFullname($name);
    $surname = $arr['surname'];
    $firstName = $arr['name'];
    $patronomyc = $arr['patronomyc'];
    $sumGender = 0;

    if (mb_substr($surname, -1, 1) === 'в') {
        $sumGender++;
    } elseif (mb_substr($surname, -2, 2) === 'ва') {
        $sumGender--;
    }
    
    if ((mb_substr($firstName, -1, 1) == 'й') || (mb_substr($firstName, -1, 1) == 'н')) {
        $sumGender++;
    } elseif (mb_substr($firstName, -1, 1) === 'а') {
        $sumGender--;
    }
   
    if (mb_substr($patronomyc, -2, 2) === 'ич') {
        $sumGender++;
    } elseif (mb_substr($patronomyc, -3, 3) === 'вна') {
        $sumGender--;
    }

    return ($sumGender <=> 0);
}

foreach ($example_persons_array as $value) {
    $name = $value['fullname'];
    echo getGenderFromName($name) . PHP_EOL;
}
echo PHP_EOL;

// Массив только определенного пола на основе массива $example_persons_array
$new_persons_array = array_filter($example_persons_array, function ($example_persons_array) {
    if (getGenderFromName($example_persons_array['fullname']) !== 0) {
        return $example_persons_array;
    }
});


// Автоматическое формирование аргументов для функции getPerfectPartner
$allPersonsArray = count($example_persons_array);
$numNameRand = rand(0, $allPersonsArray - 1);
$personRand = $example_persons_array[$numNameRand]['fullname'];

$surname = getPartsFromFullname($personRand)['surname'];
$name = getPartsFromFullname($personRand)['name'];
$patronomyc = getPartsFromFullname($personRand)['patronomyc'];

// Определение возрастно-полового состава
// Функция getGenderDescription принимает как аргумент массив $example_persons_array.
// Как результат функции возвращается информация о гендерном составе аудитории.
// Используется функция фильтрации элементов массива, функция подсчета элементов массива, функция getGenderFromName, округление.

function getGenderDescription($persons) {

    $men = array_filter($persons, function ($persons) {
        $fullname = $persons['fullname'];
        $genderMen = getGenderFromName($fullname);
        if ($genderMen > 0) {
            return $genderMen;
        }
    });

    $women = array_filter($persons, function ($persons) {
        $fullname = $persons['fullname'];
        $genderWomen = getGenderFromName($fullname);
        if ($genderWomen < 0) {
            return $genderWomen;
        }
    });

    $failedGender = array_filter($persons, function ($persons) {
        $fullname = $persons['fullname'];
        $genderFailed = getGenderFromName($fullname);   // 0
        if ($genderFailed == 0) {                       // true
            return $genderFailed + 1;                   // 0 + 1 = 1
            // при этом если "неопределенного пола" не будет, то значение не будет выведено изначально в функции getGenderFromName($fullname)
        }
    });

    // в результате выполнения функции по определению пола - getGenderFromName($name)
    $allMan = count($men);                       // подсчитываются все мужчины
    $allWomen = count($women);                   // подсчитываются все женщины
    $allFailedGender = count($failedGender);     // подсчитываются все чей пол не определился
    $allPiople = $allMan + $allWomen + $allFailedGender;   // можно было записать: $allPiople = count($persons);

    $percentMen = round((100 / $allPiople * $allMan), 0);
    $percentWomen = round((100 / $allPiople * $allWomen), 0);
    $percenFailedGender = round((100 / $allPiople * $allFailedGender), 0);

    return <<< HEREDOC
    Гендерный состав аудитории:
    ---------------------------
    Мужчины - $percentMen%
    Женщины - $percentWomen%
    Неудалось определить - $percenFailedGender%
    HEREDOC;
}
echo getGenderDescription($example_persons_array) . PHP_EOL;
echo PHP_EOL;

// Идеальный подбор пары


$surname = 'Шварцнегер';
$name = 'Арнольд';
$patronomyc = 'Густавович';

function getPerfectPartner($surname, $name, $patronomyc, $persons) {

    $surnameNorm = mb_convert_case($surname, MB_CASE_TITLE_SIMPLE);
    $nameNorm = mb_convert_case($name, MB_CASE_TITLE_SIMPLE);
    $patronomycNorm = mb_convert_case($patronomyc, MB_CASE_TITLE_SIMPLE);

    $fullNameNorm = getFullnameFromParts($surnameNorm, $nameNorm, $patronomycNorm);  // полное имя главного имени
    $shortNameNorm = getShortName($fullNameNorm);                                    // сокращенное имя главного имени
    $genderFullNameNorm = getGenderFromName($fullNameNorm);                          // пол главного имени в виде: -1 0 1

    if ($genderFullNameNorm == 0) {
        return "Заданы аргументы неопределенного пола";
    }

    // проверка противоположности пола
    do {
        $personsNumRand = array_rand($persons);
        $personFullNameRand = $persons[$personsNumRand]['fullname'];         // полное имя случайного имени
        $personFullNameRandGender = getGenderFromName($personFullNameRand);  // пол случайного имени в виде: -1 0 1
    } while (($genderFullNameNorm == $personFullNameRandGender) || ($personFullNameRandGender == 0));

    $personShortNameRand = getShortName($personFullNameRand);   // сокращенное имя случайного имени
    $percentPerfect = rand(5000, 10000) / 100;                  // от 50% до 100%
    
    return <<< HEREDOC
    $shortNameNorm + $personShortNameRand =
    ♡ Идеально на $percentPerfect% ♡
    HEREDOC;
}
echo getPerfectPartner($surname, $name, $patronomyc, $example_persons_array) . PHP_EOL;

