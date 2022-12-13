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

set_time_limit(0);
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_lista_classe.php");
require_once ("classes/db_listadeb_classe.php");
require_once ("classes/db_listanotifica_classe.php");
require_once ("libs/db_sql.php");
require_once ("classes/db_termo_classe.php");
require_once ("classes/db_cgm_classe.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_listacda_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clcgm           = new cl_cgm;
$cllista         = new cl_lista;
$cllistadeb      = new cl_listadeb;
$cllistanotifica = new cl_listanotifica;
$clListaCda      = new cl_listacda;
$clrotulo        = new rotulocampo;

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

$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k51_procede');
$clrotulo->label('k51_descr');
$clrotulo->label('v83_sequencial');
$clrotulo->label('v84_sequencial');
$clrotulo->label('v83_nomearq');
$instit = db_getsession("DB_instit");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<style>

  .fieldInterno{
    margin-top: 10px;
    
  }
  .botoes{
    margin-top : 10px;
     
  }
  .link_botao {
    cursor: pointer;
  }

</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" onload="js_gridProcessados();"  >

<center>
  <form name="func_remessaprocessados" id='func_remessaprocessados' method="post">
    <fieldset style="margin-top: 50px;width: 700px;">
      <legend><b>Filtros</b></legend>
      

      <fieldset class="fieldinterno">
        <legend><b>Arquivo de Remessa </b></legend>
          <table border="0" width="100%" >
            <tr>
              <td align="right" nowrap width="40%" ><b>
                Sequencial :
                </b>
              </td>
              <td align="left">
                <?
                  db_input("v83_sequencial",  10, $Iv83_sequencial, true, "text", 1, "");
                ?>
              </td>
            </tr>
            <tr>
              <td align="right" nowrap width="40%" ><b>
                Nome do Arquivo :
                </b>
              </td>
              <td align="left">
                <?
                  db_input("v83_nomearq",  40, $Iv83_nomearq, true, "text", 1, "");
                ?>
              </td>
            </tr>
		        <tr>
		          <td align="right" nowrap title="<?=@$Tk60_codigo?>" >
		            <b><?db_ancora("Lista", "js_pesquisalista(true);", 4);?></b>
		          </td>
		          <td align="left">
		            <?
		              db_input("k60_codigo",  4, $Ik60_codigo, true, "text", 4, "onchange='js_pesquisalista(false);'");
		              db_input("k60_descr",  40, $Ik60_descr,  true, "text", 3, "");
		            ?>
		          </td>
		        </tr>            
            <tr>
              <td align="right" >
                <b>Data do Arquivo :</b>
              </td>
              <td align="left">
               <? db_inputdata("v83_dtgeracao",@$v83_dtgeracao_dia,@$v83_dtgeracao_mes,@$v83_dtgeracao_ano,true,'text',1);?>
              </td>
            </tr>        
          </table>
      </fieldset>

      <fieldset class="fieldinterno">
        <legend><b>Arquivo de Retorno</b></legend>
          <table border="0" width="100%" align="center">
            <tr>
              <td align="right" nowrap width="40%" ><b>
                Nome do Arquivo :
                </b>
              </td>
              <td align="left">
                <?
                  db_input("v84_nomearq",  40, $Iv83_nomearq, true, "text", 1, "");
                ?>
              </td>
            </tr>
            <tr>
              <td align="right" >
                <b>Data do Retorno :</b>
              </td>
              <td align="left">
               <? db_inputdata("retorno", null, null, null, true, 'text', 1);?>
              </td>
            </tr>
            <tr>
              <td align="right" >
                <b>Data do Processamento :</b>
              </td>
              <td align="left">
               <? db_inputdata("processamento", null, null, null, true, 'text', 1); ?>
              </td>
            </tr>                     
          </table>
      </fieldset>
      <div id='botoes' class="botoes">
        <input type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisaprocessado();"/>
        <input type="button" id="limpar"    value="Limpar"    onclick="js_limpar();"/>
        <input type="button" id="fechar"    value="Fechar"    onclick="js_fechar();" >
      </div>
      
      <div id='ctnRemessaProcessados' style='margin-top: 10px;'>
      </div>
      
      
    </fieldset> 
  </form>
</center>
</body>
</html>

<script>
var sUrlRPC = "jur4_certidarqremessa.RPC.php";

function preencheProcessados(iSequencial, 
                             sNomeRemessa, 
                             sDataRemessa, 
                             sNomeRetorno, 
                             sDataRetorno, 
                             iIniCda, 
                             iFimCda, 
                             iIniInicial, 
                             iFimInicial  ){

 parent.$('v83_nomearq').value   = sNomeRemessa;
 parent.$('v83_dtgeracao').value = sDataRemessa;
 
 // dados Retorno                   
 parent.$('v84_nomearq').value    = sNomeRetorno;
 parent.$('v84_dtarquivo').value  = sDataRetorno;
 parent.$('v84_sequencial').value = iSequencial;
 
 // dados CDA                     
 parent.$('iCdaIni').value       = iIniCda;
 parent.$('iCdaFim').value       = iFimCda;
 
 //dados Inicial                  
 parent.$('iInicialIni').value   = iIniInicial;
 parent.$('iInicialFim').value   = iFimInicial; 
  
  
 top.corpo.db_iframe_remessaprocessados.hide();
 parent.js_habilitaButons();
}


 /*
  * Inicia a Montagem do grid (sem os registros)
  *
  */
function js_gridProcessados() {

  oGridProcessados = new DBGrid('gridProcessados');
  oGridProcessados.nameInstance = 'oGridProcessados';
  oGridProcessados.setCellWidth(new Array( '70px' ,
                                            '280px',
                                            '100px'
                                           ));
  oGridProcessados.setCellAlign(new Array( 'left'  ,
                                            'left'  ,
                                            'center'
                                           ));
  oGridProcessados.setHeader(new Array( 'Sequencial',
                                         'Nome Arquivo',
                                         'Data Geração'
                                        ));
  oGridProcessados.setHeight(300);
  oGridProcessados.show($('ctnRemessaProcessados'));
  oGridProcessados.clearAll(true);
  
}


function js_pesquisaprocessado() {

  var iCodRemessa                = $F('v83_sequencial');
  var sNomeRemessa               = $F('v83_nomearq');
  var iCodLista                  = $F('k60_codigo');
  var sDataRemessa               = $F('v83_dtgeracao');
  var sNomeRetorno               = $F('v84_nomearq');
  var sDataRetorno               = $F('retorno');
  var sDataProcessamento         = $F('processamento');
  
  var oParametros                = new Object();
  var msgDiv                     = _M('tributario.juridico.func_remessaprocessados.pesquisando_registros');
  oParametros.exec               = 'processados';  
  oParametros.iCodRemessa        = iCodRemessa; 
  oParametros.iCodLista          = iCodLista;     
  oParametros.sNomeRemessa       = sNomeRemessa;
  oParametros.sDataRemessa       = sDataRemessa;
  oParametros.sNomeRetorno       = sNomeRetorno; 
  oParametros.sDataRetorno       = sDataRetorno;     
  oParametros.sDataProcessamento = sDataProcessamento;
  
  js_divCarregando(msgDiv,'msgBox');
   
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoProcessados
                                             });   
}

