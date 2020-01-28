<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
//include(modification("classes/db_materialestoquegrupo_classe.php"));
include(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js, widgets/dbtextField.widget.js,
               dbautocomplete.widget.js, dbcomboBox.widget.js, prototype.maskedinput.js, DBViewEstruturaValor.js, DBViewOrganograma.js");
  db_app::load("estilos.css, grid.style.css");
  ?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
    <center>
    <div id='ctnTela'>
    </div>
    </center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function buscaOrganograma(){
  js_OpenJanelaIframe('',
                      'db_iframe_organograma',
                      'func_db_organograma.php?bInicia=true&funcao_js=parent.pesquisar|db122_sequencial|db122_descricao',
                      'Pesquisar Organograma',
                      true
                     );
}

var oCadastroOrganograma = new DBViewOrganograma('','oCadastroOrganograma', $('ctnTela'));
var iInst = '<?php echo db_getsession("DB_instit")?>';

oMascara = document.getElementById('ctnMascara').parentNode;
oMascara.setAttribute('hidden', 'hidden');
oEstrutural = document.getElementById('ctnEstrutural').parentNode;
oEstrutural.setAttribute('hidden', 'hidden');

oCadastroOrganograma.onSaveComplete = function (oRetorno) {
  alert("Grupo  "+ $F('txtEstrutural') + " " + $F('txtDescricao') + " \nIncluido com sucesso ");
  oCadastroOrganograma.dbViewEstrutural.txtDescricao.setValue('');
  oCadastroOrganograma.dbViewEstrutural.txtEstrutural.setValue(0);
  oCadastroOrganograma.dbViewEstrutural.cboTipoEstrutural.setValue(1);
  oCadastroOrganograma.dbViewEstrutural.cboAssociado.setValue('f');
  oCadastroOrganograma.txtCodigoDepartamento.setValue('');
  oCadastroOrganograma.txtNomeDepartamento.setValue('');
  $('txtEstrutural').focus();
}
oCadastroOrganograma.onBeforeSave = function (oOrganograma) {

  var aTmp = oOrganograma.sEstrutural.split(".");
  var sTmp = "";

  for (var i = 0; i < aTmp.length; i++) {

    if(i == 0){

      sTmp = aTmp[0].substring(0, aTmp[0].length - iInst.toString().length) + iInst.toString() ;
    } else {
      sTmp += "." + aTmp[i];
    }
  }
  oOrganograma.sEstrutural = sTmp;

  $('txtCodigoDepartamento').style.backgroundColor='#FFFFFF';
  $('txtDescricao')         .style.backgroundColor='#FFFFFF';
  $('txtEstrutural')        .style.backgroundColor='#FFFFFF';
  $('txtEstrutural')        .value=sTmp;

  var lRetorno = true;
  if (oOrganograma.sDescricao == "") {
    
    alert('Informe a descrição do Organograma.');
    $('txtDescricao').focus();    
    $('txtDescricao').style.backgroundColor='#99A9AE';
    return false;
  }
  if (oOrganograma.iDepartamento == "") {
    $('txtCodigoDepartamento').style.backgroundColor='#99A9AE';
    $('txtCodigoDepartamento').focus();    
    alert('Informe um departamento');
    return false;
  }
  if (oOrganograma.sEstrutural == "" || oOrganograma.sEstrutural == $F('txtMascara')) {
    $('txtEstrutural')      .style.backgroundColor='#99A9AE';
    alert('Estrutural Inválido');
    $('txtEstrutural').focus();
    return false;
  }
  $('txtCodigoDepartamento').style.backgroundColor='#FFFFFF';
  $('txtDescricao')         .style.backgroundColor='#FFFFFF';
  $('txtEstrutural')        .style.backgroundColor='#FFFFFF';
  return true;
}

$('btnPesquisar').observe("click", function() {
  js_OpenJanelaIframe('', 
                      'db_iframe_organograma', 
                      'func_db_organograma.php?funcao_js=', 
                      'Pesquisar Organograma',
                      true
                     );
})

function pesquisar(pesquisar){

  db_iframe_organograma.hide();
  oCadastroOrganograma.getDados(pesquisar);
  $('btnSalvar').disabled=false;
}

buscaOrganograma();
</script>