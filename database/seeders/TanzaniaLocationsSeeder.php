<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TanzaniaLocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array of Tanzania Regions with their Districts and Wards
        $regions = [
            'Arusha' => [
                'Arusha City' => ['Elerai', 'Engutoto', 'Kimandolu', 'Lemara', 'Olorien', 'Sekei', 'Sombetini', 'Themi'],
                'Arusha DC' => ['Bwawani', 'Kiranyi', 'Mateves', 'Moshono', 'Muriet', 'Sokoni 1', 'Sokoni 2'],
                'Karatu' => ['Endabash', 'Ganako', 'Karatu', 'Kansay', 'Qurus', 'Rhotia'],
                'Longido' => ['Engarenaibor', 'Gelai Lumbwa', 'Gelai Meirugoi', 'Kitumbeine', 'Longido', 'Olmolog'],
                'Meru' => ['Akheri', 'King\'ori', 'Leguruki', 'Makiba', 'Mbuguni', 'Ngarenanyuki', 'Poli'],
                'Monduli' => ['Engutoto', 'Lepurko', 'Makuyuni', 'Meserani', 'Monduli Juu', 'Monduli Mjini', 'Mto wa Mbu'],
                'Ngorongoro' => ['Enduleni', 'Kakesio', 'Nainokanoka', 'Ngorongoro', 'Olbalbal', 'Sale']
            ],
            'Dar es Salaam' => [
                'Ilala' => ['Buguruni', 'Gerezani', 'Ilala', 'Kariakoo', 'Kisutu', 'Mchafukoge', 'Upanga'],
                'Kinondoni' => ['Kawe', 'Kibamba', 'Kigogo', 'Kinondoni', 'Magomeni', 'Mikocheni', 'Msasani', 'Mwananyamala'],
                'Temeke' => ['Azimio', 'Chamazi', 'Chang\'ombe', 'Keko', 'Kibada', 'Kigamboni', 'Kurasini', 'Mbagala', 'Temeke']
            ],
            'Dodoma' => [
                'Dodoma City' => ['Dodoma Makulu', 'Hazina', 'Kikuyu', 'Kilimani', 'Makole', 'Tambukareli', 'Viwandani'],
                'Bahi' => ['Bahi', 'Chipanga', 'Ibihwa', 'Kigwe', 'Mpamantwa', 'Mwitikira'],
                'Chamwino' => ['Buigiri', 'Chamwino', 'Handali', 'Ikowa', 'Majeleko', 'Mvumi'],
                'Chemba' => ['Chemba', 'Farkwa', 'Goima', 'Kwamtoro', 'Mondo', 'Paranga'],
                'Kondoa' => ['Bereko', 'Bolisa', 'Kondoa Mjini', 'Kolo', 'Mondo', 'Suruke'],
                'Kongwa' => ['Kongwa', 'Mlali', 'Mpwapwa', 'Pandambili', 'Sejeli', 'Zoissa'],
                'Mpwapwa' => ['Berege', 'Chipogoro', 'Gulwe', 'Kibakwe', 'Mpwapwa', 'Rudi']
            ],
            'Geita' => [
                'Bukombe' => ['Bukombe', 'Iyogelo', 'Lyambamgongo', 'Namonge', 'Ushirombo'],
                'Chato' => ['Bwanga', 'Chato', 'Kachwamba', 'Muganza', 'Nyamirembe'],
                'Geita DC' => ['Butundwe', 'Kamena', 'Kashishi', 'Katoro', 'Nyarugusu'],
                'Geita TC' => ['Bombambili', 'Kalangalala', 'Kasamwa', 'Mtakuja'],
                'Mbogwe' => ['Ikunguigazi', 'Lugunga', 'Mbogwe', 'Nyakafulu', 'Ushirika'],
                'Nyang\'hwale' => ['Bukwimba', 'Kafita', 'Kakora', 'Nyang\'hwale', 'Nyijundu']
            ]
        ];

        foreach ($regions as $regionName => $districts) {
            // Insert region
            $regionId = DB::table('regions')->insertGetId([
                'region_name' => $regionName
            ]);

            foreach ($districts as $districtName => $wards) {
                // Insert district
                $districtId = DB::table('districts')->insertGetId([
                    'region_id' => $regionId,
                    'district_name' => $districtName
                ]);

                // Insert wards
                foreach ($wards as $wardName) {
                    DB::table('wards')->insert([
                        'district_id' => $districtId,
                        'ward_name' => $wardName
                    ]);
                }
            }
        }

        // More regions data
        $moreRegions = [
            'Iringa' => [
                'Iringa DC' => ['Idodi', 'Kalenga', 'Kiponzelo', 'Mlowa', 'Nduli', 'Pawaga'],
                'Iringa MC' => ['Gangilonga', 'Kihesa', 'Kilolo', 'Mkwawa', 'Ruaha'],
                'Kilolo' => ['Image', 'Kilolo', 'Mahenge', 'Masisiwe', 'Ukumbi'],
                'Mufindi' => ['Ifakara', 'Kiswe', 'Mafinga', 'Malangali', 'Sadani']
            ],
            'Kagera' => [
                'Biharamulo' => ['Biharamulo', 'Kabindi', 'Nyabusozi', 'Runazi', 'Rusahunga'],
                'Bukoba DC' => ['Bugabo', 'Katerero', 'Kyamulaile', 'Maruku', 'Rubale'],
                'Bukoba MC' => ['Bakoba', 'Bilele', 'Kashai', 'Kibeta', 'Miembeni'],
                'Karagwe' => ['Bugene', 'Kayanga', 'Kituntu', 'Nyabiyonza', 'Nyaishozi'],
                'Kyerwa' => ['Kaisho', 'Kyerwa', 'Murongo', 'Nkwenda', 'Rukuraijo'],
                'Missenyi' => ['Bugorora', 'Bunazi', 'Kakunyu', 'Minziro', 'Nsunga'],
                'Muleba' => ['Kamachumu', 'Muleba', 'Nshamba', 'Nyakabango', 'Rulenge'],
                'Ngara' => ['Kanazi', 'Kibimba', 'Murusagamba', 'Ngara', 'Rulenge']
            ],
            'Katavi' => [
                'Mlele' => ['Ilela', 'Inyonga', 'Kibaoni', 'Mlele', 'Utende'],
                'Mpanda DC' => ['Kabungu', 'Karema', 'Mwese', 'Sibwesa', 'Vikonge'],
                'Mpanda MC' => ['Ilembo', 'Kakese', 'Kawajense', 'Misunkumilo', 'Mpanda']
            ],
            'Kigoma' => [
                'Buhigwe' => ['Buhigwe', 'Janda', 'Kibande', 'Munanila', 'Rusaba'],
                'Kakonko' => ['Kakonko', 'Kasanda', 'Kasuga', 'Kizazi', 'Muhange'],
                'Kasulu DC' => ['Buhoro', 'Kasulu', 'Kitanga', 'Muzye', 'Nyakitonto'],
                'Kasulu TC' => ['Kasulu Mjini', 'Murufiti', 'Muzye', 'Nyansha', 'Rungwe'],
                'Kibondo' => ['Busagara', 'Kibondo', 'Kitahana', 'Mabamba', 'Misezero'],
                'Kigoma DC' => ['Mahembe', 'Makere', 'Mwandiga', 'Simbo', 'Ziwani'],
                'Kigoma MC' => ['Bangwe', 'Gungu', 'Kipampa', 'Mwanga', 'Rusimbi'],
                'Uvinza' => ['Nguruka', 'Sunuka', 'Uvinza', 'Zimamoto']
            ]
        ];

        foreach ($moreRegions as $regionName => $districts) {
            // Insert region
            $regionId = DB::table('regions')->insertGetId([
                'region_name' => $regionName
            ]);

            foreach ($districts as $districtName => $wards) {
                // Insert district
                $districtId = DB::table('districts')->insertGetId([
                    'region_id' => $regionId,
                    'district_name' => $districtName
                ]);

                // Insert wards
                foreach ($wards as $wardName) {
                    DB::table('wards')->insert([
                        'district_id' => $districtId,
                        'ward_name' => $wardName
                    ]);
                }
            }
        }

        // Even more regions
        $evenMoreRegions = [
            'Kilimanjaro' => [
                'Hai' => ['Bomang\'ombe', 'Machame', 'Masama', 'Rundugai'],
                'Moshi DC' => ['Kibosho', 'Kilema', 'Kirua Vunjo', 'Old Moshi', 'Uru'],
                'Moshi MC' => ['Karanga', 'Kiboriloni', 'Kilimanjaro', 'Mjimpya', 'Pasua'],
                'Mwanga' => ['Jipe', 'Kifula', 'Kigonigoni', 'Mwanga', 'Lang\'ata'],
                'Rombo' => ['Holili', 'Mengwe', 'Mkuu', 'Tarakea', 'Ushiri'],
                'Same' => ['Hedaru', 'Kisiwani', 'Mamba', 'Same', 'Suji'],
                'Siha' => ['Gararagua', 'Kashashi', 'Ngarenairobi', 'Siha', 'Sumu']
            ],
            'Lindi' => [
                'Kilwa' => ['Kikole', 'Kilwa Masoko', 'Kipatimu', 'Miteja', 'Tingi'],
                'Lindi DC' => ['Kitomanga', 'Mandwanga', 'Mnolela', 'Nyangao', 'Rutamba'],
                'Lindi MC' => ['Jamhuri', 'Makonde', 'Msinjahili', 'Ndoro', 'Rahaleo'],
                'Liwale' => ['Kiangara', 'Liwale B', 'Makata', 'Mlembwe', 'Nangano'],
                'Nachingwea' => ['Lionja', 'Nachingwea', 'Nambambo', 'Naipanga', 'Stendi'],
                'Ruangwa' => ['Chinongwe', 'Mandawa', 'Nandagala', 'Ruangwa', 'Mnacho']
            ],
            'Manyara' => [
                'Babati DC' => ['Babati', 'Gallapo', 'Magugu', 'Makole', 'Qash'],
                'Babati TC' => ['Babati', 'Bagara', 'Bonga', 'Maisaka', 'Singe'],
                'Hanang' => ['Bassotu', 'Endasak', 'Getanuwas', 'Hanang', 'Katesh'],
                'Kiteto' => ['Dosidosi', 'Kibaya', 'Lengusero', 'Makame', 'Ndedo'],
                'Mbulu' => ['Daudi', 'Dongobesh', 'Haydom', 'Mbulu', 'Tlawi'],
                'Simanjiro' => ['Emboreet', 'Mererani', 'Orkesumet', 'Shambarai', 'Terrat']
            ],
            'Mara' => [
                'Bunda' => ['Bunda', 'Kunzugu', 'Mihingo', 'Nyamuswa', 'Wariku'],
                'Butiama' => ['Butiama', 'Kyanyari', 'Masaba', 'Nyegina', 'Butuguri'],
                'Musoma DC' => ['Bukumi', 'Bukura', 'Musoma', 'Nyamrandirira', 'Suguti'],
                'Musoma MC' => ['Bweri', 'Iringo', 'Kitaji', 'Mukendo', 'Nyasho'],
                'Rorya' => ['Bukura', 'Kisesa', 'Kigonsera', 'Nyamwaga', 'Tai'],
                'Serengeti' => ['Ikoma', 'Kisaka', 'Machochwe', 'Mugumu', 'Natta'],
                'Tarime' => ['Bomani', 'Gorong\'a', 'Nyamwaga', 'Sirari', 'Tarime']
            ]
        ];

        foreach ($evenMoreRegions as $regionName => $districts) {
            // Insert region
            $regionId = DB::table('regions')->insertGetId([
                'region_name' => $regionName
            ]);

            foreach ($districts as $districtName => $wards) {
                // Insert district
                $districtId = DB::table('districts')->insertGetId([
                    'region_id' => $regionId,
                    'district_name' => $districtName
                ]);

                // Insert wards
                foreach ($wards as $wardName) {
                    DB::table('wards')->insert([
                        'district_id' => $districtId,
                        'ward_name' => $wardName
                    ]);
                }
            }
        }

        // Final set of regions
        $finalRegions = [
            'Mbeya' => [
                'Mbeya City' => ['Forest', 'Iyunga', 'Majengo', 'Nzovwe', 'Sisimba'],
                'Mbeya DC' => ['Isangati', 'Iwindi', 'Santilya', 'Tembela', 'Usongwe'],
                'Chunya' => ['Chunya', 'Ifumbo', 'Makongolosi', 'Mbuyuni', 'Mtande'],
                'Kyela' => ['Ikama', 'Ipinda', 'Kyela', 'Matema', 'Ngana'],
                'Rungwe' => ['Busokelo', 'Kiwira', 'Masoko', 'Tukuyu', 'Upuguso']
            ],
            'Morogoro' => [
                'Morogoro MC' => ['Bigwa', 'Kichangani', 'Kilakala', 'Mwembesongo', 'Sultan Area'],
                'Morogoro DC' => ['Gwata', 'Mikese', 'Mkambarani', 'Ngerengere', 'Tununguo'],
                'Kilombero' => ['Ifakara', 'Kibaoni', 'Lumemo', 'Mang\'ula', 'Mlimba'],
                'Kilosa' => ['Dumila', 'Kimamba', 'Kilosa', 'Mikumi', 'Ruaha'],
                'Mvomero' => ['Dakawa', 'Mlali', 'Mvomero', 'Turiani', 'Wami']
            ],
            'Mtwara' => [
                'Mtwara MC' => ['Chikongola', 'Chuno', 'Ligula', 'Magomeni', 'Ufukoni'],
                'Mtwara DC' => ['Dihimba', 'Mayanga', 'Nanyamba', 'Ndumbwe', 'Ziwani'],
                'Masasi' => ['Chilulumo', 'Chikundi', 'Lukuledi', 'Masasi', 'Mchauru'],
                'Nanyumbu' => ['Chipuputa', 'Mangaka', 'Nanyumbu', 'Napacho', 'Nangomba'],
                'Newala' => ['Kitangari', 'Luchingu', 'Makote', 'Newala', 'Tandahimba']
            ],
            'Njombe' => [
                'Njombe TC' => ['Ihanga', 'Luponde', 'Matola', 'Njombe', 'Ramadhani'],
                'Njombe DC' => ['Idamba', 'Luduga', 'Lupembe', 'Makambako', 'Wanging\'ombe'],
                'Ludewa' => ['Ludewa', 'Lugarawa', 'Makonde', 'Mlangali', 'Mwembetogwa'],
                'Makete' => ['Bulongwa', 'Iwawa', 'Kipagalo', 'Makete', 'Matamba']
            ],
            'Pwani' => [
                'Bagamoyo' => ['Bagamoyo', 'Kaole', 'Magomeni', 'Mlingotini', 'Zinga'],
                'Kibaha' => ['Kibaha', 'Magindu', 'Mlandizi', 'Ruvu', 'Visiga'],
                'Kisarawe' => ['Kisarawe', 'Maneromango', 'Masaki', 'Mzenga', 'Vihingo'],
                'Mafia' => ['Baleni', 'Jibondo', 'Kilindoni', 'Kiegeani', 'Ndagoni'],
                'Mkuranga' => ['Kisiju', 'Mkamba', 'Mkuranga', 'Nyamato', 'Shungubweni'],
                'Rufiji' => ['Bungu', 'Ikwiriri', 'Kibiti', 'Muhoro', 'Utete']
            ],
            'Rukwa' => [
                'Kalambo' => ['Kasanga', 'Kalambo', 'Matai', 'Mwimbi', 'Sopa'],
                'Nkasi' => ['Chala', 'Kate', 'Kirando', 'Namanyere', 'Wampembe'],
                'Sumbawanga DC' => ['Kaengesa', 'Laela', 'Miangalua', 'Mtowisa', 'Sandulula'],
                'Sumbawanga MC' => ['Chanji', 'Izia', 'Katandala', 'Malangali', 'Sumbawanga']
            ],
            'Ruvuma' => [
                'Mbinga' => ['Kigonsera', 'Litembo', 'Mbinga', 'Mpepai', 'Rutukira'],
                'Namtumbo' => ['Hanga', 'Ligera', 'Luegu', 'Namtumbo', 'Rwinga'],
                'Songea DC' => ['Gumbiro', 'Ligula', 'Madaba', 'Matimira', 'Peramiho'],
                'Songea MC' => ['Bombambili', 'Lizaboni', 'Majengo', 'Matarawe', 'Songea'],
                'Tunduru' => ['Matemanga', 'Nalasi', 'Namasakata', 'Tunduru', 'Vigwaza']
            ],
            'Shinyanga' => [
                'Kahama TC' => ['Busoka', 'Kahama', 'Malunga', 'Ngogwa', 'Zongomera'],
                'Kahama DC' => ['Bugarama', 'Chela', 'Isaka', 'Kagongwa', 'Ulowa'],
                'Kishapu' => ['Bubiki', 'Kishapu', 'Mwakipoya', 'Ngofila', 'Ukenyenge'],
                'Shinyanga DC' => ['Didia', 'Mwakitolyo', 'Pandagichiza', 'Samuye', 'Usule'],
                'Shinyanga MC' => ['Ibadakuli', 'Kambarage', 'Mwamalili', 'Ndala', 'Ngokolo']
            ],
            'Simiyu' => [
                'Bariadi' => ['Bariadi', 'Dutwa', 'Nkololo', 'Somanda', 'Zanzui'],
                'Busega' => ['Kabita', 'Kalemela', 'Lamadi', 'Nyaluhande', 'Shigala'],
                'Itilima' => ['Bumera', 'Chinamili', 'Lagangabilili', 'Mhunze', 'Zagayu'],
                'Maswa' => ['Buchambi', 'Maswa', 'Nguliguli', 'Senani', 'Zegu'],
                'Meatu' => ['Kisesa', 'Lubiga', 'Mwabuma', 'Mwabusalu', 'Mwandoya']
            ],
            'Singida' => [
                'Ikungi' => ['Ikungi', 'Mungaa', 'Muhintiri', 'Sepuka', 'Siuyu'],
                'Iramba' => ['Kiomboi', 'Kinampanda', 'Ndago', 'Shelui', 'Urugu'],
                'Manyoni' => ['Chikuyu', 'Heka', 'Manyoni', 'Mkwese', 'Saranda'],
                'Mkalama' => ['Iguguno', 'Kinyangiri', 'Mkalama', 'Nduguti', 'Nkinto'],
                'Singida DC' => ['Ilongero', 'Maghojoa', 'Msisi', 'Mudida', 'Singida'],
                'Singida MC' => ['Mandewa', 'Mawewe', 'Misuna', 'Mtipa', 'Uhamaka']
            ],
            'Tabora' => [
                'Igunga' => ['Igurubi', 'Igunga', 'Itunduru', 'Mbutu', 'Ziba'],
                'Kaliua' => ['Igagala', 'Kaliua', 'Kazaroho', 'Ukumbi Siganga', 'Usinge'],
                'Nzega' => ['Bukene', 'Mambali', 'Nzega', 'Puge', 'Wela'],
                'Sikonge' => ['Kipanga', 'Kitunda', 'Pangale', 'Sikonge', 'Tutuo'],
                'Tabora MC' => ['Cheyo', 'Gongoni', 'Ipuli', 'Itetemia', 'Kiloleni'],
                'Urambo' => ['Kapilula', 'Muungano', 'Urambo', 'Usoke', 'Vumilia'],
                'Uyui' => ['Goweko', 'Ilangali', 'Kibondo', 'Loya', 'Uyui']
            ],
            'Tanga' => [
                'Handeni DC' => ['Kabuku', 'Kwamatuku', 'Mkata', 'Sindeni', 'Vibaoni'],
                'Handeni TC' => ['Chanika', 'Handeni', 'Kideleko', 'Misima', 'Sindeni'],
                'Kilindi' => ['Kimbe', 'Kisangasa', 'Kwediboma', 'Masagalu', 'Songe'],
                'Korogwe TC' => ['Korogwe', 'Kwamndolwa', 'Magoma', 'Msambiazi', 'Old Korogwe'],
                'Korogwe DC' => ['Bungu', 'Dindira', 'Kerenge', 'Magoma', 'Mombo'],
                'Lushoto' => ['Lushoto', 'Mlalo', 'Mtae', 'Soni', 'Ubiri'],
                'Muheza' => ['Amani', 'Kisiwani', 'Muheza', 'Ngomeni', 'Tongwe'],
                'Mkinga' => ['Daluni', 'Duga', 'Maramba', 'Mkinga', 'Pangani'],
                'Pangani' => ['Bushiri', 'Kipumbwi', 'Mikinguni', 'Pangani', 'Tungamaa'],
                'Tanga City' => ['Central', 'Chumbageni', 'Makorora', 'Ngamiani', 'Pongwe']
            ]
        ];

        foreach ($finalRegions as $regionName => $districts) {
            // Insert region
            $regionId = DB::table('regions')->insertGetId([
                'region_name' => $regionName
            ]);

            foreach ($districts as $districtName => $wards) {
                // Insert district
                $districtId = DB::table('districts')->insertGetId([
                    'region_id' => $regionId,
                    'district_name' => $districtName
                ]);

                // Insert wards
                foreach ($wards as $wardName) {
                    DB::table('wards')->insert([
                        'district_id' => $districtId,
                        'ward_name' => $wardName
                    ]);
                }
            }
        }

        // Add missing mainland and Zanzibar regions
        $missingRegions = [
            'Songwe' => [
                'Ileje' => ['Bulambya', 'Ikinga', 'Ileje', 'Isongole', 'Itumba'],
                'Mbozi' => ['Ihanda', 'Iyula', 'Mbozi', 'Mlowo', 'Vwawa'],
                'Momba' => ['Chilulumo', 'Chitete', 'Msangano', 'Ndalambo', 'Tunduma']
            ],
            'Kaskazini Pemba' => [
                'Micheweni' => ['Konde', 'Micheweni', 'Msuka', 'Shumba', 'Wingwi'],
                'Wete' => ['Gando', 'Kisiwani', 'Ole', 'Pandani', 'Wete']
            ],
            'Kusini Pemba' => [
                'Chake Chake' => ['Chake Chake', 'Ng\'ambwa', 'Pujini', 'Vitongoji', 'Wesha'],
                'Mkoani' => ['Chokocho', 'Kengeja', 'Mkoani', 'Mtambile', 'Wambaa']
            ],
            'Kaskazini Unguja' => [
                'Kaskazini A' => ['Chaani', 'Kijini', 'Nungwi', 'Tumbatu', 'Unguja'],
                'Kaskazini B' => ['Donge', 'Mahonda', 'Mkoko', 'Mwera', 'Upenja']
            ],
            'Kusini Unguja' => [
                'Kati' => ['Chwaka', 'Dunga', 'Kiboje', 'Umbuji', 'Uroa'],
                'Kusini' => ['Bwejuu', 'Jambiani', 'Kizimkazi', 'Makunduchi', 'Paje']
            ],
            'Mjini Magharibi' => [
                'Magharibi' => ['Fumba', 'Kiembesamaki', 'Magogoni', 'Mwera', 'Unguja Ukuu'],
                'Mjini' => ['Kwahani', 'Malindi', 'Mlandege', 'Shangani', 'Stone Town']
            ]
        ];

        foreach ($missingRegions as $regionName => $districts) {
            // Insert region
            $regionId = DB::table('regions')->insertGetId([
                'region_name' => $regionName
            ]);

            foreach ($districts as $districtName => $wards) {
                // Insert district
                $districtId = DB::table('districts')->insertGetId([
                    'region_id' => $regionId,
                    'district_name' => $districtName
                ]);

                // Insert wards
                foreach ($wards as $wardName) {
                    DB::table('wards')->insert([
                        'district_id' => $districtId,
                        'ward_name' => $wardName
                    ]);
                }
            }
        }

        // Add Mwanza region
        $lastRegion = [
            'Mwanza' => [
                'Ilemela' => ['Bugogwa', 'Buswelu', 'Ilemela', 'Kirumba', 'Nyamagana'],
                'Kwimba' => ['Bungulwa', 'Hungumalwa', 'Ngudu', 'Nyamilama', 'Sumve'],
                'Magu' => ['Kahangara', 'Kisesa', 'Magu', 'Nyanguge', 'Sukuma'],
                'Misungwi' => ['Busongo', 'Kanyelele', 'Misungwi', 'Usagara', 'Yamamoto'],
                'Nyamagana' => ['Buhongwa', 'Igoma', 'Mkolani', 'Nyamagana', 'Pamba'],
                'Sengerema' => ['Busisi', 'Buyagu', 'Katunguru', 'Nyamatongo', 'Sengerema'],
                'Ukerewe' => ['Bukindo', 'Muriti', 'Nansio', 'Ng\'ambwa', 'Ukerewe']
            ]
        ];

        foreach ($lastRegion as $regionName => $districts) {
            // Insert region
            $regionId = DB::table('regions')->insertGetId([
                'region_name' => $regionName
            ]);

            foreach ($districts as $districtName => $wards) {
                // Insert district
                $districtId = DB::table('districts')->insertGetId([
                    'region_id' => $regionId,
                    'district_name' => $districtName
                ]);

                // Insert wards
                foreach ($wards as $wardName) {
                    DB::table('wards')->insert([
                        'district_id' => $districtId,
                        'ward_name' => $wardName
                    ]);
                }
            }
        }
    }
}