function js_retornoProcessados(oAjax){
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    
      if ( oRetorno.dados.length == 0 ) {
      
        alert(_M('tributario.juridico.func_remessaprocessados.nenhum_arquivo_encontrado'));
        return false;
      } 
      oGridProcessados.clearAll(true); 
      oRetorno.dados.each( 
                    function (oDado, iInd) {       

  // dados remessa                          
  var sNomeRemessa  = oDado.v83_nomearq.urlDecode();
  var sDataRemessa  = oDado.v83_dtgeracao;
 // dados Retorno    
  var iSequencial   = oDado.v84_sequencial;               
  var sNomeRetorno  = oDado.v84_nomearq.urlDecode();
  var sDataRetorno  = oDado.v84_dtarquivo;
 // dados CDA                     
  var iIniCda       = oDado.iCdaInicial;
  var iFimCda       = oDado.iCdaFinal;
 //dados Inicial                  
  var iIniInicial   = oDado.iInicialIni;
  var iFimInicial   = oDado.iInicialFim; 

                        var aRow    = new Array();  
                            aRow[0] = iSequencial;
                            aRow[1] = "<span class='link_botao' onclick='preencheProcessados("+iSequencial+",\""
                                                                                              + sNomeRemessa +"\",\""
                                                                                              + sDataRemessa +"\",\""
                                                                                              + sNomeRetorno +"\",\""
                                                                                              + sDataRetorno +"\",\""
                                                                                              + iIniCda +"\",\""
                                                                                              + iFimCda +"\",\""
                                                                                              + iIniInicial +"\",\""
                                                                                              + iFimInicial +"\");' >"+sNomeRemessa+"</span>";                           
                            aRow[2] = oDado.v83_dtgeracao;
                            oGridProcessados.addRow(aRow);
                       });
      oGridProcessados.renderRows(); 
}    

function js_pesquisalista(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lista','func_listacda.php?funcao_js=parent.js_mostralista1|k60_codigo|k60_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_lista','func_listacda.php?pesquisa_chave='+document.func_remessaprocessados.k60_codigo.value+'&funcao_js=parent.js_mostralista','Pesquisa','false');
  }
}

function js_mostralista(chave,erro){
  document.func_remessaprocessados.k60_descr.value = chave;
  if(erro==true){
    document.func_remessaprocessados.k60_descr.focus();
    document.func_remessaprocessados.k60_descr.value = '';
  }
  db_iframe_lista.hide();
}

function js_mostralista1(chave1,chave2){
  document.func_remessaprocessados.k60_codigo.value = chave1;
  document.func_remessaprocessados.k60_descr.value = chave2;
  db_iframe_lista.hide();
}



function js_limpar(){

  var aText = $('func_remessaprocessados').getInputs('text');
    aText.each(function (oText, id) {  
       oText.value = '';
    });  
    oGridProcessados.clearAll(true); 
}

function js_fechar() {

  top.corpo.db_iframe_remessaprocessados.hide();

}

</script>