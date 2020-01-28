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
include("classes/db_afasta_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clafasta = new cl_afasta;
$clrotulo = new rotulocampo;
$clafasta->rotulo->label();
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
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
          if(!isset($chave_r45_anousu)){
            $chave_r45_anousu = db_anofolha();
          }
          db_input('DBtxt23',4,$IDBtxt23,true,'text',2,"",'chave_r45_anousu');
          ?>
          &nbsp;/&nbsp;
          <?
          if(!isset($chave_r45_mesusu)){
            $chave_r45_mesusu = db_mesfolha();
          }
          db_input('DBtxt25',2,$IDBtxt25,true,'text',2,"",'chave_r45_mesusu');
          ?>
          </td>
        </tr>
        <tr>
          <td width="4%" align="right" nowrap title="<?=$Tr45_regist?>">
          <?=$Lr45_regist?>
          </td>
          <td width="96%" align="left" nowrap> 
          <?
          db_input("r45_regist",8,$Ir45_regist,true,"text",4,"","chave_r45_regist");
		      ?>
          </td>
          <td width="4%" align="right" nowrap title="<?=$Tz01_numcgm?>">
          <?=$Lz01_numcgm?>
          </td>
          <td width="96%" align="left" nowrap> 
          <?
          db_input("z01_numcgm",8,$Iz01_numcgm,true,"text",4,"","chave_z01_numcgm");
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
          <td colspan="2" align="center"> 
            <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
            <input name="limpar" type="reset" id="limpar" value="Limpar" >
            <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_afasta.hide();">
          </td>
        </tr>
      </form>
      </table>
    </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $dbwhere = "r45_anousu = $chave_r45_anousu and r45_mesusu = $chave_r45_mesusu ";
      if(isset($retorno)){
      	$dbwhere.= " and r45_dtreto is null ";
      }
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_afasta.php")==true){
             include("funcoes/db_func_afasta.php");
           }else{
           $campos = "afasta.oid as db_oid,afasta.*";
           }
        }
        if((isset($chave_r45_regist) && (trim($chave_r45_regist)!=""))){
	         $sql = $clafasta->sql_query(null, $campos,"r45_regist"," r45_regist = $chave_r45_regist and ".$dbwhere);
        }else if((isset($chave_z01_numcgm) && (trim($chave_z01_numcgm)!=""))){
	         $sql = $clafasta->sql_query(null, $campos,"z01_numcgm"," z01_numcgm = $chave_z01_numcgm and ".$dbwhere);
        }else if((isset($chave_z01_nome) && (trim($chave_z01_nome)!=""))){
	         $sql = $clafasta->sql_query(null, $campos,"z01_nome"," z01_nome like '$chave_z01_nome%' and ".$dbwhere);
        }else{
	         $sql = $clafasta->sql_query(null, $campos,"",$dbwhere);
        }
//        die($sql);
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
        $result = $clafasta->sql_record($clafasta->sql_query($pesquisa_chave,"*","",$dbwhere));
          if($clafasta->numrows!=0){
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