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
include("classes/db_atendimento_top_classe.php");
include("classes/db_db_usuclientes_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clatendimento_top = new cl_atendimento_top;
$cldb_usuclientes  = new cl_db_usuclientes;
$cldb_usuclientes->rotulo->label("at10_codigo");
$cldb_usuclientes->rotulo->label("at10_codcli");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_incluir_cliente(){
  js_OpenJanelaIframe('','db_iframe_db_usucliente','func_db_usuclientesalt_usuario.php?cliente='+<?=$cliente?>,'Pesquisa',true);
}

function js_atualiza(codigo,nome){
  js_OpenJanelaIframe('','db_iframe_db_usucliente','func_db_usuclientesalt_usuario.php?pesquisa=true&cliente='+<?=$cliente?>+'&codusu='+codigo,'Pesquisa',true);   
}


</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tat10_codigo?>">
              <?=$Lat10_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("at10_codigo",10,$Iat10_codigo,true,"text",4,"","chave_at10_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tat10_codcli?>">
              <?=$Lat10_codcli?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("at10_codcli",4,$Iat10_codcli,true,"text",4,"","chave_at10_codcli");
		       ?>
            </td>
          </tr>
          <tr>
            <td><b>Top de Atendimento:</b></td>
          	<td>
          	<? 
          	   $rs_atend_top = $clatendimento_top->sql_record($clatendimento_top->sql_query(null,"at14_usuario,at10_nome","at14_qtd desc limit 10","at14_codcli = $cliente"));
          	   db_selectrecord("atend_top",$rs_atend_top,true,1,"","chave_atend_top");
          	?>
          	</td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" >
              <input name="Incluir" type="button" id="incluir" value="Incluir Usuário" onClick="js_incluir_cliente()">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $where = " 1=1 ";
      if (isset($cliente)&&$cliente!=""){
      	$where = " at10_codcli = $cliente ";
      }
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_db_usuclientes.php")==true){
             include("funcoes/db_func_db_usuclientes.php");
           }else{
           $campos = "db_usuclientes.*";
           }
        }
        if(isset($chave_at10_codigo) && (trim($chave_at10_codigo)!="") ){
	         $sql = $cldb_usuclientes->sql_query($chave_at10_codigo,$campos,"at10_nome","at10_codigo = $chave_at10_codigo and $where");
        }else if(isset($chave_at10_codcli) && (trim($chave_at10_codcli)!="") ){
	         $sql = $cldb_usuclientes->sql_query("",$campos,"at10_nome"," at10_codcli like '$chave_at10_codcli%' and $where");
        }else if(isset($chave_atend_top)&&$chave_atend_top!="") {
	        	  if(isset($chave_atend_topdescr)&&$chave_atend_topdescr!="") {
	        		  if($chave_atend_top != $chave_atend_topdescr) {
				          $sql = $cldb_usuclientes->sql_query("",$campos,"at10_nome","at10_usuario = $chave_atend_topdescr and $where");
	        	      }
	        	      else {
				          $sql = $cldb_usuclientes->sql_query("",$campos,"at10_nome","at10_usuario = $chave_atend_top and $where");
	        	      }
	              }
	              else {
			          $sql = $cldb_usuclientes->sql_query("",$campos,"at10_nome","at10_usuario = $chave_atend_topd and $where");
	              }    	
        }else {
           $sql = $cldb_usuclientes->sql_query("",$campos,"at10_nome"," $where ");
        }
        
        $sql = "select at10_usuario,
                       at10_login,
                       at10_nome,
                       rh01_nasc,
  					   case when substr(rh01_nasc,6,5)::varchar = '".date("m-d",db_getsession("DB_datausu"))."'::varchar then 'Hoje' else  '' end::varchar as dl_aniversário,
					   ('".date("Y-m-d",db_getsession("DB_datausu"))."'::date - rh01_nasc)/365 as dl_anos,
                       rh01_sexo,
                       lotacao as dl_Lotacao,
                       cargo::varchar(50) as dl_cargo,
                        localtrabalho as dl_local
                       from ($sql) as x left join acesso_clientes_dados on at10_login = login  and cliente = at10_codcli";
        db_lovrot($sql,15,"()","",$funcao_js, "Atualiza|js_atualiza", "NoMe", array (), false);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cldb_usuclientes->sql_record($cldb_usuclientes->sql_query(null,"*","at10_nome","at10_usuario=$pesquisa_chave and $where"));
          if($cldb_usuclientes->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$at10_nome',false);</script>";
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