<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$to = 'test@debug.com';
$subject = 'test';
$message = 
        '<html>
        <head>
        <title>AU mon dieu ! un titre</title>
        </head>
        <body>
        <p> et voila le vaiseau blague a tex</p>
        </body>
        </html>';
        
        
mail($to, $subject, $message, implode("\rn\n", $headers));

