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


  $clrequisicaoaidof->rotulo->label();
  $clnotasiss->rotulo->label();
  $clcgm->rotulo->label();
  
  if (is_object($oDadosRequisicao)) {
    
    $y116_id                     = $oDadosRequisicao->y116_id;
    $y116_inscricaomunicipal     = $oDadosRequisicao->y116_inscricaomunicipal;
    $y116_tipodocumento          = $oDadosRequisicao->y116_tipodocumento;
    $q09_descr                   = $oDadosRequisicao->q09_descr;
    $y116_quantidadesolicitada   = $oDadosRequisicao->y116_quantidadesolicitada;
    $y116_datalancamento         = new DateTime($oDadosRequisicao->y116_datalancamento);
    $y116_datalancamento_dia     = $y116_datalancamento->format('d');
    $y116_datalancamento_mes     = $y116_datalancamento->format('m');
    $y116_datalancamento_ano     = $y116_datalancamento->format('Y');
    $z01_nome                    = $oDadosRequisicao->z01_nome;
    $nome_usuario                = $oDadosRequisicao->nome;
    
    if (!empty($oDadosRequisicao->y116_idusuario) || $oDadosRequisicao->y116_status == 'C') {
      
      $y116_idusuario            = $oDadosRequisicao->y116_idusuario;
      
      switch ($oDadosRequisicao->y116_status) {
        case 'L' :
          
          $y116_status = 'Liberada';
          break;
        case 'B' :

          $y116_status = 'Bloqueada';
          break; 
        case 'C' :
          
          $y116_status = 'Cancelada';
          break;
      }
      
      
      $y116_quantidadeLiberada   = $oDadosRequisicao->y116_quantidadeliberada;
      $y116_observacao           = $oDadosRequisicao->y116_observacao;
      $db_opcao = 3;
      
    } else {
      
      $Ly116_quantidadeLiberada = "Quantidade a Liberar:";
    }
  }
?>

<center>
  <form name="form1" method="post" action="fis1_requisicaoaidof002.php">
    <fieldset class="requisicao_aidof">
      <legend>Requisição de Aidof</legend>
      <table>
        <tr>
          <td nowrap title="<?php echo @$Ty116_id ?>" style="width:150px"><?php echo @$Ly116_id ?></td>
          <td><?php db_input('y116_id', 7, $Iy116_id, true, 'text', 3, '') ?></td>
        </tr>
        <tr>
          <td nowrap title="<?php echo @$Ty116_inscricaomunicipal ?>"><?php echo @$Ly116_inscricaomunicipal ?></td>
          <td> 
            <?php
              db_input('y116_inscricaomunicipal', 7, $Iy116_inscricaomunicipal, true, 'text', 3, '');
              db_input('z01_nome', 48, $Iz01_nome, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo @$Ty116_tipodocumento ?>">
            <?php echo $Ly116_tipodocumento; ?>
          </td>
          <td> 
            <?php 
              db_input('y116_tipodocumento', 7, $Iy116_tipodocumento, true, 'text', 3,
                ' onchange="js_pesquisay116_tipodocumento(false)"');
              
              db_input('q09_descr', 48, $Iq09_descr, true, 'text', 3, '');
             ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo @$Ty116_datalancamento ?>">
            <?php echo @$Ly116_datalancamento ?>
          </td>
          <td> 
            <?php
              db_inputdata('y116_datalancamento', @$y116_datalancamento_dia, @$y116_datalancamento_mes,
                @$y116_datalancamento_ano, true, 'text', 3, '');
            ?>
          </td>
       </tr>
        <tr>
          <td nowrap title="<?php echo @$Ty116_quantidadesolicitada ?>">
            <?php echo @$Ly116_quantidadesolicitada ?>
          </td>
          <td> 
            <?php db_input('y116_quantidadesolicitada', 7, $Iy116_quantidadesolicitada, true, 'text', 3, '') ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Ty116_quantidadeLiberada ?>">
            <strong><?php echo $Ly116_quantidadeLiberada; ?></strong>
          </td>
          <td>
            <?php db_input('y116_quantidadeLiberada', 7, $Iy116_quantidadeLiberada, true, 'text', $db_opcao, '') ?>
          </td>
        </tr>
        <?php if ($db_opcao == 3) { ?>
          <tr>
            <td nowrap title="<?php echo $Ty116_status ?>">
              <strong><?php echo $Ly116_status; ?></strong>
            </td>
            <td>
              <?php 
                
              db_input('y116_status', 10, $Iy116_status, true, 'text', $db_opcao, '') ?>
            </td>
          </tr>
          <?php if (!empty($oDadosRequisicao->y116_idusuario)) { ?>
          <tr>
            <td nowrap title="<?php echo $Ty116_idusuario ?>">
              <strong><?php echo $Ly116_idusuario; ?> </strong>
            </td>
            <td>
              <?php db_input('y116_idusuario', 7, $Iy116_idusuario, true, 'text', $db_opcao, '') ?>
              <?php db_input('nome_usuario', 48, $Iy116_idusuario, true, 'text', $db_opcao, '') ?>
            </td>
          </tr>
          <?php } ?>
        <?php } ?>
        <tr>
          <td colspan="2">
            <fieldset>
              <legend title="<?php echo $Ty116_observacao ?>">
                <?php echo $Ly116_observacao ?>
              </legend>
              
              <?php db_textarea('y116_observacao', 0, 0, $Iy116_observacao, true, 'text', $db_opcao, '') ?>
            </fieldset>
          </td>
        </tr> 
      </table>
    </fieldset>
    
    <div class="botoes">
      <?php if ($db_opcao != 3) { ?>
      
        <input name="liberar"  type="button" id="liberar"  value="Liberar" onclick="javascript:js_validar(this)">
        <input name="bloquear" type="button" id="bloquear" value="Bloquear" onclick="javascript:js_validar(this)">
      <?php } ?>
      
      <input name="voltar"   type="button" id="voltar"   value="Voltar" onclick="javascript:js_voltar()">
      <input name="bOpcao"   type="hidden" id="bOpcao"   value="">
      
    </div>
  </form>
</center>

<script type="text/javascript">
  function js_voltar() {
    location.href = "fis1_requisicaoaidof001.php";
  }

  function js_validar ($oCampo) {

    if ($oCampo.name == 'liberar') {

      if (document.form1.y116_quantidadeLiberada.value == "") {
          
        alert("Campo Quantidade liberada é obrigatório!");
        document.form1.y116_quantidadeLiberada.focus();
        return false;
      }

      $bOpcao = 'liberar';
      
    } else if ($oCampo.name == 'bloquear') {

      if (document.form1.y116_observacao.value == "") {
          
          alert("Campo Observação é obrigatório!");
          return false;
      }
      
      $bOpcao = 'bloquear';
    }

    if ($bOpcao != '') {
      
      document.form1.bOpcao.value = $bOpcao;
      document.form1.submit();
    }
  }  
</script>