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

require("libs/db_conecta.php");
require("libs/db_stdlib.php");
include("dbforms/db_funcoes.php");
include("classes/db_dbprefcgm_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldbprefcgm = new cl_dbprefcgm;
$cldbprefcgm->rotulo->label("z01_sequencial");
$cldbprefcgm->rotulo->label("z01_nome");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">	<? db_estilosite(); ?></style>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="<?=$w01_corbody?>"leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="<?=$w01_corbody?>" >
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0" class="texto">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tz01_sequencial?>">
              <?=$Lz01_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("z01_sequencial",10,$Iz01_sequencial,true,"text",4,"","chave_z01_sequencial");
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
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" class="botao"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar"  class="botao" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_dbprefcgm.hide();"  class="botao">
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
           if(file_exists("funcoes/db_func_dbprefcgm.php")==true){
             include("funcoes/db_func_dbprefcgm.php");
           }
        }
        if(isset($chave_z01_sequencial) && (trim($chave_z01_sequencial)!="") ){
          $sql = "select   z01_sequencial,z01_cgccpf, z01_nome
				from dbprefcgm
				inner join dbprefempresa on z01_sequencial =q55_dbprefcgm
				where q55_usuario = $id and z01_sequencial = $chave_z01_sequencial
         		order by z01_sequencial";
	        // $sql = $cldbprefcgm->sql_query($chave_z01_sequencial,$campos,"z01_sequencial");
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
              $sql = "select   z01_sequencial,z01_cgccpf, z01_nome 
				from dbprefcgm
				inner join dbprefempresa on z01_sequencial =q55_dbprefcgm
				where q55_usuario = $id and z01_nome ilike '$chave_z01_nome%'
         		order by z01_sequencial";
	         //$sql = $cldbprefcgm->sql_query("",$campos,"z01_nome"," z01_nome like '$chave_z01_nome%' ");
        }else{
         $sql = "select   z01_sequencial,z01_cgccpf, z01_nome
				from dbprefcgm
				inner join dbprefempresa on z01_sequencial =q55_dbprefcgm
				where q55_usuario = $id
         		order by z01_sequencial";
          
           //$sql = $cldbprefcgm->sql_query("",$campos,"z01_sequencial","");
        }
        $repassa = array();
        if(isset($chave_z01_nome)){
          $repassa = array("chave_z01_sequencial"=>$chave_z01_sequencial,"chave_z01_nome"=>$chave_z01_nome);
        }
        //die($sql);
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cldbprefcgm->sql_record($cldbprefcgm->sql_query($pesquisa_chave));
          if($cldbprefcgm->numrows!=0){
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
<script>
js_tabulacaoforms("form2","chave_z01_nome",true,1,"chave_z01_nome",true);
</script>