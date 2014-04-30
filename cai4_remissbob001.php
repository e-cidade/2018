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
include("libs/db_libcaixa.php");

include("classes/db_cfautent_classe.php");
$clcfautent = new cl_cfautent;
  
   //{============================== 
  //rotina que verifica se o ip do usuario irá imprimir autenticar ou naum ira fazer nada 
      $result99 = $clcfautent->sql_record($clcfautent->sql_query_file(null,"k11_tipautent as tipautent",'',"k11_ipterm = '".$HTTP_SERVER_VARS['REMOTE_ADDR']."'"));
      if($clcfautent->numrows > 0){
	db_fieldsmemory($result99,0);
      }else{
	db_msgbox("Cadastre o ip ".$HTTP_SERVER_VARS['REMOTE_ADDR']." como um caixa.");
	die();
	//db_redireciona('');
      }    
    //============================}

$clautenticar= new cl_autenticar;
$ip = db_getsession("DB_ip");
$porta = 5001;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_relatorio() {
  var F = document.form1;
  var data = F.data_ano.value+'-'+F.data_mes.value+'-'+F.data_dia.value;
  window.open('cai4_remissbob002.php?id='+F.caixa.value+'&data='+data+'&<?=db_getsession()?>','','location=0');
}
function js_imprime(tipo){
  obj = document.form1;
  dia = obj.data_dia.value;
  mes = obj.data_mes.value;
  ano = obj.data_ano.value;
  js_OpenJanelaIframe('top.corpo','db_iframe_imprime','cai4_remissbob003.php?tipo='+tipo+'&dia='+dia+'&mes='+mes+'&ano='+ano,'Impressão',true,150,200,300,200);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.elements[0].select()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <br>
	<Center>
<form name="form1" method="post">
          <table border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td nowrap colspan="3" align="center">
	       <fieldset>
		<strong>Data:</strong>
		&nbsp;&nbsp; 
		<?=db_data("data",date("d"),date("m"),date("Y"))?>
		</fieldset>
	      </td>
            </tr>
            <tr>
              <td height="25" nowrap align="center">
	        <fieldset>
	          <input name="cabecalho" type="button" id="pesquisar" value="Reemite cabeçalho" onclick="js_imprime('cabecalho');">
	        </fieldset>
               </td>  		
               <td>  		
	        <fieldset>
  	          <input name="autenticacao" type="button" id="pesquisar" value="Reemite autenticação" onclick="js_imprime('autenticacao');">
	        </fieldset>
               </td>  		
               <td>  		
	        <fieldset>
	           <input name="fechamento" type="button" id="pesquisar" value="Fechamento" onclick="js_imprime('fechamento');">
	        </fieldset>
              </td>
            </tr>
          </table>
        </form>
      </Center>
	</td>
  </tr>
</table>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>	
</body>
</html>
<?
if($tipautent==1){
  $clautenticar->verifica($ip,$porta);
  if($clautenticar->erro==true){
   db_msgbox($clautenticar->erro_msg);
  }    
}  
?>