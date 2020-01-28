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
include("classes/db_selecao_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$chave_r44_descr = isset($chave_r44_descr) ? stripslashes($chave_r44_descr) : '';

$clselecao = new cl_selecao;
$clselecao->rotulo->label("r44_selec");
$clselecao->rotulo->label("r44_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Tr44_selec?>">
              <?=$Lr44_selec?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r44_selec",3,$Ir44_selec,true,"text",4,"","chave_r44_selec");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr44_descr?>">
              <?=$Lr44_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r44_descr",70,$Ir44_descr,true,"text",4,"","chave_r44_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_selecao.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      
      $chave_r44_descr = addslashes($chave_r44_descr);
      
      /**
       * Caso parâmetro 'iGrupoSelecao' esteja setado via url filtra as seleções pelo grupo informado, 
       * caso contrário, filtra somente o grupo padrão
       */
      if ( empty($sGrupoSelecao) ) {
				$sWhere = "r44_gruposelecao = 1";
      } else {         
				$sWhere = "r44_gruposelecao in ({$sGrupoSelecao})";
			}
      
      if(!isset($pesquisa_chave)){

        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_selecao.php")==true){
             include("funcoes/db_func_selecao.php");
           }else{
           $campos = "selecao.*";
           }
        }
        
        if( isset($chave_r44_selec) ){
          if (  !DBNumber::isInteger($chave_r44_selec) ) {
        	  $chave_r44_selec = '';
          }
        }
        if(isset($chave_r44_selec) && (trim($chave_r44_selec)!="" && DBNumber::isInteger($chave_r44_selec) ) ){
	        $sql = $clselecao->sql_query("", db_getsession('DB_instit'), $campos, "r44_selec", " {$sWhere} and r44_selec = {$chave_r44_selec} and r44_instit = ".db_getsession('DB_instit'));
        }else if(isset($chave_r44_descr) && (trim($chave_r44_descr)!="") ){
	        $sql = $clselecao->sql_query("", db_getsession('DB_instit'), $campos, "r44_descr", " {$sWhere} and r44_descr like '$chave_r44_descr%' and r44_instit = ".db_getsession('DB_instit'));
        }else{
          $sql = $clselecao->sql_query("", db_getsession('DB_instit'), $campos, "r44_selec", " {$sWhere} and r44_instit = ".db_getsession('DB_instit'));
        }
        if( isset($chave_r44_descr) ){
        	$chave_r44_descr = str_replace("\\", "", $chave_r44_descr);
        }
//        echo $sql;
        db_lovrot($sql, 15, "()", "", $funcao_js);
        
      }else{

        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          $result = $clselecao->sql_record($clselecao->sql_query("", db_getsession('DB_instit'), "*", "", " {$sWhere} and r44_selec = $pesquisa_chave and r44_instit = ".db_getsession('DB_instit') ));

          if($clselecao->numrows!=0){

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$r44_descr',false);</script>";
            
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
	  (function(){
		  
		  if( document.getElementById('chave_r44_selec').value != '') {
			  var oRegex  = /^[0-9]+$/;
			  if ( !oRegex.test( document.getElementById('chave_r44_selec').value ) ) {
			    alert('Seleção deve ser preenchido somente com números!');
			    document.getElementById('chave_r44_selec').value = '';
			    return false;  
			  }
		  }
		  
	  })();
  </script>
  <?
}
?>