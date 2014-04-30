<?php
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_liclicita_classe.php");
require_once("dbforms/verticalTab.widget.php");

$oDaoLicitacao = new cl_liclicita;
$oDaoRotulo    = new rotulocampo;
$oDaoLicitacao->rotulo->label();
$oDaoRotulo->label("nome");
$oDaoRotulo->label("l03_descr");

$oGet = db_utils::postMemory($_GET);

$sSqlBuscaLicitacao = $oDaoLicitacao->sql_query($l20_codigo); 
$rsLicitacao        = $oDaoLicitacao->sql_record($sSqlBuscaLicitacao);
if ($oDaoLicitacao->numrows == 0) {
  
  db_redireciona('db_erros.php?fechar=true&db_erro=Este registro não possui licitação.');
  exit;
} else {
  $oLicitatacao = db_utils::fieldsMemory($rsLicitacao, 0);
}


$dtSituacao            = "";
$oDaoLicitacaoSituacao = db_utils::getDao("liclicitasituacao");
$sWhere                = " l11_liclicita       = ".$oLicitatacao->l20_codigo;
$sWhere               .= " and l11_licsituacao = ".$oLicitatacao->l08_sequencial;
$sOrder                = " l11_sequencial desc limit 1 ";
$sSqlDataSituacao      = $oDaoLicitacaoSituacao->sql_query(null, "l11_data", $sOrder, $sWhere);
$rsDataSituacao        = $oDaoLicitacaoSituacao->sql_record($sSqlDataSituacao);
if ($oDaoLicitacaoSituacao->numrows > 0) {
  $dtSituacao = db_formatar(db_utils::fieldsMemory($rsDataSituacao,0)->l11_data, 'd');
}

$oDadosLicitatacao  = new licitacao($l20_codigo);
$oProcessoProtocolo = $oDadosLicitatacao->getProcessoProtocolo();
$sProcessoProtocolo = '';

