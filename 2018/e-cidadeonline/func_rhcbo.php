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
include("classes/db_rhcbo_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhcbo = new cl_rhcbo;
$clrhcbo->rotulo->label("rh70_sequencial");
$clrhcbo->rotulo->label("rh70_sequencial");
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
        <table width="100%" border="0" align="center" cellspacing="0" class="texto" >
	     <form name="form1" method="post" action="" >
          <tr> 
            <td width="4%" align="left" nowrap title="<?=$Trh70_sequencial?>">
              <?=$Lrh70_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh70_sequencial",10,$Irh70_sequencial,true,"text",4,"","chave_rh70_sequencial");
		       ?>
            </td>
          </tr>
         <tr> 
             <td  align="left"> <b>Grande Grupo:</b>
             </td>
             <td > 
              <?
             
              $sqlgg= "select rh70_estrutural as rh70_estrutural_1, 
												case when length(rh70_descr)>60 
												then substr(rh70_descr,0,60)||'...' 
												else rh70_descr 
												end as rh70_descr_1 
												from rhcbo where rh70_tipo = 0
												order by rh70_estrutural ";
              
              $resultgg = db_query($sqlgg);
              db_selectrecord('grandegrupo', $resultgg, true, 1,"","","","","js_gg();");
              
              
              ?>
             </td>
          </tr>
          <tr> 
             <td ><b>Subgrupo Principal</b>
             </td>
             <td > 
             <?
             if(!isset($grandegrupo)){
                 $grandegrupo = '0';
             }
             $sqlsgp = "select rh70_estrutural as rh70_estrutural_2, 
																case when length(rh70_descr)>60 
																then substr(rh70_descr,0,60)||'...' 
																else rh70_descr 
																end as tamanho 
													from rhcbo 
													where rh70_tipo = 1 and rh70_estrutural like '$grandegrupo%'";
             $resultsgp= db_query($sqlsgp);
             db_selectrecord('subgrupoprincipal', $resultsgp, true, 1,"","","","","js_gg();");
             ?>
            </td>
          </tr>
          <tr> 
             <td ><b>Subgrupo</b>
             </td>
             <td > 
             <?
              
             if(isset($grandegrupo) && @$ggantes!= $grandegrupo){
                 $ggantes = $grandegrupo;
                 $subgrupoprincipal=$grandegrupo;
             }

             if(isset($subgrupoprincipal)){
                 $subgrupoprincipal=$subgrupoprincipal;
             }
             if(!isset($subgrupoprincipal)){
                $subgrupoprincipal='0';
             }
              db_input("ggantes",10,$Irh70_sequencial,true,"hidden",4,"","");
             $sqlsg = "select rh70_estrutural as rh70_estrutural_2, 
																case when length(rh70_descr)>60 
																then substr(rh70_descr,0,60)||'...' 
																else rh70_descr 
																end as tamanho 
													from rhcbo 
													where rh70_tipo = 2 and rh70_estrutural like '$subgrupoprincipal%'";
              $resultsg= db_query($sqlsg);
              db_selectrecord('subgrupo', $resultsg, true, 1,"","","","","js_gg();");
				      if($subgrupoprincipal != @$sub ){
				         $sub = $subgrupoprincipal;
				      }else{
				       $subgrupoprincipal = $subgrupo;
				      }
               db_input("sub",10,$Irh70_sequencial,true,"hidden",4,"","");
             ?>
             </td>
          </tr>
          <tr>
	          <td ><b>Mostrar:</b></td>
	          <td align="left" > 
	          <?
		        $arraymostra = array("A" => "Somente analítico ","T" => "Todos");
		        db_select("mostra",$arraymostra,1,1,"onchange='document.form1.submit();'");
		        ?>
	          </td>
         </tr>
         <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhcbo.hide();">
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
              $where = " and rh70_tipo=5";
           }
      
      
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_rhcbo.php")==true){
             include("funcoes/db_func_rhcbo.php");
           }else{
          // $campos = "rhcbo.*";
           $campos = " case when rh70_tipo=5 then 'Analitico' else 'Sintetico' end as tipo,rh70_sequencial,rh70_estrutural,rh70_descr ";
           }
        }
        if(isset($chave_rh70_sequencial) && (trim($chave_rh70_sequencial)!="") ){
         
	         $sql = $clrhcbo->sql_query($chave_rh70_sequencial,$campos,"rh70_sequencial");
        }else if(isset($chave_rh70_sequencial) && (trim($chave_rh70_sequencial)!="") ){
          
	         $sql = $clrhcbo->sql_query("",$campos,"rh70_sequencial"," rh70_sequencial like '$chave_rh70_sequencial%' ");
        }else{
          $campos = " case when rh70_tipo=5 then 'Analitico' else 'Sintetico' end as tipo,rh70_sequencial,rh70_estrutural,rh70_descr ";
           $sql = $clrhcbo->sql_query("",$campos,"rh70_estrutural","rh70_estrutural like '$subgrupoprincipal%' $where");
        }
        $repassa = array();
        if(isset($chave_rh70_sequencial)){
         
          $repassa = array("chave_rh70_sequencial"=>$chave_rh70_sequencial,"chave_rh70_sequencial"=>$chave_rh70_sequencial);
        }
        //echo "<br>$sql";
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          //die($clrhcbo->sql_query($pesquisa_chave));
          $campos = " case when rh70_tipo=5 then 'Analitico' else 'Sintetico' end as tipo,rh70_sequencial,rh70_estrutural,rh70_descr ";
          $result = $clrhcbo->sql_record($clrhcbo->sql_query(null,"$campos",null,"rh70_estrutural = '".$pesquisa_chave."'"));
          if($clrhcbo->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh70_estrutural','$rh70_descr','$rh70_sequencial','$tipo',false);</script>";
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
//js_tabulacaoforms("form2","chave_rh70_sequencial",true,1,"chave_rh70_sequencial",true);
function js_gg(){
document.form1.submit();
}

</script>