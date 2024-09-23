<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('languages')->delete();
        
        \DB::table('languages')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'ab',
                'name' => 'Abkhazian',
                'name_native' => 'аҧсуа',
                'dir' => 'ltr',
            ),
            1 => 
            array (
                'id' => 2,
                'code' => 'aa',
                'name' => 'Afar',
                'name_native' => 'Afaraf',
                'dir' => 'ltr',
            ),
            2 => 
            array (
                'id' => 3,
                'code' => 'af',
                'name' => 'Afrikaans',
                'name_native' => 'Afrikaans',
                'dir' => 'ltr',
            ),
            3 => 
            array (
                'id' => 4,
                'code' => 'ak',
                'name' => 'Akan',
                'name_native' => 'Akan',
                'dir' => 'ltr',
            ),
            4 => 
            array (
                'id' => 5,
                'code' => 'sq',
                'name' => 'Albanian',
                'name_native' => 'Shqip',
                'dir' => 'ltr',
            ),
            5 => 
            array (
                'id' => 6,
                'code' => 'am',
                'name' => 'Amharic',
                'name_native' => 'አማርኛ',
                'dir' => 'ltr',
            ),
            6 => 
            array (
                'id' => 7,
                'code' => 'ar',
                'name' => 'Arabic',
                'name_native' => 'العربية',
                'dir' => 'rtl',
            ),
            7 => 
            array (
                'id' => 8,
                'code' => 'an',
                'name' => 'Aragonese',
                'name_native' => 'Aragonés',
                'dir' => 'ltr',
            ),
            8 => 
            array (
                'id' => 9,
                'code' => 'hy',
                'name' => 'Armenian',
                'name_native' => 'Հայերեն',
                'dir' => 'ltr',
            ),
            9 => 
            array (
                'id' => 10,
                'code' => 'as',
                'name' => 'Assamese',
                'name_native' => 'অসমীয়া',
                'dir' => 'ltr',
            ),
            10 => 
            array (
                'id' => 11,
                'code' => 'av',
                'name' => 'Avaric',
                'name_native' => 'авар мацӀ, магӀарул мацӀ',
                'dir' => 'ltr',
            ),
            11 => 
            array (
                'id' => 12,
                'code' => 'ae',
                'name' => 'Avestan',
                'name_native' => 'avesta',
                'dir' => 'ltr',
            ),
            12 => 
            array (
                'id' => 13,
                'code' => 'ay',
                'name' => 'Aymara',
                'name_native' => 'aymar aru',
                'dir' => 'ltr',
            ),
            13 => 
            array (
                'id' => 14,
                'code' => 'az',
                'name' => 'Azerbaijani',
                'name_native' => 'azərbaycan dili',
                'dir' => 'ltr',
            ),
            14 => 
            array (
                'id' => 15,
                'code' => 'bm',
                'name' => 'Bambara',
                'name_native' => 'bamanankan',
                'dir' => 'ltr',
            ),
            15 => 
            array (
                'id' => 16,
                'code' => 'ba',
                'name' => 'Bashkir',
                'name_native' => 'башҡорт теле',
                'dir' => 'ltr',
            ),
            16 => 
            array (
                'id' => 17,
                'code' => 'eu',
                'name' => 'Basque',
                'name_native' => 'euskara, euskera',
                'dir' => 'ltr',
            ),
            17 => 
            array (
                'id' => 18,
                'code' => 'be',
                'name' => 'Belarusian',
                'name_native' => 'Беларуская',
                'dir' => 'ltr',
            ),
            18 => 
            array (
                'id' => 19,
                'code' => 'bn',
                'name' => 'Bengali',
                'name_native' => 'বাংলা',
                'dir' => 'ltr',
            ),
            19 => 
            array (
                'id' => 20,
                'code' => 'bh',
                'name' => 'Bihari',
                'name_native' => 'भोजपुरी',
                'dir' => 'ltr',
            ),
            20 => 
            array (
                'id' => 21,
                'code' => 'bi',
                'name' => 'Bislama',
                'name_native' => 'Bislama',
                'dir' => 'ltr',
            ),
            21 => 
            array (
                'id' => 22,
                'code' => 'bs',
                'name' => 'Bosnian',
                'name_native' => 'bosanski jezik',
                'dir' => 'ltr',
            ),
            22 => 
            array (
                'id' => 23,
                'code' => 'br',
                'name' => 'Breton',
                'name_native' => 'brezhoneg',
                'dir' => 'ltr',
            ),
            23 => 
            array (
                'id' => 24,
                'code' => 'bg',
                'name' => 'Bulgarian',
                'name_native' => 'български език',
                'dir' => 'ltr',
            ),
            24 => 
            array (
                'id' => 25,
                'code' => 'my',
                'name' => 'Burmese',
                'name_native' => 'ဗမာစာ',
                'dir' => 'ltr',
            ),
            25 => 
            array (
                'id' => 26,
                'code' => 'ca',
                'name' => 'Catalan; Valencian',
                'name_native' => 'Català',
                'dir' => 'ltr',
            ),
            26 => 
            array (
                'id' => 27,
                'code' => 'ch',
                'name' => 'Chamorro',
                'name_native' => 'Chamoru',
                'dir' => 'ltr',
            ),
            27 => 
            array (
                'id' => 28,
                'code' => 'ce',
                'name' => 'Chechen',
                'name_native' => 'нохчийн мотт',
                'dir' => 'ltr',
            ),
            28 => 
            array (
                'id' => 29,
                'code' => 'ny',
                'name' => 'Chichewa; Chewa; Nyanja',
                'name_native' => 'chiCheŵa, chinyanja',
                'dir' => 'ltr',
            ),
            29 => 
            array (
                'id' => 30,
                'code' => 'zh',
                'name' => 'Chinese',
            'name_native' => '中文 (Zhōngwén), 汉语, 漢語',
                'dir' => 'ltr',
            ),
            30 => 
            array (
                'id' => 31,
                'code' => 'cv',
                'name' => 'Chuvash',
                'name_native' => 'чӑваш чӗлхи',
                'dir' => 'ltr',
            ),
            31 => 
            array (
                'id' => 32,
                'code' => 'kw',
                'name' => 'Cornish',
                'name_native' => 'Kernewek',
                'dir' => 'ltr',
            ),
            32 => 
            array (
                'id' => 33,
                'code' => 'co',
                'name' => 'Corsican',
                'name_native' => 'corsu, lingua corsa',
                'dir' => 'ltr',
            ),
            33 => 
            array (
                'id' => 34,
                'code' => 'cr',
                'name' => 'Cree',
                'name_native' => 'ᓀᐦᐃᔭᐍᐏᐣ',
                'dir' => 'ltr',
            ),
            34 => 
            array (
                'id' => 35,
                'code' => 'cs',
                'name' => 'Croatian',
                'name_native' => 'hrvatski',
                'dir' => 'ltr',
            ),
            35 => 
            array (
                'id' => 36,
                'code' => 'aa',
                'name' => 'Czech',
                'name_native' => 'česky, čeština',
                'dir' => 'ltr',
            ),
            36 => 
            array (
                'id' => 37,
                'code' => 'da',
                'name' => 'Danish',
                'name_native' => 'dansk',
                'dir' => 'ltr',
            ),
            37 => 
            array (
                'id' => 38,
                'code' => 'dv',
                'name' => 'Divehi; Dhivehi; Maldivian;',
                'name_native' => 'ދިވެހި',
                'dir' => 'rtl',
            ),
            38 => 
            array (
                'id' => 39,
                'code' => 'nl',
                'name' => 'Dutch',
                'name_native' => 'Nederlands, Vlaams',
                'dir' => 'ltr',
            ),
            39 => 
            array (
                'id' => 40,
                'code' => 'en',
                'name' => 'English',
                'name_native' => 'English',
                'dir' => 'ltr',
            ),
            40 => 
            array (
                'id' => 41,
                'code' => 'eo',
                'name' => 'Esperanto',
                'name_native' => 'Esperanto',
                'dir' => 'ltr',
            ),
            41 => 
            array (
                'id' => 42,
                'code' => 'et',
                'name' => 'Estonian',
                'name_native' => 'eesti, eesti keel',
                'dir' => 'ltr',
            ),
            42 => 
            array (
                'id' => 43,
                'code' => 'ee',
                'name' => 'Ewe',
                'name_native' => 'Eʋegbe',
                'dir' => 'ltr',
            ),
            43 => 
            array (
                'id' => 44,
                'code' => 'fo',
                'name' => 'Faroese',
                'name_native' => 'føroyskt',
                'dir' => 'ltr',
            ),
            44 => 
            array (
                'id' => 45,
                'code' => 'fj',
                'name' => 'Fijian',
                'name_native' => 'vosa Vakaviti',
                'dir' => 'ltr',
            ),
            45 => 
            array (
                'id' => 46,
                'code' => 'fi',
                'name' => 'Finnish',
                'name_native' => 'suomi, suomen kieli',
                'dir' => 'ltr',
            ),
            46 => 
            array (
                'id' => 47,
                'code' => 'fr',
                'name' => 'French',
                'name_native' => 'français',
                'dir' => 'ltr',
            ),
            47 => 
            array (
                'id' => 48,
                'code' => 'ff',
                'name' => 'Fula; Fulah; Pulaar; Pular',
                'name_native' => 'Fulfulde, Pulaar, Pular',
                'dir' => 'ltr',
            ),
            48 => 
            array (
                'id' => 49,
                'code' => 'gl',
                'name' => 'Galician',
                'name_native' => 'Galego',
                'dir' => 'ltr',
            ),
            49 => 
            array (
                'id' => 50,
                'code' => 'ka',
                'name' => 'Georgian',
                'name_native' => 'ქართული',
                'dir' => 'ltr',
            ),
            50 => 
            array (
                'id' => 51,
                'code' => 'de',
                'name' => 'German',
                'name_native' => 'Deutsch',
                'dir' => 'ltr',
            ),
            51 => 
            array (
                'id' => 52,
                'code' => 'el',
                'name' => 'Greek, Modern',
                'name_native' => 'Ελληνικά',
                'dir' => 'ltr',
            ),
            52 => 
            array (
                'id' => 53,
                'code' => 'gn',
                'name' => 'Guaraní',
                'name_native' => 'Avañeẽ',
                'dir' => 'ltr',
            ),
            53 => 
            array (
                'id' => 54,
                'code' => 'gu',
                'name' => 'Gujarati',
                'name_native' => 'ગુજરાતી',
                'dir' => 'ltr',
            ),
            54 => 
            array (
                'id' => 55,
                'code' => 'ht',
                'name' => 'Haitian; Haitian Creole',
                'name_native' => 'Kreyòl ayisyen',
                'dir' => 'ltr',
            ),
            55 => 
            array (
                'id' => 56,
                'code' => 'ha',
                'name' => 'Hausa',
                'name_native' => 'Hausa, هَوُسَ',
                'dir' => 'ltr',
            ),
            56 => 
            array (
                'id' => 57,
                'code' => 'he',
                'name' => 'Hebrew',
                'name_native' => 'עברית',
                'dir' => 'rtl',
            ),
            57 => 
            array (
                'id' => 58,
                'code' => 'iw',
                'name' => 'Hebrew',
                'name_native' => 'עברית',
                'dir' => 'rtl',
            ),
            58 => 
            array (
                'id' => 59,
                'code' => 'hz',
                'name' => 'Herero',
                'name_native' => 'Otjiherero',
                'dir' => 'ltr',
            ),
            59 => 
            array (
                'id' => 60,
                'code' => 'hi',
                'name' => 'Hindi',
                'name_native' => 'हिन्दी, हिंदी',
                'dir' => 'ltr',
            ),
            60 => 
            array (
                'id' => 61,
                'code' => 'ho',
                'name' => 'Hiri Motu',
                'name_native' => 'Hiri Motu',
                'dir' => 'ltr',
            ),
            61 => 
            array (
                'id' => 62,
                'code' => 'hu',
                'name' => 'Hungarian',
                'name_native' => 'Magyar',
                'dir' => 'ltr',
            ),
            62 => 
            array (
                'id' => 63,
                'code' => 'ia',
                'name' => 'Interlingua',
                'name_native' => 'Interlingua',
                'dir' => 'ltr',
            ),
            63 => 
            array (
                'id' => 64,
                'code' => 'id',
                'name' => 'Indonesian',
                'name_native' => 'Bahasa Indonesia',
                'dir' => 'ltr',
            ),
            64 => 
            array (
                'id' => 65,
                'code' => 'ie',
                'name' => 'Interlingue',
                'name_native' => 'Originally called Occidental; then Interlingue after WWII',
                'dir' => 'ltr',
            ),
            65 => 
            array (
                'id' => 66,
                'code' => 'ga',
                'name' => 'Irish',
                'name_native' => 'Gaeilge',
                'dir' => 'ltr',
            ),
            66 => 
            array (
                'id' => 67,
                'code' => 'ig',
                'name' => 'Igbo',
                'name_native' => 'Asụsụ Igbo',
                'dir' => 'ltr',
            ),
            67 => 
            array (
                'id' => 68,
                'code' => 'ik',
                'name' => 'Inupiaq',
                'name_native' => 'Iñupiaq, Iñupiatun',
                'dir' => 'ltr',
            ),
            68 => 
            array (
                'id' => 69,
                'code' => 'io',
                'name' => 'Ido',
                'name_native' => 'Ido',
                'dir' => 'ltr',
            ),
            69 => 
            array (
                'id' => 70,
                'code' => 'is',
                'name' => 'Icelandic',
                'name_native' => 'Íslenska',
                'dir' => 'ltr',
            ),
            70 => 
            array (
                'id' => 71,
                'code' => 'it',
                'name' => 'Italian',
                'name_native' => 'Italiano',
                'dir' => 'ltr',
            ),
            71 => 
            array (
                'id' => 72,
                'code' => 'iu',
                'name' => 'Inuktitut',
                'name_native' => 'ᐃᓄᒃᑎᑐᑦ',
                'dir' => 'ltr',
            ),
            72 => 
            array (
                'id' => 73,
                'code' => 'ja',
                'name' => 'Japanese',
            'name_native' => '日本語 (にほんご／にっぽんご)',
                'dir' => 'ltr',
            ),
            73 => 
            array (
                'id' => 74,
                'code' => 'jv',
                'name' => 'Javanese',
                'name_native' => 'basa Jawa',
                'dir' => 'ltr',
            ),
            74 => 
            array (
                'id' => 75,
                'code' => 'kl',
                'name' => 'Kalaallisut, Greenlandic',
                'name_native' => 'kalaallisut, kalaallit oqaasii',
                'dir' => 'ltr',
            ),
            75 => 
            array (
                'id' => 76,
                'code' => 'kn',
                'name' => 'Kannada',
                'name_native' => 'ಕನ್ನಡ',
                'dir' => 'ltr',
            ),
            76 => 
            array (
                'id' => 77,
                'code' => 'kr',
                'name' => 'Kanuri',
                'name_native' => 'Kanuri',
                'dir' => 'ltr',
            ),
            77 => 
            array (
                'id' => 78,
                'code' => 'ks',
                'name' => 'Kashmiri',
                'name_native' => 'कश्मीरी, كشميري‎',
                'dir' => 'ltr',
            ),
            78 => 
            array (
                'id' => 79,
                'code' => 'kk',
                'name' => 'Kazakh',
                'name_native' => 'Қазақ тілі',
                'dir' => 'ltr',
            ),
            79 => 
            array (
                'id' => 80,
                'code' => 'km',
                'name' => 'Khmer',
                'name_native' => 'ភាសាខ្មែរ',
                'dir' => 'ltr',
            ),
            80 => 
            array (
                'id' => 81,
                'code' => 'ki',
                'name' => 'Kikuyu, Gikuyu',
                'name_native' => 'Gĩkũyũ',
                'dir' => 'ltr',
            ),
            81 => 
            array (
                'id' => 82,
                'code' => 'rw',
                'name' => 'Kinyarwanda',
                'name_native' => 'Ikinyarwanda',
                'dir' => 'ltr',
            ),
            82 => 
            array (
                'id' => 83,
                'code' => 'ky',
                'name' => 'Kirghiz, Kyrgyz',
                'name_native' => 'кыргыз тили',
                'dir' => 'ltr',
            ),
            83 => 
            array (
                'id' => 84,
                'code' => 'kv',
                'name' => 'Komi',
                'name_native' => 'коми кыв',
                'dir' => 'ltr',
            ),
            84 => 
            array (
                'id' => 85,
                'code' => 'kg',
                'name' => 'Kongo',
                'name_native' => 'KiKongo',
                'dir' => 'ltr',
            ),
            85 => 
            array (
                'id' => 86,
                'code' => 'ko',
                'name' => 'Korean',
            'name_native' => '한국어 (韓國語), 조선말 (朝鮮語)',
                'dir' => 'ltr',
            ),
            86 => 
            array (
                'id' => 87,
                'code' => 'ku',
                'name' => 'Kurdish',
                'name_native' => 'Kurdî, كوردی‎',
                'dir' => 'rtl',
            ),
            87 => 
            array (
                'id' => 88,
                'code' => 'kj',
                'name' => 'Kwanyama, Kuanyama',
                'name_native' => 'Kuanyama',
                'dir' => 'ltr',
            ),
            88 => 
            array (
                'id' => 89,
                'code' => 'la',
                'name' => 'Latin',
                'name_native' => 'latine, lingua latina',
                'dir' => 'ltr',
            ),
            89 => 
            array (
                'id' => 90,
                'code' => 'lb',
                'name' => 'Luxembourgish, Letzeburgesch',
                'name_native' => 'Lëtzebuergesch',
                'dir' => 'ltr',
            ),
            90 => 
            array (
                'id' => 91,
                'code' => 'lg',
                'name' => 'Luganda',
                'name_native' => 'Luganda',
                'dir' => 'ltr',
            ),
            91 => 
            array (
                'id' => 92,
                'code' => 'li',
                'name' => 'Limburgish, Limburgan, Limburger',
                'name_native' => 'Limburgs',
                'dir' => 'ltr',
            ),
            92 => 
            array (
                'id' => 93,
                'code' => 'ln',
                'name' => 'Lingala',
                'name_native' => 'Lingála',
                'dir' => 'ltr',
            ),
            93 => 
            array (
                'id' => 94,
                'code' => 'lo',
                'name' => 'Lao',
                'name_native' => 'ພາສາລາວ',
                'dir' => 'ltr',
            ),
            94 => 
            array (
                'id' => 95,
                'code' => 'lt',
                'name' => 'Lithuanian',
                'name_native' => 'lietuvių kalba',
                'dir' => 'ltr',
            ),
            95 => 
            array (
                'id' => 96,
                'code' => 'lu',
                'name' => 'Luba-Katanga',
                'name_native' => 'Kiluba',
                'dir' => 'ltr',
            ),
            96 => 
            array (
                'id' => 97,
                'code' => 'lv',
                'name' => 'Latvian',
                'name_native' => 'latviešu valoda',
                'dir' => 'ltr',
            ),
            97 => 
            array (
                'id' => 98,
                'code' => 'gv',
                'name' => 'Manx',
                'name_native' => 'Gaelg, Gailck',
                'dir' => 'ltr',
            ),
            98 => 
            array (
                'id' => 99,
                'code' => 'mk',
                'name' => 'Macedonian',
                'name_native' => 'македонски јазик',
                'dir' => 'ltr',
            ),
            99 => 
            array (
                'id' => 100,
                'code' => 'mg',
                'name' => 'Malagasy',
                'name_native' => 'Malagasy fiteny',
                'dir' => 'ltr',
            ),
            100 => 
            array (
                'id' => 101,
                'code' => 'ms',
                'name' => 'Malay',
                'name_native' => 'bahasa Melayu, بهاس ملايو‎',
                'dir' => 'ltr',
            ),
            101 => 
            array (
                'id' => 102,
                'code' => 'ml',
                'name' => 'Malayalam',
                'name_native' => 'മലയാളം',
                'dir' => 'ltr',
            ),
            102 => 
            array (
                'id' => 103,
                'code' => 'mt',
                'name' => 'Maltese',
                'name_native' => 'Malti',
                'dir' => 'ltr',
            ),
            103 => 
            array (
                'id' => 104,
                'code' => 'mi',
                'name' => 'Māori',
                'name_native' => 'te reo Māori',
                'dir' => 'ltr',
            ),
            104 => 
            array (
                'id' => 105,
                'code' => 'mr',
            'name' => 'Marathi (Marāṭhī)',
                'name_native' => 'मराठी',
                'dir' => 'ltr',
            ),
            105 => 
            array (
                'id' => 106,
                'code' => 'mh',
                'name' => 'Marshallese',
                'name_native' => 'Kajin M̧ajeļ',
                'dir' => 'ltr',
            ),
            106 => 
            array (
                'id' => 107,
                'code' => 'mn',
                'name' => 'Mongolian',
                'name_native' => 'монгол',
                'dir' => 'ltr',
            ),
            107 => 
            array (
                'id' => 108,
                'code' => 'na',
                'name' => 'Nauru',
                'name_native' => 'Ekakairũ Naoero',
                'dir' => 'ltr',
            ),
            108 => 
            array (
                'id' => 109,
                'code' => 'nv',
                'name' => 'Navajo, Navaho',
                'name_native' => 'Diné bizaad, Dinékʼehǰí',
                'dir' => 'ltr',
            ),
            109 => 
            array (
                'id' => 110,
                'code' => 'nb',
                'name' => 'Norwegian Bokmål',
                'name_native' => 'Norsk bokmål',
                'dir' => 'ltr',
            ),
            110 => 
            array (
                'id' => 111,
                'code' => 'nd',
                'name' => 'North Ndebele',
                'name_native' => 'isiNdebele',
                'dir' => 'ltr',
            ),
            111 => 
            array (
                'id' => 112,
                'code' => 'ne',
                'name' => 'Nepali',
                'name_native' => 'नेपाली',
                'dir' => 'ltr',
            ),
            112 => 
            array (
                'id' => 113,
                'code' => 'ng',
                'name' => 'Ndonga',
                'name_native' => 'Owambo',
                'dir' => 'ltr',
            ),
            113 => 
            array (
                'id' => 114,
                'code' => 'nn',
                'name' => 'Norwegian Nynorsk',
                'name_native' => 'Norsk nynorsk',
                'dir' => 'ltr',
            ),
            114 => 
            array (
                'id' => 115,
                'code' => 'no',
                'name' => 'Norwegian',
                'name_native' => 'Norsk',
                'dir' => 'ltr',
            ),
            115 => 
            array (
                'id' => 116,
                'code' => 'ii',
                'name' => 'Nuosu',
                'name_native' => 'ꆈꌠ꒿ Nuosuhxop',
                'dir' => 'ltr',
            ),
            116 => 
            array (
                'id' => 117,
                'code' => 'nr',
                'name' => 'South Ndebele',
                'name_native' => 'isiNdebele',
                'dir' => 'ltr',
            ),
            117 => 
            array (
                'id' => 118,
                'code' => 'oc',
                'name' => 'Occitan',
                'name_native' => 'Occitan',
                'dir' => 'ltr',
            ),
            118 => 
            array (
                'id' => 119,
                'code' => 'oj',
                'name' => 'Ojibwe, Ojibwa',
                'name_native' => 'ᐊᓂᔑᓈᐯᒧᐎᓐ',
                'dir' => 'ltr',
            ),
            119 => 
            array (
                'id' => 120,
                'code' => 'cu',
                'name' => 'Old Church Slavonic, Church Slavic, Church Slavonic, Old Bulgarian, Old Slavonic',
                'name_native' => 'ѩзыкъ словѣньскъ',
                'dir' => 'ltr',
            ),
            120 => 
            array (
                'id' => 121,
                'code' => 'om',
                'name' => 'Oromo',
                'name_native' => 'Afaan Oromoo',
                'dir' => 'ltr',
            ),
            121 => 
            array (
                'id' => 122,
                'code' => 'or',
                'name' => 'Oriya',
                'name_native' => 'ଓଡ଼ିଆ',
                'dir' => 'ltr',
            ),
            122 => 
            array (
                'id' => 123,
                'code' => 'os',
                'name' => 'Ossetian, Ossetic',
                'name_native' => 'ирон æвзаг',
                'dir' => 'ltr',
            ),
            123 => 
            array (
                'id' => 124,
                'code' => 'pa',
                'name' => 'Panjabi, Punjabi',
                'name_native' => 'ਪੰਜਾਬੀ, پنجابی‎',
                'dir' => 'ltr',
            ),
            124 => 
            array (
                'id' => 125,
                'code' => 'pi',
                'name' => 'Pāli',
                'name_native' => 'पाऴि',
                'dir' => 'ltr',
            ),
            125 => 
            array (
                'id' => 126,
                'code' => 'fa',
                'name' => 'Persian',
                'name_native' => 'فارسی',
                'dir' => 'rtl',
            ),
            126 => 
            array (
                'id' => 127,
                'code' => 'pl',
                'name' => 'Polish',
                'name_native' => 'polski',
                'dir' => 'ltr',
            ),
            127 => 
            array (
                'id' => 128,
                'code' => 'ps',
                'name' => 'Pashto, Pushto',
                'name_native' => 'پښتو',
                'dir' => 'ltr',
            ),
            128 => 
            array (
                'id' => 129,
                'code' => 'pt',
                'name' => 'Portuguese',
                'name_native' => 'Português',
                'dir' => 'ltr',
            ),
            129 => 
            array (
                'id' => 130,
                'code' => 'qu',
                'name' => 'Quechua',
                'name_native' => 'Runa Simi, Kichwa',
                'dir' => 'ltr',
            ),
            130 => 
            array (
                'id' => 131,
                'code' => 'rm',
                'name' => 'Romansh',
                'name_native' => 'rumantsch grischun',
                'dir' => 'ltr',
            ),
            131 => 
            array (
                'id' => 132,
                'code' => 'rn',
                'name' => 'Kirundi',
                'name_native' => 'kiRundi',
                'dir' => 'ltr',
            ),
            132 => 
            array (
                'id' => 133,
                'code' => 'ro',
                'name' => 'Romanian',
                'name_native' => 'română',
                'dir' => 'ltr',
            ),
            133 => 
            array (
                'id' => 134,
                'code' => 'ru',
                'name' => 'Russian',
                'name_native' => 'русский язык',
                'dir' => 'ltr',
            ),
            134 => 
            array (
                'id' => 135,
                'code' => 'sa',
            'name' => 'Sanskrit (Saṁskṛta)',
                'name_native' => 'संस्कृतम्',
                'dir' => 'ltr',
            ),
            135 => 
            array (
                'id' => 136,
                'code' => 'sc',
                'name' => 'Sardinian',
                'name_native' => 'sardu',
                'dir' => 'ltr',
            ),
            136 => 
            array (
                'id' => 137,
                'code' => 'sd',
                'name' => 'Sindhi',
                'name_native' => 'सिन्धी, سنڌي، سندھی‎',
                'dir' => 'ltr',
            ),
            137 => 
            array (
                'id' => 138,
                'code' => 'se',
                'name' => 'Northern Sami',
                'name_native' => 'Davvisámegiella',
                'dir' => 'ltr',
            ),
            138 => 
            array (
                'id' => 139,
                'code' => 'sm',
                'name' => 'Samoan',
                'name_native' => 'gagana faa Samoa',
                'dir' => 'ltr',
            ),
            139 => 
            array (
                'id' => 140,
                'code' => 'sg',
                'name' => 'Sango',
                'name_native' => 'yângâ tî sängö',
                'dir' => 'ltr',
            ),
            140 => 
            array (
                'id' => 141,
                'code' => 'sr',
                'name' => 'Serbian',
                'name_native' => 'српски језик',
                'dir' => 'ltr',
            ),
            141 => 
            array (
                'id' => 142,
                'code' => 'gd',
                'name' => 'Scottish Gaelic; Gaelic',
                'name_native' => 'Gàidhlig',
                'dir' => 'ltr',
            ),
            142 => 
            array (
                'id' => 143,
                'code' => 'sn',
                'name' => 'Shona',
                'name_native' => 'chiShona',
                'dir' => 'ltr',
            ),
            143 => 
            array (
                'id' => 144,
                'code' => 'si',
                'name' => 'Sinhala, Sinhalese',
                'name_native' => 'සිංහල',
                'dir' => 'ltr',
            ),
            144 => 
            array (
                'id' => 145,
                'code' => 'sk',
                'name' => 'Slovak',
                'name_native' => 'slovenčina',
                'dir' => 'ltr',
            ),
            145 => 
            array (
                'id' => 146,
                'code' => 'sl',
                'name' => 'Slovene',
                'name_native' => 'slovenščina',
                'dir' => 'ltr',
            ),
            146 => 
            array (
                'id' => 147,
                'code' => 'so',
                'name' => 'Somali',
                'name_native' => 'Soomaaliga, af Soomaali',
                'dir' => 'ltr',
            ),
            147 => 
            array (
                'id' => 148,
                'code' => 'st',
                'name' => 'Southern Sotho',
                'name_native' => 'Sesotho',
                'dir' => 'ltr',
            ),
            148 => 
            array (
                'id' => 149,
                'code' => 'es',
                'name' => 'Spanish; Castilian',
                'name_native' => 'español, castellano',
                'dir' => 'ltr',
            ),
            149 => 
            array (
                'id' => 150,
                'code' => 'su',
                'name' => 'Sundanese',
                'name_native' => 'Basa Sunda',
                'dir' => 'ltr',
            ),
            150 => 
            array (
                'id' => 151,
                'code' => 'sw',
                'name' => 'Swahili',
                'name_native' => 'Kiswahili',
                'dir' => 'ltr',
            ),
            151 => 
            array (
                'id' => 152,
                'code' => 'ss',
                'name' => 'Swati',
                'name_native' => 'SiSwati',
                'dir' => 'ltr',
            ),
            152 => 
            array (
                'id' => 153,
                'code' => 'sv',
                'name' => 'Swedish',
                'name_native' => 'svenska',
                'dir' => 'ltr',
            ),
            153 => 
            array (
                'id' => 154,
                'code' => 'ta',
                'name' => 'Tamil',
                'name_native' => 'தமிழ்',
                'dir' => 'ltr',
            ),
            154 => 
            array (
                'id' => 155,
                'code' => 'te',
                'name' => 'Telugu',
                'name_native' => 'తెలుగు',
                'dir' => 'ltr',
            ),
            155 => 
            array (
                'id' => 156,
                'code' => 'tg',
                'name' => 'Tajik',
                'name_native' => 'тоҷикӣ, toğikī, تاجیکی‎',
                'dir' => 'ltr',
            ),
            156 => 
            array (
                'id' => 157,
                'code' => 'th',
                'name' => 'Thai',
                'name_native' => 'ไทย',
                'dir' => 'ltr',
            ),
            157 => 
            array (
                'id' => 158,
                'code' => 'ti',
                'name' => 'Tigrinya',
                'name_native' => 'ትግርኛ',
                'dir' => 'ltr',
            ),
            158 => 
            array (
                'id' => 159,
                'code' => 'bo',
                'name' => 'Tibetan Standard, Tibetan, Central',
                'name_native' => 'བོད་ཡིག',
                'dir' => 'ltr',
            ),
            159 => 
            array (
                'id' => 160,
                'code' => 'tk',
                'name' => 'Turkmen',
                'name_native' => 'Türkmen, Түркмен',
                'dir' => 'ltr',
            ),
            160 => 
            array (
                'id' => 161,
                'code' => 'tl',
                'name' => 'Tagalog',
                'name_native' => 'Wikang Tagalog, ᜏᜒᜃᜅ᜔ ᜆᜄᜎᜓᜄ᜔',
                'dir' => 'ltr',
            ),
            161 => 
            array (
                'id' => 162,
                'code' => 'tn',
                'name' => 'Tswana',
                'name_native' => 'Setswana',
                'dir' => 'ltr',
            ),
            162 => 
            array (
                'id' => 163,
                'code' => 'to',
            'name' => 'Tonga (Tonga Islands)',
                'name_native' => 'faka Tonga',
                'dir' => 'ltr',
            ),
            163 => 
            array (
                'id' => 164,
                'code' => 'tr',
                'name' => 'Turkish',
                'name_native' => 'Türkçe',
                'dir' => 'ltr',
            ),
            164 => 
            array (
                'id' => 165,
                'code' => 'ts',
                'name' => 'Tsonga',
                'name_native' => 'Xitsonga',
                'dir' => 'ltr',
            ),
            165 => 
            array (
                'id' => 166,
                'code' => 'tt',
                'name' => 'Tatar',
                'name_native' => 'татарча, tatarça, تاتارچا‎',
                'dir' => 'ltr',
            ),
            166 => 
            array (
                'id' => 167,
                'code' => 'tw',
                'name' => 'Twi',
                'name_native' => 'Twi',
                'dir' => 'ltr',
            ),
            167 => 
            array (
                'id' => 168,
                'code' => 'ty',
                'name' => 'Tahitian',
                'name_native' => 'Reo Tahiti',
                'dir' => 'ltr',
            ),
            168 => 
            array (
                'id' => 169,
                'code' => 'ug',
                'name' => 'Uighur, Uyghur',
                'name_native' => 'Uyƣurqə, ئۇيغۇرچە‎',
                'dir' => 'ltr',
            ),
            169 => 
            array (
                'id' => 170,
                'code' => 'uk',
                'name' => 'Ukrainian',
                'name_native' => 'українська',
                'dir' => 'ltr',
            ),
            170 => 
            array (
                'id' => 171,
                'code' => 'ur',
                'name' => 'Urdu',
                'name_native' => 'اردو',
                'dir' => 'rtl',
            ),
            171 => 
            array (
                'id' => 172,
                'code' => 'uz',
                'name' => 'Uzbek',
                'name_native' => 'zbek, Ўзбек, أۇزبېك‎',
                'dir' => 'ltr',
            ),
            172 => 
            array (
                'id' => 173,
                'code' => 've',
                'name' => 'Venda',
                'name_native' => 'Tshivenḓa',
                'dir' => 'ltr',
            ),
            173 => 
            array (
                'id' => 174,
                'code' => 'vi',
                'name' => 'Vietnamese',
                'name_native' => 'Tiếng Việt',
                'dir' => 'ltr',
            ),
            174 => 
            array (
                'id' => 175,
                'code' => 'vo',
                'name' => 'Volapük',
                'name_native' => 'Volapük',
                'dir' => 'ltr',
            ),
            175 => 
            array (
                'id' => 176,
                'code' => 'wa',
                'name' => 'Walloon',
                'name_native' => 'Walon',
                'dir' => 'ltr',
            ),
            176 => 
            array (
                'id' => 177,
                'code' => 'cy',
                'name' => 'Welsh',
                'name_native' => 'Cymraeg',
                'dir' => 'ltr',
            ),
            177 => 
            array (
                'id' => 178,
                'code' => 'wo',
                'name' => 'Wolof',
                'name_native' => 'Wollof',
                'dir' => 'ltr',
            ),
            178 => 
            array (
                'id' => 179,
                'code' => 'fy',
                'name' => 'Western Frisian',
                'name_native' => 'Frysk',
                'dir' => 'ltr',
            ),
            179 => 
            array (
                'id' => 180,
                'code' => 'xh',
                'name' => 'Xhosa',
                'name_native' => 'isiXhosa',
                'dir' => 'ltr',
            ),
            180 => 
            array (
                'id' => 181,
                'code' => 'yi',
                'name' => 'Yiddish',
                'name_native' => 'ייִדיש',
                'dir' => 'ltr',
            ),
            181 => 
            array (
                'id' => 182,
                'code' => 'yo',
                'name' => 'Yoruba',
                'name_native' => 'Yorùbá',
                'dir' => 'ltr',
            ),
            182 => 
            array (
                'id' => 183,
                'code' => 'za',
                'name' => 'Zhuang, Chuang',
                'name_native' => 'Saɯ cueŋƅ, Saw cuengh',
                'dir' => 'ltr',
            ),
        ));
        
        
    }
}