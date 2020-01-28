<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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


$clorcprograma->rotulo->label();
$clorcprogramahorizontetemp->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");

require_once("classes/db_orcparametro_classe.php");
$clorcparametro = new cl_orcparametro;

if ($db_opcao == 1 && empty($o54_programa) || (isset($o50_programa)) && $o50_programa == '') {

  $result01 = $clorcparametro->sql_record($clorcparametro->sql_query_file(db_getsession("DB_anousu"), 'o50_programa'));

  if ($clorcparametro->numrows > 0) {

    db_fieldsmemory($result01, 0);
    $o54_programa = $o50_programa;
    $clorcparametro->o50_programa = $o50_programa + 1;
    $clorcparametro->o50_anousu = db_getsession("DB_anousu");
    $clorcparametro->alterar(db_getsession("DB_anousu"));
  } 
}

?>
<center>
<form name="form1" method="post" action="">
<fieldset id="fieldsetDadosGeraisPrograma" style="width: 600px">
<legend><b>Dados Gerais do Programa</b></legend>
			  <fieldset >
			    <legend>
			      <b>Dados Programa</b>
			    </legend>
				<table border="0">
				  <tr>
				    <td nowrap title="<?=@$To54_anousu ?>">
				       <?=@$Lo54_anousu ?>
				    </td>
				    <td>
					   <?
$o54_anousu = db_getsession('DB_anousu');
db_input('o54_anousu', 10, $Io54_anousu, true, 'text', 3);
             ?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$To54_codtri ?>">
				       <?=@$Lo54_codtri ?>
				    </td>
				    <td>
					   <?
db_input('o54_codtri', 10, $Io54_codtri, true, 'text', $db_opcao, "")
             ?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$To54_programa ?>">
				      <b>Código:</b>
				    </td>
				    <td>
					  <?
if ($db_opcao == 1) {
  $db_opcao02 = 1;
} else {
  $db_opcao02 = 3;
}

db_input('o54_programa', 10, $Io54_programa, true, 'text', $db_opcao02);

            ?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$To54_descr ?>">
				       <b>Denominação:</b>
				    </td>
				    <td>
					   <?
db_input('o54_descr', 65, $Io54_descr, true, 'text', $db_opcao, "")
             ?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$To54_tipoprograma ?>">
				       <?=@$Lo54_tipoprograma ?>
				    </td>
				    <td>
					   <?
$aTipoPrograma = array(1 => "Programas Finalísticos", 2 => "Programas de Apoio as Políticas e Áreas Especiais",
    3 => "Programas Temáticos", 4 => "Programas de Gestão, Manutenção e Serviços ao Estado");

db_select("o54_tipoprograma", $aTipoPrograma, true, $db_opcao);
             ?>
				    </td>
				  </tr>
			    </table>
			  </fieldset>

			  <fieldset>
			    <legend>
			      <b>Horizonte Temporal</b>
			    </legend>
			    <table border="0" align="left">
				  <tr>
				    <td nowrap title="<?=@$To17_dataini ?>">
				       <?=$Lo17_dataini ?>
				    </td>
				    <td>
					   <?
db_inputdata("o17_dataini", @$o17_dataini_dia, @$o17_dataini_mes, @$o17_dataini_ano, true, 'text', $db_opcao);
             ?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$To17_datafin ?>">
				       <?=$Lo17_datafin ?>
				    </td>
				    <td>
					   <?
db_inputdata("o17_datafin", @$o17_datafin_dia, @$o17_datafin_mes, @$o17_datafin_ano, true, 'text', $db_opcao);
             ?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$To17_valor ?>">
				       <?=$Lo17_valor ?>
				    </td>
				    <td>
					   <?
db_input('o17_sequencial', 10, "", true, 'hidden', $db_opcao, "");
db_input('o17_valor', 10, $Io17_valor, true, 'text', $db_opcao, "");
             ?>
				    </td>
				  </tr>
			    </table>
			  </fieldset>
