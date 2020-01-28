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

if ( $oRhpromocao->numrows == 0 ) {
  db_msgbox("Promoção não existente.");
  db_redireciona("rec4_avaliacao001.php");
}

$aRhPromocao     = db_utils::fieldsMemory($rsRhPromocao, 0);

$rh01_regist     = $iMatricula;
$h72_sequencial  = $aRhPromocao->h72_sequencial;
$z01_nome        = $aRhPromocao->z01_nome;
$aDataInicial    = explode("-",$aRhPromocao->h72_dtinicial);
$datainicial     = implode("/", array_reverse($aDataInicial));
$datainicial_dia = $aDataInicial[2];
$datainicial_mes = $aDataInicial[1];
$datainicial_ano = $aDataInicial[0];

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
  db_app::load("DBViewCursos.classe.js");
  db_app::load("dbtextField.widget.js");
?>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_gridTipoAvaliacao();" >
<form name="form1" method="post">
<center>

<fieldset style="margin-top: 50px; width: 700px;">
	<legend>
		<strong>
		  Dados da Promoção
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
        <? db_inputdata('datainicial',$datainicial_dia,$datainicial_mes,$datainicial_ano,true,'text',3,''); ?>
      </td>
    </tr> 	  
	</table>
</fieldset>


<fieldset style="margin-top: 10px; width: 700px;">
	<legend>
		<strong>
		 Avaliação
		</strong>
	</legend>
	
  <table align="left" border="0" cellspacing="4" cellpadding="0" >
    <tr> 
      <td nowrap="nowrap" width="10%"><strong>Data da Avaliação: </strong> </td>
      <td>
        <?PHP 
          db_inputdata('h73_dtavaliacao', null, null, null, true, 'text', 1, "");
        ?>
      </td>
    </tr> 
    
    <tr>
      <td colspan="2">
				<fieldset style="margin-top: 10px; width: 650px;">
				  <legend>
				    <strong>
				     Observação
				    </strong>
				  </legend> 
					  <table align="left" width="98%" border="0" cellspacing="4" cellpadding="0" >
					    <tr>
					      <td>
					        <? db_textarea("h73_observacao", 5, 90, "", true, 'text', 1 ); ?>
					      </td>
					    </tr>
					  </table>   				  
				</fieldset>         
      </td>
    </tr>
    
    <tr>
      <td colspan="2">
        <fieldset style="margin-top: 10px; width: 665px;">
          <legend>
            <strong>
             Requisitos de Avaliação
            </strong>
          </legend> 
            <table align="left" width="98%" border="0" cellspacing="4" cellpadding="0" >
              <tr>
                <td>
                  <div id='ctnTipoAvaliacao' > </div>
                </td>
              </tr>
            </table>            
        </fieldset>         
      </td>
    </tr>    
    
    <tr>
      <td colspan="2">
        <div id='ctnCursos' ></div>
      </td>
    </tr>    
    
 </table>   	
	
</fieldset>

	<br>
<input  type="button" value="Salvar" name="salvar" onclick="js_salvar();" id='salvar' />
<input  type="button" value="Novo" name="novo" id='novo' onclick="js_novo();" />
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
 *  instanciamos componente que listará os cursos
 */
 
 var oCursos  = new DBViewCursos('oCursos');
     oCursos.setCodigoPromocao($F('h72_sequencial'));
     oCursos.show($('ctnCursos'));
     
     
/**
 * função para comparar data, se uma é maior que a outra
 */     
function js_ComparaDatas(dataInicial, dataFinal) {

	var nova_dataInicial = parseInt(dataInicial.split("/")[2].toString() + 
	                                dataInicial.split("/")[1].toString() + 
	                                dataInicial.split("/")[0].toString());
	                                
	var nova_dataFinal = parseInt(dataFinal.split("/")[2].toString() + 
	                              dataFinal.split("/")[1].toString() + 
	                              dataFinal.split("/")[0].toString());
	 
	 if (nova_dataFinal < nova_dataInicial) {
	   return false;
	 } else {
	   return true;
	 } 
	 
}
     

/*
 * função para enviar os dados para salvar a avaliação
 *
 */

function js_salvar(){

  var iPromocao              = $F('h72_sequencial');
  var dDataAvaliacao         = $F('h73_dtavaliacao');
  var dDataPromocao          = $F('datainicial');
  var sObservacao            = $F('h73_observacao');
  var msgDiv                 = "Salvando Registro \n Aguarde ...";
   
  if (dDataAvaliacao == '') {
   
    alert('Entre com a Data da Avaliação');
    $('h73_dtavaliacao').focus();
    return false;
  }
  
  if (js_ComparaDatas(dDataPromocao, dDataAvaliacao) == false) { 
   alert('Data da Avaliacao é Menor que o Início da Promoção.');
   return false;
   
  } 
   
   oParametros.exec           = 'salvar';
   oParametros.aDados         = js_getValuesTipoAvaliacao();
   oParametros.iPromocao      = iPromocao;
   oParametros.dDataAvaliacao = dDataAvaliacao;
   oParametros.sObservacao    = sObservacao;
   oParametros.iPontoCursos   = oCursos.getTotalCargaHoraria(false);
   oParametros.aCursos        = oCursos.getCursosSelecionados(false);
    
   js_divCarregando(msgDiv,'msgBox');
  
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoSalvar
                                             });

} 

