<?php
namespace App\Enums;
enum OrderStatus: string
{
    case Done = "DONE";
    case Processing = "PROCESSING";
    case Being_Delivered = "BEING_DELIVERED";
}
?>