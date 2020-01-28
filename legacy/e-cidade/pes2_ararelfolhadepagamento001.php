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
include("classes/db_rhlocaltrab_classe.php");
$clrhlocaltrab = new cl_rhlocaltrab;
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
$clrotulo->label('r44_selec');
$clrotulo->label('r44_descr');
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
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
<table align="center" border=0>
<form name="form1" method="post" action="">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr >
    <td align="Right" nowrap title="Digite o Ano / Mes de competência" >
      <strong>Ano / Mês :</strong>
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
  <tr> 
    <td align="right" nowrap title="Seleção:" >
      <?
    	 db_ancora("<b>Seleção:</b>","js_pesquisasel(true)",1);
      ?>
    </td>
    <td>
        <?
        db_input('r44_selec',4,$Ir44_selec,true,'text',2,'onchange="js_pesquisasel(false)"');
        db_input('r44_descr',40,$Ir44_selec,true,'text',3,'');
        ?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap >
      <strong>Banco :</strong>
    </td>
    <td align="left">
      <?
      $xxx = array("b"=>"Banco do Brasil","c"=>"CEF", "t"=>"Todos");
      db_select('xbanco',$xxx,true,1);
      ?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap >
      <strong>Conta :</strong>
    </td>
    <td align="left">
      <?
      $xcc = array("cc"=>"Com Conta","sc"=>"Sem Conta", "t"=>"Todos");
      db_select('xconta',$xcc,true,1);
      ?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap >
      <strong>Arquivo :</strong>
    </td>
    <td align="left">
      <?
      $xarq = array("s"=>"Salário",
                    "c"=>"Complementar", 
                    "d"=>"13o. Salário"
                   );
      db_select('arquivo',$xarq,true,1);
      ?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap >
      <strong>Dentista :</strong>
    </td>
    <td align="left">
      <?
      $xdent = array("T"=>"Todos",
                     "D"=>"Dentista", 
                     "O"=>"Outros" 
                    );
      db_select('dentista',$xdent,true,1);
      ?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap >
      <strong>Tipo :</strong>
    </td>
    <td align="left">
      <?
      $xpens = array("F"=>"Funcionarios",
                     "P"=>"Pensao Alimenticia" 
                    );
      db_select('pensao',$xpens,true,1);
      ?>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2">
      <?
      $result_local = $clrhlocaltrab->sql_record($clrhlocaltrab->sql_query_file(null,db_getsession('DB_instit'), "rh55_codigo, rh55_codigo||'-'||rh55_descr as rh55_descr", "rh55_descr" ));
      db_multiploselect("rh55_codigo", "rh55_descr", "nselecionados", "sselecionados", $result_local, array(), 9, 250);
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center"> 
      <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();">
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
function js_emite(){
  selecionados = "";
  virgula_ssel = "";
  for(var i=0; i<document.form1.sselecionados.length; i++){
    selecionados+= virgula_ssel + document.form1.sselecionados.options[i].value;
    virgula_ssel = ",";
  }
  qry  = "?xbanco="+document.form1.xbanco.value;
  qry += "&ano="+document.form1.DBtxt23.value;
  qry += "&mes="+document.form1.DBtxt25.value;
  qry += "&tipoarq="+document.form1.arquivo.value;
  qry += "&xconta="+document.form1.xconta.value;
  qry += "&dentista="+document.form1.dentista.value;
  qry += "&pensao="+document.form1.pensao.value;
  qry += "&sel="+document.form1.r44_selec.value;
  qry += "&local="+ selecionados;
  ////jan = window.open('pes2_ararelcontabilidade1002.php' + qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan = window.open('pes2_ararelfolhadepagamento002.php' + qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_pesquisasel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_selecao.php?funcao_js=parent.js_mostrasel1|r44_selec|r44_descr','Pesquisa',true);
  }else{
     if(document.form1.r44_selec.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_selecao.php?pesquisa_chave='+document.form1.r44_selec.value+'&funcao_js=parent.js_mostrasel','Pesquisa',false);
     }else{
       document.form1.r44_descr.value = '';
     }
  }
}
function js_mostrasel(chave,erro){
  document.form1.r44_descr.value = chave; 
  if(erro==true){ 
    document.form1.r44_selec.focus(); 
    document.form1.r44_selec.value = ''; 
  }
}
function js_mostrasel1(chave1,chave2){
  document.form1.r44_selec.value = chave1;
  document.form1.r44_descr.value   = chave2;
  db_iframe_selecao.hide();
}
</script>