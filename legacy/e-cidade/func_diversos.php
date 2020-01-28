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
include("classes/db_diversos_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldiversos = new cl_diversos;
$clrotulo = new rotulocampo;
$clrotulo->label("k00_inscr");
$clrotulo->label("k00_matric");
$cldiversos->rotulo->label("dv05_coddiver");
$cldiversos->rotulo->label("dv05_numcgm");
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
	     <form name="form1" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tdv05_coddiver?>">
              <?=$Ldv05_coddiver?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("dv05_coddiver",10,$Idv05_coddiver,true,"text",4,"","chave_dv05_coddiver");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tdv05_numcgm?>">
              <?=$Ldv05_numcgm?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("dv05_numcgm",6,$Idv05_numcgm,true,"text",4,"","chave_dv05_numcgm");
		       ?>
            </td>
          </tr>



          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tk00_inscr?>">
              <?=$Lk00_inscr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("k00_inscr",6,$Ik00_inscr,true,"text",4,"","chave_k00_inscr");
		       ?>
            </td>
          </tr>

          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tk00_matric?>">
              <?db_ancora($Lk00_matric,' js_matriculas(true); ',1); ?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("k00_matric",6,$Ik00_matric,true,"text",4,"","chave_k00_matric");
		       ?>
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
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_diversos.php")==true){
             include("funcoes/db_func_diversos.php");
           }else{
             $campos = "diversos.*";
           }
        }

        $campos = 'dv05_coddiver,
                   cgm.z01_nome,
                   dv05_dtinsc,
                   dv05_exerc,
                   dv05_procdiver,
                   dv05_obs,
                   k00_matric,
                   k00_inscr';

        if(isset($chave_dv05_coddiver) && (trim($chave_dv05_coddiver)!="") ){
	         $sql = $cldiversos->sql_query_func("",$campos,"dv05_coddiver"," dv05_coddiver like '$chave_dv05_coddiver%' and dv05_instit = ".db_getsession('DB_instit')."");
        }else if(isset($chave_dv05_numcgm) && (trim($chave_dv05_numcgm)!="") ){
	         $sql = $cldiversos->sql_query_func("",$campos,"dv05_numcgm"," dv05_numcgm like '$chave_dv05_numcgm%' and dv05_instit = ".db_getsession('DB_instit')." ");
	    /////// inscrição /////////////////////
        }else if(isset($chave_k00_inscr) && (trim($chave_k00_inscr)!="") ){
	         $sql = $cldiversos->sql_query_func("",$campos,"k00_inscr"," k00_inscr like '$chave_k00_inscr%' and dv05_instit = ".db_getsession('DB_instit')." ");
	    /////// matricula /////////////////////
	    }else if(isset($chave_k00_matric) && (trim($chave_k00_matric)!="") ){
	         $sql = $cldiversos->sql_query_func("",$campos,"k00_matric"," k00_matric like '$chave_k00_matric%' and dv05_instit = ".db_getsession('DB_instit')." ");
	    //////////////////////////////////////*/
        }else if (isset($pesquisar)){
           $sql = $cldiversos->sql_query_func("",$campos,"dv05_coddiver desc"," dv05_instit = ".db_getsession('DB_instit')." ");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cldiversos->sql_record($cldiversos->sql_query_func($pesquisa_chave,"*",null," dv05_instit = ".db_getsession('DB_instit')." "));
          if($cldiversos->numrows!=0){
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
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script type="text/javascript">
function js_matriculas(mostra){
  js_OpenJanelaIframe('','db_iframe3','func_iptubase.php?funcao_js=parent.js_mostramatricula|0|1','Pesquisa',true);
}

function js_mostramatricula(chave1){
  document.form1.chave_k00_matric.value = chave1;
  db_iframe3.hide();
}

</script>