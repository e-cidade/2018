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
include("classes/db_itbi_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clitbi = new cl_itbi;
$clitbi->rotulo->label("it01_guia");
$clitbi->rotulo->label("it01_guia");

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
            <td width="4%" align="right" nowrap title="<?=$Tit01_guia?>">
              <?=$Lit01_guia?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("it01_guia",10,$Iit01_guia,true,"text",4,"","chave_it01_guia");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframeitbi.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $campos = "it01_guia,it01_data,it01_areaterreno,c.it03_nome as dl_comprador, t.it03_nome as dl_transmitente  ";
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_itbi.php")==true){
             include("funcoes/db_func_itbi.php");
           }else{
           $campos = "itbi.*";
           }
        }

        if(isset($chave_it01_guia) && (trim($chave_it01_guia)!="") ){
        				 
	  $sql = "select $campos
			  from itbi 
			  inner join itbinome c  on c.it03_guia = it01_guia and upper(c.it03_tipo) = 'C' 
			  inner join itbinome t  on t.it03_guia = it01_guia and upper(t.it03_tipo) = 'T'
			  left  join itbicancela on it01_guia   = it16_guia 
			  left  join itbiavalia  on it14_guia   = it01_guia 
			  inner join itbimatric  on it06_guia   = it01_guia
			  where it14_guia is not null 
			    and itbicancela.it16_guia is null 
					and c.it03_princ is true
			    and it06_matric = $matric
				and it01_guia   = $chave_it01_guia 
			  order by it01_guia";
			  
        }else{
      	
		
	  $sql = "select $campos
			  from itbi 
			  inner join itbinome c  on c.it03_guia = it01_guia and upper(c.it03_tipo) = 'C' 
			  inner join itbinome t  on t.it03_guia = it01_guia and upper(t.it03_tipo) = 'T'
			  left  join itbicancela on it01_guia   = it16_guia 
			  left  join itbiavalia  on it14_guia   = it01_guia 
			  inner join itbimatric  on it06_guia   = it01_guia
			  where it14_guia is not null 
			    and itbicancela.it16_guia is null 
					and c.it03_princ is true
			    and it06_matric = $matric
			  order by it01_guia";


        }
	//die ($sql);
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",array (),false);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
        		 
		  $sql = "select $campos
			  from itbi 
			  inner join itbinome c  on c.it03_guia = it01_guia and upper(c.it03_tipo) = 'C' 
			  inner join itbinome t  on t.it03_guia = it01_guia and upper(t.it03_tipo) = 'T'
			  left  join itbicancela on it01_guia   = it16_guia 
			  left  join itbiavalia  on it14_guia   = it01_guia 
			  inner join itbimatric  on it06_guia   = it01_guia
			  where it14_guia is not null 
			    and itbicancela.it16_guia is null 
					and c.it03_princ is true
			    and it06_matric = $matric
				and it01_guia   = $pesquisa_chave
			  order by it01_guia";	 
			 
			 
          $result = $clitbi->sql_record($sql);
          if($clitbi->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$dl_comprador',false);</script>";
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