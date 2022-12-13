<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
require_once("classes/db_solicita_classe.php");
$clsolicita = new cl_solicita;
$oRotulo = new rotulocampo;
$oRotulo->label("pc80_codproc");
$clsolicita->rotulo->label();
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc" style="margin-top: 25px" onload="">
<center>
<form name="form1" method="post" action="">
  <div style="display: table;">
    <fieldset>
      <legend> 
       <b>Filtros para Pesquisa Processo de Compras</b>
      </legend>
      <table border='0'>
        <tr>
          <td align="left" nowrap title="Emissão de:">
             <b>Data de Emissão: </b>
          </td>
          <td align="left"> 
            <?
              db_inputdata('datainicial',null ,null, null,true,'text',1);
            ?>
          </td>
          <td align="left" nowrap title="Emissão Até:">
             <b>Até : </b>
          </td>
          <td align="left"> 
            <?
              db_inputdata('datafinal',null ,null, null,true,'text',1);
            ?>
          </td>       
        </tr>
        <tr> 
          <td  align="left" nowrap title="<?=$Tpc10_numero?>"> <b>
            <? db_ancora("Solicitações de : ","js_solicitade(true);",1);?>  
          </td>
          <td align="left" nowrap>
            <?
               db_input("pc10_numerode",10,$Ipc10_numero,true,"text",4,"onchange='js_solicitade(false);'"); 
            ?>
          </td>
          <td  align="left" nowrap title="<?=$Tpc10_numero?>"> 
            <? db_ancora("<b>Até:</b> ","js_solicitaate(true);",1);?>  
          </td>
          <td align="left" nowrap>
            <?
               db_input("pc10_numeroate",10,$Ipc10_numero,true,"text",4,"onchange='js_solicitaate(false);'"); 
            ?>
          </td>       
        </tr>   
        <tr> 
          <td  align="left" nowrap title="<?=$Tpc10_numero?>"> <b>
            <? db_ancora("Processos de Compra de : ","js_pesquisaProcessoCompras(true, true);",1);?>  
          </td>
          <td align="left" nowrap>
            <?
              db_input("pc80_codproc", 10, $Ipc80_codproc, 
                       true, 
                       "text", 
                       4,
                       "onchange='js_pesquisaProcessoCompras(false, true);'",
                       "pc80_codprocini"
                      ); 
            ?>
            </b>
          </td>
          
          <td  align="left" nowrap title="<?=$Tpc10_numero?>"> 
            <? db_ancora("<b>Até:</b> ","js_pesquisaProcessoCompras(true, false);",1);?>  
          </td>
          <td align="left" nowrap>
            <?
               db_input("pc80_codproc",10,$Ipc80_codproc, true,
                         "text", 4, 
                         "onchange='js_pesquisaProcessoCompras(false, false);'", 
                         "pc80_codprocfim"); 
            ?>
          </td>       
        </tr>  
      </table>
  </div>
  <input name="enviar" type="button" id="btnPesquisar" value="Enviar dados" >
</form>
</center>
</body>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script>
//  Solicitacao DE
function js_solicitade(mostra) {

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_solicita',
                        'func_solicitamanutencaoreserva.php?funcao_js=parent.js_mostrasolicitade1|'+
                        'pc10_numero&param_depart=<?=db_getsession("DB_coddepto")?>','Pesquisa',true);
  }else{
     if(document.form1.pc10_numerode.value != ''){
        js_OpenJanelaIframe('top.corpo', 
                            'db_iframe_solicita',
                            'func_solicitamanutencaoreserva.php?pesquisa_chave='+document.form1.pc10_numerode.value+
                            '&funcao_js=parent.js_mostrasolicitade&param_depart=<?=db_getsession("DB_coddepto")?>',
                            'Pesquisa',false);
     }else{
       document.form1.pc10_numerode.value = '';
     }
  }
}

function js_mostrasolicitade(chave,erro){
  if(erro==true){
    document.form1.pc10_numerode.focus();
    document.form1.pc10_numerode.value = '';
  }
}

