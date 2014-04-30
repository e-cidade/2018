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

  $clissmotivoparalisacao->rotulo->label();

  $sAction      = ($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"));
  $sActionAlias = ucfirst($sAction);
?>

<div class="container">
  <form name="form1" method="post" action="">
    <fieldset style="width:500px;">
      <legend><?php echo $sActionAlias;?>&nbsp;Motivo de Paralisação</legend>

      <?php echo db_input('q141_sequencial', 10, 0, true, 'hidden'); ?>
      <table border="0" class="form-container">
        <tr>
          <td nowrap title="<?php echo $Tq141_descricao?>">
            <?php echo $Lq141_descricao; ?>
          </td>
          <td> 
            <?php
              db_input('q141_descricao',50,$Iq141_descricao,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="<?php echo $sAction; ?>" type="submit" id="db_opcao" value="<?php echo $sActionAlias; ?>" <?php echo ($db_botao==false?"disabled":"") ?> >
    <?php 
     if ( $db_opcao <> 1 ) {
       echo "<input name='pesquisar' type='button' id='pesquisar' value='Pesquisar' onclick='js_pesquisa();' >";
     }
    ?>
  </form>
</div>
<script>

  function js_pesquisa(){
    js_OpenJanelaIframe('top.corpo','db_iframe_issmotivoparalisacao','func_issmotivoparalisacao.php?funcao_js=parent.js_preenchepesquisa|q141_sequencial','Pesquisa',true);
  }
  
  function js_preenchepesquisa(chave){
    db_iframe_issmotivoparalisacao.hide();
    <?php
      if($db_opcao!=1){
        echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
      }
    ?>
  }
</script>