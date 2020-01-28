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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label("codigo");
$clrotulo->label("nomeinst");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");


$mesinicial = db_mesfolha();
$anoinicial = db_anofolha();              
$anofinal   = db_anofolha();
$mesfinal   = db_mesfolha();

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
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_gridArquivossiprev();a=1" >
<form name="form1" method="post" action="">
<table align="center" style="padding-top: 25px;">
  <tr> 
    <td>
      <fieldset>
        <legend>
          <b>Gerar SiPrev</b>
        </legend>
        <table>
          <tr>
            <td>
              <b>Competência Inicial :</b>
            </td>
            <td>
              <?php
              
                 db_input('mesinicial',2,true,$mesinicial,'text',1);
                 echo "/";
                 db_input('anoinicial',4,true,$anoinicial,'text',1);                 
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <b>Competência Final :</b>
            </td>
            <td>
              <?php
                 db_input('mesfinal',2,true,$mesfinal,'text',1);
                 echo "/";
                 db_input('anofinal',4,true,$anofinal,'text',1);                 
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
          <b>Unidade Gestora</b>
        </legend>
        <table >

          <tr>
            <td nowrap title="<?=@$Tcodigo?>">
              <?php db_ancora(@$Lcodigo,"js_pesquisacodigo(true);",1); ?>
            </td>
            <td colspan="3"> 
              <?php
                db_input('codigo',6,$Icodigo,true,'text',1," onchange='js_pesquisacodigo(false);'");
                db_input('nomeinst',40,$Inomeinst,true,'text',3,'');
              ?>
            </td>
          </tr>


          <tr >
            <td align="left" ><strong>Ato Legal :&nbsp;&nbsp;</strong>
            </td>
            <td>
              <?
                $arr_TipoAtoLegal = array("2" =>"Decreto",
                                          "1" =>"Constituição Federal",
                                          "3" =>"Decreto Legislativo",
                                          "4" =>"Emenda",
                                          "5" =>"Lei Complementar",
                                          "6" =>"Lei Ordinária",
                                          "7" =>"Lei Delegada",
                                          "8" =>"Lei Orgânica",
                                          "9" =>"Medida Provisória",
                                          "10"=>"Portaria",
                                          "11"=>"Resolução",
                                          "12"=>"Parecer",
                                          "13"=>"Orientação Normativa",
                                          "99"=>"Outros",
                                         );
                db_select('TipoAto',$arr_TipoAtoLegal,true,4,"");
	            ?>
	          </td>
          </tr>
          <tr>
            <td align="left" ><strong>Numero / Ano do Ato :&nbsp;&nbsp;</strong> 
            </td>
              <td align="left"> 
              
               <?db_input('NumeroAto',6, '',true,'text',4,'')?> /
               <?db_input('AnoAto',4, '',true,'text',4,'')?>
              
              <strong>&nbsp;&nbsp;&nbsp;&nbsp;Data do Ato :&nbsp;&nbsp;</strong> 
                <?
                $dt_dia = '';
                $dt_mes = '';
                $dt_ano = '';
                db_inputdata('DataAto',$dt_dia,$dt_mes,$dt_ano,true,'text',1);
                ?>
              </td>

          </tr>



          <tr>
            <td nowrap title="Nome do representante legal pela Unidade Gestora.">
              <?php db_ancora("Representante Legal : ","js_pesquisacgm(true);",1); ?>
            </td>
            <td colspan="3"> 
              <?php
                db_input('z01_numcgm',6,$Iz01_numcgm,true,'text',1," onchange='js_pesquisacgm(false);'");
                db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
              ?>
            </td>
          </tr>

        </table>
      </fieldset>
    </td>
  </tr>



  <tr>
    <td align="center">
      <input type="button" id="btnGerarArquivos" value="Gerar Arquivos" onClick="gerar();">
    </td>
  </tr>
  
  <tr>
    <td align="center">
      <fieldset>
        <legend>
          <b>Arquivos Disponiveis</b>
        </legend>
        <table>
          <tr>
            <td>
              <div id="gridArquivossiprev" style="margin-top: 10px;"> </div>
            </td>
          </tr> 
          <tr id="download" style="display: none;">
            <td align="center">
              <div id="gridArquivosDownload" style="margin-top: 10px; margin-left: 100px;"></div>
            </td>
          </tr>                  
        </table>
      </fieldset>      
    </td>
  </tr>  
            
