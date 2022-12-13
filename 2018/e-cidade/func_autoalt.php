<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_auto_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clauto = new cl_auto;
$clauto->rotulo->label("y50_codauto");
$clauto->rotulo->label("y50_nome");
$clrotulo = new rotulocampo;
$clrotulo->label("z01_numcgm");
$clrotulo->label("y80_codsani");
$clrotulo->label("q02_inscr");
$clrotulo->label("j01_matric");
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
            <td width="4%" align="right" nowrap title="<?=$Ty50_codauto?>"><?=$Ly50_codauto?></td>
            <td width="96%" align="left" nowrap>
              <? db_input("y50_codauto",10,$Iy50_codauto,true,"text",4,"","chave_y50_codauto"); ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tz01_numcgm?>"><?=$Lz01_numcgm?></td>
            <td width="96%" align="left" nowrap>
              <? db_input("z01_numcgm",10,$Iz01_numcgm,true,"text",4,"","chave_z01_numcgm"); ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tj01_matric?>"><?=$Lj01_matric?></td>
            <td width="96%" align="left" nowrap>
              <? db_input("j01_matric",10,$Ij01_matric,true,"text",4,"","chave_j01_matric"); ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tq02_inscr?>"><?=$Lq02_inscr?></td>
            <td width="96%" align="left" nowrap>
              <? db_input("q02_inscr",10,$Iq02_inscr,true,"text",4,"","chave_q02_inscr"); ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Ty80_codsani?>"><?=$Ly80_codsani?></td>
            <td width="96%" align="left" nowrap>
              <? db_input("y80_codsani",10,$Iy80_codsani,true,"text",4,"","chave_y80_codsani"); ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="Notificação"><b>Notificação</b></td>
            <td width="96%" align="left" nowrap>
              <? db_input("y30_codnoti",10,@$y30_codnoti,true,"text",4,"","chave_y30_codnoti"); ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      if(!isset($pesquisa_chave)){

		$where = "";
		if (isset($db_opcao) && ($db_opcao == 3 || $db_opcao == 33)) {
		  $where = " and not exists (select 1 from autonumpre where y17_codauto = dl_Auto) ";
		}

        if(isset($chave_y50_codauto) && (trim($chave_y50_codauto)!="") ){
		     $sql = $clauto->sql_query_busca($chave_y50_codauto," y50_instit = ".db_getsession('DB_instit')." and dl_Auto=$chave_y50_codauto and  x.y50_setor = ".db_getsession("DB_coddepto").$where);
        }elseif(isset($chave_q02_inscr) && (trim($chave_q02_inscr)!="") ){
		     $sql = $clauto->sql_query_busca(null," y50_instit = ".db_getsession('DB_instit')." and dl_identificacao='Inscrição' and dl_codigo=$chave_q02_inscr and  x.y50_setor = ".db_getsession("DB_coddepto").$where);
        }elseif(isset($chave_j01_matric) && (trim($chave_j01_matric)!="") ){
		    $sql = $clauto->sql_query_busca(null," y50_instit = ".db_getsession('DB_instit')." and dl_identificacao='Matrícula' and dl_codigo=$chave_j01_matric and  x.y50_setor = ".db_getsession("DB_coddepto").$where);
        }elseif(isset($chave_z01_numcgm) && (trim($chave_z01_numcgm)!="") ){
		    $sql = $clauto->sql_query_busca(null," y50_instit = ".db_getsession('DB_instit')." and dl_identificacao='Cgm' and dl_codigo=$chave_z01_numcgm and  x.y50_setor = ".db_getsession("DB_coddepto").$where);
        }elseif(isset($chave_y80_codsani) && (trim($chave_y80_codsani)!="")){
	        $sql = $clauto->sql_query_busca(null," y50_instit = ".db_getsession('DB_instit')." and dl_identificacao='Sanitário' and dl_codigo=$chave_y80_codsani and  x.y50_setor = ".db_getsession("DB_coddepto").$where);
        }elseif(isset($chave_y30_codnoti) && (trim($chave_y30_codnoti)!="")){
	    	$sql = $clauto->sql_query_busca(null," y50_instit = ".db_getsession('DB_instit')." and dl_identificacao='Notificação' and dl_codigo=$chave_y30_codnoti and  x.y50_setor=".db_getsession("DB_coddepto").$where);
	    }elseif(isset($chave_y50_numbloco) && (trim($chave_y50_numbloco)!="")){
	    	$sql = $clauto->sql_query_busca(null," y50_instit = ".db_getsession('DB_instit')." and x.y50_numbloco = '$chave_y50_numbloco' and  x.y50_setor=".db_getsession("DB_coddepto").$where);
	    }else{
        	$sql = $clauto->sql_query_busca(null," y50_instit = ".db_getsession('DB_instit')." and x.y50_setor=".db_getsession("DB_coddepto").$where);
        }

        db_lovrot($sql,12,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clauto->sql_record($clauto->sql_query_busca($pesquisa_chave," y50_instit = ".db_getsession('DB_instit')." and dl_Auto=$pesquisa_chave and x.y50_setor=".db_getsession("DB_coddepto")));
          if($clauto->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$z01_nome',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
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