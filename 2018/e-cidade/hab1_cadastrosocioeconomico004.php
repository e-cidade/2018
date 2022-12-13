<?php
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_habitgrupoprograma_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oRotuloCampos = new rotulocampo();
$oRotuloCampos->label("z01_nome");
$oRotuloCampos->label("z01_numcgm");

$oGet = db_utils::postMemory($_GET);

unset($_SESSION["oCandidatoHabitacao"]);
 
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
                   dbmessageBoard.widget.js,dbautocomplete.widget.js,dbcomboBox.widget.js,
                   datagrid.widget.js");
      db_app::load("estilos.css,grid.style.css");
    ?>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <center>
      <form name='form1' method="post">
        <table>
          <tr>
            <td>
              <fieldset>
               <table>
                 <tr>
                   <td>
                     <?db_ancora($Lz01_nome, "js_pesquisacgm(true)", 1)?>
                   </td>
                   <td>
                     <?
                      db_input("z01_numcgm", 10, $Iz01_numcgm, true, "text", 1, "onchange='js_pesquisacgm(false);situacaoCpf();'");
                      db_input("z01_nome", 40, $Iz01_nome, true, "text", 3);
                     ?>
                     <input type='button' value='Novo' onclick="js_novoCgm()"> 
                     <input type='button' value='alterar' onclick="js_alterarCgm($F('z01_numcgm'))"> 
                   </td>
                 </tr>
                 <tr>
                   <td>
                     <b>Situação CPF :</b>
                   </td>
                   <td>
                     <?
                     
                       $aSituacaoCPF = array('0'=>'Selecione ...',
                                             '1'=>'Regular',
                                             '2'=>'Irregular',
                                             '3'=>'Suspenso');
                       
                       db_select('situacaocpf',$aSituacaoCPF,true,1);
                     ?>
                   </td>
                 </tr>                 
               </table>
             </fieldset>
            </td>
          </tr>
          <tr>
            <td>
              <fieldset>
                <legend>
                   <b>Grupo</b>
                </legend>
                <table>
                  <tr>
                    <td>
                     <?
                     
                       $oDaoHabitacaoGrupo = new cl_habitgrupoprograma;
                       
                       $sSqlGrupos = $oDaoHabitacaoGrupo->sql_query_file(null,"*","ht03_descricao");
                       $rsGrupos   = $oDaoHabitacaoGrupo->sql_record($sSqlGrupos);
                     
                       $aGrupos = db_utils::getCollectionByRecord($rsGrupos);
                       foreach ($aGrupos as $oGrupo) {

                         $sHtml  = " <input type='checkbox'                        "; 
                         $sHtml .= "       value='{$oGrupo->ht03_sequencial}'      "; 
                         $sHtml .= "        name='grupohabitacao'                  ";
                         $sHtml .= "          id='grupo{$oGrupo->ht03_sequencial}' ";
                         $sHtml .= "     onclick='js_getProgramasGrupo({$oGrupo->ht03_sequencial})'/>";
                         $sHtml .= " <label for='grupo{$oGrupo->ht03_sequencial}'>{$oGrupo->ht03_descricao}</label>";
                         $sHtml .= " <br>";
                         
                         echo $sHtml;
                       }
                     ?>
                    </td>
                  </tr>
                </table>
              </fieldset>
            </td>
          </tr>
          <tr>
            <td>
              <fieldset>
                <legend>
                   <b>Programa</b>
                </legend>
                <table>
                  <tr>
                    <td id='programasgrupos'>
                    </td>
                  </tr>
                </table>
              </fieldset>    
            </td>
          </tr>
          <tr>
            <td colspan="2" style="text-align: center">
              <? 
                if (isset($oGet->alteracao)) {
                  echo "<input type='button' value='Pesquisar Cadastro' onclick='js_pesquisarCandidatos()'>";
                }
              ?>
            </td>
          </tr>  
        </table>
      </form>
    </center>
  </body>
</html>
<script>

var sUrlRPC = 'hab4_inscricaocanditado.RPC.php';

