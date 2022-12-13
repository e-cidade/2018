<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_loteimpressaocartaoidentificacao_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clloteimpressaocartaoidentificacao = new cl_loteimpressaocartaoidentificacao;
$clloteimpressaocartaoidentificacao->rotulo->label("ed305_sequencial");
$clloteimpressaocartaoidentificacao->rotulo->label("ed305_usuario");


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
            <td width="4%" align="right" nowrap title="<?=$Ted305_sequencial?>">
              <?=$Led305_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ed305_sequencial",10,$Ied305_sequencial,true,"text",4,"","chave_ed305_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ted305_usuario?>">
              <?=$Led305_usuario?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ed305_usuario",10,$Ied305_usuario,true,"text",4,"","chave_ed305_usuario");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_loteimpressaocartaoidentificacao.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      
			/**
			 * Se for modulo escola faz where pelo departamento logado
			 */
			$sWhere = "";
			$sAnd   = "";
			if (db_getsession('DB_modulo') == 1100747) {
			  
				$sWhere = " ed18_i_codigo = ".db_getsession("DB_coddepto");
				$sAnd   = " and ";
			}
      $campos  = " distinct           ";
      $campos .= " ed305_sequencial,  "; 
      $campos .= " nome,              ";
      $campos .= " ed305_data,        ";
      $campos .= " ed305_hora,        ";
      $campos .= " ( select count(*)  ";
      $campos .= "     from loteimpressaocartaoidentificacaoaluno  ";
      $campos .= "    where ed306_loteimpressaocartaoidentificacao = loteimpressaocartaoidentificacao.ed305_sequencial ) as dl_qtd_cartoes ";
      
      if(!isset($pesquisa_chave)){
        if(isset($chave_ed305_sequencial) && (trim($chave_ed305_sequencial)!="") ){
         	 $sWhere .= "$sAnd ed305_sequencial = $chave_ed305_sequencial";
	         $sql     = $clloteimpressaocartaoidentificacao->sql_query($chave_ed305_sequencial, $campos, "ed305_sequencial", $sWhere);
        }else if(isset($chave_ed305_usuario) && (trim($chave_ed305_usuario)!="") ){
        	 $sWhere .= "$sAnd ed305_usuario like '$chave_ed305_usuario%' ";
	         $sql = $clloteimpressaocartaoidentificacao->sql_query_lotes("",$campos,"ed305_usuario",$sWhere);
        }else{
           $sql = $clloteimpressaocartaoidentificacao->sql_query_lotes("",$campos,"ed305_sequencial",$sWhere);
        }
        $repassa = array();
        if(isset($chave_ed305_usuario)){
          $repassa = array("chave_ed305_sequencial"=>$chave_ed305_sequencial,"chave_ed305_usuario"=>$chave_ed305_usuario);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
        	$sWhere .= "$sAnd ed305_sequencial = $pesquisa_chave";
          $result = $clloteimpressaocartaoidentificacao->sql_record($clloteimpressaocartaoidentificacao->sql_query_lotes(null,"*",null,$sWhere));
          if($clloteimpressaocartaoidentificacao->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ed305_usuario',false);</script>";
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
js_tabulacaoforms("form2","chave_ed305_usuario",true,1,"chave_ed305_usuario",true);
</script>