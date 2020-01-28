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
include("classes/db_veiccadposto_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clveiccadposto = new cl_veiccadposto;
$clveiccadposto->rotulo->label("ve29_codigo");
$clveiccadposto->rotulo->label("ve29_codigo");
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
<?     
    if (isset($param_tipo) && $param_tipo == 3){
?>       
          <tr> 
            <td width="4%" align="right" nowrap title="Tipo de posto">
						<b>Tipo de Posto</b>
            </td>
            <td width="96%" align="left" nowrap> 
					<?
            $tipo = array('0' => 'Todos', '1'=>'Interno','2'=>'Externo');
					  db_select('tipo',$tipo,true,2,"");
				  ?>
          </td>
          </tr>
<?
    }
?>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tve29_codigo?>">
              <?=$Lve29_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ve29_codigo",10,$Ive29_codigo,true,"text",4,"","chave_ve29_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_veiccadposto.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $dbwhere = "";
      $and     = "";
      if (isset($param_tipo) && trim($param_tipo) != ""){
        if ($param_tipo == 0){         // Externo
          $and     = " and ";
          $dbwhere = "descrdepto is null and z01_nome is not null";
        } else if ($param_tipo == 1){  // Interno
          $and     = " and ";
          $dbwhere = "z01_nome is null and descrdepto is not null";
        }
      }

      if(!isset($pesquisa_chave)) {
        $campos = "ve29_codigo,descrdepto,z01_nome, case when descrdepto is null and z01_nome is not null then 'EXTERNO' else case when descrdepto is not null then 'INTERNO' else 'EXTERNO' end end as tipo";
        if(isset($chave_ve29_codigo) && (trim($chave_ve29_codigo)!="") ){
	         $sql = $clveiccadposto->sql_query_tip(null,$campos,"ve29_codigo","ve29_codigo = $chave_ve29_codigo $and $dbwhere");
        }else{
           $sql = $clveiccadposto->sql_query_tip("",$campos,"ve29_codigo",$dbwhere);
        }
        $repassa = array();
        if(isset($chave_ve29_codigo)){
          $repassa = array("chave_ve29_codigo"=>$chave_ve29_codigo,"chave_ve29_codigo"=>$chave_ve29_codigo);
        }
        // echo $sql;
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clveiccadposto->sql_record($clveiccadposto->sql_query(null,"*",null,"ve29_codigo = $pesquisa_chave $and $dbwhere"));
          if($clveiccadposto->numrows!=0){
            db_fieldsmemory($result,0);
            if ($descrdepto!=""){
            	$posto=$descrdepto;
            }
            if ($z01_nome!=""){
            	$posto=$z01_nome;
            }
            echo "<script>".$funcao_js."('$posto',false);</script>";
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
js_tabulacaoforms("form2","chave_ve29_codigo",true,1,"chave_ve29_codigo",true);
</script>