</table>
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script><!--
  
  //var sUrlRPC = 'con4_processarpad.RPC.php'; 
  var sUrlRPC = 'pes4_arquivossiprev.RPC.php';  
  var oParam  = new Object();
  
 /*
  * Inicia a Montagem do grid (sem os registros)
  *
  */
function js_gridArquivossiprev() {

  oGridSiprev = new DBGrid('Arquivossiprev');
  oGridSiprev.nameInstance = 'oGridSiprev';
  oGridSiprev.setCheckbox(0);
  oGridSiprev.setCellWidth(new Array('50px',
                                     '200px'));
  
  oGridSiprev.setCellAlign(new Array('left',
                                     'left'));
  
  oGridSiprev.setHeader(new Array('Código',
                                  'Arquivo'));
                                       
  //oGridSiprev.aHeaders[0].lDisplayed = false;
  oGridSiprev.setHeight(120);
  oGridSiprev.hasTotalizador = true;
  oGridSiprev.show($('gridArquivossiprev'));
  oGridSiprev.clearAll(true);
  
  lista_rharquivossiprev(); //inicia o preenchimento com o retorno dos registros
        
}
/*
 * funcao para montar os registros iniciais da grid
 *
 */ 
function lista_rharquivossiprev() {

   var oParametros      = new Object();
   oParametros.exec     = 'Lista';  
   oParametros.sTodos   = ('*');   
 
   var msgDiv    = "Aguarde ...";
   js_divCarregando(msgDiv,'msgBox');
  
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoCompletaSiprev
                                             });
                                            
}
/*
 * funcao para montar a grid com os registros da tabela rharquivossiprev
 *
 */ 
function js_retornoCompletaSiprev(oAjax) {
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {
      
      oGridSiprev.clearAll(true);
      if ( oRetorno.dados.length == 0 ) {
      
        alert('Nenhum registro encontrado!');
        return false;
      } else {
     // alert(oRetorno.dados.length);
       // $('processar').disabled = false;
      }
      oRetorno.dados.each( 
                    function (oDado, iInd) {       

                            aRow = new Array();                                                              
                            aRow[0]  = oDado.rh94_sequencial;
                            aRow[1]  = oDado.rh94_descricao.urlDecode();
                            oGridSiprev.addRow(aRow);
                       });
      oGridSiprev.renderRows(); 
    } 
}

 /*
  * Inicia o envio dos checkbox selecionados no grid
  */
