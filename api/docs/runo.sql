BEGIN;

DROP TABLE IF EXISTS `runo_user`,
`runo_race`,
`runo_comment`,
`runo_role`,
`runo_race_type`;

CREATE TABLE IF NOT EXISTS `runo_role` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(64) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT now(),
    `updated_at` TIMESTAMP NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS `runo_user` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `firstname` VARCHAR(64),
    `lastname` VARCHAR(64),
    `email` VARCHAR(128) NOT NULL UNIQUE KEY,
    `password` VARCHAR(128) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT now(),
    `updated_at` TIMESTAMP NOT NULL DEFAULT now(),
    `role_id` INT UNSIGNED NOT NULL,
    FOREIGN KEY (`role_id`) REFERENCES `runo_role`(`id`)
);

CREATE TABLE IF NOT EXISTS `runo_race_type` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(64) NOT NULL UNIQUE KEY,
    `created_at` TIMESTAMP NOT NULL DEFAULT now(),
    `updated_at` TIMESTAMP NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS `runo_race` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `race_type_id` INT UNSIGNED NOT NULL,
    `started_at` TIMESTAMP NOT NULL,
    `finished_at` TIMESTAMP NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT now(),
    `updated_at` TIMESTAMP NOT NULL DEFAULT now(),
    FOREIGN KEY (`user_id`) REFERENCES `runo_user` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`race_type_id`) REFERENCES `runo_race_type` (`id`)
);

CREATE TABLE IF NOT EXISTS `runo_comment` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `race_id` INT UNSIGNED NOT NULL,
    `comment` TEXT NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT now(),
    `updated_at` TIMESTAMP NOT NULL DEFAULT now(),
    FOREIGN KEY (`race_id`) REFERENCES `runo_race` (`id`) ON DELETE CASCADE
);

INSERT INTO
    `runo_role` (`id`, `title`)
VALUES
    (NULL, 'ROLE_ADMIN'),
    (NULL, 'ROLE_USER');

INSERT INTO
    `runo_user` (
        `firstname`,
        `lastname`,
        `email`,
        `password`,
        `role_id`
    )
VALUES
    (
        'pik',
        'loo',
        'p.loukakou@gmail',
        '$2y$10$.CUX6PmatPPlkmcXxZlht.5MoLM1hOgCKw1NMfxbxVeLFT3wbmkwS',
        '1'
    );

COMMIT;
