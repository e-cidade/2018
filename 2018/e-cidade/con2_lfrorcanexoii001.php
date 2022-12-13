<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("model/relatorioContabil.model.php");
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');

db_postmemory($HTTP_POST_VARS);

$anousu    = db_getsession("DB_anousu");
$sLabelMsg = "Anexo II - Demonstrativo Função/Subfunção";
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>

<script>

variavel = 1;

function js_buscaEdicaoLrf(iAnousu,sFontePadrao){
  
  var url       = 'con4_lrfbuscaedicaoRPC.php';
  var parametro = 'ianousu='+iAnousu+'&sfontepadrao='+sFontePadrao ;
  var objAjax   = new Ajax.Request (url, { method:'post',
                                           parameters:parametro, 
                                           onComplete:js_setNomeArquivo}
                                    );  
}

function js_setNomeArquivo(oResposta){
  sNomeArquivoEdicao = oResposta.responseText;
}

js_buscaEdicaoLrf(<?=db_getsession("DB_anousu")?>,'con2_lfrorcanexoii002');

function js_abre(opcao){
 sel_instit = new Number(document.form1.db_selinstit.value);
 if(sel_instit == 0){
    alert('Você não escolheu nenhuma Instituição. Verifique!');
    return false;
 }

<?
  if ($anousu <= 2007) {
?>
 if (document.form1.vernivel.value != '' && document.form1.vernivel.value != document.form1.nivel.value){
    if(confirm('Você já escolheu anteriormente dados do nível '+document.form1.vernivel.value+' , deseja altera-los?')==false) 
      return false
    else
      js_OpenJanelaIframe('','db_iframe_orgao','func_selorcdotacao.php?&nivel='+document.form1.nivel.value+'&db_selinstit='+document.form1.db_selinstit.value,'pesquisa',true);
 }else if(top.corpo.db_iframe_orgao != undefined){
//   alert('entrou');
   
   if(document.form1.nivel.value == document.form1.vernivel.value){
     db_iframe_orgao.show();
   }else{
     js_OpenJanelaIframe('','db_iframe_orgao','func_selorcdotacao.php?&nivel='+document.form1.nivel.value+'&db_selinstit='+document.form1.db_selinstit.value,'pesquisa',true);
   }
 }else{
   js_OpenJanelaIframe('','db_iframe_orgao','func_selorcdotacao.php?&nivel='+document.form1.nivel.value+'&db_selinstit='+document.form1.db_selinstit.value,'pesquisa',true);
 }
<?
  } else {
?>
  document.form1.vernivel.value = "1A";
  document.form1.nivel.value    = "1A";
<?
  }
?>
}

