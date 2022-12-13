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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_matordem_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmatordem = new cl_matordem;
$clmatordem->rotulo->label("m51_codordem");
$clmatordem->rotulo->label("m51_data");
$clrotulo=new rotulocampo;
$clrotulo->label('e60_codemp');
$clrotulo->label('e60_numemp');
$clrotulo->label('z01_nome');

$oGet = db_utils::postMemory($_GET);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
    function js_mascara(evt){
      var evt = (evt) ? evt : (window.event) ? window.event : "";

      if( (evt.charCode >46 && evt.charCode <58) || evt.charCode ==0 ){//8:backspace|46:delete|190:.
	return true;
      }else{
	return false;
      }
    }
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tm51_codordem?>">
              <b>Ordem de Compra:</b>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("m51_codordem",10,$Im51_codordem,true,"text",4,"","chave_m51_codordem");
		       ?>
            </td>
          </tr>
        <tr>
          <td width="4%" align="right" nowrap title="<?=$Te60_codemp?>"><?=$Le60_codemp?>
	  </td>
          <td width="96%" align="left" nowrap title="<?=$Te60_numemp?>">
            <input name="chave_e60_codemp" id="chave_e60_codemp" size="12" type='text'  onKeyPress="return js_mascara(event);" >
	  <?=$Le60_numemp?>
          <? db_input("e60_numemp",10,$Ie60_numemp,true,"text",4,"","chave_e60_numemp");?>
          </td>
        </tr>
        <tr>
          <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>"><?=$Lz01_nome?></td>
          <td width="96%" align="left" nowrap>
            <? db_input("z01_nome",45,"",true,"text",4,"","chave_z01_nome"); ?>
          </td>
        </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tm51_data?>">
              <?=$Lm51_data?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("m51_data",10,$Im51_data,true,"text",4,"","chave_m51_data");
		       ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_matordem.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      $departamento       = db_getsession("DB_coddepto");
      $iInstituicaoSessao = db_getsession("DB_instit");
      $where   = "     e60_instit = {$iInstituicaoSessao} ";
      $where  .= " and m51_depto  = {$departamento} ";

      if (isset($oGet->lExibeAutomatica) && ($oGet->lExibeAutomatica == "false" || $oGet->lExibeAutomatica == false) ) {
        $where  .= " and m51_tipo = 1 ";
      }



      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_matordem.php")==true){
             include("funcoes/db_func_matordem.php");
           }else{
           $campos = "matordem.*";
           }
        }

        if (isset($chave_m51_codordem) && (trim($chave_m51_codordem)!="") ) {
	        $sql = $clmatordem->sql_query_numemp($chave_m51_codordem,$campos,"m51_codordem"," m53_codordem is null and $where and m51_codordem = $chave_m51_codordem");
        } else if(isset($chave_e60_codemp) && (trim($chave_e60_codemp)!="") ) {
	        $arr = split("/",$chave_e60_codemp);

	        if (count($arr) == 2  && isset($arr[1]) && $arr[1] != '' ) {
		        $dbwhere_ano = " and e60_anousu = ".$arr[1];
          } else if(count($arr)==1) {
            $dbwhere_ano = " and e60_anousu = ".db_getsession("DB_anousu");
       	  } else {
		        $dbwhere_ano = "";
	        }
          $sql = $clmatordem->sql_query_numemp("",$campos,"m51_codordem"," m53_codordem is null and  e60_codemp='".$arr[0]."'$dbwhere_ano and $where ");

        } else if (isset($chave_e60_numemp) && (trim($chave_e60_numemp)!="") ) {
           $sql = $clmatordem->sql_query_numemp("",$campos,"m51_codordem","m53_codordem is null and $chave_e60_numemp=matordemitem.m52_numemp and $where ");
        } else if (isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ) {
           $sql = $clmatordem->sql_query_numemp("",$campos,"m51_codordem"," m53_codordem is null and z01_nome like '$chave_z01_nome%' and $where ");
        } else if (isset($chave_m51_data) && (trim($chave_m51_data)!="") ) {
	         $sql = $clmatordem->sql_query_numemp("",$campos,"m51_data"," m51_data like '$chave_m51_data%' and   m53_codordem is nulland $where ");
        } else {
           $sql = $clmatordem->sql_query_numemp("",$campos,"m51_codordem","m53_codordem is null and $where ");
        }

        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clmatordem->sql_record($clmatordem->sql_query_numemp("","*","m51_codordem","m53_codordem is null and m51_codordem=$pesquisa_chave and $where "));
          if($clmatordem->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$m51_codordem',false);</script>";
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