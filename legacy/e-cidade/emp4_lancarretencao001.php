<?php
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
require("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
require("classes/empenho.php");
include("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("e50_codord");
$db_opcao = 1;
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <center>
    <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr> 
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
   <form name='form1' action=''>
   <table>
        <tr>
          <td>
        <fieldset>
          <legend><b>Nota de Liquidação</b></legend>
          <table>
            <tr>
              <td nowrap title="<?=@$Te50_codord?>" align='right'>
                <? db_ancora("<b>Nota de Liquidação:</b>","js_pesquisae50_codord(true);",$db_opcao);  ?>
             </td>
             <td> 
               <?
               db_input('e50_codord',8,$Ie50_codord,true,'text',$db_opcao," onchange='js_pesquisae50_codord(false);'");
               db_input('e50_numemp',8,$Ie50_codord,true,'hidden',3);
               db_input('e69_codnota',8,$Ie50_codord,true,'hidden',3);
               ?>
             </td>
            </tr>
          </table>
        </fieldset>  
        </tr> 
        <tr>
          <td colspan='2' align='center'>
          <input name="entrar_codord" type="button" id="pesquisar" value="Entrar" onclick='js_lancarRetencao()'>
        </td>
      </tr>
      </table>
      </form>
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
function js_entra(){
  if(document.form1.e50_codord.value != ""){
      obj=document.createElement('input');
      obj.setAttribute('name','pag_ord');
      obj.setAttribute('type','hidden');
      obj.setAttribute('value','true');
      document.form1.appendChild(obj);
      document.form1.submit();
  }else{
    
    alert("Selecione uma nota de liquidação!");
    return false;
    
  }
}
function js_pesquisae50_codord(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_pagordem',
                        'func_notaliquidacao.php?funcao_js=parent.js_mostrapagordem1|e50_codord|e60_numemp|e69_codnota',
                        'Pesquisa',
                        true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_notaliquidacao.php?pesquisa_chave='+document.form1.e50_codord.value+'&funcao_js=parent.js_mostrapagordem','Pesquisa', false);
  }
}
function js_mostrapagordem(chave,erro, iCodNota){

  if(erro==true) { 
 
    document.form1.e50_codord.focus(); 
    document.form1.e50_codord.value = '';
     
  } else {
    
    document.form1.e50_numemp.value  = chave;
    document.form1.e69_codnota.value = iCodNota;
    
  }
}
function js_mostrapagordem1(chave1, chave2, chave3){
  
  document.form1.e50_codord.value = chave1;
  document.form1.e50_numemp.value = chave2;
  document.form1.e69_codnota.value = chave3;
  db_iframe_pagordem.hide();
}

function js_lancarRetencao(){
  
   if ($F('e50_codord') == '') {
    
     alert('Informe o número da nota!');
     return false;
     
   }
   var iNumEmp  = $F('e50_numemp');
   var iCodOrd  = $F('e50_codord');
   var iCodNota = $F('e69_codnota');
   var lSession = "true";
   js_OpenJanelaIframe('top.corpo', 'db_iframe_retencao',
                       'emp4_lancaretencoes.php?iNumNota='+iCodNota+'&iNumEmp='+iNumEmp+'&iCodOrd='+iCodOrd+"&lSession=false",
                       'Lancar Retenções', true);
     
} 
</script>