variavel = 1;
function js_emite(opcao,origem){
<?
  if ($anousu <= 2007){
?>
  if (opcao == 3){
     var data1 = new Date(document.form1.DBtxt21_ano.value,document.form1.DBtxt21_mes.value,document.form1.DBtxt21_dia.value,0,0,0);
     var data2 = new Date(document.form1.DBtxt22_ano.value,document.form1.DBtxt22_mes.value,document.form1.DBtxt22_dia.value,0,0,0);
     if(data1.valueOf() > data2.valueOf()){
       alert('Data inicial maior que data final. Verifique!');
       return false;
     }
     perini = document.form1.DBtxt21_ano.value+'-'+document.form1.DBtxt21_mes.value+'-'+document.form1.DBtxt21_dia.value;
     perfin = document.form1.DBtxt22_ano.value+'-'+document.form1.DBtxt22_mes.value+'-'+document.form1.DBtxt22_dia.value;;
  }else if (opcao == 2){
     if(document.form1.mesfin.value == 0){
       mesfinal = 12;
     }else if(document.form1.mesfin.value < 10){
       mesfinal = '0'+document.form1.mesfin.value;
     }else if(document.form1.mesfin.value == 'mes'){
       alert('Mês final do intervalo invalido.Verifique!');
       return false
     }else{
       mesfinal = document.form1.mesfin.value;
     }

     if(document.form1.mesini.value == 0){
       mesinicial = 12;
     }else if(document.form1.mesini.value < 10){
       mesinicial = '0'+document.form1.mesini.value;
     }else{
       mesinicial = document.form1.mesini.value;
     }
    
     perini = <?=$anousu?>+'-'+mesinicial+'-01';
     perfin = <?=$anousu?>+'-'+mesfinal+'-01';
  }else{
     perini = <?=$anousu?>+'-01-01';
     perfin = <?=$anousu?>+'-01-01';
  }
<?
  }
	
  if ($anousu <= 2007){
?>
  valor_nivel = new Number(document.form1.orgaos.value);
  sel_instit  = new Number(document.form1.db_selinstit.value);
  if(valor_nivel == 0){
    alert('Você não escolheu nenhum nível a ser listado. Verifique!');
    return false;
  }else if(sel_instit == 0){
    alert('Você não escolheu nenhuma Instituição. Verifique!');
    return false;
 }else{
//    jan = window.open('mat2_matmater002.php?ordem='+document.form1.ordem.value+'&tipo_ordem='+document.form1.tipo_ordem.value,'',             'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan = window.open('','safo'+variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
    document.form1.target = 'safo' + variavel++;
    document.form1.action = sNomeArquivoEdicao+"?perfin="+perfin+"&perini="+perini+"&opcao="+opcao+"&origem="+origem;
    setTimeout("document.form1.submit()",1000);
    return true;
 }
<?
  } else {
?>
    jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    document.form1.target = 'safo'+variavel++;

   // alert(sNomeArquivoEdicao);
    document.form1.action = sNomeArquivoEdicao+'?bimestre='+$F('bimestre')+'&mes='+$F('mes')+'&opcao='+opcao+'&periodo='+$F('periodo')+'&origem='+origem;
    setTimeout("document.form1.submit()",1000);
    return true;
<?
  }
?>
}
function js_limpa(){
<?
   if ($anousu <= 2007){
?>
  if(document.form1.orgaos.value != ''){
    alert('Os dados selecionados serão excluídos. Você deverá selecionar novamente.');
    document.form1.vernivel.value = '';
    document.form1.orgaos.value = '';
    document.form1.seleciona.click();
  }
<?
   }
?>
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<form name="form1" method="post" action="con2_lfrorcanexoii002.php">
  <table align="center" border="0" cellpadding="0" cellspacing="0">
   <tr>
    <td >&nbsp;</td>
   </tr>
   <tr>
    <td colspan=3  class='table_header'>
     <?=$sLabelMsg?>
    </td>
   </tr>  
   <tr>
    <td>
      <fieldset>
       <legend><b>Filtros</b></legend>
       <table border="0" cellpadding="0" cellspacing="0" width="300">
        <tr>
         <td align="center" colspan="3">
          <?
           db_selinstit('parent.js_limpa',400,100);
          ?>
         </td>
        </tr>
         <?
          if ($anousu <= 2007) {
         ?>
       <tr>
        <td colspan="3" align="center">
      <table>
       <tr>
        <td align="right" ><strong>Filtro :</strong></td>
        <td align="left">
         <?
          //$xy = array('1A'=>'Órgão','2A'=>'Unidade','3A'=>'Função','4A'=>'Subfunção','5A'=>'Programa','6A'=>'Proj/Ativ','7A'=>'Elemento','8A'=>'Recurso');
          $xy = array('1A'=>'Órgão');
          db_select('nivel',$xy,true,2,"");
         ?>
        <td align="left">
         <input  name="seleciona" id="seleciona" type="button" value="Selecionar" onclick="js_abre();">
        </td>
       </tr>
		   <tr>
		    <td align="right" ><strong>Agrupar Por :</strong></td>
		    <td align="left">
         <?
          $z = array("1"=>"Geral","2"=>"Órgão","3"=>"Unidade");
          db_select('tipo_agrupa',$z,true,2,"");
         ?>
        </td>
        <td >&nbsp;</td>
       </tr>
      </table>
      </tr>
      <tr>
       <td colspan="2" >&nbsp;</td>
       <td >&nbsp;</td>
      </tr>
       <?
        db_selorcbalanco(true,false);
       ?>
<?
  } else {
?>
      <tr>
       <td colspan="2">&nbsp;</td>
      </tr>

      <tr>
        <td width="10">
          <b>Período:</b>
        </td>
        <td>
          <?php
          $aPeriodo = array("Mensal"=>"Mensal","Bimestral"=>"Bimestral");
           db_select("periodo",$aPeriodo,true,4); 
          ?> 
        </td>
      </tr>
      
      
      <tr id="tr_mensal">
        <td>
         <b>Mensal:</b>
        </td>
        <td>
          <?php
          
          $oDaoPeriodo    = db_utils::getDao("periodo");
          $sSqlPeriodo    = $oDaoPeriodo->sql_query( null,"*","o114_sequencial","o114_qdtporano = 1 and o114_ordem > 10");
          $rsPeriodo      = $oDaoPeriodo->sql_record($sSqlPeriodo);
          $aResultadoPeriodo  = db_utils::getCollectionByRecord($rsPeriodo);
          $aPeriodo = array();
          
          foreach ($aResultadoPeriodo as $oPeriodo) {
            
            $aPeriodo[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
          }
          
          db_select("mes",$aPeriodo,true,1); 
          ?> 
        </td>
      </tr>
      
      <tr id="tr_bimestral" style = "display:none">
        <td> 
          <b>Bimestral:</b>
        </td>
        
        <td>
        <?
          if ($anousu < 2010) {
            $x = array("1B"=>"Primeiro","2B"=>"Segundo","3B"=>"Terceiro",
                       "4B"=>"Quarto"  ,"5B"=>"Quinto" ,"6B"=>"Sexto");
            db_select("bimestre",$x,true,1);
          } else {
            
             $oRelatorio = new relatorioContabil(96, false);
             $aPeriodos = $oRelatorio->getPeriodos();
             $aListaPeriodos = array();
             foreach ($aPeriodos as $oPeriodo) {
               $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
             }
             db_select("bimestre", $aListaPeriodos, true, 1); 
          }
        ?>
       </td>
      </tr>
<?
  }

  if ($anousu <= 2007) {  
?>
       <input  name="orgaos" id="orgaos" type="hidden" value="" >
<?
  } else {
?>
       <input  name="nivel" id="nivel" type="hidden" value="" >
<?
  }
?>
       <input  name="vernivel" id="vernivel" type="hidden" value="" >    
       </table>
      </fieldset>
      <?
       if ($anousu > 2007) {
      ?>
     <table align="center">
      <tr>
       <td>&nbsp;</td>
      </tr>
      <tr>
       <td>
        <input type="submit" value="Imprimir" onClick="js_emite();">
       </td>
      </tr>     
     </table>
     <?
       }
     ?>
    </td>
   </tr>
  </table>
</form>
<script>
     $('periodo').observe("change", function() {

       //Mensal
       if ($F('periodo') == "Mensal") {

         $('tr_mensal').style.display     = ''
         $('tr_bimestral').style.display  = 'none';
       }

       //Bimestral
       if ($F('periodo') == "Bimestral") {

         $('tr_mensal').style.display    = 'none'
         $('tr_bimestral').style.display = '';
       }
     });
</script>
</body>
</html>