function js_mostrasolicitade1(chave1,x){
  document.form1.pc10_numerode.value = chave1;
  db_iframe_solicita.hide();
}

// solicitacao ATE
function js_solicitaate(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_solicitaate',
                        'func_solicita.php?funcao_js=parent.js_mostrasolicitaate1|'+
                        'pc10_numero&param_depart=<?=db_getsession("DB_coddepto")?>',
                        'Pesquisa',true);
  } else {
  
     if (document.form1.pc10_numeroate.value != '') {
     
        js_OpenJanelaIframe('top.corpo', 
                            'db_iframe_solicitaate',
                            'func_solicita.php?pesquisa_chave='+
                            document.form1.pc10_numeroate.value+
                            '&funcao_js=parent.js_mostrasolicitaate&param_depart=<?=db_getsession("DB_coddepto")?>',
                            'Pesquisa',
                            false);
     }else{
       document.form1.pc10_numeroate.value = '';
     }
  }
}

function js_mostrasolicitaate(chave,erro){
  if(erro==true){
    document.form1.pc10_numeroate.focus();
    document.form1.pc10_numeroate.value = '';
  }
}

function js_mostrasolicitaate1(chave1,x){
  document.form1.pc10_numeroate.value = chave1;
  db_iframe_solicitaate.hide();
}


function js_pesquisaProcessoCompras(mostra, lInicial) {

  var sFuncaoRetorno         = 'js_mostraProcessoInicial';
  var sFuncaoRetornoOnChange = 'js_mostraProcessoInicialChange';
  var sCampo                 = 'pc80_codprocini';
  if (!lInicial) {
   
    var sFuncaoRetorno         = 'js_mostraProcessoFinal';
    var sFuncaoRetornoOnChange = 'js_mostraProcessoFinalChange';
    var sCampo                 = 'pc80_codprocfim';
  }
  
  if (mostra) {
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_processo',
                        'func_pcproc.php?funcao_js=parent.'+sFuncaoRetorno+'|'+
                        'pc80_codproc','Pesquisa Processo de Compras',true);
  } else {
     
     var sValorCampo = $F(sCampo); 
     if (sValorCampo != '') {
        js_OpenJanelaIframe('top.corpo', 
                            'db_iframe_processo',
                            'func_pcproc.php?pesquisa_chave='+sValorCampo+
                            '&funcao_js=parent.'+sFuncaoRetornoOnChange,
                            'Pesquisa Processo de Compras', 
                            false);
     } else {
       $F(sCampo).value = '';
     }
  }
}

function js_mostraProcessoInicial(iProcesso) {
  
  $('pc80_codprocini').value = iProcesso;  
  db_iframe_processo.hide();
}

function js_mostraProcessoInicialChange(iProcesso, lErro) {
  
  if (lErro) {
    $('pc80_codprocini').value = '';
  } 
}

function js_mostraProcessoFinal(iProcesso) {
  
  db_iframe_processo.hide();
  $('pc80_codprocfim').value = iProcesso;  
}

function js_mostraProcessoFinalChange(iProcesso, lErro) {
  
  if (lErro) {
    $('pc80_codprocfim').value = '';
  } 
}


function js_pesquisarProcessos() {
   
   var sUrl  = 'com3_pesquisaprocesso002.php';
   sUrl     += '?iProcessoInicial='+$F('pc80_codprocini'); 
   sUrl     += '&iProcessoFinal='+$F('pc80_codprocfim'); 
   sUrl     += '&iSolicitacaoInicial='+$F('pc10_numerode'); 
   sUrl     += '&iSolicitacaoFinal='+$F('pc10_numeroate');
   sUrl     += '&dtInicial='+$F('datainicial'); 
   sUrl     += '&dtFinal='+$F('datafinal');
   js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_processos_filtrados',
                        sUrl,
                        'Processos de Compras encontrados',
                        true
                       );
}

$('btnPesquisar').observe("click", js_pesquisarProcessos);
</script>