<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
include("classes/db_pcmater_classe.php");
include("classes/db_pcmaterele_classe.php");

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$clpcmater    = new cl_pcmater;
$clpcmaterele = new cl_pcmaterele;

$clpcmater->rotulo->label("pc01_codmater");
$clpcmater->rotulo->label("pc01_descrmater");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload='document.form1.chave_pc01_descrmater.focus();'>
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
    <table width="35%" border="0" align="center" cellspacing="0">
    <form name="form1" method="post" action="" >
   <tr> 
      <td width="4%" align="right" nowrap title="<?=$Tpc01_codmater?>"><?=$Lpc01_codmater?></td>
      <td width="96%" align="left" nowrap><?  db_input("pc01_codmater",6,$Ipc01_codmater,true,"text",4,"","chave_pc01_codmater"); ?> </td>
   </tr>
   <tr> 
      <td width="4%" align="right" nowrap title="<?=$Tpc01_descrmater?>"> <?=$Lpc01_descrmater?></td>
      <td width="96%" align="left" nowrap><? db_input("pc01_descrmater",80,$Ipc01_descrmater,true,"text",4,"","chave_pc01_descrmater"); ?></td>
   </tr>
   <tr> 
      <td width="4%" align="right" nowrap title="Selecionar todos, ativos ou inativos"><b>Seleção por:</b></td>
      <td width="96%" align="left" nowrap>
      <?
      if(!isset($opcao)){
	    $opcao = "f";
      }
      if(!isset($opcao_bloq)){
      	$opcao_bloq = 1;
      }
      $arr_opcao = array("i"=>"Todos","f"=>"Ativos","t"=>"Inativos");
      db_select('opcao',$arr_opcao,true,$opcao_bloq,"onchange='js_reload();'"); 
      ?>
      </td>
   </tr>
   <tr> 
      <td colspan="2" align="center"> 
          <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
          <input name="limpar" type="reset" id="limpar" value="Limpar" >
          <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_pcmater.hide();">
          </td>
    </tr>
    </form>
    </table>
    </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
       
       //echo($clpcmaterele->sql_query_file(null,null,"pc07_codmater as pc01_codmater","pc07_codmater"," pc07_codele=$o56_codele "));exit;
      $where_ativo = "";
      if(isset($opcao) && trim($opcao)!="i"){
        $where_ativo = " and pc01_ativo='$opcao' ";
      }

      if (!isset($tem_material)){
        $tem_material = false;
      }

      //print(isset($o56_codele));exit;
      if(isset($o56_codele) and trim($o56_codele) != ''){
                //echo "<br><BR>".($clpcmaterele->sql_query_file(null,null,"pc07_codmater as pc01_codmater","pc07_codmater"," pc07_codele=$o56_codele "));
        $result_elemento = $clpcmaterele->sql_record($clpcmaterele->sql_query_file(null,null,"pc07_codmater as pc01_codmater","pc07_codmater"," pc07_codele=$o56_codele "));
        $numrows_elemento = $clpcmaterele->numrows;
        $materiais = "";
        $vir_mater = "";
        for($i=0;$i<$numrows_elemento;$i++){
          db_fieldsmemory($result_elemento,$i);
          $materiais .= $vir_mater.$pc01_codmater;
          $vir_mater = ",";
        }
        if(trim($materiais)!=""){
          $where_ativo .= " and pc01_codmater in ($materiais) ";
        } else {
          if ($tem_material == true){
            $where_ativo = " and 1 = 2";
          }
        }
      }
      
      if(!isset($pesquisa_chave)){
        if(empty($campos)){
           if(file_exists("funcoes/db_func_pcmater.php")==true){
             include("funcoes/db_func_pcmater.php");
           }else{
           $campos = "pcmater.*";
           }
        } 
	/*
        $campos = "pcmater.pc01_codmater,
	           pcmater.pc01_descrmater,
		   pcmater.pc01_complmater,
		   pcmater.pc01_codsubgrupo,
		   pcsubgrupo.pc04_descrsubgrupo,
		   pcmater.pc01_codele"; */
        $campos = "distinct pcmater.pc01_codmater,
	           pcmater.pc01_descrmater,
		   pcmater.pc01_complmater,
		   pcmater.pc01_codsubgrupo,
		   pcsubgrupo.pc04_descrsubgrupo";

        if(isset($chave_pc01_codmater) && (trim($chave_pc01_codmater)!="") ){
	         $sql = $clpcmater->sql_query_desdobra(null,$campos,"pc01_codmater","pc01_codmater=$chave_pc01_codmater $where_ativo");
        }else if(isset($chave_pc01_descrmater) && (trim($chave_pc01_descrmater)!="") ){
	         $sql = $clpcmater->sql_query_desdobra("",$campos,"pc01_descrmater"," pc01_descrmater like '$chave_pc01_descrmater%'  $where_ativo");
        }else{
           $sql = $clpcmater->sql_query_desdobra("",$campos,"pc01_descrmater","1=1 $where_ativo");
        }
//        echo $sql; exit;
	if(isset($enviadescr)){
	  $clpcmater->sql_record($sql);
	  if($clpcmater->numrows>0){
	    db_lovrot($sql,15,"()","",$funcao_js);
	  }else{
	    $zero = true;
	  }
	}else{
          db_lovrot($sql,15,"()","",$funcao_js);
	}
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clpcmater->sql_record($clpcmater->sql_query_desdobra(null,"distinct pc01_descrmater","","pc01_codmater=$pesquisa_chave $where_ativo"));
          if($clpcmater->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$pc01_descrmater',false);</script>";
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
<script>
function js_reload(){
  document.form1.submit();
}
<?
// CADASTRO DE PCMATER
// Quando o usuário for incluir um item, aparecerá a func_pcmater.php para caso ele queira pegar dados de um item
// já criado... EX.:o usuário ja tem um cadastro de caneta preta com Elemens,grupo e sub-grupo... Para o cadastro
// de uma caneta azul,usará os mesmos dados e mudará apenas a descrição do item... Então, quando ele selecionar o
// item caneta preta, a func retornará os dados para o usuário alterar apenas a descrição. Caso o item procurado 
// não exista  (numrows seja igual a zero), a func jogará para o cadastro apenas a descrição procurada...
if(isset($zero)){
  echo "parent.document.form1.pc01_descrmater.value = document.form1.chave_pc01_descrmater.value;";
  echo "parent.db_iframe_pcmater.hide();";
}
?>
</script>