<?php
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("classes/db_cgm_classe.php");

/** matricula **/
$clrotulo = new rotulocampo();
$clrotulo->label('j01_matric');
$clrotulo->label('z01_nome');

/** cgm **/
$clcgm = new cl_cgm;
$clcgm->rotulo->label("z01_numcgm");
$clcgm->rotulo->label("z01_nome");

/** numpre **/
$clrotulo->label('k00_numpre');

/** $oParametros->prefeitura      SE PREFEITURA      ('t','f')       **/
/** $oParametros->db21_usasisagua SE USA AGUA        ('t','f')       **/
/** $oGet->sTipo                  TIPO DE IMPORTACAO ('agua','iptu') **/

$oGet            = new _db_fields();
$oGet            = db_utils::postMemory($HTTP_GET_VARS);
$sTituloFieldset = '';

if ($oGet->sTipo == 'iptu'){
	$sTituloFieldset = ' (IPTU)';
} else if ($oGet->sTipo == 'agua'){
	$sTituloFieldset = ' (Água)';
}

$oDaoDBConfig = db_utils::getDao('db_config');
$oParametros  = $oDaoDBConfig->getParametrosInstituicao(db_getsession("DB_instit"));

/** permite funcionalidade somente se | prefeitura e acessar importacao de iptu | agua e acessar importacao de agua **/
if ( !($oParametros->prefeitura == 't' && $oGet->sTipo == 'iptu') && !($oParametros->db21_usasisagua == 't' && $oGet->sTipo == 'agua')) {
	$db_opcao = 33;
}else{
	$db_opcao = 1;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<?php
db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js');
db_app::load('estilos.css, grid.style.css');
?>
</head>
<body bgcolor=#CCCCCC>
  <form class="container" name="form1" id="form1">
        <?php if ( $db_opcao == 1 ) { ?>
        <fieldset>
        <legend>Cancelamento de Importação de Débitos<?php echo $sTituloFieldset ?>:</legend>
        <table class="form-container">
        <tr>
          <td title="<?=$Tz01_nome?>"><?
          db_ancora($Lz01_nome,' js_cgm(true); ',1);?></td>
          <td><?
          db_input('z01_numcgm',5,$Iz01_numcgm,true,'text',1,"class='pesquisa' onchange='js_cgm(false);jsLimpa(this.value, this.id);'");
          db_input('z01_nome',30,0,true,'text',3,"class='label'","z01_nomecgm");
          ?>
          </td>
        </tr>
        <tr>
          <td title="<?=$Tj01_matric?>"><?
          db_ancora($Lj01_matric,' js_matri(true); ',1);?></td>
          <td><?
          db_input('j01_matric',5,$Ij01_matric,true,'text',1,"class='pesquisa' onchange='js_matri(false);jsLimpa(this.value, this.id);'");
          db_input('z01_nome',30,0, true,'text',3,"class='label'","z01_nomematri");
          ?>
          </td>
        </tr>
        <tr>
          <td title="<?=$Tk00_numpre?>"><?=$Lk00_numpre?></td>
          <td><?
          db_input('k00_numpre',10,$Ik00_numpre,true,'text',1,"onchange='jsLimpa(this.value, this.id);'");
          ?>
          </td>
        </tr>
        </table>
        </fieldset>
        <input type="button" name="pesquisar" id="pesquisar" value="Visualizar Débitos"
            onclick="js_pesquisaDebitos()" />
        <?php } else { ?>
        <fieldset>
        <legend>Cancelamento de Importação de Débitos<?php echo $sTituloFieldset ?>:</legend>
        <table class="form-container">
        <tr>
          <td align="center"><br /> <span>Esta rotina não está disponível para esta Instituição.</span>
          </td>
        </tr>
        </table>
        </fieldset>
        <?php } ?>
        <table class="form-container">
        <tr id="grid" style="display: none;">
          <td>
            <fieldset>
              <legend>Débitos: </legend>
              <div id="oGridDebitos"></div>
            </fieldset>
            <input type="button" name="processar" id="processar" value="Cancelar Importa&ccedil;&atilde;o"
            onclick="js_cancelarImportacao()" />
          <td>
        </tr>
        </table>
  </form>
  <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<script>
function js_validaCampos(){

  var lValidacao = false;
  var aText = $('form1').getInputs('text');   
  aText.each(function (oText, id) {  
    if ($(oText).value != '') {
      lValidacao = true;
      throw $break;
    }
  });  
  if ( lValidacao == false ) {
    alert(_M("tributario.diversos.dvr3_cancelimpiptu001.informe_campo"));
  }   
  return lValidacao;  
}   

function js_cgm(mostra){
  var cgm=document.form1.z01_numcgm.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe2','func_nome.php?funcao_js=parent.js_mostracgm|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe2','func_nome.php?pesquisa_chave=' + cgm 
                                                                  + '&funcao_js=parent.js_mostracgm1','Pesquisa',false);
  }
}
function js_mostracgm(chave1,chave2){
  jsLimpa(document.form1.z01_numcgm.value, document.form1.z01_numcgm.id);
  $('z01_numcgm').value = chave1;
  $('z01_nomecgm').value = chave2;    
  db_iframe2.hide();
}
function js_mostracgm1(erro,chave){
  document.form1.z01_nomecgm.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}
