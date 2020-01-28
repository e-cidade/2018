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
$oRotuloCampos->label("z01_sexo");
$oRotuloCampos->label("z01_estciv");
$oRotuloCampos->label("z01_nasc");
$oRotuloCampos->label("z01_profis");
$oRotuloCampos->label("z01_escolaridade");
$oRotuloCampos->label("z01_renda");
$db_opcao   = 1;
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
    <form method="post" name='form1' class="container">
       <table>
         <tr>
           <td>
             <fieldset>
               <table>
                   <td> 
                     <b>Tipo Familiar:</b>
                   </td>
                   <td>
                   <?
                     $sCamposFamiliar = "z14_sequencial,
                                         case 
                                           z14_sequencial when 0 then 'Selecione...'
                                         else z14_descricao
                                         end as z14_descricao, z14_sequencial,
                                         case 
                                           z14_sequencial
                                             when 0   then 0
                                             when 15  then 1 
                                             when 1   then 2
                                             when 2   then 3
                                             when 4   then 4
                                             when 5   then 5
                                             when 10  then 6
                                         end as ordem";
                   
                     $oDaoTipoFamiliar =  db_utils::getDao("tipofamiliar");
                     $sSqlTipoFamiliar  = $oDaoTipoFamiliar->sql_query(null, 
                                                                       "{$sCamposFamiliar}",
                                                                       "ordem");
                     
                     $rsTipoFamiliar   = $oDaoTipoFamiliar->sql_record($sSqlTipoFamiliar);
                     db_selectrecord('tipofamiliar', $rsTipoFamiliar, true, 1, '', '', '', '', '', 1);

                   ?>
                   </td>
                 </tr>
                 <tr style='display:none' id='vinculocgm'>
                   <td>
                     <?db_ancora($Lz01_nome, "js_pesquisacgm(true)", 1)?>
                   </td>
                   <td>
                     <?
                      db_input("z01_numcgm", 10, $Iz01_numcgm, true, "text", 1, "onchange='js_pesquisacgm(false)'");
                      db_input("z01_nome", 40, $Iz01_nome, true, "text", 3);
                      
                     ?>
                     <input type='button' value='Novo' onclick="js_novoCgm()"> 
                     <input type='button' value='alterar' onclick="js_alterarCgm($F('z01_numcgm'))"> 
                 </tr>
                 <tr style='display:none' class='novocgm'>
                   <td>
                     <?=$Lz01_nome?>
                   </td>
                   <td>
                     <?
                      db_input("nomecgm", 50, $Iz01_nome, true, "text", 1, "");
                     ?>
                   </td>  
                   <td nowrap title="<?=$Tz01_nasc?>"> 
                    <?=$Lz01_nasc?>
                   </td>
                   <td nowrap title="<?=$Tz01_nasc?>"> 
                    <?
                    db_inputdata('z01_nasc',@$z01_nasc_dia,@$z01_nasc_mes,@$z01_nasc_ano,true,'text',$db_opcao);
                    ?>
                   </td>   
                 </tr>
                 <tr style='display:none' class='novocgm'>
                   <td nowrap title="<?=$Tz01_estciv?>"> 
                   <?=$Lz01_estciv?>
                   </td>
                   <td nowrap title="<?=$Tz01_estciv?>"> 
                     <?
                     $x = array(
                                "1" => "Solteiro",
                                "2" => "Casado",
                                "3" => "Viúvo",
                                "4" => "Divorciado"
                               );
                     db_select('z01_estciv', $x, true, $db_opcao, 'style="width:100%;text-align:left;"' );
                     ?>
                   </td>   
                   <td nowrap title="<?=$Tz01_sexo?>" align="right"> 
                     <?=$Lz01_sexo?>
                   </td>
                   <td nowrap title="<?=$Tz01_dtfalecimento?>"> 
                     <?
                     $aSex = array( 0  => "Selecione",
                                   "M" => "Masculino",
                                   "F" => "Feminino");
                     db_select('z01_sexo', $aSex, true, $db_opcao, 'style="width:100%;text-align:left;"');
                     ?>
                   </td>
                 </tr>
                 <tr style='display:none' class='novocgm'>
                   <td>
                      <?=$Lz01_profis?>
                   </td>
                   <td>
                   <?
                    db_input('z01_profis', 50, $Iz01_profis, true, 'text', $db_opcao);
                    ?> 
                   </td>
                   <td style="text-align: right">
                     <?=$Lz01_renda ?>
                   </td>
                   <td >
                   <?
                    db_input('z01_renda', 10, $Iz01_renda, true, 'text', $db_opcao, '', '', '', "width:100%");
                    ?>
                 </tr>
                 <tr style='display:none' class='novocgm'>
                   <td nowrap title=<?=@$Tz01_escolaridade?>> 
                   <?=@$Lz01_escolaridade?>
                   </td>
                   <td nowrap title="<?=@$Tz01_escolaridade?>" colspan="3"> 
                     <?
                      db_input('z01_escolaridade', 50, $Iz01_escolaridade, true, 'text', $db_opcao);
                     ?>
                   </td>
                 </tr>
               </table>
             </fieldset>
           </td>
         </tr>
         <tr>
           <td style="text-align: center">
              <input type='button' id='btnSalvar' value='Incluir' onclick='js_savecgm()'>
           </td>
         </tr>
         <tr>
           <td width="1000px">
              <fieldset>
               <legend><b>Composição Familiar</b></legend>
               <div id='ctnComposicaoFamiliar'></div>
              </fieldset>
           </td>
         </tr>
       </table>
    </form>
  </body>
