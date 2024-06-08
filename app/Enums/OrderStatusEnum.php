<?php
namespace App\Enums;

enum OrderStatusEnum: string {
    case PENDING = 'Pending';
    case PROCESSING = 'Processing';
    case COMPLETED = 'Completed';
    case DECLINED = 'Declined';
}