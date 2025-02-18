<?php 
require_once('../config/credentials.php');
require_once('../config/constants.php');
require_once('../config/api_config.php');
require_once('../config/disclaimers.php');

// Validate input
$serialNumber = trim($_POST['serialNumber']);
$serialNumber = filter_var($serialNumber, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if($serialNumber === false || $serialNumber === null): die(ERROR_MSG['SERIAL_REQUIRED']); endif;
if(!preg_match('/^\d{10}$/', $serialNumber)): die(ERROR_MSG['SERIAL_INVALID']); endif;

// API call function
function makeApiCall($url, $data, $apiCredentials) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        CURLOPT_USERPWD => $apiCredentials['username'].':'.$apiCredentials['password']
    ));
    $response = json_decode(curl_exec($curl), true);
    curl_close($curl);
    return $response;
}

// CTR detection function
function isCTR($modelNumber) {
    return isset($modelNumber[0]) && in_array($modelNumber[0], ['S', 'L']);
}

// Validate serial number API call
$data = json_encode(['SerialNumber'=>$serialNumber, 'Email'=>'']);
$response = makeApiCall(API_BASE_URL . API_ENDPOINTS['VALIDATE_SERIAL'], $data, $apiCredentials);
if($response['Message'] == 'An Error has occurred'): die(ERROR_MSG['SERIAL_NOT_VALID']); endif;
if(!isset($response)): die(ERROR_MSG['UNEXPECTED']); endif;

$modelNumber = $response['ModelNumber'];
$retailCode = $response['RetailProductCode'];
$productModel = $response['ProductModel'];
$isRegister = $response['IsRegistered']==true?'Yes':'No';

// Check warranty API call
$response = makeApiCall(API_BASE_URL . API_ENDPOINTS['CHECK_WARRANTY'], '"'.$serialNumber.'"', $apiCredentials);
if(!isset($response)): die(ERROR_MSG['UNEXPECTED']); endif;
if($response['RegistrationSource'] == 'Commercial') {
    die(ERROR_MSG['COMMERCIAL']);
} elseif($response['Message'] == 'An error has occured') {
    die(ERROR_MSG['MISSING']);
}
?>
<table>
    <tr>
        <td>Is the product registered?</td>
        <td><?=$isRegister?></td>
    </tr>
    <tr>
        <td>Model number</td>
        <td><?=$modelNumber?></td>
    </tr>
    <tr>
        <td>Serial number</td>
        <td><?=$serialNumber?></td>
    </tr>
    <tr>
        <td>Product model</td>
        <td><?=$productModel?></td>
    </tr>
    <tr>
        <td>Installation / Purchase Date</td>
        <td><?=$response['InstallationDate']?></td>
    </tr>
    <tr>
        <td>Days left in warranty</td>
        <td>
            <?php
                if($response['WarrantyActive'] === true) {
                    echo $response['DaysRemaining'];
                } else {
                    echo 'Warranty expired';
                }
            ?>
        </td>
    </tr>
</table>
<p class="disclaimer">
    <?php
    // Handling disclaimers
    if($response['WarrantyActive'] && $isRegister=='Yes' && !isCtr($modelNumber)) {
        echo $disclaimers['1'];
    } elseif($response['WarrantyActive'] && $isRegister=='No' && !isCtr($modelNumber)) {
        echo $disclaimers['2'];
    } elseif(!$response['WarrantyActive'] && $isRegister=='Yes' && !isCtr($modelNumber)) {
        echo $disclaimers['3'];
    } elseif(!$response['WarrantyActive'] && $isRegister=='No' && !isCtr($modelNumber)) {
        echo $disclaimers['4'];
    } elseif($response['WarrantyActive'] && $isRegister=='Yes' && isCtr($modelNumber)) {
        echo $disclaimers['5'];
    } elseif($response['WarrantyActive'] && $isRegister=='No' && isCtr($modelNumber)) {
        echo $disclaimers['6'];
    } elseif(!$response['WarrantyActive'] && $isRegister=='Yes' && isCtr($modelNumber)) {
        echo $disclaimers['7'];
    } elseif(!$response['WarrantyActive'] && $isRegister=='No' && isCtr($modelNumber)) {
        echo $disclaimers['8'];
    }
    ?>
</p>