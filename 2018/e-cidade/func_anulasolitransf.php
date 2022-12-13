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
include("classes/db_matpedido_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmatpedido = new cl_matpedido;
$clmatpedido->rotulo->label("m97_sequencial");
$clmatpedido->rotulo->label("m97_sequencial");
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
            <td width="4%" align="right" nowrap title="<?=$Tm97_sequencial?>">
              <?=$Lm97_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("m97_sequencial",10,$Im97_sequencial,true,"text",4,"","chave_m97_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_atendsolitransf.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?

		// filtro para trazer requisição não automáticas
		$where  = " m97_origem=5 ";
		
		// filtro por departamento
    if (isset($sFiltro)) {
      if ($sFiltro == "almox") {
        $where .= " and m97_coddepto = ".db_getsession("DB_coddepto");
      }
    }
		
		// filtra todas as requisições pelas não atendidas e as parcialmente atendidas 			
	  $where .= " group by m97_sequencial,nome,"; 
 	  $where .= "		   matpedido.m97_login,";
	  $where .= "		   matpedido.m97_origem,";
 	  $where .= " 	       matpedido.m97_data,";
 	  $where .= " 	       matpedido.m97_coddepto,"; 
 	  $where .= "		   matpedido.m97_hora,";
 	  $where .= "		   matpedido.m97_obs,";
 	  $where .= "		   matpedido.m97_db_almox,"; 	  
 	  $where .= "		   matpedidoitem.m98_quant,";
 	  $where .= "		   matpedidoitem.m98_sequencial,";
 	  $where .= "		   db_almox.m91_depto"; 	  
  	  $where .= "		   having ";
      $where .= "          coalesce(m98_quant -";
	  $where .= "                   (select coalesce(sum(m82_quant),0) ";
	  $where .= "                    from matestoqueinimei as matinimei ";
      $where .= "                     inner join matestoqueinimeimatpedidoitem as q on q.m99_matestoqueinimei = matinimei.m82_codigo ";
	  $where .= "                    where  q.m99_matpedidoitem = m98_sequencial)";
	  $where .= "                   - ";	  
      $where .= "                   (select coalesce (sum(m103_quantanulada),0) ";
      $where .= "                    from matanulitem ";
      $where .= "                     inner join matanulitempedido on matanulitempedido.m101_matanulitem =matanulitem.m103_codigo ";
      $where .= "                    where  m101_matpedidoitem = m98_sequencial) ";
      $where .= "                  ,0) > 0";
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_matpedido.php")==true){
             include("funcoes/db_func_matpedido.php");
           }else{
           $campos = "matpedido.*";
           }
        }
        if(isset($chave_m97_sequencial) && (trim($chave_m97_sequencial)!="") ){
	         $sql = $clmatpedido->sql_query_almoxleft($chave_m97_sequencial,$campos,"m97_sequencial desc","m97_sequencial=$chave_m97_sequencial and $where");
				}else{
             $sql = $clmatpedido->sql_query_almoxleft("",$campos,"m97_sequencial desc","$where");
				}
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clmatpedido->sql_record($clmatpedido->sql_query_almoxleft($pesquisa_chave,"*","m97_sequencial desc","m97_sequencial=$pesquisa_chave and $where"));
          if($clmatpedido->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$m97_sequencial',false);</script>";
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