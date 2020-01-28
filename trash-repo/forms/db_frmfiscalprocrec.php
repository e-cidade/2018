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

//MODULO: fiscal
$clfiscalprocrec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y29_descr");
$clrotulo->label("k02_descr");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_fiscalprocrec.location.href='fis1_fiscalprocrec002.php?chavepesquisa=$y45_codtipo&chavepesquisa1=$y45_receit'</script>";}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_fiscalprocrec.location.href='fis1_fiscalprocrec003.php?chavepesquisa=$y45_codtipo&chavepesquisa1=$y45_receit'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty45_codtipo?>">
       <?
       db_ancora(@$Ly45_codtipo,"js_pesquisay45_codtipo(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('y45_codtipo',4,$Iy45_codtipo,true,'text',3," onchange='js_pesquisay45_codtipo(false);'")
?>
       <?
db_input('y29_descr',50,$Iy29_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty45_receit?>">
       <?
       db_ancora(@$Ly45_receit,"js_pesquisay45_receit(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y45_receit',4,$Iy45_receit,true,'text',$db_opcao," onchange='js_pesquisay45_receit(false);'");
if($db_opcao == 2){
  db_input('y45_receit',4,$Iy45_receit,true,'hidden',$db_opcao,"","y45_receit_old");
  echo "<script>document.form1.y45_receit_old.value = '$y45_receit'</script>";
}
?>
       <?
db_input('k02_descr',15,$Ik02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr >
    <td align="left" nowrap title="Valor fixo SIM/NÃO" >
    <strong>Valor Fixo :&nbsp;&nbsp;</strong>
    </td>
    <td>
      <? 
      $tipo_fixo = array("t"=>"Sim","f"=>"Não");
      db_select("y45_vlrfixo",$tipo_fixo,true,$db_opcao,"onchange='js_testavalor(this.value);'"); ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty45_valor?>">
       <?=@$Ly45_valor?>
    </td>
    <td> 
<?
db_input('y45_valor',10,$Iy45_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty45_descr?>">
       <?=@$Ly45_descr?>
    </td>
    <td> 
<?
db_input('y45_descr',50,$Iy45_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    </td>
  </tr>
  <tr>
    <td colspan="2" align="top">
   <?
    $chavepri= array("y45_codtipo"=>@$y45_codtipo,"y45_receit"=>@$y45_receit);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="y45_codtipo,y45_receit,y45_valor,y45_descr";
    $cliframe_alterar_excluir->sql=$clfiscalprocrec->sql_query("","","*",""," y45_codtipo = $y45_codtipo");
    $cliframe_alterar_excluir->legenda="Receitas da Procedência";
    $cliframe_alterar_excluir->msg_vazio ="<font size='1'>Nenhum Registro Encontrado!</font>";
    $cliframe_alterar_excluir->textocabec ="darkblue";
    $cliframe_alterar_excluir->textocorpo ="black";
    $cliframe_alterar_excluir->fundocabec ="#aacccc";
    $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
    $cliframe_alterar_excluir->iframe_height ="170";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
   ?>
      
    </td>
  </tr>
  </table>
  </center>
</form>
<script>
function js_testavalor(fixo){
  if (fixo=='t'){
    document.form1.y45_valor.disabled='';
  }else if (fixo=='f'){
    document.form1.y45_valor.disabled='true';
  }
}
function js_pesquisay45_codtipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_fiscalproc','func_fiscalproc.php?funcao_js=parent.js_mostrafiscalproc1|y29_codtipo|y29_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_fiscalproc','func_fiscalproc.php?pesquisa_chave='+document.form1.y45_codtipo.value+'&funcao_js=parent.js_mostrafiscalproc','Pesquisa',false);
  }
}
function js_mostrafiscalproc(chave,erro){
  document.form1.y29_descr.value = chave; 
  if(erro==true){ 
    document.form1.y45_codtipo.focus(); 
    document.form1.y45_codtipo.value = ''; 
  }
}
function js_mostrafiscalproc1(chave1,chave2){
  document.form1.y45_codtipo.value = chave1;
  document.form1.y29_descr.value = chave2;
  db_iframe_fiscalproc.hide();
}
function js_pesquisay45_receit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.y45_receit.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.y45_receit.focus(); 
    document.form1.y45_receit.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.y45_receit.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_fiscalprocrec','func_fiscalprocrec.php?funcao_js=parent.js_preenchepesquisa|y45_codtipo|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_fiscalprocrec.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
+"&chavepesquisa1="+chave1}
</script>
<?
if(isset($y45_codtipo) && $y45_codtipo != ""){
  echo "<script>js_OpenJanelaIframe('','db_iframe_fiscalproc','func_fiscalproc.php?pesquisa_chave=$y45_codtipo&funcao_js=parent.js_mostrafiscalproc','Pesquisa',false);</script>";
}
?>