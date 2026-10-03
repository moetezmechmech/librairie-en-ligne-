<?php require 'inc.php';
$cart=$_SESSION['cart']??[];$msg='';
if($_SERVER['REQUEST_METHOD']=='POST'){
 if(isset($_POST['q'])){foreach($_POST['q'] as $id=>$qt){$qt=(int)$qt;if($qt>0)$cart[(int)$id]=$qt;else unset($cart[(int)$id]);}$_SESSION['cart']=$cart;header('Location: panier.php');exit;}
 if(isset($_POST['nom'])&&$cart){
  $in=implode(',',array_fill(0,count($cart),'?'));$st=$pdo->prepare("SELECT id,prix FROM produit WHERE id IN($in)");$st->execute(array_keys($cart));$px=array_column($st->fetchAll(),'prix','id');
  $tot=0;foreach($cart as $id=>$qt)$tot+=($px[$id]??0)*$qt;
  $mode=$_POST['mode']=='retrait'?'retrait':'livraison';
  $pdo->beginTransaction();
  $pdo->prepare("INSERT INTO commande(client_nom,client_tel,mode,adresse,total) VALUES(?,?,?,?,?)")->execute([trim($_POST['nom']),trim($_POST['tel']),$mode,trim($_POST['adresse']??''),$tot]);
  $cid=$pdo->lastInsertId();$li=$pdo->prepare("INSERT INTO commande_ligne(commande_id,produit_id,quantite,prix_unitaire) VALUES(?,?,?,?)");
  foreach($cart as $id=>$qt)if(isset($px[$id]))$li->execute([$cid,$id,$qt,$px[$id]]);
  $pdo->commit();$_SESSION['cart']=[];$cart=[];$msg=t('ok').$cid;
  // TODO : rediriger ici vers la passerelle de paiement (Paymee / Konnect / Flouci / ClicToPay) avec $cid et $tot
 }}
head(t('panier'));
if($msg)echo "<p class='ok'>".h($msg)."</p>";
if(!$cart){if(!$msg)echo "<p>".t('vide')."</p>";foot();exit;}
$in=implode(',',array_fill(0,count($cart),'?'));$st=$pdo->prepare("SELECT * FROM produit WHERE id IN($in)");$st->execute(array_keys($cart));$tot=0;?>
<form method="post"><table><?php foreach($st as $r):$qt=$cart[$r['id']];$tot+=$r['prix']*$qt;?><tr><td><?=col($r,'titre')?></td><td><input class="qt" type="number" min="0" name="q[<?=$r['id']?>]" value="<?=$qt?>"></td><td><?=m($r['prix']*$qt)?></td></tr><?php endforeach;?>
<tr class="tot"><td colspan="2"><?=t('total')?></td><td><?=m($tot)?></td></tr></table><button><?=t('maj')?></button></form>
<form method="post" class="co"><label><?=t('nom')?><input name="nom" required></label><label><?=t('tel')?><input name="tel" required pattern="[0-9 +]{8,15}"></label>
<label><?=t('mode')?><select name="mode"><option value="livraison"><?=t('liv')?><option value="retrait"><?=t('ret')?></select></label><label><?=t('adr')?><input name="adresse"></label><button><?=t('cmd')?></button></form><?php foot();
