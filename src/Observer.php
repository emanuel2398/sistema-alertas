<?php 

// User.php
interface Observer {
    public function update(Alert $alert);
}