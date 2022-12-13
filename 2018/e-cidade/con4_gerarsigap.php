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

/**
 * 
 * @author I
 * @revision $Author: dbiuri $
 * @version $Revision: 1.10 $
 */
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$periodo = array("1"  => " 1 - Janeiro          ",
                 "2"  => " 2 - Fevereiro (1 Bim)",
                 "3"  => " 3 - Março            ",
                 "4"  => " 4 - Abril     (2 Bim)",
                 "5"  => " 5 - Maio             ",
                 "6"  => " 6 - Junho     (3 Bim)",
                 "7"  => " 7 - Julho            ", 
                 "8"  => " 8 - Agosto    (4 Bim)",
                 "9"  => " 9 - Setembro         ",
                 "10" => "10 - Outubro   (5 Bim)",
                 "11" => "11 - Novembro         ",
                 "12" => "12 - Dezembro  (6 Bim)");

$clrotulo = new rotulocampo;
$clrotulo->label("o124_descricao");
$clrotulo->label("o124_sequencial");
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
    <table width="790" border="0" cellpadding="0" cellspacing="0" >
      <tr> 
        <td height="40px">&nbsp;</td>
      </tr>
    </table>
    <center>
      <form name="form1" method="post" action="">
        <table>
          <tr>
            <td>
              <fieldset>
                <legend><b>Gerar SIGAP</b></legend>
                  <table style='empty-cells: show;'>
                    <tr>
                      <td colspan="1">
                        <b>Arquivos do :</b>
                        <?
                          $periodopad = date("m",db_getsession("DB_datausu"))-1;
                          if(db_getsession("DB_anousu") != date("Y",db_getsession("DB_datausu"))){
                            $periodopad = 12;
                          } else {
                          	
                            if($periodopad == 0)
                              $periodopad = 1;
                          }
                          
                          db_select("periodosigap",$periodo,true,2);
                        ?>
                      </td>
                      <td>
                        <b>Código TCE:</b>
                        <?
                          db_input("codigotce", 4, 0, true, "text", 1);
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top" rowspan="2">
                        <table border="0" style='border-right: 2px groove white'>
                          <tr>
                            <td colspan='2' style='border-bottom: 2px groove white;text-align: center'>
                              <b>ARQUIVOS PRINCIPAIS</b></td>
                          </tr>
                          <tr>
                            <td><input type=checkbox name="Empenho"></td>
                            <td>Empenhos</td>
                          </tr>
                          <tr>
                            <td><input type=checkbox name="Liquidacao"></td>
                            <td>Liquidação</td>
                          </tr>
                          <tr>
                            <td><input type=checkbox name="ComprovanteLiquidacao"></td>
                            <td>Comprovante de Liquidação</td>
                          </tr>
                          <tr>
                            <td><input type=checkbox name="Pagamento"></td>
                            <td>Pagamento</td>
                          </tr>
                          <tr>
                            <td><input type=checkbox name="PagamentoFinanceiro"></td>
                            <td>Pagamento Financeiro</td>
                          </tr>
                          <tr>
                            <td><input type=checkbox name="BalanceteReceita"></td>
                            <td>Balancete de Receita</td>
                          </tr>
                          <tr>
                            <td><input type=checkbox name="Receita"></td>
                            <td>Receita</td>
                          </tr>
                          <tr>
                            <td><input type=checkbox name="BalanceteDespesa"></td>
                            <td>Balancete de Despesa</td>
                          </tr>
                          <tr>
                            <td><input type=checkbox name="Decreto"></td>
                            <td>Decretos</td>
                          </tr>
                          <tr>
                            <td><input type=checkbox name="BalanceteVerificacao"></td>
                            <td>Balancete de Verificação</td>
                          </tr>
                          <tr>
                            <td><input type=checkbox name="ReceitaDespesaExtra"></td>
                            <td>Receitas e Despesas Extra-Orçamentária</td>
                          </tr>
                          <tr>
                            <td><input type=checkbox name="SubsidioVereadores"></td>
                            <td>Subsídio Vereadores</td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                          </tr>
                         </table>
                       </td>  
                       <td valign='top' rowspan="2" style='border-right:2px groove white'>
                         <table border="0" style=''>
                           <tr>
                              <td colspan='2' style='border-bottom: 2px groove white;text-align: center'>
                                <b>ARQUIVOS COMPLEMENTARES</b>   
                             </td>
                           </tr>
                           <tr>
                             <td><input type=checkbox name="Orgao"></td>
                             <td>Orgão</td>
                           </tr>
                           <tr>
                             <td><input type=checkbox name="Unidade"></td>
                             <td>Unidades</td>
                           </tr>
                           <tr>
                             <td><input type=checkbox name="Funcao"></td>
                             <td>Funções</td>
                           </tr>
                           <tr>
                             <td><input type=checkbox name="SubFuncao"></td>
                             <td>Sub-funções</td>
                           </tr>
                           <tr>
                             <td><input type=checkbox name="Programa"></td>
                             <td>Programas</td>
                           </tr>
                           <tr>
                             <td><input type=checkbox name="Projativ"></td>
                             <td>Projetos/Atividades</td>
                           </tr>
                           <tr>
                             <td><input type=checkbox name="Rubrica"></td>
                             <td>Rubricas</td>
                           </tr>
                           <tr>
                             <td><input type=checkbox name="Recurso"></td>
                             <td>Recursos</td>
                           </tr>
                           <tr>
                             <td><input type=checkbox name="Credor"></td>
                             <td>Credor</td>
                           </tr>
                         </table>
                       </td>
                       
                       <td valign=top height=50%  >
                         <table border="0" style=''>
                           <tr>
                             <td colspan='2' style='border-bottom: 2px groove white;text-align: center'> 
                               <b>DO EXERCÍCIO</b>    
                             </td>
                           </tr>
                           <tr>
                             <td><input type=checkbox name="ContaDisponibilidade"></td>
                             <td>Disponibilidades</td>
                           </tr>
                           <tr>
                             <td><input type=checkbox name="ContaOperacao"></td>
                             <td>Operações</td>
                           </tr>
                         </table>
                        </td>
                        <td valign=top height=50%>
                         <table border="0" style="border-left: 2px groove white">
                           <tr>
                             <td colspan='2' style='border-bottom: 2px groove white;text-align: center'>
                              <b>DO EXERCICIO ANTERIOR</b>   
                             </td>
                           </tr>
                           <tr>
                             <td><input type=checkbox name="BalanceteReceitaAnterior"></td>
                             <td>Balancete Receita</td>
                           </tr>
                           <tr>
                             <td><input type=checkbox name="ReceitaAnterior"></td>
                             <td>Receita</td>
                           </tr>
                           <tr>
                             <td><input type=checkbox name="BalanceteRubricaAnterior"></td>
                             <td>Balancete por Rubrica</td>
                           </tr>
                           <tr>
                             <td><input type=checkbox name="BalanceteVerificacaoAnterior"></td>
                             <td>Balancete de Verificação</td>
                           </tr>
                           <tr>
                             <td><input type=checkbox name="BalanceteMovimentacaoAnterior"></td>
                             <td>Balancete Verificaçao Movimento Mensais</td>
                           </tr>
                         </table>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2" style='border-top: 2px groove white;height:30%'>
                        <div style='overflow:scroll;scroll-x:hidden;height:210px;' id='retorno'></div>
                      </td>
                    </tr>
                  </table>      
                </fieldset>
              </td>
            </tr>
            <tr>
              <td style="text-align: center">
               <input name="todos" type="button" value="Todos" onclick="js_marcaTodos();" >
               <input name="limpa" type="button" value="Limpa" onclick="js_limpa();" >
               <input name="processar" type="button" value="Processar" onclick="js_processar();">
              </td>
            </tr>
          </table>
      </form>
    </center>    
  </body>
  <div id='dadosppa' style='display:none; border:2px outset black;background:#cccccc'>
  <fieldset>
   <table>
     <tr>
       <td>
         <?
          db_ancora("<b>Perspectiva Cronograma:</b>","js_pesquisao125_cronogramaperspectiva(true);", 1);
         ?>
       </td>
       <td> 
         <?
         db_input('o124_sequencial',10,$Io124_sequencial,true,'text',
                 1," onchange='js_pesquisao125_cronogramaperspectiva(false);'");
         db_input('o124_descricao',40,$Io124_descricao,true,'text',3,'')
         ?>
       </td>  
     </tr>
     <tr>
        <td>
         <?
          db_ancora("<b>Perspectiva PPA:</b>","js_pesquisa_ppa(true);", 1);
         ?>
       </td>
       <td> 
         <?
         db_input('o119_sequencial',10,$Io124_sequencial,true,'text',
                 1," onchange='js_pesquisa_ppa(false);'");
         db_input('o119_descricao',40,$Io124_descricao,true,'text',3,'')
         ?>
       </td>  
      </tr>
      <tr>
      <tr>
        <td colspan="4" style='text-align: center'>
          <input type="button" value='Fechar' onclick='js_fecharDadosPPa()'>
        </td>
     </tr>  
   </table>
   </fieldset>
  </div>
