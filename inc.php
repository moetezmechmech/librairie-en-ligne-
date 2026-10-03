<?php
session_start();require 'config.php';
$pdo=new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
if(isset($_GET['lang'])&&in_array($_GET['lang'],['fr','ar']))$_SESSION['lang']=$_GET['lang'];
$L=$_SESSION['lang']??'fr';
$T=['fr'=>['catalogue'=>'Catalogue','listes'=>'Listes scolaires','panier'=>'Panier','rech'=>'Rechercher un livre ou une fourniture','tous'=>'Tout','livre'=>'Livres','fourniture'=>'Fournitures','ajouter'=>'Ajouter','vide'=>'Votre panier est vide.','ecole'=>'École','niveau'=>'Niveau','voir'=>'Voir la liste','tout'=>'Tout ajouter au panier','total'=>'Total','nom'=>'Nom complet','tel'=>'Téléphone','mode'=>'Réception','liv'=>'Livraison','ret'=>'Retrait en magasin','adr'=>'Adresse de livraison','cmd'=>'Valider la commande','ok'=>'Commande enregistrée. Numéro : ','stock'=>'Rupture','maj'=>'Mettre à jour','h1'=>'Tout pour la rentrée, à Nabeul','sub'=>'Livres scolaires et fournitures, par école et par niveau.'],
'ar'=>['catalogue'=>'الكتالوج','listes'=>'القوائم المدرسية','panier'=>'السلة','rech'=>'ابحث عن كتاب أو أداة','tous'=>'الكل','livre'=>'كتب','fourniture'=>'أدوات','ajouter'=>'أضف','vide'=>'سلتك فارغة.','ecole'=>'المدرسة','niveau'=>'المستوى','voir'=>'عرض القائمة','tout'=>'أضف الكل إلى السلة','total'=>'المجموع','nom'=>'الاسم الكامل','tel'=>'الهاتف','mode'=>'الاستلام','liv'=>'توصيل','ret'=>'استلام من المحل','adr'=>'عنوان التوصيل','cmd'=>'تأكيد الطلب','ok'=>'تم تسجيل الطلب. الرقم: ','stock'=>'غير متوفر','maj'=>'تحديث','h1'=>'كل ما تحتاجه للعودة المدرسية في نابل','sub'=>'كتب وأدوات مدرسية حسب المدرسة والمستوى.']];
function t($k){global $T,$L;return $T[$L][$k]??$k;}
function h($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function col($r,$f){global $L;return h(($r[$f.'_'.$L]??'')?:$r[$f.'_fr']);}
function m($x){return number_format((float)$x,3,',',' ').' DT';}
function head($title){global $L;$d=$L=='ar'?'rtl':'ltr';$n=array_sum($_SESSION['cart']??[]);$o=$L=='fr'?'ar':'fr';
echo "<!doctype html><html lang='$L' dir='$d'><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>".h($title)." - Scolaris</title><link rel='icon' href='logo.png'><link rel='stylesheet' href='style.css'><header><a class='logo' href='index.php'><img src='logo.png' alt='Scolaris Office City'></a><nav><a href='index.php'>".t('catalogue')."</a><a href='liste.php'>".t('listes')."</a><a href='panier.php'>".t('panier')." ($n)</a><a href='?lang=$o'>".($L=='fr'?'عربي':'FR')."</a></nav></header><main>";}
function foot(){echo "</main><footer>Scolaris Office City · Rue Ali Belhouane, Nabeul</footer></html>";}
