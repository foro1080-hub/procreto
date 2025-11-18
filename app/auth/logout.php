<?php
session_start();
session_unset();
session_destroy();
header('Location: /pretecor');
exit;
