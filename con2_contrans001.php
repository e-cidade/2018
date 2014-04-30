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

include("classes/db_contrans_classe.php");


$clrotulo = new rotulocampo;
$clrotulo->label('c45_coddoc');
$clrotulo->label('c53_descr');

$clcontrans = new cl_contrans;

$db_opcao = 1;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_relatorio1() {
  obj =  document.form1;
  query = '';
  if(obj.ano.value != '0'){
    query += "ano="+obj.ano.value+"&";
  }  
  if(obj.c45_coddoc.value!=''){
    query += "coddoc="+obj.c45_coddoc.value;
  }  
  jan = window.open('con2_contrans002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.elements[1].focus()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<center>
        <form name="form1" method="post" action="">
          <table border="0" cellspacing="0" cellpadding="0">

      <tr>
      <br>
        <td nowrap title="<?=@$Td01_codedi?>">
	<b>Exercício</b>
        </td>	
	<td>
<?
 $ano  = db_getsession("DB_anousu");
$result =  $clcontrans->sql_record($clcontrans->sql_query_file(null,'distinct c45_anousu'));
$numrows = $clcontrans->numrows;
  $arr['0']="...";
  for($i=0; $i<$numrows; $i++){
    db_fieldsmemory($result,$i);
    $arr[$c45_anousu] = $c45_anousu;
  }
 db_select("ano",$arr,true,1);
?>
        </td>
      </tr>
  <tr>
    <td nowrap title="<?=@$Tc45_coddoc?>">
       <?
       db_ancora(@$Lc45_coddoc,"js_pesquisac45_coddoc(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c45_coddoc',4,$Ic45_coddoc,true,'text',$db_opcao," onchange='js_pesquisac45_coddoc(false);'")
?>
       <?
db_input('c53_descr',35,$Ic53_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
            <tr>
              <td colspan="2" align="center"  height="25" nowrap><input name="boletim" type="button" id="boletim" onClick="js_relatorio1()" value="Gerar relatório">
	      </td>
              <td>
            </tr>
          </table>
        </form>
      </center>
	</td>
  </tr>
</table>
    <? 
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<script>
function js_pesquisac45_coddoc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_conhistdoc','func_conhistdoc.php?funcao_js=parent.js_mostraconhistdoc1|c53_coddoc|c53_descr','Pesquisa',true,'20','1','775','410');
  }else{
     if(document.form1.c45_coddoc.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_conhistdoc','func_conhistdoc.php?pesquisa_chave='+document.form1.c45_coddoc.value+'&funcao_js=parent.js_mostraconhistdoc','Pesquisa',false,'20','1','775','390');
     }else{
       document.form1.c53_descr.value = ''; 
     }
  }
}
function js_mostraconhistdoc(chave,erro){
  document.form1.c53_descr.value = chave; 
  if(erro==true){ 
    document.form1.c45_coddoc.focus(); 
    document.form1.c45_coddoc.value = ''; 
  }
}
function js_mostraconhistdoc1(chave1,chave2){
  document.form1.c45_coddoc.value = chave1;
  document.form1.c53_descr.value = chave2;
  db_iframe_conhistdoc.hide();
}

</script>