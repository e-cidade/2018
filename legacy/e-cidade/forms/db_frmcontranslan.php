<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */


//MODULO: contabilidade
$clcontranslan->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c45_coddoc");
$clrotulo->label("c50_descr");
$clrotulo->label("c53_coddoc");
$clrotulo->label("c53_descr");
if($db_opcao==11 || $db_opcao==1){
     $db_action="con1_contranslan004.php";
}else if($db_opcao==2||$db_opcao==22){
     $db_action="con1_contranslan005.php";
}else if($db_opcao==3||$db_opcao==33){
     $db_action="con1_contranslan006.php";
}

if(isset($c53_coddoc)){
  $result01 = $clcontrans->sql_record($clcontrans->sql_query("","c45_seqtrans as c46_seqtrans",null,"c45_anousu = $anousu and c45_coddoc = $c53_coddoc and c45_instit = $instit"));
  if($clcontrans->numrows > 0){
    db_fieldsmemory($result01,0);
  }else{
    $c46_seqtrans='';
  }
}
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
<?
if(empty($c46_seqtrans) && isset($outro) && $outro==true){
echo "
  <tr>
    <td colspan='2' align='center'> 
      <b>Não existe lançamento para este histórico!</b>
    </td>
  </tr>";
}

?>

  <tr>
    <td nowrap title="<?=@$Tc53_coddoc?>">
       <?=$Lc53_coddoc ?>
    </td>
    <td> 
       <? db_input('c53_coddoc',4,$Ic53_coddoc,true,'text',3);?>
       <? db_input('c53_descr',40,$Ic53_descr,true,'text',3,'')       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc46_seqtrans?>"><?=@$Lc46_seqtrans?> </td>
    <td> 
    <? db_input('c46_seqtrans',6,$Ic46_seqtrans,true,'text',3,""); ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc46_seqtranslan?>"><?=@$Lc46_seqtranslan?></td>
    <td> 
     <? db_input('c46_seqtranslan',8,$Ic46_seqtranslan,true,'text',3,"") ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc46_codhist?>"><? db_ancora(@$Lc46_codhist,"js_pesquisac46_codhist(true);",$db_opcao); ?> </td>
    <td> 
       <? db_input('c46_codhist',4,$Ic46_codhist,true,'text',$db_opcao," onchange='js_pesquisac46_codhist(false);'")   ?>
       <? db_input('c50_descr',40,$Ic50_descr,true,'text',3,'')    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc46_obs?>"><?=@$Lc46_obs?></td>
    <td><? db_textarea('c46_obs',5,80,$Ic46_obs,true,'text',$db_opcao,"") ?> </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc46_valor?>"><?=@$Lc46_valor?></td>
    <td><? db_input('c46_valor',20,$Ic46_valor,true,'text',$db_opcao) ?></td>
  </tr>
  <tr><td nowrap title="<?=@$Tc46_valor?>"><b>Obrigatório:</b></td>
    <td><?
          $xy = array("f"=>"NÃO","t"=>"SIM");
          db_select('c46_obrigatorio',$xy,true,$db_opcao);
        ?>
    </td>
  </tr>
   <tr>
    <td nowrap title="<?=@$Tc46_evento?>"><? db_ancora(@$Lc46_evento,"js_pesquisac46_evento(true);",$db_opcao); ?> </td>
    <td>
       <? db_input('c46_evento',10,$Ic46_evento,true,'text',$db_opcao) ?>
       <? if (isset($c46_evento) && ($c46_evento!="")){
	    $rr = $clconhistdoc->sql_record($clconhistdoc->sql_query($c46_evento,"c53_descr as c46_eventodescr"));
	    if ($clconhistdoc->numrows>0)
	       db_fieldsmemory($rr,0);
          }
        ?> 
       <? db_input('c46_eventodescr',40,'',true,'text',3) ?>

    </td>
  </tr>
 


  
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="seleciona" type="button"  value="Selecionar  histórico" onclick="js_pesquisahist();" >
  <?if($db_opcao==2) {?>
     <input name="novo" type="button"  value="Novo lançamento" onclick="js_novo();"  >
  <?}?>     
  <?if(isset($c46_seqtrans) && $c46_seqtrans!=""){?>
     <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar  documentos" onclick="js_pesquisa();" <?=(isset($c46_seqtrans) && $c46_seqtrans!=""?"":"disabled")?> >
  <?}?>     
  
