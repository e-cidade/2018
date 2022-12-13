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
include("classes/db_solicita_classe.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clsolicita = new cl_solicita;
$clsolicita->rotulo->label("pc10_numero");
$clsolicita->rotulo->label("pc10_data");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form2" method="post" action="" >
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tpc10_numero?>">
              <?=$Lpc10_numero?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("pc10_numero",10,$Ipc10_numero,true,"text",4,"","chave_pc10_numero");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tpc10_data?>">
              <?=$Lpc10_data?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
	         db_input("pc10_data",10,$Ipc10_data,true,"text",4,"","chave_pc10_data");
           db_input("param",10,"",false,"hidden",3);
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_solicita.hide();">
             </td>
          </tr>
        </table>
        </form>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?

      $where_depart = '';
      if (isset($departamento) && trim($departamento)!="") {
        $where_depart .= " and pc10_depto=$departamento ";
      }
      if (isset($gerautori)) {
        $where_depart .= " and pc10_correto='t' ";
      }

      if (isset($proc) and $proc=="true") {
        $where_depart .= " and pc81_codproc is not null";
      }

      if (isset($nada)) {
        $where_depart = "";
      }

      $where_depart .= " and pc10_instit = " . db_getsession("DB_instit");

      if (empty($anuladas)) {
        $where_depart .= " and not exists(select 1 from solicitaanulada where pc67_solicita = pc10_numero)";
      }

      if (!isset($pesquisa_chave)) {
        if (isset($campos)==false) {
          if (file_exists("funcoes/db_func_solicita.php")==true) {
            include("funcoes/db_func_solicita.php");
          } else {
            $campos = "solicita.*";
          }
        }

        $campos = " distinct ".$campos;
        if (isset($chave_pc10_numero) && (trim($chave_pc10_numero)!="") ) {
          $sql = $clsolicita->sql_query(null,$campos,"pc10_numero desc "," pc10_numero=$chave_pc10_numero $where_depart ");
        } else if (isset($chave_pc10_data) && (trim($chave_pc10_data)!="") ) {
          $sql = $clsolicita->sql_query("",$campos,"pc10_numero desc "," pc10_data like '$chave_pc10_data%' $where_depart ");
        } else {
          $sql = $clsolicita->sql_query("",$campos,"pc10_numero desc "," 1=1 $where_depart");
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",array(),false);
      } else {
        if ($pesquisa_chave!=null && $pesquisa_chave!="") {
          $result = $clsolicita->sql_record($clsolicita->sql_query(null,"distinct *",""," pc10_numero=$pesquisa_chave $where_depart "));
          if ($clsolicita->numrows!=0) {
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$pc10_data',false);</script>";
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
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>