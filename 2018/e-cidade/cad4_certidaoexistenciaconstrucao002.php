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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);
$oPost = db_utils::postmemory($_POST);

$z01_nome   = $oPost->z01_nomematri;
$j39_idcons = $oPost->iConstrucao;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?   
  db_app::load("scripts.js, strings.js, prototype.js");
  db_app::load("estilos.css, grid.style.css");
?>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

<center>
<form name="form1" method="post" action="cad4_certidaoexistenciaconstrucao002.php">

  <fieldset style="margin-top:50px; width: 700px;">
  
    <legend><strong>Emissão de Certidão de Existência </strong></legend>
    <table  align="left" width="90%" cellpadding="1" border="0">

      <tr>   
        <td>
          <strong>Matrícula:</strong>
        </td>
        <td> 
          <?
           db_input('k00_matric',10,'',true,'text',3);
           db_input('z01_nome',60,0, true,'text',3);
          ?>
        </td>
      </tr>  
      
      <tr>   
        <td>
          <strong>Construção:</strong>
        </td>
        <td> 
          <?
           db_input('j39_idcons',10,'',true,'text',3);
          ?>
        </td>
      </tr>        

 
 
				<tr>
					<td nowrap title="Processos registrado no sistema?">
						<strong>Processo do Sistema:</strong>
					</td>
					<td nowrap>
						<?
						  $lProcessoSistema = true;
						  $aProcessoSistema = array( "0"  => "SELECIONE...",
						                             "1" => "NÃO",
						                             "2" => "SIM"
						                           );
							//db_select('lProcessoSistema', $aProcessoSistema, true, 1, "onchange='js_processoSistema()' style='width: 95px'")
						?>
						<select id='lProcessoSistema' name='lProcessoSistema' onchange='js_processoSistema();' style='width: 95px' >
						  <option value="2">SELECIONE...</option>
						  <option value="0">NÃO</option>
						  <option value="1">SIM</option>
						</select>
					</td>
				</tr>

				<tr id="processoSistema" style="display: none;">
					<td nowrap title="<?=@$Tp58_codproc?>">
					  <strong>
						<?
							db_ancora('Processo:', 'js_pesquisaProcesso(true)', 1);
						?>
					  </strong>
					</td>
					<td nowrap>
						<? 
						db_input('v01_processo', 10, false, true, 'text', 1, 'onchange="js_pesquisaProcesso(false)"') ;
	
						db_input('p58_requer', 60, false, true, 'text', 3);
						?>
					</td>
				</tr>

				<tr id="processoExterno1" style="display: none;">
					<td nowrap title="Número do processo externo">
						<strong>Processo:</strong>
					</td>
					<td nowrap>
						<? 
						db_input('v01_processoExterno', 10, "", true, 'text', 1, null, null, null, "background-color: rgb(230, 228, 241);") ;
						?>
					</td>
				</tr>

				<tr id="processoExterno2" style="display: none;">
					<td nowrap title="Número do processo externo">
						<strong>
  						Titular do Processo:
						</strong>
					</td>
					<td nowrap>
					<? 
						db_input('v01_titular', 74, 'false', true, 'text', 1) ;
					?>
					</td>
				</tr>

				<tr id="processoExterno3" style="display: none;">
					<td nowrap title="Número do processo externo">
					  <strong>
					    Data do Processo:
					  </strong>
					</td>
					<td nowrap>
						<? 
						db_inputdata('v01_dtprocesso', @$v01_dtprocesso_dia, @$v01_dtprocesso_mes, @$v01_dtprocesso_ano, true, 'text', 1);
						?>
					</td>
				</tr>        
      
      <tr>
        <td colspan="2">
          <fieldset>
          <legend>
            <strong>Observação</strong>
          </legend>
          <? db_textarea('sObservacao', 5, 90, null, true, null, 1) ?>
          
          </fieldset>
        </td>
      </tr>
      

    </table>
  </fieldset> 
  <table style="margin-top: 10px;">
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="gerar" id="gerar" type="button" value="Processar" onclick="js_gerarCertidao();" >
          <input  name="voltar" id="voltar" type="button" value="Voltar" onclick="js_volta();" >
        </td>
      </tr>  
  </table>
</form>   
</center>   
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

/*
 * funcao responsavel pelo envio dos dados para gerar certidao
 */

var sUrlRPC = "cad4_certidaoexistenciaconstrucao.RPC.php";
 
