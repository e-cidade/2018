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
include("classes/db_rharqbanco_classe.php");
include("dbforms/db_classesgenericas.php");
$gform = new cl_formulario_rel_pes;
$clrharqbanco = new cl_rharqbanco;
$clrotulo = new rotulocampo;
$clrharqbanco->rotulo->label();
$clrotulo->label('rh34_codarq');
$clrotulo->label('rh34_descr');
$clrotulo->label('db90_descr');
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
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
  <tr>
    <?
  if(!isset($tipo)){
    $tipo = "g";
  }
  if(!isset($filtro)){
    $filtro = "i";
  }
  $gform->tipores = true;


  $gform->usalota = true;                      // PERMITIR SELEÇÃO DE LOTAÇÕES
  $gform->usaorga = true;                      // PERMITIR SELEÇÃO DE ÓRGÃO
  $gform->usacarg = true;                      // PERMITIR SELEÇÃO DE Cargo
  $gform->mostaln = true;
  
  $gform->masnome = "ordem";

  $gform->lo1nome = "lotaci";                  // NOME DO CAMPO DA LOTAÇÃO INICIAL
  $gform->lo2nome = "lotacf";                  // NOME DO CAMPO DA LOTAÇÃO FINAL
  $gform->lo3nome = "sellot";

  $gform->or1nome = "orgaoi";                  // NOME DO CAMPO DO ÓRGÃO INICIAL
  $gform->or2nome = "orgaof";                  // NOME DO CAMPO DO ÓRGÃO FINAL
  $gform->or3nome = "selorg";                  // NOME DO CAMPO DE SELEÇÃO DE ÓRGÃOS
  $gform->or4nome = "Secretaria";                  // NOME DO CAMPO DE SELEÇÃO DE ÓRGÃOS

  $gform->trenome = "tipo";               // NOME DO CAMPO TIPO DE RESUMO
  $gform->tfinome = "filtro";            // NOME DO CAMPO TIPO DE FILTRO


  $gform->resumopadrao = "g";                  // TIPO DE RESUMO PADRÃO
  $gform->filtropadrao = "i";
  $gform->strngtipores = "glo";              // OPÇÕES PARA MOSTRAR NO TIPO DE RESUMO g - geral,

  $gform->onchpad = true;                      // MUDAR AS OPÇÕES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO

//  $gform->desabam = false;
  $gform->manomes = true;
  $gform->gera_form(db_anofolha(),db_mesfolha());
  ?>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_codban?>">
      <?
      db_ancora(@$Lrh34_codban,"js_pesquisarh34_codban(true);",1);
      ?>
    </td>
    <td colspan="3"> 
      <?
      db_input('rh34_codban',6,$Irh34_codban,true,'text',1," onchange='js_pesquisarh34_codban(false);'")
      ?>
      <?
      db_input('db90_descr',40,$Idb90_descr,true,'text',3,'')
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
  qry  = "?ordem="+document.form1.ordem.value;
  qry += "&ano="+document.form1.anofolha.value;
  qry += "&mes="+document.form1.mesfolha.value;
  qry += "&banco="+document.form1.rh34_codban.value;
  qry += "&tipo="+document.form1.tipo.value;
  if(document.form1.sellot){
    if(document.form1.sellot.length > 0){
      faixalot = js_campo_recebe_valores();
      qry+= "&flt="+faixalot;
    }
  }else if(document.form1.lotaci){
    lotini = document.form1.lotaci.value;
    lotfim = document.form1.lotacf.value;
    qry+= "&lti="+lotini;
    qry+= "&ltf="+lotfim;
  }
  if(document.form1.selorg){
    if(document.form1.selorg.length > 0){
      faixaorg = js_campo_recebe_valores();
      qry+= "&for="+faixaorg;
    }
  }else if(document.form1.orgaoi){
    orgini = document.form1.orgaoi.value;
    orgfim = document.form1.orgaof.value;
    qry+= "&ori="+orgini;
    qry+= "&orf="+orgfim;
  }
  jan = window.open('pes2_alebancos002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_pesquisarh34_codban(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_bancos','func_db_bancos.php?funcao_js=parent.js_mostradb_bancos1|db90_codban|db90_descr','Pesquisa',true);
  }else{
    if(document.form1.rh34_codban.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_db_bancos','func_db_bancos.php?pesquisa_chave='+document.form1.rh34_codban.value+'&funcao_js=parent.js_mostradb_bancos','Pesquisa',false);
    }else{
      document.form1.db90_descr.value = ''; 
    }
  }
}
function js_mostradb_bancos(chave,erro){
  document.form1.db90_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh34_codban.focus(); 
    document.form1.rh34_codban.value = ''; 
  }
}
function js_mostradb_bancos1(chave1,chave2){
  document.form1.rh34_codban.value = chave1;
  document.form1.db90_descr.value = chave2;
  db_iframe_db_bancos.hide();
}
</script>