</html>  
<script>

var sUrlRPC     = 'hab4_inscricaocanditado.RPC.php';
var aFamiliares = new Array();

function js_pesquisacgm(mostra){
  if (mostra == true) {
     js_OpenJanelaIframe('', 
                         'db_iframe_cgm', 
                         'func_nome.php?funcao_js=parent.js_mostracgm1|z01_nome|z01_numcgm&filtro=1',
                         'Pesquisar CGM',
                         true,'0');
  }else{
     if(document.form1.z01_numcgm.value != ''){ 
        js_OpenJanelaIframe('',
                            'db_iframe_acordogrupo',
                            'func_nome.php?pesquisa_chave='+$F('z01_numcgm')+
                            '&funcao_js=parent.js_mostracgm&filtro=1',
                            'Pesquisa',
                            false,
                            
                            
                            '0');
     }else{
       document.form1.z01_numcgm.value = ''; 
     }
  }
}

function js_mostracgm(erro, chave){
  document.form1.z01_nome.value = chave; 
  if(erro == true) { 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}

function js_mostracgm1(chave1, chave2) {

  $('z01_numcgm').value = chave2;
  $('z01_nome').value  = chave1;
  db_iframe_cgm.hide();
}

function setDados(aFamiliaresConsulta) {

  aFamiliares = new Array();
  aFamiliaresConsulta.each(function(oFamiliar, id) {
     
    aFamiliar           = new Array(); 
    aFamiliar[0]        = oFamiliar.iCgm;
    aFamiliar[1]        = oFamiliar.sNome.urlDecode();
    aFamiliar[2]        = oFamiliar.iTipo;
    aFamiliar[3]        = oFamiliar.sTipo.urlDecode();
    aFamiliar[4]        = "<input type='button' value='E' onclick='js_excluirFamiliar("+oFamiliar.iCgm+")'>";
    aFamiliar[4]       += "<input type='button' value='A' onclick='js_alterarFamiliar("+oFamiliar.iCgm+")'>";
    aFamiliares.push(aFamiliar);
  });
   preencheGrid();
}

function js_init() {

   oGridFamilia = new DBGrid('gridFamilia');
   oGridFamilia.nameInstance = 'oGridFamilia';
   oGridFamilia.setCellWidth(new Array('10%', "60%", "10%", "10%", "10%"));
   oGridFamilia.setHeader(new Array('Cgm', "Nome", "Cod.Tipo", "Tipo", "Ação"));
   oGridFamilia.show($('ctnComposicaoFamiliar'));
}

function js_savecgm() {

  if ($F('tipofamiliar') == 15) {
    js_incluirFamilia();
  } else {
    
    var iTipoFamiliar = $F('tipofamiliar');
    if (iTipoFamiliar == 0) {
    
      alert('Selecione o Tipo Familiar!');
      return false;
    }
    
    var sNomeCgm = $F('nomecgm');
    if (sNomeCgm.trim() == "") {
    
      alert('Informe o Nome do familiar!');
      return false;
    }
        
    var sDataNascimento = $F('z01_nasc');
    var iEstadoCivil    = $F('z01_estciv');
    var sEscolaridade   = $F('z01_escolaridade');
    var nRenda          = $F('z01_renda');
    var sSexo           = $F('z01_sexo');
    if (sSexo == 0) {
      
      alert ("Informe o sexo!");
      return false;
    }
    
    var sProfissao      = $F('z01_profis');    
    
    var oNovoCgm          = new Object();
    oNovoCgm.nome         = encodeURIComponent(tagString(sNomeCgm));
    oNovoCgm.nascimento   = sDataNascimento;
    oNovoCgm.estadocivil  = iEstadoCivil;
    oNovoCgm.renda        = nRenda;
    oNovoCgm.sexo         = sSexo;
    oNovoCgm.profissao    = encodeURIComponent(tagString(sProfissao));
    oNovoCgm.escolaridade = encodeURIComponent(tagString(sEscolaridade));
    oNovoCgm.principal    = parent.iframe_grupoprograma.$F('z01_numcgm');
    oNovoCgm.alterar      = false;
    
    if (oNovoCgm.principal == "") {
    
     alert('Candidato não Informado!');
     return false;    
    }
    
    js_divCarregando("Aguarde ...", "msgBox");    
    
    var oParam  = new Object();
    
    if (!$F('z01_numcgm') == '') {
    
      oNovoCgm.filho   = $F('z01_numcgm');
      oNovoCgm.alterar = true;
    }

    oParam.exec = 'adicionarCgmFamiliar';    
    oParam.oCgm = oNovoCgm; 
    
    var oAjax = new Ajax.Request(sUrlRPC,
                                 {method:'post',
                                  parameters:'json='+Object.toJSON(oParam),
                                  onComplete: js_retornoSaveCgm
                                 } 
                                ) 
  }
}

function js_retornoSaveCgm(oRequest) {
  
  var oRetorno = eval("("+oRequest.responseText+")");
  
  js_removeObj("msgBox");
  
  if (oRetorno.iStatus == 1) {
  
    $('z01_numcgm').value = oRetorno.numcgm; 
    $('z01_nome').value   = oRetorno.nome.urlDecode();
    
    if ($('btnSalvar').value == "Alterar") {
      js_excluirFamiliar(oRetorno.numcgm);
    }
    js_incluirFamilia(); 
  } else {
  
    alert(oRetorno.sMsg.urlDecode());
  }
}


function js_incluirFamilia() {

  if ($('btnSalvar').value == 'Incluir') {
   
    for (var i = 0; i < aFamiliares.length; i++) {
      
      if (aFamiliares[i][0] == $F('z01_numcgm')){
      
        alert('Familiar já informado!');
        return false;
      }
    }
  }
  
  var aLinha       = new Array();
  aLinha[0]        = $F('z01_numcgm');
  aLinha[1]        = $F('z01_nome');
  aLinha[2]        = $F('tipofamiliar');
  aLinha[3]        = $('tipofamiliar').options[$('tipofamiliar').selectedIndex].innerHTML;
  aLinha[4]        = "<input type='button' value='E' onclick='js_excluirFamiliar("+$F('z01_numcgm')+")'>";
  aLinha[4]       += "<input type='button' value='A' onclick='js_alterarFamiliar("+$F('z01_numcgm')+")'>";
  aFamiliares.push(aLinha);
  preencheGrid();
  $('z01_numcgm').value       = '';
  $('z01_nome').value         = '';
  $('nomecgm').value          = ''; 
  $('z01_profis').value       = ''; 
  $('z01_renda').value        = ''; 
  $('z01_nasc').value         = ''; 
  $('z01_escolaridade').value = ''; 
  $('z01_sexo').value         = '0'; 
  $('z01_estciv').value       = '1';
  $('tipofamiliar').value     = '0'; 
  
  $('btnSalvar').value = 'Incluir';
  
  
}
function preencheGrid() {
  
  oGridFamilia.clearAll(true);
  aFamiliares.each(function (aLinha, id) {
    
    oGridFamilia.addRow(aLinha);
    if (aLinha[2] == '0') {
      oGridFamilia.aRows[id].sStyle += 'display:none;';
    }
    
  });
  oGridFamilia.renderRows();
}

function js_excluirFamiliar(iFamiliar) {
  
  var iIndiceExcluir = '';
  aFamiliares.each(function(oFamiliar, id) {
    if (oFamiliar[0] == iFamiliar) {
      iIndiceExcluir = id;
    }
  });
  aFamiliares.splice(iIndiceExcluir, 1);
  preencheGrid();
}

function js_alterarFamiliar(iNumCgmAltera) {

  js_divCarregando("Aguarde ...", "msgBox");  
      
  var oParam          = new Object();
  oParam.exec         = 'buscarCgmFamiliar';
  oParam.oCgm         = iNumCgmAltera;
  
  aFamiliares.each(function(oFamiliar, id) {
  
    if (oFamiliar[0] == iNumCgmAltera) {
      oParam.tipofamiliar = oFamiliar[2];
    }
  });
   
  var oAjax = new Ajax.Request(sUrlRPC,
                                {method:'post',
                                  parameters:'json='+Object.toJSON(oParam),
                                  onComplete: js_retornoAlterarCgm
                                } 
                              )
}


/*
 * Retorno da valores para a alteração
 */
function js_retornoAlterarCgm(oRequest) {

  js_removeObj("msgBox");

  var oRetorno = eval("("+oRequest.responseText+")"); 
  
  if (oRetorno.iStatus == 1) {
  
    $('z01_numcgm').value       = oRetorno.cgmfamiliar;
    $('tipofamiliar').value     = oRetorno.tipofamiliar;
    $('nomecgm').value          = oRetorno.nome.urlDecode();
    $('z01_nasc').value         = js_formatar(oRetorno.nascimento, 'd');
    $('z01_renda').value        = oRetorno.renda;
    $('z01_profis').value       = oRetorno.profissao.urlDecode();
    $('z01_escolaridade').value = oRetorno.escolaridade.urlDecode();
    $('z01_sexo').value         = oRetorno.sexo;
    
    $('btnSalvar').value        = 'Alterar';

  } else {
  
    alert(oRetorno.sMsg.urlDecode());
  }
}



function js_getDados() {

}

function adicionaPrincipal(iNumCgm, sNome) {
  
  var aNovoPrincipal       = new Array();
  aNovoPrincipal[0]        = iNumCgm;
  aNovoPrincipal[1]        = sNome;
  aNovoPrincipal[2]        = '0';
  aNovoPrincipal[3]        = 'Principal';
  aNovoPrincipal[4]        = "<input type='button' value='E' onclick='js_excluirFamiliar("+iNumCgm+")'>";
  aNovoPrincipal[4]       += "<input type='button' value='A' onclick='js_alterarFamiliar("+iNumCgm+")'>";  
  
  var iIndiceExcluir = -1;
  
  aFamiliares.each(function(oFamiliar, id) {
     
    if (oFamiliar[2] == '0') {
      iIndiceExcluir = id;
    }
  });
  if (iIndiceExcluir > -1) {
     
    aFamiliares.splice(iIndiceExcluir, 1);
    aFamiliares.unshift(aNovoPrincipal);
  } else {
    aFamiliares.unshift(aNovoPrincipal);
  }
  preencheGrid();
}
function showformulario() {
 
  if ($F('tipofamiliar')  == 15) {
   
    $('vinculocgm').style.display     = '';
    js_hideNovoCgm(false);
  } else {
  
    $('vinculocgm').style.display     = 'none';
    js_hideNovoCgm(true);
  }
}
function js_hideNovoCgm(lShow) {

  var aLinhasNovoCgm = $$("tr.novocgm");
  aLinhasNovoCgm.each(function (oLinha, iSeq) {
     
     if (lShow) {
       oLinha.style.display = '';
     } else {
       oLinha.style.display = 'none';
     }
  }); 
}
js_init();
$('tipofamiliar').observe("change", showformulario);
showformulario();
function js_novoCgm() {
  js_OpenJanelaIframe('', 
                      'db_iframe_novocgm', 
                      'prot1_cadgeralmunic001.php?lMenu=false&lFisico=true&funcaoRetorno=parent.CurrentWindow.corpo.iframe_composicaofamiliar.atualizarcgm',
                      'Novo CGM',
                         true,'0');
}
function js_alterarCgm(iCgm) {

  if (iCgm != "") {
  js_OpenJanelaIframe('', 
                      'db_iframe_novocgm', 
                      'prot1_cadgeralmunic002.php?chavepesquisa='+iCgm+
                      '&lMenu=false&lCpf=true&funcaoRetorno=parent.CurrentWindow.corpo.iframe_composicaofamiliar.atualizarcgm',
                      'Novo CGM',
                         true,'0');
 }
}

function atualizarcgm(iCgm) {
  
  db_iframe_novocgm.hide();
  $('z01_numcgm').value = iCgm;
  js_pesquisacgm(false); 
}
</script>"
