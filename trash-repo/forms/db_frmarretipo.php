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

//MODULO: caixa
$clarretipo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k03_tipo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk00_codbco?>">
       <?=@$Lk00_codbco?>
    </td>
    <td> 
<?
db_input('k00_codbco',4,$Ik00_codbco,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_codage?>">
       <?=@$Lk00_codage?>
    </td>
    <td> 
<?
db_input('k00_codage',5,$Ik00_codage,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_tipo?>">
       <?=@$Lk00_tipo?>
    </td>
    <td> 
<?
db_input('k00_tipo',4,$Ik00_tipo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_descr?>">
       <?=@$Lk00_descr?>
    </td>
    <td> 
<?
db_input('k00_descr',40,$Ik00_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_emrec?>">
       <?=@$Lk00_emrec?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('k00_emrec',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_agnum?>">
       <?=@$Lk00_agnum?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('k00_agnum',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_agpar?>">
       <?=@$Lk00_agpar?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('k00_agpar',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_msguni?>">
       <?=@$Lk00_msguni?>
    </td>
    <td> 
<?
db_textarea('k00_msguni',0,0,$Ik00_msguni,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_msguni2?>">
       <?=@$Lk00_msguni2?>
    </td>
    <td> 
<?
db_textarea('k00_msguni2',0,0,$Ik00_msguni2,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_msgparc?>">
       <?=@$Lk00_msgparc?>
    </td>
    <td> 
<?
db_textarea('k00_msgparc',0,0,$Ik00_msgparc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_msgparc2?>">
       <?=@$Lk00_msgparc2?>
    </td>
    <td> 
<?
db_textarea('k00_msgparc2',0,0,$Ik00_msgparc2,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_msgparcvenc?>">
       <?=@$Lk00_msgparcvenc?>
    </td>
    <td> 
<?
db_textarea('k00_msgparcvenc',0,0,$Ik00_msgparcvenc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_msgparcvenc2?>">
       <?=@$Lk00_msgparcvenc2?>
    </td>
    <td> 
<?
db_textarea('k00_msgparcvenc2',0,0,$Ik00_msgparcvenc2,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_msgrecibo?>">
       <?=@$Lk00_msgrecibo?>
    </td>
    <td> 
<?
db_textarea('k00_msgrecibo',0,0,$Ik00_msgrecibo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_tercdigcarneunica?>">
       <?=@$Lk00_tercdigcarneunica?>
    </td>
    <td> 
<?
db_input('k00_tercdigcarneunica',10,$Ik00_tercdigcarneunica,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_tercdigcarnenormal?>">
       <?=@$Lk00_tercdigcarnenormal?>
    </td>
    <td> 
<?
db_input('k00_tercdigcarnenormal',10,$Ik00_tercdigcarnenormal,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_tercdigrecunica?>">
       <?=@$Lk00_tercdigrecunica?>
    </td>
    <td> 
<?
db_input('k00_tercdigrecunica',10,$Ik00_tercdigrecunica,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_tercdigrecnormal?>">
       <?=@$Lk00_tercdigrecnormal?>
    </td>
    <td> 
<?
db_input('k00_tercdigrecnormal',10,$Ik00_tercdigrecnormal,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_txban?>">
       <?=@$Lk00_txban?>
    </td>
    <td> 
<?
db_input('k00_txban',15,$Ik00_txban,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_rectx?>">
       <?=@$Lk00_rectx?>
    </td>
    <td> 
<?
db_input('k00_rectx',4,$Ik00_rectx,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcodmodelo?>">
       <?=@$Lcodmodelo?>
    </td>
    <td> 
<?
db_input('codmodelo',6,$Icodmodelo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_impval?>">
       <?=@$Lk00_impval?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('k00_impval',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_vlrmin?>">
       <?=@$Lk00_vlrmin?>
    </td>
    <td> 
<?
db_input('k00_vlrmin',15,$Ik00_vlrmin,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk03_tipo?>">
       <?
       db_ancora(@$Lk03_tipo,"js_pesquisak03_tipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k03_tipo',3,$Ik03_tipo,true,'text',$db_opcao," onchange='js_pesquisak03_tipo(false);'")
?>
       <?
db_input('k03_tipo',3,$Ik03_tipo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_marcado?>">
       <?=@$Lk00_marcado?>
    </td>
    <td> 
<?
$x = array('t'=>'Marcado','f'=>'Desmarcado');
db_select('k00_marcado',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_hist1?>">
       <?=@$Lk00_hist1?>
    </td>
    <td> 
<?
db_input('k00_hist1',80,$Ik00_hist1,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_hist2?>">
       <?=@$Lk00_hist2?>
    </td>
    <td> 
<?
db_input('k00_hist2',80,$Ik00_hist2,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_hist3?>">
       <?=@$Lk00_hist3?>
    </td>
    <td> 
<?
db_input('k00_hist3',80,$Ik00_hist3,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_hist4?>">
       <?=@$Lk00_hist4?>
    </td>
    <td> 
<?
db_input('k00_hist4',80,$Ik00_hist4,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_hist5?>">
       <?=@$Lk00_hist5?>
    </td>
    <td> 
<?
db_input('k00_hist5',80,$Ik00_hist5,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_hist6?>">
       <?=@$Lk00_hist6?>
    </td>
    <td> 
<?
db_input('k00_hist6',80,$Ik00_hist6,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_hist7?>">
       <?
       db_ancora(@$Lk00_hist7,"js_pesquisak00_hist7(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k00_hist7',80,$Ik00_hist7,true,'text',$db_opcao," onchange='js_pesquisak00_hist7(false);'")
?>
       <?
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_hist8?>">
       <?=@$Lk00_hist8?>
    </td>
    <td> 
<?
db_input('k00_hist8',80,$Ik00_hist8,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_tipoagrup?>">
       <?=@$Lk00_tipoagrup?>
    </td>
    <td> 
<?
$x = array('1'=>'Nenhum','2'=>'Parcial','3'=>'Total');
db_select('k00_tipoagrup',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisak03_tipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadtipo','func_cadtipo.php?funcao_js=parent.js_mostracadtipo1|k03_tipo|k03_tipo','Pesquisa',true);
  }else{
     if(document.form1.k03_tipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cadtipo','func_cadtipo.php?pesquisa_chave='+document.form1.k03_tipo.value+'&funcao_js=parent.js_mostracadtipo','Pesquisa',false);
     }else{
       document.form1.k03_tipo.value = ''; 
     }
  }
}
function js_mostracadtipo(chave,erro){
  document.form1.k03_tipo.value = chave; 
  if(erro==true){ 
    document.form1.k03_tipo.focus(); 
    document.form1.k03_tipo.value = ''; 
  }
}
function js_mostracadtipo1(chave1,chave2){
  document.form1.k03_tipo.value = chave1;
  document.form1.k03_tipo.value = chave2;
  db_iframe_cadtipo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_preenchepesquisa|k00_tipo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_arretipo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>