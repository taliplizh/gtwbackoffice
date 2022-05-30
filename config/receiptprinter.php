<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Printer connector type
    |--------------------------------------------------------------------------
    |
    | Connection protocol to communicate with the receipt printer.
    | Valid values are: cups, network, windows
    |
    */
        // windows  หากคุณใช้ Windows เป็นเว็บเซิร์ฟเวอร์ของคุณ
        // cups     หากคุณใช้ Linux หรือ Mac เป็นเว็บเซิร์ฟเวอร์ของคุณ
        // network  หากคุณกำลังใช้เครื่องพิมพ์เครือข่าย
    'connector_type' => 'windows',
    /*
    |--------------------------------------------------------------------------
    | Printer connector descriptor
    |--------------------------------------------------------------------------
    |
    | Typically printer name or IP address.
    |
    */
    'connector_descriptor' => 'CanonG2010series',
    /*
    |--------------------------------------------------------------------------
    | Printer port
    |--------------------------------------------------------------------------
    |
    | Typically 9100.
    |
    */
    'connector_port' => 9100,
];