</form>
<script>
function js_novo(){
  <?if(isset($c53_coddoc)){?>
  location.href = "con1_contranslan004.php?c53_coddoc=<?=$c53_coddoc?>";
  <?}?>
} 
function js_pesquisahist(){
  js_OpenJanelaIframe('top.corpo.iframe_contranslan','db_iframe_conhist','func_conhistdoc.php?funcao_js=parent.js_preenchepesquisahist|c53_coddoc|c53_descr','Pesquisa',true,'0');
}
function js_preenchepesquisahist(coddoc,descr){
      document.form1.c53_coddoc.value = coddoc;
      document.form1.c53_descr.value  = descr;
  <?if($db_opcao!=11||$db_opcao!=1){?>

	      obj=document.createElement('input');
	      obj.setAttribute('name','outro');
	      obj.setAttribute('type','hidden');
      	      obj.setAttribute('value','true');
	      document.form1.appendChild(obj);
  <?}?>    
      document.form1.submit();
      db_iframe_conhist.hide();
}

function js_pesquisac46_seqtrans(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_contranslan','db_iframe_contrans','func_contrans.php?funcao_js=parent.js_mostracontrans1|c45_seqtrans|c45_coddoc','Pesquisa',true,'0');
  }else{
     if(document.form1.c46_seqtrans.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_contranslan','db_iframe_contrans','func_contrans.php?pesquisa_chave='+document.form1.c46_seqtrans.value+'&funcao_js=parent.js_mostracontrans','Pesquisa',false,'0');
     }else{
       document.form1.c45_coddoc.value = ''; 
     }
  }
}
function js_mostracontrans(chave,erro){
  document.form1.c45_coddoc.value = chave; 
  if(erro==true){ 
    document.form1.c46_seqtrans.focus(); 
    document.form1.c46_seqtrans.value = ''; 
  }
}
function js_mostracontrans1(chave1,chave2){
  document.form1.c46_seqtrans.value = chave1;
  document.form1.c45_coddoc.value = chave2;
  db_iframe_contrans.hide();
}


function js_pesquisac46_codhist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_contranslan','db_iframe_conhist','func_conhist.php?funcao_js=parent.js_mostraconhist1|c50_codhist|c50_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.c46_codhist.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_contranslan','db_iframe_conhist','func_conhist.php?pesquisa_chave='+document.form1.c46_codhist.value+'&funcao_js=parent.js_mostraconhist','Pesquisa',false,'0');
     }else{
       document.form1.c50_descr.value = ''; 
     }
  }
}
function js_mostraconhist(chave,erro){
  document.form1.c50_descr.value = chave; 
  if(erro==true){ 
    document.form1.c46_codhist.focus(); 
    document.form1.c46_codhist.value = ''; 
  }
}
function js_mostraconhist1(chave1,chave2){
  document.form1.c46_codhist.value = chave1;
  document.form1.c50_descr.value = chave2;
  db_iframe_conhist.hide();
}
function js_pesquisac46_evento(mostra){
  js_OpenJanelaIframe('top.corpo.iframe_contranslan','db_iframe_conhistdoc','func_conhistdoc.php?funcao_js=parent.js_mostraevento|c53_coddoc|c53_descr','Pesquisa',true,'0');
} 
function js_mostraevento(chave1,chave2){
   document.form1.c46_evento.value=chave1;
   document.form1.c46_eventodescr.value=chave2;
   db_iframe_conhistdoc.hide();
}  
function js_pesquisa(){
 <?if(isset($c46_seqtrans)){?> 
    js_OpenJanelaIframe('top.corpo.iframe_contranslan','db_iframe_contranslan','func_contranslan.php?chave_c46_seqtrans=<?=$c46_seqtrans?>&funcao_js=parent.js_preenchepesquisa|c46_seqtranslan','Pesquisa',true,'0');
 <?}?>  
}
function js_preenchepesquisa(chave){
  db_iframe_contranslan.hide();
  <?

  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
<?
if( $db_opcao == 11 ){
  echo "\n
           js_pesquisahist();
  \n";
}
?>
</script>