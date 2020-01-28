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
include("classes/db_benstransf_classe.php");
include("classes/db_benstransfdes_classe.php");
include("classes/db_benstransfconf_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clbenstransf = new cl_benstransf;
$clbenstransfdes = new cl_benstransfdes;
$clbenstransfconf = new cl_benstransfconf;
$clbenstransf->rotulo->label("t93_codtran");
$clbenstransf->rotulo->label("t93_data");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" width="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
        <form name="form1" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tt93_codtran?>">
              <?=$Lt93_codtran?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("t93_codtran",10,$It93_codtran,true,"text",4,"","chave_t93_codtran");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tt93_data?>">
              <?=$Lt93_data?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
             	      db_inputdata('t93_data',@$t93_data_dia,@$t93_data_mes,@$t93_data_ano,true,'text',1,"");
		       ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_benstransf.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?

      $where_instit = " and t93_instit = ".db_getsession("DB_instit");

      if(isset($campos)==false){
         if(file_exists("funcoes/db_func_benstransf.php")==true){
           include("funcoes/db_func_benstransf.php");
         }else{
         $campos = "benstransf.*";
         }
      }
      if((isset($t93_data_ano) && trim($t93_data_ano) != "") && (isset($t93_data_mes) && trim($t93_data_mes) != "") && (isset($t93_data_dia) && trim($t93_data_dia) != "")){
	      $chave_t93_data =  $t93_data_ano."-".$t93_data_mes."-".$t93_data_dia;
      } else if(isset($t93_data_ano) && trim($t93_data_ano) != ""){
	      $chave_t93_data = $t93_data_ano."%";
      }
      if(isset($rel) && $rel == 'true'){
        $param = " in ";
      }else{
        $param = " not in ";
      }
      $campos = "distinct $campos";

      if(!isset($pesquisa_chave)){
        if(isset($chave_t93_codtran) && (trim($chave_t93_codtran)!="") ){
          $sql = $clbenstransf->sql_query_departamento_destino(null,$campos,"t93_codtran"," t93_codtran $param (select t96_codtran from benstransfconf) and t93_codtran = $chave_t93_codtran $where_instit ");
        }else if(isset($chave_t93_data) && (trim($chave_t93_data)!="") ){
	        $sql = $clbenstransf->sql_query_departamento_destino("",$campos,"t93_data","t93_codtran $param (select t96_codtran from benstransfconf) and t93_data like '$chave_t93_data' $where_instit ");
        }else{
          $sql = $clbenstransf->sql_query_departamento_destino("",$campos,"t93_codtran","t93_codtran $param (select t96_codtran from benstransfconf) $where_instit ");
        }

        db_lovrot($sql,15,"()","",$funcao_js);

      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clbenstransf->sql_record($clbenstransf->sql_query_departamento_destino(null,$campos,"","t93_codtran $param (select t96_codtran from benstransfconf) and t93_codtran = $pesquisa_chave and t93_instit = ".db_getsession("DB_instit")));
          if($clbenstransf->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$nome',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
     //select * from benstransf where t93_depart=1 and benstransf.t93_codtran
     // not in (select t96_codtran from benstransfconf);
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
