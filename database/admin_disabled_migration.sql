-- Run this ONCE against the room_sched_db database (via phpMyAdmin or the
-- mysql command line) BEFORE the updated functions.php / room_fetch.php
-- files are used. It adds a new column that tracks the admin's manual
-- Shutdown/Activate decision, completely separate from tablet_status and
-- heartbeat.update (which the employee kiosk always keeps as
-- Active/'yes' on its own).

ALTER TABLE `rooms`
  ADD COLUMN `admin_disabled` ENUM('yes','no') NOT NULL DEFAULT 'no' AFTER `tablet_status`;
