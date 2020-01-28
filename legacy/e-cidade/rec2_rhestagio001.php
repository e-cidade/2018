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
include("classes/db_gerfcom_classe.php");
$clgerfcom = new cl_gerfcom;
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
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
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
<form name="form1">
<center>
<table>
<table>
<tr>
 <td>
<fieldset><legend><b>Relatorio de Estágios </b></legend>
<table>
<tr>
  <td align="right">
    <b>Período:</b>
  </td>
  <td nowrap>
  <?
    db_inputdata('h64_dataini',null,null,null,true,'text',1,"");
    echo " <b>A</b> ";
    db_inputdata('h64_datafim',null,null,null,true,'text',1,"");
  ?>
  </td>
 </tr>
 <tr>
   <td align="right">
     <b>Avaliações:</b>
   </td>
   <td>
   <?
    $opcoes = array(
                    "t" => "Todas",
                    "n" => "Não Aplicadas",
                    "a" => "Aplicadas"
                   );

   db_select('avaliacao',$opcoes,true,1,"");
   ?>
   </td>
   </tr>
   <tr>
    <td align="right">
     <b>Mostrar:</b>
   </td>
   <td>
   <?
    $opcoes = array(
                    "questionario" => "Lista questionário",
                    "avaliacoes"   => "Lista Funcionários",
                   );
   if (!isset($mostra)){
      $mostra = "avaliacoes";
   }
   db_select('mostra',$opcoes,true,1,"");
   ?>
   </td>
  <?
  if(!isset($tipo)){
    $tipo = "l";
  }
  if(!isset($filtro)){
    $filtro = "i";
  }
  if(!isset($anofolha) || (isset($anofolha) && trim($anofolha) == "")){
    $anofolha = db_anofolha();
  }
  if(!isset($mesfolha) || (isset($mesfolha) && trim($mesfolha) == "")){
    $mesfolha = db_mesfolha();
  }
  include("dbforms/db_classesgenericas.php");
  $geraform = new cl_formulario_rel_pes;

  $geraform->usaregi = true;                      // PERMITIR SELEÇÃO DE MATRÍCULAS
  $geraform->usalota = true;                      // PERMITIR SELEÇÃO DE LOTAÇÕES
  $geraform->usaorga = true;                      // PERMITIR SELEÇÃO DE ÓRGÃO
  $geraform->usaloca = true;                      // PERMITIR SELEÇÃO DE LOCAL DE TRABALHO
  $geraform->usarecu = false;                      // PERMITIR SELEÇÃO DE RECURSO

  $geraform->re1nome = "regisi";                  // NOME DO CAMPO DA MATRÍCULA INICIAL
  $geraform->re2nome = "regisf";                  // NOME DO CAMPO DA MATRÍCULA FINAL
  $geraform->re3nome = "selreg";                  // NOME DO CAMPO DE SELEÇÃO DE MATRÍCULAS

  $geraform->lo1nome = "lotai";                  // NOME DO CAMPO DA LOTAÇÃO INICIAL
  $geraform->lo2nome = "lotaf";                  // NOME DO CAMPO DA LOTAÇÃO FINAL
  $geraform->lo3nome = "sellot";                  // NOME DO CAMPO DE SELEÇÃO DE LOTAÇÕES

  $geraform->or1nome = "orgaoi";                  // NOME DO CAMPO DO ÓRGÃO INICIAL
  $geraform->or2nome = "orgaof";                  // NOME DO CAMPO DO ÓRGÃO FINAL
  $geraform->or3nome = "selorg";                  // NOME DO CAMPO DE SELEÇÃO DE ÓRGÃOS 

  $geraform->rc1nome = "recuri";                  // NOME DO CAMPO DO RECURSO INICIAL
  $geraform->rc2nome = "recurf";                  // NOME DO CAMPO DO RECURSO FINAL
  $geraform->rc3nome = "selrec";                  // NOME DO CAMPO DE SELEÇÃO DE RECURSOS 

  $geraform->tr1nome = "locali";                  // NOME DO CAMPO DO LOCAL INICIAL
  $geraform->tr2nome = "localf";                  // NOME DO CAMPO DO LOCAL FINAL
  $geraform->tr3nome = "selloc";                  // NOME DO CAMPO DE SELEÇÃO DE LOCAIS

  $geraform->trenome = "tipo";                    // NOME DO CAMPO TIPO DE RESUMO
  $geraform->tfinome = "filtro";                  // NOME DO CAMPO TIPO DE FILTRO

  $geraform->masnome   = "ordem";                 // NOME DO CAMPO ORDEM 

  $geraform->resumopadrao = "g";                  // TIPO DE RESUMO PADRAO
