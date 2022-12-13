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
include("libs/db_libpessoal.php");
include("classes/db_rhpagocor_classe.php");
include("classes/db_folha_classe.php");
include("classes/db_rhpesjustica_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$clfolha = new cl_folha;
$clrhpagocor = new cl_rhpagocor;
$clrhpesjustica = new cl_rhpesjustica;
$clrotulo = new rotulocampo;
$clrhpagocor->rotulo->label();
$clrotulo->label("z01_nome");
$db_opcao = 1;
$db_botao = true;
if(!isset($pagar)){
  $pagar = 1;
}
if(!isset($rh58_datai_dia) && !isset($rh58_datai_mes) && !isset($rh58_datai_ano)){
  $rh58_datai_dia = date("d",db_getsession("DB_datausu"));
  $rh58_datai_mes = date("m",db_getsession("DB_datausu"));
  $rh58_datai_ano = date("Y",db_getsession("DB_datausu"));
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="25%" height="18">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <form name="form1" method="post" action="">
      <center>
      <table border="0">
        <tr>
          <td align="center" colspan="2">
            <table>
              <tr>
                <td nowrap title="<?=@$Trh58_data?>" align="right">
                  <?
                  db_ancora(@$Lrh58_data,"",3);
                  ?>
                </td>
                <td nowrap> 
                  <?
                  db_inputdata("rh58_datai", $rh58_datai_dia, $rh58_datai_mes, $rh58_datai_ano, true, 'text', 1);
                  ?>
									<b>&nbsp;a&nbsp;</b>
                  <?
                  db_inputdata("rh58_dataf", $rh58_dataf_dia, $rh58_dataf_mes, $rh58_dataf_ano, true, 'text', 1);
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Trh58_tipoocor?>" align="right">
                  <?
                  db_ancora(@$Lrh58_tipoocor,"js_pesquisarh58_tipoocor(true);",1);
                  ?>
                </td>
                <td nowrap> 
                  <?
                  db_input('rh58_tipoocor',8,$Irh58_tipoocor,true,'text',1," onchange='js_pesquisarh58_tipoocor(false);'")
                  ?>
                  <?
                  db_input('rh59_descr',40,$Irh59_descr,true,'text',3,'');
                  db_input('rh59_tipo',2,$Irh59_tipo,true,'hidden',3,'');
                  ?>
                </td>
              </tr>
              <tr>
                <td align="right"><b>Pagar:</b></td>
                <td>
                  <?
                  $arr_pagar = Array(0=>"Todos",1=>"Funcionários que não estão na justiça");
                  db_select("pagar", $arr_pagar, true, 1, "");
                  ?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td align="center">
            <input name="incluir" type="button" id="db_opcao" value="Gerar relatório" onclick="js_verifica_campos()">
          </td>
        </tr>
      </table>
      </center>
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
function js_verifica_campos(){
  if(document.form1.rh58_datai_dia.value == "" || document.form1.rh58_datai_mes.value == "" || document.form1.rh58_datai_ano.value == ""){
    alert("Informe a data para pagamento!");
    document.form1.rh58_datai_dia.select();
    document.form1.rh58_datai_dia.focus();
  }else if(document.form1.rh58_tipoocor.value == ""){
    alert("Informe o tipo de ocorrência!");
    document.form1.rh58_tipoocor.focus();
  }else{
		qry = "?datai="+document.form1.rh58_datai_ano.value+"-"+document.form1.rh58_datai_mes.value+"-"+document.form1.rh58_datai_dia.value;
		qry+= "&dataf="+document.form1.rh58_dataf_ano.value+"-"+document.form1.rh58_dataf_mes.value+"-"+document.form1.rh58_dataf_dia.value;
		qry+= "&tipo="+document.form1.rh58_tipoocor.value;
		qry+= "&paga="+document.form1.pagar.value;
    jan = window.open('pes2_rhpagatramov002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }
}
function js_pesquisarh58_tipoocor(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpagtipoocor','func_rhpagtipoocor.php?funcao_js=parent.js_mostrarhpagtipoocor1|rh59_codigo|rh59_descr|rh59_tipo','Pesquisa',true);
  }else{
     if(document.form1.rh58_tipoocor.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpagtipoocor','func_rhpagtipoocor.php?pesquisa_chave='+document.form1.rh58_tipoocor.value+'&funcao_js=parent.js_mostrarhpagtipoocor','Pesquisa',false);
     }else{
       document.form1.rh59_descr.value = '';
     }
  }
}
function js_mostrarhpagtipoocor(chave,chave2,erro){
  document.form1.rh59_descr.value = chave;
  if(erro==true){
    document.form1.rh58_tipoocor.focus();
    document.form1.rh58_tipoocor.value = '';
    document.form1.rh59_tipo.value = '';
  }else{
    document.form1.rh59_tipo.value = chave2;
  }
}
function js_mostrarhpagtipoocor1(chave1,chave2,chave3){
  document.form1.rh58_tipoocor.value = chave1;
  document.form1.rh59_descr.value = chave2;
  document.form1.rh59_tipo.value = chave3;
  db_iframe_rhpagtipoocor.hide();
}
</script>