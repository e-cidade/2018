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

//MODULO: Pessoal
require_once("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$clrhresponsavelregist->rotulo->label();
$clrhresponsavel->rotulo->label();
$clcgm->rotulo->label();

if (isset($db_opcaoal)) {
	
  $db_opcao = 33;
  $db_botao = false;
} else if (isset($opcao) && $opcao == "alterar") {
	
  $db_botao = true;
  $db_opcao = 2;
} else if (isset($opcao) && $opcao == "excluir") {
	
  $db_opcao = 3;
  $db_botao = true;
} else {
	  
  $db_opcao = 1;
  $db_botao = true;
  if (isset($novo) || isset($oPost->alterar) ||   isset($oPost->excluir) || (isset($oPost->incluir) && $lSqlErro == false ) ) {
  	
  	$rh107_sequencial  = '';
  	$rh108_regist      = '';
  	$z01_nome_servidor = '';
  	$rh108_status      = 's';
  }
} 
?>
<form name="form1" method="post" action="">
<fieldset>
  <legend>
    <b>Vinculação entre Responsável e Servidores</b>
  </legend>
  <table border="0">
	  <tr>
	    <td nowrap title="<?=@$Trh107_sequencial?>">
	      <?=@$Lrh107_sequencial?>
	    </td>
	    <td> 
	      <?
	        db_input('rh108_sequencial', 10, $Irh108_sequencial, true, 'hidden', 3);
	        db_input('rh108_rhresponsavel', 10, $Irh108_rhresponsavel, true, 'text', 3);
	      ?>
	    </td>
	    <td>
	      <?
	        db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3);
	      ?> 
	    </td>
	  </tr>
    <tr>
	    <td title="<?=@$Trh108_regist?>">
	      <?
	        db_ancora('<b>Servidor:</b>', "js_pesquisarh108_regist(true);", $db_opcao);
	      ?>
	    </td>
	    <td> 
	      <?
	        db_input('rh108_regist', 10, $Irh108_regist, true, 'text', $db_opcao, "onchange='js_pesquisarh108_regist(false);'");
	      ?>
	    </td>
	    <td colspan="2">
	      <?
	        db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, null, "z01_nome_servidor");
	      ?>
	    </td>
    </tr>
    <tr>
	    <td title="<?=@$Trh108_status?>">
	      <?=@$Lrh108_status?>
	    </td>
	    <td colspan="2"> 
	      <?
	        $aStatus = array("s" => "Selecione",
	                         "t" => "Ativo", 
	                         "f" => "Inativo");             
	        db_select("rh108_status", $aStatus, true, $db_opcao, "onchange='js_desabilitaSelecionar();' style='width:100%;'"); 
	      ?>
	    </td>
    </tr>
  </table>
</fieldset>
<table>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
             type="submit" id="db_opcao" onclick="return js_validarCampos();"
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
             <?=($db_botao==false?"disabled":"")?>  >
    </td>
    <td>
      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" 
             <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table>
  <tr>
    <td valign="top"  align="center">  
    <?
      $sCamposRhresponsavelRegist  = "rh108_sequencial,rh108_regist,cgm.z01_nome,rh108_status"; 
      $sWhereRhresponsavelRegist   = "rh108_rhresponsavel = {$oGet->rh107_sequencial}";
      $sOrderByRhresponsavelRegist = "rh108_sequencial";
      $sSqlRhresponsavelRegist     = $clrhresponsavelregist->sql_query(null, 
                                                                       $sCamposRhresponsavelRegist, 
                                                                       $sOrderByRhresponsavelRegist, 
                                                                       $sWhereRhresponsavelRegist);
      $aChavePrimaria                          = array("rh108_sequencial" => @$rh108_sequencial);
      $cliframe_alterar_excluir->chavepri      = $aChavePrimaria;
      $cliframe_alterar_excluir->sql           = $sSqlRhresponsavelRegist;
      $cliframe_alterar_excluir->campos        = "rh108_sequencial,rh108_regist,z01_nome,rh108_status";
      $cliframe_alterar_excluir->alignlegenda  = "left";
      $cliframe_alterar_excluir->legenda       = "Servidores Vinculados";
      $cliframe_alterar_excluir->iframe_height = "160";
      $cliframe_alterar_excluir->iframe_width  = "530";
      $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
</form>
<script>
function js_cancelar() {

  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

function js_validarCampos() {

  var iMatriculaServidor = $('rh108_regist').value;
  var sStatus            = $('rh108_status').value;
  if (iMatriculaServidor == '') {
  
    alert('Informe a matricula do servidor!');
    return false;
  }
  
  if (sStatus == 's') {

    alert('Selecione o status da vinculação!');
    return false;  
  }
}

function js_desabilitaSelecionar() {

  var sStatus = $('rh108_status').value;
  if (sStatus != 's') {
    $('rh108_status').options[0].disabled = true; 
  }
}

function js_pesquisarh108_regist(mostra) {

  if (mostra == true) {
  
    var sUrl = 'func_rhpessoal.php?funcao_js=parent.js_mostrarh108_regist1|rh01_regist|z01_nome';
    js_OpenJanelaIframe('top.corpo.iframe_rhresponsavelregist', 'db_iframe_servidor', sUrl, 'Pesquisa', true, '0');
  } else {
  
    if ($('rh108_regist').value != '') {
     
      var sUrl = 'func_rhpessoal.php?pesquisa_chave='+$('rh108_regist').value
                                                +'&funcao_js=parent.js_mostrarh108_regist';
      js_OpenJanelaIframe('top.corpo.iframe_rhresponsavelregist', 'db_iframe_servidor', sUrl, 'Pesquisa', false, '0');
    } else {
      $('z01_nome_servidor').value = '';
    }
  }
}

function js_mostrarh108_regist(chave1, erro) {

  $('z01_nome_servidor').value = chave1; 
  if (erro == true) { 
  
    $('rh108_regist').focus(); 
    $('rh108_regist').value = '';
  }
}

function js_mostrarh108_regist1(chave1, chave2) {

  $('rh108_regist').value      = chave1;
  $('z01_nome_servidor').value = chave2;
  db_iframe_servidor.hide();
}

js_desabilitaSelecionar();
</script>