function js_getProgramasGrupo() {
   
   var aGruposDisponiveis    = $$('input[name=grupohabitacao]');
   var aProgramasDisponiveis = $$('input[name=programahabitacao]');
   
   var aGruposSelecionados    = new Array();
   var aProgramasSelecionados = new Array();
   
   aGruposDisponiveis.each(function (oCheckbox) {
     if (oCheckbox.checked) {
       aGruposSelecionados.push(oCheckbox.value);
     }
   });
   
   aProgramasDisponiveis.each(function (oCheckbox) {
     if (oCheckbox.checked) {
       aProgramasSelecionados.push(oCheckbox.value);
     }
   });   
   
   parent.document.formaba.cadsocioeconomico.disabled  = false;
   parent.document.formaba.composicaofamiliar.disabled = false;
   
   var oParam                    = new Object();
   oParam.exec                   = 'getHabitacaoProgramasGrupo';
   oParam.sGruposSelecionados    = aGruposSelecionados.join(',');
   oParam.sProgramasSelecionados = aProgramasSelecionados.join(',');
   
   js_divCarregando("Aguarde, pesquisando programas dos grupos selecionados", "msgBox");
   
   var oAjar    = new Ajax.Request(sUrlRPC, 
                                  {
                                   method:'post',
                                   parameters:'json='+Object.toJSON(oParam),
                                   onComplete:js_retornoGetPrograma
                                  })
}

function js_retornoGetPrograma(oAjax) {
  
  js_removeObj("msgBox");
  
  var oRetorno = eval("("+oAjax.responseText+")");
  
  $('programasgrupos').innerHTML = '';
  
  if (oRetorno.iStatus == 1) {
    
    var sHtml = '';
    
    oRetorno.aProgramas.each(function (oLinha, id) {
  
      sHtml += "<input type='checkbox'                          "; 
      sHtml += "      value='"+oLinha.ht01_sequencial+"'        "; 
      sHtml += "       name='programahabitacao'                 "; 
      sHtml += "         id='programa"+oLinha.ht01_sequencial+"'";
      
      if (oLinha.lChecked) {
        sHtml += " checked ";
      }
      
      sHtml += "/>";
      sHtml += "<label for='programa"+oLinha.ht01_sequencial+"' >"+oLinha.ht01_descricao.urlDecode()+"</label>";
      sHtml += "<br>";
    }); 
      
    $('programasgrupos').innerHTML = sHtml;      
    
  } else {
    alert(oRetorno.sMsg.urlDecode());
  }
}


function js_pesquisarCandidatos() {

   js_OpenJanelaIframe('', 
                       'db_iframe_candidato', 
                       'func_habitcandidato.php?funcao_js=parent.js_carregaCandidato|ht10_numcgm',
                       'Pesquisar Candidatos',
                       true,
                       '0'
                      );
}

function js_carregaCandidato(iCgm) {
  
  db_iframe_candidato.hide();
  
  js_divCarregando('Aguarde, carregando dados do Candidato', 'msgBox');
  
  var oParam   = new Object();
  oParam.exec  = 'getDadosCandidato';
  oParam.iCgm  = iCgm;
  var oAjax    = new Ajax.Request(sUrlRPC, 
                                 {
                                   method:'post',
                                   parameters:'json='+Object.toJSON(oParam),
                                   onComplete:js_retornoGetCandidato
                                 });
}

function js_retornoGetCandidato(oAjax) {
  
  js_removeObj('msgBox');
  
  var oRetorno = eval("("+oAjax.responseText+")");
  
  if (oRetorno.iStatus == 1 ) {
  
    var aGrupos = $$('input[name=grupohabitacao]');
    
    aGrupos.each(function (oCheckbox) {
     
      oCheckbox.checked = false; 
      if (js_search_in_array(oRetorno.candidato.aInteresseGrupo, oCheckbox.value)) {
        oCheckbox.checked = true;
      }
    });
    
    $('z01_numcgm').value = oRetorno.candidato.iNumCgm;
    $('z01_nome').value   = oRetorno.candidato.sNome.urlDecode();
    
    if (oRetorno.candidato.iSituacaoCpf == null){
      $('situacaocpf').value = 0;
    } else {
      $('situacaocpf').value = oRetorno.candidato.iSituacaoCpf;
    }
    
    parent.document.formaba.cadsocioeconomico.disabled=false
    parent.iframe_cadsocioeconomico.setDados(oRetorno.candidato.iAvaliacao);
    parent.iframe_composicaofamiliar.setDados(oRetorno.candidato.aFamiliares);  
  
    js_getProgramasGrupo();
  
  } else {
    alert(oRetorno.sMsg.urlDecode());
  }
  
}