function js_gerarCertidao() {

  var iMatricula       = $F('k00_matric');
  var lProcessoSistema = $F('lProcessoSistema');
  var iConstrucao      = $F('j39_idcons');
  var sObservacao      = $F('sObservacao');
  var iProcesso        = '';
  var sTitular         = '';
  var dtDataProcesso   = '';
  var msgDiv           = "Gerando Certidão \n Aguarde ...";
  var oParametros      = new Object();
  
  if (lProcessoSistema == '1') {

    iProcesso = $F('v01_processo');
  } else if (lProcessoSistema == '0') {
    
    iProcesso      = $F('v01_processoExterno');
    sTitular       = $F('v01_titular');
  } dtDataProcesso = $F('v01_dtprocesso');

  if ((lProcessoSistema == '1' || lProcessoSistema == '0') && iProcesso == '') {
 
    alert("Selecione algum processo.\nCaso não queira processo vinculado, altere a opção 'processo do sistema'. ");
    return false;
  }

  //alert('iMatricula -> '+ iMatricula +'\niConstrucao -> ' +iConstrucao+ '\niProcesso -> ' + iProcesso + '\nsTitular -> ' + sTitular + '\ndtDataProcesso -> ' + dtDataProcesso + '\nObs -> ' + sObservacao);

  js_divCarregando(msgDiv,'msgBox');
  
  oParametros.exec             = 'geraCertidao';     
  oParametros.iMatricula       = iMatricula;
  oParametros.lProcessoSistema = lProcessoSistema;
  oParametros.iConstrucao      = iConstrucao;
  oParametros.sObservacao      = sObservacao;
  oParametros.iProcesso        = iProcesso;
  oParametros.sTitular         = sTitular;
  oParametros.dtDataProcesso   = dtDataProcesso;
  
  var oAjaxLista  = new Ajax.Request(sUrlRPC,
      {method: "post",
       parameters:'json='+Object.toJSON(oParametros),
       onComplete: js_certidao
      });   
  
}

function js_certidao(oAjax) {

  js_removeObj('msgBox');
  
  var oRetorno = eval("("+oAjax.responseText+")");
  
  if (oRetorno.iStatus == 1) {
    
	  jan = window.open("cad4_certidaoexistencia003.php?iCodigoCertidao=" + oRetorno.iCodigoCertidao,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	  jan.moveTo(0,0)    
  } else {
    
    alert(oRetorno.sMessage.urlDecode());
    return false;
  }
}

/*
 * FUNCOES DE PESQUISA
 */

function js_pesquisaProcesso(lMostra) {

  if (lMostra) {
    js_OpenJanelaIframe('','db_iframe_matric', 'func_protprocesso.php?funcao_js=parent.js_mostraProcesso|p58_codproc|z01_nome','Pesquisa',true);
  } else {
    js_OpenJanelaIframe('','db_iframe_matric', 'func_protprocesso.php?pesquisa_chave='+document.form1.v01_processo.value+'&funcao_js=parent.js_mostraProcessoHidden','Pesquisa',false);
  }
   
}
function js_mostraProcesso(iCodProcesso, sRequerente) {

  document.form1.v01_processo.value = iCodProcesso;
  document.form1.p58_requer.value  = sRequerente;
  db_iframe_matric.hide();
  
}

function js_mostraProcessoHidden(iCodProcesso, sNome, lErro) {

  if(lErro == true) {
    document.form1.v01_processo.value = "";
    document.form1.p58_requer.value  = sNome;
  } else {
    document.form1.p58_requer.value  = sNome;
  }
}    

    /*
      funcao que trata se o processo é externo ou interno
    */

function js_processoSistema() {

  
  var lProcessoSistema = $F('lProcessoSistema');
  
	if (lProcessoSistema == 1 ) {
		
		document.getElementById('processoExterno1').style.display = 'none';
		document.getElementById('processoExterno2').style.display = 'none';
		document.getElementById('processoExterno3').style.display = 'none';
		document.getElementById('processoSistema').style.display  = '';
		$('v01_processo')  .value = "";
		$('p58_requer')    .value = "";
		$('v01_dtprocesso').value = "";
		
	}	else if (lProcessoSistema == 0) {
		
		document.getElementById('processoExterno1').style.display = '';
		document.getElementById('processoExterno2').style.display = '';
		document.getElementById('processoExterno3').style.display = '';
		document.getElementById('processoSistema').style.display  = 'none';

		$('v01_processo')       .value = "";
		$('v01_processoExterno').value = "";
		$('v01_titular')        .value = "";
		$('v01_dtprocesso')     .value = "";
		
	} else if (lProcessoSistema == 2) {

	  document.getElementById('processoExterno1').style.display = 'none';
		document.getElementById('processoExterno2').style.display = 'none';
		document.getElementById('processoExterno3').style.display = 'none';
		document.getElementById('processoSistema').style.display  = 'none';

		$('v01_processo')       .value = "";
		$('v01_processoExterno').value = "";
		$('v01_titular')        .value = "";
		$('v01_dtprocesso')     .value = "";	  

	}
}


function js_volta(){
  location.href = 'cad4_certidaoexistenciaconstrucao001.php ';                      
}

</script>