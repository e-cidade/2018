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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
$clrotulo = new rotulocampo;
$clrotulo->label("m80_codigo");
$db_opcao = 1;

$aux = new cl_arquivo_auxiliar;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_abre(){
  obj = document.form1;
   
  var obj = document.form1; //formulario
  
  var aAlmox = $('almoxarifados'); //select de almoxarifados
  var sAlmox = ""; //inicializa string dos almoxarifados vazia
  
  /* pega os almoxarifados (cria a string para pesquisa) */
  for (i = 0; i < aAlmox.options.length; i++) {    
    
    sAlmox += aAlmox.options[i].value;
    if ( i < (aAlmox.options.length-1)) {
       sAlmox += ",";
    }
  }
  
  /* PEGA OS VALORS DE PESQUISA */
  var iTipoPeriodo = $('tipo_periodo').value;   // obj.tipo_periodo.value;              //tipo de periodo   
  var iTransfIni   = $('m80_codigo_ini').value; // obj.m80_codigo_ini.value;            //transferencia inicial
  var iTransfFim   = $('m80_codigo_fim').value; //obj.m80_codigo_fim.value;            //transferencia final
  var dif          = (iTransfFim-iTransfIni);   //diferença entre as duas transferencias   
  var sDataIni     = new String($('perini').value); //data inicial
  var sDataFim     = new String($('perfim').value); //data final
  var lMsg         = false;                               //variavel lMsg iniciada em false
  
  /* VERIFICA SE EXISTE ALGUMA DATA VAZIA E COMPLETA */
  if (sDataIni != "" && sDataFim == "") {
    
    sDataFim = sDataIni;
    $('perfim').value = sDataIni;
    
  } else if(sDataFim != "" && sDataIni == "") {
    sDataIni = sDataFim;
    $('perini').value = sDataFim;
  } 
  
  /* verifica a data */
  if( sDataIni == "" && sDataFim == "" ) {
    lMsg = true;
  }
  
  /* verifica transferencias */
  if( iTransfIni == 0 && iTransfFim == 0 ) {
    lMsg = true;
  } else if ( dif > 50 ) {
    lMsg = true;
  } else {
    lMsg = false;
  }
  
  /* se mensagem for igual a TRUE ele manda o alert */
  if (lMsg) {
  
    if (confirm('O sistema poderá ficar lento devido ao volume de requisições, deseja realmente emitir relatório?')) {
      if (!confirm('Tem certeza de que deseja emitir este relatório, já que o sistema pode ficar lento?')) {
        return false;
      }     
    } else {
    return false;
    }
  }   
  
  query  ='';
  query += "&almox="+sAlmox;
  query += "&tipo_periodo="+iTipoPeriodo;   
  query += "&dataini="+sDataIni;
  query += "&datafim="+sDataFim;   
  query += "&ini="+iTransfIni;
  query += "&fim="+iTransfFim;
  query += "&departamento=<?=db_getsession("DB_coddepto")?>";
  jan = window.open('mat2_transfermater002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="document.form1.m80_codigo_ini.focus();" >
<table width="100%" height='18'  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2" align=center>
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr> 
  <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <form name='form1'>
    
  <table align=center style="margin-top:15px;">
  <tr><td>
    
  <fieldset> 
  <legend><b>Termo de Transferência</b></legend> 
    
    <table align=center>    
      <tr>
  <td nowrap title="<?=@$Tm40_codigo?>">
     <strong>Transferências de  </strong>
  </td>
  <td colspan="3"> 
     <? db_input('m80_codigo',8,$Im80_codigo,true,'text',$db_opcao,"onchange='js_copiacampo();'","m80_codigo_ini")  ?>
    &nbsp;<strong> à </strong>&nbsp;  
     <? db_input('m80_codigo',8,$Im80_codigo,true,'text',$db_opcao,"","m80_codigo_fim")  ?>
  </td>
      </tr>
      
            <tr>
        <td>
          <b>Tipos de Periodo:</b>  
        </td>
        <td colspan=3>
          <?
            $aAtendimento = array ( "1" => "Data da Transferência",
                                    "2" => "Data do Recebimento" );
            db_select("tipo_periodo",$aAtendimento,true,1,"style='width:273px;'  ");
         ?>
        </td>
      </tr>
      
            <tr>
  <td nowrap title="<?=@$Tm40_codigo?>">
     <strong>Período:  </strong>
  </td>
  <td colspan=3> 
     <? db_inputdata('perini','','','',true,'text',1,"");  ?>
      &nbsp;<strong> à </strong>&nbsp; 
     <? db_inputdata('perfim','','','',true,'text',1,"");  ?>
  </td>
      </tr>
      
      
  <tr>
    <td colspan=4 align=center>
      <table>
        <tr><td>    
         <?            
          $aux->cabecalho = "<strong>Almoxarifados</strong>";
          $aux->codigo = "m91_codigo"; //chave de retorno da func
          $aux->descr  = "descrdepto";   //chave de retorno
          $aux->nomeobjeto = 'almoxarifados';
          $aux->funcao_js = 'js_mostra';
          $aux->funcao_js_hide = 'js_mostra1';
          $aux->sql_exec  = "";
          $aux->func_arquivo = "func_db_almox.php";  //func a executar
          $aux->nomeiframe = "db_iframe_db_depart";
          $aux->localjan = "";
          $aux->onclick = "";
          $aux->db_opcao = 2;
          $aux->tipo = 2;
          $aux->top = 0;
          $aux->linhas = 10;
          $aux->vwhidth = 400;
          $aux->funcao_gera_formulario();
         ?>
       </td></tr>
     </table>
    </td>  
  </tr>
       
    </table>
    
   </fieldset>
   
   </td></tr>
   <tr>
       <td colspan='4' align='center'>
         <input name='pesquisar' type='button' value='Gerar relatório' onclick='js_abre();'>      
       </td>
     </tr>
   </table>
        
    </form>
  </td>
 </tr>
</table>
    <? 
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<script>

function js_BuscaDadosArquivoalmoxarifados(chave){
  document.form1.db_lanca.onclick = '';
  if(chave){
    
    js_OpenJanelaIframe('',
                        'db_iframe_db_depart', 
                        'func_db_almox.php?funcao_js=parent.js_mostra|m91_codigo|descrdepto',
                        'Pesquisar Almoxarifados', 
                        true);
  }else{
    js_OpenJanelaIframe('','db_iframe_db_depart', 
                           'func_db_almox.php?pesquisa_chave='+
                           document.form1.m91_codigo.value+'&funcao_js=parent.js_mostra1&dpto=true',
                           'Pesquisar Almoxarifados',
                           false);
  }
}

function js_mostra(chave,chave1){
  document.form1.m91_codigo.value = chave;
  document.form1.descrdepto.value = chave1;
  db_iframe_db_depart.hide();
  find;
  document.form1.db_lanca.onclick = js_insSelectalmoxarifados;
}
function js_mostra1(chave,chave1){
  document.form1.descrdepto.value = chave;
  if(chave1){
    document.form1.m91_codigo.value = '';
    document.form1.m91_codigo.focus();
  }else{
    find;
    document.form1.db_lanca.onclick = js_insSelectalmoxarifados;
  }
  db_iframe_db_depart.hide();
}

function js_copiacampo(){
 if(document.form1.m80_codigo_fim.value== ""){
    document.form1.m80_codigo_fim.value = document.form1.m80_codigo_ini.value;
  }
  document.form1.m80_codigo_fim.focus();
}
$('m91_codigo').stopObserving("change");
$('m91_codigo').observe("change", function() {
                         js_BuscaDadosArquivoalmoxarifados(false)
                         }
                        );
</script>