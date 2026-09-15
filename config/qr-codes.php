<?php

return [

    /*
    |----------------------------------------------------------------------
    | QR logo storage
    |----------------------------------------------------------------------
    | Uploaded logo images used by the QR code generator are stored on this
    | disk, inside the given directory. The private "local" disk works for
    | single-server deployments. When running the panel on multiple servers
    | (or rebuilding servers from scratch), point this at a shared disk
    | such as "s3" so previously uploaded logos keep working.
    */

    'disk' => env('QR_LOGO_DISK', 'local'),

    'directory' => env('QR_LOGO_DIRECTORY', 'qr-logos'),

];
