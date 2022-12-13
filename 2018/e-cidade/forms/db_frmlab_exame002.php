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

//MODULO: Laboratório
$cllab_exame->rotulo->label();
$clrotulo = new rotulocampo ( );
$clrotulo->label ( "la19_i_exame" );
?>
<div class="container">
  <form name="form1" method="post" action="">
    <fieldset>
      <legend>Exames</legend>
      <table class="form-container">
        <tr style="display:none;">
          <td nowrap title="<?=$Tla08_i_codigo?>">
           <?=$Lla08_i_codigo?>
          </td>
          <td>
            <?php
            db_input('la08_i_codigo',10,$Ila08_i_codigo,true,'text',3,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Tla19_i_exame?>">
           <?
           db_ancora ( $Lla19_i_exame, "", 3 );
           ?>
          </td>
          <td>
            <?php
              db_input ( 'la08_i_codigo', 10, $Ila19_i_exame, true, 'text', 3, "" );
              db_input ( 'la08_c_descr',  50, $Ila08_c_descr, true, 'text', 3,"");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Tla08_t_interferencia?>" colspan='2'>
            <fieldset class='separator'>
              <legend><?=$Lla08_t_interferencia?></legend>
              <?php
                db_textarea('la08_t_interferencia', 6, 59,$Ila08_t_interferencia,true,'text',$db_opcao,"")
              ?>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Tla08_observacao?>" colspan='2'>

            <fieldset class='separator'>
              <legend><?=$Lla08_observacao?></legend>
              <?php
                db_textarea('la08_observacao', 6, 59, $Ila08_observacao,true,'text',$db_opcao,"");
              ?>
            </fieldset>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  </form>
</div>