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
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;

// Declaracao do Historico Padrao

$historico = 'Lançamento Acrescimos Ref. Numpre #$k00_numpre# Parcela #$k00_numpar#';
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/encode_decode64.js"></script>

<script>
function js_processa(tipo) {
  var historico64 = "";

  obj = document.form1;

  historico64 = js_base64_encode(obj.historico.value);

  if (obj.codret.value == '') {
    alert('Informe um arquivo para processar!');
    return false;
  } else {
    js_OpenJanelaIframe('','db_iframe_relatorio','cai2_procdisarq002.php?&parcela='+obj.parcela.value+'&codret='+obj.codret.value+'&botao='+tipo+'&historico='+historico64+'&procedencia='+obj.procedencia.value,'Pesquisa',true);
  }
}

function js_processarelatorio(){

  obj = document.form1;
  
  if(obj.codret.value == ''){
     alert('Informe o arquivo.');
     return false;
  }else {
    jan = window.open('cai2_procdisarq003.php?&codret='+obj.codret.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
  return true;
}  
  
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center" border="0">
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>

      <tr>
        <td align='left' ><b> Procedência Diversos:</b></td>
        <td>
          <?
            if(!isset($parcela)) {
              $parcela = date("m", db_getsession("DB_datausu"));
            }
            $sSqlProced = "select dv09_procdiver, dv09_descr from procdiver where dv09_instit = ".db_getsession("DB_instit")." order by 1";
            $resultProced = db_query($sSqlProced);
            db_selectrecord('procedencia', $resultProced, true, 1, "", "", "", "", "");
          ?>
        </td>		
      </tr>
     
      <tr>
        <td nowrap title="<?=@$Tx01_numcgm?>"><b>
           <?
           db_ancora("Arquivo retorno:","js_pesquisacodret(true);",$db_opcao);
           ?>
           </b>
        </td>
        <td> 
           <?
             db_input('codret',10,"",true,'text',$db_opcao," onchange='js_pesquisacodret(false);'")
           ?>
           <?
             db_input('arqret',40,"",true,'text',3,'')
           ?>
        </td>
      </tr>
      
      
      <tr>
        <td align='left' ><b> Parcela/Vencimento:</b></td>
        <td>
          <? 
            $sSql = "select x33_parcela, to_char(x33_dtvenc, 'dd/mm/yyyy') as datavenc from aguaconfvenc where x33_exerc = ".db_getsession("DB_anousu")." order by x33_dtvenc";
            $result = db_query($sSql);
            db_selectrecord('parcela', $result, true, 1, "", "", "", "", "");
          ?>
        </td>		
      </tr>

      <tr>
        <td align='left'><b>Histórico</b></td>
        <td>
          <?
            db_textarea('historico',5, 60,'',true,'text',1,"")
          ?>
        </td>
      </tr>
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="processar" id="processar" type="button" value="Processar" onclick="js_processa('processa');" >
          <input  name="desprocessar" id="desprocessar" type="button" value="Desprocessar" onclick="js_processa('desprocessa');" >
          <input  name="relatorio" id="relatorio" type="button" value="Relatório" onclick="js_processarelatorio();" >
        </td>
      </tr>
    </form>
  </table>
  <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisacodret(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_disarq','func_disarq_alt.php?funcao_js=parent.js_mostracodret1|codret|arqret','Pesquisa',true);
  }else{
     if(document.form1.codret.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_disarq','func_disarq_alt.php?pesquisa_chave='+document.form1.codret.value+'&funcao_js=parent.js_mostracodret','Pesquisa',false);
     }else{
       document.form1.arqret.value = ''; 
     }
  }
}
function js_mostracodret(chave,erro){
  document.form1.arqret.value = chave; 
  if(erro==true){ 
    document.form1.codret.focus(); 
    document.form1.codret.value = ''; 
  }
}
function js_mostracodret1(chave1,chave2){
  document.form1.codret.value = chave1;
  document.form1.arqret.value = chave2;
  db_iframe_disarq.hide();
}

</script>