<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'view_role',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 01:06:29',
                'updated_at' => '2025-01-06 01:06:29',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'view_any_role',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 01:06:30',
                'updated_at' => '2025-01-06 01:06:30',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'create_role',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 01:06:30',
                'updated_at' => '2025-01-06 01:06:30',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'update_role',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 01:06:30',
                'updated_at' => '2025-01-06 01:06:30',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'delete_role',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 01:06:30',
                'updated_at' => '2025-01-06 01:06:30',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'delete_any_role',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 01:06:30',
                'updated_at' => '2025-01-06 01:06:30',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'view_marketing',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 01:13:46',
                'updated_at' => '2025-01-06 01:13:46',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'view_perusahaan',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 01:13:46',
                'updated_at' => '2025-01-06 01:13:46',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'view_any_marketing',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 01:57:03',
                'updated_at' => '2025-01-06 01:57:03',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'view_any_perusahaan',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 01:57:19',
                'updated_at' => '2025-01-06 01:57:19',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'create_perusahaan',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 02:17:03',
                'updated_at' => '2025-01-06 02:17:03',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'update_perusahaan',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 02:17:03',
                'updated_at' => '2025-01-06 02:17:03',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'restore_perusahaan',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 02:17:03',
                'updated_at' => '2025-01-06 02:17:03',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'restore_any_perusahaan',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 02:17:03',
                'updated_at' => '2025-01-06 02:17:03',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'replicate_perusahaan',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 02:17:03',
                'updated_at' => '2025-01-06 02:17:03',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'reorder_perusahaan',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 02:17:03',
                'updated_at' => '2025-01-06 02:17:03',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'delete_perusahaan',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 02:17:03',
                'updated_at' => '2025-01-06 02:17:03',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'delete_any_perusahaan',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 02:17:03',
                'updated_at' => '2025-01-06 02:17:03',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'force_delete_perusahaan',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 02:17:03',
                'updated_at' => '2025-01-06 02:17:03',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'force_delete_any_perusahaan',
                'guard_name' => 'web',
                'created_at' => '2025-01-06 02:17:03',
                'updated_at' => '2025-01-06 02:17:03',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'create_marketing',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'update_marketing',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'restore_marketing',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'restore_any_marketing',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'replicate_marketing',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'reorder_marketing',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'delete_marketing',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'delete_any_marketing',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'force_delete_marketing',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'force_delete_any_marketing',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'view_pekerjaan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'view_any_pekerjaan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'create_pekerjaan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'update_pekerjaan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'restore_pekerjaan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'restore_any_pekerjaan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'replicate_pekerjaan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            37 => 
            array (
                'id' => 38,
                'name' => 'reorder_pekerjaan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            38 => 
            array (
                'id' => 39,
                'name' => 'delete_pekerjaan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            39 => 
            array (
                'id' => 40,
                'name' => 'delete_any_pekerjaan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            40 => 
            array (
                'id' => 41,
                'name' => 'force_delete_pekerjaan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:25',
                'updated_at' => '2025-02-09 03:55:25',
            ),
            41 => 
            array (
                'id' => 42,
                'name' => 'force_delete_any_pekerjaan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 03:55:26',
                'updated_at' => '2025-02-09 03:55:26',
            ),
            42 => 
            array (
                'id' => 43,
                'name' => 'view_activitylog',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            43 => 
            array (
                'id' => 44,
                'name' => 'view_any_activitylog',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            44 => 
            array (
                'id' => 45,
                'name' => 'create_activitylog',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            45 => 
            array (
                'id' => 46,
                'name' => 'update_activitylog',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            46 => 
            array (
                'id' => 47,
                'name' => 'restore_activitylog',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            47 => 
            array (
                'id' => 48,
                'name' => 'restore_any_activitylog',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            48 => 
            array (
                'id' => 49,
                'name' => 'replicate_activitylog',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            49 => 
            array (
                'id' => 50,
                'name' => 'reorder_activitylog',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            50 => 
            array (
                'id' => 51,
                'name' => 'delete_activitylog',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            51 => 
            array (
                'id' => 52,
                'name' => 'delete_any_activitylog',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            52 => 
            array (
                'id' => 53,
                'name' => 'force_delete_activitylog',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            53 => 
            array (
                'id' => 54,
                'name' => 'force_delete_any_activitylog',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            54 => 
            array (
                'id' => 55,
                'name' => 'view_arsip',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            55 => 
            array (
                'id' => 56,
                'name' => 'view_any_arsip',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            56 => 
            array (
                'id' => 57,
                'name' => 'create_arsip',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            57 => 
            array (
                'id' => 58,
                'name' => 'update_arsip',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            58 => 
            array (
                'id' => 59,
                'name' => 'restore_arsip',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            59 => 
            array (
                'id' => 60,
                'name' => 'restore_any_arsip',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            60 => 
            array (
                'id' => 61,
                'name' => 'replicate_arsip',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            61 => 
            array (
                'id' => 62,
                'name' => 'reorder_arsip',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            62 => 
            array (
                'id' => 63,
                'name' => 'delete_arsip',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            63 => 
            array (
                'id' => 64,
                'name' => 'delete_any_arsip',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            64 => 
            array (
                'id' => 65,
                'name' => 'force_delete_arsip',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            65 => 
            array (
                'id' => 66,
                'name' => 'force_delete_any_arsip',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            66 => 
            array (
                'id' => 67,
                'name' => 'view_item::arsip',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            67 => 
            array (
                'id' => 68,
                'name' => 'view_any_item::arsip',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            68 => 
            array (
                'id' => 69,
                'name' => 'create_item::arsip',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            69 => 
            array (
                'id' => 70,
                'name' => 'update_item::arsip',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            70 => 
            array (
                'id' => 71,
                'name' => 'delete_item::arsip',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            71 => 
            array (
                'id' => 72,
                'name' => 'delete_any_item::arsip',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            72 => 
            array (
                'id' => 73,
                'name' => 'publish_item::arsip',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            73 => 
            array (
                'id' => 74,
                'name' => 'view_pelanggan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:30',
                'updated_at' => '2025-02-09 04:00:30',
            ),
            74 => 
            array (
                'id' => 75,
                'name' => 'view_any_pelanggan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            75 => 
            array (
                'id' => 76,
                'name' => 'create_pelanggan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            76 => 
            array (
                'id' => 77,
                'name' => 'update_pelanggan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            77 => 
            array (
                'id' => 78,
                'name' => 'restore_pelanggan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            78 => 
            array (
                'id' => 79,
                'name' => 'restore_any_pelanggan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            79 => 
            array (
                'id' => 80,
                'name' => 'replicate_pelanggan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            80 => 
            array (
                'id' => 81,
                'name' => 'reorder_pelanggan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            81 => 
            array (
                'id' => 82,
                'name' => 'delete_pelanggan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            82 => 
            array (
                'id' => 83,
                'name' => 'delete_any_pelanggan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            83 => 
            array (
                'id' => 84,
                'name' => 'force_delete_pelanggan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            84 => 
            array (
                'id' => 85,
                'name' => 'force_delete_any_pelanggan',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            85 => 
            array (
                'id' => 86,
                'name' => 'view_user',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            86 => 
            array (
                'id' => 87,
                'name' => 'view_any_user',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            87 => 
            array (
                'id' => 88,
                'name' => 'create_user',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            88 => 
            array (
                'id' => 89,
                'name' => 'update_user',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            89 => 
            array (
                'id' => 90,
                'name' => 'restore_user',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            90 => 
            array (
                'id' => 91,
                'name' => 'restore_any_user',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            91 => 
            array (
                'id' => 92,
                'name' => 'replicate_user',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            92 => 
            array (
                'id' => 93,
                'name' => 'reorder_user',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            93 => 
            array (
                'id' => 94,
                'name' => 'delete_user',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            94 => 
            array (
                'id' => 95,
                'name' => 'delete_any_user',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            95 => 
            array (
                'id' => 96,
                'name' => 'force_delete_user',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
            96 => 
            array (
                'id' => 97,
                'name' => 'force_delete_any_user',
                'guard_name' => 'web',
                'created_at' => '2025-02-09 04:00:31',
                'updated_at' => '2025-02-09 04:00:31',
            ),
        ));
        
        
    }
}