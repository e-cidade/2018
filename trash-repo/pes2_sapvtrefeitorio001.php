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
$rotulocampo = new rotulocampo;
$rotulocampo->label("DBtxt23");
$rotulocampo->label("DBtxt25");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_emite(){
  qry = 'base1='+document.form1.base01.value+
	'&base2='+document.form1.base02.value+
	'&valor='+document.form1.valor.value+
	'&ano='+document.form1.DBtxt23.value+
	'&mes='+document.form1.DBtxt25.value;
  jan = window.open('pes2_sapvtrefeitorio002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC rightmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="" onsubmit="return js_verifica();">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
        <td align="right" nowrap title="Digite o Ano / Mes de competência" >
        <strong>Ano / Mês :&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
           $DBtxt23 = db_anofolha();
           db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'')
          ?>
          &nbsp;/&nbsp;
          <?
           $DBtxt25 = db_mesfolha();
           db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'')
          ?>
        </td>
      </tr>
      <tr >
        <td align="right" nowrap title="Valor Base" >
        <strong>Valor Base :&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
           db_input('valor',10,'',true,'text',2,'')
          ?>
        </td>
      </tr>
      </tr>
          <tr>
            <td nowrap align="right" title="" width="40%"><b>
              <?
              db_ancora('Proventos ',"js_pesquisabase01(true)",2);
              ?>
	      </b>
            </td>
            <td nowrap> 
              <?
              $base01="B";
              db_input('base01',4,$base01,true,'text',2,"onchange='js_pesquisabase01(false)'");
              db_input("descr_base01",30,"",true,"text",3,"","descr_base01");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="" width="40%"><b>
              <?
              db_ancora('Descontos ',"js_pesquisabase02(true)",2);
              ?>
	      </b>
            </td>
            <td nowrap> 
              <?
              $base02="B";
              db_input('base02',4,$base02,true,'text',2,"onchange='js_pesquisabase02(false)'");
              db_input("descr_base02",30,"",true,"text",3,"","descr_base02");
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
function js_pesquisabase01(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bases','func_bases.php?funcao_js=parent.js_mostrabase011|r08_codigo|r08_descr','Pesquisa',true);
  }else{
    if(document.form1.base01.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_base01','func_bases.php?pesquisa_chave='+document.form1.base01.value+'&funcao_js=parent.js_mostrabase01','Pesquisa',false);
    }else{
      document.form1.descr_base01.value = ''; 
    }
  }
}
function js_mostrabase01(chave,erro){
  document.form1.descr_base01.value = chave; 
  if(erro==true){ 
    document.form1.base01.focus(); 
    document.form1.base01.value = ''; 
  }
}
function js_mostrabase011(chave1,chave2){
  document.form1.base01.value = chave1;
  document.form1.descr_base01.value = chave2;
  db_iframe_bases.hide();
}



function js_pesquisabase02(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bases','func_bases.php?funcao_js=parent.js_mostrabase021|r08_codigo|r08_descr','Pesquisa',true);
  }else{
    if(document.form1.base02.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_base02','func_bases.php?pesquisa_chave='+document.form1.base02.value+'&funcao_js=parent.js_mostrabase02','Pesquisa',false);
    }else{
      document.form1.descr_base02.value = ''; 
    }
  }
}
function js_mostrabase02(chave,erro){
  document.form1.descr_base02.value = chave; 
  if(erro==true){ 
    document.form1.base02.focus(); 
    document.form1.base02.value = ''; 
  }
}
function js_mostrabase021(chave1,chave2){
  document.form1.base02.value = chave1;
  document.form1.descr_base02.value = chave2;
  db_iframe_bases.hide();
}
function js_pesquisatabdesc(mostra){
     if(mostra==true){
       db_iframe.jan.location.href = 'func_tabdesc.php?funcao_js=parent.js_mostratabdesc1|0|2';
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
       db_iframe.jan.location.href = 'func_tabdesc.php?pesquisa_chave='+document.form1.codsubrec.value+'&funcao_js=parent.js_mostratabdesc';
     }
}
function js_mostratabdesc(chave,erro){
  document.form1.k07_descr.value = chave;
  if(erro==true){
     document.form1.codsubrec.focus();
     document.form1.codsubrec.value = '';
  }
}
function js_mostratabdesc1(chave1,chave2){
     document.form1.codsubrec.value = chave1;
     document.form1.k07_descr.value = chave2;
     db_iframe.hide();
}
</script>


<?
if(isset($ordem)){
  echo "<script>
       js_emite();
       </script>";  
}
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

?>