function js_pesquisacgm(mostra){

  if (mostra) {
     js_OpenJanelaIframe('', 
                         'db_iframe_cgm', 
                         'func_nome.php?funcao_js=parent.js_mostracgm1|z01_nome|z01_numcgm&filtro=1',
                         'Pesquisar CGM',
                         true,'0');
  } else {
    if(document.form1.z01_numcgm.value != ''){ 
       js_OpenJanelaIframe('',
                           'db_iframe_acordogrupo',
                           'func_nome.php?pesquisa_chave='+$F('z01_numcgm')+
                           '&funcao_js=parent.js_mostracgm&filtro=1',
                           'Pesquisa',
                           false,
                           '0');
    } else {
      document.form1.z01_numcgm.value = ''; 
    }
  }
}

function js_mostracgm(erro, chave){
  document.form1.z01_nome.value = chave; 
  if(erro == true) { 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  } else {
    parent.iframe_composicaofamiliar.adicionaPrincipal($F('z01_numcgm'), chave);
  }
}

function js_mostracgm1(chave1, chave2) {

  $('z01_numcgm').value = chave2;
  $('z01_nome').value  = chave1;
  db_iframe_cgm.hide();
  parent.iframe_composicaofamiliar.adicionaPrincipal(chave2, chave1);
}


function js_novoCgm() {
  js_OpenJanelaIframe('', 
                      'db_iframe_novocgm', 
                      'prot1_cadgeralmunic001.php?lMenu=false&lFisico=true&funcaoRetorno=parent.CurrentWindow.corpo.iframe_grupoprograma.retornoCgm',
                      'Novo CGM',
                      true,
                      '0');
}

function js_alterarCgm(iCgm) {

  if (iCgm != "") {
    js_OpenJanelaIframe('', 
                        'db_iframe_novocgm', 
                        'prot1_cadgeralmunic002.php?chavepesquisa='+iCgm+
                        '&lMenu=false&lCpf=true&funcaoRetorno=parent.CurrentWindow.corpo.iframe_grupoprograma.retornoCgm',
                        'Novo CGM',
                        true,
                        '0');
  }
}


function retornoCgm(iCgm) {
  
  db_iframe_novocgm.hide();
  $('z01_numcgm').value = iCgm;
  js_pesquisacgm(false); 
}
/*
 * funcao para retornar a situação do CPF
 *
 */ 
function situacaoCpf() {


   var iCgm         = $F('z01_numcgm');

   var oParametros  = new Object();
   
   oParametros.exec = 'getSituacaoCpf';
   oParametros.iCgm = iCgm;
    
  
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoSituacaoCpf
                                             });
                                            
}
function js_retornoSituacaoCpf(oAjax) {
    
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.iStatus == 1) {
      if ( oRetorno.sResult == "1" || oRetorno.sResult == 1 ) {
		      $("situacaocpf").options.length = 0;
		      $("situacaocpf").options[0] = new Option(oRetorno.sSituacao, oRetorno.iSituacao);
		      $("situacaocpf").options[1] = new Option('Regular',"1");
		      $("situacaocpf").options[2] = new Option('Irregular', "2");
		      $("situacaocpf").options[3] = new Option("Suspenso", "3");
		      //alert(oRetorno.sSituacao);
		  } else {
		  
          $("situacaocpf").options.length = 0;
          $("situacaocpf").options[0] = new Option('Selecione...', '');
          $("situacaocpf").options[1] = new Option('Regular',"1");
          $("situacaocpf").options[2] = new Option('Irregular', "2");
          $("situacaocpf").options[3] = new Option("Suspenso", "3");		  
		  }   
    } 
}

</script>
