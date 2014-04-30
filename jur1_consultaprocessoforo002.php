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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/verticalTab.widget.php");
require_once("classes/db_processoforo_classe.php");
require_once("classes/db_processoforomov_classe.php");
require_once("classes/db_parjuridico_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clprocessoforo    = new cl_processoforo;
$clprocessoforomov = new cl_processoforomov;
$clparjuridico     = new cl_parjuridico;
$clprocessoforo->rotulo->label();

$lPartilha = false;

$sWhere           = "v70_sequencial = {$oGet->v70_sequencial}";
$sSqlProcessoForo = $clprocessoforo->sql_query_cgm_nome(null, " * ", null, $sWhere,true);
$rsProcessoForo   = $clprocessoforo->sql_record($sSqlProcessoForo);
if ($clprocessoforo->numrows > 0) {
	db_fieldsmemory($rsProcessoForo, 0);
}

$rsParJuridico = $clparjuridico->sql_record($clparjuridico->sql_query_file(db_getsession("DB_anousu"), db_getsession("DB_instit"), "v19_partilha"));
$lPartilha = db_utils::fieldsMemory($rsParJuridico, 0)->v19_partilha;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js");
  db_app::load("widgets/windowAux.widget.js,messageboard.widget.js");
  db_app::load("estilos.css, grid.style.css,tab.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<center>
<form name="form1" method="post">
<table width="100%" style="margin-top: 20px;" align="center">
  <tr>
	  <td>
		  <fieldset>
		    <legend>
		      <b>Processo do Sistema [<?=@$v70_sequencial?>]</b>
		    </legend>
		    <table cellpadding="2" cellspacing="2">
		      <tr>
		        <td align="left" title="<?@$Tv70_codforo?>" width="200px">
		          <?=@$Lv70_codforo?>
		        </td>
		        <td style="background-color: #FFFFFF;" align="left" width="300px">
		          <?=$v70_codforo?> 
		        </td>
		        <td>&nbsp;</td>
		        <td align="left" title="<?@$Tv70_processoforomov?>" width="200px">
		          <b>Movimentação Atual:</b>
		        </td>
		        <td style="background-color: #FFFFFF;" align="left" width="300px">
		          <?
		          $sSqlProcessoForoMov = $clprocessoforomov->sql_record($clprocessoforomov->sql_query(null," v74_descricao "," v73_data desc limit 1 "," v73_processoforo = $v70_sequencial "));
              if ( $sSqlProcessoForoMov != false && pg_numrows($sSqlProcessoForoMov)>0){
                db_fieldsmemory($sSqlProcessoForoMov,0);
                echo $v74_descricao;
              }
		          ?>
		        </td>
		      </tr>
		      <tr> 
		        <td align="left" title="Nome do Responsável na Ação" width="200px">
		          <b>Contribuinte:</b>
		        </td>
		        <td style="background-color: #FFFFFF;" align="left" width="300px">
		          <?=@$dl_nome?>
		        </td>
		        <td>&nbsp;</td>
		        <td  align="left" title="<?@$Tv70_vara?>" width="200px">
		          <b>Vara:</b>
		        </td>
		        <td style="background-color: #FFFFFF;" align="left" width="300px">
		          <?=@$v70_vara."-".$v53_descr?>
		        </td>
		      </tr>
          <tr> 
            <td align="left" title="<?@$Tv70_data?>" width="200px">
              <b>Data:</b>
            </td>
            <td style="background-color: #FFFFFF;" align="left" width="300px">
              <?=db_formatar(@$v70_data, 'd')?>
            </td>
            <td>&nbsp;</td>
            <td  align="left" title="<?@$Tv70_anulado?>" width="200px">
              <b>Anulada:</b>
            </td>
            <td style="background-color: #FFFFFF;" align="left" width="300px">
              <?=@$v70_anulado=='f'?"Não":"Sim"?>
            </td>
          </tr>
          <tr> 
            <td align="left" title="<?@$Tv70_valorinicial?>" width="200px">
              <?=@$Lv70_valorinicial?>
            </td>
            <td style="background-color: #FFFFFF;" align="left" width="300px">
              <?=db_formatar(@$v70_valorinicial, 'f')?>
            </td>
            <td>&nbsp;</td>
            <td  align="left" title="<?@$Tv70_observacao?>" width="200px">
              <?=@$Lv70_observacao?>
            </td>
            <td style="background-color: #FFFFFF;" align="left" width="300px">
              <?=@$v70_observacao?>
            </td>
          </tr>
		    </table>
		  </fieldset>    
	  </td>
  </tr>
</table>
  <fieldset>
    <legend>
      <b>Outras Informações</b>
    </legend>
	  <?
	    $oTabDetalhes = new verticalTab("detalhesprocessoforo",300);
	    $oTabDetalhes->add("iniciaisvinculadas","Iniciais Vinculadas",
	                       "func_processoforoinicial.php?v71_processoforo={$v70_sequencial}&detalhamento=true&funcao_js=parent.js_consultaInicial|v71_inicial");
	    $oTabDetalhes->add("movimentacoes","Movimentações",
	                       "func_processoforomov.php?v73_processoforo={$v70_sequencial}&detalhamento=true");
      $oTabDetalhes->add("nomesenvolvidos","Nomes Envolvidos",
                         "jur1_consultaprocessoforo003.php?v70_sequencial={$v70_sequencial}&detalhamento=true");
      if ($lPartilha) {
        $oTabDetalhes->add("custas","Custas e Taxas Judiciais",
                           "jur1_consultaprocessoforopartilhacusta.php?v70_sequencial={$v70_sequencial}&detalhamento=true");
      }
	    $oTabDetalhes->show();
	  ?>
  </fieldset>
</form>
</center>
</body>
<script>
function js_consultaInicial(iInicial) {

  var sUrl = 'func_inicialmovcert.php?v50_inicial='+iInicial+'&funcao_js=parent.js_oculta';
  js_OpenJanelaIframe('top.corpo','db_iframe_inicialmovcert', sUrl, 'Dados da Inicial',true);
}
</script>
</html>