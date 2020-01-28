<?php
/*
 *      E-cidade Software Publico para Gestao Municipal
 *   Copyright (C) 2014  DBSeller Servicos de Informatica
 *                             www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 * 
 *   Este programa e software livre; voce pode redistribui-lo e/ou
 *   modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versao 2 da
 *   Licenca como (a seu criterio) qualquer versao mais nova.
 * 
 *   Este programa e distribuido na expectativa de ser util, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *   COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *   PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *   detalhes.
 * 
 *   Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *   junto com este programa; se nao, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 * 
 *   Copia da licenca no diretorio licenca/licenca_en.txt
 *                 licenca/licenca_pt.txt
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_bairro_classe.php");

db_postmemory($_POST);
db_postmemory($_GET);

$oDaoBairro = new cl_bairro;
$oDaoBairro->rotulo->label("j13_codi");
$oDaoBairro->rotulo->label("j13_descr");
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
                <td width="4%" align="right" nowrap title="<?= $Tj13_codi ?>">
                  <?= $Lj13_codi ?>
                </td>
                <td width="96%" align="left" nowrap> 
                  <?php
                  db_input("j13_codi", 4, $Ij13_codi, true, "text", 4, "", "chave_j13_codi");
                  ?>
                </td>
              </tr>
              <tr> 
                <td width="4%" align="right" nowrap title="<?= $Tj13_descr ?>">
                  <?= $Lj13_descr ?>
                </td>
                <td width="96%" align="left" nowrap> 
                  <?php
                  db_input("j13_descr", 40, $Ij13_descr, true, "text", 4, "", "chave_j13_descr");
                  ?>
                </td>
              </tr>
              <tr> 
                <td colspan="2" align="center"> 
                  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
                  <input name="limpar" type="button" id="limpar" value="Limpar" onClick="js_limpar();">
                  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_bairrotriagem.hide();">
                </td>
              </tr>
            </form>
          </table>
        </td>
      </tr>
      <tr> 
        <td align="center" valign="top"> 
          <?php
          
          $sFiltraData = "";
          if ( !empty($dtInicio) && !empty($dtFim) ) {
            
            $oDtInicio   = new DBDate($dtInicio);
            $oDtFim      = new DBDate($dtFim);
            $sFiltraData = " and s152_d_datasistema between '{$oDtInicio->getDate()}' and '{$oDtFim->getDate()}' ";
          }
          
          $sCampos  = " bairro.* ";
          $aWhere   = array();
          $sFiltro  = " exists ( select 1 ";
          $sFiltro .= "            from sau_triagemavulsaagravo ";
          $sFiltro .= "                 inner join sau_triagemavulsa on s152_i_codigo = s167_sau_triagemavulsa ";
          $sFiltro .= "                 inner join cgs_und           on z01_i_cgsund  = s152_i_cgsund ";
          $sFiltro .= "           where z01_v_bairro = j13_descr ";
          if ( !empty($sFiltraData)) {
            $sFiltro .= "  {$sFiltraData} ";
          }
          $sFiltro .= "  ) ";
          $aWhere[] = $sFiltro;
          
          if (!isset($pesquisa_chave)) {
            
            if (isset($chave_j13_codi) && (trim($chave_j13_codi) != "")) {
              $aWhere[] = " j13_codi = {$chave_j13_codi} ";
            } else if (isset($chave_j13_descr) && (trim($chave_j13_descr) != "")) {
              $aWhere[] = " j13_descr like '$chave_j13_descr%' ";
            }
            
            $sSql = $oDaoBairro->sql_query("", $sCampos, "j13_descr", implode(" and ", $aWhere));
            db_lovrot($sSql, 15, "()", "", $funcao_js);
          } else {
            
            if ($pesquisa_chave != null && $pesquisa_chave != "") {
              
              $aWhere[] = " j13_codi = {$chave_j13_codi} ";
              $sSql     = $oDaoBairro->sql_query("", $sCampos, "j13_descr", implode(" and ", $aWhere));
              $rsSql    = $oDaoBairro->sql_record($sSql);
              if ($oDaoBairro->numrows != 0) {
                
                db_fieldsmemory($rsSql, 0);
                echo "<script>" . $funcao_js . "('$j13_descr',false);</script>";
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
    document.form2.chave_j13_codi.value = "";
    document.form2.chave_j13_descr.value = "";
  }
</script>