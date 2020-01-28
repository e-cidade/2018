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
 * @revision $Author: dbtales.baz $
 * @version $Revision: 1.4 $
 */
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo();
$clrotulo->label("o05_ppalei");
$clrotulo->label("o0i_descricao");
$clrotulo = new rotulocampo();
$clrotulo->label("o05_ppaversao");
$clrotulo->label("o01_sequencial");
$clrotulo->label("o01_descricao");
$db_opcao = 1;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
 db_app::load("scripts.js, prototype.js, strings.js, estilos.css,grid.style.css, ppaUserInterface.js");
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
  <form name ='form1'>
    <center>
       <table>
         <tr>
            <td>
              <fieldset>
                <legend>
                  <b>Exportação do PPA para Orçamento</b>
                </legend>
                <table>
                  <tr>
                    <td nowrap title="<?=@$To05_ppalei?>">
                      <?
                      db_ancora("<b>Lei do PPA</b>","js_pesquisao05_ppalei(true);",$db_opcao);
                      ?>
                    </td>
                    <td nowrap>
                      <?
                      db_input('o05_ppalei',10,$Io01_sequencial,true,'text',$db_opcao,"
                                onchange='js_pesquisao05_ppalei(false);'");
                      db_input('o01_descricao',40,$Io01_descricao,true,'text',3,'');
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td nowrap title="<?=@$To05_ppaversao?>">
                      <b>Perspectiva:</b>
                    </td>
                    <td id='verppa'>

                    </td>
                  </tr>
                  <tr>
                    <td>
                      <b>Ano para Exportacao:</b>
                    </td>
                    <td>
                      <?
                        db_input('anointegrar', 10,0, true, 'text', 3, '');
                      ?>
                    </td>
                  </tr>
                </table>
              </fieldset>
            </td>
         </tr>
         <tr>
           <td colspan="2" style='text-align: center'>
             <input type="button" id='processar' disabled value="Processar" onclick='js_processar()'>
           </td>
         </tr>
       </table>
       <div id='erro' style='width:50%; text-align: left;font-weight: bold; display:none'>
       <table width="100%" cellspacing="0" style='border: 2px inset white'>
         <tr>
            <th class="table_header">Ano</th>
            <th class="table_header">Dotação</th>
            <th class="table_header">Quantidade</th>
         </tr>
         <tbody style="background-color: white" id='listaerros'>
         </tbody>
       </table>
       </div>
    </center>
  </form>
</body>

</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
 function js_pesquisao05_ppalei(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('',
                          'db_iframe_ppalei',
                          'func_ppalei.php?funcao_js=parent.js_mostrappalei1|o01_sequencial|o01_descricao',
                          'Pesquisa de Leis para o PPA',
                          true);
    }else{
       if(document.form1.o05_ppalei.value != ''){
          js_OpenJanelaIframe('',
                              'db_iframe_ppalei',
                              'func_ppalei.php?pesquisa_chave='
                              +document.form1.o05_ppalei.value+'&funcao_js=parent.js_mostrappalei',
                              'Leis PPA',
                              false);
       }else{
         document.form1.o01_descricao.value = '';
       }
    }
  }

  function js_mostrappalei(chave, erro) {

    document.form1.o01_descricao.value = chave;
    if(erro==true){
      document.form1.o05_ppalei.focus();
      document.form1.o05_ppalei.value = '';
      js_limpaComboBoxPerspectivaPPA();
      } else {
        js_getVersoesPPA($F('o05_ppalei'), 1);
      }
      js_getUltimoAnoIntegrado();
  }

  function js_mostrappalei1(chave1,chave2){

    document.form1.o05_ppalei.value = chave1;
    document.form1.o01_descricao.value = chave2;
    js_getVersoesPPA(chave1, 1);
    db_iframe_ppalei.hide();
    js_getUltimoAnoIntegrado();
  }

 /**
  * trazemos qual o proximo ano que devemos homologar
  */
 function js_getUltimoAnoIntegrado() {

    var oParam = new Object();
    oParam.o119_sequencial = $F('o05_ppaversao');
    oParam.exec            = "getUltimoAnoIntegrado";
    var oAjax   = new Ajax.Request(
                         'orc4_ppaRPC.php',
                         {
                          asynchronous:false,
                          method    : 'post',
                          parameters: 'json='+js_objectToJson(oParam),
                          onComplete: js_retornogetUltimoAnoIntegrado
                         }
                        );
 }

 function js_retornogetUltimoAnoIntegrado(oRequest) {
 
   var oRetorno 		 = eval("("+oRequest.responseText+")");
   var iAnoExercicio = <?php echo db_getsession("DB_anousu");?>
        
   if (oRetorno.status == 1) {

	   if((oRetorno.anointegrar - iAnoExercicio) > 1 ){

		   js_limpa();
		   alert('Perspectiva já processada o exercício seguinte.');
		 }else{
		 
      $('processar').disabled = false;
      $('anointegrar').value  = oRetorno.anointegrar;
		 }
   }
 }
 function js_processar() {

    if ($F('o05_ppaversao') == "0") {

      alert('Informe a Perspectiva!');
      return false;
    }
    if (!confirm('Confirma a integração do ppa com o orçamento de '+$F('anointegrar')+'?')) {
        
      return false;
    }
    var oParam = new Object();
    oParam.o119_sequencial = $F('o05_ppaversao');
    oParam.exec            = "integrar";
    $('processar').disabled = true;
    js_divCarregando("Aguarde, Gerando dados para orcamento","msgbox");
    var oAjax   = new Ajax.Request(
                         'orc4_ppaRPC.php',
                         {
                          method    : 'post',
                          parameters: 'json='+js_objectToJson(oParam),
                          onComplete: js_retornoProcessar,
                         }
                        );

 }

 function js_retornoProcessar(oRequest) {

   js_removeObj("msgbox");
   $('processar').disabled = false;
   var oRetorno = eval("("+oRequest.responseText+")");
   if (oRetorno.status == 1) {
     alert('Processamento concluído');
   } else if (oRetorno.status == 199) {

     var sMessage  = 'Foi Detectada a existência de projeções orçamentárias idênticas.\n';
         sMessage += 'Você deve corrigir as projeções através do menu:\n';
         sMessage += 'Procedimentos->Ações do PPA->Alteração.\n';
         sMessage += 'Clique em OK para ver os dados.\n';
     alert(sMessage);
     var saida  = "";
     $('erro').style.display='';
     oRetorno.itens.each(function (oErro, id) {

          saida += "<tr><td class='linhagrid' style='text-align:right'>"+oErro.o08_ano+"</td>";
          saida += "<td class='linhagrid'>"+oErro.dot.urlDecode()+"</td>";
          saida += "<td class='linhagrid' style='text-align:right'>"+oErro.qtd+"</td></tr>";

     });
     $('listaerros').innerHTML = saida;

   } else {
     alert(oRetorno.message.urlDecode());
   }
   js_limpa();
 }
 function js_limpa() {

   var aItens = $$('select, input[type=text]');
   aItens.each(function(input,id) {

     if (input.options) {
       input.options.length = 1;
     }
     input.value  = '';
   });

 }
 js_drawSelectVersaoPPA($('verppa'));
</script>