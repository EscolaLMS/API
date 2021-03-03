<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $is_exist = Config::all();

        if (!$is_exist->count()) {
            Config::create([
                'code' => 'pageHome',
                'option_key' => 'banner_title',
                'option_value' => 'Learn courses online'
            ]);

            Config::create([
                'code' => 'pageHome',
                'option_key' => 'banner_text',
                'option_value' => 'Learn every topic. On time. Everytime.'
            ]);

            Config::create([
                'code' => 'pageHome',
                'option_key' => 'instructor_text',
                'option_value' => 'We have more than 3,250 skilled & professional Instructors'
            ]);

            Config::create([
                'code' => 'pageHome',
                'option_key' => 'learn_block_title',
                'option_value' => 'Learn every topic, everytime.'
            ]);

            Config::create([
                'code' => 'pageHome',
                'option_key' => 'learn_block_text',
                'option_value' => '
            Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit.'
            ]);


            Config::create([
                'code' => 'pageAbout',
                'option_key' => 'content',
                'option_value' => 'lorem ipsum'
            ]);


            Config::create([
                'code' => 'pageContact',
                'option_key' => 'telephone',
                'option_value' => '+61 (800) 123-54323'
            ]);

            Config::create([
                'code' => 'pageContact',
                'option_key' => 'email',
                'option_value' => 'XYZ@example.com'
            ]);

            Config::create([
                'code' => 'pageContact',
                'option_key' => 'address',
                'option_value' => '8432 Newyork United States'
            ]);

            Config::create([
                'code' => 'pageContact',
                'option_key' => 'map',
                'option_value' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.940622898076!2d-74.00543578509465!3d40.74133204375838!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259bf14f1f51f%3A0xcc1b5ab35bd75df0!2sGoogle!5e0!3m2!1sen!2sin!4v1542693598934" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>'
            ]);

            Config::create([
                'code' => 'settingGeneral',
                'option_key' => 'application_name',
                'option_value' => 'Escola LMS'
            ]);

            Config::create([
                'code' => 'settingGeneral',
                'option_key' => 'meta_key',
                'option_value' => 'Learn courses online'
            ]);

            Config::create([
                'code' => 'settingGeneral',
                'option_key' => 'meta_description',
                'option_value' => 'Learn every topic. On time. Every time.'
            ]);

            Config::create([
                'code' => 'settingGeneral',
                'option_key' => 'admin_commission',
                'option_value' => '20'
            ]);

            Config::create([
                'code' => 'settingGeneral',
                'option_key' => 'admin_email',
                'option_value' => 'admin@escola-lms.com'
            ]);

            Config::create([
                'code' => 'settingGeneral',
                'option_key' => 'minimum_withdraw',
                'option_value' => '100'
            ]);

            Config::create([
                'code' => 'settingGeneral',
                'option_key' => 'header_logo',
                'option_value' => 'config/logo.png'
            ]);

            Config::create([
                'code' => 'settingGeneral',
                'option_key' => 'fav_icon',
                'option_value' => 'config/favicon.ico'
            ]);

            Config::create([
                'code' => 'settingGeneral',
                'option_key' => 'footer_logo',
                'option_value' => 'config/logo_footer.png'
            ]);

            Config::create([
                'code' => 'settingPayment',
                'option_key' => 'username',
                'option_value' => ''
            ]);

            Config::create([
                'code' => 'settingPayment',
                'option_key' => 'password',
                'option_value' => ''
            ]);

            Config::create([
                'code' => 'settingPayment',
                'option_key' => 'signature',
                'option_value' => ''
            ]);

            Config::create([
                'code' => 'settingPayment',
                'option_key' => 'test_mode',
                'option_value' => '1'
            ]);

            Config::create([
                'code' => 'settingPayment',
                'option_key' => 'is_active',
                'option_value' => '1'
            ]);

            Config::create([
                'code' => 'settingEmail',
                'option_key' => 'smtp_host',
                'option_value' => null
            ]);

            Config::create([
                'code' => 'settingEmail',
                'option_key' => 'smtp_port',
                'option_value' => null
            ]);

            Config::create([
                'code' => 'settingEmail',
                'option_key' => 'smtp_secure',
                'option_value' => null
            ]);

            Config::create([
                'code' => 'settingEmail',
                'option_key' => 'smtp_username',
                'option_value' => null
            ]);

            Config::create([
                'code' => 'settingEmail',
                'option_key' => 'smtp_password',
                'option_value' => null
            ]);

            Config::create([
                'code' => 'settingReward',
                'option_key' => 'time_logged',
                'option_value' => 10,
            ]);

            Config::create([
                'code' => 'settingReward',
                'option_key' => 'time_on_course',
                'option_value' => 10,
            ]);

            Config::create([
                'code' => 'settingReward',
                'option_key' => 'completing_course',
                'option_value' => 50,
            ]);

            Config::create([
                'code' => 'settingReward',
                'option_key' => 'sharing_achievement',
                'option_value' => 20,
            ]);

            Config::create([
                'code' => 'settingReward',
                'option_key' => 'sharing_other',
                'option_value' => 5,
            ]);
        }
    }
}
