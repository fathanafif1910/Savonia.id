<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users
        $admin = User::create([
            'name'        => 'Admin Savonia',
            'email'       => 'admin@savonia.id',
            'password'    => Hash::make('admin123'),
            'role'        => 'admin',
            'phone'       => '081234567890',
            'address'     => 'Savonia Store HQ, Jl. Tebet Raya No. 45, Jakarta Selatan',
            'city'        => 'Jakarta Selatan',
            'postal_code' => '12820',
        ]);

        $customer1 = User::create([
            'name'        => 'Fathan Hidayat',
            'email'       => 'customer@gmail.com',
            'password'    => Hash::make('password'),
            'role'        => 'customer',
            'phone'       => '089876543210',
            'address'     => 'Jl. Bunga Melati Blok C No. 12',
            'city'        => 'Jakarta Barat',
            'postal_code' => '11530',
        ]);

        $customer2 = User::create([
            'name'        => 'Nabila Putri',
            'email'       => 'nabila@gmail.com',
            'password'    => Hash::make('password'),
            'role'        => 'customer',
            'phone'       => '087712349988',
            'address'     => 'Cluster Nirwana Garden No. 8',
            'city'        => 'Tangerang Selatan',
            'postal_code' => '15414',
        ]);

        // 2. Categories — Tech Accessories Savonia.id
        $categoriesData = [
            [
                'name'        => 'Powerbank',
                'slug'        => 'powerbank',
                'icon'        => 'battery-charging',
                'description' => 'Powerbank kapasitas besar, fast charging, slim & portable untuk kebutuhan sehari-hari.',
            ],
            [
                'name'        => 'iPhone Accessories',
                'slug'        => 'iphone-accessories',
                'icon'        => 'smartphone',
                'description' => 'Aksesoris original kompatibel iPhone: case, kabel, charger, screen protector, dan lainnya.',
            ],
            [
                'name'        => 'Laptop Accessories',
                'slug'        => 'laptop-accessories',
                'icon'        => 'laptop',
                'description' => 'Hub, cooling pad, mouse wireless, sleeve, dan segala perlengkapan laptop produktivitas.',
            ],
            [
                'name'        => 'Smartphone Accessories',
                'slug'        => 'smartphone-accessories',
                'icon'        => 'phone',
                'description' => 'Case, holder, ring magnet, tempered glass, dan aksesoris smartphone all-brand.',
            ],
            [
                'name'        => 'Electric Grooming Device',
                'slug'        => 'electric-grooming',
                'icon'        => 'zap',
                'description' => 'Alat grooming elektrik: pencukur, trimmer, pembersih wajah, dan perawatan diri modern.',
            ],
            [
                'name'        => 'Adaptor / Cable',
                'slug'        => 'adaptor-cable',
                'icon'        => 'cable',
                'description' => 'Kabel data, charger adaptor GaN, konverter USB-C, dan aksesori konektivitas premium.',
            ],
            [
                'name'        => 'TWS/IEM Protection',
                'slug'        => 'tws-iem-protection',
                'icon'        => 'shield',
                'description' => 'Case pelindung, pouch, dan aksesori perlindungan untuk TWS dan IEM kesayangan Anda.',
            ],
            [
                'name'        => 'Gaming Support',
                'slug'        => 'gaming-support',
                'icon'        => 'gamepad-2',
                'description' => 'Mousepad, controller grip, cooling fan mobile, dan aksesori pendukung gaming performa tinggi.',
            ],
            [
                'name'        => 'TWS (True Wireless Stereo)',
                'slug'        => 'tws',
                'icon'        => 'headphones',
                'description' => 'TWS earbuds terbaik: ANC aktif, latency rendah, koneksi stabil, dan suara jernih premium.',
            ],
            [
                'name'        => 'IEM (In-Ear Monitor)',
                'slug'        => 'iem',
                'icon'        => 'ear',
                'description' => 'In-Ear Monitor audiophile: detail tinggi, isolasi noise pasif, untuk music enthusiast.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $cat['is_active'] = true;
            $categories[$cat['slug']] = Category::create($cat);
        }

        // 3. Products — Tech Accessories Savonia.id
        $productsData = [

            // ── POWERBANK ──────────────────────────────────────────
            [
                'category_slug'     => 'powerbank',
                'name'              => 'Baseus Magnetic Wireless Powerbank 10000mAh 20W',
                'price'             => 349000,
                'discount_price'    => 299000,
                'stock'             => 45,
                'weight_gram'       => 220,
                'material'          => 'Aluminium Alloy & ABS',
                'color'             => 'Midnight Black',
                'image'             => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=600&auto=format&fit=crop&q=80',
                'short_description' => 'Powerbank MagSafe kompatibel 10000mAh, wireless 15W + PD 20W, desain slim premium.',
                'description'       => "Baseus Magnetic Wireless Powerbank hadir dengan teknologi MagSafe yang memungkinkan pengisian daya tanpa kabel secara otomatis.\n\nSpesifikasi:\n- Kapasitas: 10000mAh\n- Output Wireless: 15W (MagSafe), 10W (Qi)\n- Output Kabel: USB-C PD 20W\n- Pengisian Daya Penuh: ~2.5 jam (via PD)\n- Dimensi Tipis: hanya 14mm\n- Tersedia port USB-C dan USB-A",
                'is_featured'       => true,
            ],
            [
                'category_slug'     => 'powerbank',
                'name'              => 'Anker 322 Powerbank 20000mAh Dual USB Fast Charge',
                'price'             => 289000,
                'discount_price'    => 249000,
                'stock'             => 30,
                'weight_gram'       => 340,
                'material'          => 'Polycarbonate',
                'color'             => 'Matte White',
                'image'             => 'https://images.unsplash.com/photo-1588528402605-1f6aca24fa3a?w=600&auto=format&fit=crop&q=80',
                'short_description' => 'Powerbank 20000mAh kapasitas jumbo, 2 port USB-A 12W dan 1 USB-C 22.5W.',
                'description'       => "Anker 322 dengan kapasitas 20000mAh cukup untuk mengisi iPhone 15 sebanyak 4-5 kali penuh.\n\nSpesifikasi:\n- Kapasitas: 20000mAh / 74Wh\n- Input: USB-C 22.5W\n- Output: 2x USB-A 12W + 1x USB-C 22.5W\n- Fitur safety: temperatur sensor, overcharge protection",
                'is_featured'       => false,
            ],

            // ── IPHONE ACCESSORIES ─────────────────────────────────
            [
                'category_slug'     => 'iphone-accessories',
                'name'              => 'Spigen Ultra Hybrid Case iPhone 15 Pro Max – Crystal Clear',
                'price'             => 195000,
                'discount_price'    => 165000,
                'stock'             => 60,
                'weight_gram'       => 35,
                'material'          => 'TPU + Polycarbonate Hybrid',
                'color'             => 'Crystal Clear',
                'image'             => 'https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?w=600&auto=format&fit=crop&q=80',
                'short_description' => 'Case iPhone 15 Pro Max bening anti-yellowing dengan frame TPU shockproof Military Grade.',
                'description'       => "Spigen Ultra Hybrid adalah case bening terpopuler yang mempertahankan tampilan asli iPhone Anda.\n\nSpesifikasi:\n- Kompatibel: iPhone 15 Pro Max\n- Bahan: Polycarbonate back + TPU bumper\n- Proteksi: Military Grade Drop Test (MIL-STD-810G)\n- Fitur: Air Cushion corners, anti-yellowing coating\n- Tidak menambah ketebalan berlebih",
                'is_featured'       => true,
            ],
            [
                'category_slug'     => 'iphone-accessories',
                'name'              => 'Tempered Glass iPhone 15 Series Full Cover Privacy Anti-Spy',
                'price'             => 89000,
                'discount_price'    => 65000,
                'stock'             => 80,
                'weight_gram'       => 20,
                'material'          => 'Tempered Glass 9H',
                'color'             => 'Black Frame',
                'image'             => 'https://images.unsplash.com/photo-1596558450268-9c27524ba856?w=600&auto=format&fit=crop&q=80',
                'short_description' => 'Screen protector privacy anti-spy 9H untuk iPhone 15 / 15 Plus / 15 Pro / 15 Pro Max.',
                'description'       => "Tempered glass dengan lapisan privacy 180° mencegah orang lain melihat layar dari samping.\n\nSpesifikasi:\n- Kekerasan: 9H Tempered Glass\n- Ketebalan: 0.33mm\n- Fitur: Privacy filter, anti-fingerprint, oleophobic coating\n- Aksesoris: Dust removal sticker + alcohol wipe",
                'is_featured'       => false,
            ],

            // ── LAPTOP ACCESSORIES ─────────────────────────────────
            [
                'category_slug'     => 'laptop-accessories',
                'name'              => 'Ugreen Revodok Pro 10-in-1 USB-C Hub Thunderbolt 4',
                'price'             => 785000,
                'discount_price'    => 699000,
                'stock'             => 20,
                'weight_gram'       => 175,
                'material'          => 'Aluminium Alloy',
                'color'             => 'Space Gray',
                'image'             => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&auto=format&fit=crop&q=80',
                'short_description' => '10-in-1 hub Thunderbolt 4: HDMI 4K, 3x USB-A, SD Card, PD 100W, RJ45, jack audio.',
                'description'       => "UGREEN Revodok Pro mengubah satu port USB-C/Thunderbolt menjadi 10 port sekaligus.\n\nPort yang tersedia:\n- 1x Thunderbolt 4 / USB4 (40Gbps, PD 100W)\n- 1x HDMI 4K@60Hz\n- 3x USB-A 3.2 Gen1 (5Gbps)\n- 1x USB-C 3.2 (5Gbps)\n- 1x SD & MicroSD reader (UHS-I)\n- 1x RJ45 Gigabit Ethernet\n- 1x 3.5mm Audio Jack",
                'is_featured'       => true,
            ],
            [
                'category_slug'     => 'laptop-accessories',
                'name'              => 'Baseus Cooling Pad Laptop RGB 6 Fan Ultra Silent',
                'price'             => 235000,
                'discount_price'    => 199000,
                'stock'             => 35,
                'weight_gram'       => 600,
                'material'          => 'ABS + Metal Mesh',
                'color'             => 'Dark Gray',
                'image'             => 'https://images.unsplash.com/photo-1527443224154-c4a573d5f5ac?w=600&auto=format&fit=crop&q=80',
                'short_description' => 'Cooling pad laptop 6 kipas RGB adjustable speed, mendukung laptop 11-17 inch.',
                'description'       => "Baseus Cooling Pad dengan 6 kipas senyap menjaga suhu laptop tetap optimal saat gaming atau rendering.\n\nSpesifikasi:\n- Jumlah Fan: 6 kipas DC brushless\n- Kecepatan: 1200 RPM (mode senyap) / 1800 RPM (turbo)\n- Koneksi: USB-A (powered)\n- Fitur: RGB lighting 7 warna, height adjustable 6 level\n- Kapasitas: Laptop 11-17 inch",
                'is_featured'       => false,
            ],

            // ── SMARTPHONE ACCESSORIES ─────────────────────────────
            [
                'category_slug'     => 'smartphone-accessories',
                'name'              => 'Magnetic Phone Holder Mobil Dashboard 360° MagSafe',
                'price'             => 149000,
                'discount_price'    => 119000,
                'stock'             => 55,
                'weight_gram'       => 130,
                'material'          => 'Aluminium + N52 Neodymium Magnet',
                'color'             => 'Gunmetal Black',
                'image'             => 'https://images.unsplash.com/photo-1617788138017-80ad40651399?w=600&auto=format&fit=crop&q=80',
                'short_description' => 'Car holder magnetic 360° dengan magnet N52 super kuat, cocok MagSafe & semua HP.',
                'description'       => "Holder mobil magnetik dengan magnet neodymium N52 grade memastikan HP tidak jatuh meski di jalan berlubang.\n\nSpesifikasi:\n- Tipe: Magnetic Dashboard + CD Slot mount\n- Rotasi: 360° horizontal & vertikal\n- Magnet: N52 Neodymium (6 magnet)\n- Kompatibel: Semua smartphone dengan ring/case magnet, MagSafe ready\n- Bonus: 2x metal plate sticker",
                'is_featured'       => true,
            ],
            [
                'category_slug'     => 'smartphone-accessories',
                'name'              => 'Griptok Magsafe Ring Stand 3-in-1 Adjustable Kickstand',
                'price'             => 79000,
                'discount_price'    => 59000,
                'stock'             => 70,
                'weight_gram'       => 45,
                'material'          => 'Zinc Alloy + MagSafe Ring',
                'color'             => 'Titanium Silver',
                'image'             => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=600&auto=format&fit=crop&q=80',
                'short_description' => 'Griptok ring 3-in-1: pegangan, stand, dan mount magnetik MagSafe serba guna.',
                'description'       => "Griptok serbaguna ini bisa digunakan sebagai ring grip, kickstand, dan mount magnetik ke holder mobil.\n\nSpesifikasi:\n- Fungsi: Ring grip + kickstand adjustable (45-90°) + magnetic mount\n- Kompatibel MagSafe\n- Bahan: Zinc alloy anti-korosi\n- Adhesive 3M kuat, tidak merusak casing",
                'is_featured'       => false,
            ],

            // ── ELECTRIC GROOMING ──────────────────────────────────
            [
                'category_slug'     => 'electric-grooming',
                'name'              => 'Xiaomi Enchen Boost 2 Electric Hair Clipper Wireless',
                'price'             => 189000,
                'discount_price'    => 159000,
                'stock'             => 40,
                'weight_gram'       => 280,
                'material'          => 'ABS + Ceramic Blade',
                'color'             => 'Black',
                'image'             => 'https://images.unsplash.com/photo-1621607512214-68297480165e?w=600&auto=format&fit=crop&q=80',
                'short_description' => 'Alat cukur rambut wireless Enchen Boost 2, blade titanium ceramic, baterai tahan 3 jam.',
                'description'       => "Xiaomi Enchen Boost 2 adalah clipper profesional untuk di rumah dengan pisau keramik ultra-tajam.\n\nSpesifikasi:\n- Tipe blade: Titanium-coated Ceramic\n- Baterai: 1500mAh Li-ion (fast charge USB-C)\n- Waktu pakai: ~3 jam\n- Level ketebalan: 4 guide combs (3mm, 6mm, 9mm, 12mm)\n- Fitur: Washable blade, low-noise motor",
                'is_featured'       => true,
            ],

            // ── ADAPTOR / CABLE ────────────────────────────────────
            [
                'category_slug'     => 'adaptor-cable',
                'name'              => 'Baseus GaN5 Pro 65W 3-Port Charger (USB-C + USB-A)',
                'price'             => 329000,
                'discount_price'    => 279000,
                'stock'             => 50,
                'weight_gram'       => 95,
                'material'          => 'Gallium Nitride (GaN)',
                'color'             => 'White',
                'image'             => 'https://images.unsplash.com/photo-1587925358603-c2eea5305bbc?w=600&auto=format&fit=crop&q=80',
                'short_description' => 'Charger GaN 65W 3 port: 2x USB-C PD + 1x USB-A, mengisi laptop & HP sekaligus.',
                'description'       => "Charger GaN 65W Baseus mampu mengisi MacBook Air, iPhone, dan earbuds secara bersamaan.\n\nSpesifikasi:\n- Total Output: 65W\n- Port 1 (USB-C): 45W PD\n- Port 2 (USB-C): 20W PD\n- Port 3 (USB-A): 22.5W SCP/QC\n- Teknologi: GaN (lebih kecil 40% dari charger konvensional)\n- Kompatibel: Laptop, MacBook, iPhone, Android, iPad",
                'is_featured'       => true,
            ],
            [
                'category_slug'     => 'adaptor-cable',
                'name'              => 'Ugreen USB-C to Lightning Cable 2M MFi Certified 27W',
                'price'             => 125000,
                'discount_price'    => 99000,
                'stock'             => 90,
                'weight_gram'       => 60,
                'material'          => 'Nylon Braided + Aluminium Connector',
                'color'             => 'Midnight Black',
                'image'             => 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=600&auto=format&fit=crop&q=80',
                'short_description' => 'Kabel USB-C ke Lightning 2 meter bersertifikat MFi Apple, fast charge 27W, anti-kusut.',
                'description'       => "Kabel nylon braided bersertifikasi MFi (Made for iPhone) resmi dari Apple, tidak merusak baterai.\n\nSpesifikasi:\n- Panjang: 200cm\n- Standar: MFi Certified\n- Kecepatan Charge: hingga 27W (Fast Charge iPhone)\n- Kecepatan Data: USB 2.0\n- Konduktor: 28AWG tembaga premium\n- Bahan: Nylon braided tahan tarik 25000x",
                'is_featured'       => false,
            ],

            // ── TWS/IEM PROTECTION ─────────────────────────────────
            [
                'category_slug'     => 'tws-iem-protection',
                'name'              => 'Satisfy Audio IEM Earphone Pouch Case Zipper Canvas',
                'price'             => 65000,
                'discount_price'    => 49000,
                'stock'             => 60,
                'weight_gram'       => 70,
                'material'          => 'Canvas + EVA Foam Inner',
                'color'             => 'Army Green / Black / Navy',
                'image'             => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&auto=format&fit=crop&q=80',
                'short_description' => 'Pouch canvas zipper premium untuk IEM & TWS, anti-benturan, muat kabel & tip extra.',
                'description'       => "Case pelindung IEM dari bahan canvas tebal dengan inner foam EVA yang melindungi earphone dari benturan.\n\nSpesifikasi:\n- Bahan luar: Canvas 600D water-resistant\n- Padding dalam: EVA foam 1cm\n- Ukuran: 12 x 9 x 4cm\n- Muat: IEM + kabel + tips extra\n- Ritsleting: YKK double zipper\n- Tersedia 3 warna pilihan",
                'is_featured'       => true,
            ],

            // ── GAMING SUPPORT ─────────────────────────────────────
            [
                'category_slug'     => 'gaming-support',
                'name'              => 'Fantech MP64 REXUS XL Gaming Mousepad Anti-Slip',
                'price'             => 89000,
                'discount_price'    => 69000,
                'stock'             => 40,
                'weight_gram'       => 450,
                'material'          => 'Micro-Textured Cloth + Rubber Base',
                'color'             => 'Black',
                'image'             => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=600&auto=format&fit=crop&q=80',
                'short_description' => 'Mousepad XL gaming 800x300mm dengan permukaan micro-texture kecepatan tinggi & alas karet tebal.',
                'description'       => "Mousepad XL ukuran desk-mat yang menutup seluruh area mouse dan keyboard untuk kenyamanan gaming maksimal.\n\nSpesifikasi:\n- Ukuran: 800 x 300 x 3mm\n- Permukaan: Micro-textured cloth (speed control)\n- Alas: Natural rubber anti-slip 3mm\n- Edge: Jahitan tepi stitched warna oranye\n- Perawatan: Machine washable",
                'is_featured'       => false,
            ],

            // ── TWS ────────────────────────────────────────────────
            [
                'category_slug'     => 'tws',
                'name'              => 'Soundcore Liberty 4 NC – ANC TWS 98.5% Noise Cancelling',
                'price'             => 1299000,
                'discount_price'    => 999000,
                'stock'             => 15,
                'weight_gram'       => 55,
                'material'          => 'ABS + Silicone Eartip',
                'color'             => 'Midnight Black / Pearl White',
                'image'             => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=600&auto=format&fit=crop&q=80',
                'short_description' => 'TWS ANC terbaik di kelasnya: 98.5% noise cancellation, LDAC, 10mm driver, 50 jam total.',
                'description'       => "Soundcore Liberty 4 NC menghadirkan ANC kelas flagship dengan LDAC Hi-Res audio.\n\nSpesifikasi:\n- Driver: 10mm dynamic driver + 6mm tweeter\n- ANC: 98.5% (adaptive)\n- Codec: LDAC, AAC, SBC\n- Baterai: 10 jam (earbud) + 40 jam (case) = 50 jam total\n- Fitur: Transparency mode, 6-mic call noise reduction, IPX4\n- Latency gaming: 88ms",
                'is_featured'       => true,
            ],
            [
                'category_slug'     => 'tws',
                'name'              => 'Edifier NeoBuds Pro 2 – LHDC TWS Hi-Res ANC',
                'price'             => 1499000,
                'discount_price'    => 1299000,
                'stock'             => 10,
                'weight_gram'       => 50,
                'material'          => 'ABS Matte + Silicone Eartip',
                'color'             => 'Space Black',
                'image'             => 'https://images.unsplash.com/photo-1606220945770-b5b6c2c55bf1?w=600&auto=format&fit=crop&q=80',
                'short_description' => 'TWS Hi-Res LHDC 5.0 + ANC hybrid aktif, driver BA+DD, baterai 42 jam, IPX54.',
                'description'       => "Edifier NeoBuds Pro 2 adalah TWS audiophile grade dengan driver hybrid BA+DD untuk reproduksi suara akurat.\n\nSpesifikasi:\n- Driver: Knowles BA + 10mm Dynamic Driver (Hybrid)\n- Codec: LHDC 5.0 (Hi-Res 900kbps), AAC, SBC\n- ANC: Adaptive Hybrid ANC\n- Baterai: 7 jam earbud + 35 jam total\n- Sertifikasi: Hi-Res Audio Wireless\n- Fitur: Spatial Audio, IPX54 water resistant",
                'is_featured'       => true,
            ],

            // ── IEM ────────────────────────────────────────────────
            [
                'category_slug'     => 'iem',
                'name'              => 'KZ ZSN Pro X – Hybrid 1BA+1DD In-Ear Monitor',
                'price'             => 249000,
                'discount_price'    => 199000,
                'stock'             => 35,
                'weight_gram'       => 40,
                'material'          => 'Zinc Alloy Faceplate + Resin Shell',
                'color'             => 'Black & Silver',
                'image'             => 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=600&auto=format&fit=crop&q=80',
                'short_description' => 'IEM hybrid entry-level terbaik: 1BA treble + 1DD bass, detachable 2-pin silver cable.',
                'description'       => "KZ ZSN Pro X adalah IEM hybrid yang menjadi rekomendasi entry-level terbaik untuk audiophile pemula.\n\nSpesifikasi:\n- Driver: 1x Knowles BA (treble) + 1x 10mm DD (bass/mid)\n- Impedansi: 24Ω\n- Sensitivitas: 112dB\n- Respon Frekuensi: 7Hz - 40kHz\n- Kabel: Silver-plated OFC 2-pin detachable\n- Konektor: 3.5mm + upgrade opsi\n- Tersedia: Tanpa mic / Dengan mic",
                'is_featured'       => true,
            ],
            [
                'category_slug'     => 'iem',
                'name'              => 'Moondrop Aria 2 – Single Dynamic Driver IEM Hi-Fi',
                'price'             => 699000,
                'discount_price'    => 599000,
                'stock'             => 18,
                'weight_gram'       => 55,
                'material'          => 'CNC Aluminium Alloy',
                'color'             => 'Midnight Black',
                'image'             => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=600&auto=format&fit=crop&q=80',
                'short_description' => 'IEM single DD flagship entry: CNC aluminium, suara natural balanced, kabel LITZ silver-coated.',
                'description'       => "Moondrop Aria 2 menghadirkan karakter suara Harman-target yang akurat dan detail untuk harga yang sangat terjangkau.\n\nSpesifikasi:\n- Driver: 10mm DD dengan diafragma DLC (Diamond-Like Carbon)\n- Impedansi: 32Ω\n- Sensitivitas: 122dB/Vrms\n- Respon Frekuensi: 5Hz - 36kHz\n- Kabel: 4-core LITZ silver-coated OFC\n- Shell: Full CNC Aluminium Alloy\n- Konektor: 0.78mm 2-pin, 3.5mm SE",
                'is_featured'       => true,
            ],
        ];

        $createdProducts = [];
        $skuCounter      = 101;
        foreach ($productsData as $prod) {
            $catSlug = $prod['category_slug'];
            unset($prod['category_slug']);

            $prod['category_id'] = $categories[$catSlug]->id;
            $prod['slug']        = Str::slug($prod['name']) . '-' . $skuCounter;
            $prod['sku']         = 'SAV-' . strtoupper(substr($catSlug, 0, 3)) . '-' . $skuCounter++;
            $prod['is_active']   = true;

            $createdProducts[] = Product::create($prod);
        }

        // 4. Sample Completed Order (Data Tugas Akhir)
        $order1 = Order::create([
            'invoice_number'     => 'INV-SAV-20261020-001',
            'user_id'            => $customer1->id,
            'subtotal'           => 299000,
            'shipping_cost'      => 15000,
            'grand_total'        => 314000,
            'status'             => 'completed',
            'payment_status'     => 'paid',
            'payment_type'       => 'qris',
            'snap_token'         => 'mock-snap-token-001',
            'midtrans_transaction_id' => 'midtrans-mock-tx-001',
            'shipping_courier'   => 'J&T Express (Reguler)',
            'tracking_number'    => 'JT9928371625',
            'recipient_name'     => $customer1->name,
            'recipient_phone'    => $customer1->phone,
            'shipping_address'   => $customer1->address,
            'shipping_city'      => $customer1->city,
            'shipping_postal_code' => $customer1->postal_code,
            'customer_notes'     => 'Tolong dibungkus bubble wrap tebal ya kak.',
            'paid_at'            => now()->subDays(2),
            'shipped_at'         => now()->subDay(),
            'completed_at'       => now(),
        ]);

        OrderItem::create([
            'order_id'      => $order1->id,
            'product_id'    => $createdProducts[0]->id,
            'product_name'  => $createdProducts[0]->name,
            'product_sku'   => $createdProducts[0]->sku,
            'product_image' => $createdProducts[0]->image,
            'price'         => $createdProducts[0]->final_price,
            'quantity'      => 1,
            'subtotal'      => $createdProducts[0]->final_price,
        ]);

        Review::create([
            'order_id'   => $order1->id,
            'product_id' => $createdProducts[0]->id,
            'user_id'    => $customer1->id,
            'rating'     => 5,
            'comment'    => 'Powerbank-nya keren banget, tipis tapi baterainya awet! Pengiriman cepat. Recommended seller!',
        ]);

        // 5. Sample Pending Order
        $order2 = Order::create([
            'invoice_number'      => 'INV-SAV-20261020-002',
            'user_id'             => $customer2->id,
            'subtotal'            => 999000,
            'shipping_cost'       => 12000,
            'grand_total'         => 1011000,
            'status'              => 'paid',
            'payment_status'      => 'paid',
            'payment_type'        => 'bank_transfer',
            'snap_token'          => 'mock-snap-token-002',
            'shipping_courier'    => 'SiCepat BEST',
            'recipient_name'      => $customer2->name,
            'recipient_phone'     => $customer2->phone,
            'shipping_address'    => $customer2->address,
            'shipping_city'       => $customer2->city,
            'shipping_postal_code' => $customer2->postal_code,
            'customer_notes'      => 'Paket aman ya min, barang mahal.',
            'paid_at'             => now()->subHours(6),
        ]);

        OrderItem::create([
            'order_id'      => $order2->id,
            'product_id'    => $createdProducts[12]->id, // Soundcore Liberty 4 NC
            'product_name'  => $createdProducts[12]->name,
            'product_sku'   => $createdProducts[12]->sku,
            'product_image' => $createdProducts[12]->image,
            'price'         => $createdProducts[12]->final_price,
            'quantity'      => 1,
            'subtotal'      => $createdProducts[12]->final_price,
        ]);

        // 6. More sample orders for realistic dashboard data
        $order3 = Order::create([
            'invoice_number'      => 'INV-SAV-20261019-003',
            'user_id'             => $customer1->id,
            'subtotal'            => 699000,
            'shipping_cost'       => 14000,
            'grand_total'         => 713000,
            'status'              => 'shipped',
            'payment_status'      => 'paid',
            'payment_type'        => 'gopay',
            'snap_token'          => 'mock-snap-token-003',
            'shipping_courier'    => 'J&T Express (Reguler)',
            'tracking_number'     => 'JT8812345678',
            'recipient_name'      => $customer1->name,
            'recipient_phone'     => $customer1->phone,
            'shipping_address'    => $customer1->address,
            'shipping_city'       => $customer1->city,
            'shipping_postal_code' => $customer1->postal_code,
            'paid_at'             => now()->subDays(1),
            'shipped_at'          => now()->subHours(8),
            'created_at'          => now()->subDays(1),
        ]);

        OrderItem::create([
            'order_id'      => $order3->id,
            'product_id'    => $createdProducts[14]->id, // Moondrop Aria 2
            'product_name'  => $createdProducts[14]->name,
            'product_sku'   => $createdProducts[14]->sku,
            'product_image' => $createdProducts[14]->image,
            'price'         => $createdProducts[14]->final_price,
            'quantity'      => 1,
            'subtotal'      => $createdProducts[14]->final_price,
        ]);
    }
}