</html>
<? db_menu(db_getsession("DB_id_usuario"), 
           db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>  
           
<script>
var sUrlRPC = 'con4_processarpad.RPC.php';
function js_processar() {

   if ($F('codigotce').trim() == "") {
   
     alert('informe do código do Município no TCE!');
     return false;
   }
   
   var oParam        = new Object();
   oParam.exec       = "processarSigap";
   oParam.iPeriodo   = $F('periodosigap');
   
   oParam.aArquivos  = new Array();
   oParam.iCodigoTCE = $F('codigotce');
   oParam.iPerspectivaPPa        = $F('o119_sequencial');
   oParam.iPerspectivaCronograma = $F('o124_sequencial');
   var aArquivos     = $$("input[type='checkbox']");
   var lErroPPA        = false;
   var lErroCronograma = false;
   aArquivos.each(function (oCheckbox, id) {
   
     with (oCheckbox) {
     
       if (checked) { 
         
         if (name == 'Ppa' ) {
           
           if (oParam.iPerspectivaPPa == '') {
             lErroPPA = true;
           }
         }
         if ( name == 'PpaLoa' || name == 'LoaDespesa' || name == 'LoaReceita') {
           
           if (oParam.iPerspectivaCronograma == '') {
             lErroCronograma = true;
           } 
         }
         oParam.aArquivos.push(oCheckbox.name);
       }
     }
     
   });
   if (lErroPPA) {
   
     alert('Informe uma perspectiva do ppa');
     return false;
   }
   if (lErroCronograma) {
   
     alert('Informe uma perspectiva do cronograma de desembolso!');
     return false;
   }
   js_divCarregando('Aguarde, Processando Arquivos', 'msgBox');
   
   var oAjax = new Ajax.Request(sUrlRPC,
                                {
                                  method:'post',
                                  parameters:'json='+Object.toJSON(oParam),
                                  onComplete:js_retornoProcessaSigap 
                                }
                              ); 
}

function js_retornoProcessaSigap(oAjax) {
   
  js_removeObj('msgBox');

  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
    
    var sRetorno = "<b>Arquivos Gerados:</b><br>";
    sRetorno    += "Verifique o arquivo SIGAP.log.<br>";
    for (var i = 0; i < oRetorno.itens.length; i++) {

      with (oRetorno.itens[i]) {
            
        sRetorno += "<a  href='db_download.php?arquivo="+caminho+"'>"+nome+"</a><br>";
      }
    }
    
    $('retorno').innerHTML = sRetorno;
  } else {
    
    $('retorno').innerHTML = '';
    alert(oRetorno.message.urlDecode());
    return false;
  }
}

