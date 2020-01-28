<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
//require("libs/db_liborcamento.php");
include("classes/db_cgm_classe.php");

require_once ("classes/db_empageordem_classe.php");
require("libs/db_app.utils.php");

$clempageordem = new cl_empageordem();
$clempageordem->rotulo->label();

$clcgm        = new cl_cgm;

$clrotulo = new rotulocampo;
$clcgm->rotulo->label();

db_postmemory($HTTP_POST_VARS);

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, widgets/windowAux.widget.js");
db_app::load("estilos.css, grid.style.css");
?>
<script>

function js_abre(){
  
  var sQuery = '';
  sQuery += 'e42_sequencial='+$F('e42_sequencial');
  sQuery += '&z01_numcgm='+$F('z01_numcgm');
  //sQuery += '&historico='+$F('historico');
  
  var dtemissao = '';
  
  if($F('dt_emissao') != ''){
    var aDtEmissao = $F('dt_emissao').split('/');
    var dtemissao = aDtEmissao[2]+'-'+aDtEmissao[1]+'-'+aDtEmissao[0];
  }
    
  sQuery += '&dtemissao='+dtemissao;
  
  js_OpenJanelaIframe('','db_iframe_pesquisa',
                      'emp1_empconsultanf002.php?'+sQuery+'&funcao_js=parent.js_consulta|e69_codnota',
                      'Pesquisa',true);
}
</script>  

<script>
function js_mascara(evt){
  var evt = (evt) ? evt : (window.event) ? window.event : "";
      
  if( (evt.charCode >46 && evt.charCode <58) || evt.charCode ==0 ){//8:backspace|46:delete|190:. 
    return true;
  }else{
	  return false;
  }  
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
<form name="form1" method="post" id="form1">
<table width="600" style="margin-top: 20px;" align="center">
  <tr>
  <td>
  <fieldset><legend><b>Reemitir OP Auxiliar por Credor</b></legend>
    <table>
      <tr>
        <td  align="left" nowrap title="<?=$Te69_numero?>">
        <? db_ancora("<b>Código da OP:</b>","js_pesquisaOrdemPagamento(true);",1);  ?>
        </td>
        <td>
        <?
          db_input("e42_sequencial",13,$Ie42_sequencial,true,"text",4,"onchange='js_pesquisaOrdemPagamento(false);'"); 
        ?>
        </td>
      </tr>
      <tr> 
        <td  align="left" nowrap title="<?=$Tz01_numcgm?>"><?db_ancora('<b>Credor:</b>',"js_pesquisa_cgm(true);",1);?></td>
        <td align="left" nowrap>
          <? 
            db_input("z01_numcgm",6,$Iz01_numcgm,true,"text",4,"onchange='js_pesquisa_cgm(false);'");
            db_input("z01_nome2",40,"",true,"text",3);  
          ?>
        </td>
      </tr>
      <tr>
        <td align='left'><b>Data Inicial:</b>
        
        </td>
        <td align='left'>
        <?
          db_inputdata('dt_inicial',"","","",false,'text',1,"","",""); 
        ?>
        &nbsp;à&nbsp;<b>Data Inicial:</b>
        <?
          db_inputdata('dt_final',"","","",false,'text',1,"","",""); 
        ?> 
        </td>
      </tr>
    </table>
  </fieldset>    
  </td>
  </tr>
  <tr>
    <td align="center">
    <input type="button" value='Pesquisar' onclick="js_abre();">
    </td>
  </tr>
</table>
</form>
</center>

<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
//-------------------------------------------------
function js_pesquisa_cgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','func_nome',
                        'func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome',
                        'Pesquisa',true);
  }else{
     if(document.form1.z01_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','func_nome',
                            'func_cgm.php?pesquisa_chave='+$F('z01_numcgm')+'&funcao_js=parent.js_mostracgm',
                            'Pesquisa',false);
     }else{
       $('z01_nome2').value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  
  document.form1.z01_nome2.value = chave; 
  if(erro==true){ 
    $('z01_numcgm').value = ''; 
    $('z01_numcgm').focus(); 
  }
}
function js_mostracgm1(chave1,chave2){
  $('z01_numcgm').value = chave1;  
  $('z01_nome2').value  = chave2;
  func_nome.hide();
}

function js_pesquisaOrdemPagamento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empageordem',
                        'func_empageordem.php?funcao_js=parent.js_mostraOrdemPagamento1|e42_sequencial&credor=true',
                        'Pesquisa',true);
  }else{
     if($F('e42_sequencial') != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_empageordem',
                            'func_empageordem.php?pesquisa_chave='+$F('e42_sequencial')+'&funcao_js=parent.js_mostraOrdemPagamento&credor=true',
                            'Pesquisa',false);
     }else{
       $('e42_sequencial').value = ''; 
     }
  }
}
function js_mostraOrdemPagamento(chave,erro){
  //$('e69_numero').value = chave; 
  if(erro==true){
    alert("\n\nusuário:\n\n Código informado não é válido !!!\n\nAdministrador:\n\n");
    $('e42_sequencial').value = '';  
    $('e42_sequencial').focus(); 
  }else{
    js_consultaOpAuxPorCredor();
  }
}
function js_mostraOrdemPagamento1(chave1,chave2){
   $('e42_sequencial').value = chave1;  
   db_iframe_empageordem.hide();
   js_consultaOpAuxPorCredor();
}

