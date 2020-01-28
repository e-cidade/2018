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
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($_POST);
db_postmemory($_GET);

/**
 * Essa função foi desenvolvida para listar somente os agravos vinculados a uma triagem
 */

$oDaoAgravo = new cl_sau_triagemavulsaagravo();
$oDaoSauCid = new cl_sau_cid();
$oDaoSauCid->rotulo->label("sd70_i_codigo");
$oDaoSauCid->rotulo->label("sd70_c_cid");
$oDaoSauCid->rotulo->label("sd70_c_nome");
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
      <tr>
        <td height="63" align="center" valign="top">
          <table width="35%" border="0" align="center" cellspacing="0">
            <form name="form2" method="post" action="" >
              <tr>
                <td width="4%" align="right" nowrap title="<?= $Tsd70_i_codigo ?>">
                  <?= $Lsd70_i_codigo ?>
                </td>
                <td width="96%" align="left" nowrap>
                  <?php
                  db_input("sd70_i_codigo", 5, $Isd70_i_codigo, true, "text", 4, "", "chave_sd70_i_codigo");
                  ?>
                </td>
              </tr>
              <tr>
                <td width="4%" align="right" nowrap title="<?= $Tsd70_c_nome ?>">
                  <?= $Lsd70_c_nome ?>
                </td>
                <td width="96%" align="left" nowrap>
                  <?php
                  db_input("sd70_c_nome", 60, $Isd70_c_nome, true, "text", 4, "", "chave_sd70_c_nome");
                  ?>
                </td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                  <input name="limpar" type="button" id="limpar" value="Limpar" onClick="js_limpar();">
                  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="js_fechar('<?= @$campoFoco ?>');">
                </td>
              </tr>
            </form>
          </table>
        </td>
      </tr>
      <tr>
        <td align="center" valign="top">
          <?php
          
          $sCampos = " distinct sd70_i_codigo, sd70_c_nome ";
          $aWhere  = array();
          
          if ( !empty($dtInicio) && !empty($dtFim) ) {
            
            $oDtInicio = new DBDate($dtInicio);
            $oDtFim    = new DBDate($dtFim);
            $aWhere[]  = "  s152_d_datasistema between '{$oDtInicio->getDate()}' and '{$oDtFim->getDate()}' ";
          }
          
          if (!isset($pesquisa_chave)) {

            if (isset($chave_sd70_i_codigo) && (trim($chave_sd70_i_codigo) != "")) {
              $aWhere[] = " sd70_i_codigo = {$chave_sd70_i_codigo}";
            } else if (isset($chave_sd70_c_nome) && (trim($chave_sd70_c_nome) != "")) {
              $aWhere[] = " sd70_c_nome like '$chave_sd70_c_nome%' ";
            } 
            $sWhere = implode(" and ", $aWhere);
            $sSql   = $oDaoAgravo->sql_query("", $sCampos, "sd70_c_nome", $sWhere);
            
            $repassa = array();
            if (isset($chave_sd70_c_nome)) {
              $repassa = array("chave_sd70_i_codigo" => $chave_sd70_i_codigo, "chave_sd70_c_nome" => $chave_sd70_c_nome);
            }
            db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
          } else {
            
            if ($pesquisa_chave != null && $pesquisa_chave != "") {
              
              $aWhere[] = "sd70_i_codigo = {$pesquisa_chave} ";
              $sWhere   = implode(" and ", $aWhere);
              $result   = $oDaoAgravo->sql_record($oDaoAgravo->sql_query(null, $sCampos, null, $sWhere));
              
              if ($oDaoAgravo->numrows != 0) {
                db_fieldsmemory($result, 0);
                echo "<script>" . $funcao_js . "('$sd70_c_nome',false);</script>";
              } else {
                echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado',true);</script>";
              }
            } else {
              echo "<script>" . $funcao_js . "('',false);</script>";
            }
          }
          ?>
        </td>
      </tr>
    </table>
  </body>
</html>
<script>
  function js_limpar() {
    document.form2.chave_sd70_i_codigo.value = "";
    document.form2.chave_sd70_c_cid.value    = "";
    document.form2.chave_sd70_c_nome.value   = "";
  }
  js_tabulacaoforms("form2", "chave_sd70_c_nome", true, 1, "chave_sd70_c_nome", true);


  /**
   * Botoão Fechar
   * campoFoco = foco de retorno quando fechar
   */
  function js_fechar(campoFoco) {
    if (campoFoco != undefined && campoFoco != '') {

      eval("parent.document.getElementById('" + campoFoco + "').focus(); ");
      eval("parent.document.getElementById('" + campoFoco + "').select(); ");
    }
    parent.db_iframe_sauagravaotriagem.hide();
  }

</script>