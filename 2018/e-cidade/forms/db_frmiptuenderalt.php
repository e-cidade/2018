<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
?>
<fieldset>
<legend><b>Manutenção de endereços de entrega:</b></legend>
<table border="0" width="790">
      <tr>
        <td nowrap title="<?php echo $Tj43_matric; ?>">
          <?php echo $Lj43_matric; ?>
        </td>
        <td>
        <?php
          db_input('j43_matric',10,$Ij43_matric,true,'text',3," onchange='js_pesquisaj43_matric(false);'");
          db_input('z01_nome',78,$Ij01_numcgm,true,'text',3,'','');
        ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Tj43_ender; ?>">
       <?php echo $Lj43_ender; ?>
        </td>
        <td>
        <?php db_input('j43_ender',91,$Ij43_ender,true,'text',$db_opcao,""); ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Tj43_numimo; ?>">
       <?php echo $Lj43_numimo; ?>
        </td>
        <td>
        <?php db_input('j43_numimo',10,$Ij43_numimo,true,'text',$db_opcao,""); ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Tj43_comple; ?>">
       <?php echo $Lj43_comple; ?>
        </td>
        <td>
        <?php db_input('j43_comple',91,$Ij43_comple,true,'text',$db_opcao,""); ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Tj43_cxpost; ?>">
       <?php echo $Lj43_cxpost; ?>
        </td>
        <td>
        <?php db_input('j43_cxpost',10,$Ij43_cxpost,true,'text',$db_opcao,""); ?>
        </td>
      </tr>
  <tr>
    <td nowrap title="<?php echo $Tj43_bairro; ?>">
       <?php echo $Lj43_bairro; ?>
    </td>
    <td>
    <?php db_input('j43_bairro',91,$Ij43_bairro,true,'text',$db_opcao,""); ?>
    </td>
  </tr>
      <tr>
        <td nowrap title="<?php echo $Tj43_munic; ?>">
          <?php echo $Lj43_munic; ?>
        </td>
        <td>
        <?php db_input('j43_munic',91,$Ij43_munic,true,'text',$db_opcao,""); ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Tj43_uf; ?>">
        <?php echo $Lj43_uf; ?>
        </td>
        <td>
        <?php db_input('j43_uf',10,$Ij43_uf,true,'text',$db_opcao,"");?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Tj43_cep; ?>">
        <?php echo $Lj43_cep; ?>
        </td>
        <td>
        <?php db_input('j43_cep',10,$Ij43_cep,true,'text',$db_opcao,""); ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Tj43_dest; ?>">
        <?php echo $Lj43_dest; ?>
        </td>
        <td>
        <?php db_input('j43_dest',91,$Ij43_dest,true,'text',$db_opcao,""); ?>
        </td>
      </tr>
    </table>
</fieldset>
<br />
<input name="atualizar" type="submit" id="atualizar" value="Atualizar" onclick="return js_verifica_campos_digitados();">
<input name="excluir" type="submit" id="excluir" value="Excluir" <?php echo ($db_botao==1?"disabled":""); ?>>