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
$clrotulo->label("ve01_veiccadtipo");
$clrotulo->label("ve20_descr");
$clrotulo->label("ve06_veiccadcomb");
$clrotulo->label("ve26_descr");
$clrotulo->label("ve01_dtaquis");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite(){
  qry  = '';
  qry += '&busca='+document.form1.busca.value;
  qry += '&tipo='+document.form1.ve01_veiccadtipo.value;
  qry += '&comb='+document.form1.ve06_veiccadcomb.value;	
  qry += '&dtaquis='+document.form1.ve01_dtaquis_ano.value+'-'+document.form1.ve01_dtaquis_mes.value+'-'+document.form1.ve01_dtaquis_dia.value;
  qry += '&dtaquis1='+document.form1.ve01_dtaquis1_ano.value+'-'+document.form1.ve01_dtaquis1_mes.value+'-'+document.form1.ve01_dtaquis1_dia.value;
  jan = window.open('vei2_veiculos002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_pesquisave01_veiccadtipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veiccadtipo','func_veiccadtipo.php?funcao_js=parent.js_mostraveiccadtipo1|ve20_codigo|ve20_descr','Pesquisa',true);
  }else{
     if(document.form1.ve01_veiccadtipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_veiccadtipo','func_veiccadtipo.php?pesquisa_chave='+document.form1.ve01_veiccadtipo.value+'&funcao_js=parent.js_mostraveiccadtipo','Pesquisa',false);
     }else{
       document.form1.ve20_descr.value = ''; 
     }
  }
}
function js_mostraveiccadtipo(chave,erro){
  document.form1.ve20_descr.value = chave; 
  if(erro==true){ 
    document.form1.ve01_veiccadtipo.focus(); 
    document.form1.ve01_veiccadtipo.value = ''; 
  }
}
function js_mostraveiccadtipo1(chave1,chave2){
  document.form1.ve01_veiccadtipo.value = chave1;
  document.form1.ve20_descr.value = chave2;
  db_iframe_veiccadtipo.hide();
}
function js_pesquisave06_veiccadcomb(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veiccadcomb','func_veiccadcomb.php?funcao_js=parent.js_mostraveiccadcomb1|ve26_codigo|ve26_descr','Pesquisa',true);
  }else{
     if(document.form1.ve06_veiccadcomb.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_veiccadcomb','func_veiculoscomb.php?pesquisa_chave='+document.form1.ve06_veiccadcomb.value+'&funcao_js=parent.js_mostraveiccadcomb','Pesquisa',false);
     }else{
       document.form1.ve26_descr.value = ''; 
     }
  }
}
function js_mostraveiccadcomb(chave,erro){
  document.form1.ve26_descr.value = chave; 
  if(erro==true){ 
    document.form1.ve06_veiccadcomb.focus(); 
    document.form1.ve06_veiccadcomb.value = ''; 
  }
}
function js_mostraveiccadcomb1(chave1,chave2){
  document.form1.ve06_veiccadcomb.value = chave1;
  document.form1.ve26_descr.value = chave2;
  db_iframe_veiccadcomb.hide();
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
    <td nowrap title="<?=@$Tve01_veiccadtipo?>">
       <?
       db_ancora(@$Lve01_veiccadtipo,"js_pesquisave01_veiccadtipo(true);",4);
       ?>
    </td>
    <td> 
<?
db_input('ve01_veiccadtipo',10,$Ive01_veiccadtipo,true,'text',4," onchange='js_pesquisave01_veiccadtipo(false);'")
?>
       <?
db_input('ve20_descr',40,$Ive20_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
    <tr>
    <td nowrap title="<?=@$Tve06_veiccadcomb?>">
       <?
       db_ancora(@$Lve06_veiccadcomb,"js_pesquisave06_veiccadcomb(true);",4);
       ?>
    </td>
    <td> 
<?

db_input('ve06_veiccadcomb',10,$Ive06_veiccadcomb,true,'text',4," onchange='js_pesquisave06_veiccadcomb(false);'")
?>
       <?
db_input('ve26_descr',40,$Ive26_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve01_dtaquis?>">
       <?=@$Lve01_dtaquis?>
    </td>
    <td> 
<?
db_inputdata('ve01_dtaquis',@$ve01_dtaquis_dia,@$ve01_dtaquis_mes,@$ve01_dtaquis_ano,true,'text',4,"");
echo "<b> a </b>";
db_inputdata('ve01_dtaquis1',@$ve01_dtaquis_dia,@$ve01_dtaquis_mes,@$ve01_dtaquis_ano,true,'text',4,"");
?>
    </td>
  </tr>
  <tr >
        <td align="left" nowrap title="Buscar por" >
        <strong>Buscar por :&nbsp;&nbsp;</strong>
        </td>
        <td>
	  <? 
	  $tipo_busca = array("t"=>"Todos","b"=>"Baixados","n"=>"Não Baixados");
	  db_select("busca",$tipo_busca,true,2); ?>
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