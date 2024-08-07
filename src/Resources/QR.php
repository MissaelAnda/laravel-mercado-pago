<?php

namespace MissaelAnda\MercadoPago\Resources;

/**
 * @method static setImage(string $image)
 * @method static setTemplateDocument(string $templateDocument)
 * @method static setTemplateImage(string $templateImage)
 */
class QR extends Resource
{
    public string $image;
    public string $templateDocument;
    public string $templateImage;
}