sUrl = "emp4_ordemPagamentoRPC.php";

function js_consultaOpAuxPorCredor() {

  js_divCarregando("Aguarde, pesquisando ...","msgBox");
     
  var oParametros            = new Object();
  oParametros.e42_sequencial = $F('e42_sequencial');
  oParametros.exec           = "consOpAuxCredor";
    
  var oAjax   = new Ajax.Request(
                           sUrl, 
                           {
                            method    : 'post', 
                            parameters: 'json='+Object.toJSON(oParametros) , 
                            onComplete: js_retornoConsultaOpAuxPorCredor
                            }
                          );
}

function js_retornoConsultaOpAuxPorCredor(oAjax) {
  
  js_removeObj("msgBox");
  
  oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno == '2') {
    
    alert(oRetorno.message.urlDecode());
     
  } else {
   $('z01_numcgm').value = oRetorno.dados[0].z01_numcgm;    
   $('z01_nome2').value = oRetorno.dados[0].z01_nome.urlDecode();    
   //$('dt_emissao').value = js_formatar(oRetorno.dados[0].e42_dtpagamento, 'd', 0) ;    
   //$('historico').value = oRetorno.dados[0].e94_historico.urlDecode();    
  } 
}

function js_novaOPAuxiliar() {

  if ($F('z01_numcgm') == "" ){
    alert('Credor não informada!');
    return false;
  }
  
  if ($F('dt_emissao') == "") {
    alert('Data de emissão não informada!');
    return false;
  }
  
  var oParametros             = new Object();
  oParametros.exec            = "incluirOP";
  oParametros.e42_dtpagamento = $F('dt_emissao'); 
  oParametros.z01_numcgm      = $F('z01_numcgm'); 
  oParametros.historico       = encodeURIComponent(tagString($F('historico'))); 
  
  js_divCarregando("Aguarde, Gerando Autorização.","msgBox");
  var oAjax   = new Ajax.Request(
                           sUrl, 
                           {
                            method    : 'post', 
                            parameters: 'json='+Object.toJSON(oParametros), 
                            onComplete: function(oResponse) {
                            
                              js_removeObj("msgBox");
                              oRetorno = eval ("("+oResponse.responseText+")");
                              if (oRetorno.status == 1) {
                              
                                 $('e42_sequencial').value = oRetorno.iCodigoOPaxiliar;
                                 js_carregaOPauxiliar();
                                 
                              } else {
                                alert(oRetorno.message.urlDecode());
                              }
                            }
                          }
                          );
}
/*
function js_carregaOPauxiliar() {

  if ($F('e42_sequencial') != "") {
    
    var iAlturaViewPort   = document.body.scrollHeight-30;
    var iLArguraViewPort  = document.body.scrollWidth-12;
    var sQuery = 'e42_sequencial='+$F('e42_sequencial');

    js_OpenJanelaIframe('top.corpo','db_iframe_op',
                        'emp4_ordemauxporcredor002.php?'+sQuery,
                        'Manutenção OP Auxiliar',
                        true,
                        22,
                        0,
                        iLArguraViewPort,
                        iAlturaViewPort
                       );
  }
} 
*/
function js_abre(){
  
  var sQuery = '';
  sQuery += 'e42_sequencial='+$F('e42_sequencial');
  sQuery += '&z01_numcgm='+$F('z01_numcgm');
  
  
  var dtInicial = '';
  var dtFinal   = ''; 
  if($F('dt_inicial') != ''){
    //var aDtInicial = $F('dt_inicial').split('/');
    //var dtInicial = aDtEmissao[2]+'-'+aDtEmissao[1]+'-'+aDtEmissao[0];
    var dtInicial = js_formatar($F('dt_inicial'),'d');
  }
  if($F('dt_final') != ''){
    //var aDtInicial = $F('dt_inicial').split('/');
    //var dtInicial = aDtEmissao[2]+'-'+aDtEmissao[1]+'-'+aDtEmissao[0];
    var dtFinal = js_formatar($F('dt_final'),'d');
  }
    
  sQuery += '&dtinicial='+dtInicial;
  sQuery += '&dtfinal='+dtFinal;
  

  js_OpenJanelaIframe('top.corpo','db_iframe_pesquisa',
                      'emp2_empordemauxporcredor003.php?'+sQuery+'&funcao_js=parent.js_emitir|e42_sequencial',
                      'Pesquisa',true);
}
function js_emitir(iCodigo) {
  db_iframe_pesquisa.hide();
  var e42_sequencial = iCodigo;
  
  window.open('emp2_ordemauxporcredor002.php?e42_sequencial='+e42_sequencial,'','location=0');
}

</script>
</body>
</html>