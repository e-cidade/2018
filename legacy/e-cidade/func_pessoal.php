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
include("classes/db_pessoal_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clpessoal = new cl_pessoal;
$clrotulo = new rotulocampo;
$clpessoal->rotulo->label("r01_anousu");
$clpessoal->rotulo->label("r01_mesusu");
$clpessoal->rotulo->label("r01_regist");
$clpessoal->rotulo->label("r01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");
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
           <td align="right" nowrap title="Digite o Ano / Mes de competência" >
             <strong>Ano / Mês :&nbsp;&nbsp;</strong>
           </td>
           <td colspan='3'>
           <?
           if(!isset($chave_r01_anousu)){
           	 $chave_r01_anousu = db_anofolha();
           }
           db_input('DBtxt23',4,$IDBtxt23,true,'text',2,"",'chave_r01_anousu');
           ?>
           &nbsp;/&nbsp;
           <?
           if(!isset($chave_r01_mesusu)){
           	 $chave_r01_mesusu = db_mesfolha();
           }
           db_input('DBtxt25',2,$IDBtxt25,true,'text',2,"",'chave_r01_mesusu');
           ?>
           </td>
         </tr>
         <tr>
           <td width="4%" align="right" nowrap title="<?=$Tr01_regist?>">
           <?=$Lr01_regist?>
           </td>
           <td width="96%" align="left" nowrap> 
           <?
		   db_input("r01_regist",8,$Ir01_regist,true,"text",4,"","chave_r01_regist");
		   ?>
           </td>
           <td width="4%" align="right" nowrap title="<?=$Tr01_numcgm?>">
           <?=$Lr01_numcgm?>
           </td>
           <td width="96%" align="left" nowrap> 
           <?
           db_input("r01_numcgm",8,$Ir01_numcgm,true,"text",4,"","chave_r01_numcgm");
	       ?>
           </td>
         </tr>
         <tr> 
           <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
           <?=$Lz01_nome?>
           </td>
           <td width="96%" align="left" nowrap colspan='3'> 
           <?
           db_input("z01_nome",80,$Iz01_nome,true,"text",4,"","chave_z01_nome");
	       ?>
           </td>
         </tr>
         <tr> 
           <td colspan="4" align="center"> 
             <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframepessoal.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $dbwhere = " r01_mesusu = $chave_r01_mesusu ";
      $dbwhere.= " and r01_anousu = $chave_r01_anousu ";
      
      if(!isset($pesquisa_chave) || isset($filtroquery)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_pessoal.php")==true){
             include("funcoes/db_func_pessoal.php");
           }else{
           $campos = " pessoal.*";
           }
        }
        $sql = "";
        if((isset($chave_r01_regist) && (trim($chave_r01_regist)!=""))){
	         $sql = $clpessoal->sql_query(null,null,null,$campos,"r01_regist"," $dbwhere and r01_regist = $chave_r01_regist ");
             // echo $sql;
//             db_lovrot($sql,15,"()","",$funcao_js);
        }else if(isset($chave_r01_numcgm) && (trim($chave_r01_numcgm)!="") ){
	         $sql = $clpessoal->sql_query(null,null,null,$campos,"z01_nome"," $dbwhere and z01_numcgm = $chave_r01_numcgm ");
             // echo $sql;
//             db_lovrot($sql,15,"()","",$funcao_js);
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
	         $sql = $clpessoal->sql_query(null,null,null,$campos,"z01_nome"," $dbwhere and z01_nome like '$chave_z01_nome%' ");
             // echo $sql;
             // db_lovrot($sql,15,"()","",$funcao_js);
        }
        db_lovrot($sql,15,"()","",$funcao_js);
             // $sql = $clpessoal->sql_query(db_getsession("DB_anousu"),date("m",db_getsession("DB_datausu")),null,$campos,"r01_regist ");

      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          //die($clpessoal->sql_query($chave_r01_anousu,$chave_r01_mesusu,$pesquisa_chave));
          $result = $clpessoal->sql_record($clpessoal->sql_query($chave_r01_anousu,$chave_r01_mesusu,$pesquisa_chave));
          if($clpessoal->numrows!=0){
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