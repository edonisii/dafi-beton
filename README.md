# DAFI Beton — Sistemi i Menaxhimit të Stokut

Aplikacion i brendshëm për fabrikën e betonit **DAFI Beton** (Ferizaj / Lipjan) për
menaxhimin e stokut të lëndëve të para: rërë, çimento, zhavorr, ujë, aditivë etj.

Ndërtuar me **Laravel 13 + Filament 4**, bazë të dhënash **SQLite** (pa server DB të veçantë).

## Çka bën

- **Materialet** — regjistro çdo lëndë të parë me njësi matëse (ton, m³, kg, litër…) dhe stok minimal për alarm.
- **Hyrje / Dalje** — kur **blen** material regjistron një *Hyrje* (rrit stokun); kur e **përdor** në prodhim regjistron një *Dalje* (ul stokun). Te hyrjet mban edhe furnitorin dhe çmimin — vlera totale llogaritet vetë.
- **Furnitorët** — lista e furnitorëve me kontakte.
- **Paneli kryesor (Dashboard)** — stoku aktual i çdo materiali, alarm i kuq për materialet nën minimum, dhe vlera e blerjeve të muajit.

Stoku aktual llogaritet gjithmonë nga lëvizjet: `Σ hyrjet − Σ daljet`.

## Si të nisësh (në kompjuter)

```bash
cd dafi-beton
php artisan serve
```

Pastaj hap: http://127.0.0.1:8000/admin

### Kyçja (login)

- **Email:** `admin@dafibeton.com`
- **Fjalëkalimi:** `dafibeton123`

> Ndrysho fjalëkalimin sa më parë. Përdorues të rinj mund të shtohen me:
> `php artisan make:filament-user`

## Të dhëna shembull

Baza e të dhënave vjen e mbushur me materialet tipike dhe një hyrje fillestare për secilin
(Çimento është qëllimisht nën stokun minimal që të shihet alarmi). Për ta rifreskuar:

```bash
php artisan migrate:fresh --seed
```

## Struktura

- `app/Models/` — `Material`, `Supplier`, `StockMovement`
- `app/Filament/Resources/` — ekranet e menaxhimit
- `app/Filament/Widgets/` — `StockOverview` (statistika), `StockLevels` (tabela e stokut)
- `database/seeders/DatabaseSeeder.php` — të dhënat fillestare
