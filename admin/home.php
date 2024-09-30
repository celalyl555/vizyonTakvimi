<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index');
	exit;
}

include('conn.php');
$stmt = $con->prepare('SELECT * FROM kategori');
$stmt->execute();
$kategoriListesi = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $con->prepare('SELECT * FROM filmturleri');
$stmt->execute();
$filmturuListesi = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $con->prepare('SELECT * FROM ulke');
$stmt->execute();
$ulkeListesi = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $con->prepare('SELECT * FROM stüdyo');
$stmt->execute();
$studyoListesi = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $con->prepare('SELECT * FROM sinemadagitim');
$stmt->execute();
$dagitimListesi = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "
    SELECT o.idoyuncu, o.adsoyad, o.dogum, o.olum, o.resimyol, 
    GROUP_CONCAT(k.kategoriAd SEPARATOR ', ') AS roller,
    GROUP_CONCAT(k.idKategori SEPARATOR ', ') AS kategori_idler
    FROM oyuncular o
    LEFT JOIN kayit_kategori kc ON o.idoyuncu = kc.kayit_id
    LEFT JOIN kategori k ON kc.kategori_id = k.idKategori
    GROUP BY o.idoyuncu
";

$stmt = $con->query($sql);
$veriler = $stmt->fetchAll(PDO::FETCH_ASSOC);
// FİLMLER VERİ TABANI
$sql = "SELECT f.film_adi, f.id, f.vizyon_tarihi, f.kapak_resmi, GROUP_CONCAT(ft.filmturu SEPARATOR ', ') AS filmturleri
FROM filmler f
JOIN film_filmturu fft ON f.id = fft.film_id
JOIN filmturleri ft ON fft.filmturu_id = ft.idfilm
GROUP BY f.id";

$stmt = $con->query($sql);
$filmler = $stmt->fetchAll(PDO::FETCH_ASSOC);




?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Home Page</title>
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
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

</head>




