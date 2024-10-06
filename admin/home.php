<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: index');
    exit;
}

include('conn.php');

// Tek bir sorgu ile tüm verileri almak için bir fonksiyon oluşturduk.
function fetchAll($con, $query, $params = []) {
    $stmt = $con->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Kategoriler, film türleri, ülkeler, stüdyolar, dağıtım ve haberler için verileri al
$kategoriListesi = fetchAll($con, 'SELECT * FROM kategori');
$filmturuListesi = fetchAll($con, 'SELECT * FROM filmturleri');
$ulkeListesi = fetchAll($con, 'SELECT * FROM ulke');
$studyoListesi = fetchAll($con, 'SELECT * FROM stüdyo');
$dagitimListesi = fetchAll($con, 'SELECT * FROM sinemadagitim');
$haberler = fetchAll($con, 'SELECT * FROM haberler ORDER BY idhaber DESC');

// Belirtilen haber id'sine göre verileri almak için
if (isset($_GET['haberid'])) {
    $idd = $_GET['haberid'];
    $haber2 = fetchAll($con, 'SELECT * FROM haberler WHERE idhaber = :haberid', ['haberid' => $idd])[0] ?? null;
}

// Oyuncular verisini almak için
$sql = "
    SELECT o.idoyuncu, o.adsoyad, o.dogum, o.olum, o.resimyol, 
    GROUP_CONCAT(k.kategoriAd SEPARATOR ', ') AS roller,
    GROUP_CONCAT(k.idKategori SEPARATOR ', ') AS kategori_idler
    FROM oyuncular o
    LEFT JOIN kayit_kategori kc ON o.idoyuncu = kc.kayit_id
    LEFT JOIN kategori k ON kc.kategori_id = k.idKategori
    GROUP BY o.idoyuncu
";
$veriler = fetchAll($con, $sql);

// Filmleri ve dizileri ayırmak için
$sql = "
    SELECT f.film_adi, f.id, f.vizyon_tarihi, f.kapak_resmi, f.statu, 
    GROUP_CONCAT(ft.filmturu SEPARATOR ', ') AS filmturleri
    FROM filmler f
    JOIN film_filmturu fft ON f.id = fft.film_id
    JOIN filmturleri ft ON fft.filmturu_id = ft.idfilm
    GROUP BY f.id desc
";
$filmler45 = fetchAll($con, $sql);
$filmler = [];
$diziler = [];

foreach ($filmler45 as $films) {
    if ($films['statu'] == 1) {
        $filmler[] = $films;
    } elseif ($films['statu'] == 2) {
        $diziler[] = $films;
    }
}

// Film veya dizi detayını almak için
if (isset($_GET['filmid']) || isset($_GET['diziid'])) {
    // film_id veya diziid değerini al
    $param = $_GET['filmid'] ?? $_GET['diziid'] ?? null;

    try {
        // Film veya dizi detayları için SQL sorgusu
        $sql = "
            SELECT f.film_adi, f.id, f.vizyon_tarihi, f.bitis_tarihi, f.filmsure,f.topHasilat,f.topKisi, f.film_konu, f.kapak_resmi, 
            COALESCE(GROUP_CONCAT(DISTINCT ft.filmturu SEPARATOR ', '), '') AS filmturleri, 
            COALESCE(GROUP_CONCAT(DISTINCT s.studyoad SEPARATOR ', '), '') AS studyolar,
            COALESCE(GROUP_CONCAT(DISTINCT sd.dagitimad SEPARATOR ', '), '') AS dagitim,
            COALESCE(GROUP_CONCAT(DISTINCT sd.iddagitim SEPARATOR ', '), '') AS dagitim_id, 
            COALESCE(GROUP_CONCAT(DISTINCT u.country_name SEPARATOR ', '), '') AS ulkeler,
            COALESCE(GROUP_CONCAT(DISTINCT g.resim_yolu SEPARATOR ', '), '') AS resimler,
            COALESCE(GROUP_CONCAT(DISTINCT CONCAT(o.adsoyad, ' (', k.kategoriAd, ')') SEPARATOR ', '), '') AS oyuncular
            FROM filmler f
            JOIN film_filmturu fft ON f.id = fft.film_id
            JOIN filmturleri ft ON fft.filmturu_id = ft.idfilm
            LEFT JOIN film_dagitim fd ON f.id = fd.film_id
            LEFT JOIN sinemadagitim sd ON fd.dagitim_id = sd.iddagitim
            LEFT JOIN film_studyolar fs ON f.id = fs.film_id
            LEFT JOIN stüdyo s ON fs.studyo_id = s.id
            LEFT JOIN film_ulkeler fu ON f.id = fu.film_id
            LEFT JOIN ulke u ON fu.ulke_id = u.id
            LEFT JOIN film_galeri g ON f.id = g.film_id
            LEFT JOIN oyuncuiliski ol ON f.id = ol.film_id
            LEFT JOIN oyuncular o ON ol.oyuncu_id = o.idoyuncu
            LEFT JOIN kategori k ON ol.kategori_id = k.idKategori
            WHERE f.id = :film_id
            GROUP BY f.id desc
        ";

        $stmt = $con->prepare($sql);
        $stmt->execute(['film_id' => $param]);
        $filmler2 = $stmt->fetch(PDO::FETCH_ASSOC);

        // Eğer film verisi bulunamazsa hata mesajı göster
        if (!$filmler2) {
            echo "Film bulunamadı.";
        }

        // Ekstra sorgular
        $veriler2 = fetchAll($con, 'SELECT * FROM filmveriler WHERE film_id = :film_id', ['film_id' => $param]);
        $salonlar = fetchAll($con, 'SELECT * FROM filmsalon WHERE film_id = :film_id', ['film_id' => $param]);

    } catch (PDOException $e) {
        // Hata mesajını yakala ve ekrana yazdır
        echo "Hata: " . $e->getMessage();
    }
}

// Oyuncular verisini işleme
$oyuncular = [];
if (isset($filmler2['oyuncular'])) {
    $oyuncuString = $filmler2['oyuncular'];

    // Eğer oyuncu verisi string ise, explode ile parçala
    if (is_string($oyuncuString)) {
        $oyuncular = explode(', ', $oyuncuString);
    }

    // Kategorilere göre oyuncuları ayırmak için dizi
    $kategoriOyuncular = [
        'Yönetmen' => [],
        'Senaryo Yazarı' => [],
        'Görüntü Yönetmeni' => [],
        'Müzik' => [],
        'Kurgu' => [],
        'Aktör' => []
    ];

    // Oyuncuları kategorilere göre ayırma işlemi
    foreach ($oyuncular as $oyuncuKategori) {
        if (preg_match('/^(.*?)\s*\((.*?)\)$/', $oyuncuKategori, $matches)) {
            $oyuncuAd = trim($matches[1]);
            $kategori = trim($matches[2]);

            // Kategori dizisine oyuncuyu ekle
            if (isset($kategoriOyuncular[$kategori])) {
                $kategoriOyuncular[$kategori][] = $oyuncuAd;
            }
        }
    }
}

// Eğer $kategoriOyuncular boşsa her bir kategoriye boş bir dizi ata
$yonetmenler = $kategoriOyuncular['Yönetmen'] ?? [];
$senaryolar = $kategoriOyuncular['Senaryo Yazarı'] ?? [];
$GörüntüYönetmeni = $kategoriOyuncular['Görüntü Yönetmeni'] ?? [];
$Müzik = $kategoriOyuncular['Müzik'] ?? [];
$Kurgu = $kategoriOyuncular['Kurgu'] ?? [];
$Oyuncu = $kategoriOyuncular['Aktör'] ?? [];
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link rel="shortcut icon" href="images/ico.png" id="favicon">

    <title>Vizyon Takvimi Admin</title>
    <!-- CSS Dosyaları -->
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/style.css"> <!-- Eğer bu stil dosyasına ihtiyacınız yoksa kaldırabilirsiniz. -->
    <link href="css/main.css" rel="stylesheet" type="text/css">



    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@0.9.13/dist/css/bootstrap-multiselect.css">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>




</head>




<body class="loggedin">
    <!-- Progress Bar -->
    <div id="progress-container"
        style="display: none; width: 100%; background: #f3f3f3; border: 1px solid #ccc; border-radius: 5px; margin-top: 10px;">
        <div id="progress-bar" style="width: 0%; height: 20px; background: #4caf50; border-radius: 5px;"></div>
    </div>


    <div class="wrapper d-flex align-items-stretch">
        <nav id="sidebar">
            <div class="custom-menu">
                <button type="button" id="sidebarCollapse" class="btn btn-primary">
                </button>
            </div>
            <div class="img bg-wrap text-center py-4" style="background-image: url(images/bg_1.jpg);">
                <div class="user-logo">
                    <div class="img" style="background-image: url(images/logo.jpg);"></div>
                    <h1>Vizyon Takvimi</h1>
                </div>
            </div>
            <ul class="list-unstyled components mb-5">
                <li class="active">
                    <button onclick="showContent('content1')"><span
                            class="fa fa-tachometer-alt mr-3"></span>DASHBOARD</button>
                </li>
                <li>
                    <button onclick="showContent('content2')"><span
                            class="fa fa-users mr-3 notif"></span>OYUNCULAR</button>
                </li>
                <li>
                    <button onclick="showContent('content3')"><span class="fa fa-film mr-3"></span>FİLMLER</button>
                </li>
                <li>
                    <button onclick="showContent('content4')"><span class="fa fa-tv mr-3"></span>DİZİLER</button>
                </li>
                <li>
                    <button onclick="showContent('content7')"><span
                            class="fa fa-newspaper mr-3"></span>HABERLER</button>
                </li>

                <li>
                    <button onclick="direct()"><span class="fa fa-sign-out-alt mr-3"></span>ÇIKIŞ</button>
                </li>
            </ul>

        </nav>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5 pt-5">
            <div id="content1" class="content pl-5">DASHBOARD</div>


            <div id="content2" class="content" style="display: none;">



                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 top-section">
                            <div class="table-wrapper">

                                <div class="table-title">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h2>OYUNCULAR</h2>
                                        </div>
                                        <div class="col-sm-6">
                                            <a href="#addEmployeeModal"
                                                class="btn btn-success d-flex align-items-center" data-toggle="modal">
                                                <i class="material-icons mr-2">&#xE147;</i> <span>Oyuncu Ekle</span>
                                            </a>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12 ml-4 rowSelect">
                                                <label for="rowsPerPageSelect0">Satır Sayısı: </label>
                                                <select id="rowsPerPageSelect0" class="rows-per-page-select">
                                                    <option value="5">5</option>
                                                    <option value="10">10</option>
                                                    <option value="20">20</option>
                                                    <option value="50">50</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-over">
                                    <table class="table table-striped table-hover paginated-table">
                                        <thead>
                                            <tr>
                                                <th class="align-middle text-center">Fotoğraf</th>
                                                <th class="align-middle text-center">Ad - Soyad</th>
                                                <th class="align-middle text-center">Doğum Tarihi</th>
                                                <th class="align-middle text-center">Ölüm Tarihi</th>
                                                <th class="align-middle text-center">Roller</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($veriler as $row): ?>
                                            <tr>
                                                <td class="align-middle text-center"><img
                                                        src="../foto/<?php echo htmlspecialchars($row['resimyol']); ?>"
                                                        class="rounded img-thumbnail tumbimg" alt="Fotoğraf"></td>
                                                <td class="align-middle text-center">
                                                    <?php echo htmlspecialchars($row['adsoyad']); ?>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <?php
        $tarih = $row['dogum'];
        echo ($tarih === '0000-00-00' || empty($tarih)) ? '-' : formatDate($tarih);
    ?>
                                                </td>

                                                <td class="align-middle text-center">
                                                    <?php echo !empty($row['olum']) ? formatDate($row['olum']) : '-'; ?>
                                                </td>

                                                <td class="align-middle text-center">
                                                    <?php echo !empty($row['roller']) ? htmlspecialchars($row['roller']) : '-'; ?>
                                                </td>

                                                </td>
                                                <td class="align-middle text-center">
                                                    <div class="d-row-ayar">
                                                        <a href="#editEmployeeModal" class="btn-edit p-03 m-0"
                                                            onclick="getId1('<?php echo $row['idoyuncu']; ?>');"
                                                            data-toggle="modal"><i class="material-icons"
                                                                data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                                                        <a href="#deleteEmployeeModal" class="btn-delete p-03 m-0"
                                                            onclick="getId('<?php echo $row['idoyuncu']; ?>');"
                                                            data-toggle="modal">
                                                            <i class="material-icons" data-toggle="tooltip"
                                                                title="Delete">&#xE872;</i>
                                                        </a>

                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="clearfix">
                                    <div class="hint-text"><b id="currentPageEntries0">1</b> arası <b
                                            id="totalEntries0"></b> kayıt gösteriliyor</div>
                                    <ul class="pagination" id="pagination0">
                                        <!-- Dinamik sayfalama burada olacak -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- ADD Modal HTML -->
                        <div id="addEmployeeModal" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form id="kayitForm" enctype="multipart/form-data">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Düzenle</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Ad - Soyad</label>
                                                <input type="text" name="adSoyad" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Doğum Tarihi</label>
                                                <input type="date" name="dogumTarihi" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label>Ölüm Tarihi (Hayattaysa Boş Bırakın)</label>
                                                <input type="date" name="olumTarihi" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Görsel Yükle</label>
                                                <input type="file" name="gorsel" class="form-control" accept="image/*"
                                                    required>
                                            </div>


                                            <div class="form-group">
                                                <label for="sinemadagitim">Roller</label>
                                                <div class="selected-tags">
                                                    <input type="text" id="sinemadagitim" name="sinemadagitim"
                                                        class="tagInput form-control" placeholder="Seçilen Roller"
                                                        readonly onclick="toggleDropdown(this)">
                                                </div>
                                                <div class="multiselect">
                                                    <div class="checkboxes">
                                                        <input type="text" class="searchBox" placeholder="Ara..."
                                                            onkeyup="filterFunction(this)">
                                                        <?php
                                                                    foreach ($kategoriListesi as $kategori) {
                                                                        $id = htmlspecialchars($kategori['idKategori']);
                                                                        $country_name = htmlspecialchars($kategori['kategoriAd']);
                                                                        echo "<label for='kategorii{$id}'><input type='checkbox' id='kategorii{$id}' name='kategori[]' value='{$id}' onclick='updateTags(this)' />{$country_name}</label>";
                                                                    }
                                                                ?>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <input type="button" id="addoyuncugeri" class="btn btn-default"
                                                data-dismiss="modal" value="Geri">
                                            <input type="submit" class="btn btn-info" value="Kaydet">
                                        </div>
                                    </form>


                                </div>
                            </div>
                        </div>
                        <!-- Edit Modal HTML -->
                        <div id="editEmployeeModal" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form id="oyuncuForm">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Ölüm Tarihi (Hayattaysa Boş Bırakın)</label>
                                                <input type="date" name="olum_tarihi" class="form-control" required>
                                            </div>
                                            <input type="hidden" id="oyuncuedit" name="oyuncu_id" value="">
                                        </div>
                                        <div class="modal-footer">
                                            <input type="button" id="oyuncueditgeri" class="btn btn-default"
                                                data-dismiss="modal" value="Geri">
                                            <input type="button" id="submitBtnn" class="btn btn-info" value="Kaydet">
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <!-- Delete Modal HTML -->
                        <div id="deleteEmployeeModal" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form>
                                        <div class="modal-header">
                                            <h4 class="modal-title">Sil</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Bu kayıtları silmek istediğinizden emin misiniz?</p>
                                            <p class="text-warning"><small>Bu işlem geri alınamaz.</small></p>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="button" id="deleteoyuncugeri" class="btn btn-default"
                                                data-dismiss="modal" value="Geri">
                                            <input type="submit" id="oyuncuSil" class="btn btn-danger" value="Sil">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kategori İşlemleri -->

                    <div class="col-12 bottom-section">
                        <div class="container-fluid">
                            <!--    <div class="row">
                                <div class="col-md-6 left-column">
                                    <div class="table-wrapper">
                                        <div class="table-title">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h2>KATEGORİLER</h2>
                                                </div>
                                                <div class="col-sm-6">
                                                    <a href="#addEmployeeModalKategori"
                                                        class="btn btn-success d-flex align-items-center"
                                                        data-toggle="modal">
                                                        <i class="material-icons mr-2">&#xE147;</i> <span>Kategori
                                                            Ekle</span>
                                                    </a>

                                                </div>
            <div class="row mt-3">
                            <div class="col-sm-12">
                                <label for="rowsPerPageSelect1">Satır Sayısı: </label>
                                <select id="rowsPerPageSelect1" class="rows-per-page-select">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                        </div>
                                            </div>
                                        </div>
                                        <table class="table table-striped table-hover paginated-table">
                                            <thead>
                                                <tr>

                                                    <th>Kategori Adı</th>
                                                    <th></th>


                                            </thead>
                                            <tbody>
                                                <tr>
                                                <?php 
                                                   foreach ($kategoriListesi as $kategori) {
                                                    echo "<td>" . htmlspecialchars($kategori['kategoriAd']) . "</td>"; // Her bir kategori adını güvenli bir şekilde göster
                                             
                                                   
                                                   ?>

                                                    <td>

                                                        <a href="#deleteEmployeeModalkat" id="kategoridelete"
                                                            onclick="getId('<?php echo $kategori['idKategori']; ?>');"
                                                            class="delete" data-toggle="modal">
                                                            <i class="material-icons" data-toggle="tooltip"
                                                                title="Delete">&#xE872;</i>
                                                        </a>
                                                    </td>
                                                </tr>

                                                <?php     }?>
                                            </tbody>
                                        </table>
                                        <div class="clearfix">
                        <div class="hint-text"><b id="currentPageEntries1">1</b> arası <b
                                id="totalEntries1"></b> kayıt gösteriliyor</div>
                        <ul class="pagination" id="pagination1">
                          
                        </ul>
                    </div>
                                    </div>
                                </div>
                                Add Modal HTML 
                                <div id="addEmployeeModalKategori" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form id="kategoriEkleForm">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Ekle</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Kategori Adı</label>
                                                        <input type="text" name="kategori_adi" id="kategori_adi"
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="button" id="addkategoriModal" class="btn btn-default"
                                                        data-dismiss="modal" value="Geri">
                                                    <input type="button" class="btn btn-info" value="Kaydet"
                                                        id="submitBtn">
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>

                                 
                                <div id="deleteEmployeeModalkat" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form>
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Sil</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    kategoriid
                                                    <p>Bu kayıtları silmek istediğinizden emin misiniz?</p>
                                                    <p class="text-warning"><small>Bu işlem geri alınamaz.</small></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="button" id="deleteEmployeeModalKategori"
                                                        class="btn btn-default" data-dismiss="modal" value="Geri">
                                                    <input type="submit" id="kategoriSil" class="btn btn-danger"
                                                        value="Sil">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>-->

                        </div>
                    </div>
                </div>
            </div>

            <input id="kategoriid" type="hidden">
            <!-- FİLMLER İÇERİĞİ BAŞLANGIÇ -->
            <div id="content3" class="content" style="display: none;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 top-section">
                            <div class="table-wrapper">
                                <div class="table-title">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h2>FİLMLER</h2>
                                        </div>
                                        <div class="col-sm-6">
                                            <a href="#addEmployeeModalfilmm"
                                                class="btn btn-success d-flex align-items-center" data-toggle="modal">
                                                <i class="material-icons mr-2">&#xE147;</i> <span>Film Ekle</span>
                                            </a>

                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12 ml-4 rowSelect">
                                                <label for="rowsPerPageSelect1">Satır Sayısı: </label>
                                                <select id="rowsPerPageSelect1" class="rows-per-page-select">
                                                    <option value="5">5</option>
                                                    <option value="10">10</option>
                                                    <option value="20">20</option>
                                                    <option value="50">50</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-over">
                                    <table class="table table-striped table-hover paginated-table">
                                        <thead>
                                            <tr>
                                                <th class="w-fit">Film Afişi</th>
                                                <th>Film Adı</th>
                                                <th>Vizyon Tarihi</th>
                                                <th>Film Türü</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($filmler as $row): ?>
                                            <tr>
                                                <td><img src="../kapakfoto/<?php echo htmlspecialchars($row['kapak_resmi']); ?>"
                                                        class="rounded img-thumbnail tumbimg" alt="Fotoğraf"></td>
                                                <td class="align-middle">
                                                    <?php echo htmlspecialchars($row['film_adi']); ?>
                                                </td>
                                                <td class="align-middle">
                                                    <?php echo formatDate($row['vizyon_tarihi']); ?></td>
                                                <td class="align-middle">
                                                    <?php echo !empty($row['filmturleri']) ? htmlspecialchars($row['filmturleri']) : '-'; ?>
                                                </td>



                                                </td>
                                                <td class="align-middle">

                                                    <a href="#deleteEmployeeModalfilmler" class="btn-delete"
                                                        onclick="getId('<?php echo $row['id']; ?>');"
                                                        data-toggle="modal"><i class="material-icons"
                                                            data-toggle="tooltip" title="Delete">&#xE872;</i></a>

                                                </td>
                                                <td class="align-middle">
                                                    <button
                                                        onclick="showContent('content6','<?php echo $row['id']; ?>','film')"
                                                        class="btn-page"><i
                                                            class="material-icons">chevron_right</i></button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="clearfix">
                                    <div class="hint-text"><b id="currentPageEntries1">1</b> arası <b
                                            id="totalEntries1"></b> kayıt gösteriliyor</div>
                                    <ul class="pagination" id="pagination1">
                                        <!-- Dinamik sayfalama burada olacak -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- ADD Modal HTML -->
                        <div id="addEmployeeModalfilmm" class="modal fade">
                            <div class="modal-dialog modal-xl">
                                <!-- Modal genişliğini artırmak için modal-xl kullanıldı -->
                                <div class="modal-content">
                                    <form id="filmForm" method="post" enctype="multipart/form-data">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Film Ekle</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <!-- İlk sütun -->
                                                    <div class="form-group">
                                                        <label>Film Adı</label>
                                                        <input type="text" name="filmadi" class="form-control">
                                                    </div>
                                                    <!-- Vizyon Tarihi -->
                                                    <div class="form-group">
                                                        <label>Vizyon Tarihi</label>
                                                        <input type="date" name="vizyonTarihi" class="form-control">
                                                    </div>
                                                    <!--Film  -->
                                                    <div class="form-group">
                                                        <label>Film Süresi</label>
                                                        <div style="display: flex; gap: 10px; align-items: center;">
                                                            <input type="number" name="filmsure" class="form-control"
                                                                placeholder="Film Süresi dakika olarak" min="0">

                                                        </div>
                                                    </div>

                                                    <!-- Sinema Dağıtım -->
                                                    <div class="form-group">
                                                        <label for="sinemadagitim">Sinema Dağıtım</label>
                                                        <div class="selected-tags">
                                                            <input type="text" id="sinemadagitim" name="sinemadagitim"
                                                                class="tagInput form-control"
                                                                placeholder="Seçilen dağıtım şirketleri" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                    foreach ($dagitimListesi as $dagitim) {
                                                                        $id = htmlspecialchars($dagitim['iddagitim']);
                                                                        $country_name = htmlspecialchars($dagitim['dagitimad']);
                                                                        echo "<label for='dagitim{$id}'><input type='checkbox' id='dagitim{$id}' name='dagitimListesi[]' value='{$id}' onclick='updateTags(this)' />{$country_name}</label>";
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Stüdyo -->
                                                    <div class="form-group">
                                                        <label>Stüdyo</label>
                                                        <div class="selected-tags">
                                                            <input type="text" class="tagInput form-control"
                                                                placeholder="Seçilen stüdyolar" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                    foreach ($studyoListesi as $studyo) {
                                                                        $id = htmlspecialchars($studyo['id']);
                                                                        $country_name = htmlspecialchars($studyo['studyoad']);
                                                                        echo "<label for='studyo{$id}'><input type='checkbox' id='studyo{$id}' name='studyoListesi[]' value='{$id}' onclick='updateTags(this)' />{$country_name}</label>";
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- ülke -->
                                                    <div class="form-group">
                                                        <label>Ülke</label>
                                                        <div class="selected-tags">
                                                            <input type="text" class="tagInput form-control"
                                                                placeholder="Seçilen ülkeler" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                    foreach ($ulkeListesi as $ulke) {
                                                                        $id = htmlspecialchars($ulke['id']);
                                                                        $country_name = htmlspecialchars($ulke['country_name']);
                                                                        echo "<label for='ulkeler{$id}'><input type='checkbox' id='ulkeler{$id}' name='ulkeListesi[]' value='{$id}' onclick='updateTags(this)' />{$country_name}</label>";
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Film Türü -->
                                                    <div class="form-group">
                                                        <label>Film Türü</label>
                                                        <div class="selected-tags">
                                                            <input type="text" class="tagInput form-control"
                                                                placeholder="Seçilen film türleri" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                    foreach ($filmturuListesi as $filmturu) {
                                                                        $id = htmlspecialchars($filmturu['idfilm']);
                                                                        $film_turu = htmlspecialchars($filmturu['filmturu']);
                                                                        echo "<label for='filmturu{$id}'><input type='checkbox' id='filmturu{$id}' name='filmturuListesi[]' value='{$id}' onclick='updateTags(this)' />{$film_turu}</label>";
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>



                                                </div>

                                                <div class="col-md-6">
                                                    <!-- İkinci sütun -->


                                                    <!-- Yönetmen -->
                                                    <div class="form-group">
                                                        <label>Yönetmen</label>
                                                        <div class="selected-tags">
                                                            <input type="text" class="tagInput form-control"
                                                                placeholder="Seçilen yönetmen" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                   foreach ($veriler as $row) {
                                                                       
                                                                    $id = htmlspecialchars($row['idoyuncu']);
                                                                    $oyuncuad = htmlspecialchars($row['adsoyad']);
                                                                    $istediginiz_sayi = 34;
                                                                    $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';
                                                                    if (preg_match($pattern, $row['kategori_idler'])) {
                                                                        echo "<label for='yonetmen{$id}'><input type='checkbox' id='yonetmen{$id}' name='yonetmenListesi[]' value='{$id}' onclick='updateTags(this)' />{$oyuncuad}</label>";

                                                                    } 
                                                                   
                                                            }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Senaryo -->
                                                    <div class="form-group">
                                                        <label>Senaryo</label>
                                                        <div class="selected-tags">
                                                            <input type="text" class="tagInput form-control"
                                                                placeholder="Seçilen senaryo yazarı" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                    foreach ($veriler as $row) {
                                                                       
                                                                        $id = htmlspecialchars($row['idoyuncu']);
                                                                        $oyuncuad = htmlspecialchars($row['adsoyad']);
                                                                        $istediginiz_sayi = 38;
                                                                        $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';
                                                                        if (preg_match($pattern, $row['kategori_idler'])) {
                                                                            echo "<label for='senaryo{$id}'><input type='checkbox' id='senaryo{$id}' name='senaryoListesi[]' value='{$id}' onclick='updateTags(this)' />{$oyuncuad}</label>";

                                                                        } 
                                                                       
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Görüntü Yönetmeni -->
                                                    <div class="form-group">
                                                        <label>Görüntü Yönetmeni</label>
                                                        <div class="selected-tags">
                                                            <input type="text" class="tagInput form-control"
                                                                placeholder="Seçilen görüntü yönetmeni" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                   foreach ($veriler as $row) {
                                                                       
                                                                    $id = htmlspecialchars($row['idoyuncu']);
                                                                    $oyuncuad = htmlspecialchars($row['adsoyad']);
                                                                    $istediginiz_sayi = 35;
                                                                    $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';
                                                                    if (preg_match($pattern, $row['kategori_idler'])) {
                                                                        echo "<label for='gyonetmen{$id}'><input type='checkbox' id='gyonetmen{$id}' name='gyonetmeniListesi[]' value='{$id}' onclick='updateTags(this)' />{$oyuncuad}</label>";

                                                                    } 
                                                                   
                                                            }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Kurgu -->
                                                    <div class="form-group">
                                                        <label>Kurgu</label>
                                                        <div class="selected-tags">
                                                            <input type="text" class="tagInput form-control"
                                                                placeholder="Seçilen film türleri burada görünecek..."
                                                                readonly onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                    foreach ($veriler as $row) {
                                                                       
                                                                        $id = htmlspecialchars($row['idoyuncu']);
                                                                        $oyuncuad = htmlspecialchars($row['adsoyad']);
                                                                        $istediginiz_sayi = 37;
                                                                        $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';
                                                                        if (preg_match($pattern, $row['kategori_idler'])) {
                                                                            echo "<label for='kurgu{$id}'><input type='checkbox' id='kurgu{$id}' name='kurguListesi[]' value='{$id}' onclick='updateTags(this)' />{$oyuncuad}</label>";

                                                                        } 
                                                                       
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Müzik -->
                                                    <div class="form-group">
                                                        <label>Müzik</label>
                                                        <div class="selected-tags">
                                                            <input type="text" class="tagInput form-control"
                                                                placeholder="Seçilen film türleri burada görünecek..."
                                                                readonly onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                    foreach ($veriler as $row) {
                                                                       
                                                                        $id = htmlspecialchars($row['idoyuncu']);
                                                                        $oyuncuad = htmlspecialchars($row['adsoyad']);
                                                                        $istediginiz_sayi = 36;
                                                                        $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';
                                                                        if (preg_match($pattern, $row['kategori_idler'])) {
                                                                            echo "<label for='muzik{$id}'><input type='checkbox' id='muzik{$id}' name='müzikListesi[]' value='{$id}' onclick='updateTags(this)' />{$oyuncuad}</label>";

                                                                        } 
                                                                       
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Film Oyuncuları -->
                                                    <div class="form-group">
                                                        <label>Film Oyuncuları</label>
                                                        <div class="selected-tags">
                                                            <input type="text" class="tagInput form-control"
                                                                placeholder="Seçilen film oyuncuları" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                    foreach ($veriler as $row) {
                                                                       
                                                                            $id = htmlspecialchars($row['idoyuncu']);
                                                                            $oyuncuad = htmlspecialchars($row['adsoyad']);
                                                                            $istediginiz_sayi = 29;
                                                                            $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';
                                                                            if (preg_match($pattern, $row['kategori_idler'])) {
                                                                                echo "<label for='filmoyuncu{$id}'><input type='checkbox' id='filmoyuncu{$id}' name='oyuncuListesi[]' value='{$id}' onclick='updateTags(this)' />{$oyuncuad}</label>";

                                                                            } 
                                                                           
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Toplam Hasılat -->
                                                    <div class="form-group">
                                                        <label>Toplam Hasılat</label>
                                                        <input type="number" name="topHasilat" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Toplam Seyirci</label>
                                                        <input type="number" name="topSeyirci" class="form-control">
                                                    </div>
                                                </div>
                                                <!-- Film Aciklamasi -->
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="filmKonu">Filmin Konusu</label>
                                                        <textarea class="form-control textarea" rows="6" name="filmKonu"
                                                            id="filmKonu"></textarea>
                                                    </div>
                                                </div>
                                                <!-- sağ sol sütun bitiş -->
                                                <div class="multiple-uploader" id="single-uploader">
                                                    <div class="mup-msg">
                                                        <span class="mup-main-msg">Kapak Resmi Yüklemek için
                                                            Tıklayınız.</span>
                                                        <span class="mup-msg" id="max-upload-number">Sadece 1 Kapak
                                                            Fotoğrafı Yükleyiniz.</span>

                                                    </div>
                                                </div>


                                                <div class="multiple-uploader" id="multiple-uploader">
                                                    <div class="mup-msg">
                                                        <span class="mup-main-msg">Film Galerisine Fotoğraf Eklemek için
                                                            Tıklayınız.</span>
                                                        <span class="mup-msg" id="max-upload-number">En Az 3 Fotoğraf
                                                            Yükleyiniz.</span>

                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="button" id="addoyuncugeri" class="btn btn-default"
                                                data-dismiss="modal" value="Geri">
                                            <input type="submit" class="btn btn-info" value="Kaydet">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <!-- Delete Modal HTML -->
                        <div id="deleteEmployeeModalfilmler" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form>
                                        <div class="modal-header">
                                            <h4 class="modal-title">Sil</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Bu kayıtları silmek istediğinizden emin misiniz?</p>
                                            <p class="text-warning"><small>Bu işlem geri alınamaz.</small></p>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="button" id="deletefilmgeri" class="btn btn-default"
                                                data-dismiss="modal" value="Geri">
                                            <input type="submit" id="filmSil" class="btn btn-danger" value="Sil">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Film Türü İşlemleri -->

                    <div class="col-12 bottom-section">
                        <div class="row wrap-1440">
                            <div class="col-md-4 left-column p-0">
                                <div class="table-wrapper">
                                    <div class="table-title">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <h2>FİLM TÜRLERİ</h2>
                                            </div>
                                            <div class="col-sm-6">
                                                <a href="#addEmployeeModalfilmturu"
                                                    class="btn btn-success d-flex align-items-center"
                                                    data-toggle="modal">
                                                    <i class="material-icons mr-2">&#xE147;</i> <span>Film Türü
                                                        Ekle</span>
                                                </a>

                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-12 ml-4 rowSelect">
                                                    <label for="rowsPerPageSelect2">Satır Sayısı: </label>
                                                    <select id="rowsPerPageSelect2" class="rows-per-page-select">
                                                        <option value="5">5</option>
                                                        <option value="10">10</option>
                                                        <option value="20">20</option>
                                                        <option value="50">50</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-over">
                                        <table class="table table-striped table-hover paginated-table">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">Film Türü</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <?php 
                                                       foreach ($filmturuListesi as $filmturu) {
                                                        echo "<td>" . htmlspecialchars($filmturu['filmturu']) . "</td>"; // Her bir kategori adını güvenli bir şekilde göster
                                                    
                                                    
                                                       ?>

                                                    <td class="text-center">

                                                        <a href="#deleteEmployeeModalfilmturu" id="kategoridelete"
                                                            onclick="getId('<?php echo $filmturu['idfilm']; ?>');"
                                                            class="btn-delete p-03" data-toggle="modal">
                                                            <i class="material-icons" data-toggle="tooltip"
                                                                title="Delete">&#xE872;</i>
                                                        </a>
                                                    </td>
                                                </tr>

                                                <?php     }?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="clearfix">
                                        <div class="hint-text"><b id="currentPageEntries2">1</b> arası <b
                                                id="totalEntries2"></b> kayıt gösteriliyor</div>
                                        <ul class="pagination" id="pagination2">
                                            <!-- Dinamik sayfalama burada olacak -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- Add Modal HTML -->
                            <div id="addEmployeeModalfilmturu" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form id="kategoriEkleForm">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Ekle</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Film Türü</label>
                                                    <input type="text" name="film_turu" id="film_turu"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="button" id="addfilmturuModal" class="btn btn-default"
                                                    data-dismiss="modal" value="Geri">
                                                <input type="button" class="btn btn-info" value="Kaydet"
                                                    id="submitBtnfilmturu">
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal HTML -->
                            <div id="deleteEmployeeModalfilmturu" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form>
                                            <div class="modal-header">
                                                <h4 class="modal-title">Sil</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">&times;</button>
                                            </div>
                                            <div class="modal-body">

                                                <p>Bu kayıtları silmek istediğinizden emin misiniz?</p>
                                                <p class="text-warning"><small>Bu işlem geri alınamaz.</small></p>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="button" id="deleteEmployeeModalfilmturugeri"
                                                    class="btn btn-default" data-dismiss="modal" value="Geri">
                                                <input type="submit" id="filmturuSil" class="btn btn-danger"
                                                    value="Sil">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 middle-column">
                                <div class="table-wrapper">
                                    <div class="table-title">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <h2>STÜDYO</h2>
                                            </div>
                                            <div class="col-sm-6">
                                                <a href="#addEmployeeModalstudyo"
                                                    class="btn btn-success d-flex align-items-center"
                                                    data-toggle="modal">
                                                    <i class="material-icons mr-2">&#xE147;</i> <span>Stüdyo
                                                        Ekle</span>
                                                </a>

                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-12 ml-4 rowSelect">
                                                    <label for="rowsPerPageSelect3">Satır Sayısı: </label>
                                                    <select id="rowsPerPageSelect3" class="rows-per-page-select">
                                                        <option value="5">5</option>
                                                        <option value="10">10</option>
                                                        <option value="20">20</option>
                                                        <option value="50">50</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-over">
                                        <table class="table table-striped table-hover paginated-table">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">Stüdyo Adı</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <?php 
                                                       foreach ($studyoListesi as $studyo) {
                                                        echo "<td>" . htmlspecialchars($studyo['studyoad']) . "</td>"; // Her bir kategori adını güvenli bir şekilde göster
                                                    
                                                    
                                                       ?>

                                                    <td class="text-center">

                                                        <a href="#deleteEmployeeModalstudyo" id="kategoridelete"
                                                            onclick="getId('<?php echo $studyo['id']; ?>');"
                                                            class="btn-delete p-03" data-toggle="modal">
                                                            <i class="material-icons" data-toggle="tooltip"
                                                                title="Delete">&#xE872;</i>
                                                        </a>
                                                    </td>
                                                </tr>

                                                <?php     }?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="clearfix">
                                        <div class="hint-text"><b id="currentPageEntries3">1</b> arası <b
                                                id="totalEntries3"></b> kayıt gösteriliyor</div>
                                        <ul class="pagination" id="pagination3">
                                            <!-- Dinamik sayfalama burada olacak -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- Add Modal HTML -->
                            <div id="addEmployeeModalstudyo" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form id="studyoEkleForm">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Stüdyo Ekle</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Stüdyo Adı</label>
                                                    <input type="text" name="studyo" id="studyo" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="button" id="addstudyoModal" class="btn btn-default"
                                                    data-dismiss="modal" value="Geri">
                                                <input type="button" class="btn btn-info" value="Kaydet"
                                                    id="submitBtnstudyo">
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal HTML -->
                            <div id="deleteEmployeeModalstudyo" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form>
                                            <div class="modal-header">
                                                <h4 class="modal-title">Sil</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">&times;</button>
                                            </div>
                                            <div class="modal-body">

                                                <p>Bu kayıtları silmek istediğinizden emin misiniz?</p>
                                                <p class="text-warning"><small>Bu işlem geri alınamaz.</small></p>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="button" id="deleteEmployeeModalstudyogeri"
                                                    class="btn btn-default" data-dismiss="modal" value="Geri">
                                                <input type="submit" id="studyoSil" class="btn btn-danger" value="Sil">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4 p-0 right-column">
                                <div class="table-wrapper">
                                    <div class="table-title">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <h2>SİNEMA DAĞITIM</h2>
                                            </div>
                                            <div class="col-sm-6">
                                                <a href="#addEmployeeModaldagitim"
                                                    class="btn btn-success d-flex align-items-center"
                                                    data-toggle="modal">
                                                    <i class="material-icons mr-2">&#xE147;</i> <span>Sinema Dağıtım
                                                        Ekle</span>
                                                </a>

                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-12 ml-4 rowSelect">
                                                    <label for="rowsPerPageSelect4">Satır Sayısı: </label>
                                                    <select id="rowsPerPageSelect4" class="rows-per-page-select">
                                                        <option value="5">5</option>
                                                        <option value="10">10</option>
                                                        <option value="20">20</option>
                                                        <option value="50">50</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-over">
                                        <table class="table table-striped table-hover paginated-table">
                                            <thead>
                                                <tr>

                                                    <th colspan="2">Sinema Dağıtım</th>
                                                </tr>

                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <?php 
                                                       foreach ($dagitimListesi as $dagitim) {
                                                        echo "<td>" . htmlspecialchars($dagitim['dagitimad']) . "</td>"; // Her bir kategori adını güvenli bir şekilde göster
                                                    
                                                    
                                                       ?>

                                                    <td class="text-center">

                                                        <a href="#deleteEmployeeModaldagitim" id="kategoridelete"
                                                            onclick="getId('<?php echo $dagitim['iddagitim']; ?>');"
                                                            class="btn-delete p-03" data-toggle="modal">
                                                            <i class="material-icons" data-toggle="tooltip"
                                                                title="Delete">&#xE872;</i>
                                                        </a>
                                                    </td>
                                                </tr>

                                                <?php     }?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="clearfix">
                                        <div class="hint-text"><b id="currentPageEntries4">1</b> arası <b
                                                id="totalEntries4"></b> kayıt gösteriliyor</div>
                                        <ul class="pagination" id="pagination4">
                                            <!-- Dinamik sayfalama burada olacak -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- Add Modal HTML -->
                            <div id="addEmployeeModaldagitim" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form id="dagitimEkleForm">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Sinema Dağıtım Ekle</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="dagitim">Sinema Dağıtım</label>
                                                    <input type="text" name="dagitim" id="dagitim" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="button" id="addModaldagitim" class="btn btn-default"
                                                    data-dismiss="modal" value="Geri">
                                                <input type="button" class="btn btn-info" value="Kaydet"
                                                    id="submitBtndagitim">
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal HTML -->
                            <div id="deleteEmployeeModaldagitim" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form>
                                            <div class="modal-header">
                                                <h4 class="modal-title">Sil</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">&times;</button>
                                            </div>
                                            <div class="modal-body">

                                                <p>Bu kayıtları silmek istediğinizden emin misiniz?</p>
                                                <p class="text-warning"><small>Bu işlem geri alınamaz.</small></p>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="button" id="deleteEmployeeModaldagitimgeri"
                                                    class="btn btn-default" data-dismiss="modal" value="Geri">
                                                <input type="submit" id="dagitimSil" class="btn btn-danger" value="Sil">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>




            <div id="content7" class="content" style="display: none;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 top-section">
                            <div class="table-wrapper mt-0">
                                <div class="table-title">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h2>HABERLER</h2>
                                        </div>
                                        <div class="col-sm-6">
                                            <a href="#" class="btn btn-success d-flex align-items-center"
                                                onclick="showContent('content5'); return false;">
                                                <i class="material-icons mr-2">&#xE147;</i> <span>Haber Ekle</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12 ml-1 rowSelect">
                                            <label for="rowsPerPageSelect5">Satır Sayısı: </label>
                                            <select id="rowsPerPageSelect5" class="rows-per-page-select">
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="50">50</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-over">
                                    <table class="table table-striped table-hover paginated-table" id="haberlerTable">
                                        <thead>
                                            <tr>
                                                <th class="align-middle text-center">Fotoğraf</th>
                                                <th class="align-middle text-center">Haber Başlığı</th>
                                                <th class="align-middle text-center">Yayın Tarihi</th>
                                                <th class="align-middle text-center">Statü</th>
                                                <th class="align-middle text-center"></th>
                                                <th class="align-middle text-center"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($haberler as $row): ?>
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <img src="../haberfoto/<?php echo htmlspecialchars($row['haberfoto']); ?>"
                                                        class="rounded img-thumbnail tumbimg" alt="Fotoğraf">
                                                </td>
                                                <td class="align-middle text-center">
                                                    <?php echo htmlspecialchars($row['baslik']); ?>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <?php echo formatDateTime($row['tarih']); ?>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <?php 
                                                        // statu değerine göre uygun metni belirle
                                                        if ($row['statu'] == 1) {
                                                            echo "Film";
                                                        } elseif ($row['statu'] == 2) {
                                                            echo "Dizi";
                                                        } else {
                                                            echo "Bilinmeyen"; // Diğer durumlar için varsayılan bir mesaj
                                                        }
                                                    ?>
                                                </td>

                                                <td class="align-middle text-center">
                                                    <div class="d-row-ayar">
                                                        <a href="#deleteEmployeeModalhaber" class="btn-delete m-0"
                                                            onclick="getId('<?php echo $row['idhaber']; ?>'); return false;"
                                                            data-toggle="modal"><i class="material-icons"
                                                                data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <button
                                                        onclick="showContent('content8','<?php echo $row['idhaber']; ?>','haber')"
                                                        class="btn-page"><i
                                                            class="material-icons">chevron_right</i></button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="clearfix">
                                    <div class="hint-text"><b id="currentPageEntries5">1</b> arası <b
                                            id="totalEntries5"></b> kayıt gösteriliyor</div>
                                    <ul class="pagination" id="pagination5">
                                        <!-- Dinamik sayfalama burada olacak -->
                                    </ul>
                                </div>
                            </div>

                        </div>

                        <!-- Silme Modalı -->
                        <div id="deleteEmployeeModalhaber" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="post" action="your_delete_action.php">
                                        <!-- Silme işlemi için form eylemi -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Sil</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Bu kayıtları silmek istediğinizden emin misiniz?</p>
                                            <p class="text-warning"><small>Bu işlem geri alınamaz.</small></p>
                                            <input type="hidden" name="idhaber" id="idhaber" value="">
                                        </div>
                                        <div class="modal-footer">
                                            <input type="button" id="deleteEmployeeModalhabergeri"
                                                class="btn btn-default" data-dismiss="modal" value="Geri">
                                            <input type="submit" id="haberSil" class="btn btn-danger" value="Sil">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="content8" class="content" style="display: none;">

                <div class="d-flex justify-content-between align-items-center custombg1 mt-5 mb-0">
                    <h2>Haber Görseli</h2>
                </div>

                <div class="bg-white border p-2 bt-0 rounded">
                    <div class="row">
                        <div class="col-md-12">
                            <img class="card-img-top-1 rounded" src="../haberfoto/<?php echo $haber2['haberfoto']?>"
                                alt="">
                        </div>
                    </div>
                </div>


                <div class="d-flex justify-content-between align-items-center custombg1 mt-5 mb-0">
                    <h2><?php echo $haber2['baslik']?></h2>
                </div>

                <div class="row w-100 m-0">
                    <div class="col-md-12 icerik-haber bg-white border bt-0 mt-0 p-3">
                        <!-- burası haber içerik echo -->
                        <?php echo $haber2['icerik']?>

                        <!-- burası haber içerik echo -->
                    </div>
                </div>

            </div>
            <div id="content5" class="content" style="display: none;">


                <div class="d-flex justify-content-between align-items-center custombg1 mt-5 mb-0">
                    <h2>Haber Ekle</h2>
                    <div class="containerswitch">
                        <span class="labelswitch">Filmler</span>
                        <label class="switch" for="checkbox12">
                            <input type="checkbox" id="checkbox12" />
                            <div class="slider round"></div>
                        </label>
                        <span class="labelswitch">Diziler</span>
                    </div>

                </div>

                <form id="formHaberler" class="bg-white border bt-0 mt-0 p-3" method="post"
                    enctype="multipart/form-data">

                    <div class="mb-3">
                        <label for="haberBaslik" class="form-label">Haber Başlığı</label>
                        <input type="text" class="form-control" id="haberBaslik" placeholder="Başlık girin">
                    </div>

                    <div class="mb-3 mt-5">
                        <label for="haberIcerik" class="form-label">Haber İçeriği</label>
                        <textarea name="content" id="haberIcerik" rows="10" class="form-control"></textarea>
                    </div>
                    <label for="haberBaslik" class="form-label mt-5">Haber Görseli</label>
                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <div class="multiple-uploader" id="single-uploader-haber">
                                <div class="mup-msg">
                                    <span class="mup-main-msg">Kapak Resmi Yüklemek için
                                        Tıklayınız.</span>
                                    <span class="mup-msg" id="max-upload-number">Sadece 1 Kapak
                                        Fotoğrafı Yükleyiniz.</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">Haberi Kaydet</button>
                </form>


                <!-- HTML Element for CKEditor -->
                <script>
                ClassicEditor
                    .create(document.querySelector('#haberIcerik'), {
                        // Gömme özelliğini etkinleştir
                        mediaEmbed: {
                            previewsInData: true,
                            // Burada özelleştirilmiş kodu tanımlayın

                        },
                        ckfinder: {
                            uploadUrl: 'http://localhost/vizyontakvimi/admin/controller/upload.php',
                        },
                    })
                    .catch(error => {
                        console.error(error);
                    });
                </script>

            </div>

            <div id="content6" class="content pl-5" style="display: none;">

                <div class="table-wrapper mt-0">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-6">
                                <H2 class="custombg1h2">Box Office Değerleri</H2>
                            </div>
                            <div class="col-sm-6">
                                <a href="#uploadModal" class="btn btn-success d-flex align-items-center mr-2"
                                    data-toggle="modal">
                                    <i class="material-icons">&#xE147;</i> <span>Excel Dosyası Yükle</span>
                                </a>
                            </div>
                        </div>
                        <!-- Seçilen satır sayısını belirleyen select -->
                        <div class="row mt-3">
                            <div class="col-12 ml-1 rowSelect">
                                <label for="rowsPerPageSelect6">Satır Sayısı: </label>
                                <select id="rowsPerPageSelect6" class="rows-per-page-select">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-over">
                        <table class="table table-striped table-hover paginated-table fixtext" id="boxOfficeTable1">
                            <thead>
                                <tr>
                                    <th>Tarih</th>
                                    <th>Sinema</th>
                                    <th>Perde</th>
                                    <th>Kişi</th>
                                    <th>Hasılat</th>
                                    <th>Toplam Kişi</th>
                                    <th>Toplam Hasılat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- PHP ile dinamik satırlar buraya gelir -->
                                <?php foreach ($veriler2 as $veri): ?>
                                <tr>
                                    <td class="align-left"><?= formatDate(htmlspecialchars($veri['tarih'])); ?></td>
                                    <td class="align-left"><?= htmlspecialchars($veri['sinema']) ?></td>
                                    <td class="align-left"><?= htmlspecialchars($veri['perde']) ?></td>
                                    <td class="align-left"><?= htmlspecialchars($veri['kisi']) ?></td>
                                    <td class="align-left">
                                        <?= htmlspecialchars(number_format($veri['hasilat'], 2, ',', '.')) . '₺' ?>
                                    </td>

                                    <td class="align-left"><?= htmlspecialchars($veri['toplamkisi']) ?></td>
                                    <td class="align-left">
                                        <?= htmlspecialchars(number_format($veri['toplamhasilat'], 2, ',', '.')) . '₺' ?>
                                    </td>

                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix">
                        <div class="hint-text"><b id="currentPageEntries6">1</b> arası <b id="totalEntries6"></b> kayıt
                            gösteriliyor</div>
                        <ul class="pagination" id="pagination6">
                            <!-- Dinamik sayfalama burada olacak -->
                        </ul>
                    </div>
                </div>




                <div class="table-wrapper mt-0">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-6">
                                <H2 class="custombg1h2">Sinema Salonları</H2>
                            </div>
                            <div class="col-sm-6">
                                <a href="#uploadModalsinema" class="btn btn-success d-flex align-items-center mr-2"
                                    data-toggle="modal">
                                    <i class="material-icons">&#xE147;</i> <span>Excel Dosyası Yükle</span>
                                </a>
                            </div>

                            <div class="col-12 ml-1 rowSelect">
                                <label for="rowsPerPageSelect7">Satır Sayısı: </label>
                                <select id="rowsPerPageSelect7" class="rows-per-page-select">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="table-over">
                        <table class="table table-striped table-hover  paginated-table">
                            <thead>
                                <tr>
                                    <th>Şehir</th>
                                    <th>Sinema</th>
                                    <th>Format</th>
                                    <th>Dil</th>
                                    <th>1.Seans</th>
                                    <th>2.Seans</th>
                                    <th>3.Seans</th>
                                    <th>4.Seans</th>
                                    <th>5.Seans</th>
                                    <th>6.Seans</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($salonlar as $salon): ?>
                                <tr>
                                    <td class="align-middle"><?= htmlspecialchars($salon['sehir']) ?></td>
                                    <td class="align-middle"><?= htmlspecialchars($salon['sinema']) ?></td>
                                    <td class="align-middle"><?= htmlspecialchars($salon['format']) ?></td>
                                    <td class="align-middle"><?= htmlspecialchars($salon['dil']) ?></td>
                                    <td class="align-middle">
                                        <?= !empty($salon['seans1']) ? htmlspecialchars($salon['seans1']) : '-' ?></td>
                                    <td class="align-middle">
                                        <?= !empty($salon['seans2']) ? htmlspecialchars($salon['seans2']) : '-' ?></td>
                                    <td class="align-middle">
                                        <?= !empty($salon['seans3']) ? htmlspecialchars($salon['seans3']) : '-' ?></td>
                                    <td class="align-middle">
                                        <?= !empty($salon['seans4']) ? htmlspecialchars($salon['seans4']) : '-' ?></td>
                                    <td class="align-middle">
                                        <?= !empty($salon['seans5']) ? htmlspecialchars($salon['seans5']) : '-' ?></td>
                                    <td class="align-middle">
                                        <?= !empty($salon['seans6']) ? htmlspecialchars($salon['seans6']) : '-' ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                    <div class="clearfix">
                        <div class="hint-text"><b id="currentPageEntries7">1</b> arası <b id="totalEntries7"></b> kayıt
                            gösteriliyor</div>
                        <ul class="pagination" id="pagination7">
                            <!-- Dinamik sayfalama burada olacak -->
                        </ul>
                    </div>
                </div>


                <div class="d-flex justify-content-between align-items-center custombg1 mt-5 mb-0">
                    <h2>Film Detayları</h2>
                </div>

                <div id="uploadModal" class="modal fade">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <form id="uploadForm" method="post" enctype="multipart/form-data">
                                <input type="hidden" value="<?php echo $filmler2['id'] ?>" name="filmid">
                                <input type="hidden" value="<?php echo $filmler2['dagitim_id'] ?>" name="dagitimid">

                                <div class="modal-header">
                                    <h4 class="modal-title">Film Verileri Ekle</h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">&times;</button>
                                </div>

                                <div class="modal-body">
                                    <div class="containerswitch">
                                        <span class="labelswitch1">Hafta içi</span>
                                        <label class="switch" for="checkbox13">
                                            <input type="checkbox" id="checkbox13" />
                                            <div class="slider round"></div>
                                        </label>
                                        <span class="labelswitch1">Hafta Sonu</span>
                                    </div>
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label">Excel Dosyasını Seçin:</label>
                                        <input class="form-control" type="file" id="formFile" name="excelFile"
                                            accept=".xlsx, .xls">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Geri">
                                    <input type="button" class="btn btn-info" id="submitForm" value="Kaydet">
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <div id="uploadModalsinema" class="modal fade">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <form id="uploadFormsinema" method="post" enctype="multipart/form-data">
                                <input type="hidden" value="<?php echo $filmler2['id'] ?>" name="filmidd">
                                <input type="hidden" value="<?php echo $filmler2['dagitim_id'] ?>" name="dagitimidd">

                                <div class="modal-header">
                                    <h4 class="modal-title">Film Sinema Salonları Ekle</h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label">Excel Dosyasını Seçin:</label>
                                        <input class="form-control" type="file" id="formFile2" name="excelFile"
                                            accept=".xlsx, .xls">
                                    </div>
                                    <div class="mb-3">
                                        <label for="basdate" class="form-label">Başlangıç tarihi :</label>
                                        <input class="form-control" type="date" id="basdate" name="basdate">
                                    </div>
                                    <div class="mb-3">
                                        <label for="bitisdate" class="form-label">Bitiş Tarihi :</label>
                                        <input class="form-control" type="date" id="bitisdate" name="bitisdate">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Geri">
                                    <input type="button" class="btn btn-info" id="submitForm2" value="Kaydet">
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <form id="filmdetay" method="post" enctype="multipart/form-data">
                    <input type="hidden" value="<?php echo $filmler2['id'] ?>" name="film_id">

                    <div class="row filmDetayAyar bg-white border bt-0 p-3 m-0">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="filmAdi">Film Adı</label>
                                <input type="text" class="form-control" id="filmAdi" name="filmadedit"
                                    value="<?php echo $filmler2['film_adi']  ?>"
                                    placeholder="Varsa Film Adı Burda Yazıcak">
                            </div>
                            <div class="form-group">
                                <label for="vizyonTarihi">Vizyon Tarihi</label>
                                <input type="date" class="form-control" name="vizyontaredit"
                                    value="<?php echo $filmler2['vizyon_tarihi']  ?>" id="vizyonTarihi">
                            </div>

                            <div class="form-group">
                                <label>Film Süresi</label>
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <input type="number" name="filmsureedit" class="form-control"
                                        value="<?php echo $filmler2['filmsure']; ?>"
                                        placeholder="Film Süresi dakika olarak" min="0">

                                </div>
                            </div>

                            <!-- Sinema Dağıtım -->
                            <div class="form-group">
                                <label>Sinema Dağıtım</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control"
                                        value="<?php echo $filmler2['dagitim']  ?>"
                                        placeholder="Seçilen dağıtım şirketleri" readonly
                                        onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
                            $dagitimlar = explode(", ", $filmler2['dagitim']); // Seçili dağıtımları al
                            foreach ($dagitimListesi as $dagitim) {
                                $id = htmlspecialchars($dagitim['iddagitim']);
                                $country_name = htmlspecialchars($dagitim['dagitimad']);
                                $checked = in_array($country_name, $dagitimlar) ? 'checked' : ''; // Seçili ise 'checked' ekle
                                echo "<label for='dagitim1{$id}'><input type='checkbox' id='dagitim1{$id}' name='dagitimListesiedit[]' value='{$id}' {$checked} onclick='updateTags(this)' />{$country_name}</label>";
                            }
                        ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Stüdyo -->
                            <div class="form-group">
                                <label>Stüdyo</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control" placeholder="Seçilen stüdyolar"
                                        value="<?php echo $filmler2['studyolar'] ?>" readonly
                                        onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
                                              $studyolar = explode(", ", $filmler2['studyolar']); // Seçili stüdyoları al
                                              foreach ($studyoListesi as $studyo) {
                                                  $id = htmlspecialchars($studyo['id']);
                                                  $country_name = htmlspecialchars($studyo['studyoad']);
                                                  $checked = in_array($country_name, $studyolar) ? 'checked' : ''; // Seçili ise 'checked' ekle
                                                  echo "<label for='studyo1{$id}'>
                                                          <input type='checkbox' id='studyo1{$id}' name='studyoListesiedit[]' value='{$id}' {$checked} onclick='updateTags(this)' />
                                                          {$country_name}
                                                        </label>";
                                              }
                                              ?>
                                    </div>
                                </div>
                            </div>

                            <!-- ülke -->
                            <div class="form-group">
                                <label>Ülke</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control" placeholder="Seçilen stüdyolar"
                                        value="<?php echo $filmler2['ulkeler'] ?>" readonly
                                        onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
                                              $ulkeler = explode(", ", $filmler2['ulkeler']); // Seçili stüdyoları al
                                              foreach ($ulkeListesi as $ulke) {
                                                  $id = htmlspecialchars($ulke['id']);
                                                  $country_name = htmlspecialchars($ulke['country_name']);
                                                  $checked = in_array($country_name, $ulkeler) ? 'checked' : ''; // Seçili ise 'checked' ekle
                                                  echo "<label for='ulke1{$id}'>
                                                          <input type='checkbox' id='ulke1{$id}' name='ulkeListesiedit[]' value='{$id}' {$checked} onclick='updateTags(this)' />
                                                          {$country_name}
                                                        </label>";
                                              }
                                              ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Film Türü -->
                            <div class="form-group">
                                <label>Film Türü</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control" placeholder="Seçilen film türleri"
                                        value="<?php echo $filmler2['filmturleri'] ?>" readonly
                                        onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
                                              $filmturleri = explode(", ", $filmler2['filmturleri']); // Seçili stüdyoları al
                                              foreach ($filmturuListesi as $filmturuu) {
                                                  $id = htmlspecialchars($filmturuu['idfilm']);
                                                  $country_name = htmlspecialchars($filmturuu['filmturu']);
                                                  $checked = in_array($country_name, $filmturleri) ? 'checked' : ''; // Seçili ise 'checked' ekle
                                                  echo "<label for='filmturu1{$id}'>
                                                          <input type='checkbox' id='filmturu1{$id}' name='filmturuListesiedit[]' value='{$id}' {$checked} onclick='updateTags(this)' />
                                                          {$country_name}
                                                        </label>";
                                              }
                                              ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <!-- Yönetmen -->
                            <div class="form-group">
                                <label>Yönetmen</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control" placeholder="Seçilen yönetmen"
                                        value="<?php echo htmlspecialchars(implode(', ', $yonetmenler)); ?>" readonly
                                        onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
            // Yönetmenler dizisini kontrol ediyoruz

            // Veritabanından gelen oyuncuların üzerinden geçiyoruz
            foreach ($veriler as $row) {
                $id = htmlspecialchars($row['idoyuncu']);
                $oyuncuad = htmlspecialchars($row['adsoyad']);
                $istediginiz_sayi = 34; // Örnek bir değer, gerçek uygulamanızda güncelleyin
                $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';

                // Oyuncunun kategorisini kontrol et
                if (preg_match($pattern, $row['kategori_idler'])) {
                    // Eğer oyuncu adı yönetmenler dizisinde bulunuyorsa, checkbox'ı işaretle
                    $checked = in_array($oyuncuad, $yonetmenler) ? 'checked' : '';
                    
                    // Checkbox'ı oluştur
                    echo "<label for='yonetmen1{$id}'><input type='checkbox' id='yonetmen1{$id}' name='yonetmenListesiedit[]' value='{$id}' onclick='updateTags(this)' {$checked}/>{$oyuncuad}</label>";
                }
            }
            ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Senaryo -->
                            <div class="form-group">
                                <label>Senaryo</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control" placeholder="Seçilen yönetmen"
                                        value="<?php echo htmlspecialchars(implode(', ', $senaryolar)); ?>" readonly
                                        onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
            // Yönetmenler dizisini kontrol ediyoruz

            // Veritabanından gelen oyuncuların üzerinden geçiyoruz
            foreach ($veriler as $row) {
                $id = htmlspecialchars($row['idoyuncu']);
                $oyuncuad = htmlspecialchars($row['adsoyad']);
                $istediginiz_sayi = 38; // Örnek bir değer, gerçek uygulamanızda güncelleyin
                $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';

                // Oyuncunun kategorisini kontrol et
                if (preg_match($pattern, $row['kategori_idler'])) {
                    // Eğer oyuncu adı yönetmenler dizisinde bulunuyorsa, checkbox'ı işaretle
                    $checked = in_array($oyuncuad, $senaryolar) ? 'checked' : '';
                    
                    // Checkbox'ı oluştur
                    echo "<label for='senaryo1{$id}'><input type='checkbox' id='senaryo1{$id}' name='senaryoListesiedit[]' value='{$id}' onclick='updateTags(this)' {$checked}/>{$oyuncuad}</label>";
                }
            }
            ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Görüntü Yönetmeni -->
                            <div class="form-group">
                                <label>Görüntü Yönetmeni</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control"
                                        value="<?php echo htmlspecialchars(implode(', ', $GörüntüYönetmeni)); ?>"
                                        placeholder="Seçilen görüntü yönetmeni" readonly onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
            // Yönetmenler dizisini kontrol ediyoruz

            // Veritabanından gelen oyuncuların üzerinden geçiyoruz
            foreach ($veriler as $row) {
                $id = htmlspecialchars($row['idoyuncu']);
                $oyuncuad = htmlspecialchars($row['adsoyad']);
                $istediginiz_sayi = 35; // Örnek bir değer, gerçek uygulamanızda güncelleyin
                $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';

                // Oyuncunun kategorisini kontrol et
                if (preg_match($pattern, $row['kategori_idler'])) {
                    // Eğer oyuncu adı yönetmenler dizisinde bulunuyorsa, checkbox'ı işaretle
                    $checked = in_array($oyuncuad, $GörüntüYönetmeni) ? 'checked' : '';
                    
                    // Checkbox'ı oluştur
                    echo "<label for='goryonetmen{$id}'><input type='checkbox' id='goryonetmen{$id}' name='goryonetmenListesiedit[]' value='{$id}' onclick='updateTags(this)' {$checked}/>{$oyuncuad}</label>";
                }
            }
            ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Kurgu -->
                            <div class="form-group">
                                <label>Kurgu</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control"
                                        value="<?php echo htmlspecialchars(implode(', ', $Kurgu)); ?>"
                                        placeholder="Seçilen film türleri burada görünecek..." readonly
                                        onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
            // Yönetmenler dizisini kontrol ediyoruz

            // Veritabanından gelen oyuncuların üzerinden geçiyoruz
            foreach ($veriler as $row) {
                $id = htmlspecialchars($row['idoyuncu']);
                $oyuncuad = htmlspecialchars($row['adsoyad']);
                $istediginiz_sayi = 37; // Örnek bir değer, gerçek uygulamanızda güncelleyin
                $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';

                // Oyuncunun kategorisini kontrol et
                if (preg_match($pattern, $row['kategori_idler'])) {
                    // Eğer oyuncu adı yönetmenler dizisinde bulunuyorsa, checkbox'ı işaretle
                    $checked = in_array($oyuncuad, $Kurgu) ? 'checked' : '';
                    
                    // Checkbox'ı oluştur
                    echo "<label for='kurgu1{$id}'><input type='checkbox' id='kurgu1{$id}' name='kurguListesiedit[]' value='{$id}' onclick='updateTags(this)' {$checked}/>{$oyuncuad}</label>";
                }
            }
            ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Müzik -->
                            <div class="form-group">
                                <label>Müzik</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control"
                                        value="<?php echo htmlspecialchars(implode(', ', $Müzik)); ?>"
                                        placeholder="Seçilen film türleri burada görünecek..." readonly
                                        onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
            // Yönetmenler dizisini kontrol ediyoruz

            // Veritabanından gelen oyuncuların üzerinden geçiyoruz
            foreach ($veriler as $row) {
                $id = htmlspecialchars($row['idoyuncu']);
                $oyuncuad = htmlspecialchars($row['adsoyad']);
                $istediginiz_sayi = 36; // Örnek bir değer, gerçek uygulamanızda güncelleyin
                $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';

                // Oyuncunun kategorisini kontrol et
                if (preg_match($pattern, $row['kategori_idler'])) {
                    // Eğer oyuncu adı yönetmenler dizisinde bulunuyorsa, checkbox'ı işaretle
                    $checked = in_array($oyuncuad, $Müzik) ? 'checked' : '';
                    
                    // Checkbox'ı oluştur
                    echo "<label for='muzik1{$id}'><input type='checkbox' id='muzik1{$id}' name='muzikListesiedit[]' value='{$id}' onclick='updateTags(this)' {$checked}/>{$oyuncuad}</label>";
                }
            }
            ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Film Oyuncuları -->
                            <div class="form-group">
                                <label>Film Oyuncuları</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control"
                                        value="<?php echo htmlspecialchars(implode(', ', $Oyuncu)); ?>"
                                        placeholder="Seçilen film oyuncuları" readonly onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
                                        // Yönetmenler dizisini kontrol ediyoruz
                                    
                                        // Veritabanından gelen oyuncuların üzerinden geçiyoruz
                                        foreach ($veriler as $row) {
                                            $id = htmlspecialchars($row['idoyuncu']);
                                            $oyuncuad = htmlspecialchars($row['adsoyad']);
                                            $istediginiz_sayi = 29; // Örnek bir değer, gerçek uygulamanızda güncelleyin
                                            $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';
                                        
                                            // Oyuncunun kategorisini kontrol et
                                            if (preg_match($pattern, $row['kategori_idler'])) {
                                                // Eğer oyuncu adı yönetmenler dizisinde bulunuyorsa, checkbox'ı işaretle
                                                $checked = in_array($oyuncuad, $Oyuncu) ? 'checked' : '';
                                                
                                                // Checkbox'ı oluştur
                                                echo "<label for='oyuncu1{$id}'><input type='checkbox' id='oyuncu1{$id}' name='oyuncuListesiedit[]' value='{$id}' onclick='updateTags(this)' {$checked}/>{$oyuncuad}</label>";
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Toplam Hasılat -->
                            <div class="form-group">
                                <label>Toplam Hasılat</label>
                                <input type="number" name="topHasilatedit"   value="<?php echo $filmler2['topHasilat']; ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Toplam Seyirci</label>
                                <input type="number" name="topSeyirciedit"   value="<?php echo $filmler2['topKisi']; ?>" class="form-control">
                            </div>

                        </div>
                        <!-- Film Aciklamasi -->
                        <div class="col-12">
                            <div class="form-group">
                                <label for="filmKonu">Filmin Konusu</label>
                                <textarea class="form-control textarea" rows="6" name="filmkonu"
                                    id="filmKonu"><?php echo $filmler2['film_konu']  ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center custombg1 mt-5 mb-0">
                        <h2>Afiş Resimi</h2>
                    </div>

                    <div class="row bg-white border bt-0 p-3 m-0">
                        <div class="col-md-3 mb-4">
                            <div class="card bgdark">
                                <img class="card-img-top" src="../kapakfoto/<?php echo $filmler2['kapak_resmi']  ?>"
                                    alt="">
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="multiple-uploader" id="single-uploader-film-edit">
                                <div class="mup-msg">
                                    <span class="mup-main-msg">Kapak Resmi Yüklemek için
                                        Tıklayınız.</span>
                                    <span class="mup-msg" id="max-upload-number">Sadece 1 Kapak
                                        Fotoğrafı Yükleyiniz.</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex justify-content-between align-items-center custombg1 mt-5 mb-0">
                        <h2>Galeri Resimleri</h2>
                    </div>

                    <div class="row bg-white border bt-0 p-3 m-0">
                        <?php
                                // $filmler2['resimler'] dizisindeki resimleri ayırın
                                $resimler = explode(', ', $filmler2['resimler']); // Resimler virgülle ayrılmış olabilir

                                // Her bir resmi HTML'deki yapıya yerleştirmek için döngü kullanın
                                foreach ($resimler as $resim) {
                            ?>
                        <div class="col-md-3 mb-4">
                            <div class="card bgdark">
                                <img class="card-img-top" src="../galeri/<?php echo htmlspecialchars($resim); ?>"
                                    alt="">
                            </div>
                        </div>
                        <?php
                                }
                            ?>

                        <div class="col-md-3 mb-4">
                            <div class="multiple-uploader" id="multiple-uploader-galerifilm">
                                <div class="mup-msg">
                                    <span class="mup-main-msg">Film Galerisine Fotoğraf Eklemek için
                                        Tıklayınız.</span>
                                    <span class="mup-msg" id="max-upload-number">En Az 3 Fotoğraf
                                        Yükleyiniz.</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-lg">Kaydet</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- DİZİLER -->
            <div id="content4" class="content" style="display: none;">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 top-section">
                            <div class="table-wrapper">
                                <div class="table-title">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <H2>DİZİLER</H2>
                                        </div>
                                        <div class="col-sm-6">
                                            <a href="#addEmployeeModaldizi"
                                                class="btn btn-success d-flex align-items-center" data-toggle="modal">
                                                <i class="material-icons mr-2">&#xE147;</i> <span>Dizi Ekle</span>
                                            </a>

                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12 ml-4 rowSelect">
                                                <label for="rowsPerPageSelect8">Satır Sayısı: </label>
                                                <select id="rowsPerPageSelect8" class="rows-per-page-select">
                                                    <option value="5">5</option>
                                                    <option value="10">10</option>
                                                    <option value="20">20</option>
                                                    <option value="50">50</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-over">
                                    <table class="table table-striped table-hover paginated-table">
                                        <thead>
                                            <tr>
                                                <th class="w-fit">Dizi Afişi</th>
                                                <th>Dizi Adı</th>
                                                <th>Vizyon Tarihi</th>
                                                <th>Dizi Türü</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($diziler as $row): ?>
                                            <tr>
                                                <td><img src="../kapakfoto/<?php echo htmlspecialchars($row['kapak_resmi']); ?>"
                                                        class="rounded img-thumbnail tumbimg" alt="Fotoğraf"></td>
                                                <td class="align-middle">
                                                    <?php echo htmlspecialchars($row['film_adi']); ?>
                                                </td>
                                                <td class="align-middle">
                                                    <?php echo formatDate($row['vizyon_tarihi']); ?></td>
                                                <td class="align-middle">
                                                    <?php echo !empty($row['filmturleri']) ? htmlspecialchars($row['filmturleri']) : '-'; ?>
                                                </td>



                                                </td>
                                                <td class="align-middle">

                                                    <a href="#deleteEmployeeModaldiziler" class="btn-delete"
                                                        onclick="getId('<?php echo $row['id']; ?>');"
                                                        data-toggle="modal"><i class="material-icons"
                                                            data-toggle="tooltip" title="Delete">&#xE872;</i></a>

                                                </td>
                                                <td class="align-middle">
                                                    <button
                                                        onclick="showContent('content9','<?php echo $row['id']; ?>','dizi')"
                                                        class="btn-page"><i
                                                            class="material-icons">chevron_right</i></button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="clearfix">
                                    <div class="hint-text"><b id="currentPageEntries8">1</b> arası <b
                                            id="totalEntries8"></b> kayıt gösteriliyor</div>
                                    <ul class="pagination" id="pagination8">
                                        <!-- Dinamik sayfalama burada olacak -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- ADD Modal HTML -->
                        <div id="addEmployeeModaldizi" class="modal fade">
                            <div class="modal-dialog modal-xl">
                                <!-- Modal genişliğini artırmak için modal-xl kullanıldı -->
                                <div class="modal-content">
                                    <form id="diziForm" method="post" enctype="multipart/form-data">

                                        <div class="modal-header">
                                            <h4 class="modal-title">Dizi Ekle</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <!-- İlk sütun -->
                                                    <div class="form-group">
                                                        <label>Dizi Adı</label>
                                                        <input type="text" name="filmadi" class="form-control">
                                                    </div>
                                                    <!-- Vizyon Tarihi -->
                                                    <div class="form-group">
                                                        <label>Vizyon Tarihi</label>
                                                        <input type="date" name="vizyonTarihi" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="vizyonTarihi">Bitiş Tarihi</label>
                                                        <input type="date" class="form-control" name="bitistar"
                                                            value="<?php echo $filmler2['bitis_tarihi']  ?>"
                                                            id="bitis_tarihi">
                                                    </div>
                                                    <!-- Sinema Dağıtım 
                                                    <div class="form-group">
                                                        <label for="sinemadagitim">Sinema Dağıtım</label>
                                                        <div class="selected-tags">
                                                            <input type="text" id="sinemadagitim" name="sinemadagitim"
                                                                class="tagInput form-control"
                                                                placeholder="Seçilen dağıtım şirketleri" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                    foreach ($dagitimListesi as $dagitim) {
                                                                        $id = htmlspecialchars($dagitim['iddagitim']);
                                                                        $country_name = htmlspecialchars($dagitim['dagitimad']);
                                                                        echo "<label for='dagitim{$id}'><input type='checkbox' id='dagitim{$id}' name='dagitimListesi[]' value='{$id}' onclick='updateTags(this)' />{$country_name}</label>";
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                    <!-- Stüdyo 
                                                    <div class="form-group">
                                                        <label>Stüdyo</label>
                                                        <div class="selected-tags">
                                                            <input type="text" class="tagInput form-control"
                                                                placeholder="Seçilen stüdyolar" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                    foreach ($studyoListesi as $studyo) {
                                                                        $id = htmlspecialchars($studyo['id']);
                                                                        $country_name = htmlspecialchars($studyo['studyoad']);
                                                                        echo "<label for='studyo{$id}'><input type='checkbox' id='studyo{$id}' name='studyoListesi[]' value='{$id}' onclick='updateTags(this)' />{$country_name}</label>";
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div> -->

                                                    <!-- ülke -->
                                                    <div class="form-group">
                                                        <label>Ülke</label>
                                                        <div class="selected-tags">
                                                            <input type="text" class="tagInput form-control"
                                                                placeholder="Seçilen ülkeler" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                    foreach ($ulkeListesi as $ulke) {
                                                                        $id = htmlspecialchars($ulke['id']);
                                                                        $country_name = htmlspecialchars($ulke['country_name']);
                                                                        echo "<label for='ulkelerr{$id}'><input type='checkbox' id='ulkelerr{$id}' name='ulkeListesi[]' value='{$id}' onclick='updateTags(this)' />{$country_name}</label>";
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Film Türü -->
                                                    <div class="form-group">
                                                        <label>Dizi Türü</label>
                                                        <div class="selected-tags">
                                                            <input type="text" class="tagInput form-control"
                                                                placeholder="Seçilen film türleri" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                    foreach ($filmturuListesi as $filmturu) {
                                                                        $id = htmlspecialchars($filmturu['idfilm']);
                                                                        $film_turu = htmlspecialchars($filmturu['filmturu']);
                                                                        echo "<label for='filmturuu{$id}'><input type='checkbox' id='filmturuu{$id}' name='filmturuListesi[]' value='{$id}' onclick='updateTags(this)' />{$film_turu}</label>";
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>



                                                </div>

                                                <div class="col-md-6">
                                                    <!-- İkinci sütun -->


                                                    <!-- Yönetmen -->
                                                    <div class="form-group">
                                                        <label>Yönetmen</label>
                                                        <div class="selected-tags">
                                                            <input type="text" class="tagInput form-control"
                                                                placeholder="Seçilen yönetmen" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                   foreach ($veriler as $row) {
                                                                       
                                                                    $id = htmlspecialchars($row['idoyuncu']);
                                                                    $oyuncuad = htmlspecialchars($row['adsoyad']);
                                                                    $istediginiz_sayi = 34;
                                                                    $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';
                                                                    if (preg_match($pattern, $row['kategori_idler'])) {
                                                                        echo "<label for='yonetmenn{$id}'><input type='checkbox' id='yonetmenn{$id}' name='yonetmenListesi[]' value='{$id}' onclick='updateTags(this)' />{$oyuncuad}</label>";

                                                                    } 
                                                                   
                                                            }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Senaryo -->
                                                    <div class="form-group">
                                                        <label>Senaryo</label>
                                                        <div class="selected-tags">
                                                            <input type="text" class="tagInput form-control"
                                                                placeholder="Seçilen senaryo yazarı" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                    foreach ($veriler as $row) {
                                                                       
                                                                        $id = htmlspecialchars($row['idoyuncu']);
                                                                        $oyuncuad = htmlspecialchars($row['adsoyad']);
                                                                        $istediginiz_sayi = 38;
                                                                        $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';
                                                                        if (preg_match($pattern, $row['kategori_idler'])) {
                                                                            echo "<label for='senaryoo{$id}'><input type='checkbox' id='senaryoo{$id}' name='senaryoListesi[]' value='{$id}' onclick='updateTags(this)' />{$oyuncuad}</label>";

                                                                        } 
                                                                       
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Görüntü Yönetmeni 
                                                    <div class="form-group">
                                                        <label>Görüntü Yönetmeni</label>
                                                        <div class="selected-tags">
                                                            <input type="text" class="tagInput form-control"
                                                                placeholder="Seçilen görüntü yönetmeni" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                   foreach ($veriler as $row) {
                                                                       
                                                                    $id = htmlspecialchars($row['idoyuncu']);
                                                                    $oyuncuad = htmlspecialchars($row['adsoyad']);
                                                                    $istediginiz_sayi = 35;
                                                                    $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';
                                                                    if (preg_match($pattern, $row['kategori_idler'])) {
                                                                        echo "<label for='gyonetmenn{$id}'><input type='checkbox' id='gyonetmenn{$id}' name='gyonetmeniListesi[]' value='{$id}' onclick='updateTags(this)' />{$oyuncuad}</label>";

                                                                    } 
                                                                   
                                                            }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>-->
                                                    <!-- Kurgu -->
                                                    <div class="form-group">
                                                        <label>Kurgu</label>
                                                        <div class="selected-tags">
                                                            <input type="text" class="tagInput form-control"
                                                                placeholder="Seçilen film türleri burada görünecek..."
                                                                readonly onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                    foreach ($veriler as $row) {
                                                                       
                                                                        $id = htmlspecialchars($row['idoyuncu']);
                                                                        $oyuncuad = htmlspecialchars($row['adsoyad']);
                                                                        $istediginiz_sayi = 37;
                                                                        $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';
                                                                        if (preg_match($pattern, $row['kategori_idler'])) {
                                                                            echo "<label for='kurguu{$id}'><input type='checkbox' id='kurguu{$id}' name='kurguListesi[]' value='{$id}' onclick='updateTags(this)' />{$oyuncuad}</label>";

                                                                        } 
                                                                       
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Müzik -->
                                                    <div class="form-group">
                                                        <label>Müzik</label>
                                                        <div class="selected-tags">
                                                            <input type="text" class="tagInput form-control"
                                                                placeholder="Seçilen film türleri burada görünecek..."
                                                                readonly onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                    foreach ($veriler as $row) {
                                                                       
                                                                        $id = htmlspecialchars($row['idoyuncu']);
                                                                        $oyuncuad = htmlspecialchars($row['adsoyad']);
                                                                        $istediginiz_sayi = 36;
                                                                        $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';
                                                                        if (preg_match($pattern, $row['kategori_idler'])) {
                                                                            echo "<label for='muzikk{$id}'><input type='checkbox' id='muzikk{$id}' name='müzikListesi[]' value='{$id}' onclick='updateTags(this)' />{$oyuncuad}</label>";

                                                                        } 
                                                                       
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Film Oyuncuları -->
                                                    <div class="form-group">
                                                        <label>Dizi Oyuncuları</label>
                                                        <div class="selected-tags">
                                                            <input type="text" class="tagInput form-control"
                                                                placeholder="Seçilen film oyuncuları" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..." onkeyup="filterFunction(this)">
                                                                <?php
                                                                    foreach ($veriler as $row) {
                                                                       
                                                                            $id = htmlspecialchars($row['idoyuncu']);
                                                                            $oyuncuad = htmlspecialchars($row['adsoyad']);
                                                                            $istediginiz_sayi = 29;
                                                                            $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';
                                                                            if (preg_match($pattern, $row['kategori_idler'])) {
                                                                                echo "<label for='filmoyuncuu{$id}'><input type='checkbox' id='filmoyuncuu{$id}' name='oyuncuListesi[]' value='{$id}' onclick='updateTags(this)' />{$oyuncuad}</label>";

                                                                            } 
                                                                           
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                                <!-- Film Aciklamasi -->
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="filmKonuu">Dizinin Konusu</label>
                                                        <textarea class="form-control textarea" rows="6" name="filmKonu"
                                                            id="filmKonu"></textarea>
                                                    </div>
                                                </div>
                                                <!-- sağ sol sütun bitiş -->
                                                <div class="multiple-uploader" id="single-uploader-dizi">
                                                    <div class="mup-msg">
                                                        <span class="mup-main-msg">Kapak Resmi Yüklemek için
                                                            Tıklayınız.</span>
                                                        <span class="mup-msg" id="max-upload-number">Sadece 1 Kapak
                                                            Fotoğrafı Yükleyiniz.</span>

                                                    </div>
                                                </div>


                                                <div class="multiple-uploader" id="multiple-uploader-dizi">
                                                    <div class="mup-msg">
                                                        <span class="mup-main-msg">Dizi Galerisine Fotoğraf Eklemek için
                                                            Tıklayınız.</span>
                                                        <span class="mup-msg" id="max-upload-number">En Az 3 Fotoğraf
                                                            Yükleyiniz.</span>

                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="button" id="addoyuncugeri" class="btn btn-default"
                                                data-dismiss="modal" value="Geri">
                                            <input type="submit" class="btn btn-info" value="Kaydet">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <!-- Delete Modal HTML -->
                        <div id="deleteEmployeeModaldiziler" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form>
                                        <div class="modal-header">
                                            <h4 class="modal-title">Sil</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Bu kayıtları silmek istediğinizden emin misiniz?</p>
                                            <p class="text-warning"><small>Bu işlem geri alınamaz.</small></p>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="button" id="deletefilmgeri" class="btn btn-default"
                                                data-dismiss="modal" value="Geri">
                                            <input type="submit" id="diziSil" class="btn btn-danger" value="Sil">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Film Türü İşlemleri -->

                    <div class="col-12 bottom-section">
                        <div class="row wrap-1440">
                            <div class="col-md-12 left-column p-0">
                                <div class="table-wrapper">
                                    <div class="table-title">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <h2>DİZİ TÜRLERİ</h2>
                                            </div>
                                            <div class="col-sm-6">
                                                <a href="#addEmployeeModalturu1"
                                                    class="btn btn-success d-flex align-items-center"
                                                    data-toggle="modal">
                                                    <i class="material-icons mr-2">&#xE147;</i> <span>Dizi Türü
                                                        Ekle</span>
                                                </a>

                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-12 ml-4 rowSelect">
                                                    <label for="rowsPerPageSelect9">Satır Sayısı: </label>
                                                    <select id="rowsPerPageSelect9" class="rows-per-page-select">
                                                        <option value="5">5</option>
                                                        <option value="10">10</option>
                                                        <option value="20">20</option>
                                                        <option value="50">50</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-over">
                                        <table class="table table-striped table-hover paginated-table">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">Dizi Türü</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <?php 
                                                       foreach ($filmturuListesi as $filmturu) {
                                                        echo "<td>" . htmlspecialchars($filmturu['filmturu']) . "</td>"; // Her bir kategori adını güvenli bir şekilde göster
                                                    
                                                    
                                                       ?>

                                                    <td class="text-center">

                                                        <a href="#deleteEmployeeModaldizituru" id="kategoridelete"
                                                            onclick="getId('<?php echo $filmturu['idfilm']; ?>');"
                                                            class="btn-delete p-03" data-toggle="modal">
                                                            <i class="material-icons" data-toggle="tooltip"
                                                                title="Delete">&#xE872;</i>
                                                        </a>
                                                    </td>
                                                </tr>

                                                <?php     }?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="clearfix">
                                        <div class="hint-text"><b id="currentPageEntries9">1</b> arası <b
                                                id="totalEntries9"></b> kayıt gösteriliyor</div>
                                        <ul class="pagination" id="pagination9">
                                            <!-- Dinamik sayfalama burada olacak -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- Add Modal HTML -->
                            <div id="addEmployeeModalturu1" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form id="kategoriEkleForm">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Ekle</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Dizi Türü</label>
                                                    <input type="text" name="dizi_turu" id="dizi_turu"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="button" id="addfilmturuModal" class="btn btn-default"
                                                    data-dismiss="modal" value="Geri">
                                                <input type="button" class="btn btn-info" value="Kaydet"
                                                    id="submitBtndizituru">
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal HTML -->
                            <div id="deleteEmployeeModaldizituru" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form>
                                            <div class="modal-header">
                                                <h4 class="modal-title">Sil</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">&times;</button>
                                            </div>
                                            <div class="modal-body">

                                                <p>Bu kayıtları silmek istediğinizden emin misiniz?</p>
                                                <p class="text-warning"><small>Bu işlem geri alınamaz.</small></p>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="button" id="deleteEmployeeModalfilmturugeri"
                                                    class="btn btn-default" data-dismiss="modal" value="Geri">
                                                <input type="submit" id="dizituruSil" class="btn btn-danger"
                                                    value="Sil">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>









                        </div>

                    </div>
                </div>

            </div>

            <div id="content9" class="content" style="display: none;">
                <form id="dizidetay" method="post" enctype="multipart/form-data">
                    <input type="hidden" value="<?php echo $filmler2['id'] ?>" name="film_id">

                    <div class="d-flex justify-content-between align-items-center custombg1 mt-5 mb-0">
                        <h2>Dizi Detayları</h2>
                    </div>

                    <div class="row filmDetayAyar bg-white border bt-0 p-3 m-0">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="filmAdi">Dizi Adı</label>
                                <input type="text" class="form-control" id="filmAdi" name="filmadedit"
                                    value="<?php echo $filmler2['film_adi']  ?>"
                                    placeholder="Varsa Film Adı Burda Yazıcak">
                            </div>
                            <div class="form-group">
                                <label for="vizyonTarihi">Başlama Tarihi</label>
                                <input type="date" class="form-control" name="vizyontaredit"
                                    value="<?php echo $filmler2['vizyon_tarihi']  ?>" id="vizyonTarihi">
                            </div>
                            <div class="form-group">
                                <label for="vizyonTarihi">Bitiş Tarihi</label>
                                <input type="date" class="form-control" name="bitistaredit"
                                    value="<?php echo $filmler2['bitis_tarihi']  ?>" id="bitis_tarihi">
                            </div>
                            <!-- Sinema Dağıtım 
                            <div class="form-group">
                                <label>Sinema Dağıtım</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control"
                                        value="<?php echo $filmler2['dagitim']  ?>"
                                        placeholder="Seçilen dağıtım şirketleri" readonly
                                        onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
                            $dagitimlar = explode(", ", $filmler2['dagitim']); // Seçili dağıtımları al
                            foreach ($dagitimListesi as $dagitim) {
                                $id = htmlspecialchars($dagitim['iddagitim']);
                                $country_name = htmlspecialchars($dagitim['dagitimad']);
                                $checked = in_array($country_name, $dagitimlar) ? 'checked' : ''; // Seçili ise 'checked' ekle
                                echo "<label for='dagitim1{$id}'><input type='checkbox' id='dagitim1{$id}' name='dagitimListesiedit[]' value='{$id}' {$checked} onclick='updateTags(this)' />{$country_name}</label>";
                            }
                        ?>
                                    </div>
                                </div>
                            </div> -->
                            <!-- Stüdyo
                            <div class="form-group">
                                <label>Stüdyo</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control" placeholder="Seçilen stüdyolar"
                                        value="<?php echo $filmler2['studyolar'] ?>" readonly
                                        onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
                                              $studyolar = explode(", ", $filmler2['studyolar']); // Seçili stüdyoları al
                                              foreach ($studyoListesi as $studyo) {
                                                  $id = htmlspecialchars($studyo['id']);
                                                  $country_name = htmlspecialchars($studyo['studyoad']);
                                                  $checked = in_array($country_name, $studyolar) ? 'checked' : ''; // Seçili ise 'checked' ekle
                                                  echo "<label for='studyo1{$id}'>
                                                          <input type='checkbox' id='studyo1{$id}' name='studyoListesiedit[]' value='{$id}' {$checked} onclick='updateTags(this)' />
                                                          {$country_name}
                                                        </label>";
                                              }
                                              ?>
                                    </div>
                                </div>
                            </div> -->

                            <!-- ülke -->
                            <div class="form-group">
                                <label>Ülke</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control" placeholder="Seçilen stüdyolar"
                                        value="<?php echo $filmler2['ulkeler'] ?>" readonly
                                        onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
                                              $ulkeler = explode(", ", $filmler2['ulkeler']); // Seçili stüdyoları al
                                              foreach ($ulkeListesi as $ulke) {
                                                  $id = htmlspecialchars($ulke['id']);
                                                  $country_name = htmlspecialchars($ulke['country_name']);
                                                  $checked = in_array($country_name, $ulkeler) ? 'checked' : ''; // Seçili ise 'checked' ekle
                                                  echo "<label for='ulkee1{$id}'>
                                                          <input type='checkbox' id='ulkee1{$id}' name='ulkeListesiedit[]' value='{$id}' {$checked} onclick='updateTags(this)' />
                                                          {$country_name}
                                                        </label>";
                                              }
                                              ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Dizi Türü -->
                            <div class="form-group">
                                <label>Dizi Türü</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control" placeholder="Seçilen film türleri"
                                        value="<?php echo $filmler2['filmturleri'] ?>" readonly
                                        onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
                                              $filmturleri = explode(", ", $filmler2['filmturleri']); // Seçili stüdyoları al
                                              foreach ($filmturuListesi as $filmturuu) {
                                                  $id = htmlspecialchars($filmturuu['idfilm']);
                                                  $country_name = htmlspecialchars($filmturuu['filmturu']);
                                                  $checked = in_array($country_name, $filmturleri) ? 'checked' : ''; // Seçili ise 'checked' ekle
                                                  echo "<label for='filmturuu1{$id}'>
                                                          <input type='checkbox' id='filmturuu1{$id}' name='filmturuListesiedit[]' value='{$id}' {$checked} onclick='updateTags(this)' />
                                                          {$country_name}
                                                        </label>";
                                              }
                                              ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <!-- Yönetmen -->
                            <div class="form-group">
                                <label>Yönetmen</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control" placeholder="Seçilen yönetmen"
                                        value="<?php echo htmlspecialchars(implode(', ', $yonetmenler)); ?>" readonly
                                        onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
            // Yönetmenler dizisini kontrol ediyoruz

            // Veritabanından gelen oyuncuların üzerinden geçiyoruz
            foreach ($veriler as $row) {
                $id = htmlspecialchars($row['idoyuncu']);
                $oyuncuad = htmlspecialchars($row['adsoyad']);
                $istediginiz_sayi = 34; // Örnek bir değer, gerçek uygulamanızda güncelleyin
                $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';

                // Oyuncunun kategorisini kontrol et
                if (preg_match($pattern, $row['kategori_idler'])) {
                    // Eğer oyuncu adı yönetmenler dizisinde bulunuyorsa, checkbox'ı işaretle
                    $checked = in_array($oyuncuad, $yonetmenler) ? 'checked' : '';
                    
                    // Checkbox'ı oluştur
                    echo "<label for='yonetmenn1{$id}'><input type='checkbox' id='yonetmenn1{$id}' name='yonetmenListesiedit[]' value='{$id}' onclick='updateTags(this)' {$checked}/>{$oyuncuad}</label>";
                }
            }
            ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Senaryo -->
                            <div class="form-group">
                                <label>Senaryo</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control" placeholder="Seçilen yönetmen"
                                        value="<?php echo htmlspecialchars(implode(', ', $senaryolar)); ?>" readonly
                                        onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
            // Yönetmenler dizisini kontrol ediyoruz

            // Veritabanından gelen oyuncuların üzerinden geçiyoruz
            foreach ($veriler as $row) {
                $id = htmlspecialchars($row['idoyuncu']);
                $oyuncuad = htmlspecialchars($row['adsoyad']);
                $istediginiz_sayi = 38; // Örnek bir değer, gerçek uygulamanızda güncelleyin
                $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';

                // Oyuncunun kategorisini kontrol et
                if (preg_match($pattern, $row['kategori_idler'])) {
                    // Eğer oyuncu adı yönetmenler dizisinde bulunuyorsa, checkbox'ı işaretle
                    $checked = in_array($oyuncuad, $senaryolar) ? 'checked' : '';
                    
                    // Checkbox'ı oluştur
                    echo "<label for='senaryoo1{$id}'><input type='checkbox' id='senaryoo1{$id}' name='senaryoListesiedit[]' value='{$id}' onclick='updateTags(this)' {$checked}/>{$oyuncuad}</label>";
                }
            }
            ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Görüntü Yönetmeni 
                            <div class="form-group">
                                <label>Görüntü Yönetmeni</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control"
                                        value="<?php echo htmlspecialchars(implode(', ', $GörüntüYönetmeni)); ?>"
                                        placeholder="Seçilen görüntü yönetmeni" readonly onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
            // Yönetmenler dizisini kontrol ediyoruz

            // Veritabanından gelen oyuncuların üzerinden geçiyoruz
            foreach ($veriler as $row) {
                $id = htmlspecialchars($row['idoyuncu']);
                $oyuncuad = htmlspecialchars($row['adsoyad']);
                $istediginiz_sayi = 35; // Örnek bir değer, gerçek uygulamanızda güncelleyin
                $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';

                // Oyuncunun kategorisini kontrol et
                if (preg_match($pattern, $row['kategori_idler'])) {
                    // Eğer oyuncu adı yönetmenler dizisinde bulunuyorsa, checkbox'ı işaretle
                    $checked = in_array($oyuncuad, $GörüntüYönetmeni) ? 'checked' : '';
                    
                    // Checkbox'ı oluştur
                    echo "<label for='goryonetmen{$id}'><input type='checkbox' id='goryonetmen{$id}' name='goryonetmenListesiedit[]' value='{$id}' onclick='updateTags(this)' {$checked}/>{$oyuncuad}</label>";
                }
            }
            ?>
                                    </div>
                                </div>
                            </div> -->
                            <!-- Kurgu -->
                            <div class="form-group">
                                <label>Kurgu</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control"
                                        value="<?php echo htmlspecialchars(implode(', ', $Kurgu)); ?>"
                                        placeholder="Seçilen film türleri burada görünecek..." readonly
                                        onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
            // Yönetmenler dizisini kontrol ediyoruz

            // Veritabanından gelen oyuncuların üzerinden geçiyoruz
            foreach ($veriler as $row) {
                $id = htmlspecialchars($row['idoyuncu']);
                $oyuncuad = htmlspecialchars($row['adsoyad']);
                $istediginiz_sayi = 37; // Örnek bir değer, gerçek uygulamanızda güncelleyin
                $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';

                // Oyuncunun kategorisini kontrol et
                if (preg_match($pattern, $row['kategori_idler'])) {
                    // Eğer oyuncu adı yönetmenler dizisinde bulunuyorsa, checkbox'ı işaretle
                    $checked = in_array($oyuncuad, $Kurgu) ? 'checked' : '';
                    
                    // Checkbox'ı oluştur
                    echo "<label for='kurguu1{$id}'><input type='checkbox' id='kurguu1{$id}' name='kurguListesiedit[]' value='{$id}' onclick='updateTags(this)' {$checked}/>{$oyuncuad}</label>";
                }
            }
            ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Müzik -->
                            <div class="form-group">
                                <label>Müzik</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control"
                                        value="<?php echo htmlspecialchars(implode(', ', $Müzik)); ?>"
                                        placeholder="Seçilen film türleri burada görünecek..." readonly
                                        onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
            // Yönetmenler dizisini kontrol ediyoruz

            // Veritabanından gelen oyuncuların üzerinden geçiyoruz
            foreach ($veriler as $row) {
                $id = htmlspecialchars($row['idoyuncu']);
                $oyuncuad = htmlspecialchars($row['adsoyad']);
                $istediginiz_sayi = 36; // Örnek bir değer, gerçek uygulamanızda güncelleyin
                $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';

                // Oyuncunun kategorisini kontrol et
                if (preg_match($pattern, $row['kategori_idler'])) {
                    // Eğer oyuncu adı yönetmenler dizisinde bulunuyorsa, checkbox'ı işaretle
                    $checked = in_array($oyuncuad, $Müzik) ? 'checked' : '';
                    
                    // Checkbox'ı oluştur
                    echo "<label for='muzikk1{$id}'><input type='checkbox' id='muzikk1{$id}' name='muzikListesiedit[]' value='{$id}' onclick='updateTags(this)' {$checked}/>{$oyuncuad}</label>";
                }
            }
            ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Dizi Oyuncuları -->
                            <div class="form-group">
                                <label>Dizi Oyuncuları</label>
                                <div class="selected-tags">
                                    <input type="text" class="tagInput form-control"
                                        value="<?php echo htmlspecialchars(implode(', ', $Oyuncu)); ?>"
                                        placeholder="Seçilen film oyuncuları" readonly onclick="toggleDropdown(this)">
                                </div>
                                <div class="multiselect">
                                    <div class="checkboxes">
                                        <input type="text" class="searchBox" placeholder="Ara..."
                                            onkeyup="filterFunction(this)">
                                        <?php
                                        // Yönetmenler dizisini kontrol ediyoruz
                                    
                                        // Veritabanından gelen oyuncuların üzerinden geçiyoruz
                                        foreach ($veriler as $row) {
                                            $id = htmlspecialchars($row['idoyuncu']);
                                            $oyuncuad = htmlspecialchars($row['adsoyad']);
                                            $istediginiz_sayi = 29; // Örnek bir değer, gerçek uygulamanızda güncelleyin
                                            $pattern = '/\b' . preg_quote($istediginiz_sayi, '/') . '\b/';
                                        
                                            // Oyuncunun kategorisini kontrol et
                                            if (preg_match($pattern, $row['kategori_idler'])) {
                                                // Eğer oyuncu adı yönetmenler dizisinde bulunuyorsa, checkbox'ı işaretle
                                                $checked = in_array($oyuncuad, $Oyuncu) ? 'checked' : '';
                                                
                                                // Checkbox'ı oluştur
                                                echo "<label for='oyuncuu1{$id}'><input type='checkbox' id='oyuncuu1{$id}' name='oyuncuListesiedit[]' value='{$id}' onclick='updateTags(this)' {$checked}/>{$oyuncuad}</label>";
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Film Aciklamasi -->
                        <div class="col-12">
                            <div class="form-group">
                                <label for="filmKonu">Dizinin Konusu</label>
                                <textarea class="form-control textarea" rows="6" name="filmkonu"
                                    id="filmKonu"><?php echo $filmler2['film_konu']  ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center custombg1 mt-5 mb-0">
                        <h2>Afiş Resimi</h2>
                    </div>

                    <div class="row bg-white border bt-0 p-3 m-0">
                        <div class="col-md-3 mb-4">
                            <div class="card bgdark">
                                <img class="card-img-top" src="../kapakfoto/<?php echo $filmler2['kapak_resmi']  ?>"
                                    alt="">
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="multiple-uploader" id="single-uploader-dizi-edit">
                                <div class="mup-msg">
                                    <span class="mup-main-msg">Kapak Resmi Yüklemek için
                                        Tıklayınız.</span>
                                    <span class="mup-msg" id="max-upload-number">Sadece 1 Kapak
                                        Fotoğrafı Yükleyiniz.</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex justify-content-between align-items-center custombg1 mt-5 mb-0">
                        <h2>Galeri Resimleri</h2>
                    </div>

                    <div class="row bg-white border bt-0 p-3 m-0">
                        <?php
                                // $filmler2['resimler'] dizisindeki resimleri ayırın
                                $resimler = explode(', ', $filmler2['resimler']); // Resimler virgülle ayrılmış olabilir

                                // Her bir resmi HTML'deki yapıya yerleştirmek için döngü kullanın
                                foreach ($resimler as $resim) {
                            ?>
                        <div class="col-md-3 mb-4">
                            <div class="card bgdark">
                                <img class="card-img-top" src="../galeri/<?php echo htmlspecialchars($resim); ?>"
                                    alt="">
                            </div>
                        </div>
                        <?php
                                }
                            ?>

                        <div class="col-md-3 mb-4">
                            <div class="multiple-uploader" id="multiple-uploader-galeridizi">
                                <div class="mup-msg">
                                    <span class="mup-main-msg">Dizi Galerisine Fotoğraf Eklemek için
                                        Tıklayınız.</span>
                                    <span class="mup-msg" id="max-upload-number">En Az 3 Fotoğraf
                                        Yükleyiniz.</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-lg">Kaydet</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    </div>
    </div>

    <?php
function formatDate($dateString) {
    // Ay isimlerini tanımla
    $months = [
        "Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran",
        "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"
    ];

    // Tarih parçalarını ayır
    $dateParts = explode("-", $dateString);
    $year = $dateParts[0];
    $month = (int)$dateParts[1] - 1; // Aylar 0-11 arasında indekslenir
    $day = (int)$dateParts[2];

    // Formatlanmış tarihi döndür
    return $day . ' ' . $months[$month] . ' ' . $year;
}

function formatDateTime($dateTimeString) {
    // Ay isimlerini tanımla
    $months = [
        "Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran",
        "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"
    ];

    // Tarih ve saati ayır
    $dateTimeParts = explode(" ", $dateTimeString);
    $dateParts = explode("-", $dateTimeParts[0]);
    $timeParts = explode(":", $dateTimeParts[1]);

    $year = $dateParts[0];
    $month = (int)$dateParts[1] - 1; // Aylar 0-11 arasında indekslenir
    $day = (int)$dateParts[2];

    $hour = (int)$timeParts[0];
    $minute = (int)$timeParts[1];

    // Formatlanmış tarihi ve saati döndür
    return $day . ' ' . $months[$month] . ' ' . $year . ' ' . sprintf('%02d', $hour) . ':' . sprintf('%02d', $minute);
}

// Kullanım örneği:

?>




    <script src="js/controller.js"></script>

    <script>
    let kapakTotalFiles = 0;
    let galeriTotalFiles = 0;


    function validateForm(event) {
        // Form öğelerini al
        const filmadi = document.querySelector('input[name="filmadi"]');
        const vizyonTarihi = document.querySelector('input[name="vizyonTarihi"]');
        const dagitimListesi = document.querySelectorAll('input[name="dagitimListesi[]"]:checked');
        const studyoListesi = document.querySelectorAll('input[name="studyoListesi[]"]:checked');
        const ulkeListesi = document.querySelectorAll('input[name="ulkeListesi[]"]:checked');
        const filmturuListesi = document.querySelectorAll('input[name="filmturuListesi[]"]:checked');

        // Yeni alanlar için doğrulamalar
        const yonetmenListesi = document.querySelectorAll('input[name="yonetmenListesi[]"]:checked');
        const senaryoListesi = document.querySelectorAll('input[name="senaryoListesi[]"]:checked');
        const gyonetmeniListesi = document.querySelectorAll('input[name="gyonetmeniListesi[]"]:checked');
        const kurguListesi = document.querySelectorAll('input[name="kurguListesi[]"]:checked');
        const muzikListesi = document.querySelectorAll('input[name="müzikListesi[]"]:checked');
        const oyuncuListesi = document.querySelectorAll('input[name="oyuncuListesi[]"]:checked');
        const kapakfoto = document.querySelectorAll('input[name="kapakfotograf[]"]');
        const galeri = document.querySelectorAll('input[name="galerifotograf[]"]');



        // Boş alanları kontrol et
        if (!filmadi.value.trim()) {
            alert('Film adı boş olamaz!');
            filmadi.focus();
            event.preventDefault(); // Form gönderimini durdur
            return false;
        }

        if (!vizyonTarihi.value) {
            alert('Vizyon tarihi boş olamaz!');
            vizyonTarihi.focus();
            event.preventDefault();
            return false;
        }

        if (dagitimListesi.length === 0) {
            alert('En az bir dağıtım şirketi seçilmelidir!');
            event.preventDefault();
            return false;
        }

        if (studyoListesi.length === 0) {
            alert('En az bir stüdyo seçilmelidir!');
            event.preventDefault();
            return false;
        }

        if (ulkeListesi.length === 0) {
            alert('En az bir ülke seçilmelidir!');
            event.preventDefault();
            return false;
        }

        if (filmturuListesi.length === 0) {
            alert('En az bir film türü seçilmelidir!');
            event.preventDefault();
            return false;
        }

        // Yeni alanlar için kontroller
        if (yonetmenListesi.length === 0) {
            alert('En az bir yönetmen seçilmelidir!');
            event.preventDefault();
            return false;
        }

        if (senaryoListesi.length === 0) {
            alert('En az bir senarist seçilmelidir!');
            event.preventDefault();
            return false;
        }

        if (gyonetmeniListesi.length === 0) {
            alert('En az bir görüntü yönetmeni seçilmelidir!');
            event.preventDefault();
            return false;
        }

        if (kurguListesi.length === 0) {
            alert('En az bir kurgucu seçilmelidir!');
            event.preventDefault();
            return false;
        }

        if (muzikListesi.length === 0) {
            alert('En az bir müzik bestecisi seçilmelidir!');
            event.preventDefault();
            return false;
        }

        if (oyuncuListesi.length === 0) {
            alert('En az bir oyuncu seçilmelidir!');
            event.preventDefault();
            return false;
        }
        if (kapakTotalFiles === 0) { // length değil, doğrudan sayıyı kontrol edin
            alert('En az bir kapak fotoğrafı seçilmelidir!');
            event.preventDefault();
            return false;
        }

        if (galeriTotalFiles < 3) { // 3'ten az olduğunda uyarı verilir
            alert('En az 3 adet film galeri fotoğrafı seçilmelidir!');
            event.preventDefault();
            return false;
        }


        // Eğer tüm doğrulamalar geçerse, formun gönderilmesine izin ver
        return true;
    }

    // Formun submit olayına validateForm fonksiyonunu bağla
    document.getElementById('filmForm').addEventListener('submit', validateForm);
    </script>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/table.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@0.9.13/dist/js/bootstrap-multiselect.js"></script>

    <script src="js/multiple-uploader.js"></script>
    <script>
    let multipleUploader = new MultipleUploader('#multiple-uploader').init({
        maxUpload: 20, // maximum number of uploaded images
        minUpload: 3,
        maxSize: 2, // in size in mb
        filesInpName: 'galerifotograf', // input name sent to backend
        formSelector: '#filmForm', // form selector
    });

    let multipleUploader2 = new MultipleUploader('#single-uploader').init({
        maxUpload: 1, // maximum number of uploaded images
        maxSize: 2, // in size in mb
        filesInpName: 'kapakfotograf', // input name sent to backend
        formSelector: '#filmForm', // form selector
    });



    let multipleUploader3 = new MultipleUploader('#single-uploader-haber').init({
        maxUpload: 1, // maximum number of uploaded images
        maxSize: 2, // in size in mb
        filesInpName: 'kapakfoto', // input name sent to backend
        formSelector: '#formHaberler', // form selector
    });


    let multipleUploader4 = new MultipleUploader('#single-uploader-film-edit').init({
        maxUpload: 1, // maximum number of uploaded images
        maxSize: 2, // in size in mb
        filesInpName: 'filmkapakedit', // input name sent to backend
        formSelector: '#filmdetay', // form selector
    });

    let multipleUploader5 = new MultipleUploader('#multiple-uploader-galerifilm').init({
        maxUpload: 20, // maximum number of uploaded images
        maxSize: 2, // in size in mb
        filesInpName: 'filmgaleriedit', // input name sent to backend
        formSelector: '#filmdetay', // form selector
    });


    let multipleUploader6 = new MultipleUploader('#single-uploader-dizi').init({
        maxUpload: 1, // maximum number of uploaded images
        maxSize: 2, // in size in mb
        filesInpName: 'kapakfotograf', // input name sent to backend
        formSelector: '#diziForm', // form selector
    });

    let multipleUploader7 = new MultipleUploader('#multiple-uploader-dizi').init({
        maxUpload: 20, // maximum number of uploaded images
        maxSize: 2, // in size in mb
        filesInpName: 'galerifotograf', // input name sent to backend
        formSelector: '#diziForm', // form selector
    });



    let multipleUploader8 = new MultipleUploader('#single-uploader-dizi-edit').init({
        maxUpload: 1, // maximum number of uploaded images
        maxSize: 2, // in size in mb
        filesInpName: 'filmkapakedit', // input name sent to backend
        formSelector: '#dizidetay', // form selector
    });

    let multipleUploader9 = new MultipleUploader('#multiple-uploader-galeridizi').init({
        maxUpload: 20, // maximum number of uploaded images
        maxSize: 2, // in size in mb
        filesInpName: 'filmgaleriedit', // input name sent to backend
        formSelector: '#dizidetay', // form selector
    });
    </script>


    <script>
    $(document).ready(function() {
        $('#minimal-multiselect').multiselect();
    });
    </script>
    <script>
    function showContent(contentId, id, statu) {

        if (typeof id === 'undefined') {
            localStorage.setItem("uri", contentId);

            var contents = document.querySelectorAll('.content');
            contents.forEach(function(content) {
                content.style.display = 'none';
            });

            // Remove active class from all list items
            var tabs = document.querySelectorAll('ul li');
            tabs.forEach(function(tab) {
                tab.classList.remove('active');
            });

            // Show the selected content div
            document.getElementById(contentId).style.display = 'block';
        } else {
            localStorage.setItem("uri", contentId);
            const url = new URL(window.location.href);
            url.searchParams.delete('filmid');
            url.searchParams.delete('diziid');
            url.searchParams.set(statu + 'id', id);
            window.history.pushState({}, '', url);
            location.reload();



        }

    }



    $(document).ready(function() {
        $('#multiple-checkboxes').multiselect({
            includeSelectAllOption: true,
        });
    });

    function direct() {
        window.location.href = 'logout';
    }
    </script>

    <script>
    function getId(id) {
        document.getElementById("kategoriid").value = id;
    }

    function getId1(id) {
        document.getElementById("oyuncuedit").value = id;
    }

    const storedValue = localStorage.getItem('uri');

    if (storedValue) {
        showContent(storedValue);
    }
    </script>




    <script>
    function toggleDropdown(element) {
        var dropdown = element.parentElement.nextElementSibling.querySelector(".checkboxes");
        if (dropdown.style.display === "block") {
            dropdown.style.display = "none";
        } else {
            closeAllDropdowns(); // Diğer dropdown'ları kapat
            dropdown.style.display = "block";
        }
        document.addEventListener('click', outsideClickListener);
    }

    function closeAllDropdowns() {
        var checkboxes = document.querySelectorAll(".checkboxes");
        checkboxes.forEach(function(checkbox) {
            checkbox.style.display = "none";
        });
    }

    function outsideClickListener(event) {
        if (!event.target.closest('.form-group')) {
            closeAllDropdowns();
            document.removeEventListener('click', outsideClickListener);
        }
    }

    function filterFunction(input) {
        var filter = input.value.toLowerCase();
        var checkboxes = input.parentElement.querySelectorAll("label");
        checkboxes.forEach(function(label) {
            var text = label.textContent || label.innerText;
            if (text.toLowerCase().indexOf(filter) > -1) {
                label.style.display = "";
            } else {
                label.style.display = "none";
            }
        });
    }

    function updateTags(checkbox) {
        var tagInput = checkbox.closest('.multiselect').previousElementSibling.querySelector('.tagInput');
        var selectedTags = [];
        var checkboxes = checkbox.closest('.checkboxes').querySelectorAll("input[type='checkbox']");

        checkboxes.forEach(function(box) {
            if (box.checked) {
                selectedTags.push(box.parentElement.textContent.trim());
            }
        });

        tagInput.value = selectedTags.join(", ");
    }






    const kapakfotoInputs = document.querySelectorAll('input[name="kapakfotograf[]"]');
    const galeriInputs = document.querySelectorAll('input[name="galerifotograf[]"]');


    // Kapak fotoğrafı inputları için değişim olayı dinleyici
    kapakfotoInputs.forEach(input => {
        input.addEventListener('change', () => {

            // Her kapak fotoğrafı inputundan yüklenen dosya sayısını topla
            kapakfotoInputs.forEach(input => {
                kapakTotalFiles += input.files.length;
            });

            console.log(`Kapak Fotoğrafı Yüklenen Dosya Sayısı: ${kapakTotalFiles}`); // Konsola yazdır
        });
    });

    // Galeri inputları için değişim olayı dinleyici
    galeriInputs.forEach(input => {
        input.addEventListener('change', () => {


            // Her galeri inputundan yüklenen dosya sayısını topla
            galeriInputs.forEach(input => {
                galeriTotalFiles += input.files.length;
            });

            console.log(`Galeri Yüklenen Dosya Sayısı: ${galeriTotalFiles}`); // Konsola yazdır
        });
    });
    </script>



</body>

</html>