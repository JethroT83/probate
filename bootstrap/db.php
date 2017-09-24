<?php

# Define Database Credentials
if(!defined("SERVER")){new \database\config();}


# Initiate the Database
$GLOBALS['connection'] = new \database\connection();