function js_marcaTodos() {

  var aCheckboxes = $$('input[type=checkbox]');
  aCheckboxes.each(function(oCheckbox) {
    oCheckbox.checked = true;
  }); 
}

function js_limpa() {
   
  var aCheckboxes = $$('input[type=checkbox]');
  aCheckboxes.each(function (oCheckbox) {
    oCheckbox.checked = false;
  }); 
}
function js_pesquisao125_cronogramaperspectiva(mostra) {

  if (mostra==true){
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_cronogramaperspectiva',
                        'func_cronogramaperspectiva.php?funcao_js='+
                        'parent.js_mostracronogramaperspectiva1|o124_sequencial|o124_descricao|o124_ano',
                        'Perspectivas do Cronograma',true);
  }else{
     if ($F('o124_sequencial') != ''){ 
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_cronogramaperspectiva',
                            'func_cronogramaperspectiva.php?pesquisa_chave='+
                            $F('o124_sequencial')+
                            '&funcao_js=parent.js_mostracronogramaperspectiva',
                            'Perspectivas do Cronograma',
                            false);
     }else{
       $('o124_sequencial').value = '';
     }
  }
}

function js_mostracronogramaperspectiva(chave,erro, ano) {
  $('o124_descricao').value = chave; 
  if(erro==true) { 
    
    $('o124_sequencial').focus(); 
    $('o124_sequencial').value = '';
      
  }
}

