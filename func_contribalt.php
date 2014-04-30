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
include("classes/db_contrib_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcontrib = new cl_contrib;
$clrotulo = new rotulocampo;
$clrotulo->label('d08_notif');
$clrotulo->label('j01_matric');
$clrotulo->label('z01_nome');

//echo 'contribuicao :'.$contribuicao;
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
             <td width="4%" align="right" nowrap title="<?=$Td08_notif?>">
             <?=$Ld08_notif?>
             </td>
             <td width="96%" align="left" nowrap>
             <?
               db_input("d08_notif",6,$Id08_notif,true,"text",4,"","chave_notificacao");
             ?>
             </td>
          </tr>
          <tr>
             <td width="4%" align="right" nowrap title="<?=$Tj01_matric?>">
	     <?=$Lj01_matric?>
             </td>
             <td width="96%" align="left" nowrap>
             <?
               db_input("j01_matric",4,'',true,"text",4,"","chave_matricula");
             ?>
             </td>
          </tr>
          <tr>
             <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
	     <?=$Lz01_nome?>
             </td>
             <td width="96%" align="left" nowrap>
             <?
               db_input("z01_nome",40,'',true,"text",4,"","chave_nome");
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
      if($contribuicao == ''){
//	echo "<script>alert('Nenhuma contribuição foi escolhida.')</script>";
//	echo "<script>location.href=parent.con2_geranotif001.php</script>";
	db_redireciona('db_erros.php?fechar=true&db_erro=Nenhuma contribuição foi escolhida.');
      }
      if(!isset($pesquisa_chave)){
	if(isset($chave_notificacao) && $chave_notificacao != ''){
           if(isset($campos)==false){
              if(file_exists("funcoes/db_func_contrib.php")==true){
                include("funcoes/db_func_contrib.php");
              }else{
              $campos = "contrib.oid,contrib.*";
              }
           }
           $sql = $clcontrib->sql_query_not("","","j01_matric,z01_numcgm,z01_nome,d08_notif",""," d07_contri = ".$contribuicao." and j01_matric in (select d09_matric from contricalc where d09_contri = ".$contribuicao.") and d08_notif = ".$chave_notificacao);
           db_lovrot($sql,15,"()","",$funcao_js);
	}elseif(isset($chave_matricula) && $chave_matricula != ''){
           if(isset($campos)==false){
              if(file_exists("funcoes/db_func_contrib.php")==true){
                include("funcoes/db_func_contrib.php");
              }else{
              $campos = "contrib.oid,contrib.*";
              }
           }
           $sql = $clcontrib->sql_query_not("","","j01_matric,z01_numcgm,z01_nome,d08_notif",""," d07_contri = ".$contribuicao." and j01_matric in (select d09_matric from contricalc where d09_contri = ".$contribuicao.") and j01_matric = ".$chave_matricula);
           db_lovrot($sql,15,"()","",$funcao_js);
	}elseif(isset($chave_nome) && $chave_nome != ''){
           if(isset($campos)==false){
              if(file_exists("funcoes/db_func_contrib.php")==true){
                include("funcoes/db_func_contrib.php");
              }else{
              $campos = "contrib.oid,contrib.*";
              }
           }
           $sql = $clcontrib->sql_query_not("","","j01_matric,z01_numcgm,z01_nome,d08_notif",""," d07_contri = ".$contribuicao." and j01_matric in (select d09_matric from contricalc where d09_contri = ".$contribuicao.") and z01_nome like ('".$chave_nome."%')");
           db_lovrot($sql,15,"()","",$funcao_js);
	}else{
           if(isset($campos)==false){
              if(file_exists("funcoes/db_func_contrib.php")==true){
                include("funcoes/db_func_contrib.php");
              }else{
              $campos = "contrib.oid,contrib.*";
              }
           }
           $sql = $clcontrib->sql_query_not("","","j01_matric,z01_numcgm,z01_nome,d08_notif",""," d07_contri = ".$contribuicao." and j01_matric in (select d09_matric from contricalc where d09_contri = ".$contribuicao.") ");
           db_lovrot($sql,15,"()","",$funcao_js);
	}


	
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clcontrib->sql_record($clcontrib->sql_query_not("","","*",""," d07_matric = ".$pesquisa_chave." and d07_contri = ".$contribuicao."  and j01_matric in (select d09_matric from contricalc where d09_contri = ".$contribuicao.")"));
          if($clcontrib->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$oid',false);</script>";
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