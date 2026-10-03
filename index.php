<?php require 'inc.php';
if($_SERVER['REQUEST_METHOD']=='POST'){$id=(int)$_POST['id'];$_SESSION['cart'][$id]=($_SESSION['cart'][$id]??0)+1;header('Location: '.$_SERVER['REQUEST_URI']);exit;}
$q=trim($_GET['q']??'');$ty=in_array($_GET['type']??'',['livre','fourniture'])?$_GET['type']:'';
$sql="SELECT * FROM produit WHERE (titre_fr LIKE ? OR titre_ar LIKE ?)".($ty?" AND type=?":"")." ORDER BY titre_fr";
$p=["%$q%","%$q%"];if($ty)$p[]=$ty;$st=$pdo->prepare($sql);$st->execute($p);
head(t('catalogue'));?>
<section class="hero"><div class="fl" aria-hidden="true"><i>✏️</i><i>📏</i><i>📚</i><i>🖍️</i><i>✂️</i><i>📐</i></div><h1><?=t('h1')?></h1><p><?=t('sub')?></p>
<form><input name="q" value="<?=h($q)?>" placeholder="<?=t('rech')?>"><select name="type"><option value=""><?=t('tous')?><option value="livre" <?=$ty=='livre'?'selected':''?>><?=t('livre')?><option value="fourniture" <?=$ty=='fourniture'?'selected':''?>><?=t('fourniture')?></select><button>OK</button></form></section>
<div class="grid"><?php foreach($st as $r):?>
<article><div class="ico"><?=$r["type"]=="livre"?"📘":"✏️"?></div><span class="tag"><?=t($r['type'])?></span><h3><?=col($r,'titre')?></h3><p class="prix"><?=m($r['prix'])?></p>
<?php if($r['stock']>0):?><form method="post"><input type="hidden" name="id" value="<?=$r['id']?>"><button><?=t('ajouter')?></button></form><?php else:?><em><?=t('stock')?></em><?php endif;?></article>
<?php endforeach;?></div><?php foot();
