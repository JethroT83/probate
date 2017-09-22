<?php

# Define Database Credentials
new \database\config();

# Initiate the Database
$GLOBALS['connection'] = new \database\connection();