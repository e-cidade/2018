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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_issbase_classe.php");
include("classes/db_cgm_classe.php");

db_postmemory($_POST);
db_postmemory($_GET);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clissbase = new cl_issbase;
$clcgm     = new cl_cgm;
$clissbase->rotulo->label("q02_inscr");
$clcgm->rotulo->label("z01_nome");
$clissbase->rotulo->label("q02_inscmu");
$clrotulo = new rotulocampo;
$clrotulo->label("q02_inscr");
$clrotulo->label("z01_nome");

$sBaixa = isset($_GET['calculo']) ? "and q02_dtbaix is null" : "";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
  function js_close(){

    if (typeof nomeJanela === 'undefined') {

      parent.db_iframe_issbase.hide();
      return;
    }

    var nome = parent.top.corpo.aux.nomeJanela;
    eval('parent.top.corpo.'+nome+'.hide();');
  }
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="document.form2.chave_z01_nome.focus();">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tq02_inscr?>">
              <?=$Lq02_inscr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
	       db_input("q02_inscr",10,$Iq02_inscr,true,"text",10,"","chave_q02_inscr");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
              <?=$Lz01_nome?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
	       db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","chave_z01_nome");
              ?>
            </td>
          </tr>

          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tq02_inscmu?>">
              <?=$Lq02_inscmu?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
	       db_input("q02_inscmu",10,$Iq02_inscmu,true,"text",4,"","chave_q02_inscmu");
              ?>
            </td>
          </tr>

          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" onclick="return js_valida(arguments[0]);">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="js_close();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
       $propaga["chave_z01_nome"] = @$chave_z01_nome;
       $propaga["chave_q02_inscr"] = @$chave_q02_inscr;
       $clissbase->propagar = $propaga;

       if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           $campos = "issbase.q02_inscr,cgm.z01_numcgm as db_z01_numcgm,cgm.z01_nome,cgm.z01_ender,cgm.z01_numero,cgm.z01_compl,issbase.q02_dtinic,issbase.q02_dtbaix,q02_inscmu";
        }
        if(isset($chave_q02_inscr) && (trim($chave_q02_inscr)!="") ){
	   		  $sql = $clissbase->sql_query(null,$campos,"q02_inscr", "q02_inscr = {$chave_q02_inscr} {$sBaixa}");
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
	   		  $sql = $clissbase->sql_query("",$campos,"z01_nome"," z01_nome like '$chave_z01_nome%' {$sBaixa}");
        }else if(isset($chave_q02_inscmu) && (trim($chave_q02_inscmu)!="") ){
	        $sql = $clissbase->sql_query(null,$campos,"q02_inscmu"," q02_inscmu ilike '%$chave_q02_inscmu%'");
        }else{
          $sql = "";
        }
	if(!empty($sql)){

          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$propaga);
	}
      }else{
      	$result = $clissbase->sql_record($clissbase->sql_query(null, "*", null, "q02_inscr = {$pesquisa_chave} {$sBaixa}"));
        if($clissbase->numrows!=0){
          db_fieldsmemory($result,0);
          if(@$sani==1){
          	 echo "<script>".$funcao_js."(\"$q02_inscr\",\"$z01_nome\");</script>";
          }else{
             echo "<script>".$funcao_js."(\"$z01_nome\",false,\"$q02_dtbaix\",$z01_numcgm);</script>";
          }

        }else{
          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
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
  function js_valida(event) {

		document.getElementById('chave_q02_inscr').onkeyup = event;
	  return true;

	}
	document.form2.chave_z01_nome.focus();
	document.form2.chave_z01_nome.select();
  </script>
  <?
}
?>