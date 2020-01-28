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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_acordo_classe.php");
$lAtivo      = '';
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clacordo = new cl_acordo;
$clacordo->rotulo->label("ac16_sequencial");
$clacordo->rotulo->label("ac16_acordogrupo");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js");
db_app::load("dbmessageBoard.widget.js, prototype.js, contratos.classe.js");
db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
      <form name="form2" method="post" action="" >
        <table border="0" align="center" cellspacing="0">
          <tr>
            <td width="4%" align="right" nowrap title="<?php echo $Tac16_sequencial; ?>">
              <?php echo $Lac16_sequencial; ?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
                db_input("ac16_sequencial",10,$Iac16_sequencial,true,"text",4,"","chave_ac16_sequencial");
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo @$Tac16_acordogrupo; ?>">
              <?php
                db_ancora(@$Lac16_acordogrupo, "js_pesquisaac16_acordogrupo(true);", 1);
              ?>
            </td>
            <td>
              <?php
                db_input('ac16_acordogrupo', 10, $Iac16_acordogrupo, true, 'text', 1, "onchange='js_pesquisaac16_acordogrupo(false);'");
                db_input('ac02_descricao', 30, "", true, 'text', 3);
              ?>
            </td>
          </tr>

          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_acordo.hide();">
             </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php

      $sWhere  = " 1 = 1 ";
      
      if (!isset($lNovoDetalhe)) {

	      if (!isset($lDepartamento)) {
	
	        $sWhere .= " and ( ac16_coddepto = ".db_getsession("DB_coddepto");
	        $sWhere .= "  or ac16_deptoresponsavel = ".db_getsession("DB_coddepto")." ) ";
	      }
	
	      if (isset($iTipoFiltro)) {
	        $sWhere .= " and ac16_acordosituacao in (4) ";
	      }
	      
	      if ( !empty($lLancamento) ) {
	        $sWhere .= " and exists (select 1 from conlancamacordo where c87_acordo = ac16_sequencial limit 1) ";
	      } 
	
	      /**
	       * Caso tenha sido setado $lComExecucao como false, buscamos os acordos que nao tiveram item executado
	       */
	      if (isset($lComExecucao) && $lComExecucao == 'false') {
	
	        $sWhere .= " and not exists (select 1 from acordoitemexecutadoperiodo     aitemexecutadoperiodo
	                                                   inner join acordoitemexecutado aitemexecutado on aitemexecutado.ac29_sequencial = aitemexecutadoperiodo.ac38_acordoitemexecutado
	                                                   inner join acordoitemprevisao  aitemprevisao  on aitemprevisao.ac37_sequencial  = aitemexecutadoperiodo.ac38_acordoitemprevisao
	                                                   inner join acordoitem          aitem          on aitem.ac20_sequencial          = aitemprevisao.ac37_acordoitem
	                                        where aitemprevisao.ac37_acordoitem = acordoitem.ac20_sequencial
	                                    )";
	      }
	
	      if (isset($lAtivo)) {
	
	        if ($lAtivo == 1) {
	          $sWhere .= " and ac17_ativo is true";
	        } else if ($lAtivo == 2) {
	          $sWhere .= " and ac17_ativo is false";
	        }
	      }
	
	      if (isset($lGeraAutorizacao) && $lGeraAutorizacao == "true") {
	        $sWhere .= " and ac16_origem in(1, 2, 6) ";
	      }
	
	      if (isset($sListaOrigens) && !empty($sListaOrigens)) {
	        $sWhere .= " and ac16_origem in({$sListaOrigens}) ";
	      }
      
      
      }

      if (!isset($pesquisa_chave)) {

        if (isset($campos) == false) {

          if (file_exists("funcoes/db_func_acordo.php") == true) {

            $campos  = "distinct acordo.ac16_sequencial, ac17_descricao as dl_Situação , acordo.ac16_coddepto";
            $campos .= ",descrdepto,codigo, nomeinst"; 
            
            $campos .= ", acordo.ac16_numero, acordo.ac16_dataassinatura, acordo.ac16_contratado";
            $campos .= ", acordo.ac16_datainicio, acordo.ac16_datafim, acordo.ac16_resumoobjeto::text";
            $campos .= ", ac28_descricao as dl_Origem";
           } else {
             $campos = "acordo.*";
           }
        }

        if (isset($chave_ac16_sequencial) && (trim($chave_ac16_sequencial)!="")) {

           $sql = $clacordo->sql_query_acordoReativado(null, $campos,"ac16_sequencial",
                                       "ac16_sequencial = {$chave_ac16_sequencial} and $sWhere");

        } else if (isset($ac16_acordogrupo) && (trim($ac16_acordogrupo)!="")) {

           $sql = $clacordo->sql_query_acordoReativado("",$campos,"ac16_sequencial",
                                       "ac16_acordogrupo = '{$ac16_acordogrupo}' and {$sWhere}");
        } else {
           $sql = $clacordo->sql_query_acordoReativado("",$campos,"ac16_sequencial", $sWhere);
        }

        $repassa = array();
        
        if (isset($chave_ac16_sequencial)) {
          $repassa = array("chave_ac16_sequencial"=>$chave_ac16_sequencial,"chave_ac16_sequencial"=>$chave_ac16_sequencial);
        }
        
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != "") {

          $sSqlBuscaAcordo = $clacordo->sql_query_acordoReativado(null,
                                                                      "*",
                                                                      null,
                                                                      "ac16_sequencial = {$pesquisa_chave}
                                                                       and {$sWhere}");


          $result = $clacordo->sql_record($sSqlBuscaAcordo);

          if ($clacordo->numrows != 0) {

            db_fieldsmemory($result,0);
            if (isset($descricao) && $descricao == 'true') {
              echo "<script>".$funcao_js."('$ac16_sequencial','$ac16_resumoobjeto',false);</script>";
            } else {
              echo "<script>".$funcao_js."('$ac16_sequencial',false);</script>";
            }
          } else {

            if (isset($descricao) && $descricao == 'true') {
              echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','',true);</script>";
            } else {
              echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
            }
          }
        } else {

          if (isset($descricao) && $descricao == 'true') {
            echo "<script>".$funcao_js."('','',false);</script>";
          } else {
            echo "<script>".$funcao_js."('',false);</script>";
          }
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?php
if (!isset($pesquisa_chave)) {
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_ac16_sequencial",true,1,"chave_ac16_sequencial",true);


function js_pesquisaac16_acordogrupo(mostra) {

	  if (mostra == true) {

	    var sUrl = 'func_acordogrupo.php?funcao_js=parent.js_mostraacordogrupo1|ac02_sequencial|ac02_descricao';
	    js_OpenJanelaIframe('',
	                        'db_iframe_pesquisagrupo',
	                        sUrl,
	                        'Pesquisar Grupos de Acordo',
	                        true,
	                        '0');
	  } else {

	    if ($('ac16_acordogrupo').value != '') {

	      js_OpenJanelaIframe('',
	                          'db_iframe_pesquisagrupo',
	                          'func_acordogrupo.php?pesquisa_chave='+$('ac16_acordogrupo').value+
	                          '&funcao_js=parent.js_mostraacordogrupo',
	                          'Pesquisar Grupos de Acordo',
	                          false,
	                          '0');
	     } else {
	       $('ac02_sequencial').value = '';
	     }
	  }
	}

	function js_mostraacordogrupo(chave,erro) {

	  $('ac02_descricao').value = chave;
	  if (erro == true) {

	    $('ac16_acordogrupo').focus();
	    $('ac16_acordogrupo').value = '';
	  }
	}

	function js_mostraacordogrupo1(chave1,chave2) {

	  $('ac16_acordogrupo').value = chave1;
	  $('ac02_descricao').value   = chave2;
	  $('ac16_acordogrupo').focus();

	  db_iframe_pesquisagrupo.hide();
	}
</script>