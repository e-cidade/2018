<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

  //MODULO: escola
  $oDaoRegraArredondamento->rotulo->label();
  
  if ($db_opcao == 1) {
 	  $db_action="edu1_regraarredondamento004.php";
  }
  if ($db_opcao == 2 || $db_opcao == 22) {
 	  $db_action="edu1_regraarredondamento005.php";
  }
  if ($db_opcao == 3 || $db_opcao == 33) {
 	  $db_action="edu1_regraarredondamento006.php";
  }

  $lBloquearAlteracao = false;
  $iAno               = db_getsession("DB_anousu");
  $sDisabled          = '';
  
  if (isset($ed316_sequencial)) {
    /**
     * Verificamos se a regra esta sendo utilizada em alguma escola, e se todas as turmas estao encerradas ou em aberto
     */
    $oDaoAvaliacaoEstruturaRegra    = db_utils::getDao("avaliacaoestruturaregra");
    $sWhereAvaliacaoEstruturaRegra  = "ed318_regraarredondamento = {$ed316_sequencial}";
    $sWhereAvaliacaoEstruturaRegra .= " AND ed315_ativo is true";
    $sSqlAvaliacaoEstruturaRegra    = $oDaoAvaliacaoEstruturaRegra->sql_query(null,
                                                                              "ed315_escola",
                                                                              null,
                                                                              $sWhereAvaliacaoEstruturaRegra
                                                                             );
    
    $rsAvaliacaoEstruturaRegra   = $oDaoAvaliacaoEstruturaRegra->sql_record($sSqlAvaliacaoEstruturaRegra);
    $iLinhasEstruturaRegra       = $oDaoAvaliacaoEstruturaRegra->numrows;
    
    if ($iLinhasEstruturaRegra > 0) {
      
      for ($iContadorRegra = 0; $iContadorRegra < $iLinhasEstruturaRegra; $iContadorRegra++) {
        
        $iEscola      = db_utils::fieldsMemory($rsAvaliacaoEstruturaRegra, $iContadorRegra)->ed315_escola;
        $oDaoRegencia = db_utils::getDao("regencia");
        
        $sWhereRegenciaTotal = "ed57_i_escola = {$iEscola} AND ed52_i_ano = {$iAno} ";
        $sSqlRegenciaTotal   = $oDaoRegencia->sql_query(null, "ed57_i_codigo", null, $sWhereRegenciaTotal);
        $rsRegenciaTotal     = $oDaoRegencia->sql_record($sSqlRegenciaTotal);
        $iTotalRegencias     = $oDaoRegencia->numrows;
        
        $sWhereRegenciaEncerrada  = "ed59_c_encerrada = 'S' and ed57_i_escola = {$iEscola} AND ed52_i_ano = {$iAno}";
        $sSqlRegenciaEncerrada    = $oDaoRegencia->sql_query(null, "ed57_i_codigo", null, $sWhereRegenciaEncerrada);
        $rsRegenciaEncerrada      = $oDaoRegencia->sql_record($sSqlRegenciaEncerrada);
        $iTotalRegenciasEncerrada = $oDaoRegencia->numrows;
        
        if ($iTotalRegencias > $iTotalRegenciasEncerrada && $iTotalRegenciasEncerrada != 0) {
          
          $lBloquearAlteracao = true;
          break;
        }
      }
      
      if ($lBloquearAlteracao) {
        
        db_msgbox("Não é possível alterar a regra, pois existem escolas utilizando esta regra, com turmas encerradas e em aberto");
        $sDisabled = "disabled";
      }
    }
  }
?>
<form name="form1" method="post" action="<?=$db_action?>">
  <div style="display: table">
    <fieldset>
      <legend><b>Regras de Arredondamento</b></legend>
      <center>
        <table border="0">
          <tr>
            <td nowrap title="<?=@$Ted316_descricao?>">
               <?=@$Led316_descricao?>
            </td>
            <td> 
              <?
                db_input('ed316_sequencial', 4, $Ied316_sequencial, true, 'hidden', 3, $sDisabled);
                db_input('ed316_descricao', 40, $Ied316_descricao, true, 'text', $db_opcao, $sDisabled);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ted316_ativo?>">
               <?=@$Led316_ativo?>
            </td>
            <td> 
              <?
                $x = array("f"=>"NAO","t"=>"SIM");
                db_select('ed316_ativo', $x, true, $db_opcao, $sDisabled);
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <?=$Led316_casasdecimaisarredondamento?>
            </td>
            <td>
              <?
                $aValores = array("1"=>"0","2"=>"00");
                db_select('ed316_casasdecimaisarredondamento', $aValores, true, $db_opcao, $sDisabled);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ted316_observacao?>" colspan="2">
              <fieldset>
                <legend><b><?=@$Led316_observacao?></b></legend>
                <?
                  db_textarea('ed316_observacao', 5, 64, $Ied316_observacao, true, 'text', $db_opcao, $sDisabled);
                ?>
              </fieldset>  
            </td>
          </tr>
        </table>
      </center>
    </fieldset>
  </div>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" 
         id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
         <?=($db_botao==false || !empty($sDisabled)?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_regraarredondamento', 
                      'db_iframe_regraarredondamento',
                      'func_regraarredondamento.php?funcao_js=parent.js_preenchepesquisa|ed316_sequencial',
                      'Pesquisa',
                      true
                     );
}
function js_preenchepesquisa(chave){
  
  db_iframe_regraarredondamento.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
?>
}
</script>