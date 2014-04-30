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

require_once "libs/db_stdlib.php";
require_once "libs/db_utils.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";
require_once "dbforms/verticalTab.widget.php";
require_once "classes/db_veiculos_classe.php";
require_once "classes/db_veicresp_classe.php";
require_once "classes/db_veicpatri_classe.php";
require_once "classes/db_veicparam_classe.php";
require_once "classes/db_veicbaixa_classe.php";
require_once "classes/db_veiculoscomb_classe.php";
require_once "classes/db_veictipoabast_classe.php";

$clveiculos      = new cl_veiculos;
$clveicresp      = new cl_veicresp;
$clveicpatri     = new cl_veicpatri;
$clveicparam     = new cl_veicparam;
$clveicbaixa     = new cl_veicbaixa;
$clveiculoscomb  = new cl_veiculoscomb;
$clveictipoabast = new cl_veictipoabast;
$clveicrcesp     = new cl_veicresp;

$clveiculos->rotulo->label();

$clrotulo = new rotulocampo;

$clrotulo->label("ve32_descr");
$clrotulo->label("ve31_descr");
$clrotulo->label("ve24_descr");
$clrotulo->label("cp05_localidades");
$clrotulo->label("ve20_descr");
$clrotulo->label("ve21_descr");
$clrotulo->label("ve22_descr");
$clrotulo->label("ve23_descr");
$clrotulo->label("ve25_descr");
$clrotulo->label("ve06_veiccadcomb");
$clrotulo->label("ve26_descr");
$clrotulo->label("ve30_descr");
$clrotulo->label("ve02_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("ve03_bem");
$clrotulo->label("t52_descr");
$clrotulo->label("descrdepto");
$clrotulo->label("nome");
$clrotulo->label("ve07_sigla");
$clrotulo->label("ve40_veiccadcentral");

/*
 * Recupera as informações passadas por GET para o objeto $oGet e efetua a busca das informações
 * do veículo e armazena dentro do array $aVeiculo
 */

$oGet             = db_utils::postMemory($_GET, false);
$sSqlBuscaVeiculo = $clveiculos->sql_query_veiculo($oGet->veiculo, "*, ( select array_to_string(array_accum(distinct a.ve40_veiccadcentral||'-'||c.descrdepto),', ') from veiculos.veiccentral a inner join veiccadcentral b on b.ve36_sequencial = a.ve40_veiccadcentral inner join db_depart c on c.coddepto = b.ve36_coddepto where a.ve40_veiculos = veiculos.ve01_codigo ) as descr_central, ( select array_to_string(array_accum(distinct ve26_descr),', ') from veiculoscomb a inner join veiccadcomb b on b.ve26_codigo = a.ve06_veiccadcomb where a.ve06_veiculos = veiculos.ve01_codigo ) as lista_combustivel ");
$rsBuscaVeiculo   = $clveiculos->sql_record($sSqlBuscaVeiculo);
$oVeiculo         = db_utils::fieldsMemory($rsBuscaVeiculo, false);
?>
<html>
<head>
<title>Dados do Cadastro de Veículos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
<style type='text/css'>
.valores {background-color:#FFFFFF}
</style>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <fieldset>
    
    <legend>
      <strong>Dados Cadastrais:</strong>
    </legend>
    
    <table>
      <tr>
      
        <td>
          <?=@$Lve01_codigo?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve01_codigo?>
        </td>
        
        <td>
          <?=@$Lve01_placa?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve01_placa?>
        </td>
        
        <td>
          <?=@$Lve02_numcgm?>
        </td>
        <td class="valores">
          <?= (isset($oVeiculo->z01_nome)) ? $oVeiculo->z01_nome : 'NENHUM'?>
        </td>  
            
      </tr>
      <tr>
                
        <td>
          <?=@$Lve01_veiccadtipo?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve20_descr?>
        </td>
        
        <td>
          <?=@$Lve01_veiccadmarca?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve21_descr?>
        </td>
        
        <td>
          <?=@$Lve01_veiccadmodelo?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve22_descr?>
        </td>
        
      </tr>
      <tr>
      
        <td>
          <?=@$Lve01_veiccadcor?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve23_descr?>
        </td>
        
        <td>
          <?=@$Lve01_veiccadproced?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve25_descr?>
        </td>
        
        <td>
          <?=@$Lve01_veiccadcateg?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve32_descr?>
        </td>
        
      </tr>
      <tr>
      
        <td>
          <?=@$Lve01_chassi?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve01_chassi?>
        </td>
      
        <td>
          <?=@$Lve01_ranavam?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve01_ranavam?>
        </td>
        
        <td>
          <?=@$Lve01_placanum?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve01_placanum?>
        </td>
      
      </tr>
      <tr>
      
        <td>
          <?=@$Lve01_certif?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve01_certif?>
        </td>
        
        <td>
          <?=@$Lve01_quantpotencia?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve01_quantpotencia?>
        </td>
        
        <td>
          <?=@$Lve01_veiccadpotencia?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve31_descrcompleta?>
        </td>
      
      </tr>
      <tr>
        
        <td>
          <?=@$Lve01_medidaini?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve01_medidaini.' '.$oVeiculo->ve07_sigla?>
        </td>
        
        <td>
          <?=@$Lve01_quantcapacidad?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve01_quantcapacidad?>
        </td>
        
        <td>
          <?=@$Lve01_veiccadtipocapacidade?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve24_descr?>
        </td>
      
      </tr>
      <tr>
        
        <td>
          <?=@$Lve01_dtaquis?>
        </td>
        <td class="valores">
          <?=db_formatar($oVeiculo->ve01_dtaquis, 'd')?>
        </td>
        
        <td>
          <?=@$Lve06_veiccadcomb?>
        </td>
        <td class="valores">
          <?=$oVeiculo->lista_combustivel?>
        </td>
        
        <td>
          <?=@$Lve01_veiccadcategcnh?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve30_descr?>
        </td>
      
      </tr>
      <tr>
        
        <td>
          <?=@$Lve01_anofab?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve01_anofab?>
        </td>
        
        <td>
          <?=@$Lve01_anomod?>
        </td>
        <td class="valores">
          <?=$oVeiculo->ve01_anomod?>
        </td>
        
        <td>
          <?=@$Lve01_ceplocalidades?>
        </td>
        <td class="valores">
          <?=$oVeiculo->cp05_localidades?>
        </td>
      
      </tr>
      <tr>
        
        <td>
          <?=@$Lve40_veiccadcentral?>
        </td>
        <td class="valores">
          <?=$oVeiculo->descr_central?>
        </td>
        
        <td>
          <?=@$Lve03_bem?>
        </td>
        <td class="valores">
          <?=($oVeiculo->ve03_bem) ? $oVeiculo->t52_descr : 'SEM LIGAÇÃO COM O PATRIMONIO'?>
        </td>
        
      </tr>
        
    </table>
    
  </fieldset>
  
  <fieldset>
    
    <legend>
      <strong>Dados da Baixa:</strong>
    </legend>
    
    <table>

      <? if ($oVeiculo->ve04_codigo) { ?>
        <tr>
        
          <td>
            <b>Data da Baixa:</b>
          </td>
          <td class="valores">
            <?=db_formatar($oVeiculo->ve04_data, 'd')?>
          </td>
          
          <td>
            <b>Horário da Baixa:</b>
          </td>
          <td class="valores">
            <?=$oVeiculo->ve04_hora?>
          </td>
          
        </tr>
        
        <tr>
        
          <td>
            <b>Usuário Responsável:</b>
          </td>
          <td class="valores">
            <?=$oVeiculo->nome?>
          </td>
          
          <td>
            <b>Motivo:</b>
          </td>
          <td class="valores">
            <?=$oVeiculo->ve04_motivo?>
          </td>
        
        </tr>
      <? } else { ?>
        <tr>
          <td>
            <b>VEICULO NÃO BAIXADO</b>
          </td>
        </tr>
      <? } ?>
    </table>
  </fieldset>
  <fieldset>
    <legend>
      <strong>Detalhamento do veículo:</strong>
    </legend>
    
    <?
      $oTabDetalhes = new verticalTab('detalhesVeiculo', 300);
      
      $sGetUrl = "?veiculo={$oVeiculo->ve01_codigo}";
      $oTabDetalhes->add('retiradas'             , 'Retiradas'            , "func_detalhamentoretiradasveiculos.php{$sGetUrl}");
      $oTabDetalhes->add('abastecimento'         , 'Abastecimentos'       , "func_detalhamentoabastecimentosveiculos.php{$sGetUrl}");
      $oTabDetalhes->add('manutencao'            , 'Manutenções'          , "func_detalhamentomanutencaoveiculos.php{$sGetUrl}");
      $oTabDetalhes->add('manutenção de medidas' , 'Manutenções de Medida', "func_detalhamentomanutencaomedida.php{$sGetUrl}");
      $oTabDetalhes->add('Impressão'             , 'Impressão'            , "func_impressaofichaveiculo.php{$sGetUrl}");
      $oTabDetalhes->show();
    ?>
  </fieldset>
</body>
</html>