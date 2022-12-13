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
db_postmemory($HTTP_POST_VARS);


$clrotulo = new rotulocampo;
$clrotulo->label("l20_codigo");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite(){
    var query = "";

    query += "l20_codigo="+document.form1.l20_codigo.value;
    query += "&modelo="+document.form1.modelo.value;
    query += "&imp_vlrun="+document.form1.imp_vlrun.value;
    query += "&imp_vlrtotal="+document.form1.imp_vlrtotal.value;
    query += "&imp_descla="+document.form1.imp_descla.value;
    query += "&imp_troca="+document.form1.imp_troca.value;
    query += "&imp_lote="+document.form1.imp_lote.value;
    document.form1.l20_codigo.value='';
    jan = window.open('lic2_licmap002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);

}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
  <tr> 
    <td  align="right" nowrap title="<?=$Tl20_codigo?>">
    <b>
    <?db_ancora('Licitação',"js_pesquisa_liclicita(true);",1);?>&nbsp;:
    </b>&nbsp;&nbsp; 
    </td>
    
    <td align="left" nowrap>
      <? db_input("l20_codigo",6,$Il20_codigo,true,"text",3,"onchange='js_pesquisa_liclicita(false);'");
         ?></td>
  </tr>
  <tr>
        <td align="right" nowrap title="Modelo" >
        <strong>Modelo :&nbsp;&nbsp;</strong>
        </td>
        <td>
	  <? 
	  $tipo_ordem = array("1"=>"Modelo 1","2"=>"Modelo 2");
	  db_select("modelo",$tipo_ordem,true,2); ?>
        </td>
      </tr>
  <tr>
        <td align="right" nowrap title="Imprimir valor unitário" >
        <strong>Imprimir valor unitário :&nbsp;&nbsp;</strong>
        </td>
        <td>
	  <? 
	  $matriz_vlrun = array("S"=>"SIM","N"=>"NÃO");
	  db_select("imp_vlrun",$matriz_vlrun,true,2); ?>
        </td>
      </tr>
  <tr>
        <td align="right" nowrap title="Imprimir valor total" >
        <strong>Imprimir valor total :&nbsp;&nbsp;</strong>
        </td>
        <td>
	  <? 
	  $matriz_vlrtotal = array("S"=>"SIM","N"=>"NÃO");
	  db_select("imp_vlrtotal",$matriz_vlrtotal,true,2); ?>
        </td>
      </tr>
      <tr>
        <td align="right" nowrap title="Imprimir justificativa de desclassificacao">
        <b>Imprimir justificativa de desclassificacao:&nbsp;&nbsp;</b></td>
        <td>
        <?
          $matriz_descla = array("S"=>"SIM","N"=>"NÃO");
          db_select("imp_descla",$matriz_descla,true,2);
        ?>
        </td>
      </tr>
      <tr>
        <td align="right" nowrap title="Imprimir justificativa de troca de fornecedor">
        <b>Imprimir justificativa de troca de fornecedor:&nbsp;&nbsp;</b></td>
        <td>
        <?
          $matriz_troca = array("S"=>"SIM","N"=>"NÃO");
          db_select("imp_troca",$matriz_troca,true,2);
        ?>
        </td>
      </tr>
      <tr>
        <td align="right" nowrap title="Imprimir justificativa de lote/itens anulados">
        <b>Imprimir justificativa de lote/itens anulados:&nbsp;&nbsp;</b></td>
        <td>
        <?
          $matriz_lote = array("S"=>"SIM","N"=>"NÃO");
          db_select("imp_lote",$matriz_lote,true,2);
        ?>
        </td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
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

function js_pesquisa_liclicita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_liclicita','func_liclicita.php?funcao_js=parent.js_mostraliclicita1|l20_codigo','Pesquisa',true);
  }else{
     if(document.form1.l20_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_liclicita','func_liclicita.php?pesquisa_chave='+document.form1.l20_codigo.value+'&funcao_js=parent.js_mostraliclicita','Pesquisa',false);
     }else{
       document.form1.l20_codigo.value = ''; 
     }
  }
}
function js_mostraliclicita(chave,erro){
  document.form1.l20_codigo.value = chave; 
  if(erro==true){ 
    document.form1.l20_codigo.value = ''; 
    document.form1.l20_codigo.focus(); 
  }
}
function js_mostraliclicita1(chave1){
   document.form1.l20_codigo.value = chave1;  
   db_iframe_liclicita.hide();
}
</script>