function js_matri(mostra){
  var matri=document.form1.j01_matric.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe3','func_iptubase.php?funcao_js=parent.js_mostramatri|j01_matric|z01_nome','Pesquisa',true);
  }else{
    if (matri != "") {
	    js_OpenJanelaIframe('top.corpo','db_iframe3','func_iptubase.php?pesquisa_chave='+matri+
	                                                                  '&funcao_js=parent.js_mostramatri1','Pesquisa',false);
    }                                                                 
  }
}
function js_mostramatri(chave1,chave2){
  jsLimpa(document.form1.j01_matric.value, document.form1.j01_matric.id);
  document.form1.j01_matric.value = chave1;
  document.form1.z01_nomematri.value = chave2;
  db_iframe3.hide();
}
function js_mostramatri1(chave,erro){
  document.form1.z01_nomematri.value = chave; 
  if(erro==true){ 
    document.form1.j01_matric.focus(); 
    document.form1.j01_matric.value = ''; 
  }
}

function jsLimpa(iValorCampo, iIdCampo) {
  var aText = $('form1').getInputs('text');   
  aText.each(function (oText, id) {  
    $(oText).value = '';
  });  
  $(iIdCampo).value = iValorCampo
}

js_init_table();

var sUrlRPC = 'dvr3_importacaoiptu.RPC.php';

function js_cancelarImportacao() {

	var aDebitosSelecionados = oGridDebitos.getSelection();
  var aNumpresSelecionados = new Array();
  var lErro                = false;
  var sMsgErro             = "Erro: \n";

  if (aDebitosSelecionados.length == 0) {
	  alert(_M("tributario.diversos.dvr3_cancelimpiptu001.nenhum_debito_para_cancelamento"));
	  return false;
  }
  
  aDebitosSelecionados.each(
    function ( aRow ) {
    	aNumpresSelecionados.push( aRow[0] );
    }
  );
  
  if (lErro) {
	  alert(sMsgErro);
	  return false;
  }

  if( !confirm(_M('tributario.diversos.dvr3_cancelimpiptu001.deseja_cancelar_importacao')) ) {
	  return false;
  }
  var msg = _M('tributario.diversos.dvr3_cancelimpiptu001.processando_cancelamento_importacao_debitos');
  js_divCarregando(msg, 'msgbox');
  //js_divCarregando('Processando cancelamento da importação de débitos, aguarde.', 'msgbox');

  var oParam                  = new Object();
  oParam.sExec                = 'cancelaImportacao';
  oParam.aCodigosImportacao   = aNumpresSelecionados;

	var oAjax = new Ajax.Request(sUrlRPC,
            									{ 
															 method    : 'POST',
       												 parameters: 'json='+Object.toJSON(oParam), 
       												 onComplete: js_retornoProcessamento
      												});
	
}

