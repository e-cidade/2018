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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oDaoVeiculos             = db_utils::getdao('veiculos');
$oDaoVeicCadCentralDepart = db_utils::getdao('veiccadcentraldepart');

$oDaoVeiculos->rotulo->label("ve01_codigo");
$oDaoVeicCadCentralDepart->rotulo->label("ve37_veiccadcentral");

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
          <table width="23%" border="0" align="center" cellspacing="0">
	          <form name="form1" method="post" action="" >
              <tr>
                <td width="4%" align="right" nowrap title="<?=$Tve01_codigo?>">
                  <?=$Lve01_codigo?>
                </td>
                <td width="96%" align="left" nowrap>
                  <?
		                db_input("ve01_codigo", 10, $Ive01_codigo, true, "text", 4, "", "chave_ve01_codigo");
		              ?>
                </td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                  <input name="limpar" type="reset" id="limpar" value="Limpar" >
                  <input name="Fechar" type="button" id="fechar" value="Fechar"
                         onClick="parent.db_iframe_veiculos.hide();">
                </td>
              </tr>
            </form>
          </table>
        </td>
      </tr>
      <tr>
        <td align="center" valign="top">
          <?
            $sWhere = "";

            if (isset($baixa) && $baixa != "") {
      	      $sWhere = " ve01_ativo = '$baixa' ";
            } else {
      	      $sWhere = " ve01_ativo = '1' ";
			      }

            if (trim($sWhere) != "") {
              $sWhere .= "and";
            }

            if (isset($chave_ve37_sequencial)
                && trim($chave_ve37_sequencial) != ""
                && $chave_ve37_sequencial != "0") {

              $sWhere .= " ve37_sequencial = $chave_ve37_sequencial ";

            } else {

              /* Descubro a central deste departamento */
              $sWhereCentral = " ve37_coddepto = ".db_getsession("DB_coddepto");
              $sSqlCentral   = $oDaoVeicCadCentralDepart->sql_query("",
                                                                    "ve37_veiccadcentral",
                                                                    "",
                                                                    $sWhereCentral
                                                                   );
              $rsCentral     = $oDaoVeicCadCentralDepart->sql_record($sSqlCentral);

              /* Se encontrou resultado */
              if ($oDaoVeicCadCentralDepart->numrows > 0) {

                $sIn        = "";
                $sConcatena = "";

                /* Adiciona as centrais encontradas no IN do where */
                for ($iCont = 0; $iCont < $oDaoVeicCadCentralDepart->numrows; $iCont++) {

                  $oDados     = db_utils::fieldsmemory($rsCentral, $iCont);
                  $sIn       .= $sConcatena.$oDados->ve37_veiccadcentral;
                  $sConcatena = ", ";

                }

                $sWhere .= " ve36_sequencial in($sIn) ";

              } else {
                $sWhere .= "  ve36_coddepto = ".db_getsession("DB_coddepto")." ";
              }

            }

            $sCampos  = "distinct ve01_codigo,ve01_placa,ve20_descr,ve21_descr,ve22_descr,ve23_descr,ve01_chassi, ";
            $sCampos .= "ve01_certif,ve01_anofab,ve01_anomod, ve01_quantcapacidad ";
            if ( !isset($pesquisa_chave) ) {

              if (isset($chave_ve01_codigo) && (trim($chave_ve01_codigo) != "")) {

        	      if ($sWhere != "") {
           		    $sWhere = " and ".$sWhere;
           	    }

                $sSql = $oDaoVeiculos->sql_query_central($chave_ve01_codigo,
                                                         $sCampos, "ve01_codigo",
                                                         "ve01_codigo=$chave_ve01_codigo $sWhere"
                                                        );

              } else {
                $sSql = $oDaoVeiculos->sql_query_central("", $sCampos, "ve01_codigo", $sWhere);
              }

              $repassa = array();

              if (isset($chave_ve01_codigo)) {
                $repassa = array(
                                  "chave_ve01_codigo" => $chave_ve01_codigo,
                                  "chave_ve01_codigo" => $chave_ve01_codigo
                                );
              }

              db_lovrot($sSql,15,"()","",$funcao_js,"","NoMe",$repassa,false);

            } else {

              if ($pesquisa_chave != null && $pesquisa_chave != "") {

                if ($sWhere != "") {
           	      $sWhere = " and ".$sWhere;
                }

                $sSqlVeiculos = $oDaoVeiculos->sql_query_central(null,
                                                                 $sCampos,
                                                                 null,
                                                                 "ve01_codigo = $pesquisa_chave $sWhere"
                                                                );
                $result = $oDaoVeiculos->sql_record($sSqlVeiculos);

                if (isset($iParam) && $iParam == 1 && $oDaoVeiculos->numrows != 0) {

                  db_fieldsmemory($result, 0);
                  echo "<script>".$funcao_js."('$ve01_placa', '$ve01_quantcapacidad', false);</script>";

                } elseif(isset($iParam) && $iParam == 1 && $oDaoVeiculos->numrows == 0) {
                  echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado', '', true);</script>";
                } elseif ($oDaoVeiculos->numrows != 0) {

                  db_fieldsmemory($result, 0);
                  echo "<script>".$funcao_js."('$ve01_placa',false);</script>";

                } else {
	                echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
                }

              } else {
	              echo "<script>".$funcao_js."('',false);</script>";
              }

            }
          ?>
        </td>
      </tr>
    </table>
  </body>
</html>
