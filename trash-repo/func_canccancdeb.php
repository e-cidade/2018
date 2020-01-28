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
include("classes/db_cancdebitos_classe.php");


db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clcancdebitos = new cl_cancdebitos;

$clcancdebitos->rotulo->label("k20_codigo");
$clcancdebitos->rotulo->label("k20_data");
$clcancdebitos->rotulo->label("k20_descr");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
             <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tk20_codigo?>">
              <?=$Lk20_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
                db_input("k20_codigo",10,$Ik20_codigo,true,"text",4,"","chave_k20_codigo");
              ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tk20_data?>">
              <?=$Lk20_data?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
                db_input("k20_data",10,$Ik20_data,true,"text",4,"","chave_k20_data");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tk20_descr?>">
              <?=$Lk20_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?db_input("k20_descr",10,$Ik20_descr,true,"text",4,"","chave_k20_descr");?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cancdebitos.hide();">
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
       $sql = "select distinct
                      k20_codigo,
                      to_char(k20_data,'dd/mm/yyyy') as k20_data,
                      k20_descr,
               	      nome
                 from cancdebitos
                      inner join cancdebitosreg     on cancdebitosreg.k21_codigo             = cancdebitos.k20_codigo
                      inner join arrecad            on cancdebitosreg.k21_numpre             = arrecad.k00_numpre
                                                   and cancdebitosreg.k21_numpar             = arrecad.k00_numpar
                      left  join cancdebitosprocreg on cancdebitosprocreg.k24_cancdebitosreg = cancdebitosreg.k21_sequencia
        							inner join db_usuarios        on db_usuarios.id_usuario                = cancdebitos.k20_usuario 
							 where cancdebitosprocreg.k24_cancdebitosreg is null and k20_instit = ".db_getsession("DB_instit");
        if(isset($chave_k20_codigo) && (trim($chave_k20_codigo)!="") ){
           $sql .= " and k20_codigo = $chave_k20_codigo";
        }else if(isset($chave_k20_data) && (trim($chave_k20_data)!="") ){
           $sql .= " and k20_data like '$chave_k20_data%'";
        }else if(isset($chave_k20_descr) && (trim($chave_k20_descr)!="") ){
           $sql .= " and k20_descr = '$chave_k20_descr'";
        }
        $sql .= " order by k20_codigo, k20_data";				
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
					 $sql = "select distinct
												 k20_codigo,
												 to_char(k20_data,'dd/mm/yyyy') as k20_data,
											 	 k20_descr,
												 nome
									  from cancdebitos
												 inner join cancdebitosreg     on cancdebitosreg.k21_codigo             = cancdebitos.k20_codigo
												 inner join arrecad            on cancdebitosreg.k21_numpre             = arrecad.k00_numpre
																										  and cancdebitosreg.k21_numpar             = arrecad.k00_numpar
												 left  join cancdebitosprocreg on cancdebitosprocreg.k24_cancdebitosreg = cancdebitosreg.k21_sequencia
												 inner join db_usuarios        on db_usuarios.id_usuario                = cancdebitos.k20_usuario 
								  where cancdebitosprocreg.k24_cancdebitosreg is null and cancdebitos.k20_codigo = $pesquisa_chave and k20_instit = ".db_getsession("DB_instit");
          $result = $clcancdebitos->sql_record($sql);
          if($clcancdebitos->numrows!=0){
              db_fieldsmemory($result,0);
              echo "<script>".$funcao_js."('$k20_data',false);</script>";
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