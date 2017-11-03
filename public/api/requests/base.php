<?php
//Base request, required on each request to make sure that it's not directly accessed
if(empty($access)) {
    die("No direct access allowed! (Request level)");
}