<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");

$clrotulo = new rotulocampo;
$clrotulo->label("e60_codemp");
$clrotulo->label("e60_numemp");
$clrotulo->label("e50_codord");
$clrotulo->label("e53_valor");
$db_opcao = 1;


db_app::load("scripts.js,
              strings.js,
              prototype.js,
              widgets/DBLancador.widget.js,
              widgets/DBToogle.widget.js,
              estilos.css,
            ");


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="790" height='18'  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table valign="top" marginwidth="0" width="600" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <td align="center" valign="top" bgcolor="#CCCCCC">
    <form name='form1'>
    <fieldset>
    <table>
      <tr>
        <td nowrap title="<?=@$Te60_codemp?>">
         <? db_ancora(@$Le60_codemp,"js_pesquisae60_codemp(true);",1); ?>
        </td>
        <td>
         <? db_input('e60_codemp',13,$Ie60_codemp,true,'text',$db_opcao," onchange='js_pesquisae60_codemp(false);'","e60_codemp_ini")  ?>
          <strong> / </strong>
         <? db_input('e60_codemp',13,$Ie60_codemp,true,'text',$db_opcao,"","e60_codemp_fim" )  ?>
        </td>
      </tr>
      <tr>
		<td nowrap title="<?=@$Te60_numemp?>">
		   <? db_ancora(@$Le60_numemp,"js_pesquisae60_numemp(true);",1); ?>
		</td>
		<td>
		   <? db_input('e60_numemp',13,$Ie60_numemp,true,'text',$db_opcao," onchange='js_pesquisae60_numemp(false);'")  ?>
		</td>
      </tr>
      <tr>
	<td nowrap title="<?=@$Te50_codord?>">
	   <b><? db_ancora("Nota de Liquidação:","js_pesquisae50_codord(true);",1); ?></b>
	</td>
	<td>
	   <? db_input('e50_codord',13,$Ie50_codord,true,'text',$db_opcao," onchange='js_pesquisae50_codord(false);'")  ?>
	</td>
      </tr>
     <tr>
     <td align="left" >
       <b> Período:</b>
     </td>
     <td>
      <?  db_inputdata('dtini',@$dia,@$mes,@$ano,true,'text',1,"");
          echo " a ";
          db_inputdata('dtfim',@$dia,@$mes,@$ano,true,'text',1,"");
       ?>
     </td>
     </tr>
     <tr>
     <td align="left" >
       <b> Valor:</b>
     </td>
     <td>
      <?
        db_input('valor_ordem',20,$Ie53_valor,true,'text',2);
       ?>
     </td>
     </tr>

     <tr>
     <td align="left" >
       <b> Histórico:</b>
     </td>
     <td>
      <?
        db_textarea('historico',5,60,0,true,'text',2);
       ?>
     </td>
     </tr>
     
     <tr>
     
       <td colspan="2">
         <div id='ctnLancadorFornecedor'></div>
       <td>
     
     </tr>
     
    </table>
    </fieldset>
    </form>
  </td>
 </tr>
 <tr>
   <td colspan='2' align='center'>
     <input name='pesquisar' type='button' value='Emitir' onclick='js_abre();'>
   </td>
 </tr>
</table>
</center>
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<script>


//ctnLancadorFornecedor
var oLancadorFornecedor = new DBLancador('LancadorFornecedor');
    oLancadorFornecedor.setLabelAncora("Fornecedor:");
    oLancadorFornecedor.setTextoFieldset("Fornecedores");
    oLancadorFornecedor.setTituloJanela("Pesquisar Fornecedores");
    oLancadorFornecedor.setNomeInstancia("oLancadorFornecedor");
    oLancadorFornecedor.setParametrosPesquisa("func_nome.php", ["z01_numcgm", "z01_nome"]);
    oLancadorFornecedor.setGridHeight(150);
    oLancadorFornecedor.show($("ctnLancadorFornecedor"));

function getForncedores() {

  var aFornecedore  = [];
  var aSelecionados = oLancadorFornecedor.getRegistros();
  
  aSelecionados.each( function( oDados, iIndice){
    aFornecedore.push( oDados.sCodigo );
  });
  
  return aFornecedore;
}
    

function js_abre(){
  
  var obj            = document.form1;
  var e50_codord     = obj.e50_codord.value;
  var e60_codemp_ini = obj.e60_codemp_ini.value;
  var e60_codemp_fim = obj.e60_codemp_fim.value;
  var e60_numemp     = obj.e60_numemp.value;
  var historico      = obj.historico.value;
  var valor_ordem    = obj.valor_ordem.value;
  var dtini_dia      = obj.dtini_dia.value;
  var dtini_mes      = obj.dtini_mes.value;
  var dtini_ano      = obj.dtini_ano.value;
  var dtfim_dia      = obj.dtfim_dia.value;
  var dtfim_mes      = obj.dtfim_mes.value;
  var dtfim_ano      = obj.dtfim_ano.value;
  var aFornecedores  = getForncedores();

  var query          = '';

   if(e50_codord != ''){
     query += "&e50_codord="+e50_codord;

   }else{

       if((dtini_dia != '') && (dtini_mes != '') && (dtini_ano != '')){
         query +="&dtini="+dtini_ano+"X"+dtini_mes+"X"+dtini_dia;
       }
       if((dtfim_dia != '') && (dtfim_mes != '') && (dtfim_ano != '')){
         query +="&dtfim="+dtfim_ano+"X"+dtfim_mes+"X"+dtfim_dia;
       }
   }

   query += "&e60_codemp_ini="+obj.e60_codemp_ini.value;
   if(e60_codemp_ini != '') {

     if(e60_codemp_fim != '') {
       if(e60_codemp_fim < e60_codemp_ini) {
         alert("Empenho inicial maior que o empenho final. Verifique!");
         return false;
       }
     }
   }
   query += "&e60_codemp_fim="+obj.e60_codemp_fim.value;

   if(e60_numemp != '') {
       query += "&e60_numemp="+e60_numemp;
   }

   if(query == ''){
     alert("Selecione alguma ordem de pagamento ou indique o período!");
   }else{
     var sUrl = 'emp2_emitenotaliq002.php?aFornecedor=' + aFornecedores + '&historico=' + historico + '&valor_ordem=' + valor_ordem + query;
     jan = window.open(sUrl,
                       '',
                       'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
     jan.moveTo(0,0);
   }
}



    
    
function js_pesquisae60_codemp(mostra){
  
 var obj            = document.form1;
 var e60_codemp_ini = obj.e60_codemp_ini.value;
 var sUrl1          = 'func_empempenho.php?funcao_js=parent.js_mostracodemp1|e60_codemp';
 var sUrl2          = 'func_empempenho.php?pesquisa_chave='+e60_codemp_ini+'&funcao_js=parent.js_mostracodemp';

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho',sUrl1,'Pesquisa',true);
  }else{
     if(e60_codemp_ini != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_empempenho',sUrl2,'Pesquisa',false);
     }else{
       obj.e60_codemp_ini.value = '';
     }
  }
}
function js_mostracodemp(chave,erro){
 var obj = document.form1;

  if(erro==true){
    obj.e50_codemp.focus();
    obj.e50_codemp.value = '';
  }
}
function js_mostracodemp1(chave1,x){
 var obj = document.form1;
  obj.e60_codemp_ini.value = chave1;
  db_iframe_empempenho.hide();
}

function js_pesquisae60_numemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostranumemp1|e60_numemp','Pesquisa',true);
  }else{
     if(document.form1.e60_numemp.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostranumemp','Pesquisa',false);
     }else{
       document.form1.e60_numemp.value = '';
     }
  }
}
function js_mostranumemp(chave,erro){
  if(erro==true){
    document.form1.e50_codemp.focus();
    document.form1.e50_codemp.value = '';
  }
}
function js_mostranumemp1(chave1,x){
  document.form1.e60_numemp.value = chave1;
  db_iframe_empempenho.hide();
}
function js_pesquisae50_codord(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostracodordem1|e50_codord','Pesquisa',true);
  }else{
     if(document.form1.e50_codord.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?pesquisa_chave='+document.form1.e50_codord.value+'&funcao_js=parent.js_mostracodordem','Pesquisa',false);
     }else{
       document.form1.e50_codord.value = '';
     }
  }
}
function js_mostracodordem(chave,erro){
  if(erro==true){
    document.form1.e50_codord.focus();
    document.form1.e50_codord.value = '';
  }
}
function js_mostracodordem1(chave1,x){
  document.form1.e50_codord.value = chave1;
  db_iframe_pagordem.hide();
}
</script>