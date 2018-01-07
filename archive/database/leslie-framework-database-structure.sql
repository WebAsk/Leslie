SET FOREIGN_KEY_CHECKS = 0;

SET GLOBAL sql_mode = NO_ENGINE_SUBSTITUTION;

DROP VIEW IF EXISTS documents_view;
DROP VIEW IF EXISTS joints_view;
DROP VIEW IF EXISTS contents_view;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `documents`;
DROP TABLE IF EXISTS `items_fields`;
DROP TABLE IF EXISTS `item_types_fields`;
DROP TABLE IF EXISTS `field_types`;
DROP TABLE IF EXISTS `items_permalinks`;
DROP TABLE IF EXISTS `items_languages`;
DROP TABLE IF EXISTS `items_joints`;
DROP TABLE IF EXISTS `item_states`;
DROP TABLE IF EXISTS `items_permalinks`;
DROP TABLE IF EXISTS `languages`;
DROP TABLE IF EXISTS `items_list`;
DROP TABLE IF EXISTS `item_types`;
DROP TABLE IF EXISTS `user_types`;
DROP TABLE IF EXISTS `items`;

CREATE TABLE `items` (

    `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
    `singular` varchar(12) NOT NULL,
    `plural` varchar(12) NOT NULL,
    `contents` tinyint(1) unsigned NOT NULL DEFAULT '0',
    `joints` tinyint(1) unsigned NOT NULL DEFAULT '0',
    `documents` tinyint(1) unsigned NOT NULL DEFAULT '0',
    `sales` tinyint(1) unsigned NOT NULL DEFAULT '0',
    `icon` varchar(12) NOT NULL DEFAULT 'sitemap',
    `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
    `order` tinyint(2) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user_types` (

    `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(20) NOT NULL,
    `admin` tinyint(1) unsigned NOT NULL,
    `delete` tinyint(1) unsigned NOT NULL,
    `super` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Super user',
    `order` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Priority or level',
    PRIMARY KEY (id),
    KEY (`admin`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `item_types` (

    `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
    `item` tinyint(3) unsigned NOT NULL,
    `singular` varchar(255) NOT NULL,
    `plural` varchar(255) NOT NULL,
    `accounts` tinyint(1) unsigned NOT NULL,
    `access` tinyint(1) unsigned NOT NULL DEFAULT '3' COMMENT 'User type id access level limit',
    `notice` tinyint(1) unsigned NOT NULL,
    `view` tinyint(1) unsigned NOT NULL DEFAULT '0',
    `primary` tinyint(1) unsigned NOT NULL,
    `joint` tinyint(1) unsigned unsigned NULL,
    `intro` tinyint(1) NOT NULL,
    `description` tinyint(1) unsigned NOT NULL,
    `joints` varchar(255) NOT NULL,
    `prefix` varchar(12) DEFAULT NULL COMMENT 'Extra tables prefix name',
    `multiple` tinyint(1) NOT NULL,
    `permalink` tinyint(1) NOT NULL,
    `permanent` tinyint(1) NOT NULL,
    `active` tinyint(1) NOT NULL DEFAULT '1',
    `navigation` tinyint(1) NOT NULL DEFAULT '1',
    `order` tinyint(3) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (id),
    FOREIGN KEY (item) REFERENCES items(id),
    KEY (accounts),
    FOREIGN KEY (access) REFERENCES user_types(id),
    KEY (`view`)    

) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `items_list` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `id_type` tinyint(3) unsigned NOT NULL,
    `id_user` mediumint(8) unsigned NOT NULL,
    `name` varchar(255) NOT NULL,
    `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
    `state` tinyint(3) unsigned NOT NULL DEFAULT '0',
    `active` tinyint(1) NOT NULL DEFAULT '1',
    `order` mediumint(8) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (id),
    FOREIGN KEY (id_type) REFERENCES item_types(id),
    KEY (id_user),
    UNIQUE (code),
    KEY (active),
    KEY (`state`)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `languages` (

    `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(12) NOT NULL,
    `sign` varchar(8) NOT NULL,
    `default` tinyint(1) NOT NULL,
    `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
    PRIMARY KEY (id),
    KEY (`default`),
    KEY (active)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `item_states` (

    `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
    `item` tinyint(3) unsigned NOT NULL,
    `value` varchar(255) NOT NULL,
    `default` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Item list visibility',
    `list` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Item list visibility',
    `edit` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'item data editablity',
    `view` tinyint(1) NOT NULL DEFAULT '0',
    `permits` tinyint(4) NOT NULL DEFAULT '2',
    `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
    `order` tinyint(3) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (id),
    FOREIGN KEY (item) REFERENCES item_types(id),
    KEY (`list`),
    KEY (`edit`),
    KEY (`view`),
    KEY (permits),
    KEY (active)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `items_joints` (

    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `type` tinyint(2) unsigned NOT NULL,
    `id_joint` int(10) unsigned NOT NULL,
    `id_content` int(10) unsigned NOT NULL,
    `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
    `insert` datetime NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (`type`) REFERENCES item_types(id),
    KEY (id_joint),
    FOREIGN KEY (id_content) REFERENCES items_list(id),
    KEY (active)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `items_languages` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `id_content` int(10) unsigned NOT NULL,
    `id_language` tinyint(1) unsigned NOT NULL DEFAULT '1',
    `title` varchar(255) NOT NULL,
    `intro` varchar(255) NULL,
    `description` text NULL,
    `insert` datetime NULL,
    `update` datetime NULL,
    `views` mediumint(8) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (id),
    FOREIGN KEY (id_content) REFERENCES items_list(id),
    FOREIGN KEY (id_language) REFERENCES languages(id)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `items_permalinks` (

    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
    `item` int(10) unsigned NOT NULL COMMENT 'Item language id',
    `value` varchar(255) NOT NULL,
    `insert` datetime NULL,
    `order` int(10) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (id),
    FOREIGN KEY (`type`) REFERENCES item_types(id),
    FOREIGN KEY (item) REFERENCES items_languages(id)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `field_types` (
    `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(33) NOT NULL,
    `label` varchar(255) NOT NULL,
    `placeholder` varchar(50) NOT NULL,
    `type` varchar(8) NOT NULL DEFAULT 'text',
    `attributes` varchar(33) DEFAULT NULL,
    `required` tinyint(1) NOT NULL DEFAULT '1',
    `readonly` tinyint(1) NOT NULL DEFAULT '0',
    `default` varchar(255) DEFAULT NULL COMMENT 'Default value of field',
    `admin` tinyint(1) NOT NULL DEFAULT '1',
    `site` tinyint(1) NOT NULL DEFAULT '1',
    `icon` varchar(12) NULL,
    `order` tinyint(3) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (id),
    KEY (`admin`),
    KEY (`site`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `item_types_fields` (

    `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
    `id_field_type` tinyint(3) unsigned NOT NULL,
    `id_content_type` tinyint(3) unsigned NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_field_type) REFERENCES field_types(id),
    FOREIGN KEY (id_content_type) REFERENCES item_types(id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `items_fields` (

    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `id_type` tinyint(3) unsigned NOT NULL,
    `id_item_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
    `id_language` tinyint(1) unsigned NOT NULL,
    `id_content` int(10) unsigned NOT NULL,
    `value` text NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_item_type) REFERENCES item_types(id),
    FOREIGN KEY (id_type) REFERENCES field_types(id),
    FOREIGN KEY (id_language) REFERENCES languages(id),
    KEY (id_content)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `documents` (

    `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
    `item` tinyint(3) unsigned NOT NULL,
    `folder` varchar(12) NULL,
    `images` tinyint(1) unsigned NOT NULL DEFAULT '1',
    `width` smallint(6) unsigned NOT NULL,
    `height` smallint(6) unsigned NOT NULL,
    `icon` varchar(12) NULL DEFAULT 'file',
    `order` tinyint(2) NOT NULL DEFAULT '0',
    PRIMARY KEY (id),
    FOREIGN KEY (item) REFERENCES item_types(id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    `type` tinyint(1) unsigned NOT NULL DEFAULT '3' COMMENT 'Simple user',
    `content` int(10) unsigned NULL,
    `email` varchar(80) NOT NULL,
    `password` varchar(255) NULL,
    `code` varchar(255) NOT NULL,
    `active` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'user confirm',
    `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'manual disabling',
    `insert` datetime NULL,
    `update` datetime NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (`type`) REFERENCES user_types(id),
    KEY (`content`),
    UNIQUE (email),
    KEY (password),
    UNIQUE (code)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `sessions` (
    
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `code` varchar(64) NOT NULL,
    `user` mediumint(8) unsigned NOT NULL,
    `ip` varchar(16) NOT NULL,
    `agent` varchar(255) NOT NULL,
    `insert` datetime NOT NULL,
    `update` datetime NULL DEFAULT NULL,
    `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
    PRIMARY KEY (id),
    FOREIGN KEY (`user`) REFERENCES users(id),
    KEY (`ip`),
    KEY (`agent`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE VIEW contents_view AS SELECT 
main.id AS id,
main.code AS code,
lang.id_language AS `language`,
item_types.id AS `type`,
item_types.`primary` AS `primary`,
item_types.plural AS `type_plural`, 
item_types.singular AS `type_singular`,
GROUP_CONCAT(DISTINCT joints.id_joint) AS joints,
image.`name` AS image,
lang.title AS title,
lang.intro AS intro, 
lang.description AS description, 
permalink.`value` AS permalink,
lang.`insert` AS `insert`,
lang.`update` AS `update`,
main.`state` AS `state`,
lang.`views` AS `views`,
main.`order` AS `order`
FROM 
items,
item_types,
items_list AS main
LEFT JOIN items_joints AS joints
ON joints.id_content = main.id
LEFT JOIN items_list AS image
ON image.id IN(SELECT id_joint FROM items_joints WHERE id_content = main.id)
    AND image.id_type IN(
        SELECT item FROM documents WHERE images = 1
    ) 
LEFT JOIN item_types AS image_type
ON image_type.id = image.id_type,
items_languages AS lang
LEFT JOIN items_permalinks AS permalink
ON permalink.id = (SELECT id FROM items_permalinks WHERE item = lang.id ORDER BY `order`, id DESC LIMIT 0, 1),
users
WHERE
items.contents = 1
AND item_types.item = items.id
AND item_types.`view` = 1
AND main.id_type = item_types.id
AND main.active = 1 
AND (main.`state` = (SELECT id FROM item_states WHERE `view` = 1 AND item = main.id_type) OR main.`state` = 0)
AND lang.id_content = main.id 
GROUP BY lang.id;


CREATE VIEW joints_view AS SELECT 
main.id AS id,
main.code AS code,
lang.id_language AS `language`,
item_types.id AS `type`,
item_types.plural AS `plural`, 
images.`name` AS image,
lang.title AS title,
lang.intro AS intro, 
lang.description AS description, 
permalink.`value` AS permalink,
lang.`insert` AS `insert`,
lang.`update` AS `update`,
main.`order` AS `order`
FROM 
items,
item_types,
items_languages AS lang
LEFT JOIN items_permalinks AS permalink ON permalink.id = (
    SELECT id 
    FROM items_permalinks 
    WHERE item = lang.id 
    ORDER BY `order`, id DESC
    LIMIT 1
),
items_list AS main
LEFT JOIN items_list AS images

    ON images.id = (
        SELECT id FROM items_list
        WHERE id IN(
            SELECT id_joint 
            FROM items_joints 
            WHERE id_content = main.id
            AND id_type = (
                SELECT item_types.id FROM items, item_types 
                WHERE items.documents = 1 
                AND item_types.item = items.id 
                AND item_types.plural = 'images'
                ORDER BY item_types.`order`, item_types.id LIMIT 0, 1
            )
        )
        ORDER BY `order` LIMIT 0, 1
    )
WHERE
items.joints = 1
AND item_types.item = items.id 
AND item_types.`view` = 1
AND main.id_type = item_types.id
AND main.active = 1 
AND (
    main.`state` IN(
        SELECT id FROM item_states 
        WHERE `view` = 1 
        AND item = main.id_type
    ) 
    OR main.`state` = 0
)
AND lang.id_content = main.id;



CREATE VIEW documents_view AS SELECT 
main.id AS id,
main.code AS code,
lang.id_language AS `language`,
item_types.id AS `type`,
item_types.plural AS `plural`, 
main.`name` AS `name`,
lang.title AS title,
lang.intro AS intro, 
lang.description AS description,
main.`order` AS `order`
FROM 
items,
item_types,
items_languages AS lang,
items_list AS main 
WHERE
items.documents = 1
AND item_types.item = items.id
AND item_types.`view` = 1
AND main.id_type = item_types.id
AND main.active = 1
AND (main.`state` = (SELECT id FROM item_states WHERE `view` = 1 AND item = main.id_type) OR main.`state` = 0)
AND lang.id_content = main.id;

SET FOREIGN_KEY_CHECKS = 1;