function js_retornoSalvar(oAjax) {
    
    js_removeObj('msgBox');

    var oRetorno = eval("("+oAjax.responseText+")");
    
    if (oRetorno.iStatus == 1) {
    
	    alert(oRetorno.sMessage.urlDecode().replace(/\\n/g, "\n"));
	    window.location.reload();
	    return false;
    } else {
      
      alert(oRetorno.sMessage.urlDecode().replace(/\\n/g, "\n"));
      return false;      
    }
}


/*
 * função para retornar a pontuação digitada em cada
 * text dos tipos de avaliação
   @return array
 */
  
function js_getValuesTipoAvaliacao(){

  var aRegistros          = oGridTipoAvaliacao.aRows;
  var aRegistrosProcessar = new Array();
  
  aRegistros.each(function (oRegistro, iSeq) {
     
    var iSequencial               = oRegistro.aCells[0].getValue();
    var iValor                    = oRegistro.aCells[2].getValue();
    var iMinimo                   = oRegistro.aCells[3].getValue(); 
    var iMaximo                   = oRegistro.aCells[4].getValue();
    var iTipoLancamento           = oRegistro.aCells[5].getValue();
    var lInconsistenciaZerado     = false;
    var lInconsistenciaMenorMaior = false;
     
    var oValorTipo             = new Object();
        oValorTipo.iValor      = iValor; 
        oValorTipo.iSequencial = iSequencial;
        oValorTipo.iTipoLanc   = iTipoLancamento;
        oValorTipo.iMinimo     = iMinimo;
        oValorTipo.iMaximo     = iMaximo;
       
        aRegistrosProcessar.push(oValorTipo); 
     
  }); 
  
  return aRegistrosProcessar;
}  




 /*
  * Inicia a Montagem do grid tipos de avaliação
  *
  */
function js_gridTipoAvaliacao() {

  oGridTipoAvaliacao                 = new DBGrid('TipoAvaliacao');
  oGridTipoAvaliacao.nameInstance    = 'oGridTipoAvaliacao';
  oGridTipoAvaliacao.hasTotalizador  = false;
  oGridTipoAvaliacao.allowSelectColumns(false);
  
  oGridTipoAvaliacao.setCellWidth(new Array( 
                                             '50px'  ,
                                             '250px' ,
                                             '150px' , 
                                             '50px'  ,
                                             '50px'  ,
                                             '50px' 
                                           ));
  
  oGridTipoAvaliacao.setCellAlign(new Array( 
                                             'left'  ,
                                             'left'  ,
                                             'center',
                                             'right',
                                             'right' ,
                                             'center' 
                                           ));
  
  oGridTipoAvaliacao.setHeader(new Array( 
                                          'Seq'  ,
                                          'Tipo de Avaliação',
                                          'Valor',
                                          'Mínimo'  ,
                                          'Máximo' ,
                                          'tipoLanc'
                                        ));
                                       
  //oGridTipoAvaliacao.aHeaders[2].lDisplayed = false; 
  //oGridTipoAvaliacao.aHeaders[3].lDisplayed = false; 
  //oGridTipoAvaliacao.aHeaders[4].lDisplayed = false; 
  oGridTipoAvaliacao.aHeaders[5].lDisplayed = false; 
  oGridTipoAvaliacao.setHeight(120);
  oGridTipoAvaliacao.show($('ctnTipoAvaliacao'));
  oGridTipoAvaliacao.clearAll(true);
  
  js_listaTipoAvaliacao();
  
}

/*
 * funcao para montar os registros iniciais da grid
 *
 */ 
function js_listaTipoAvaliacao() {

   var msgDiv             = "Pesquisando Tipos de Avaliação \n Aguarde ...";
   oParametros.exec       = 'tipoAvaliacao';
    
   js_divCarregando(msgDiv,'msgBox');
  
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoTipoAvaliacao
                                             });
                                            
}

/*
 * funcao para montar a grid com os registros de tipo de avaliacao
 *  retornado do RPC
 *
 */ 
function js_retornoTipoAvaliacao(oAjax) {
    
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.iStatus == 1) {
      
      oGridTipoAvaliacao.clearAll(true);

      oRetorno.dados.each( 
                    function (oDado, iInd) {       

                        var aRow    = new Array();  
                            
                            aRow[0] = oDado.h69_sequencial;
                            aRow[1] = oDado.h69_descricao.urlDecode();
                            /*
                              validamos o tipo de lançamento
                              se for 2, valor padrao, vem como campo readonly com o valor padrao
                            */
                            if (oDado.h68_tipolancamento != 2) {
                              aRow[2] = "<input type='text' value='0' maxlength='10' onkeyup=\"js_ValidaCampos(this,1,'Pontuação','f','f',event);\" id='tipo_"+oDado.h69_sequencial+"'  /> ";
                            } else {
                              aRow[2] = "<input style='background-color: rgb(222, 184, 135);' readonly='readonly' value="+oDado.h69_quantmaxima+" type='text' maxlength='10' onkeyup=\"js_ValidaCampos(this,1,'Pontuação','f','f',event);\" id='tipo_"+oDado.h69_sequencial+"'  /> ";
                            }
                            aRow[3] = oDado.h69_quantminima;
                            aRow[4] = oDado.h69_quantmaxima;
                            aRow[5] = oDado.h68_tipolancamento;
                            oGridTipoAvaliacao.addRow(aRow);
                       });
      oGridTipoAvaliacao.renderRows(); 
      
    } else {
      alert(oRetorno.sMessage.urlDecode());
      return false;
    }
}




function js_novo(){

  window.location = 'rec4_avaliacao001.php';
}


</script>