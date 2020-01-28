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
include("classes/db_atendimento_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_atendareatec_classe.php");
include("classes/db_atendtipoausencia_classe.php");
include("classes/db_atendtecnicoocupado_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clatendimento = new cl_atendimento;
$cldb_usuarios = new cl_db_usuarios;
$clatendareatec= new cl_atendareatec;
$clatendtecnicoocupado= new cl_atendtecnicoocupado;
$clatendtipoausencia= new cl_atendtipoausencia;

$clatendimento->rotulo->label("at02_codatend");
$clatendimento->rotulo->label("at02_codcli");

$clatendtipoausencia->rotulo->label("at71_codigo");
$clatendtipoausencia->rotulo->label("at71_descr");





if(isset($Confirmar)){


  $clatendtecnicoocupado->atendimento_tecnico_registra($at71_codigo,$clatendtecnicoocupado,$clatendtipoausencia);


}


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_enviar() {
	document.form1.submit();
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width="100%" height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table border="0" align="center" cellspacing="0">
	     <form name="form1" method="post" action="" >
	     
          <tr>
          <td align="right" width="50%" nowrap >
          <b>Usuário:</b>&nbsp;&nbsp;          
          </td>
          <td align="left" nowrap>
		    <?      
		    if (!isset($at40_tecnico)) {
		      global $at40_tecnico;
		      $at40_tecnico = db_getsession("DB_id_usuario");
		    }
		    db_selectrecord('at40_tecnico',($cldb_usuarios->sql_record($cldb_usuarios->sql_query(null,"id_usuario,nome","nome"," usuarioativo = '1' and usuext = 0"))),true,1,"", "", "", "0-Todos", "js_enviar()");          
		   
		    ?>      
          </td>
           </tr>          
          <tr>          
           
            <td align="right" nowrap title="<?=$Tat02_codatend?>">
              <?=$Lat02_codatend?>
            </td>
            <td align="left" nowrap> 
              <?
		       db_input("at02_codatend",4,$Iat02_codatend,true,"text",4,"","chave_at02_codatend");
		       ?>
            </td>
          </tr>
          <tr> 
            <td  align="right" nowrap title="<?=$Tat02_codcli?>">
              <?=$Lat02_codcli?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("at02_codcli",0,$Iat02_codcli,true,"text",4,"","chave_at02_codcli");
		       ?>
            </td>
          </tr>
          
          
          
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
              <?
              $result_atend = $clatendtecnicoocupado->sql_record($clatendtecnicoocupado->sql_query(null,"at72_codtipo as at71_codigo",null," at72_id_usuario = ".db_getsession("DB_id_usuario")));          
              if($clatendtecnicoocupado->numrows>0){
                 db_fieldsmemory($result_atend,0,0);
              }
	      db_selectrecord('at71_codigo',($clatendtipoausencia->sql_record($clatendtipoausencia->sql_query(null,"at71_codigo,at71_descr","at71_codigo"))),true,1);          
              ?>
              <input name="Confirmar" type="submit" id="confirmar" value="Confirmar">
              <input name="Consulta_atendtecnico" type="submit" id="consulta_atendtecnico" value="Ténicos Ocupados" onclick='js_consulta_atend()'>
<script>
function js_consulta_atend(){
  js_OpenJanelaIframe('top.corpo','db_iframe_atendtecnico','func_atendtecnicoocupado.php','Pesquisa Técnicos Ocupados',true);
}
</script>              
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
            $campos = "distinct on (at02_codatend) at02_codatend,at06_datalanc as dl_data,at06_horalanc as dl_hora,at01_nomecli as dl_cliente,substr(at10_nome, 1, 20) as dl_solicitante,at04_descr as dl_Contato_por,login as dl_Tecnico, at25_descr as area, at02_observacao as dl_Obs,at01_obs";
           // $campos = "at02_codatend";
        }
        if(isset($chave_at02_codatend) && (trim($chave_at02_codatend)!="") ){
        	 if($opcao=="incluir") {
	         	 $sql = $clatendimento->sql_query_inc($chave_at02_codatend,$campos,"at02_codatend desc","at02_codatend = $chave_at02_codatend and atenditem.at05_codatend is null and at02_codtipo >= 100 and atendorig.at11_origematend is null");
        	 }
        	 
        	 if($opcao=="alterar") {
	         	 $sql = $clatendimento->sql_query_inc($chave_at02_codatend,$campos,"at02_codatend desc","at02_codatend = $chave_at02_codatend and at02_codtipo >= 100 and atendorig.at11_origematend is null");
        	 }
        }else if(isset($chave_at02_codcli) && (trim($chave_at02_codcli)!="") ){
        	 if($opcao=="incluir") {
		         $sql = $clatendimento->sql_query_inc("",$campos,"at02_codcli desc"," at02_codcli like '$chave_at02_codcli%' and atenditem.at05_codatend is null and at02_codtipo >= 100 and atendorig.at11_origematend is null");
        	 }

        	 if($opcao=="alterar") {
		         $sql = $clatendimento->sql_query_inc("",$campos,"at02_codcli desc"," at02_codcli like '$chave_at02_codcli%' and at02_codtipo >= 100 and atendorig.at11_origematend is null");
        	 	 
        	 }
        }else{
           if($opcao=="incluir") {
           	
           	if (isset($at40_tecnico) and $at40_tecnico != "0") {
	            $sql = $clatendimento->sql_query_inc("",$campos,"at02_codatend desc","at02_datafim is null and at02_codtipo >= 100 and atendorig.at11_origematend is null and atendimentosituacao.at16_situacao  in (1, 4) and (tecnico.at03_id_usuario=$at40_tecnico or (at28_atendcadarea is not null and at27_usuarios = $at40_tecnico))"); 

           	 
           	} else {
           	  $sql = $clatendimento->sql_query_inc("",$campos,"at02_codatend desc","at02_datafim is null and at02_codtipo >= 100 and atendorig.at11_origematend is null and atendimentosituacao.at16_situacao in (1, 4)");
           	
           	}
	           
           }	

           if($opcao=="alterar") {
	           $sql = $clatendimento->sql_query_inc("",$campos,"at02_codatend desc","at02_codtipo >= 100 and atendorig.at11_origematend is null");
          
           }	
        }
       
		  $repassa = array();
		//die($sql);	 
		//db_lovrot($sql,15,"()","",$funcao_js); 
		//db_lovrot($sql,30,"()","",$funcao_js,null,"NoMe",$repassa,false);
        db_lovrot($sql,20,"()","",$funcao_js, "", "NoMe", array (), false);
        // db_criatabela(pg_exec($sql));
          
      }else{ 
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
        	if($opcao=="incluir") {
	            $result = $clatendimento->sql_record($clatendimento->sql_query_inc(null,"*",null,"at02_codatend = $pesquisa_chave and atenditem.at05_codatend is null and at02_codtipo >= 100 and atendorig.at11_origematend is null"));
        	}

        	if($opcao=="alterar") {
	            $result = $clatendimento->sql_record($clatendimento->sql_query_inc(null,"*",null,"at02_codatend = $pesquisa_chave and at02_codtipo >= 100 and atendorig.at11_origematend is null"));
        	}
        	
	        if($clatendimento->numrows!=0){
	            db_fieldsmemory($result,0);
	            echo "<script>".$funcao_js."('$at02_codcli',false);</script>";
	        }
	        else {
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
document.form1.chave_at02_codatend.focus();
document.form1.chave_at02_codatend.select();
  </script>
  <?
}
?>