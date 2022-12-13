<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_acordo_classe.php");
require_once("classes/db_mensageriaacordo_classe.php");

require_once("classes/db_mensageriaacordodb_usuario_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);


$clrotulo = new rotulocampo;
$clrotulo->label("ac51_assunto");
$clrotulo->label("ac51_mensagem");
$clrotulo->label("ac52_dias");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, 
                strings.js, 
                prototype.js, 
                datagrid.widget.js,
                widgets/DBLancador.widget.js,
                widgets/DBToogle.widget.js,
                estilos.css, 
                grid.style.css
               ");
?>
<style>

 #ac51_mensagem{
   width: 100%;
 }

td {
  white-space: nowrap;
}

fieldset table td:first-child {
  white-space: nowrap;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<center>

      <fieldset style="margin-top: 30px; width: 400px;">

        <legend><strong>Parâmetros de Contratos a Vencer</strong></legend>

	      <table align="left" border="0">
	      
	        <tr>
	          <td colspan="2">
	            
	              <fieldset  id='fieldSetVariaveis'>
	                <legend>Variáveis Disponíveis</legend>
		              
		              <table align="left" border="0" >
		              
		                <tr>
		                  <td>[dias]</td>
		                  <td> - Dias que antecedem o vencimento.</td>
		                </tr>
		                
		                <tr>
		                  <td>[ano]</td>
		                  <td> - Ano do acordo.</td>
		                </tr>
		                
		                <tr>
		                  <td>[numero]</td>
		                  <td> - Número do acordo.</td>
		                </tr>
		                
		                <tr>
		                  <td>[data_inicial]</td>
		                  <td> - Inicio da vigência do acordo.</td>
		                </tr>
		                
		                <tr>
		                  <td>[data_final]</td>
		                  <td> - Final da vigência do acordo.</td>
		                </tr>
		                
		              </table>
		              
		            </fieldset>	            
	          </td>
	        </tr>
	      
	        <tr>
	          <td align="left" width="10%">
	            <strong>&nbsp;Assunto:</strong>
	          </td>
	          <td align="left">
	            <?php db_input('ac51_assunto', 66,$Iac51_assunto,true,'text', 1 ); ?>
	          </td>
	        </tr>

		      <tr>
		        <td colspan="2">
		          <fieldset  class="fieldsetinterno">
		            <legend>
		              <b>Mensagem</b>
		            </legend>
		              <?
		                db_textarea('ac51_mensagem',5,75,$Iac51_mensagem,true,'text',1,"");
		              ?>
		          </fieldset>
		        </td>
		      </tr>
		      
		      <tr>
		        <td colspan="2">
		          
		          <fieldset class="fieldsetinterno" id='fieldSetDias'>
		            <legend>Dias a Vencer</legend>
                <strong>Dias: </strong>
		            <?php db_input('ac52_dias', 10,$Iac52_dias,true,'text', 1 ); ?>
		            <input type='button' id='btnLancaDia' value='Adicionar' onclick="js_adicionarDias( $F('ac52_dias') ); " />
		            <br/>
		            <div style="margin-top: 10px;" id='ctnDiasVencer'></div>
		          
		          </fieldset>
		          
		        </td>
		      </tr>
		      
		      <tr>
		        <td colspan="2">
		          <div id='ctnUsuarios'></div>
		        </td>
		      </tr>
		      
	      </table>

      </fieldset>


      <div style="margin-top: 10px; text-align: center;">
        <input id="salvar" name="salvar" type="button" value="Salvar" onclick="js_salvar();">
      </div>
</center>

<?PHP db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>

</body>

<script>

const CAMINHO_MENSAGENS = "patrimonial.contratos.con4_acordovencer001.";
var   aDadosLinhaDia     = [];
var   sUrl               = "ac4_mensageriaacordo.RPC.php";

//new DBToogle('fieldSetDias', false);
new DBToogle('fieldSetVariaveis', false);

var oLancadorUsuarios = new DBLancador('LancadorUsuarios');
    oLancadorUsuarios.setLabelAncora("Usuários");
    oLancadorUsuarios.setTextoFieldset("Usuários Notificados");
    oLancadorUsuarios.setTituloJanela("Pesquisar Usuários");
    oLancadorUsuarios.setNomeInstancia("oLancadorUsuarios");
    oLancadorUsuarios.setParametrosPesquisa("func_mensageriadb_usuarios.php", ["dl_Código", "dl_Nome"]);
    oLancadorUsuarios.setGridHeight(150);
    oLancadorUsuarios.show($("ctnUsuarios"));

js_gridDiasVencer();
js_getParametros();


/**
 * valida antes de colar no campo valor
 */
$('ac52_dias').ondrop = function(event) {
  return false;
} 
$('ac52_dias').onpaste = function(event) {
  return false;
} 
/**
 * funcao que ira popular a tela com os parametros configurados
 */
function js_getParametros() {

  var oParam          = new Object();
      oParam.exec     = "getParametros";
  new Ajax.Request( sUrl, {
                        method: 'post',
                        parameters: 'json='+js_objectToJson(oParam),
                        onComplete: js_retornoGetParametros
  });
}

/**
* retorno da funcao getDadosParalisacao
* ira preenher os campos
*/
function js_retornoGetParametros(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMessage.urlDecode();

  if (!oRetorno.lErro) {

    $("ac51_assunto") .value = oRetorno.sAssunto .urlDecode();
    $("ac51_mensagem").value = oRetorno.sMensagem.urlDecode();
    oRetorno.aDias.each( function( iDia, iIndice ){
      js_adicionarDias( iDia );
    });

    oRetorno.aUsuarios.each( function( oValor, iIndice ){

      var sNomeUsuario   = oValor.sNomeUsuario  .urlDecode();
      var iCodigoUsuario = oValor.iCodigoUsuario.urlDecode();
          oLancadorUsuarios.adicionarRegistro ( iCodigoUsuario, sNomeUsuario );
    } );
  }
}
 

/**
 * funcao que ira postar os dados para alterar parametros
 */
function js_salvar(){

  var sAssunto  = encodeURIComponent(tagString($F("ac51_assunto")));
  var sMensagem = encodeURIComponent(tagString($F("ac51_mensagem")));
  var aDias     = getDiasLancados();
  var aUsuarios = getUsuariosLancados();

  if (sAssunto == "") {
    
    alert( _M(CAMINHO_MENSAGENS + "salvar_assunto_vazio" ) );
    return false;
  }
  if (sMensagem == "") {
    
    alert( _M(CAMINHO_MENSAGENS + "salvar_mensagem_vazio" ) );
    return false;
  } 
  
  if ( aDias == '' ) {

    alert( _M(CAMINHO_MENSAGENS + "salvar_dias_vazio") );
    return false;
  }

  if ( aUsuarios == '' ) {

    alert( _M(CAMINHO_MENSAGENS + "salvar_usuario_vazio" ) );
    return false;
  }

  var oParam           = new Object();
      oParam.exec      = "salvarParametros";
      oParam.sMensagem = sMensagem;
      oParam.sAssunto  = sAssunto ;
      oParam.aDias     = aDias    ;
      oParam.aUsuarios = aUsuarios;
      
      js_divCarregando( _M(CAMINHO_MENSAGENS +  "salvando" ) ,'msgBox');
      
      new Ajax.Request( sUrl, {
                          method     : 'post',
                          parameters : 'json=' + js_objectToJson(oParam),
                          onComplete : js_retornoSalvar
      });
  
  //console.log(oParam);
}

function js_retornoSalvar(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMessage.urlDecode();
  alert(sMensagem);

}  

/*
 * funcao retorna um array de dias para enviar para salvar no RPC
 */
function getDiasLancados(){

  var aDias = [];
  aDadosLinhaDia.each( function ( oValor, iIndice) {
    aDias.push(oValor.iDia);
  });
  return aDias;
}

/**
 * funcao retorna um array de usuarios lancados para enviar ao rpc salvar
 */
function getUsuariosLancados(){

  var aUsuarios = [];
  oLancadorUsuarios.getRegistros().each( function( oDados, iIndice){

    aUsuarios.push( oDados.sCodigo );
  });
  
  return aUsuarios;
}


/**
 * funcao para adicioar os dias no array aDadosLinhaDia
   e apos renderiza a grid que ira ler desse array
 */
function js_adicionarDias( iDias ) {


  if ( iDias > 1500 ) {
    
    alert( _M(CAMINHO_MENSAGENS + 'limite_dia') );
    return false; 
  }

  
  var lIncluido = false; //variavel de controle para registro existente
  
  if (iDias == '' || iDias < 1) {
    
    alert( _M( CAMINHO_MENSAGENS + 'selecione_dia') );
    return false;
  }
  var oRegistro = {};
      oRegistro.iDia  = iDias;

      // percorremos o array para ver se o registro ja nao esta incluido
      aDadosLinhaDia.each( function( oValor, iIndice ) {

        if (oValor.iDia == iDias) {

          alert( _M(CAMINHO_MENSAGENS + 'registro_ja_incluido'));
          lIncluido = true;
          return false;
        }
      });

      if ( !lIncluido ) {

        aDadosLinhaDia.push(oRegistro);
        js_RenderizaDias();
      }
}

/**
 * funcao que ira popular a grid de dias
 */
function js_RenderizaDias(){

  oGridDiasVencer.clearAll(true);  
  aDadosLinhaDia.each( function( oValor, iIndice ){

    var aLinha    = [];
        aLinha[0] = oValor.iDia;
        aLinha[1] = "<input type='button' onclick='js_removerDias(" + oValor.iDia + ");' value='Remover' "; 
    oGridDiasVencer.addRow(aLinha);
  });
  oGridDiasVencer.renderRows();
  $('ac52_dias').value = '';

}

/**
 * funcao que ira remover dias da grid, ele remove do array aDadosLinhaDia
   e depois chama js_RenderizaDias(), que ira ler o array e popular a grid
 */
function js_removerDias( iRegistro ){
  
  aDadosLinhaDia.each( function( oValor, iIndice ){

    if (iRegistro == oValor.iDia) {

      delete(aDadosLinhaDia[iIndice]);
      js_RenderizaDias();
      return false;
    }
  });
}

/**
 * instancia a grid de dias a vencer
 */
function js_gridDiasVencer(){

  oGridDiasVencer = new DBGrid('DiasVencer');
  oGridDiasVencer.nameInstance = 'oGridDiasVencer';
  oGridDiasVencer.setCellWidth([ '250px' ,
                                 '250px'
                               ]);
  
  oGridDiasVencer.setCellAlign(['center'  ,
                                'center'
                               ]);
  
  oGridDiasVencer.setHeader([ 'Dias',
                              'Ação'
                            ]);
                                       
  oGridDiasVencer.setHeight(90);
  oGridDiasVencer.show($('ctnDiasVencer'));
  oGridDiasVencer.clearAll(true);  
}




</script>
</html>