function js_retornoProcessamento(oAjax) {

	var oGet = js_urlToObject();
	
	js_removeObj('msgbox');

	var oRetorno  = eval("("+oAjax.responseText+")");

	oGridDebitos.clearAll(true);

	if (oRetorno.status == 1) {

		alert(_M("tributario.diversos.dvr3_cancelimpiptu001.sucesso_cancelamento_debitos"));

		window.location = 'dvr3_cancelimpiptu001.php?sTipo=' + oGet.sTipo;
		 
	} else {

		alert(oRetorno.message);
		
	}
}

function js_init_table() {
	
	oGridDebitos              = new DBGrid('oGridDebitos');
  oGridDebitos.nameInstance = 'oGridDebitos';
  oGridDebitos.setHeight(150);
  oGridDebitos.setCheckbox(0);
  oGridDebitos.setCellAlign(new Array('center', 
		  																'center', 
		  																'center', 
		  																'center'  , 
		  																'left'  , 
		  																'left'  ));
	
  oGridDebitos.setCellWidth(new Array('15%', 
		   																'10%', 
		   																'5%' , 
		   																'20%', 
		   																'20%', 
		   																'30%'));
		
  oGridDebitos.setHeader   (new Array('Código Importação', 
		  															  'Data'             , 
		  															  'Hora'             , 
		  															  'Tipo Débito'      ,
		  															  'Receitas'         , 
		  															  'Observação'       ));
	  
  oGridDebitos.show($('oGridDebitos'));
  
}

function js_pesquisaDebitos() {

  if( !js_validaCampos() ) {
    return false
  }

  var oParam            = new Object();
  
  oParam.iChavePesquisa = 0;
  oParam.iTipoPesquisa  = 0;
  
  if( $F('j01_matric') ){
  
    oParam.iChavePesquisa     = $F('j01_matric');
    oParam.iTipoPesquisa      = 2;
    
  } else if( $F('z01_numcgm') ){
  
    oParam.iChavePesquisa     = $F('z01_numcgm');
    oParam.iTipoPesquisa      = 3;
    
  } else if( $F('k00_numpre') ){
  
    oParam.iChavePesquisa     = $F('k00_numpre');
    oParam.iTipoPesquisa      = 4;
    
  }   

  oParam.sExec      = 'getDebitosImportados';


	js_divCarregando(_M("tributario.diversos.dvr3_cancelimpiptu001.pesquisando_debitos"), 'msgbox');

	var oAjax = new Ajax.Request(sUrlRPC, {method: 'POST',
                 												 parameters: 'json='+Object.toJSON(oParam), 
                 												 onComplete: js_retornaDebitos} );
	
}

function js_retornaDebitos(oAjax) {
	
	js_removeObj('msgbox');

	var oRetorno  = eval("("+oAjax.responseText+")");

	oGridDebitos.clearAll(true);

	if (oRetorno.status == 1) {

		$('grid').style.display = '';

		for (var i = 0; i < oRetorno.aDebitos.length; i++) {
		
			with (oRetorno.aDebitos[i]) {

			  aLinha     = new Array();
			  aLinha[0]	 = dv11_sequencial;
			  aLinha[1]  = js_formatar(dv11_data,'d'); 				
			  aLinha[2]  = dv11_hora.urlDecode();                                                                        
			  aLinha[3]  = k00_tipo + ' - ' + k00_descr.urlDecode();                                                             
			  aLinha[4]  = receitas.urlDecode().replace(/,/g, '<BR>');		
			  aLinha[5]  = dv11_obs.urlDecode();		
			}
			oGridDebitos.addRow(aLinha);
			
		}
		
		oGridDebitos.renderRows();

	} else {

		alert(_M("tributario.diversos.dvr3_cancelimpiptu001.nenhum_registro_encontrado"));

	}

}

</script>
<script>
<?php if ( $db_opcao == 1 ) { ?>
  $("z01_numcgm").addClassName("field-size2");
  $("z01_nomecgm").addClassName("field-size7");
  $("j01_matric").addClassName("field-size2");
  $("z01_nomematri").addClassName("field-size7");
  $("k00_numpre").addClassName("field-size2");
<?php }?>

</script>