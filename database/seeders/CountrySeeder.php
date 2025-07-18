<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            // Paesi europei - Difficoltà bassa (1-2)
            ['name' => 'Italia', 'code' => 'IT', 'flag_url' => 'https://flagcdn.com/w320/it.png', 'capital' => 'Roma', 'continent' => 'Europa', 'difficulty_level' => 1],
            ['name' => 'Francia', 'code' => 'FR', 'flag_url' => 'https://flagcdn.com/w320/fr.png', 'capital' => 'Parigi', 'continent' => 'Europa', 'difficulty_level' => 1],
            ['name' => 'Germania', 'code' => 'DE', 'flag_url' => 'https://flagcdn.com/w320/de.png', 'capital' => 'Berlino', 'continent' => 'Europa', 'difficulty_level' => 1],
            ['name' => 'Regno Unito', 'code' => 'GB', 'flag_url' => 'https://flagcdn.com/w320/gb.png', 'capital' => 'Londra', 'continent' => 'Europa', 'difficulty_level' => 1],
            ['name' => 'Spagna', 'code' => 'ES', 'flag_url' => 'https://flagcdn.com/w320/es.png', 'capital' => 'Madrid', 'continent' => 'Europa', 'difficulty_level' => 1],
            ['name' => 'Portogallo', 'code' => 'PT', 'flag_url' => 'https://flagcdn.com/w320/pt.png', 'capital' => 'Lisbona', 'continent' => 'Europa', 'difficulty_level' => 2],
            ['name' => 'Paesi Bassi', 'code' => 'NL', 'flag_url' => 'https://flagcdn.com/w320/nl.png', 'capital' => 'Amsterdam', 'continent' => 'Europa', 'difficulty_level' => 2],
            ['name' => 'Belgio', 'code' => 'BE', 'flag_url' => 'https://flagcdn.com/w320/be.png', 'capital' => 'Bruxelles', 'continent' => 'Europa', 'difficulty_level' => 2],
            ['name' => 'Svizzera', 'code' => 'CH', 'flag_url' => 'https://flagcdn.com/w320/ch.png', 'capital' => 'Berna', 'continent' => 'Europa', 'difficulty_level' => 2],
            ['name' => 'Austria', 'code' => 'AT', 'flag_url' => 'https://flagcdn.com/w320/at.png', 'capital' => 'Vienna', 'continent' => 'Europa', 'difficulty_level' => 2],

            // Paesi americani - Difficoltà media (2-3)
            ['name' => 'Stati Uniti', 'code' => 'US', 'flag_url' => 'https://flagcdn.com/w320/us.png', 'capital' => 'Washington', 'continent' => 'Nord America', 'difficulty_level' => 1],
            ['name' => 'Canada', 'code' => 'CA', 'flag_url' => 'https://flagcdn.com/w320/ca.png', 'capital' => 'Ottawa', 'continent' => 'Nord America', 'difficulty_level' => 1],
            ['name' => 'Messico', 'code' => 'MX', 'flag_url' => 'https://flagcdn.com/w320/mx.png', 'capital' => 'Città del Messico', 'continent' => 'Nord America', 'difficulty_level' => 2],
            ['name' => 'Brasile', 'code' => 'BR', 'flag_url' => 'https://flagcdn.com/w320/br.png', 'capital' => 'Brasília', 'continent' => 'Sud America', 'difficulty_level' => 2],
            ['name' => 'Argentina', 'code' => 'AR', 'flag_url' => 'https://flagcdn.com/w320/ar.png', 'capital' => 'Buenos Aires', 'continent' => 'Sud America', 'difficulty_level' => 2],
            ['name' => 'Cile', 'code' => 'CL', 'flag_url' => 'https://flagcdn.com/w320/cl.png', 'capital' => 'Santiago', 'continent' => 'Sud America', 'difficulty_level' => 3],
            ['name' => 'Colombia', 'code' => 'CO', 'flag_url' => 'https://flagcdn.com/w320/co.png', 'capital' => 'Bogotá', 'continent' => 'Sud America', 'difficulty_level' => 3],
            ['name' => 'Perù', 'code' => 'PE', 'flag_url' => 'https://flagcdn.com/w320/pe.png', 'capital' => 'Lima', 'continent' => 'Sud America', 'difficulty_level' => 3],

            // Paesi asiatici - Difficoltà varia (1-4)
            ['name' => 'Giappone', 'code' => 'JP', 'flag_url' => 'https://flagcdn.com/w320/jp.png', 'capital' => 'Tokyo', 'continent' => 'Asia', 'difficulty_level' => 1],
            ['name' => 'Cina', 'code' => 'CN', 'flag_url' => 'https://flagcdn.com/w320/cn.png', 'capital' => 'Pechino', 'continent' => 'Asia', 'difficulty_level' => 1],
            ['name' => 'India', 'code' => 'IN', 'flag_url' => 'https://flagcdn.com/w320/in.png', 'capital' => 'Nuova Delhi', 'continent' => 'Asia', 'difficulty_level' => 2],
            ['name' => 'Corea del Sud', 'code' => 'KR', 'flag_url' => 'https://flagcdn.com/w320/kr.png', 'capital' => 'Seul', 'continent' => 'Asia', 'difficulty_level' => 2],
            ['name' => 'Thailandia', 'code' => 'TH', 'flag_url' => 'https://flagcdn.com/w320/th.png', 'capital' => 'Bangkok', 'continent' => 'Asia', 'difficulty_level' => 3],
            ['name' => 'Indonesia', 'code' => 'ID', 'flag_url' => 'https://flagcdn.com/w320/id.png', 'capital' => 'Jakarta', 'continent' => 'Asia', 'difficulty_level' => 3],
            ['name' => 'Vietnam', 'code' => 'VN', 'flag_url' => 'https://flagcdn.com/w320/vn.png', 'capital' => 'Hanoi', 'continent' => 'Asia', 'difficulty_level' => 3],
            ['name' => 'Malaysia', 'code' => 'MY', 'flag_url' => 'https://flagcdn.com/w320/my.png', 'capital' => 'Kuala Lumpur', 'continent' => 'Asia', 'difficulty_level' => 4],

            // Paesi africani - Difficoltà alta (3-5)
            ['name' => 'Sud Africa', 'code' => 'ZA', 'flag_url' => 'https://flagcdn.com/w320/za.png', 'capital' => 'Città del Capo', 'continent' => 'Africa', 'difficulty_level' => 3],
            ['name' => 'Egitto', 'code' => 'EG', 'flag_url' => 'https://flagcdn.com/w320/eg.png', 'capital' => 'Il Cairo', 'continent' => 'Africa', 'difficulty_level' => 2],
            ['name' => 'Nigeria', 'code' => 'NG', 'flag_url' => 'https://flagcdn.com/w320/ng.png', 'capital' => 'Abuja', 'continent' => 'Africa', 'difficulty_level' => 3],
            ['name' => 'Kenya', 'code' => 'KE', 'flag_url' => 'https://flagcdn.com/w320/ke.png', 'capital' => 'Nairobi', 'continent' => 'Africa', 'difficulty_level' => 4],
            ['name' => 'Ghana', 'code' => 'GH', 'flag_url' => 'https://flagcdn.com/w320/gh.png', 'capital' => 'Accra', 'continent' => 'Africa', 'difficulty_level' => 4],
            ['name' => 'Marocco', 'code' => 'MA', 'flag_url' => 'https://flagcdn.com/w320/ma.png', 'capital' => 'Rabat', 'continent' => 'Africa', 'difficulty_level' => 3],

            // Paesi oceanici - Difficoltà varia (2-4)
            ['name' => 'Australia', 'code' => 'AU', 'flag_url' => 'https://flagcdn.com/w320/au.png', 'capital' => 'Canberra', 'continent' => 'Oceania', 'difficulty_level' => 1],
            ['name' => 'Nuova Zelanda', 'code' => 'NZ', 'flag_url' => 'https://flagcdn.com/w320/nz.png', 'capital' => 'Wellington', 'continent' => 'Oceania', 'difficulty_level' => 2],
            ['name' => 'Fiji', 'code' => 'FJ', 'flag_url' => 'https://flagcdn.com/w320/fj.png', 'capital' => 'Suva', 'continent' => 'Oceania', 'difficulty_level' => 4],

            // Paesi nordici - Difficoltà media-alta (3-4)
            ['name' => 'Norvegia', 'code' => 'NO', 'flag_url' => 'https://flagcdn.com/w320/no.png', 'capital' => 'Oslo', 'continent' => 'Europa', 'difficulty_level' => 3],
            ['name' => 'Svezia', 'code' => 'SE', 'flag_url' => 'https://flagcdn.com/w320/se.png', 'capital' => 'Stoccolma', 'continent' => 'Europa', 'difficulty_level' => 3],
            ['name' => 'Danimarca', 'code' => 'DK', 'flag_url' => 'https://flagcdn.com/w320/dk.png', 'capital' => 'Copenaghen', 'continent' => 'Europa', 'difficulty_level' => 3],
            ['name' => 'Finlandia', 'code' => 'FI', 'flag_url' => 'https://flagcdn.com/w320/fi.png', 'capital' => 'Helsinki', 'continent' => 'Europa', 'difficulty_level' => 4],
            ['name' => 'Islanda', 'code' => 'IS', 'flag_url' => 'https://flagcdn.com/w320/is.png', 'capital' => 'Reykjavik', 'continent' => 'Europa', 'difficulty_level' => 4],

            // Altri paesi europei - Difficoltà varia (2-5)
            ['name' => 'Polonia', 'code' => 'PL', 'flag_url' => 'https://flagcdn.com/w320/pl.png', 'capital' => 'Varsavia', 'continent' => 'Europa', 'difficulty_level' => 2],
            ['name' => 'Repubblica Ceca', 'code' => 'CZ', 'flag_url' => 'https://flagcdn.com/w320/cz.png', 'capital' => 'Praga', 'continent' => 'Europa', 'difficulty_level' => 3],
            ['name' => 'Slovacchia', 'code' => 'SK', 'flag_url' => 'https://flagcdn.com/w320/sk.png', 'capital' => 'Bratislava', 'continent' => 'Europa', 'difficulty_level' => 4],
            ['name' => 'Ungheria', 'code' => 'HU', 'flag_url' => 'https://flagcdn.com/w320/hu.png', 'capital' => 'Budapest', 'continent' => 'Europa', 'difficulty_level' => 3],
            ['name' => 'Romania', 'code' => 'RO', 'flag_url' => 'https://flagcdn.com/w320/ro.png', 'capital' => 'Bucarest', 'continent' => 'Europa', 'difficulty_level' => 3],
            ['name' => 'Bulgaria', 'code' => 'BG', 'flag_url' => 'https://flagcdn.com/w320/bg.png', 'capital' => 'Sofia', 'continent' => 'Europa', 'difficulty_level' => 4],
            ['name' => 'Grecia', 'code' => 'GR', 'flag_url' => 'https://flagcdn.com/w320/gr.png', 'capital' => 'Atene', 'continent' => 'Europa', 'difficulty_level' => 2],
            ['name' => 'Croazia', 'code' => 'HR', 'flag_url' => 'https://flagcdn.com/w320/hr.png', 'capital' => 'Zagabria', 'continent' => 'Europa', 'difficulty_level' => 3],
            ['name' => 'Serbia', 'code' => 'RS', 'flag_url' => 'https://flagcdn.com/w320/rs.png', 'capital' => 'Belgrado', 'continent' => 'Europa', 'difficulty_level' => 4],
            ['name' => 'Bosnia ed Erzegovina', 'code' => 'BA', 'flag_url' => 'https://flagcdn.com/w320/ba.png', 'capital' => 'Sarajevo', 'continent' => 'Europa', 'difficulty_level' => 4],
            ['name' => 'Slovenia', 'code' => 'SI', 'flag_url' => 'https://flagcdn.com/w320/si.png', 'capital' => 'Lubiana', 'continent' => 'Europa', 'difficulty_level' => 5],
            ['name' => 'Montenegro', 'code' => 'ME', 'flag_url' => 'https://flagcdn.com/w320/me.png', 'capital' => 'Podgorica', 'continent' => 'Europa', 'difficulty_level' => 5],
            ['name' => 'Macedonia del Nord', 'code' => 'MK', 'flag_url' => 'https://flagcdn.com/w320/mk.png', 'capital' => 'Skopje', 'continent' => 'Europa', 'difficulty_level' => 5],
            ['name' => 'Albania', 'code' => 'AL', 'flag_url' => 'https://flagcdn.com/w320/al.png', 'capital' => 'Tirana', 'continent' => 'Europa', 'difficulty_level' => 4],
            ['name' => 'Lituania', 'code' => 'LT', 'flag_url' => 'https://flagcdn.com/w320/lt.png', 'capital' => 'Vilnius', 'continent' => 'Europa', 'difficulty_level' => 4],
            ['name' => 'Lettonia', 'code' => 'LV', 'flag_url' => 'https://flagcdn.com/w320/lv.png', 'capital' => 'Riga', 'continent' => 'Europa', 'difficulty_level' => 4],
            ['name' => 'Estonia', 'code' => 'EE', 'flag_url' => 'https://flagcdn.com/w320/ee.png', 'capital' => 'Tallinn', 'continent' => 'Europa', 'difficulty_level' => 5],
            ['name' => 'Ucraina', 'code' => 'UA', 'flag_url' => 'https://flagcdn.com/w320/ua.png', 'capital' => 'Kiev', 'continent' => 'Europa', 'difficulty_level' => 3],
            ['name' => 'Bielorussia', 'code' => 'BY', 'flag_url' => 'https://flagcdn.com/w320/by.png', 'capital' => 'Minsk', 'continent' => 'Europa', 'difficulty_level' => 4],
            ['name' => 'Moldavia', 'code' => 'MD', 'flag_url' => 'https://flagcdn.com/w320/md.png', 'capital' => 'Chisinau', 'continent' => 'Europa', 'difficulty_level' => 5],
            ['name' => 'Irlanda', 'code' => 'IE', 'flag_url' => 'https://flagcdn.com/w320/ie.png', 'capital' => 'Dublino', 'continent' => 'Europa', 'difficulty_level' => 2],
            ['name' => 'Lussemburgo', 'code' => 'LU', 'flag_url' => 'https://flagcdn.com/w320/lu.png', 'capital' => 'Lussemburgo', 'continent' => 'Europa', 'difficulty_level' => 5],
            ['name' => 'Malta', 'code' => 'MT', 'flag_url' => 'https://flagcdn.com/w320/mt.png', 'capital' => 'La Valletta', 'continent' => 'Europa', 'difficulty_level' => 5],
            ['name' => 'Cipro', 'code' => 'CY', 'flag_url' => 'https://flagcdn.com/w320/cy.png', 'capital' => 'Nicosia', 'continent' => 'Europa', 'difficulty_level' => 4],

            // Paesi asiatici aggiuntivi - Difficoltà varia (2-5)
            ['name' => 'Russia', 'code' => 'RU', 'flag_url' => 'https://flagcdn.com/w320/ru.png', 'capital' => 'Mosca', 'continent' => 'Asia', 'difficulty_level' => 2],
            ['name' => 'Turchia', 'code' => 'TR', 'flag_url' => 'https://flagcdn.com/w320/tr.png', 'capital' => 'Ankara', 'continent' => 'Asia', 'difficulty_level' => 2],
            ['name' => 'Iran', 'code' => 'IR', 'flag_url' => 'https://flagcdn.com/w320/ir.png', 'capital' => 'Teheran', 'continent' => 'Asia', 'difficulty_level' => 3],
            ['name' => 'Iraq', 'code' => 'IQ', 'flag_url' => 'https://flagcdn.com/w320/iq.png', 'capital' => 'Baghdad', 'continent' => 'Asia', 'difficulty_level' => 3],
            ['name' => 'Israele', 'code' => 'IL', 'flag_url' => 'https://flagcdn.com/w320/il.png', 'capital' => 'Gerusalemme', 'continent' => 'Asia', 'difficulty_level' => 3],
            ['name' => 'Giordania', 'code' => 'JO', 'flag_url' => 'https://flagcdn.com/w320/jo.png', 'capital' => 'Amman', 'continent' => 'Asia', 'difficulty_level' => 4],
            ['name' => 'Libano', 'code' => 'LB', 'flag_url' => 'https://flagcdn.com/w320/lb.png', 'capital' => 'Beirut', 'continent' => 'Asia', 'difficulty_level' => 4],
            ['name' => 'Siria', 'code' => 'SY', 'flag_url' => 'https://flagcdn.com/w320/sy.png', 'capital' => 'Damasco', 'continent' => 'Asia', 'difficulty_level' => 4],
            ['name' => 'Arabia Saudita', 'code' => 'SA', 'flag_url' => 'https://flagcdn.com/w320/sa.png', 'capital' => 'Riad', 'continent' => 'Asia', 'difficulty_level' => 3],
            ['name' => 'Emirati Arabi Uniti', 'code' => 'AE', 'flag_url' => 'https://flagcdn.com/w320/ae.png', 'capital' => 'Abu Dhabi', 'continent' => 'Asia', 'difficulty_level' => 3],
            ['name' => 'Qatar', 'code' => 'QA', 'flag_url' => 'https://flagcdn.com/w320/qa.png', 'capital' => 'Doha', 'continent' => 'Asia', 'difficulty_level' => 4],
            ['name' => 'Kuwait', 'code' => 'KW', 'flag_url' => 'https://flagcdn.com/w320/kw.png', 'capital' => 'Kuwait City', 'continent' => 'Asia', 'difficulty_level' => 4],
            ['name' => 'Bahrein', 'code' => 'BH', 'flag_url' => 'https://flagcdn.com/w320/bh.png', 'capital' => 'Manama', 'continent' => 'Asia', 'difficulty_level' => 5],
            ['name' => 'Oman', 'code' => 'OM', 'flag_url' => 'https://flagcdn.com/w320/om.png', 'capital' => 'Muscat', 'continent' => 'Asia', 'difficulty_level' => 4],
            ['name' => 'Yemen', 'code' => 'YE', 'flag_url' => 'https://flagcdn.com/w320/ye.png', 'capital' => 'Sana\'a', 'continent' => 'Asia', 'difficulty_level' => 5],
            ['name' => 'Afghanistan', 'code' => 'AF', 'flag_url' => 'https://flagcdn.com/w320/af.png', 'capital' => 'Kabul', 'continent' => 'Asia', 'difficulty_level' => 4],
            ['name' => 'Pakistan', 'code' => 'PK', 'flag_url' => 'https://flagcdn.com/w320/pk.png', 'capital' => 'Islamabad', 'continent' => 'Asia', 'difficulty_level' => 3],
            ['name' => 'Bangladesh', 'code' => 'BD', 'flag_url' => 'https://flagcdn.com/w320/bd.png', 'capital' => 'Dhaka', 'continent' => 'Asia', 'difficulty_level' => 4],
            ['name' => 'Sri Lanka', 'code' => 'LK', 'flag_url' => 'https://flagcdn.com/w320/lk.png', 'capital' => 'Colombo', 'continent' => 'Asia', 'difficulty_level' => 4],
            ['name' => 'Nepal', 'code' => 'NP', 'flag_url' => 'https://flagcdn.com/w320/np.png', 'capital' => 'Kathmandu', 'continent' => 'Asia', 'difficulty_level' => 4],
            ['name' => 'Bhutan', 'code' => 'BT', 'flag_url' => 'https://flagcdn.com/w320/bt.png', 'capital' => 'Thimphu', 'continent' => 'Asia', 'difficulty_level' => 5],
            ['name' => 'Maldive', 'code' => 'MV', 'flag_url' => 'https://flagcdn.com/w320/mv.png', 'capital' => 'Malé', 'continent' => 'Asia', 'difficulty_level' => 5],
            ['name' => 'Myanmar', 'code' => 'MM', 'flag_url' => 'https://flagcdn.com/w320/mm.png', 'capital' => 'Naypyidaw', 'continent' => 'Asia', 'difficulty_level' => 4],
            ['name' => 'Laos', 'code' => 'LA', 'flag_url' => 'https://flagcdn.com/w320/la.png', 'capital' => 'Vientiane', 'continent' => 'Asia', 'difficulty_level' => 5],
            ['name' => 'Cambogia', 'code' => 'KH', 'flag_url' => 'https://flagcdn.com/w320/kh.png', 'capital' => 'Phnom Penh', 'continent' => 'Asia', 'difficulty_level' => 4],
            ['name' => 'Filippine', 'code' => 'PH', 'flag_url' => 'https://flagcdn.com/w320/ph.png', 'capital' => 'Manila', 'continent' => 'Asia', 'difficulty_level' => 3],
            ['name' => 'Singapore', 'code' => 'SG', 'flag_url' => 'https://flagcdn.com/w320/sg.png', 'capital' => 'Singapore', 'continent' => 'Asia', 'difficulty_level' => 3],
            ['name' => 'Brunei', 'code' => 'BN', 'flag_url' => 'https://flagcdn.com/w320/bn.png', 'capital' => 'Bandar Seri Begawan', 'continent' => 'Asia', 'difficulty_level' => 5],
            ['name' => 'Corea del Nord', 'code' => 'KP', 'flag_url' => 'https://flagcdn.com/w320/kp.png', 'capital' => 'Pyongyang', 'continent' => 'Asia', 'difficulty_level' => 4],
            ['name' => 'Mongolia', 'code' => 'MN', 'flag_url' => 'https://flagcdn.com/w320/mn.png', 'capital' => 'Ulaanbaatar', 'continent' => 'Asia', 'difficulty_level' => 4],
            ['name' => 'Kazakistan', 'code' => 'KZ', 'flag_url' => 'https://flagcdn.com/w320/kz.png', 'capital' => 'Nur-Sultan', 'continent' => 'Asia', 'difficulty_level' => 4],
            ['name' => 'Uzbekistan', 'code' => 'UZ', 'flag_url' => 'https://flagcdn.com/w320/uz.png', 'capital' => 'Tashkent', 'continent' => 'Asia', 'difficulty_level' => 5],
            ['name' => 'Turkmenistan', 'code' => 'TM', 'flag_url' => 'https://flagcdn.com/w320/tm.png', 'capital' => 'Ashgabat', 'continent' => 'Asia', 'difficulty_level' => 5],
            ['name' => 'Kirghizistan', 'code' => 'KG', 'flag_url' => 'https://flagcdn.com/w320/kg.png', 'capital' => 'Bishkek', 'continent' => 'Asia', 'difficulty_level' => 5],
            ['name' => 'Tagikistan', 'code' => 'TJ', 'flag_url' => 'https://flagcdn.com/w320/tj.png', 'capital' => 'Dushanbe', 'continent' => 'Asia', 'difficulty_level' => 5],
            ['name' => 'Armenia', 'code' => 'AM', 'flag_url' => 'https://flagcdn.com/w320/am.png', 'capital' => 'Yerevan', 'continent' => 'Asia', 'difficulty_level' => 4],
            ['name' => 'Azerbaijan', 'code' => 'AZ', 'flag_url' => 'https://flagcdn.com/w320/az.png', 'capital' => 'Baku', 'continent' => 'Asia', 'difficulty_level' => 4],
            ['name' => 'Georgia', 'code' => 'GE', 'flag_url' => 'https://flagcdn.com/w320/ge.png', 'capital' => 'Tbilisi', 'continent' => 'Asia', 'difficulty_level' => 4],

            // Paesi africani aggiuntivi - Difficoltà varia (3-5)
            ['name' => 'Algeria', 'code' => 'DZ', 'flag_url' => 'https://flagcdn.com/w320/dz.png', 'capital' => 'Algeri', 'continent' => 'Africa', 'difficulty_level' => 3],
            ['name' => 'Tunisia', 'code' => 'TN', 'flag_url' => 'https://flagcdn.com/w320/tn.png', 'capital' => 'Tunisi', 'continent' => 'Africa', 'difficulty_level' => 3],
            ['name' => 'Libia', 'code' => 'LY', 'flag_url' => 'https://flagcdn.com/w320/ly.png', 'capital' => 'Tripoli', 'continent' => 'Africa', 'difficulty_level' => 4],
            ['name' => 'Sudan', 'code' => 'SD', 'flag_url' => 'https://flagcdn.com/w320/sd.png', 'capital' => 'Khartoum', 'continent' => 'Africa', 'difficulty_level' => 4],
            ['name' => 'Etiopia', 'code' => 'ET', 'flag_url' => 'https://flagcdn.com/w320/et.png', 'capital' => 'Addis Abeba', 'continent' => 'Africa', 'difficulty_level' => 4],
            ['name' => 'Somalia', 'code' => 'SO', 'flag_url' => 'https://flagcdn.com/w320/so.png', 'capital' => 'Mogadiscio', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Eritrea', 'code' => 'ER', 'flag_url' => 'https://flagcdn.com/w320/er.png', 'capital' => 'Asmara', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Gibuti', 'code' => 'DJ', 'flag_url' => 'https://flagcdn.com/w320/dj.png', 'capital' => 'Gibuti', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Tanzania', 'code' => 'TZ', 'flag_url' => 'https://flagcdn.com/w320/tz.png', 'capital' => 'Dodoma', 'continent' => 'Africa', 'difficulty_level' => 4],
            ['name' => 'Uganda', 'code' => 'UG', 'flag_url' => 'https://flagcdn.com/w320/ug.png', 'capital' => 'Kampala', 'continent' => 'Africa', 'difficulty_level' => 4],
            ['name' => 'Ruanda', 'code' => 'RW', 'flag_url' => 'https://flagcdn.com/w320/rw.png', 'capital' => 'Kigali', 'continent' => 'Africa', 'difficulty_level' => 4],
            ['name' => 'Burundi', 'code' => 'BI', 'flag_url' => 'https://flagcdn.com/w320/bi.png', 'capital' => 'Gitega', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Repubblica Democratica del Congo', 'code' => 'CD', 'flag_url' => 'https://flagcdn.com/w320/cd.png', 'capital' => 'Kinshasa', 'continent' => 'Africa', 'difficulty_level' => 4],
            ['name' => 'Repubblica del Congo', 'code' => 'CG', 'flag_url' => 'https://flagcdn.com/w320/cg.png', 'capital' => 'Brazzaville', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Repubblica Centrafricana', 'code' => 'CF', 'flag_url' => 'https://flagcdn.com/w320/cf.png', 'capital' => 'Bangui', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Camerun', 'code' => 'CM', 'flag_url' => 'https://flagcdn.com/w320/cm.png', 'capital' => 'Yaoundé', 'continent' => 'Africa', 'difficulty_level' => 4],
            ['name' => 'Gabon', 'code' => 'GA', 'flag_url' => 'https://flagcdn.com/w320/ga.png', 'capital' => 'Libreville', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Guinea Equatoriale', 'code' => 'GQ', 'flag_url' => 'https://flagcdn.com/w320/gq.png', 'capital' => 'Malabo', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'São Tomé e Príncipe', 'code' => 'ST', 'flag_url' => 'https://flagcdn.com/w320/st.png', 'capital' => 'São Tomé', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Senegal', 'code' => 'SN', 'flag_url' => 'https://flagcdn.com/w320/sn.png', 'capital' => 'Dakar', 'continent' => 'Africa', 'difficulty_level' => 4],
            ['name' => 'Gambia', 'code' => 'GM', 'flag_url' => 'https://flagcdn.com/w320/gm.png', 'capital' => 'Banjul', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Guinea-Bissau', 'code' => 'GW', 'flag_url' => 'https://flagcdn.com/w320/gw.png', 'capital' => 'Bissau', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Guinea', 'code' => 'GN', 'flag_url' => 'https://flagcdn.com/w320/gn.png', 'capital' => 'Conakry', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Sierra Leone', 'code' => 'SL', 'flag_url' => 'https://flagcdn.com/w320/sl.png', 'capital' => 'Freetown', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Liberia', 'code' => 'LR', 'flag_url' => 'https://flagcdn.com/w320/lr.png', 'capital' => 'Monrovia', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Costa d\'Avorio', 'code' => 'CI', 'flag_url' => 'https://flagcdn.com/w320/ci.png', 'capital' => 'Yamoussoukro', 'continent' => 'Africa', 'difficulty_level' => 4],
            ['name' => 'Mali', 'code' => 'ML', 'flag_url' => 'https://flagcdn.com/w320/ml.png', 'capital' => 'Bamako', 'continent' => 'Africa', 'difficulty_level' => 4],
            ['name' => 'Burkina Faso', 'code' => 'BF', 'flag_url' => 'https://flagcdn.com/w320/bf.png', 'capital' => 'Ouagadougou', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Niger', 'code' => 'NE', 'flag_url' => 'https://flagcdn.com/w320/ne.png', 'capital' => 'Niamey', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Ciad', 'code' => 'TD', 'flag_url' => 'https://flagcdn.com/w320/td.png', 'capital' => 'N\'Djamena', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Mauritania', 'code' => 'MR', 'flag_url' => 'https://flagcdn.com/w320/mr.png', 'capital' => 'Nouakchott', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Capo Verde', 'code' => 'CV', 'flag_url' => 'https://flagcdn.com/w320/cv.png', 'capital' => 'Praia', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Zambia', 'code' => 'ZM', 'flag_url' => 'https://flagcdn.com/w320/zm.png', 'capital' => 'Lusaka', 'continent' => 'Africa', 'difficulty_level' => 4],
            ['name' => 'Zimbabwe', 'code' => 'ZW', 'flag_url' => 'https://flagcdn.com/w320/zw.png', 'capital' => 'Harare', 'continent' => 'Africa', 'difficulty_level' => 4],
            ['name' => 'Botswana', 'code' => 'BW', 'flag_url' => 'https://flagcdn.com/w320/bw.png', 'capital' => 'Gaborone', 'continent' => 'Africa', 'difficulty_level' => 4],
            ['name' => 'Namibia', 'code' => 'NA', 'flag_url' => 'https://flagcdn.com/w320/na.png', 'capital' => 'Windhoek', 'continent' => 'Africa', 'difficulty_level' => 4],
            ['name' => 'Lesotho', 'code' => 'LS', 'flag_url' => 'https://flagcdn.com/w320/ls.png', 'capital' => 'Maseru', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Eswatini', 'code' => 'SZ', 'flag_url' => 'https://flagcdn.com/w320/sz.png', 'capital' => 'Mbabane', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Mozambico', 'code' => 'MZ', 'flag_url' => 'https://flagcdn.com/w320/mz.png', 'capital' => 'Maputo', 'continent' => 'Africa', 'difficulty_level' => 4],
            ['name' => 'Madagascar', 'code' => 'MG', 'flag_url' => 'https://flagcdn.com/w320/mg.png', 'capital' => 'Antananarivo', 'continent' => 'Africa', 'difficulty_level' => 4],
            ['name' => 'Mauritius', 'code' => 'MU', 'flag_url' => 'https://flagcdn.com/w320/mu.png', 'capital' => 'Port Louis', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Seychelles', 'code' => 'SC', 'flag_url' => 'https://flagcdn.com/w320/sc.png', 'capital' => 'Victoria', 'continent' => 'Africa', 'difficulty_level' => 5],
            ['name' => 'Comore', 'code' => 'KM', 'flag_url' => 'https://flagcdn.com/w320/km.png', 'capital' => 'Moroni', 'continent' => 'Africa', 'difficulty_level' => 5],

            // Paesi americani aggiuntivi - Difficoltà varia (2-5)
            ['name' => 'Guatemala', 'code' => 'GT', 'flag_url' => 'https://flagcdn.com/w320/gt.png', 'capital' => 'Guatemala City', 'continent' => 'Nord America', 'difficulty_level' => 4],
            ['name' => 'Belize', 'code' => 'BZ', 'flag_url' => 'https://flagcdn.com/w320/bz.png', 'capital' => 'Belmopan', 'continent' => 'Nord America', 'difficulty_level' => 5],
            ['name' => 'El Salvador', 'code' => 'SV', 'flag_url' => 'https://flagcdn.com/w320/sv.png', 'capital' => 'San Salvador', 'continent' => 'Nord America', 'difficulty_level' => 4],
            ['name' => 'Honduras', 'code' => 'HN', 'flag_url' => 'https://flagcdn.com/w320/hn.png', 'capital' => 'Tegucigalpa', 'continent' => 'Nord America', 'difficulty_level' => 4],
            ['name' => 'Nicaragua', 'code' => 'NI', 'flag_url' => 'https://flagcdn.com/w320/ni.png', 'capital' => 'Managua', 'continent' => 'Nord America', 'difficulty_level' => 4],
            ['name' => 'Costa Rica', 'code' => 'CR', 'flag_url' => 'https://flagcdn.com/w320/cr.png', 'capital' => 'San José', 'continent' => 'Nord America', 'difficulty_level' => 3],
            ['name' => 'Panama', 'code' => 'PA', 'flag_url' => 'https://flagcdn.com/w320/pa.png', 'capital' => 'Panama City', 'continent' => 'Nord America', 'difficulty_level' => 3],
            ['name' => 'Cuba', 'code' => 'CU', 'flag_url' => 'https://flagcdn.com/w320/cu.png', 'capital' => 'L\'Avana', 'continent' => 'Nord America', 'difficulty_level' => 2],
            ['name' => 'Giamaica', 'code' => 'JM', 'flag_url' => 'https://flagcdn.com/w320/jm.png', 'capital' => 'Kingston', 'continent' => 'Nord America', 'difficulty_level' => 3],
            ['name' => 'Haiti', 'code' => 'HT', 'flag_url' => 'https://flagcdn.com/w320/ht.png', 'capital' => 'Port-au-Prince', 'continent' => 'Nord America', 'difficulty_level' => 4],
            ['name' => 'Repubblica Dominicana', 'code' => 'DO', 'flag_url' => 'https://flagcdn.com/w320/do.png', 'capital' => 'Santo Domingo', 'continent' => 'Nord America', 'difficulty_level' => 3],
            ['name' => 'Puerto Rico', 'code' => 'PR', 'flag_url' => 'https://flagcdn.com/w320/pr.png', 'capital' => 'San Juan', 'continent' => 'Nord America', 'difficulty_level' => 4],
            ['name' => 'Trinidad e Tobago', 'code' => 'TT', 'flag_url' => 'https://flagcdn.com/w320/tt.png', 'capital' => 'Port of Spain', 'continent' => 'Nord America', 'difficulty_level' => 4],
            ['name' => 'Barbados', 'code' => 'BB', 'flag_url' => 'https://flagcdn.com/w320/bb.png', 'capital' => 'Bridgetown', 'continent' => 'Nord America', 'difficulty_level' => 5],
            ['name' => 'Saint Lucia', 'code' => 'LC', 'flag_url' => 'https://flagcdn.com/w320/lc.png', 'capital' => 'Castries', 'continent' => 'Nord America', 'difficulty_level' => 5],
            ['name' => 'Grenada', 'code' => 'GD', 'flag_url' => 'https://flagcdn.com/w320/gd.png', 'capital' => 'St. George\'s', 'continent' => 'Nord America', 'difficulty_level' => 5],
            ['name' => 'Saint Vincent e Grenadine', 'code' => 'VC', 'flag_url' => 'https://flagcdn.com/w320/vc.png', 'capital' => 'Kingstown', 'continent' => 'Nord America', 'difficulty_level' => 5],
            ['name' => 'Antigua e Barbuda', 'code' => 'AG', 'flag_url' => 'https://flagcdn.com/w320/ag.png', 'capital' => 'St. John\'s', 'continent' => 'Nord America', 'difficulty_level' => 5],
            ['name' => 'Dominica', 'code' => 'DM', 'flag_url' => 'https://flagcdn.com/w320/dm.png', 'capital' => 'Roseau', 'continent' => 'Nord America', 'difficulty_level' => 5],
            ['name' => 'Saint Kitts e Nevis', 'code' => 'KN', 'flag_url' => 'https://flagcdn.com/w320/kn.png', 'capital' => 'Basseterre', 'continent' => 'Nord America', 'difficulty_level' => 5],
            ['name' => 'Venezuela', 'code' => 'VE', 'flag_url' => 'https://flagcdn.com/w320/ve.png', 'capital' => 'Caracas', 'continent' => 'Sud America', 'difficulty_level' => 3],
            ['name' => 'Guyana', 'code' => 'GY', 'flag_url' => 'https://flagcdn.com/w320/gy.png', 'capital' => 'Georgetown', 'continent' => 'Sud America', 'difficulty_level' => 4],
            ['name' => 'Suriname', 'code' => 'SR', 'flag_url' => 'https://flagcdn.com/w320/sr.png', 'capital' => 'Paramaribo', 'continent' => 'Sud America', 'difficulty_level' => 5],
            ['name' => 'Guiana Francese', 'code' => 'GF', 'flag_url' => 'https://flagcdn.com/w320/gf.png', 'capital' => 'Caienna', 'continent' => 'Sud America', 'difficulty_level' => 5],
            ['name' => 'Ecuador', 'code' => 'EC', 'flag_url' => 'https://flagcdn.com/w320/ec.png', 'capital' => 'Quito', 'continent' => 'Sud America', 'difficulty_level' => 3],
            ['name' => 'Bolivia', 'code' => 'BO', 'flag_url' => 'https://flagcdn.com/w320/bo.png', 'capital' => 'Sucre', 'continent' => 'Sud America', 'difficulty_level' => 4],
            ['name' => 'Paraguay', 'code' => 'PY', 'flag_url' => 'https://flagcdn.com/w320/py.png', 'capital' => 'Asunción', 'continent' => 'Sud America', 'difficulty_level' => 4],
            ['name' => 'Uruguay', 'code' => 'UY', 'flag_url' => 'https://flagcdn.com/w320/uy.png', 'capital' => 'Montevideo', 'continent' => 'Sud America', 'difficulty_level' => 3],

            // Paesi oceanici aggiuntivi - Difficoltà varia (3-5)
            ['name' => 'Papua Nuova Guinea', 'code' => 'PG', 'flag_url' => 'https://flagcdn.com/w320/pg.png', 'capital' => 'Port Moresby', 'continent' => 'Oceania', 'difficulty_level' => 4],
            ['name' => 'Vanuatu', 'code' => 'VU', 'flag_url' => 'https://flagcdn.com/w320/vu.png', 'capital' => 'Port Vila', 'continent' => 'Oceania', 'difficulty_level' => 5],
            ['name' => 'Isole Salomone', 'code' => 'SB', 'flag_url' => 'https://flagcdn.com/w320/sb.png', 'capital' => 'Honiara', 'continent' => 'Oceania', 'difficulty_level' => 5],
            ['name' => 'Samoa', 'code' => 'WS', 'flag_url' => 'https://flagcdn.com/w320/ws.png', 'capital' => 'Apia', 'continent' => 'Oceania', 'difficulty_level' => 5],
            ['name' => 'Tonga', 'code' => 'TO', 'flag_url' => 'https://flagcdn.com/w320/to.png', 'capital' => 'Nuku\'alofa', 'continent' => 'Oceania', 'difficulty_level' => 5],
            ['name' => 'Kiribati', 'code' => 'KI', 'flag_url' => 'https://flagcdn.com/w320/ki.png', 'capital' => 'Tarawa', 'continent' => 'Oceania', 'difficulty_level' => 5],
            ['name' => 'Tuvalu', 'code' => 'TV', 'flag_url' => 'https://flagcdn.com/w320/tv.png', 'capital' => 'Funafuti', 'continent' => 'Oceania', 'difficulty_level' => 5],
            ['name' => 'Nauru', 'code' => 'NR', 'flag_url' => 'https://flagcdn.com/w320/nr.png', 'capital' => 'Yaren', 'continent' => 'Oceania', 'difficulty_level' => 5],
            ['name' => 'Palau', 'code' => 'PW', 'flag_url' => 'https://flagcdn.com/w320/pw.png', 'capital' => 'Ngerulmud', 'continent' => 'Oceania', 'difficulty_level' => 5],
            ['name' => 'Micronesia', 'code' => 'FM', 'flag_url' => 'https://flagcdn.com/w320/fm.png', 'capital' => 'Palikir', 'continent' => 'Oceania', 'difficulty_level' => 5],
            ['name' => 'Isole Marshall', 'code' => 'MH', 'flag_url' => 'https://flagcdn.com/w320/mh.png', 'capital' => 'Majuro', 'continent' => 'Oceania', 'difficulty_level' => 5],
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}
