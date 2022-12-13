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
include("dbforms/db_funcoes.php");
include("classes/db_cnae_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcnae = new cl_cnae;
$clcnae->rotulo->label("q71_sequencial");
$clcnae->rotulo->label("q71_estrutural");
$clcnae->rotulo->label("q71_descr");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">	<? db_estilosite(); ?></style>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="<?=$w01_corbody?>" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="<?=$w01_corbody?>">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0" class="texto">
	     <form name="form2" method="post" action="" >
          
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tq71_estrutural?>">
              <?=$Lq71_estrutural?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("q71_estrutural",8,$Iq71_estrutural,true,"text",4,"","chave_q71_estrutural");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tq71_descr?>">
              <?=$Lq71_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("q71_descr",40,$Iq71_descr,true,"text",4,"","chave_q71_descr");
		       ?>
            </td>
          </tr>
          <tr>
	          <td align="right"><b>Mostrar:</b></td>
	          <td align="left" > 
	          <?
		        $arraymostra = array("A" => "Somente analítico ","T" => "Todos");
		        db_select("mostra",$arraymostra,1,1,"onchange='document.form2.submit();'");
		        ?>
	          </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" class="botao"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" class="botao">
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cnae.hide();" class="botao">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
       $where = "";
           if(!isset($mostra) ||$mostra=='A'){
              $where = "where q72_cnae is not null";
           }
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_cnae.php")==true){
             include("funcoes/db_func_cnae.php");
           }else{
           $campos = "cnae.*";
           }
        }
       if(isset($chave_q71_estrutural) && (trim($chave_q71_estrutural)!="") ){
           $where1 = "";
           if(!isset($mostra) ||$mostra=='A'){
              $where1 = "and q72_cnae is not null";
           }
           
           $sql = "select 
						case when q72_cnae is null then 'Sintetico' else 'Analitico' END as tipo ,
						q71_estrutural,
						q71_descr,
						q72_sequencial
					from cnae 
					left join cnaeanalitica on q72_cnae = q71_sequencial
					where q71_estrutural ilike '%$chave_q71_estrutural%'  $where1
					order by q71_estrutural "; 
           //$sql = $clcnae->sql_query("",$campos,"q71_estrutural"," q71_estrutural like '%$chave_q71_estrutural%' ");
        }elseif(isset($chave_q71_descr)&& (trim($chave_q71_descr)!="")){
           $where1 = "";
           if(!isset($mostra) ||$mostra=='A'){
              $where1 = "and q72_cnae is not null";
           }
           $sql = "select 
						case when q72_cnae is null then 'Sintetico' else 'Analitico' END as tipo ,
						q71_estrutural,
						q71_descr,
						q72_sequencial
					from cnae 
					left join cnaeanalitica on q72_cnae = q71_sequencial
					where q71_descr ilike '%$chave_q71_descr%'  $where1
					order by q71_estrutural"; 
	         
        }else{
          $sql = "select 
						case when q72_cnae is null then 'Sintetico' else 'Analitico' END as tipo ,
						q71_estrutural,
						q71_descr,
						q72_sequencial
					from cnae 
					left join cnaeanalitica on q72_cnae = q71_sequencial $where
                    order by q71_estrutural "; 
           //$sql = $clcnae->sql_query("",$campos,"q71_sequencial","");
        }
        $repassa = array();
        /*
        if(isset($chave_q71_estrutural)){
          $repassa = array("chave_q71_sequencial"=>$chave_q71_sequencial,"chave_q71_estrutural"=>$chave_q71_estrutural);
        }
*/
        //die($sql);
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          //$result = $clcnae->sql_record($clcnae->sql_query($pesquisa_chave));
          $sql = "select 
						case when q72_cnae is null then 'Sintetico' else 'Analitico' END as tipo ,
						q71_estrutural,
						q71_descr,
						q72_sequencial
					from cnae 
					left join cnaeanalitica on q72_cnae = q71_sequencial
					where  q71_estrutural ilike '%$pesquisa_chave'
					order by q71_estrutural";
         // die($sql);
          $result = db_query($sql);
          $linhas = pg_num_rows($result);
          if($linhas>0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$q71_estrutural','$q71_descr','$q72_sequencial',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('','Chave(".$pesquisa_chave.") não Encontrado','',true);</script>";
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
js_tabulacaoforms("form2","chave_q71_estrutural",true,1,"chave_q71_estrutural",true);
</script>