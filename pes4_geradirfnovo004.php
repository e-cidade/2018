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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_libpessoal.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
db_postmemory($HTTP_POST_VARS);
global $db_config;
db_selectmax("db_config","select lower(trim(munic)) as d08_carnes , cgc from db_config where codigo = ".db_getsession("DB_instit"));

if(trim($db_config[0]["cgc"]) == "90940172000138"){
     $d08_carnes = "daeb";
}else{
     $d08_carnes = $db_config[0]["d08_carnes"];
}
$db_opcao = 1;
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("rh01_numcgm");
$clrotulo->label("rh21_descr");
$clrotulo->label("rh08_descr");
$clrotulo->label("rh18_descr");
$clrotulo->label("rh37_descr");
$clrotulo->label("r70_descr");
$clrotulo->label("r59_descr");
$clrotulo->label("db90_descr");
$clrotulo->label("rh50_oid");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

<?php
db_app::load('prototype.js, 
              strings.js, 
              scripts.js');

db_app::load("estilos.css");
?>

<script>
function js_emite(){
  //js_controlarodape(true);
  qry  = 'ano_base='+ document.form1.ano_base.value;
  qry += '&oriret='+ document.form1.oriret.value;
  qry += '&codret='+ document.form1.codret.value;
  qry += '&nomeresp=' + document.form1.nomeresp.value;
  qry += '&cpfresp=' + document.form1.cpfresp.value;
  qry += '&dddresp=' + document.form1.dddresp.value;
  qry += '&foneresp=' + document.form1.foneresp.value;
  qry += '&r70_numcgm=' + document.form1.r70_numcgm.value;
  if(document.form1.pref_fun){
    qry += '&pref_fun=' + document.form1.pref_fun.value;
  }
  js_OpenJanelaIframe('top.corpo','db_iframe_geradirf','pes4_geradirf002.php?'+qry,'Gerando Arquivo',true);
}

function js_erro(msg){
  //js_controlarodape(false);
  top.corpo.db_iframe_geradirf.hide();
  alert(msg);
}
function js_fechaiframe(){
  db_iframe_geradirf.hide();
}
function js_controlarodape(mostra){
  if(mostra == true){
    document.form1.rodape.value = parent.bstatus.document.getElementById('st').innerHTML;
    parent.bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;<blink><strong><font color="red">GERANDO ARQUIVO</font></strong></blink>' ;
  }else{
    parent.bstatus.document.getElementById('st').innerHTML = document.form1.rodape.value;
  }
}

function js_detectaarquivo(arquivo,pdf){
//  js_controlarodape(false);
  top.corpo.db_iframe_geradirf.hide();
  listagem = arquivo+"#Download Arquivo TXT |";
  listagem+= pdf+"#Download Relatório";
  js_montarlista(listagem,"form1");
}

</script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  
    <center>
    <form name="form1" method="post" id='form1' action="">
    <table border='0' style="margin-top:20px">
    
	   <tr>
	    <td><fieldset><legend><b>Informações de Processamento</b></legend>
	      <table>
	       <tr>
	        <td nowrap>
	          <b>Tipo de Processamento:</b>
	        </td>
	        <td nowrap>
	          <?php
	            $aTipoProcessamento = array("1" => "Geral",
	                                        "2" => "Selecionados");
	            db_select("iTipoProcessamento", $aTipoProcessamento, true, 1, "onchange='return js_tipoProcessamento();' style='width: 100%;'");
	          ?>
	        </td>
          <td nowrap>
            <b>Somente Valores Acima de 24.556,65: </b> 
          </td>
          <td>
            <?php
             $arr_acima = array("S"=>"Sim","N"=>"Não");
             db_select('acima6000',$arr_acima,true,4,"");
	          ?>
          
          </td>
	      </tr>
	     </table>
	    </fieldset>
	   </td>
	   </tr>
    
     <tr>
       <td>
         <fieldset><legend><b>Dados DIRF</b></legend> 
          <table>
            <tr>
              <td align="right" nowrap title="Digite o Ano Base">
                <strong>Ano Base:</strong>
              </td>
              <td align="left">
                <?php
                  $sqlanomes = "select max(cast(r11_anousu as text)||lpad(cast(r11_mesusu as text),2,'0')) from cfpess";
                  $resultanomes = db_query($sqlanomes);
                  db_fieldsmemory($resultanomes,0);
                  $ano_base = substr($max,0,4)-1;
                  db_input('ano_base', 4, 1, true, 'text', 2, "onchange='return js_tipoProcessamento();'", null, null, null, 4);
                  
                ?>
              </td>
            </tr>
            <tr align="center">
              <td align="right" nowrap title="Tipo de Declaração: ">
                <strong>Tipo de Declaração:</strong>
              </td>
              <td align="left">
                <?php
                 $xy = array("O"=>"Original","R"=>"Retificadora");
                 db_select('oriret',$xy,true,4,"");
                ?>
              </td>
            </tr>
            <tr id='recibo'>
              <td align="right" nowrap>
                <b>Número do Recibo:</b>
              </td>
              <td>
                <?php
                 db_input('numerorecibo', 10, 1, true, 'text', 2, '');
                ?>
              </td>
            </tr>
          </table>
       </fieldset>
     </td>
     </tr>                              
    <tr>                                 
     <td colspan="2"  align="center">    
     <fieldset>                          
        <legend><strong>Dados do Responsável</strong></legend>
        <table width="100%">
      <tr>
        <td align="right" nowrap title="Nome do Responsável ">
         <strong>Nome:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?php
            db_input('nomeresp',40,'',true,'text',2,'');
	        ?>
        </td>
        </tr>
        <tr>
        <td align="right" nowrap title="Código Nacional de Pessoal FÍSICA" >
        <strong>CPF:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?php
            db_input('cpfresp', 14, '', true, 'text', 2, 
                      "  onBlur='js_verificaCGCCPF(this)' 
                         onKeyDown='return js_controla_tecla_enter(this,event);' 
                         onKeyUp='js_limpa(this)' ", 
                     null, null, null, 14);
          ?>
        </td>
        </tr>
        <tr>
        <td align="right" nowrap title="DDD do Responsável">
         <strong>DDD:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?php
            db_input('dddresp', 4, 1, true, 'text', 2, '', null, null, null, 3);
	        ?>
        </td>
        </tr>
        <tr>
        <td align="right" nowrap title="Fone do Responsável ">
         <strong>Fone:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?php
            db_input('foneresp', 10, 1, true, 'text', 2, '', null, null, null, 9);
       	  ?>
        </td>
        </tr>
         <tr>
        <td align="right" nowrap title="CPF do Responsável pelo CNPJ">
        <strong>CPF do Responsável pelo CNPJ:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?php
            db_input('cpfrespcnpj', 14, '', true, 'text', 2,
                     " onBlur='js_verificaCGCCPF(this)' 
                       onKeyDown='return js_controla_tecla_enter(this,event);' 
                       onKeyUp='js_limpa(this)' ", null, null, null, 14);
          ?>
        </td>
       </tr>        
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td><fieldset><legend><b>Saúde</b></legend>
      <table>
      <tr>
        <td nowrap title="<?=@$Trh01_numcgm?>">
          <?php
            db_ancora(@$Lrh01_numcgm,"js_pesquisarh01_numcgm(true);",$db_opcao);
          ?>
        </td>
        <td nowrap>
          <?php
            db_input('rh01_numcgm',6,$Irh01_numcgm,true,'text',$db_opcao,"onchange='js_pesquisarh01_numcgm(false);' tabIndex='1'","cgm_saude1");
          ?>
          <?php
            db_input('z01_nome',33,$Iz01_nome,true,'text',3,'',"nome_saude1");
          ?>
        </td>
        <td align="right" nowrap title="ANS" >
         <strong>ANS:</strong>
        </td>
        <td align="left">
          <?php
            db_input('numeroans1',10,'',true,'text',2,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Trh01_numcgm?>">
          <?php
            db_ancora(@$Lrh01_numcgm,"js_pesquisarh01_numcgm_1(true);",$db_opcao);
          ?>
        </td>
        <td nowrap>
          <?php
            db_input('rh01_numcgm',6,$Irh01_numcgm,true,'text',$db_opcao,"onchange='js_pesquisarh01_numcgm_1(false);' tabIndex='2'","cgm_saude2");
          ?>
          <?php
            db_input('z01_nome',33,$Iz01_nome,true,'text',3,'',"nome_saude2");
          ?>
        </td>
        <td align="right" nowrap title="ANS">
         <strong>ANS:</strong>
        </td>
        <td align="left">
          <?php
            db_input('numeroans2',10,'',true,'text',2,'');
          ?>
        </td>
       </tr>
     </table>
    </fieldset>
   </td>
   </tr>     
   <tr>
    <td><fieldset><legend><b>Informações Financeiras</b></legend>
      <table><tr>
        <td nowrap title="<?=@$Trh01_numcgm?>">
          <b>Buscar Pagamentos Efetuados na Contabilidade:</b>
        </td>
        <td nowrap>
         <?php
           $arr = array('s' => 'Sim','n'=>'Não');
           db_select("dadosfinanceiros",$arr,true,$db_opcao,"");
         ?>
        </td>
      </tr>
     </table>
    </fieldset>
   </td>
   </tr>
   <tr>
    <td colspan="2">
      <fieldset>
        <legend><b>CNPJ</b></legend>
        <table>
          <tr>
            <td nowrap align="right" title="CNPJ">
              <b>CNPJ:</b>
            </td>
            <td>
              <?php
		            $instit = db_getsession("DB_instit");
		            $sSqlUnidades  = "select distinct  o41_cnpj, ";
		            $sSqlUnidades .= "       case when o41_cnpj = cgc then nomeinst else o41_descr end as nome_fundo ";
		            $sSqlUnidades .= "  from orcunidade  ";
		            $sSqlUnidades .= "       inner join orcorgao  on o41_orgao  = o40_orgao ";
		            $sSqlUnidades .= "                           and o40_anousu = o41_anousu ";
		            $sSqlUnidades .= "       inner join db_config on codigo     = o41_instit ";
		            $sSqlUnidades .= " where o41_instit = {$instit} ";
		            $sSqlUnidades .= "   and o41_anousu = ".db_getsession("DB_anousu");
		            $result = db_query($sSqlUnidades);
		            db_selectrecord("cnpjpagador", $result, true, @$db_opcao, "", "", "", "", "return js_tipoProcessamento()", "2");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
	  <td colspan="2" align = "center"> 
      <input  name="gera" id="gera" type="button" value="Gerar DIRF" onclick="return js_emitirDirf();">
        </td>
      </tr>
     </table> 
  </form>
</center>
</body>
</html>
<script>
$('numerorecibo').maxLength = 12;
function js_pesquisarh01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('', 
                        'func_nome', 
                        'func_nome.php?testanome=true&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  } else {
    if (document.form1.cgm_saude1.value != '') {
     
      js_OpenJanelaIframe('',
                          'func_nome', 
                          'func_nome.php?testanome=true&pesquisa_chave='+
                          document.form1.cgm_saude1.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
    }else{
      document.form1.nome_saude1.value = ''; 
    }
  }
}

function js_mostracgm(erro,chave1,chave2) {

  document.form1.cgm_saude1.value  = chave1;
  document.form1.nome_saude1.value = chave2; 
  
  if (erro == true) { 
    document.form1.cgm_saude1.focus(); 
    document.form1.cgm_saude1.value = ''; 
  }
}

function js_mostracgm1(chave1,chave2){

  document.form1.cgm_saude1.value = chave1;
  document.form1.nome_saude1.value = chave2;
  func_nome.hide();
}

function js_pesquisarh01_numcgm_1(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('', 
                        'func_nome', 
                        'func_nome.php?testanome=true&funcao_js=parent.js_mostracgm1_1|z01_numcgm|z01_nome','Pesquisa',true);
  } else {
    if (document.form1.cgm_saude2.value != '') {
     
      js_OpenJanelaIframe('',
                          'func_nome', 
                          'func_nome.php?testanome=true&pesquisa_chave='+
                          document.form1.cgm_saude2.value+'&funcao_js=parent.js_mostracgm_1','Pesquisa',false);
    }else{
      document.form1.nome_saude2.value = ''; 
    }
  }
}

function js_mostracgm_1(erro,chave1,chave2) {

  document.form1.cgm_saude2.value  = chave1;
  document.form1.nome_saude2.value = chave2; 
  
  if (erro == true) { 
    document.form1.cgm_saude2.focus(); 
    document.form1.cgm_saude2.value = ''; 
  }
}

function js_mostracgm1_1(chave1,chave2){

  document.form1.cgm_saude2.value = chave1;
  document.form1.nome_saude2.value = chave2;
  func_nome.hide();
}

function js_tipoProcessamento() {
  
  var iTipoProcessamento = new Number($('iTipoProcessamento').value);
  if (iTipoProcessamento == 2) {
  
    parent.document.formaba.geradirf.disabled     = false;
    parent.document.formaba.selecionados.disabled = false;
    top.corpo.iframe_selecionados.js_montaGrid();
    parent.mo_camada('selecionados');
  } else {
  
    parent.document.formaba.geradirf.disabled     = true;
    parent.document.formaba.selecionados.disabled = true;
    top.corpo.iframe_selecionados.js_montaGrid();
    parent.mo_camada('geradirf');
  }
}

function js_emitirDirf() {

  var oParam  = new Object();
  oParam.exec = 'gerarDirf';
  oParam.iAno                   = $F('ano_base');
  oParam.TipoDeclaracao         = $F('oriret');
  oParam.iNumeroRecibo          = $F('numerorecibo');
  oParam.sNomeResponsavel       = tagString(encodeURIComponent($F('nomeresp')));
  oParam.sCpfResponsavelCNPJ    = $F('cpfrespcnpj');
  oParam.sDDDResponsavel        = $F('dddresp');
  oParam.sFoneResponsavel       = $F('foneresp');
  oParam.sCpfResponsavel        = $F('cpfresp');
  oParam.iCcgmSaude             = $F('cgm_saude1');
  oParam.iNumeroANS             = $F('numeroans1');
  oParam.iCcgmSaude2            = $F('cgm_saude2');
  oParam.iNumeroANS2            = $F('numeroans2');
  oParam.lProcessaEmpenho       = $F('dadosfinanceiros')=='s'?true:false;
  oParam.sCnpj                  = $F('cnpjpagador');
  oParam.sAcima6000             = $F('acima6000');
  oParam.aMatriculaSelecionadas = top.corpo.iframe_selecionados.js_retornaMatriculasSelecionados();
  
  js_divCarregando('Aguarde, Processando...','div_msg', true);
  
  var oAjax = new Ajax.Request('pes4_processardirf.RPC.php',
                               {method:'post',
                               parameters:'json='+Object.toJSON(oParam),
                               onComplete:js_retornoEmiteDirf
                               });
}

function js_retornoEmiteDirf(oAjax) {

  js_removeObj('div_msg');

  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
  
   var listagem = "tmp/"+oRetorno.arquivo+"#Download Arquivo TXT";
       js_montarlista(listagem,"form1");
  } else {
   alert('Erro ao gerar Arquivo.');
  }
}
</script>