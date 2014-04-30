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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/db_liborcamento.php");
require_once("model/relatorioContabil.model.php");

$oGet = db_utils::postMemory($_GET);
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');

db_postmemory($HTTP_POST_VARS);
$oRelatorio = new relatorioContabil($oGet->codrel);
$anousu = db_getsession("DB_anousu");

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>

<script>

variavel = 1;
function js_emite() {

  sel_instit  = new Number(document.form1.db_selinstit.value);
  if(sel_instit == 0){
    alert('Voce nao escolheu nenhuma Instituicao. Verifique!');
    return false;
  }else{
    obj = document.form1;
    if (obj.emite_balorc.checked==false   &&
        obj.emite_desp_funcsub.checked==false &&
        obj.emite_rcl.checked==false          &&
        obj.emite_rec_desp.checked==false     &&
        obj.emite_resultado.checked==false    &&
        obj.emite_rp.checked==false           &&
        obj.emite_oper.checked==false         &&
        obj.emite_mde.checked==false          &&
        obj.emite_alienacao.checked==false    &&
        obj.emite_proj.checked==false         &&
        obj.emite_ppp.checked==false          &&
        obj.emite_saude.checked==false){
        alert("Selecione pelo menos um relatorio para ser impresso!");
	      return false;
    }
    var env = '';
    var aRelatorios = $$('input.relatorios');
    aRelatorios.each(function(oCheckbox, id) {
    
       var iEmite = 0;
       if (oCheckbox.checked) {
        iEmite = 1;
       }
       env += "&"+oCheckbox.id+"="+iEmite;
    })
    ;
//    env  = '&emite_balorc_rec='  + obj.emite_balorc_rec.value; 
//    env += '&emite_balorc_desp=' + obj.emite_balorc_desp.value;
//    env += '&emite_desp_funcsub='+ obj.emite_desp_funcsub.value;
//    env += '&emite_rcl='         + obj.emite_rcl.value;
//    env += '&emite_rec_desp='    + obj.emite_rec_desp.value;
//    env += '&emite_resultado='   + obj.emite_resultado.value;
//    env += '&emite_rp='          + obj.emite_rp.value;
//    env += '&emite_mde='         + obj.emite_mde.value;
//    env += '&emite_oper='        + obj.emite_oper.value;
//    env += '&emite_aplicacao_recursos='+ obj.emite_alienacao.value;
//    env += '&emite_proj='        + obj.emite_proj.value;
//    //env += '&emite_alienacao='   + obj.emite_alienacao.value;
//    env += '&emite_alienacao='   + '0';
//    env += '&emite_saude='       + obj.emite_saude.value;
//    env += '&emite_ppp='         + obj.emite_ppp.value;

    <?
    $executar = '';
    if ($anousu < 2007){
      $executar = "con2_lrfresumido002.php";
    } else if ($anousu == 2008){
      $executar = "con2_lrfresumido002_2008.php";
    } else if ($anousu == 2009) {
      $executar = "con2_lrfresumido002_2009.php";
    } else if($anousu == 2010) {
       $executar = "con2_lrfresumido002_2010.php";
    } else if ($anousu >= 2013) {
      $executar = "con2_lrfresumido002_2013.php";
    }
    
    if ($executar == '') {
      $executar = "con2_lrfresumido002_2010.php";
    }
    ?>

    jan = window.open('<?=$executar?>?db_selinstit='+obj.db_selinstit.value+'&bimestre='+obj.o116_periodo.value+env,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<form name="form1" method="post" action="" >
  <table  align="center" border=0>
    <tr>
      <td class='table_header'>
        Anexo VII - Demonstrativo Simplificado do Relatório Resumido da Execução Orçamentária
      </td>
    </tr>
    <tr>
      <td>
        <fieldset>
          <table border=0 width="100%">
            <tr>
              <td align="left" colspan="">
                <? db_selinstit('',300,100); ?>
        	    </td>
            </tr>
            <tr>
              <td nowrap>
                <b>Periodo :&nbsp;&nbsp;</b>
                <?
                 $aPeriodos = $oRelatorio->getPeriodos();
                 $aListaPeriodos = array();
                 foreach ($aPeriodos as $oPeriodo) {
                   $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
                 }
                 db_select("o116_periodo", $aListaPeriodos, true, 1);
                ?>
              </td> 
            </tr>
            <tr>
              <td colspan=2>
                <fieldset style='text-align:left; border:0px solid black;border-top:2px groove white'>
                  <legend ><b>Opções de Impressão</b></legend>
                  <table border=0>
                    <tr>
                      <td colspan>
                        <label for="emite_balorc">
                          <b>Balanço Orçamentário</b>
                        </label>
                      </td>
                      <td>
                        <input class='relatorios' codigo='79' type='checkbox' id='emite_balorc' disabled>
                      </td>
                    </tr>    
        					  <tr>
        					    <td colspan>
        					       <label for='emite_desp_funcsub'>
        					         <b>Despesas por Função/SubFunção
        					       </label>
        					    </td>
        						  <td>
        						    <input class='relatorios' codigo='96' type='checkbox' id='emite_desp_funcsub' disabled>
        					    </td>
        					  </tr>    
        					  <tr>
         			        <td colspan>
         			          <label for="emite_rcl">
        					      <b>Receita Corrente Líquida:</b>
        					      </label>
        					    </td>
        						  <td>
        						    <input class='relatorios' codigo='81' type='checkbox' id='emite_rcl' disabled>
        					    </td>
        				    </tr>    
        				    <tr>
        				      <td colspan>
        				        <label for='emite_rec_desp'>
        				          <b>Receita/Despesa do RPPS:</b>
        				        </label>
        				      </td>
        					    <td>
        					      <input class='relatorios' codigo='82' type='checkbox' id='emite_rec_desp' disabled>
        				      </td>
        				    </tr>    
        				    <tr>
        				      <td colspan>
        				        <label for='emite_resultado'>
        				          <b>Resultado Nominal/Primário:</b>
        				        </label>
        				      </td>
        					    <td>
        					      <input class='relatorios' codigo='88' type='checkbox' id='emite_resultado' disabled>
        				      </td>
        				    </tr>    
        				    <tr>
        				      <td colspan>
        				        <label for='emite_rp'>
        				        <b>Restos a Pagar</b>
        				        </label>
        				      </td>
        					    <td>
        					      <input class='relatorios' codigo='97' type='checkbox' id='emite_rp' disabled>
        				      </td>
        				    </tr>    
        				    <tr>
        				      <td colspan>
        				        <label for='emite_mde'>
        				         <b>Despesas com MDE:</b>
        				        </label>
        				      </td>
        					    <td>
        					      <input class='relatorios' codigo='86' type='checkbox' id='emite_mde' disabled>
        				      </td>
        				    </tr>    
        				    <tr>
        				      <td colspan>
        				        <label for='emite_saude'>
        				          <b>Despesas com Saúde:</b>
        				        </label>
        				      </td>
        					    <td>
        					      <input class='relatorios' codigo='85' type='checkbox' id='emite_saude' disabled>
        				      </td>
        				    </tr>    
        				    <tr>
        				      <td colspan>
        				        <label for="emite_oper">
           				        <b>Operações de Crédito e Despesas de Capital:</b>
           				      </label>  
        				      </td>
        				      <td>
        				        <input class='relatorios' codigo='105' type='checkbox' id='emite_oper' disabled>
        				      </td>
        				    </tr>
        				    <tr>
        			        <td colspan>
        		            <label for='emite_proj'>
        		             <b>Projeção Atuarial dos Regimes de Previdência</b>
        		             </label>
        		          </td>
         			        <td>
         			          <input class='relatorios' codigo='106' type='checkbox' id='emite_proj' disabled>
        			        </td>
        				    </tr>    
        				    <tr>
        			        <td colspan>
        			          <label for='emite_alienacao'>
        			           <b>Receita de Alienação de Ativos / Aplicação dos Recursos</b> 
        			          </label> 
        			        </td>
        				      <td>
        				        <input class='relatorios' codigo='107' type='checkbox' id='emite_alienacao' disabled>
        			        </td>
        				    </tr>
                    <tr>
                      <td colspan>
                        <label for='emite_ppp'><b>Despesas de Caráter Continuado Derivadas de PPP:</b></label>
                      </td>
                      <td>
                       <input class='relatorios' codigo='87' type='checkbox' id='emite_ppp' disabled>
                      </td>
                    </tr>  				        
                  </table>
                </fieldset>
              </td>
            </tr>
          </table>
      </fieldset>
    </td>
  </tr>    
  <tr>
    <td align="center" colspan="2">
      <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite();">
    </td>
  </tr>
</table>
</form>
</body>
</html>
<script>
function js_getRelatoriosPorPeriodos(iPeriodo) {

  var aRelatorios = $$('input.relatorios');
  aRelatorios.each(function(oCheckbox, id) {
  
     oCheckbox.disabled = true;
     oCheckbox.checked  = false;
  });
  var oParam             = new Object();
  oParam.exec            = "getRelatoriosPorPeriodos";
  oParam.iCodigoPeriodo  = iPeriodo;
  var oAjax    = new Ajax.Request('con4_configuracaorelatorioRPC.php',
                                {
                                 method: "post",
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_retornoRelatorios
                                 });
}

function js_retornoRelatorios(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  var aRelatorios = $$('input.relatorios');
  aRelatorios.each(function(oCheckbox, id) {
  
    for (var i = 0; i < oRetorno.itens.length; i++) {
      
      if (oCheckbox.getAttribute('codigo') == oRetorno.itens[i].o113_orcparamrel) {
        
        oCheckbox.disabled = false;
        oCheckbox.checked  = true;
      }   
    }
  });
}

js_getRelatoriosPorPeriodos($F('o116_periodo'));
$('o116_periodo').observe("change", function () {
  js_getRelatoriosPorPeriodos($F('o116_periodo'));    
});
</script>