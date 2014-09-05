
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- section
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `section`;

CREATE TABLE `section`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `visible` TINYINT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- section_i18n
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `section_i18n`;

CREATE TABLE `section_i18n`
(
    `id` INTEGER NOT NULL,
    `locale` VARCHAR(5) DEFAULT 'en_US' NOT NULL,
    `title` VARCHAR(255),
    `description` LONGTEXT,
    PRIMARY KEY (`id`,`locale`),
    CONSTRAINT `section_i18n_FK_1`
        FOREIGN KEY (`id`)
        REFERENCES `section` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
