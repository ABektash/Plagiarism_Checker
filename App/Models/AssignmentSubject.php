<?php
interface AssignmentSubject {
    public function addObserver(AssignmentObserver $observer): void;
    public function removeObserver(AssignmentObserver $observer): void;
    public function notifyObservers(string $message): void;
}
?>
