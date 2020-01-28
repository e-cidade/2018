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
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>


function js_emite(){
  //js_controlarodape(true);
  qry  = 'ano_base='+ document.form1.ano_base.value;
  qry += '&codret='+ document.form1.codret.value;
  qry += '&nomeresp=' + document.form1.nomeresp.value;
  qry += '&cpfresp=' + document.form1.cpfresp.value;
  qry += '&dddresp=' + document.form1.dddresp.value;
  qry += '&foneresp=' + document.form1.foneresp.value;
  qry += '&pref_fun=' + document.form1.pref_fun.value;
  js_OpenJanelaIframe('top.corpo','db_iframe_geradirf','pes4_geradirf002.php?'+qry,'Gerando Arquivo',true);
}

function js_erro(msg){
  //js_controlarodape(false);
  top.corpo.db_iframe_geradirf.hide();
  alert(msg);
}
function js_fechaiframe(){
  db_iframe_geradirf.hide();
}
function js_controlarodape(mostra){
  if(mostra == true){
    document.form1.rodape.value = parent.bstatus.document.getElementById('st').innerHTML;
    parent.bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;<blink><strong><font color="red">GERANDO ARQUIVO</font></strong></blink>' ;
  }else{
    parent.bstatus.document.getElementById('st').innerHTML = document.form1.rodape.value;
  }
}

function js_detectaarquivo(arquivo,pdf){
//  js_controlarodape(false);
  top.corpo.db_iframe_geradirf.hide();
  listagem = arquivo+"#Download arquivo TXT (pagamento eletrônico)|";
  listagem+= pdf+"#Download relatório";
  js_montarlista(listagem,"form1");
}

</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

   <table  align="center">
    <form name="form1" method="post" action="" >
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td align="right" nowrap title="Digite o Ano Base" >
          <strong>Ano Base:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
          $sqlanomes = "select max(cast(r11_anousu as text)||lpad(cast(r11_mesusu as text),2,'0')) from cfpess";
          $resultanomes = db_query($sqlanomes);
          db_fieldsmemory($resultanomes,0);
          $ano_base = substr($max,0,4);
            db_input('ano_base',4,'',true,'text',2,'')
          ?>
        </td>
      </tr>
      <tr align="center">
        <td align="right" nowrap title="Código de Retenção" >
          <strong>Código Retencao:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            $codret = '0561';
            db_input('codret',4,'',true,'text',2,'')
	  ?>
        </td>
      </tr>
      <tr>
        <td align="right" nowrap title="Tirar por">
          <strong>Tirar por:&nbsp;&nbsp;</strong>
	</td>  
        <td align="left">
          <?
          $arr_ = array('p'=>'Pref','f'=>'Funpas','t'=>'Todos');
          db_select("pref_fun", $arr_, true, 1);
          ?>                             
        </td>                            
      </tr>                              
    <tr>                                 
     <td colspan="2"  align="center">    
     <fieldset>                          
        <legend><strong>Dados do Responsavel</strong></legend>
        <table width="100%">
      <tr>
        <td align="right" nowrap title="Nome do Responsavel " >
         <strong>Nome:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            db_input('nomeresp',40,'',true,'text',2,'')
	  ?>
        </td>
        <td align="right" nowrap title="DDD do Responsavel " >
         <strong>DDD:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            db_input('dddresp',4,'',true,'text',2,'')
	  ?>
        </td>
        <td align="right" nowrap title="Fone do Responsavel " >
         <strong>Fone:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            db_input('foneresp',12,'',true,'text',2,'')
	  ?>
        </td>
        <td align="right" nowrap title="Código Nacional de Pessoal FISICA" >
        <strong>CPF:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            db_input('cpfresp',14,'',true,'text',2,"  onBlur='js_verificaCGCCPF(this)' onKeyDown='return js_controla_tecla_enter(this,event);' onKeyUp='js_limpa(this)' ")
	  ?>
        </td>
      </tr>
	
        </table>
      </fieldset>
    </td>
  </tr>
      <tr>
	<td colspan="2" align = "center"> 
          <input  name="gera" id="gera" type="button" value="Gera" onclick="js_emite();" >
        </td>
      </tr>
  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>