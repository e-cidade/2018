<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_liborcamento.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("model/relatorioContabil.model.php");

$oRotulo = new rotulocampo;
$oRotulo->label('DBtxt21');
$oRotulo->label('DBtxt22');

$dtAnousu    = db_getsession("DB_anousu");
?>
<html>
<head>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">

<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

<?
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
?>

<body>
<center>
<form name="form1" method="post"  >
 <fieldset style=" height:200;width: 300;">
 <legend><b>Filtros</b></legend>
  <table align="center" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan=3  class='table_header'>
        <p> Anexo II - Demonstrativo Função/Subfunção </p>
      </td>
    </tr>
   <tr>
    <td align="center" colspan="3">
      <?
      db_selinstit('',400,100);
      ?>
    </td>
  </tr>    
  <tr>
    <td width="15">
    <b>Período:</b>
    </td>
    <td>
      <?php
      $aPeriodo = array("Bimestral"=>"Bimestral","Mensal"=>"Mensal");
       db_select("periodo",$aPeriodo,true,4); 
      ?> 
    </td>
  </tr>
  
  
   <tr id="tr_mensal" style = "display:none">
        <td width="15">
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
  
  
    <tr id="tr_bimestral" >
      <td width="15"> 
        <b>Bimestral:</b>
      </td>
      <td>
        <?php 
        $oDaoPeriodo    = db_utils::getDao("periodo");
        $sSqlPeriodo    = $oDaoPeriodo->sql_query( null,"*","o114_sequencial","o114_qdtporano = 6");
        $rsPeriodo      = $oDaoPeriodo->sql_record($sSqlPeriodo);
        $aResultadoPeriodo  = db_utils::getCollectionByRecord($rsPeriodo);
        $aBimestre = array();
        
        foreach ($aResultadoPeriodo as $oPeriodo) {

          $aBimestre[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
        }
        db_select("bimestre",$aBimestre,true,1);
        ?>
      </td>
    </tr>
  </table>
  </fieldset>    
  <input type="submit" value="Imprimir" onClick="js_emite();">
<center>
</body>
<script>
     $('periodo')[0].selected = true; 
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

     function js_emite() {

       var sOrigem     = document.form1.db_selinstit.value;
       var sDocumento  = "con2_anexodemonstrativofuncaosubfuncao002.php";
       var sDestino    = sDocumento+'?sBimestre='+$F('bimestre')+'&sMes='+$F('mes')+'&sPeriodo='+$F('periodo')+'&sOrigem='+sOrigem;
       jan = window.open(sDestino,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
       jan.moveTo(0,0);
     }

</script>

</html>