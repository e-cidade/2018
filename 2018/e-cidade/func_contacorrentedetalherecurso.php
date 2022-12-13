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
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$oGet  = db_utils::postMemory($_GET, 0);
$oPost = db_utils::postMemory($_POST, 0);

$oDaoOrcTipoRec = new cl_orctiporec();
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body class="body-default">
<div class="container">
  <table>
    <tr>
      <td>
        <table width="35%" border="0" align="center" cellspacing="0">
          <form name="form2" method="post" action="" >
            <tr>
              <td width="4%" align="right" nowrap title="Código">
                <label for="o15_codigo" class="bold">Código:</label>
              </td>
              <td width="96%" align="left" nowrap>
                <?
                $So15_codigo = "Código";
                db_input("o15_codigo",10,1,true,"text",4,"");
                ?>
              </td>
            </tr>
            <tr>
              <td width="4%" align="right" nowrap title="Descrição">
               <label for="o15_descr" class="bold">Descrição</label>
              </td>
              <td width="96%" align="left" nowrap>
                <?
                $So15_descr = "Descrição";
                db_input("o15_descr",50,0,true,"text",4,"");
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                <input name="limpar" type="reset" id="limpar" value="Limpar" >
                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_contacorrentedetalherecurso.hide();">
              </td>
            </tr>
          </form>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" valign="top">
        <?php
        $iAnoUsu = db_getsession("DB_anousu");
        $aWhere = array();
        if (isset($oGet->iReduzido)) {
          $aWhere[] = " c19_reduz = {$oGet->iReduzido} ";
        }

        $aWhere[] = " c19_conplanoreduzanousu = {$iAnoUsu} ";

        $sCampos = "distinct o15_codigo, o15_descr";

        if (!isset($oGet->pesquisa_chave)) {

          if (!empty($oPost->o15_codigo)){
            $aWhere[] = " o15_codigo = {$oPost->o15_codigo} ";
          }

          if (!empty($oPost->o15_descr)){
            $aWhere[] = " o15_descr ilike '%{$oPost->o15_descr}%' ";
          }

          $sWhere = implode(" AND ", $aWhere);
          $sql    = $oDaoOrcTipoRec->sql_query_contacorrentedetalhe($sCampos, $sWhere);
          db_lovrot($sql, 15, "()", "", $funcao_js);
        } else {

          //Busca pela chave sequencial
          if (!empty($oGet->pesquisa_chave)) {

            $aWhere[] = " o15_codigo = {$oGet->pesquisa_chave} ";
            $sWhere = implode(" AND ", $aWhere);
            $rsOrcTipoRec = $oDaoOrcTipoRec->sql_record($oDaoOrcTipoRec->sql_query_contacorrentedetalhe($sCampos, $sWhere));
            if ($oDaoOrcTipoRec->numrows != 0) {

              db_fieldsmemory($rsOrcTipoRec, 0);
              echo "<script>".$funcao_js."('$o15_descr', false);</script>";
            } else {
              echo "<script>".$funcao_js."('Chave(".$oGet->pesquisa_chave.") não Encontrado',true);</script>";
            }
          } else {
            echo "<script>".$funcao_js."('', '', '', false);</script>";
          }
        }
        ?>
      </td>
    </tr>
  </table>
</div>
</body>
</html>