//  $geraform->valortipores = "g";                  // TIPO DE RESUMO PADRAO
  $geraform->filtropadrao = "s";                  // NOME DO DAS LOTAÇÕES SELECIONADAS

  $geraform->strngtipores = "glot";              // OPÇÕES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
                                                  //                                       l - lotação,
                                                  //                                       o - órgão,
                                                  //                                       t - local de trabalho
                                                  //                                       s - recurso          

  $geraform->tipofol = false;                      // MOSTRAR DO CAMPO PARA TIPO DE FOLHA

  $geraform->arr_tipofol = array(
                                 "r14"=>"Salário",
                                 "r48"=>"Complementar",
                                 "r20"=>"Rescisão",
                                 "r35"=>"13o. Salário",
                                 "r22"=>"Adiantamento"
                                );
  $geraform->campo_auxilio_regi = "faixa_regis";  // NOME DO DAS MATRÍCULAS SELECIONADAS
  $geraform->campo_auxilio_lota = "faixa_lotac";  // NOME DO DAS LOTAÇÕES SELECIONADAS
  $geraform->campo_auxilio_orga = "faixa_orgao";  // NOME DO DOS ÓRGÃOS SELECIONADOS
  $geraform->campo_auxilio_loca = "faixa_local";  // NOME DO DOS LOCAIS SELECIONADOS

  $geraform->mostord   = false;                    // CAMPO PARA ESCOLHA DE ORDEM  
  $geraform->mostnal   = false;                    // TIPO DE ORDEM ALF./NUM      
  $geraform->selecao   = false;                    // CAMPO PARA ESCOLHA DA SELEÇÃO
  $geraform->selregime = false;                    // CAMPO PARA ESCOLHA DO REGIME
  $geraform->atinpen   = false;                    // CAMPO PARA ESCOLHA DO REGIME

  $geraform->onchpad   = true;                    // MUDAR AS OPÇÕES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
  $geraform->gera_form($anofolha,$mesfolha);
  ?>
</table> 
</fieldset>
</td>
</tr>
  <tr>
    <td colspan="2" align = "center"> 
      <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
    </td>
  </tr>
</table>
</center>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_emite(){
  qry  = "?tipo="+document.form1.tipo.value;
  qry += "&anofolha="+$F('anofolha');
  qry += "&mesfolha="+$F('mesfolha');
  qry += "&avaliacao="+$F('avaliacao');
  qry += "&dataInicial="+$F('h64_dataini')+'&dataFinal='+$F('h64_datafim');
  if(document.form1.complementar){
    qry+= "&semest="+document.form1.complementar.value;
  }

  if(document.form1.selreg){
    if(document.form1.selreg.length > 0){
      faixareg = js_campo_recebe_valores();
      qry+= "&fre="+faixareg;
    }
  }else if(document.form1.regisi){
    regini = document.form1.regisi.value;
    regfim = document.form1.regisf.value;
    qry+= "&rei="+regini;
    qry+= "&ref="+regfim;
  }

  if(document.form1.sellot){
    if(document.form1.sellot.length > 0){
      faixalot = js_campo_recebe_valores();
      qry+= "&flt="+faixalot;
    }
  }else if(document.form1.lotai){
    lotini = document.form1.lotai.value;
    lotfim = document.form1.lotaf.value;
    qry+= "&lti="+lotini;
    qry+= "&ltf="+lotfim;
  }

  if(document.form1.selloc){
    if(document.form1.selloc.length > 0){
      faixaloc = js_campo_recebe_valores();
      qry+= "&flc="+faixaloc;
    }
  }else if(document.form1.locali){
    locini = document.form1.locali.value;
    locfim = document.form1.localf.value;
    qry+= "&lci="+locini;
    qry+= "&lcf="+locfim;
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
  if(document.form1.selrec){
    if(document.form1.selrec.length > 0){
      faixarec = js_campo_recebe_valores();
      qry+= "&frc="+faixarec;
    }
  }else if(document.form1.recuri){
    recini = document.form1.recuri.value;
    recfim = document.form1.recurf.value;
    qry+= "&rci="+recini;
    qry+= "&rcf="+recfim;
  }
  switch ($F('mostra')){

     case "avaliacoes":
      url = "rec2_rhestagio002.php";
      break;
    case "questionario":
      url = "rec2_rhestagio003.php";
      break;
  }
  jan = window.open(url+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);

}
$('filtro').style.width    = '150px';
$('mostra').style.width    = '150px';
if ($('tipo')){
  $('tipo').style.width      = '150px';
}
$('avaliacao').style.width = '150px';
</script>