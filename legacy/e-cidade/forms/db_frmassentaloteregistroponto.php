<?php
/**
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
$oDaoRhpreponto = new cl_rhpreponto();
$oDaoRhpreponto->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("nomeinst");
$oRotulo->label("rh142_sequencial");
$oRotulo->label("rh01_numcgm");
$oRotulo->label("z01_nome");
$oRotulo->label("rh01_regist");
$db_opcao = 1;

$iAnousu  = DBPessoal::getAnoFolha();
$iMesusu  = DBPessoal::getMesFolha();
$sDisplay = ( isset($_GET['action']) && $_GET['action'] == 'cancelar' ) ? "none" : "";
?>
<div class="container" style="width: 680px; margin-top: 10px !important;">
  <fieldset>
    <legend>Lançamento de Substituições no Ponto</legend>
      <table class="form-container">
       <tr>
          <td class="label">
            <label>Competência:</label>
          </td>
          <td>
            <?php
              db_input('iAnousu', 4, '', true, 'text', 3, '');
              echo "/";
              db_input('iMesusu', 2, '', true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr title="<?php echo $Trh01_regist; ?>">
          <td nowrap title="<?php echo $Trh01_regist; ?>" class="label">
            <label><?php echo $Srh01_regist; ?>:</label>
          </td>
          <td>
            <?php db_input('rh01_regist', 4, $Irh01_regist, true, 'text', 3, ''); ?>
            <?php db_input('z01_nome', 71, $Iz01_nome, true, 'text', 3, ''); ?>
          </td>
        </tr>
        <tr style="display: <?php echo $sDisplay; ?>;">
          <td class="label">
            <label>Folha:</label>
          </td>
          <td>
            <?php
  
              if(DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
  
                $aTiposFolha = array(0=>'Selecione');
                if(FolhaPagamentoSalario::hasFolhaAberta()) {
                  $aTiposFolha[FolhaPagamento::TIPO_FOLHA_SALARIO]      ='Salário';
                } elseif(FolhaPagamentoSuplementar::hasFolhaAberta()){
                  $aTiposFolha[FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR]  ='Suplementar';
                }
  
                if(FolhaPagamentoComplementar::hasFolhaAberta()){
                  $aTiposFolha[FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR] ='Complementar';
                }
              } else {
                $aTiposFolha[FolhaPagamento::TIPO_FOLHA_SALARIO]        ='Salário';
                // $aTiposFolha[FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR]   ='Complementar';
              }
  
              db_select('iFolha', $aTiposFolha, '', 1, "", "", "");
            ?>
          </td>
       </tr>
      </table>
      <fieldset>
      <legend>Assentamentos Encontrados:</legend>
      <div id="grid_servidor_assentamentos"></div>
      </fieldset>
  </fieldset>
    <input type="button" value="Processar" id="processar" />
</div>
