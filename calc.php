<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $depositAmount = isset($_POST['depositAmount']) ? $_POST['depositAmount'] : 0;
    $depositTerm = isset($_POST['depositTerm']) ? $_POST['depositTerm'] : 1;
    $sumAdd = isset($_POST['additionalAmount']) ? $_POST['additionalAmount'] : 0;
    $depositDate = isset($_POST['depositDate']) ? $_POST['depositDate'] : '';
    $additionalActive = isset($_POST['depositAdditional']) && $_POST['depositAdditional'] == 'yes';

    // Проверка на високосность
    $year = date('Y', strtotime($depositDate));
    $isLeapYear = (($year % 4 == 0) && ($year % 100 != 0)) || ($year % 400 == 0);

    $percent = 0.1; 
    $daysy = $isLeapYear ? 366 : 365; 
    $startDate = new DateTime($depositDate);
    $endDate = new DateTime();
    $interval = $startDate->diff($endDate);
    $yearsPassed = $interval->format('%y');
    $monthsPassed = $interval->format('%m');

    // Получаем сколько месяцев прошло с открытия вклада
    $totalMonthsPassed = $yearsPassed * 12 + $monthsPassed;

    // И выясняем сколько из этих месяцев относились к вкладу
    $depositMonthsPassed = min($totalMonthsPassed, $depositTerm * 12);

    // Получаем временные метки для сегодняшней даты и даты открытия вклада
    $todayTimestamp = strtotime(date('Y-m-d'));
    $depositTimestamp = strtotime($depositDate);
    $monthStart = strtotime(date('Y-m-01', $todayTimestamp)); 
    
    

    if (date('Ym', $todayTimestamp) === date('Ym', $depositTimestamp)) {
        $daysn = ($todayTimestamp - $depositTimestamp)/ (60 * 60 * 24);
        
    }
    else {
        $daysn = ($todayTimestamp - $monthStart)/ (60 * 60 * 24);
    }
    if ($totalMonthsPassed > $depositMonthsPassed) {
        $daysn = 0; // Если вклад уже закрыт, проценты по дням текущего месяца не считаем
    }

    $monthlyInterestRate = $percent / 12;
    
    if ($additionalActive) {
        // Если активировано ежемесячное пополнение
        $summn_1 = $depositAmount * pow((1 + $monthlyInterestRate), $depositMonthsPassed) + $sumAdd * ((pow((1 + $monthlyInterestRate), $depositMonthsPassed)-1)/$monthlyInterestRate);
    } else {
        // Если ежемесячное пополнение не активировано
        $summn_1 = $depositAmount * pow((1 + $monthlyInterestRate), $depositMonthsPassed);
    }

    $summn = $summn_1 + ($summn_1 + $sumAdd) * $daysn * ($percent / $daysy);

    $result = number_format($summn, 0);
    function replaceCommas($string) {
        return str_replace(',', ' ', $string);
    }
    
    $formattedResult = replaceCommas($result);
    echo $formattedResult;
} else {

    header("Location: index.php");
    exit();
}
?>