<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_habitcandidato_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clhabitcandidato = new cl_habitcandidato;
$clhabitcandidato->rotulo->label("ht10_sequencial");
$clhabitcandidato->rotulo->label("ht10_numcgm");
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
            <td width="4%" align="right" nowrap title="<?=$Tht10_sequencial?>">
              <?=$Lht10_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ht10_sequencial",10,$Iht10_sequencial,true,"text",4,"","chave_ht10_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tht10_numcgm?>">
              <?=$Lht10_numcgm?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ht10_numcgm",10,$Iht10_numcgm,true,"text",4,"","chave_ht10_numcgm");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_habitcandidato.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      
      $aWhere = array();
      $sWhere = "1=1";
      
      if (isset($sListaInteressePrograma) && trim($sListaInteressePrograma) != '') {
      	
      	$aWhere[] = " exists ( select 1
      	                         from habitcandidatointeresse
      	                              inner join habitgrupoprograma on habitgrupoprograma.ht03_sequencial    = habitcandidatointeresse.ht20_habitgrupoprograma
      	                              inner join habitprograma      on habitprograma.ht01_habitgrupoprograma = habitgrupoprograma.ht03_sequencial  
      	                        where habitcandidatointeresse.ht20_habitcandidato = habitcandidato.ht10_sequencial 
      	                          and habitprograma.ht01_sequencial in ($sListaInteressePrograma) ) ";
      	                           
        $sWhere .= " and ".implode(" and ",$aWhere);
      }
      
      if(!isset($pesquisa_chave)){
        
      	if(isset($campos)==false){
           if(file_exists("funcoes/db_func_habitcandidato.php")==true){
             include("funcoes/db_func_habitcandidato.php");
           }
        }
        
        $campos  = " habitcandidato.*,  ";
        $campos .= " z01_nome,          ";
        $campos .= " case   
                       when exists ( select 1 
                                       from habitcandidatointeresse 
                                      where ht20_habitcandidato = ht10_sequencial 
                                        and ht20_ativo is true ) then 'Sim' else 'Não'
                     end as dl_interesses, ";
        
        $campos .= " case
                       when exists ( select 1 
                                       from habitcandidatointeresse
                                            inner join habitcandidatointeresseprograma on ht13_habitcandidatointeresse = ht20_sequencial
                                            inner join habitinscricao                  on ht15_habitcandidatointeresseprograma = ht13_sequencial   
                                      where ht20_habitcandidato = ht10_sequencial 
                                        and ht20_ativo is true ) then 'Sim' else 'Não'
                     end  as dl_inscricoes";
        
        
        if(isset($chave_ht10_sequencial) && (trim($chave_ht10_sequencial)!="") ){
          
	        $sql = $clhabitcandidato->sql_query(null,$campos,"ht10_sequencial",$sWhere." and ht10_sequencial = $chave_ht10_sequencial");
	        
        } else if(isset($chave_ht10_numcgm) && (trim($chave_ht10_numcgm)!="") ){
          
	        $sql = $clhabitcandidato->sql_query("",$campos,"ht10_numcgm",$sWhere." and ht10_numcgm like '$chave_ht10_numcgm%' ");
        } else {
          
          $sql = $clhabitcandidato->sql_query("",$campos,"ht10_sequencial",$sWhere);
        }
        
        $repassa = array();
        
        if(isset($chave_ht10_numcgm)){
          $repassa = array("chave_ht10_sequencial"=>$chave_ht10_sequencial,"chave_ht10_numcgm"=>$chave_ht10_numcgm);
        }
        
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        
      } else {
      	
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
        	
          $result = $clhabitcandidato->sql_record($clhabitcandidato->sql_query(null,"*",null,"  ht10_sequencial = $pesquisa_chave"));
          
          
          if ($clhabitcandidato->numrows!=0) {
          	
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$z01_nome',false);</script>";
            
          } else {
          	
	          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
          
        } else {
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
js_tabulacaoforms("form2","chave_ht10_numcgm",true,1,"chave_ht10_numcgm",true);
</script>