function js_mostracronogramaperspectiva1(chave1,chave2,chave3) {

  $('o124_sequencial').value = chave1;
  $('o124_descricao').value  = chave2;
  db_iframe_cronogramaperspectiva.hide();
}

function js_pesquisa_ppa(mostra) {

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_ppa',
                        'func_ppaversaosigap.php?funcao_js='+
                        'parent.js_mostrappa1|o119_sequencial|o01_descricao',
                        'Perspectivas do Cronograma',true);
  }else{
     if( $F('o119_sequencial') != ''){ 
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_ppa',
                            'func_ppaversaosigap.php?pesquisa_chave='+
                            $F('o119_sequencial')+
                            '&funcao_js=parent.js_mostrappa',
                            'Perspectivas do Cronograma',
                            false);
     }else{
     
       document.form1.o124_descricao.value = '';
       document.form1.ano.value             = ''
        
     }
  }
}

function js_mostrappa(chave,erro, ano) {
  $('o119_descricao').value = chave; 
  if(erro==true) { 
    
    $('o119_sequencial').focus(); 
    $('o119_sequencial').value = '';
      
  }
}

function js_mostrappa1(chave1,chave2,chave3) {

  $('o119_sequencial').value = chave1;
  $('o119_descricao').value  = chave2;
  db_iframe_ppa.hide();
}
function js_pesquisaDadosPPA(event) {
  
  js_posionaDivApos($('dadosppa'), event.target);
  $('dadosppa').style.display = '';
  
}
function js_fecharDadosPPa() {
  $('dadosppa').style.display = 'none';
}

function js_posionaDivApos(sDiv, oObjeto) {

    el =  oObjeto;
    var x = 0;
    var y = el.offsetHeight;
    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {

     x += el.offsetLeft;
     y += el.offsetTop;
     el = el.offsetParent;

   }
   x += el.offsetLeft;
   y += el.offsetTop;
   sDiv.style.position = 'absolute';
   sDiv.style.top      = y+10;
   sDiv.style.left     = x;
}
var oMessageBoardUsuario = new DBMessageBoard("msg2",
                                       "Dados Solicitados: ",
                                       'Informe as perspectivas para o PPA e Cronograma de Desembolso',
                                       $("dadosppa")    
                                      );
   oMessageBoardUsuario.show();
</script>