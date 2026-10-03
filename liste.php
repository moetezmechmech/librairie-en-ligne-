<?php require 'inc.php';
$e=(int)($_GET['ecole']??0);$n=(int)($_GET['niveau']??0);$lignes=[];
if($e&&$n){$st=$pdo->prepare("SELECT p.*,l.quantite FROM liste_scolaire s JOIN liste_scolaire_ligne l ON l.liste_id=s.id JOIN produit p ON p.id=l.produit_id WHERE s.ecole_id=? AND s.niveau_id=? ORDER BY p.type,p.titre_fr");$st->execute([$e,$n]);$lignes=$st->fetchAll();
 if($_SERVER['REQUEST_METHOD']=='POST'){foreach($lignes as $r)if($r['stock']>0)$_SESSION['cart'][$r['id']]=($_SESSION['cart'][$r['id']]??0)+$r['quantite'];header('Location: panier.php');exit;}}
head(t('listes'));?>
<h1><?=t('listes')?></h1>
<form class="inline"><label><?=t('ecole')?> <select name="ecole"><?php foreach($pdo->query("SELECT * FROM ecole ORDER BY nom") as $r):?><option value="<?=$r['id']?>" <?=$e==$r['id']?'selected':''?>><?=h($r['nom'])?><?php endforeach;?></select></label>
<label><?=t('niveau')?> <select name="niveau"><?php foreach($pdo->query("SELECT * FROM niveau ORDER BY id") as $r):?><option value="<?=$r['id']?>" <?=$n==$r['id']?'selected':''?>><?=col($r,'libelle')?><?php endforeach;?></select></label><button><?=t('voir')?></button></form>
<?php if($lignes):$tot=0;?><table><?php foreach($lignes as $r):$tot+=$r['prix']*$r['quantite'];?><tr><td><?=col($r,'titre')?></td><td>×<?=$r['quantite']?></td><td><?=m($r['prix']*$r['quantite'])?></td></tr><?php endforeach;?>
<tr class="tot"><td colspan="2"><?=t('total')?></td><td><?=m($tot)?></td></tr></table><form method="post"><button><?=t('tout')?></button></form><?php endif;foot();
