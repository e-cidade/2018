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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$oRotulo = new rotulocampo();
$oRotulo->label("p58_codproc");
$oRotulo->label("numeroProcesso");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function abre() {

  var sNumeroInicial = $F("p58_codproc");
  var sNumeroFinal   = $F("p58_codprocfin");

  if (sNumeroInicial == '' || sNumeroFinal == '') {
    
    alert('Selecione um intervalo de processos.');
    return false;
  }
  
  url  = "pro4_capaprocesso.php?";
  url += "&numeroProcessoInicial=" + sNumeroInicial;
  url += "&numeroProcessoFinal="   + sNumeroFinal;
  
  window.open(url,'','location=0');
  
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="100%" height="18">&nbsp;</td>
  </tr>
</table>
<center>
<form method="post" name="form1" onSubmit="abre()">

  <fieldset style="width: 300px; margin-top: 20px;">
  <legend><b>Reimpressão da Capa</b></legend>
    <table border="0" cellspacing="0" cellpadding="0" align='left'>
      <tr>
        <td align='left'>
          <table border='0' align='left'>
            <tr>
              <td nowrap="nowrap">
                <?=db_ancora('Processo:',"js_pesquisaprotocolo(true, 'p58_codproc');",1); ?>
              </td>
              <td>
                <input type="text" size="10" id='p58_codproc' name="p58_codproc" onChange="js_pesquisaprotocolo(false, 'p58_codproc');">
              </td>
              <td>
                <!-- <b>Até</b> -->
              </td>
              <td>
               <!--  <input type="checkbox" name="entre" value="1"> -->
              </td>
              <td nowrap="nowrap">
                <?=db_ancora("Até:","js_pesquisaprotocolo(true, 'p58_codprocfin');",1);?>
              </td>
              <td>
                <input type="text" size="10" id='p58_codprocfin' name="p58_codprocfin" onChange="js_pesquisaprotocolo(false, 'p58_codprocfin');">
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </fieldset>
  <input type="button" value="Imprimir" onclick="abre()" style="margin-top: 10px;">
  </center>
</form>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>

$('p58_codproc')   .value = '';
$('p58_codprocfin').value = '';


function js_pesquisaprotocolo(mostra, campo) {
  
  var sCampo               = $(campo);
  var sFuncaoRetorno       = 'js_mostra1';
  var sFuncaoRetornoChange = 'js_mostra';  
  if (sCampo.id == 'p58_codprocfin') {

    sFuncaoRetorno       = 'js_mostra2';
    sFuncaoRetornoChange = 'js_mostraFim'; 
  }
  if(mostra==true) {
  
    js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_despint',
                        //'func_procdespint.php?reimprime=1&grupo=1&funcao_js=parent.'+sFuncaoRetorno+'|p58_codproc|p58_requer',
                        'func_procdespint.php?reimprime=1&grupo=1&funcao_js=parent.'+sFuncaoRetorno+'|dl_processo|p58_requer',
                        'Pesquisar Protocolo',
                        true);
  } else {
     if (sCampo.value != '') {
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_despint',
                            'func_procdespint.php?reimprime=1&grupo=1&pesquisa_chave='+sCampo.value+
                            '&funcao_js=parent.'+sFuncaoRetornoChange ,'Pesquisar Protocolo',
                            false);
     }
  }
}

function js_mostra( numeroProcesso, chave, nome, erro){


  $("p58_codproc"). value = numeroProcesso;
  if (erro == true) { 
  
    document.form1.p58_codproc.focus(); 
    document.form1.p58_codproc.value = ''; 
  }
}

function js_mostra1(chave1, chave2) {

  document.form1.p58_codproc.value = chave1;
  db_iframe_despint.hide();
}

function js_mostraFim(chave1, chave2) {

  document.form1.p58_codprocfin.value = chave1;
  db_iframe_despint.hide();
}

function js_mostra2(chave1, chave2) {

  document.form1.p58_codprocfin.value = chave1;
  db_iframe_despint.hide();
}
</script>