<body class="loggedin">


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
                    <button onclick="showContent('content5')"><span
                            class="fa fa-newspaper mr-3"></span>HABERLER</button>
                </li>
                <li>
                    <button onclick="showContent('content6')"><span class="fa fa-bars mr-3"></span>MENU</button>
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
                                            <H5>OYUNCULAR</H5>
                                        </div>
                                        <div class="col-sm-6">
                                            <a href="#addEmployeeModal"
                                                class="btn btn-success d-flex align-items-center" data-toggle="modal">
                                                <i class="material-icons mr-2">&#xE147;</i> <span>Oyuncu Ekle</span>
                                            </a>

                                        </div>

                                    </div>
                                </div>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="align-middle text-center">Fotoğraf</th>
                                            <th class="align-middle text-center">Ad - Soyad</th>
                                            <th class="align-middle text-center">Doğum Tarihi</th>
                                            <th class="align-middle text-center">Ölüm Tarihi</th>
                                            <th class="align-middle text-center">Roller</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($veriler as $row): ?>
                                        <tr>
                                            <td class="align-middle text-center"><img
                                                    src="../foto/<?php echo htmlspecialchars($row['resimyol']); ?>"
                                                    class="rounded img-thumbnail" alt="Fotoğraf"
                                                    style="width: 50px; height: 50px;"></td>
                                            <td class="align-middle text-center">
                                                <?php echo htmlspecialchars($row['adsoyad']); ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <?php echo formatDate($row['dogum']); ?></td>
                                            <td class="align-middle text-center">
                                                <?php echo !empty($row['olum']) ? formatDate($row['olum']) : '-'; ?>
                                            </td>

                                            <td class="align-middle text-center">
                                                <?php echo !empty($row['roller']) ? htmlspecialchars($row['roller']) : '-'; ?>
                                            </td>

                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="#editEmployeeModal" class="edit"
                                                    onclick="getId1('<?php echo $row['idoyuncu']; ?>');"
                                                    data-toggle="modal"><i class="material-icons" data-toggle="tooltip"
                                                        title="Edit">&#xE254;</i></a>
                                                <a href="#deleteEmployeeModal text-center" class="delete"
                                                    onclick="getId('<?php echo $row['idoyuncu']; ?>');"
                                                    data-toggle="modal"><i class="material-icons" data-toggle="tooltip"
                                                        title="Delete">&#xE872;</i></a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>

                                    </tbody>
                                </table>
                                <div class="clearfix">
                                    <div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
                                    <ul class="pagination">
                                        <li class="page-item disabled"><a href="#">Previous</a></li>
                                        <li class="page-item"><a href="#" class="page-link">1</a></li>
                                        <li class="page-item"><a href="#" class="page-link">2</a></li>
                                        <li class="page-item active"><a href="#" class="page-link">3</a></li>
                                        <li class="page-item"><a href="#" class="page-link">4</a></li>
                                        <li class="page-item"><a href="#" class="page-link">5</a></li>
                                        <li class="page-item"><a href="#" class="page-link">Next</a></li>
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
                                                <input type="text" name="adSoyad" class="form-control" >
                                            </div>
                                            <div class="form-group">
                                                <label>Doğum Tarihi</label>
                                                <input type="date" name="dogumTarihi" class="form-control" >
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
                                                <select name="kategori[]" id="minimal-multiselect" multiple="multiple">
                                                    <?php
                    foreach ($kategoriListesi as $kategori) {
                        echo '<option value="' . $kategori['idKategori'] . '">' . htmlspecialchars($kategori['kategoriAd']) . '</option>';
                    }
                ?>
                                                </select>
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
                                                    <h5>KATEGORİLER</h5>
                                                </div>
                                                <div class="col-sm-6">
                                                    <a href="#addEmployeeModalKategori"
                                                        class="btn btn-success d-flex align-items-center"
                                                        data-toggle="modal">
                                                        <i class="material-icons mr-2">&#xE147;</i> <span>Kategori
                                                            Ekle</span>
                                                    </a>

                                                </div>

                                            </div>
                                        </div>
                                        <table class="table table-striped table-hover">
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
                                            <div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
                                            <ul class="pagination">
                                                <li class="page-item disabled"><a href="#">Previous</a></li>
                                                <li class="page-item"><a href="#" class="page-link">1</a></li>
                                                <li class="page-item"><a href="#" class="page-link">2</a></li>
                                                <li class="page-item active"><a href="#" class="page-link">3</a></li>
                                                <li class="page-item"><a href="#" class="page-link">4</a></li>
                                                <li class="page-item"><a href="#" class="page-link">5</a></li>
                                                <li class="page-item"><a href="#" class="page-link">Next</a></li>
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
                                            <H5>FİLMLER</H5>
                                        </div>
                                        <div class="col-sm-6">
                                            <a href="#addEmployeeModalfilmm"
                                                class="btn btn-success d-flex align-items-center" data-toggle="modal">
                                                <i class="material-icons mr-2">&#xE147;</i> <span>Film Ekle</span>
                                            </a>

                                        </div>

                                    </div>
                                </div>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Film Afişi</th>
                                            <th>Film Adı</th>
                                            <th>Vizyon Tarihi</th>
                                            <th>Film Türü</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($filmler as $row): ?>
                                        <tr>
                                            <td><img src="../kapakfoto/<?php echo htmlspecialchars($row['kapak_resmi']); ?>"
                                                    class="rounded img-thumbnail" alt="Fotoğraf"
                                                    style="width: 50px; height: 50px;"></td>
                                            <td class="align-middle"><?php echo htmlspecialchars($row['film_adi']); ?>
                                            </td>
                                            <td class="align-middle"><?php echo formatDate($row['vizyon_tarihi']); ?></td>
                                            <td class="align-middle">
                                                <?php echo !empty($row['filmturleri']) ? htmlspecialchars($row['filmturleri']) : '-'; ?>
                                            </td>

                                          

                                            </td>
                                            <td class="align-middle">
                                              
                                                <a href="#deleteEmployeeModalfilmler" class="delete"
                                                    onclick="getId('<?php echo $row['id']; ?>');"
                                                    data-toggle="modal"><i class="material-icons" data-toggle="tooltip"
                                                        title="Delete">&#xE872;</i></a>

                                            </td>
                                            <td class="align-middle">
                                               <button onclick="showContent('content6')" class="btn btn-primary rounded-pill"><i class="material-icons mr-2">chevron_right</i></button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        
                                    </tbody>
                                </table>
                                <div class="clearfix">
                                    <div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
                                    <ul class="pagination">
                                        <li class="page-item disabled"><a href="#">Previous</a></li>
                                        <li class="page-item"><a href="#" class="page-link">1</a></li>
                                        <li class="page-item"><a href="#" class="page-link">2</a></li>
                                        <li class="page-item active"><a href="#" class="page-link">3</a></li>
                                        <li class="page-item"><a href="#" class="page-link">4</a></li>
                                        <li class="page-item"><a href="#" class="page-link">5</a></li>
                                        <li class="page-item"><a href="#" class="page-link">Next</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- ADD Modal HTML -->
                        <div id="addEmployeeModalfilmm" class="modal fade">
                            <div class="modal-dialog modal-xl">
                                <!-- Modal genişliğini artırmak için modal-xl kullanıldı -->
                                <div class="modal-content">
                                    <form id="filmForm"  method="post" enctype="multipart/form-data">
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
                                                        <input type="text" name="filmadi" class="form-control"
                                                             >
                                                    </div>
                                                    <!-- Vizyon Tarihi -->
                                                    <div class="form-group">
                                                        <label>Vizyon Tarihi</label>
                                                        <input type="date" name="vizyonTarihi" class="form-control"
                                                             >
                                                    </div>
                                                    <!-- Sinema Dağıtım -->
                                                    <div class="form-group">
                                                        <label>Sinema Dağıtım</label>
                                                        <div class="selected-tags">
                                                            <input type="text"  class="tagInput"
                                                                placeholder="Seçilen dağıtım şirketleri" readonly
                                                                onclick="toggleDropdown(this)" >
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..."
                                                                    onkeyup="filterFunction(this)">
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
                                                            <input type="text"   class="tagInput"
                                                                placeholder="Seçilen stüdyolar" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..."
                                                                    onkeyup="filterFunction(this)">
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
                                                            <input type="text"  class="tagInput"
                                                                placeholder="Seçilen ülkeler" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..."
                                                                    onkeyup="filterFunction(this)">
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
                                                            <input type="text"  class="tagInput"
                                                                placeholder="Seçilen film türleri" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..."
                                                                    onkeyup="filterFunction(this)">
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
                                                            <input type="text"  class="tagInput"
                                                                placeholder="Seçilen yönetmen" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..."
                                                                    onkeyup="filterFunction(this)">
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
                                                            <input type="text" class="tagInput"
                                                                placeholder="Seçilen senaryo yazarı" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text"   class="searchBox"
                                                                    placeholder="Ara..."
                                                                    onkeyup="filterFunction(this)">
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
                                                            <input type="text" class="tagInput"
                                                                placeholder="Seçilen görüntü yönetmeni" readonly
                                                                onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text"   class="searchBox"
                                                                    placeholder="Ara..."
                                                                    onkeyup="filterFunction(this)">
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
                                                            <input type="text" class="tagInput"
                                                                placeholder="Seçilen film türleri burada görünecek..."
                                                                readonly onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text"  class="searchBox"
                                                                    placeholder="Ara..."
                                                                    onkeyup="filterFunction(this)">
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
                                                            <input type="text" class="tagInput"
                                                                placeholder="Seçilen film türleri burada görünecek..."
                                                                readonly onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text"  class="searchBox"
                                                                    placeholder="Ara..."
                                                                    onkeyup="filterFunction(this)">
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
                                                            <input type="text"  class="tagInput"
                                                                placeholder="Seçilen film oyuncuları"
                                                                readonly onclick="toggleDropdown(this)">
                                                        </div>
                                                        <div class="multiselect">
                                                            <div class="checkboxes">
                                                                <input type="text" class="searchBox"
                                                                    placeholder="Ara..."
                                                                    onkeyup="filterFunction(this)">
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


                                                </div> <!-- sağ sol sütun bitiş -->
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
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-4 left-column">
                                    <div class="table-wrapper">
                                        <div class="table-title">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h5>FİLM TÜRLERİ</h5>
                                                </div>
                                                <div class="col-sm-6">
                                                    <a href="#addEmployeeModalfilmturu"
                                                        class="btn btn-success d-flex align-items-center"
                                                        data-toggle="modal">
                                                        <i class="material-icons mr-2">&#xE147;</i> <span>Film Türü
                                                            Ekle</span>
                                                    </a>

                                                </div>

                                            </div>
                                        </div>
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>

                                                    <th>Film Türü</th>
                                                    <th></th>


                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <?php 
                                                   foreach ($filmturuListesi as $filmturu) {
                                                    echo "<td>" . htmlspecialchars($filmturu['filmturu']) . "</td>"; // Her bir kategori adını güvenli bir şekilde göster
                                             
                                                   
                                                   ?>

                                                    <td>

                                                        <a href="#deleteEmployeeModalfilmturu" id="kategoridelete"
                                                            onclick="getId('<?php echo $filmturu['idfilm']; ?>');"
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
                                            <div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
                                            <ul class="pagination">
                                                <li class="page-item disabled"><a href="#">Previous</a></li>
                                                <li class="page-item"><a href="#" class="page-link">1</a></li>
                                                <li class="page-item"><a href="#" class="page-link">2</a></li>
                                                <li class="page-item active"><a href="#" class="page-link">3</a></li>
                                                <li class="page-item"><a href="#" class="page-link">4</a></li>
                                                <li class="page-item"><a href="#" class="page-link">5</a></li>
                                                <li class="page-item"><a href="#" class="page-link">Next</a></li>
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
                                <div class="col-md-4 right-column">
                                    <div class="table-wrapper">
                                        <div class="table-title">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h5>STÜDYO</h5>
                                                </div>
                                                <div class="col-sm-6">
                                                    <a href="#addEmployeeModalstudyo"
                                                        class="btn btn-success d-flex align-items-center"
                                                        data-toggle="modal">
                                                        <i class="material-icons mr-2">&#xE147;</i> <span>Stüdyo
                                                            Ekle</span>
                                                    </a>

                                                </div>

                                            </div>
                                        </div>
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>

                                                    <th>Stüdyo Adı</th>
                                                    <th></th>


                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <?php 
                                                   foreach ($studyoListesi as $studyo) {
                                                    echo "<td>" . htmlspecialchars($studyo['studyoad']) . "</td>"; // Her bir kategori adını güvenli bir şekilde göster
                                             
                                                   
                                                   ?>

                                                    <td>

                                                        <a href="#deleteEmployeeModalstudyo" id="kategoridelete"
                                                            onclick="getId('<?php echo $studyo['id']; ?>');"
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
                                            <div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
                                            <ul class="pagination">
                                                <li class="page-item disabled"><a href="#">Previous</a></li>
                                                <li class="page-item"><a href="#" class="page-link">1</a></li>
                                                <li class="page-item"><a href="#" class="page-link">2</a></li>
                                                <li class="page-item active"><a href="#" class="page-link">3</a></li>
                                                <li class="page-item"><a href="#" class="page-link">4</a></li>
                                                <li class="page-item"><a href="#" class="page-link">5</a></li>
                                                <li class="page-item"><a href="#" class="page-link">Next</a></li>
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
                                                        <input type="text" name="studyo" id="studyo"
                                                            class="form-control" required>
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
                                                    <input type="submit" id="studyoSil" class="btn btn-danger"
                                                        value="Sil">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4 right-column">
                                    <div class="table-wrapper">
                                        <div class="table-title">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h5>SİNEMA DAĞITIM</h5>
                                                </div>
                                                <div class="col-sm-6">
                                                    <a href="#addEmployeeModaldagitim"
                                                        class="btn btn-success d-flex align-items-center"
                                                        data-toggle="modal">
                                                        <i class="material-icons mr-2">&#xE147;</i> <span>Sinema Dağıtım
                                                            Ekle</span>
                                                    </a>

                                                </div>

                                            </div>
                                        </div>
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>

                                                    <th>Sinema Dağıtım</th>
                                                    <th></th>


                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <?php 
                                                   foreach ($dagitimListesi as $dagitim) {
                                                    echo "<td>" . htmlspecialchars($dagitim['dagitimad']) . "</td>"; // Her bir kategori adını güvenli bir şekilde göster
                                             
                                                   
                                                   ?>

                                                    <td>

                                                        <a href="#deleteEmployeeModaldagitim" id="kategoridelete"
                                                            onclick="getId('<?php echo $dagitim['iddagitim']; ?>');"
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
                                            <div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
                                            <ul class="pagination">
                                                <li class="page-item disabled"><a href="#">Previous</a></li>
                                                <li class="page-item"><a href="#" class="page-link">1</a></li>
                                                <li class="page-item"><a href="#" class="page-link">2</a></li>
                                                <li class="page-item active"><a href="#" class="page-link">3</a></li>
                                                <li class="page-item"><a href="#" class="page-link">4</a></li>
                                                <li class="page-item"><a href="#" class="page-link">5</a></li>
                                                <li class="page-item"><a href="#" class="page-link">Next</a></li>
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
                                                        <label>Sinema Dağıtım</label>
                                                        <input type="text" name="dagitim" id="dagitim"
                                                            class="form-control" required>
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
                                                    <input type="submit" id="dagitimSil" class="btn btn-danger"
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
            </div>

            
            <div id="content4" class="content" style="display: none;">DİZİLER</div>
            <div id="content5" class="content" style="display: none;">

        <div class="container d-flex justify-content-center mt-5">
  <div class="col-md-6">
    <h2>Haber Ekle</h2>
    <form>
      <div class="mb-3">
        <label for="haberBaslik" class="form-label">Haber Başlığı</label>
        <input type="text" class="form-control" id="haberBaslik" placeholder="Başlık girin">
      </div>
      
      <div class="mb-3">
        <label for="haberIcerik" class="form-label">Haber İçeriği</label>
        <textarea name="content" id="haberIcerik" rows="10" class="form-control"></textarea>
      </div>
      
      <button type="submit" class="btn btn-primary">Haberi Kaydet</button>
    </form>
  </div>
</div>


  <!-- CKEditor Initialize Script -->
  <script>
  ClassicEditor
    .create(document.querySelector('#haberIcerik'), {
        toolbar: [
            'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', '|', 'undo', 'redo', 'imageUpload'
        ],
        ckfinder: {
            uploadUrl: 'controller/upload.php',  // PHP dosyasına görselleri yükleyecek
        }
    })
    .then(editor => {
        editor.plugins.get('FileRepository').on('fileUploadResponse', (evt, data) => {
            const response = JSON.parse(data.response);
            if (response.error) {
                alert(response.error.message); // Hata mesajını göster
            }
        });
    })
    .catch(error => {
        console.error(error);
    });

  </script>


        </div>

            <div id="content6" class="content pl-5" style="display: none;">
                <form>
                    <div class="form-group">
                        <label for="filmAdi">Film Adı</label>
                        <input type="email" class="form-control" id="filmAdi" placeholder="Varsa Film Adı Burda Yazıcak">
                    </div>
                    <div class="form-group">
                        <label for="vizyonTarihi">Vizyon Tarihi</label>
                        <input type="date" class="form-control" id="vizyonTarihi">
                    </div>
                    <!-- Yönetmen -->
                    <div class="form-group">
                        <label>Yönetmen</label>
                        <div class="selected-tags">
                            <input type="text"  class="tagInput"
                                placeholder="Seçilen yönetmen" readonly
                                onclick="toggleDropdown(this)">
                        </div>
                        <div class="multiselect">
                            <div class="checkboxes">
                                <input type="text" class="searchBox"
                                    placeholder="Ara..."
                                    onkeyup="filterFunction(this)">
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
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </form>
            </div>

            <div id="content7" class="content" style="display: none;">Sign Out content here.</div>

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
    </script>


    <script>
    $(document).ready(function() {
        $('#minimal-multiselect').multiselect();
    });
    </script>
    <script>
    function showContent(contentId) {
        localStorage.setItem("uri", contentId);
        // Hide all content divs
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