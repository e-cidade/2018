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
$clrotulo->label("ve05_veiccadcategcnh");
$clrotulo->label("ve30_descr");
$clrotulo->label("ve05_veiccadmotoristasit");
$clrotulo->label("ve33_descr");
$clrotulo->label("ve05_dtvenc");
$clrotulo->label("ve05_dtprimcnh"); 
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite(){
  qry  = 'ordem='+document.form1.ordem.value;
  qry += '&categcnh='+document.form1.ve05_veiccadcategcnh.value;
  qry += '&motoristasit='+document.form1.ve05_veiccadmotoristasit.value;
  qry += '&dtvenc='+document.form1.ve05_dtvenc_ano.value+'-'+document.form1.ve05_dtvenc_mes.value+'-'+document.form1.ve05_dtvenc_dia.value;
  qry += '&dtvenc1='+document.form1.ve05_dtvenc1_ano.value+'-'+document.form1.ve05_dtvenc1_mes.value+'-'+document.form1.ve05_dtvenc1_dia.value;
  qry += '&dtprimcnh='+document.form1.ve05_dtprimcnh_ano.value+'-'+document.form1.ve05_dtprimcnh_mes.value+'-'+document.form1.ve05_dtprimcnh_dia.value;
  qry += '&dtprimcnh1='+document.form1.ve05_dtprimcnh1_ano.value+'-'+document.form1.ve05_dtprimcnh1_mes.value+'-'+document.form1.ve05_dtprimcnh1_dia.value;
  jan = window.open('vei2_veicmotoristas002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_pesquisave05_veiccadcategcnh(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veiccadcategcnh','func_veiccadcategcnh.php?funcao_js=parent.js_mostraveiccadcategcnh1|ve30_codigo|ve30_descr','Pesquisa',true);
  }else{
     if(document.form1.ve05_veiccadcategcnh.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_veiccadcategcnh','func_veiccadcategcnh.php?pesquisa_chave='+document.form1.ve05_veiccadcategcnh.value+'&funcao_js=parent.js_mostraveiccadcategcnh','Pesquisa',false);
     }else{
       document.form1.ve30_descr.value = ''; 
     }
  }
}
function js_mostraveiccadcategcnh(chave,erro){
  document.form1.ve30_descr.value = chave; 
  if(erro==true){ 
    document.form1.ve05_veiccadcategcnh.focus(); 
    document.form1.ve05_veiccadcategcnh.value = ''; 
  }
}
function js_mostraveiccadcategcnh1(chave1,chave2){
  document.form1.ve05_veiccadcategcnh.value = chave1;
  document.form1.ve30_descr.value = chave2;
  db_iframe_veiccadcategcnh.hide();
}
function js_pesquisave05_veiccadmotoristasit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veiccadmotoristasit','func_veiccadmotoristasit.php?funcao_js=parent.js_mostraveiccadmotoristasit1|ve33_codigo|ve33_descr','Pesquisa',true);
  }else{
     if(document.form1.ve05_veiccadmotoristasit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_veiccadmotoristasit','func_veiccadmotoristasit.php?pesquisa_chave='+document.form1.ve05_veiccadmotoristasit.value+'&funcao_js=parent.js_mostraveiccadmotoristasit','Pesquisa',false);
     }else{
       document.form1.ve33_descr.value = ''; 
     }
  }
}
function js_mostraveiccadmotoristasit(chave,erro){
  document.form1.ve33_descr.value = chave; 
  if(erro==true){ 
    document.form1.ve05_veiccadmotoristasit.focus(); 
    document.form1.ve05_veiccadmotoristasit.value = ''; 
  }
}
function js_mostraveiccadmotoristasit1(chave1,chave2){
  document.form1.ve05_veiccadmotoristasit.value = chave1;
  document.form1.ve33_descr.value = chave2;
  db_iframe_veiccadmotoristasit.hide();
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
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
  
        <tr>
    <td nowrap title="<?=@$Tve05_veiccadcategcnh?>">
       <?
       db_ancora(@$Lve05_veiccadcategcnh,"js_pesquisave05_veiccadcategcnh(true);",4);
       ?>
    </td>
    <td> 
<?
db_input('ve05_veiccadcategcnh',10,$Ive05_veiccadcategcnh,true,'text',4," onchange='js_pesquisave05_veiccadcategcnh(false);'")
?>
       <?
db_input('ve30_descr',40,$Ive30_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve05_dtvenc?>">
       <?=@$Lve05_dtvenc?>
    </td>
    <td> 
<?
db_inputdata('ve05_dtvenc',@$ve05_dtvenc_dia,@$ve05_dtvenc_mes,@$ve05_dtvenc_ano,true,'text',4,"");
echo "<b> a </b>";
db_inputdata('ve05_dtvenc1',@$ve05_dtvenc_dia,@$ve05_dtvenc_mes,@$ve05_dtvenc_ano,true,'text',4,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve05_dtprimcnh?>">
       <?=@$Lve05_dtprimcnh?>
    </td>
    <td> 
<?
db_inputdata('ve05_dtprimcnh',@$ve05_dtprimcnh_dia,@$ve05_dtprimcnh_mes,@$ve05_dtprimcnh_ano,true,'text',4,"");
echo "<b> a </b>";
db_inputdata('ve05_dtprimcnh1',@$ve05_dtprimcnh_dia,@$ve05_dtprimcnh_mes,@$ve05_dtprimcnh_ano,true,'text',4,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve05_veiccadmotoristasit?>">
       <?
       db_ancora(@$Lve05_veiccadmotoristasit,"js_pesquisave05_veiccadmotoristasit(true);",4);
       ?>
    </td>
    <td> 
<?
db_input('ve05_veiccadmotoristasit',10,$Ive05_veiccadmotoristasit,true,'text',4," onchange='js_pesquisave05_veiccadmotoristasit(false);'")
?>
       <?
db_input('ve33_descr',40,$Ive33_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
      <tr >
        <td align="left" nowrap title="Ordem Alfabética/Numérica" >
        <strong>Ordem :&nbsp;&nbsp;</strong>
        </td>
        <td>
	  <? 
	  $tipo_ordem = array("b"=>"Numérica","a"=>"Alfabética");
	  db_select("ordem",$tipo_ordem,true,2); ?>
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