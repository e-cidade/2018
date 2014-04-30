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
include("classes/db_agualeitura_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clagualeitura = new cl_agualeitura;
$clrotulo = new rotulocampo;
$clagualeitura->rotulo->label();
$clrotulo->label("x01_matric");
$clrotulo->label("x21_codhidrometro");
$clrotulo->label("x04_nrohidro");
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
	  <td nowrap title="<?=@$Tx21_exerc?>" align="right">
	    <b><?=@$RLx21_exerc?>&nbsp;/&nbsp;<?=@$RLx21_mes?>:</b>
	  </td>
	  <td nowrap>
	    <?
	    if(!isset($chave_x21_exerc) || (isset($chave_x21_exerc) && trim($chave_x21_exerc) == "")){
	      $chave_x21_exerc = db_getsession("DB_anousu");
	    }
	    db_input('x21_exerc',4,$Ix21_exerc,true,'text',1,"","chave_x21_exerc");
	    ?>
	    <b>&nbsp;/&nbsp;</b>
	    <?
	    if(!isset($chave_x21_mes) || (isset($chave_x21_mes) && trim($chave_x21_mes) == "")){
	      $chave_x21_mes = date("m",db_getsession("DB_datausu"));
	    }
	    db_input('x21_mes',2,$Ix21_mes,true,'text',1,"","chave_x21_mes");
	    ?>
          </td>
        </tr>
        <tr>
          <td width="4%" align="right" nowrap title="<?=$Tx21_codhidrometro?>">
            <?=$Lx21_codhidrometro?>
          </td>
          <td width="96%" align="left" nowrap>
            <?
	    db_input("x21_codhidrometro",8,$Ix21_codhidrometro,true,"text",4,"","chave_x21_codhidrometro");
	    ?>
          </td>
          <td width="4%" align="right" nowrap title="<?=$Tx04_nrohidro?>">
            <?=$Lx04_nrohidro?>
          </td>
          <td width="96%" align="left" nowrap>
            <?
	    db_input("x04_nrohidro",8,$Ix04_nrohidro,true,"text",4,"","chave_x04_nrohidro");
	    ?>
          </td>
        </tr>
        <tr>
          <td width="4%" align="right" nowrap title="<?=$Tx01_matric?>">
            <?=$Lx01_matric?>
          </td>
          <td width="96%" align="left" nowrap>
            <?
	    db_input("x01_matric",8,$Ix01_matric,true,"text",4,"","chave_x01_matric");
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
          <td width="96%" align="left" nowrap colspan="3">
            <?
	    db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","chave_z01_nome");
	    ?>
          </td>
        </tr>
        <tr> 
          <td colspan="4" align="center"> 
            <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
            <input name="limpar" type="reset" id="limpar" value="Limpar" >
            <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_agualeitura.hide();">
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
          if(file_exists("funcoes/db_func_agualeitura.php")==true){
            include("funcoes/db_func_agualeitura.php");
          }else{
            $campos = "agualeitura.*";
          }
        }

      	$dbwhere = " x21_exerc = ".$chave_x21_exerc." and x21_mes = ".$chave_x21_mes;

        if(isset($chave_x21_codhidrometro) && (trim($chave_x21_codhidrometro)!="") ){
          $sql = $clagualeitura->sql_query_pesquisa("",$campos,"x21_codhidrometro",$dbwhere." and x21_codhidrometro = $chave_x21_codhidrometro ");
        }else if(isset($chave_x04_nrohidro) && (trim($chave_x04_nrohidro)!="") ){
          $sql = $clagualeitura->sql_query_pesquisa("",$campos,"x04_nrohidro",$dbwhere." and x04_nrohidro like '$chave_x04_nrohidro%' ");
        }else if(isset($chave_x01_matric) && (trim($chave_x01_matric)!="") ){
          $sql = $clagualeitura->sql_query_pesquisa("",$campos,"x01_matric",$dbwhere." and x01_matric = $chave_x01_matric ");
        }else if(isset($chave_z01_numcgm) && (trim($chave_z01_numcgm)!="") ){
          $sql = $clagualeitura->sql_query_pesquisa("",$campos,"z01_numcgm",$dbwhere." and z01_numcgm = $chave_z01_numcgm ");
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
          $sql = $clagualeitura->sql_query_pesquisa("",$campos,"z01_nome",$dbwhere." and z01_nome like '$chave_z01_nome%' ");
        }else{
          // $sql = $clagualeitura->sql_query("",$campos,"x21_codleitura","");
          $sql = "";
        }
				//die($sql);
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clagualeitura->sql_record($clagualeitura->sql_query_pesquisa($pesquisa_chave));
          if($clagualeitura->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$x21_codleitura',false);</script>";
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