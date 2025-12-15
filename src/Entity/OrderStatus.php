<?php

namespace App\Entity;

enum OrderStatus: string
{
    case PENDING = 'en préparation';
    case SHIPPED = 'expédiée';
    case DELIVERED = 'livrée';
    case CANCELLED = 'annulée';
}