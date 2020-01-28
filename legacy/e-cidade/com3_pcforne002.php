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
include("classes/db_pcforne_classe.php");
include("dbforms/db_funcoes.php");
require_once("dbforms/verticalTab.widget.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clpcforne = new cl_pcforne;
$clpcforne->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc60_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_cgccpf");
if (isset($pc60_numcgm)&&$pc60_numcgm!=""){
	$result_forne = $clpcforne->sql_record($clpcforne->sql_query($pc60_numcgm));
	if ($clpcforne->numrows>0){
		db_fieldsmemory($result_forne,0);
	}else{
		echo "<script>alert('Fornecedor não cadastrado!!');parent.db_iframe.hide();</script>";
		exit;	
	}
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

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"> 
  <fieldset>
  <!-- Dados do Fornecedor - INICIO -->
  <legend style="font-weight: bold; font-size: 11px;">&nbsp;Dados do Fornecedor&nbsp;</legend>
    <table width="60%">
      <tr>
        <td width="13%" title="<?=@$Tpc60_numcgm?>"><?=@$Lpc60_numcgm?></td><!-- ID CGM -->
        <td style="background-color: #FFF; color: #000; width: 200px;"><?=$pc60_numcgm;?></td>
        <td width="13%" title="<?=@$Tz01_nome;?>"><?=@$Lz01_nome;?></td><!-- Razão Social / Nome -->
        <td style="background-color: #FFF; color: #000;"><?=$z01_nome ?></td>
      </tr>
      <tr>
        <td title="<?=@$Tz01_cgccpf;?>"><?=@$Lz01_cgccpf;?></td><!-- CNPJ/CGC/CPF -->
        <td style="background-color: #FFF; color: #000;"><?=$z01_cgccpf; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td title="<?=@$Tpc60_dtlanc;?>"><?=@$Lpc60_dtlanc?></td><!-- Data -->
        <td style="background-color: #FFF; color: #000;"><? echo @$pc60_dtlanc_dia."/".@$pc60_dtlanc_mes."/".@$pc60_dtlanc_ano; ?></td>
        <td title="<?=@$Tpc60_bloqueado?>"><?=@$Lpc60_bloqueado?></td>
        <td style="background-color: #FFF; color: #000;">
          <?
            /**
             * Verifica se o valor é TRUE ou FALSE para printar na
             * tela SIM ou NÃO
             */
            $bloqueado = "";
            if (isset($pc60_bloqueado) && $pc60_bloqueado != "") {
              if ($pc60_bloqueado == 't') {
                $bloqueado = "Sim";
              }else if ($pc60_bloqueado == 'f') {
                $bloqueado = "Não";
              }
            }
            echo $bloqueado;
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?=@$Tpc60_obs?>"><?=@$Lpc60_obs?></td><!-- Observações -->
        <td colspan="3" style="background-color: #FFF; color: #000;">
          <? echo nl2br($pc60_obs); ?>
        </td>
      </tr>
    </table>
  <!-- Dados do Fornecedor - FINAL -->
  </fieldset>
  <br />
  <fieldset>
  <legend style="font-weight: bold; font-size: 11px;">&nbsp;Detalhamento&nbsp;</legend>
  <?
    /**
     * Cria a tabela para navegação vertical
     */
    $oTabDetalhes = new verticalTab("detalhesfornecedor",300);
    $oTabDetalhes->add("contas", "Consulta Contas", "com3_pcforne003.php?pc60_numcgm={$pc60_numcgm}");
    $oTabDetalhes->add("mov", "Consulta Movimentos", "com3_pcforne004.php?pc60_numcgm={$pc60_numcgm}");
    $oTabDetalhes->add("grupfor", "Grupos de Fornecimento", "com3_pcforne005.php?pc60_numcgm={$pc60_numcgm}");
    $oTabDetalhes->add("certif", "Certificados", "com3_pcforne006.php?pc60_numcgm={$pc60_numcgm}");
    $oTabDetalhes->show();    
  ?>
  </fieldset>
</body>
</html>