</fieldset>
<br />
<fieldset id="fieldsetDetalhesPrograma" style="width: 600px;">
  <legend><b>Detalhes do Programa</b></legend>
		  <fieldset>
		    <legend>
	      	  <?=@$Lo54_problema ?>
		    </legend>
		    <table>
		      <tr>
		    	<td>
			   	  <?
db_textarea('o54_problema', 0, 75, $Io54_problema, true, 'text', $db_opcao, "")
             ?>
		        </td>
		      </tr>
		    </table>
		  </fieldset>
		  <fieldset>
		    <legend>
		       <?=@$Lo54_finali ?>
		    </legend>
		    <table>
			  <tr>
		    	<td>
				   <?
db_textarea('o54_finali', 0, 75, $Io54_finali, true, 'text', $db_opcao, "")
           ?>
			    </td>
			  </tr>
		    </table>
		  </fieldset>
		  <fieldset>
		    <legend>
			   <?=@$Lo54_publicoalvo ?>
		    </legend>
		    <table>
			  <tr>
			    <td>
				   <?
db_textarea('o54_publicoalvo', 0, 75, $Io54_publicoalvo, true, 'text', $db_opcao, "")
           ?>
			    </td>
			  </tr>
		    </table>
		  </fieldset>
		  <fieldset>
		    <legend>
	           <?=@$Lo54_justificativa ?>
		    </legend>
		    <table>
			  <tr>
			    <td>
				   <?
db_textarea('o54_justificativa', 0, 75, $Io54_justificativa, true, 'text', $db_opcao, "")
           ?>
			    </td>
			  </tr>
		    </table>
		  </fieldset>
		  <fieldset>
		    <legend>
	          <?=@$Lo54_objsetorassociado ?>
		    </legend>
		    <table>
			  <tr>
			    <td>
				   <?
db_textarea('o54_objsetorassociado', 0, 75, $Io54_objsetorassociado, true, 'text', $db_opcao, "")
           ?>
			    </td>
			  </tr>
		    </table>
		  </fieldset>
		  <fieldset>
		    <legend>
	          <?=@$Lo54_estrategiaimp ?>
		    </legend>
		    <table>
			  <tr>
			    <td>
				   <?
db_textarea('o54_estrategiaimp', 0, 75, $Io54_estrategiaimp, true, 'text', $db_opcao, "")
           ?>
			    </td>
			  </tr>
		    </table>
		  </fieldset>
</fieldset>

<input name=<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?> type="submit"  id="db_opcao" value="<?=($db_opcao
    == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" <?=($db_botao == false ? "disabled"
    : "") ?> >
<?if (empty($novo)) { ?>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<?} else { ?>
  <input name="Fechar" type="button"  id="fechar" value="Fechar" onClick="parent.db_iframe_orcprograma.hide();">
<?} ?>
</form>
</center>
<script>


/**
 * Toogle
 */
var oDBToogleDetalhesPrograma = new DBToogle('fieldsetDetalhesPrograma', false);
var oDBToogleDadosPrograma    = new DBToogle('fieldsetDadosGeraisPrograma', true);

oDBToogleDetalhesPrograma.afterClick = function () {
  oDBToogleDadosPrograma.show(false);
};

oDBToogleDadosPrograma.afterClick = function () {
  oDBToogleDetalhesPrograma.show(false);
};

function js_pesquisa(){
    js_OpenJanelaIframe('','db_iframe_orcprograma','func_orcprograma.php?db_instit=<?=db_getsession('DB_instit') ?>&funcao_js=parent.js_preenchepesquisa|o54_anousu|o54_programa','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_orcprograma.hide();
  <?
if ($db_opcao != 1) {
  echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])
      . "?chavepesquisa='+chave+'&chavepesquisa1='+chave1;";
}
  ?>
}
</script>
