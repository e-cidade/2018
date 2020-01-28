<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_turmaachorarioprofissional_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clturmaachorarioprofissional = new cl_turmaachorarioprofissional;
$clturmaachorarioprofissional->rotulo->label("ed346_sequencial");
$clturmaachorarioprofissional->rotulo->label("ed346_turmaac");
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
            <td width="4%" align="right" nowrap title="<?=$Ted346_sequencial?>">
              <?=$Led346_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ed346_sequencial",10,$Ied346_sequencial,true,"text",4,"","chave_ed346_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ted346_turmaac?>">
              <?=$Led346_turmaac?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ed346_turmaac",10,$Ied346_turmaac,true,"text",4,"","chave_ed346_turmaac");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_turmaachorarioprofissional.hide();">
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
           if(file_exists("funcoes/db_func_turmaachorarioprofissional.php")==true){
             include("funcoes/db_func_turmaachorarioprofissional.php");
           }else{
           $campos = "turmaachorarioprofissional.*";
           }
        }
        if(isset($chave_ed346_sequencial) && (trim($chave_ed346_sequencial)!="") ){
	         $sql = $clturmaachorarioprofissional->sql_query($chave_ed346_sequencial,$campos,"ed346_sequencial");
        }else if(isset($chave_ed346_turmaac) && (trim($chave_ed346_turmaac)!="") ){
	         $sql = $clturmaachorarioprofissional->sql_query("",$campos,"ed346_turmaac"," ed346_turmaac like '$chave_ed346_turmaac%' ");
        }else{
           $sql = $clturmaachorarioprofissional->sql_query("",$campos,"ed346_sequencial","");
        }
        $repassa = array();
        if(isset($chave_ed346_turmaac)){
          $repassa = array("chave_ed346_sequencial"=>$chave_ed346_sequencial,"chave_ed346_turmaac"=>$chave_ed346_turmaac);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clturmaachorarioprofissional->sql_record($clturmaachorarioprofissional->sql_query($pesquisa_chave));
          if($clturmaachorarioprofissional->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ed346_turmaac',false);</script>";
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
<script>
js_tabulacaoforms("form2","chave_ed346_turmaac",true,1,"chave_ed346_turmaac",true);
</script>
