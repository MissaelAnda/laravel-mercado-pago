<?php

namespace MissaelAnda\MercadoPago\Resources\Point;

enum OperatingMode: string
{
    case PDV = 'PDV';
    case STANDALONE = 'STANDALONE';
}
