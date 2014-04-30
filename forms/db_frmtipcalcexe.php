<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: issqn
require_once("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo                 = new rotulocampo;
$cltipcalcexe->rotulo->label();
$clrotulo->label("q92_descr");

if (isset($db_opcaoal)) {
  
  $db_opcao = 33;
  $db_botao = false;
}else if (isset($opcao) && $opcao == "alterar") {
  
    $db_botao = true;
    $db_opcao = 2;
}else if (isset($opcao) && $opcao == "excluir") {
  
    $db_opcao = 3;
    $db_botao = true;
}else{  
  
    $db_opcao = 1;
    $db_botao = true;
    
    if (isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro == false ) ) {
      
      $q83_codigo = "";
      $q83_anousu = "";
      $q83_codven = "";
      $q92_descr  = "";
   }
} 
/**
 * sql para retornar a descrição do novo campo criado :$q83_cadvencdescsimples
 */
if (isset($q83_cadvencdescsimples) && $q83_cadvencdescsimples != null) {

	require_once("classes/db_cadvencdesc_classe.php");
	$oDaoCadvencdesc  = new cl_cadvencdesc;
	$sSqlDescr        = $oDaoCadvencdesc->sql_query_file($q83_cadvencdescsimples, "q92_descr as descricaoSimples", null, null);
  $rsDescr          = $oDaoCadvencdesc->sql_record($sSqlDescr);
  $descricaoSimples = db_utils::fieldsMemory($rsDescr, 0)->descricaosimples;
}

?>

<center>

<form name="form1" method="post" action="">

<fieldset style="margin-top: 30px; width: 700px;">
<legend><strong>Cadastro de Vencimentos</strong></legend>

<table border="0" align='left'>

  <tr>
    <td nowrap title="<?=@$Tq83_tipcalc?>">
       <?=@$Lq83_tipcalc?>
    </td>    
    <td> 
     <?
       db_input('q83_codigo', 10, $Iq83_codigo, true, 'hidden', 3, "");
       db_input('q83_tipcalc', 4, $Iq83_tipcalc, true, 'text', 3, "");
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq83_anousu?>">
       <?=@$Lq83_anousu?>
    </td>
    <td> 
     <?
        if (!isset($q83_anousu) || isset($q83_anousu) && $q83_anousu == "") {
        	$q83_anousu = db_getsession('DB_anousu');
        }
        db_input('q83_anousu', 4, $Iq83_anousu, true, 'text', $db_opcao, "")
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq83_codven?>">
       <?
         db_ancora(@$Lq83_codven,"js_pesquisaq83_codven(true);",$db_opcao);
       ?>
    </td>
    <td> 
      <?
        db_input('q83_codven',4,$Iq83_codven,true,'text',$db_opcao," onchange='js_pesquisaq83_codven(false);'");
        db_input('q92_descr',40,$Iq92_descr,true,'text',3,'')
      ?>
    </td>
  </tr>
  
  
  <tr id='cntVencimentoSimples' style="display:none;">
    <td nowrap title="<?=@$Tq83_cadvencdescsimples?>">
    
       <?
         db_ancora(@$Lq83_cadvencdescsimples,"js_pesquisaSimples(true);",$db_opcao);
       ?>    
    </td>
    <td>
      <? 
        db_input('q83_cadvencdescsimples', 4 ,$Iq83_cadvencdescsimples, true, 'text', $db_opcao, " onchange='js_pesquisaSimples(false);'");
        db_input('descricaoSimples',      40 ,$Iq92_descr, true,'text', 3, '');
      ?>
    </td>
  </tr>
  
  
  
</table>

</fieldset>

<div style="margin-top: 10px;">
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
  <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
</div>
  
 <table style="margin-top: 10px;">
  <tr>
    <td valign="top"  align="center">  
    <?
	     
       $chavepri= array("q83_codigo" => @$q83_codigo);
	     
	     $cliframe_alterar_excluir->chavepri      = $chavepri;	 
	     $cliframe_alterar_excluir->sql           = $cltipcalcexe->sql_query_venc(null, "*", "q83_anousu", "q83_tipcalc = {$q83_tipcalc}");
	     $cliframe_alterar_excluir->campos        = "q83_codigo, q83_tipcalc, q83_anousu, q83_codven, q92_descr";
	     $cliframe_alterar_excluir->legenda       = "ITENS LANÇADOS";
	     $cliframe_alterar_excluir->iframe_height = "160";
	     $cliframe_alterar_excluir->iframe_width  = "700";
	     $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>

</form>

</center>


<script>
    
/**
 * func de pesquisa do campo codigo do venmcimento do simples
 */
    
function js_pesquisaSimples(mostra){
   
  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo.iframe_tipcalcexe','db_iframe_cadvencdescSimples','func_cadvencdesc.php?funcao_js=parent.js_mostraSimples1|q92_codigo|q92_descr','Pesquisa',true,'0','1','775','390');
  }else{
    
     if($F('q83_cadvencdescsimples') != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_tipcalcexe','db_iframe_cadvencdescSimples','func_cadvencdesc.php?pesquisa_chave='+$F("q83_cadvencdescsimples")+'&funcao_js=parent.js_mostraSimples','Pesquisa',false);
     }else{
       document.form1.descricaoSimples.value = ''; 
     }
  }
}
function js_mostraSimples(chave, erro){
  
  $('descricaoSimples').value = chave; 
  if(erro == true){ 
    $('q83_cadvencdescsimples').focus(); 
    $('q83_cadvencdescsimples').value = ''; 
  }
}
function js_mostraSimples1(chave1, chave2) {
  
  $('q83_cadvencdescsimples').value = chave1;
  $('descricaoSimples')      .value = chave2;
  db_iframe_cadvencdescSimples.hide();
}

/**
 * func de pesquisa do campo codigo do venmcimento
 */
    
function js_pesquisaq83_codven(mostra){
   
  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo.iframe_tipcalcexe','db_iframe_cadvencdesc','func_cadvencdesc.php?funcao_js=parent.js_mostracadvencdesc1|q92_codigo|q92_descr','Pesquisa',true,'0','1','775','390');
  } else {
    
     if ($('q83_codven').value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_tipcalcexe','db_iframe_cadvencdesc','func_cadvencdesc.php?pesquisa_chave='+$('q83_codven').value+'&funcao_js=parent.js_mostracadvencdesc','Pesquisa',false);
     }else{
       $('q92_descr').value = ''; 
     }
  }
}
 
function js_mostracadvencdesc(chave,erro){
  
  $('q92_descr').value = chave; 
  if(erro==true){ 
    document.form1.q83_codven.focus(); 
    document.form1.q83_codven.value = ''; 
  } 
  js_toogleSimples();
}

function js_mostracadvencdesc1(iCodVen, sDesc) {

  $('q83_codven').value = iCodVen;
  $('q92_descr').value  = sDesc;
  db_iframe_cadvencdesc.hide();
  js_toogleSimples();
}

function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

function js_toogleSimples() {

  if (<?php echo $iVencSimples ?> == '3') {
  //if ($('q83_codven').value == '3') {
    $('cntVencimentoSimples').style.display = '';
  } else {
   
    $('cntVencimentoSimples').style.display = 'none';
    $('q83_cadvencdescsimples').value       = null;
    $('descricaoSimples').value             = null;
 }  
}

js_toogleSimples();

</script>