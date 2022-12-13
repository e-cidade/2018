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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_matmater_classe.php");

db_postmemory($_POST);
parse_str( $_SERVER["QUERY_STRING"] );

$iModulo = db_getsession( "DB_modulo" );

$clmatmater = new cl_matmater;
$clmatmater->rotulo->label("m60_codmater");
$clmatmater->rotulo->label("m60_descr");
$Lm60_codmater = "<b>Código do Material:</b>";
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
      <form name="form2" method="post" action="" >
        <table width="35%" border="0" align="center" cellspacing="0">
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tm60_codmater?>">
              <label for="chave_m60_codmater"><?=$Lm60_codmater?></label>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
              db_input( "m60_codmater", 10, $Im60_codmater, true, "text", 4, "", "chave_m60_codmater" );
      		    ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tm60_descr?>">
              <label for="chave_m60_descr" class="bold"> Material:</label>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
              db_input( "m60_descr", 40, $Im60_descr, true, "text", 4, "", "chave_m60_descr" );
    		      ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_matmater.hide();">
             </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php
      $aWhere = array();

      if( $iModulo == 1000004  ) {
        $aWhere[] = "exists( select 1 from far_matersaude where far_matersaude.fa01_i_codmater = m60_codmater )";
      }

      if (isset($campos) == false) {

        if (file_exists("funcoes/db_func_matmater.php") == true) {
          include("funcoes/db_func_matmater.php");
        } else {
          $campos = "matmater.*";
        }
      }

      if ( isset($lOutrosCampos) ) {

        $campos  = " matmater.m60_codmater, m60_descr, m60_codmatunid as DB_m60_codmatunid, m61_descr,";
        $campos .= " matmater.m60_quantent, matmater.m60_codant, matmater.m60_controlavalidade";
      }

      if( !isset( $pesquisa_chave) ) {

        if( isset( $chave_m60_codmater ) && ( trim( $chave_m60_codmater ) != "" ) ) {
          $aWhere[] = "m60_codmater = {$chave_m60_codmater}";
        }

        if( isset( $chave_m60_descr ) && ( trim( $chave_m60_descr ) != "" ) ) {
          $aWhere[] = "m60_descr ilike '{$chave_m60_descr}%'";
        }

        $sWhere = implode( ' AND ', $aWhere );
        $sSql   = $clmatmater->sql_query( null, $campos, "m60_codmater", $sWhere );

        db_lovrot( $sSql, 15, "()", "", $funcao_js );
      } else {

        if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

          $aWhere[] = "m60_codmater = {$pesquisa_chave}";
          $sWhere = implode(" and ", $aWhere);
          $result = $clmatmater->sql_record( $clmatmater->sql_query(null, "*", null, $sWhere) );

          if( $clmatmater->numrows != 0 ) {

            db_fieldsmemory( $result, 0 );
            $m60_descr = addslashes( $m60_descr );
            echo "<script>".$funcao_js."('".substr($m60_descr,0,40)."',false, '$m61_descr');</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado', true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>