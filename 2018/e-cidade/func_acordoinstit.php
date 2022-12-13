<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
require_once ("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_acordo_classe.php");

$iTipoFiltro = 0;
$lAtivo      = '';
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clacordo = new cl_acordo;
$clacordo->rotulo->label("ac16_sequencial");
$clacordo->rotulo->label("ac16_numeroacordo");
$clacordo->rotulo->label("ac16_acordogrupo");

$oGet = db_utils::postMemory($_GET);
$iInstituicaoSessao = db_getsession('DB_instit');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js");
db_app::load("dbmessageBoard.widget.js, prototype.js, contratos.classe.js");
db_app::load("estilos.css, grid.style.css");
?>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
      <form name="form2" method="post" action="" >
        <table border="0" align="center" cellspacing="0">

          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tac16_sequencial?>">
              <?=$Lac16_sequencial?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php db_input("ac16_sequencial",10,$Iac16_sequencial,true,"text",4,"","chave_ac16_sequencial"); ?>
            </td>
          </tr>

          <tr>
            <td width="4%" align="right" nowrap title="<?php echo $Tac16_numeroacordo; ?>">
              <?php echo $Lac16_numeroacordo; ?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php db_input("ac16_numeroacordo", 10, 0, true, "text", 4); ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo $Tac16_acordogrupo; ?>">
              <?php db_ancora($Lac16_acordogrupo, "js_pesquisaac16_acordogrupo(true);", 1); ?>
            </td>
            <td>
              <?
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


      $aWhereAdicional = array();
      $aWhereAdicional[] = "ac16_instit = {$iInstituicaoSessao}";
      if (!empty($oGet->iCodigoCategoria)) {
        $aWhereAdicional[] = "ac16_acordocategoria = {$oGet->iCodigoCategoria}";
      }

      if(!isset($pesquisa_chave)) {

        if (isset($campos) == false) {

          if (file_exists("funcoes/db_func_acordo.php") == true) {
             include("funcoes/db_func_acordo.php");
           } else {
             $campos = "acordo.*";
           }
        }

        if (isset($chave_ac16_sequencial) && (trim($chave_ac16_sequencial)!="")) {
           $aWhereAdicional[] = "ac16_sequencial = {$chave_ac16_sequencial}";
        } else if (isset($ac16_acordogrupo) && (trim($ac16_acordogrupo)!="")) {
           $aWhereAdicional[] = "ac16_acordogrupo = '{$ac16_acordogrupo}'";
        }

        /**
         * Numero e ano do acordo - separados por '/', caso nao for informado ano, pega da sessao
         */
        if (!empty($ac16_numeroacordo)) {

          $aNumeroAcordo = explode('/', $ac16_numeroacordo);
          $iNumero = $aNumeroAcordo[0];
          $iAno = !empty($aNumeroAcordo[1]) ? $aNumeroAcordo[1] : db_getsession("DB_anousu");

          $aWhereAdicional[] = "ac16_numeroacordo = $iNumero";
          $aWhereAdicional[] = "ac16_anousu = $iAno";
        }

        $sql = $clacordo->sql_query("",$campos,"ac16_sequencial", implode(" and ", $aWhereAdicional));

        $repassa = array();
        if (isset($chave_ac16_sequencial)) {
          $repassa = array("chave_ac16_sequencial"=>$chave_ac16_sequencial,"chave_ac16_sequencial"=>$chave_ac16_sequencial);
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {


        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          $aWhereAdicional[] = "ac16_sequencial = {$pesquisa_chave}";
          $result = $clacordo->sql_record($clacordo->sql_query(null,
                                                               "*",
                                                               null,
                                                               implode(" and ", $aWhereAdicional)));
          if($clacordo->numrows!=0){
            db_fieldsmemory($result,0);
            if (isset($descricao) && $descricao == 'true') {

              if (isset($isLancador) && $isLancador == "true") {
                echo "<script>".$funcao_js."('$ac16_resumoobjeto',false);</script>";
              } else {
                echo "<script>".$funcao_js."('$ac16_sequencial','$ac16_resumoobjeto', false, '$ac16_origem');</script>";
              }
            } else {
              echo "<script>".$funcao_js."('$ac16_sequencial',false);</script>";
            }
          }else{
            if (isset($descricao) && $descricao == 'true') {
              echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','',true);</script>";
            } else {
              echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
            }
          }
        }else{
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
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>

/**
 * Formata numero / ano de um elemento html
 *
 * @param {object} elemento
 * @returns {void}
 */
function js_formatarNumeroAno(elemento) {

  /**
   * Formata a cada tecla digitada
   *
   * @param {object} elemento
   * @returns {boolean}
   */
  elemento.onkeypress = function(event) {

    var iTecla = event.keyCode ? event.keyCode : event.charCode;
    var sTecla = String.fromCharCode(iTecla);

    /**
     * Nao permite por 2x '/' ou como primeiro caracter
     */
    if (sTecla == '/') {

      if (this.value.indexOf('/') !== -1 || this.value == '') {
        return false;
      }
    }

    return js_mask(event, "0-9|/");
  };
}

js_formatarNumeroAno(document.getElementById('ac16_numeroacordo'));

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
