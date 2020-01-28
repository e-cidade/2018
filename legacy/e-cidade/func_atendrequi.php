<?
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
require_once("classes/db_atendrequi_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clatendrequi = new cl_atendrequi;
$clrotulo     = new rotulocampo;

$oGet = db_utils::postMemory($_GET);

$clatendrequi->rotulo->label("m42_codigo");
$clrotulo->label("m40_codigo");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body style="background-color: #CCCCCC; margin-top: 15px;">

<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tm42_codigo?>">
              <?=$Lm42_codigo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("m42_codigo",10,$Im42_codigo,true,"text",4,"","chave_m42_codigo");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tm40_codigo?>">
              <?=$Lm40_codigo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("m40_codigo",10,$Im40_codigo,true,"text",4,"","chave_m40_codigo");
		       ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_atendrequi.hide();">
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
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_atendrequi.php")==true){
             require_once("funcoes/db_func_atendrequi.php");
           }else{
           $campos = "atendrequi.*";
           }
        }

        $campos = "distinct ".$campos;
        $aWhere = array();
        if (!empty($oGet->devolucao) && $oGet->devolucao) {

          $iDepartamentoSessao = db_getsession('DB_coddepto');
          $aWhere = array(
            'db_depart.instit = '.db_getsession('DB_instit'),
            "( atendrequi.m42_depto = {$iDepartamentoSessao} or matrequi.m40_almox = (select m91_codigo from db_almox where m91_depto = {$iDepartamentoSessao}) )",
            "extract(year from m40_data) = ".db_getsession('DB_anousu')

          );
        }

        $sql = $clatendrequi->sql_query_requi(null, $campos, "m42_codigo desc",implode(' and ', $aWhere));
        if (!empty($chave_m42_codigo)){

          $aWhere[] = "m42_codigo = {$chave_m42_codigo}";
	         $sql = $clatendrequi->sql_query_requi($chave_m42_codigo,$campos,"m42_codigo desc", implode(' and ', $aWhere));
        } else if(!empty($chave_m40_codigo)){

          $aWhere[] = "m40_codigo = {$chave_m40_codigo}";
	        $sql = $clatendrequi->sql_query_requi(null, $campos, "m42_codigo desc", implode(' and ', $aWhere));
        }

        db_lovrot($sql,15,"()","",$funcao_js);


      } else {

        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clatendrequi->sql_record($clatendrequi->sql_query_requi($pesquisa_chave));
          if($clatendrequi->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$m42_codigo',false);</script>";
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
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>