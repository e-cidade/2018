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
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_rhpromocao_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$oGet        = db_utils::postMemory($_GET);
$oRhpromocao = new cl_rhpromocao;

$iMatricula  = $oGet->iMatricula; 


/*
 * Dados da Promoção da Matricula selecionada
 */
$sCamposRhPromocao  = "z01_nome,       ";
$sCamposRhPromocao .= "h72_sequencial, ";
$sCamposRhPromocao .= "h72_dtinicial   "; 

$sSqlRhPromocao = $oRhpromocao->sql_query(null, $sCamposRhPromocao, null, "h72_regist = {$iMatricula} and h72_dtfinal is null");
$rsRhPromocao   = $oRhpromocao->sql_record($sSqlRhPromocao); 
$aRhPromocao    = db_utils::fieldsMemory($rsRhPromocao, 0);

$rh01_regist    = $iMatricula;
$h72_sequencial = $aRhPromocao->h72_sequencial;
$z01_nome       = $aRhPromocao->z01_nome; 
$datainicial    = implode("/", array_reverse(explode("-",$aRhPromocao->h72_dtinicial)));



?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("classes/dbViewAvaliacoes.classe.js");
  db_app::load("widgets/windowAux.widget.js");
  db_app::load("widgets/dbmessageBoard.widget.js");  
  db_app::load("dbcomboBox.widget.js");   
?>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_gridTipoAvaliacao();" >
<form name="form1" method="post">
<center>

<fieldset style="margin-top: 50px; width: 700px;">
	<legend>
		<strong>
		  Cancelamento de Avaliação - Dados da Promoção
		</strong>
	</legend>

	<table align="left" border="0" cellspacing="4" cellpadding="0" >


    <tr> 
      <td><strong>Código: </strong> </td>
      <td>
        <? db_input('h72_sequencial',10,"Sequencial da Promoção",true,'text',3,""); ?>
      </td>
    </tr>	  
	  <tr> 
	    <td nowrap title="Código">
	      <strong>Matrícula: </strong>
	    </td>
	    <td nowrap>
	      <?
	        db_input('rh01_regist', 10, "Matrícula do Servidor", true, 'text', 3, "");
	        db_input('z01_nome', 60, "Nome do Servidor", true, 'text', 3, '');
	      ?>
	    </td>
	  </tr>
    <tr> 
      <td><strong>Data Inicial: </strong> </td>
      <td>
        <? db_input('datainicial',10,"Data Inicial",true,'text',3,''); ?>
      </td>
    </tr> 	  
	</table>
</fieldset>


<fieldset style="margin-top: 10px; width: 680px;">
	<legend>
		<strong>
		 Avaliações Cadastradas
		</strong>
	</legend>
	
  <table align="left" border="0" cellspacing="4" cellpadding="0" >
    <tr> 
      <td nowrap="nowrap" width="10%">
        <div id='ctnTipoAvaliacao' > </div>
      </td>
    </tr> 
    
 </table>   	
	
</fieldset>

	<br>
<input  type="button" value="Cancelar" name="salvar" onclick="js_cancelar();" id='salvar' />
<input  type="button" value="Voltar" name="novo" id='novo' onclick="js_novo();" />
</center>
</form>


<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

  var sUrlRPC      = 'rec4_avaliacao.RPC.php';  
  var oParametros  = new Object();


/*
 * retorna os valores selecionados para cancelar
 */
function js_getAvaliacaoSelecionados(){

   var aListaCheckbox = oGridAvaliacao.getSelection();
   var aListaCancelar = new Array();
   
   aListaCheckbox.each(
     function ( aRow ) {
       aListaCancelar.push(aRow[0]);
    }
   );
     return aListaCancelar;
}

/*
 * função para enviar os dados para cancelar a avaliação
 *
 */

function js_cancelar(){

   var msgDiv                 = "Cancelando Avaliaçao \n Aguarde ...";
   
   oParametros.exec           = 'cancelarAvaliacao';
   oParametros.aDados         = js_getAvaliacaoSelecionados();
   oParametros.iPromocao      = $F('h72_sequencial');
   
   js_divCarregando(msgDiv,'msgBox');
  
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoCancelar
                                             });

} 

function js_retornoCancelar(oAjax) {
    
    js_removeObj('msgBox');
    
    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.iStatus == 1) {
    
      alert(oRetorno.sMessage.urlDecode());
      window.location.reload();
      return false;
    
    } else {
       
      alert(oRetorno.sMessage.urlDecode().replace(/\\n/g, '\n'));
      return false;   
    }
}




 /*
  * Inicia a Montagem do grid tipos de avaliação
  *
  */
function js_gridTipoAvaliacao() {

  oGridAvaliacao                 = new DBGrid('TipoAvaliacao');
  oGridAvaliacao.nameInstance    = 'oGridAvaliacao';
  oGridAvaliacao.hasTotalizador  = false;
  oGridAvaliacao.allowSelectColumns(false);
  oGridAvaliacao.setCheckbox(0);
  
  oGridAvaliacao.setCellWidth(new Array( 
                                         '50px'  ,
                                         '50px' 
                                        ));
  
  oGridAvaliacao.setCellAlign(new Array( 
                                         'left'  ,
                                         'center'
                                        ));
  
  oGridAvaliacao.setHeader(new Array( 
                                      'Seq'  ,
                                      'Data da Avaliação'
                                     ));
                                       
  oGridAvaliacao.setHeight(150);
  oGridAvaliacao.show($('ctnTipoAvaliacao'));
  oGridAvaliacao.clearAll(true);
  
  js_listaAvaliacao();
  
}

/*
 * funcao para montar os registros iniciais da grid
 *
 */ 
function js_listaAvaliacao() {

   var msgDiv             = "Pesquisando Avaliações \n Aguarde ...";
   oParametros.exec       = 'listaAvaliacao';
   oParametros.iPromocao  = $F('h72_sequencial');
   oParametros.iMatricula = $F('rh01_regist');
    
   js_divCarregando(msgDiv,'msgBox');
  
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoAvaliacao
                                             });
                                            
}

/*
 * funcao para montar a grid com avaliacao
 *  retornado do RPC
 *
 */ 
function js_retornoAvaliacao(oAjax) {
    
    js_removeObj('msgBox');
    
    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.iStatus == 1) {
      
      oGridAvaliacao.clearAll(true);

      oRetorno.dados.each( 
                    function (oDado, iInd) {       

                        var aRow    = new Array();  
                            
                            aRow[0] = oDado.h73_sequencial;
                            aRow[1] = oDado.h73_dtavaliacao;
                            oGridAvaliacao.addRow(aRow);
                       });
      oGridAvaliacao.renderRows(); 
      
    } else {
      alert(oRetorno.sMessage.urlDecode());
      js_novo();
      return false;
    }
}




function js_novo(){

  window.location = 'rec4_avaliacaocancelamento001.php';
}


</script>