function gerar() {

   var iMesIni         = $F('mesinicial');
   var iAnoIni         = $F('anoinicial');
   var iMesFim         = $F('mesfinal');
   var iAnoFim         = $F('anofinal');
   var iUnidadeGestora = $F('codigo');
   var iTipoAto        = $F('TipoAto');
   var iNumeroAto      = $F('NumeroAto');
   var iAnoAto         = $F('AnoAto');
   var dDataAto        = $F('DataAto');
   var cRepresentante  = $F('z01_nome');

   var aListaCheckbox = oGridSiprev.getSelection();
   var aListaArquivos = new Array();
   
   
   aListaCheckbox.each(
     function ( aRow ) {
       aListaArquivos.push(aRow[0]);
       //alert(aRow[0]);
    }
   );
   
   /*
    * Definimos as propriedades do objeto que será postado para o RCP
   */
   var oParametros            = new Object();
   oParametros.exec           = 'Gerar';
   oParametros.sListaArquivos = aListaArquivos.join(',');
   oParametros.iMesinicial    = iMesIni;
   oParametros.iAnoinicial    = iAnoIni;
   oParametros.iMesfinal      = iMesFim;
   oParametros.iAnofinal      = iAnoFim;
   oParametros.iUnidadeGestora= iUnidadeGestora;
   oParametros.iTipoAto       = iTipoAto;       
   oParametros.iNumeroAto     = iNumeroAto;     
   oParametros.iAnoAto        = iAnoAto;     
   oParametros.dDataAto       = dDataAto;       
   oParametros.cRepresentante = cRepresentante;       



   /*
    * Valida os dados antes da postagem
    * se a data inicial e final estao preenchida
    * se a data inicial é menor que a data final
    * se no minimo um arquivo foi selecionado
    * então a postagem para processamento será realizada.
   */   
   if (iMesIni == null || iMesIni == '' || iMesIni > 12 || iMesIni == 0) {
   
     alert('Mês Inicial Inválido');
     $('mesinicial').value = '';
     $('mesinicial').focus();
   } else if (iAnoIni == null || iAnoIni == '' || iAnoIni < 1900) {
   
     alert('Ano Inicial Inválido');
     $('anoinicial').value = '';
     $('anoinicial').focus();
   } else if (iMesFim == null || iMesFim == '' || iMesFim > 12 || iMesFim == 0) {
   
     alert('Mês Final Inválido');
     $('mesfinal').value = '';
     $('mesfinal').focus();     
   } else if (iAnoFim == null || iAnoFim == '' || iAnoFim < 1900) {
   
     alert('Ano Final Inválido');
     $('anofinal').value = '';
     $('anofinal').focus();     
   } else if (iAnoIni > iAnoFim) {
    
     alert('Ano Inicial Maior que Ano Final');
     $('anoinicial').value = '';
     $('anoinicial').focus();       
   } else if (oParametros.sListaArquivos == null || oParametros.sListaArquivos == "") {
     alert("Selecione no Minimo um tipo de Arquivo");
   } else {


	   var msgDiv    = "Aguarde ...";
	   js_divCarregando(msgDiv,'msgBox');
	   
	   var oAjaxArquivos  = new Ajax.Request(sUrlRPC,
	                                             {method: "post",
	                                              parameters:'json='+Object.toJSON(oParametros),
	                                              onComplete: retorno_siprev
	                                             });
  //  jan = window.open('pes4_relinconsistenciasiprev002.php?json='+Object.toJSON(oParametros),'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  //  jan.moveTo(0,0);
  }
  
}
 /*
  * Trata o Retorno do Processamento Siprev
  */
function retorno_siprev(oAjax) {
    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.status == 1) {
      
    //  oGridSiprev.clearAll(true);
      if ( oRetorno.dados.length == 0 ) {
      
        js_removeObj('msgBox');
        alert('Nenhum registro encontrado!');
        return false;
      } else {
            
            
		 // alert(oRetorno.arquivos);
            
	       
	      if (oRetorno.arquivos != '' || oRetorno.arquivos != null) {
	       
		        jan = window.open('pes4_relinconsistenciasiprev002.php?json='+Object.toJSON(oRetorno.arquivos),'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
		        jan.moveTo(0,0);      
	      }
       
      }
    $("download").style.display = "inline";
    $("gridArquivosDownload").innerHTML  = "<a target='_blank' href='tmp/SIPREV.zip' >SIPREV.zip </a> <br /><br />";
   // $("gridArquivosDownload").innerHTML += "<b>Arquivos de Erro</b> <br/><br/>";  
   // $("gridArquivosDownload").innerHTML += "<a target='_blank' href='tmp/servidor_erro.log' >Servidor_erro.log </a> <br />";
  //  $("gridArquivosDownload").innerHTML += "<a target='_blank' href='tmp/dependentes_erro.log' >Dependentes_erro.log </a> ";                   
    }
    js_removeObj('msgBox');
}

function js_pesquisacodigo(mostra){
  
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_db_config.php?funcao_js=parent.js_mostrarhpessoal1|codigo|nomeinst','Pesquisa',true);
  }else{
    if(document.form1.codigo.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_db_config.php?pesquisa_chave='+document.form1.codigo.value+'&funcao_js=parent.js_mostrarhpessoal','Pesquisa',false);
    }else{
      document.form1.nomeinst.value = ''; 
    }
  }
}
function js_mostrarhpessoal(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.codigo.focus(); 
    document.form1.codigo.value = ''; 
  }
}
function js_mostrarhpessoal1(chave1,chave2){
  document.form1.codigo.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_rhpessoal.hide();
}


function js_pesquisacgm(mostra){
  
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    if(document.form1.z01_numcgm.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = ''; 
    }
  }
}
function js_mostracgm(erro, chave) {
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}




--></script>