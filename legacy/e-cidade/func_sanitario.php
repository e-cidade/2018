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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_sanitario_classe.php");
require_once("classes/db_parfiscal_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clsanitario = new cl_sanitario;
$clparfiscal = new cl_parfiscal;
$clrotulo    = new rotulocampo;
$clsanitario->rotulo->label("y80_codsani");
$clrotulo->label("z01_nome");
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
            <td width="4%" align="right" nowrap title="<?=$Ty80_codsani?>">
              <?=$Ly80_codsani?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
    		       db_input("y80_codsani",10,$Iy80_codsani,true,"text",4,"","chave_y80_codsani");
		          ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
              <?=$Lz01_nome?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
		           db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","chave_z01_nome");
		          ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="Selecione 'Sim' para buscar somente as atividades principais, ou 'Não' para buscar todas as atividades">
              <strong>Atividade:</strong>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
			         $x=array('0'=>'Todas as atividades','1'=>'Somente atividade principal');
               if(!isset($Tatividade)){ $Tatividade=0; }
    		       db_select('Tatividade',$x,true,1);
    	        ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_sanitario.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php
      $result_param = $clparfiscal->sql_record($clparfiscal->sql_query_file());
      if ($clparfiscal->numrows>0){
  	   db_fieldsmemory($result_param,0);
      }

      if (isset($y32_sanidepto)&&$y32_sanidepto=='1' && @$tp != 'iss' ){
  	    $where=" y80_depto =".db_getsession("DB_coddepto");
      }else{
      	$where = " 1=1";
      }

		  if(!isset($Tatividade) || $Tatividade == 1){
		   $where .= "and y83_ativprinc = 'true' ";
		  }

        if (isset($lMostarTodos) && $lMostarTodos) {

          if (!isset($pesquisa_chave)) {

            $campos  = "distinct y80_codsani, y80_numbloco, y80_numcgm, z01_numcgm as db_z01_numcgm, z01_nome, j14_nome, ";
            $campos .= "y80_numero,y80_compl, y80_dtbaixa, y18_inscr, y83_dtini as dl_Data_de_Início                     ";
            if (isset($chave_y80_codsani) && (trim($chave_y80_codsani) != "")) {

              $sWhere = "y80_codsani = {$chave_y80_codsani} and {$where} ";
              $sql    = $clsanitario->sql_query_sem_ativ($chave_y80_codsani, $campos, "y80_codsani", $sWhere);
            } else if (isset($chave_z01_nome) && (trim($chave_z01_nome) != "")) {

              $sWhere = " cgm.z01_nome like '{$chave_z01_nome}%' and {$where} ";
              $sql    = $clsanitario->sql_query_sem_ativ("", $campos, "y80_numcgm", $sWhere);
            } else {

              $sql = $clsanitario->sql_query_sem_ativ("", $campos, "y80_codsani", $where);
            }

            db_lovrot($sql,15,"()","",$funcao_js);
          } else {

            if ($pesquisa_chave != null && $pesquisa_chave != "") {

              $sCampos       = " sanitario.y80_codsani,cgm.z01_nome,cgm.z01_numcgm ";
              $sWhere        = " y80_codsani = {$pesquisa_chave} and {$where} ";
              $sSqlSanitario = $clsanitario->sql_query_sem_ativ($pesquisa_chave, $sCampos, "", $sWhere);
              $result        = $clsanitario->sql_record($sSqlSanitario);
              if ($clsanitario->numrows != 0) {

                db_fieldsmemory($result,0);
                echo "<script>".$funcao_js."($y80_codsani,\"$z01_nome\",false,$z01_numcgm);</script>";
              } else {
                echo "<script>".$funcao_js."('','Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
              }
            } else {
              echo "<script>".$funcao_js."('','',false);</script>";
            }
          }
        } else {

		      if (!isset($pesquisa_chave)) {

		        $campos  = "distinct y80_codsani, y80_numbloco, y80_numcgm, z01_numcgm as db_z01_numcgm, z01_nome, j14_nome, ";
		        $campos .= "y80_numero,y80_compl, y80_dtbaixa, y18_inscr, y83_dtini as dl_Data_de_Início                     ";
		        if (isset($chave_y80_codsani) && (trim($chave_y80_codsani) != "")) {

		          $sWhere = "y80_codsani = {$chave_y80_codsani} and {$where} ";
		          $sql    = $clsanitario->sql_query_ativ($chave_y80_codsani, $campos, "y80_codsani", $sWhere);
		        } else if (isset($chave_z01_nome) && (trim($chave_z01_nome) != "")) {

		          $sWhere = " cgm.z01_nome like '{$chave_z01_nome}%' and {$where} ";
		          $sql    = $clsanitario->sql_query_ativ("", $campos, "y80_numcgm", $sWhere);
		        } else {

		          $sql = $clsanitario->sql_query_ativ("", $campos, "y80_codsani", $where);
		        }

		        db_lovrot($sql,15,"()","",$funcao_js);
		      } else {

		        if ($pesquisa_chave != null && $pesquisa_chave != "") {

		          $sCampos       = " sanitario.y80_codsani,cgm.z01_nome,cgm.z01_numcgm ";
		          $sWhere        = " y80_codsani = {$pesquisa_chave} and {$where} ";
		          $sSqlSanitario = $clsanitario->sql_query_ativ($pesquisa_chave, $sCampos, "", $sWhere);
		          $result        = $clsanitario->sql_record($sSqlSanitario);
		          if ($clsanitario->numrows != 0) {

		            db_fieldsmemory($result,0);
		            echo "<script>".$funcao_js."($y80_codsani,\"$z01_nome\",false,$z01_numcgm);</script>";
		          } else {
		            echo "<script>".$funcao_js."('','Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
		          }
		        } else {
		          echo "<script>".$funcao_js."('','',false);</script>";
		        }
		      }
        }
      ?>
     </td>
   </tr>
</table>
</body>
</html>