if (!empty($oProcessoProtocolo)) {
	
	//pro3_consultaprocesso002.php?codproc=42096/2013&numero=42096/2013
	$sProcessoProtocolo = $oProcessoProtocolo->getNumeroProcesso() . "/" . $oProcessoProtocolo->getAnoProcesso();
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
<style type="text/css">

  .valor {
    background-color: #FFF;
  }
</style>
</head>
<body>
<fieldset>
  <legend style="font-weight: bolder;"> Dados da Licitação</legend>
  
  <table style="" border='0'>
  
    <tr>
      <td title="<?=$Tl20_codigo?>" >
        <?php echo $Ll20_codigo;?>
      </td>
      <td nowrap="nowrap" class="valor" style="text-align: left;">
        <?php echo $oLicitatacao->l20_codigo;?>
      </td>
      <td nowrap="nowrap" title="Numero do Processo">
         <?php
           if ($sProcessoProtocolo != '') {
           	
             db_ancora("Número do Processo:", "consultaProcesso('{$sProcessoProtocolo}')", 1);
           } else {
           	 echo "<b> Número do Processo: </b>";
           }
          ?>
      </td>
      <td align='left' class="valor" >
        <?php echo $sProcessoProtocolo ?>
      </td>       
    </tr>
    <tr>
      <td nowrap title="<?=$Tl20_edital?>" style="width: 100px;">
        <?=$Ll20_edital?>
      </td>
      <td nowrap="nowrap" class="valor" style="width: 400px; text-align: left; ">
        <?php echo $oLicitatacao->l20_edital;?>
      </td>
      <td nowrap="nowrap" style=" width: 100px;">
        <?=$Ll20_anousu?>
      </td>
      <td nowrap="nowrap" class="valor" style="width:100px; text-align: left; ">
        <?php echo $oLicitatacao->l20_anousu;?>
      </td>
    </tr>
    <tr>
      <td nowrap="nowrap"  title="<?=@$Tl20_codtipocom?>">
      	<b>Modalidade:</b>
      </td> 
      <td nowrap="nowrap" class="valor" style="text-align: left;">
        <?php echo $oLicitatacao->l20_codtipocom . " - " . $oLicitatacao->l03_descr;?> 
      </td> 
      <td nowrap="nowrap" title="<?=$Tl20_numero?>">
        <?=$Ll20_numero?>
      </td>
      <td  nowrap="nowrap" class="valor" style="text-align: left; ">
        <?php echo $oLicitatacao->l20_numero;?> 
      </td>
    </tr>    
     <tr>
      <td nowrap="nowrap" title="<?=@$Tl20_datacria?>">
        <b><?=@$Ll20_datacria?></b>
      </td>
      <td  nowrap="nowrap" class="valor" style="text-align: left;"> 
        <?php echo implode("/", array_reverse(explode("-", $oLicitatacao->l20_datacria))); ?>
      </td>
      <td nowrap="nowrap" title="<?=@$Tl20_horacria?>">
        <b><?=@$Ll20_horacria?></b>
      </td>
      <td  nowrap="nowrap" class="valor" style="text-align: left;"> 
        <?php echo $oLicitatacao->l20_horacria; ?>
      </td>      
    </tr>   
    <tr>
      <td nowrap="nowrap" title="<?=@$Tl20_dataaber?>">
       <b><?=@$Ll20_dataaber?></b>
      </td>
      <td  nowrap="nowrap" class="valor" style="text-align: left;">
        <?php echo implode("/", array_reverse(explode("-", $oLicitatacao->l20_dataaber))); ?>
      </td>
      <td nowrap="nowrap" title="<?=@$Tl20_horaaber?>">
        <b><?=@$Ll20_horaaber?></b>
      </td>
      <td nowrap="nowrap" class="valor" style="text-align: left;"> 
        <?php echo $oLicitatacao->l20_horaaber; ?>
      </td>
    </tr>     
    <tr>
      <td nowrap="nowrap" >
        <b>Data Situação:</b>
      </td>
      <td nowrap="nowrap" class="valor" style="text-align: left;">
        <?php echo $dtSituacao; ?>
      </td>
      <td nowrap="nowrap" style="text-align: left;" title="<?=@$Tl20_dtpublic?>">
        <b><?=@$Ll20_dtpublic?></b>
      </td>
      <td  nowrap="nowrap" class="valor" style="text-align: left;"> 
        <?php echo implode("/", array_reverse(explode("-", $oLicitatacao->l20_dtpublic))); ?>
      </td>
    </tr>    
    <tr>
      <td nowrap="nowrap" title="<?=@$Tl20_id_usucria?>">
        <?=@$Ll20_id_usucria?>
      </td>          
      <td nowrap="nowrap" class="valor" style="text-align: left;"> 
        <?php echo $oLicitatacao->l20_id_usucria . " - " . $oLicitatacao->nome; ?>
      </td>
    
      <td nowrap="nowrap" >
        <b>Situação:</b>
      </td>
      <td nowrap="nowrap" class="valor" style="text-align: left;">
        <?php echo $oLicitatacao->l08_descr; ?>
      </td>
    </tr>    
    <tr> 
      <td nowrap="nowrap" title="<?=@$Tl20_local?>">
        <b><?=@$Ll20_local?></b>
      </td>
      <td colspan='3' align='left' class="valor" >
        <?php echo $oLicitatacao->l20_local ?>
      </td>
    </tr>    
     <tr> 
      <td nowrap="nowrap" title="<?=@$Tl20_objeto?>">
        <b><?=@$Ll20_objeto?></b>
      </td>
      <td colspan='3' align='left' class="valor" >
        <?php echo $oLicitatacao->l20_objeto ?>
      </td>        
    </tr>     
  </table>
</fieldset>

<?php 
  /**
   * Configuramos e exibimos as "abas verticais" (componente verticalTab)
   */
  $oVerticalTab = new verticalTab('detalhesLicitacao', 350);
  $sGetUrl      = "l20_codigo={$oGet->l20_codigo}";
  
  $oVerticalTab->add('dadosItensLicitacoes', 'Itens/Licitacões', 
                     "forms/db_frminfolic.php?{$sGetUrl}");
  
  $oVerticalTab->add('dadosProcessosCompras', 'Processos de Compras', 
                     "lic3_infolic003.php?tipo=p&{$sGetUrl}");
  
  $oVerticalTab->add('dadosSolicitacoesCompras', 'Solicitações de Compras', 
                     "lic3_infolic003.php?tipo=s&{$sGetUrl}");
  
  $oVerticalTab->add('dadosSituacoesLicitacao', 'Situações da Licitação', 
                     "lic3_infolic003.php?tipo=m&{$sGetUrl}");
  
  $oVerticalTab->add('dadosEditais', 'Editais', 
                     "lic3_infolicanexo002.php?{$sGetUrl}");
  
  $oVerticalTab->add('dadosAtas', 'Atas', 
                     "lic3_infolicata002.php?{$sGetUrl}");
  
  $oVerticalTab->add('dadosMinutas', 'Minutas',
  									 "lic3_infolicminuta002.php?{$sGetUrl}"); 

  $oVerticalTab->add('dadosAcordo', 'Acordos',
      "com3_pesquisalicitacaocontrato.php?{$sGetUrl}");
  
  $oVerticalTab->show();
?>
  
</body>

<script>

function consultaProcesso(sProtProcesso) {

  var sUrlProcesso = "pro3_consultaprocesso002.php?numero=" +  sProtProcesso;//42096/2013" ;// "lic3_licitacao002.php?l20_codigo="+iCodigoLicitacao
  js_OpenJanelaIframe('top.corpo', 'db_iframe_processo', sUrlProcesso, 'Consulta de Processo', true);
}

</script>