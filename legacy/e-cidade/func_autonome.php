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
include("classes/db_autotipo_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clauto = new cl_auto;
$clautotipo = new cl_autotipo;
$clauto->rotulo->label("y50_codauto");
$clauto->rotulo->label("y50_nome");
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
             </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      if(!isset($pesquisa_chave)){

      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result_numcgm = $clauto->sql_record($clauto->sql_querycgm($pesquisa_chave));
          if($clauto->numrows!=0){
            db_fieldsmemory($result_numcgm,0);
            $result_cgmauto = $clauto->sql_record($clauto->sql_query_cgm(null,"z01_numcgm=$z01_numcgm and y50_codauto<>$pesquisa_chave"));
	          $numrows=$clauto->numrows;
	          if ($numrows!=0){
	            db_fieldsmemory($result_cgmauto,0);

              $sWhere = " y59_codauto = $y50_codauto and y59_codtipo = $codtipo ";
              $sSql = $clautotipo->sql_query_file(null, "*", null, $sWhere);
	            $result_proced=$clautotipo->sql_record($sSql);
	            if ($clautotipo->numrows!=0){
                // echo "<script>".$funcao_js."($sSql);</script>";
                echo "<script>".$funcao_js."(true);</script>";
	            }else{
                // echo "<script>".$funcao_js."($sSql);</script>";
                echo "<script>".$funcao_js."(false);</script>";
              }
	          }
          }else{
            // echo "<script>".$funcao_js."($sSql);</script>";
	          echo "<script>".$funcao_js."(erro);</script>";
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