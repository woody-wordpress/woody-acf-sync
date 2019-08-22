<?php

/**
 * Plugin Name: Woody ACF Sync
 * Plugin URI: https://github.com/woody-wordpress/woody-acf-sync
 * Version: 1.3.3
 * Description: A WP CLI command to synchronize ACF fields
 * Author: Raccourci Agency
 * Author URI: https://www.raccourci.fr
 * License: GPL2
 *
 * This program is GLP but; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of.
 */

class Woody_ACF_Sync_Command
{
    public function sync($args, $assoc_args)
    {
        $groups = acf_get_field_groups();
        $sync   = [];

        if (empty($groups)) {
            return WP_CLI::success("No ACF Sync required");
        }

        foreach ($groups as $group) {
            $local    = acf_maybe_get($group, 'local', false);
            $modified = acf_maybe_get($group, 'modified', 0);
            $private  = acf_maybe_get($group, 'private', false);

            if ($local !== 'json' || $private) {
                // do nothing
            } elseif (!$group['ID']) {
                $sync[$group['key']] = $group;
            } elseif ($modified && $modified > get_post_modified_time('U', true, $group['ID'], true)) {
                $sync[$group['key']] = $group;
            }
        }

        if (empty($sync)) {
            return WP_CLI::success("No ACF Sync required");
        }

        if (!empty($sync)) {
            foreach ($sync as $key => $v) {
                if (acf_have_local_fields($key)) {
                    $sync[$key]['fields'] = acf_get_local_fields($key);
                }
                acf_import_field_group($sync[$key]);
            }
            WP_CLI::success('ACF has been successfully synchronized');
        }
    }
}

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::add_command('acf', 'Woody_ACF_Sync_Command');
}
