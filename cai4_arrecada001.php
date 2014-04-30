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

/*
Programa principal para geranciar as autenticacoes, com totala autenticar
*/
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");

require_once("classes/db_cfautent_classe.php");

//------------------------------------------------------
//   Arquivos que verificam se o boletim já foi liberado ou naum
include("classes/db_boletim_classe.php");
$clverficaboletim =  new cl_verificaboletim(new cl_boletim);
//------------------------------------------------------

$clcfautent = new cl_cfautent();

$iInstit                 = db_getsession("DB_instit");
$iIp                     = db_getsession("DB_ip");
$lCadastroAutenticadora  = false;
$sSqlValidaAutenticadora = $clcfautent->sql_query_file(null, "1", '', "k11_ipterm = '{$iIp}' and k11_instit = {$iInstit}");
$rsValidaAutenticadora   = $clcfautent->sql_record($sSqlValidaAutenticadora);
if ($clcfautent->numrows > 0) {
  $lCadastroAutenticadora = true;  
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<? if (!$lCadastroAutenticadora) {
	$sMsg  = "Atenção!\\n\\n";
	$sMsg .= "Este endereço Ip não está cadastrado como Autenticadora!\\n\\n"; 
	$sMsg .= "Cadastre a autenticadora acessando o menu: \\n";
	$sMsg .= "Cadastros>>Autenticadoras>>Inclusão de Autenticadoras\\n\\n";
	$sMsg .= "Endereço Ip: {$iIp}\\n";
	db_msgbox($sMsg);
   }	
?>
<script>
function js_emitecheque(){
    js_OpenJanelaIframe('top.corpo','db_iframe_emite','cai4_emitecheque.php?valor='+document.form1.apagar.value,'Emissão de Cheque',true);
}
function js_zeratroco(){
document.form1.apagar.value=0;
document.form1.recebido.value=0;
document.form1.troco.value=0;
}
var system_os = new Browser();

if(system_os.system == 'Windows'){
  obexist = new ActiveXObject('Scripting.FileSystemObject');
  if(obexist.FileExists('c:\\autentica.exe') == false ) {
     alert('Arquivo C:\AUTENTICA NAO INSTALADO. NÃO SERÁ IMPRESSA AUTENTICAÇÃO, MAS, SERÁ REGISTRADA NO SISTEMA.');    
  }
  ob = new ActiveXObject('WScript.Shell');
}

function js_calculatroco(){
  document.form1.recebido.value = document.form1.recebido.value.replace(",",".");
  document.form1.troco.value = "";
  if(Number(document.form1.recebido.value) >= Number(document.form1.apagar.value)){
    var troco = Number(document.form1.recebido.value) - Number(document.form1.apagar.value);
    if(troco>=0 && !isNaN(troco)){
      document.form1.troco.value = troco.toFixed(2);
    }else{
      document.form1.recebido.value = "";
      document.form1.recebido.focus();
    }
  }else if(document.form1.recebido.value!=""){
    alert("O valor recebido deve ser maior que o valor a pagar.");
    document.form1.recebido.focus();
  }
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="auto" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="auto" align="left" valign="top" bgcolor="#CCCCCC"> 
		<table width="100%">
        <tr> 
          <td align="center">&nbsp;</td>
        </tr>
        <tr> 
          <td align="center" valign="middle"> 
		        <iframe height="80" width="990" name="numeros" src="cai4_arrecada002.php" ></iframe></td>
        </tr>
        <tr> 
          <td align="center"></td>
		  </tr>
        <tr> 
          <td align="center">
		        <iframe  height="300" width="990" name="recibos" src="cai4_arrecada004.php" ></iframe> 
          </td>
        </tr>
        <tr> 
          <td align="center">
		  
		        <form name="form1" method="post" action="">
              <strong>&Agrave; pagar: </strong> 
              <input type="text" name="apagar" readonly>
              <strong>&nbsp;Recebido: </strong> 
              <input type="text" name="recebido" onKeyUp="js_ValidaCampos(this,4,'Valor recebido','f','f',event);" onChange="js_calculatroco()">
              &nbsp;
              <strong>Troco:<input type="text" name="troco" readonly></strong>
              &nbsp;&nbsp; 
              <strong> 
                <input type="button" name="emite_cheque" value="Emite Cheque" onclick="js_emitecheque();">
                <input type="button" name="zera_troco" value="Zera Troco" onclick="js_zeratroco();">
              </strong>
            </form>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=140;
$func_iframe->posY=100;
$func_iframe->largura=1;
$func_iframe->altura=1;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>