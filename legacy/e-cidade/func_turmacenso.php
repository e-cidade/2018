<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_turmacenso_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clturmacenso = new cl_turmacenso;
$clturmacenso->rotulo->label("ed342_sequencial");
$clturmacenso->rotulo->label("ed342_nome");
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
            <td width="4%" align="right" nowrap title="<?=$Ted342_sequencial?>">
              <?=$Led342_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ed342_sequencial",10,$Ied342_sequencial,true,"text",4,"","chave_ed342_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ted342_nome?>">
              <?=$Led342_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ed342_nome",80,$Ied342_nome,true,"text",4,"","chave_ed342_nome");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_turmacenso.hide();">
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
           if(file_exists("funcoes/db_func_turmacenso.php")==true){
             include("funcoes/db_func_turmacenso.php");
           }else{
           $campos = "turmacenso.*";
           }
        }
        if(isset($chave_ed342_sequencial) && (trim($chave_ed342_sequencial)!="") ){
	         $sql = $clturmacenso->sql_query($chave_ed342_sequencial,$campos,"ed342_sequencial");
        }else if(isset($chave_ed342_nome) && (trim($chave_ed342_nome)!="") ){
	         $sql = $clturmacenso->sql_query("",$campos,"ed342_nome"," ed342_nome like '$chave_ed342_nome%' ");
        }else{
           $sql = $clturmacenso->sql_query("",$campos,"ed342_sequencial","");
        }
        $repassa = array();
        if(isset($chave_ed342_nome)){
          $repassa = array("chave_ed342_sequencial"=>$chave_ed342_sequencial,"chave_ed342_nome"=>$chave_ed342_nome);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clturmacenso->sql_record($clturmacenso->sql_query($pesquisa_chave));
          if($clturmacenso->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ed342_nome',false);</script>";
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
js_tabulacaoforms("form2","chave_ed342_nome",true,1,"chave_ed342_nome",true);
</script>
