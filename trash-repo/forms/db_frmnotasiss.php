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
 
//MODULO: issqn
$clnotasiss->rotulo->label();

$aGrupos = array('' => 'Selecione');

$oDaoGrupoNotaIss = db_utils::getDao('gruponotaiss');
$sSqlGrupos = $oDaoGrupoNotaIss->sql_query_file(null, '*', 'q139_sequencial');
$rsGrupos   = $oDaoGrupoNotaIss->sql_record($sSqlGrupos);

if ( $oDaoGrupoNotaIss->numrows > 0 ) {

  $iGrupos = $oDaoGrupoNotaIss->numrows;

  for ( $iIndice = 0; $iIndice < $iGrupos; $iIndice++ ) {

    $oGrupo = db_utils::fieldsMemory($rsGrupos, $iIndice);
    $aGrupos[$oGrupo->q139_sequencial] = $oGrupo->q139_descricao;
  }
} 
?>
<div class="container">

  <form name="form1" method="post" action="">

    <fieldset style="margin-top:30px;width:450px;">
      <legend>Nota Fiscal:</legend>

      <table class="form-container">
        <tr style="display:none;">
          <td nowrap title="<?=@$Tq09_codigo?>">
             <?=@$Lq09_codigo?>
          </td>
          <td> 
          <?php db_input('q09_codigo',6,$Iq09_codigo,true,'text',3," class='field-size2'"); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=@$Tq09_nota?>">
             <?=@$Lq09_nota?>
          </td>
          <td> 
            <?php db_input('q09_nota',5,$Iq09_nota,true,'text',$db_opcao,"class='field-size2'"); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=@$Tq09_descr?>">
             <?=@$Lq09_descr?>
          </td>
          <td> 
            <?php db_input('q09_descr',40,$Iq09_descr,true,'text',$db_opcao,"class='field-size8'"); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Tq09_gruponotaiss; ?>">
             <?php echo $Lq09_gruponotaiss; ?>
          </td>
          <td> 
            <?php db_select('q09_gruponotaiss', $aGrupos, true, $db_opcao); ?>
          </td>
        </tr>
        
      </table>

    </fieldset>
  
    <br />

    <input name="db_opcao" type="submit" onclick="return js_validar();" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    <?php if ( $db_opcao != 1 ) : ?>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    <?php endif; ?>

  </form>

</div>
<script type="text/javascript">

function js_validar() {

  var oGrupo = document.getElementById('q09_gruponotaiss'); 
  var iGrupo = oGrupo.value;

  if ( iGrupo == '' ) {

    alert('Campo Grupo: Não Informado.');
    return false;
  }

  return true;
}

function js_pesquisa() {
  js_OpenJanelaIframe('','db_iframe_notasiss','func_notasiss.php?funcao_js=parent.js_preenchepesquisa|q09_codigo','Pesquisa',true);
}

function js_preenchepesquisa(chave){

  db_iframe_notasiss.hide();
  <?php
  if($db_opcao !=1 ) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
</script>