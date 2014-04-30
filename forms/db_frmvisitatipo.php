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

//MODULO: social
$clvisitatipo->rotulo->label();
?>
<table border="0">
  <tr style="display: none">
    <td nowrap title="<?=@$Tas13_sequencial?>">
       <?=@$Las13_sequencial?>
    </td>
    <td>
    <?php
      db_input('as13_sequencial',10,$Ias13_sequencial,true,'hidden',3,"");
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tas13_descricao?>">
       <?=@$Las13_descricao?>
    </td>
    <td>
      <?php
        db_input('as13_descricao',80,$Ias13_descricao,true,'text',$db_opcao,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tas13_exigeencaminhamento?>">
       <?=@$Las13_exigeencaminhamento?>
    </td>
    <td>
      <?php
        $x = array('f'=>'Não','t'=>'Sim');
        db_select('as13_exigeencaminhamento',$x,true,$db_opcao,"");
      ?>
    </td>
  </tr>
  </table>

<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_visitatipo','func_visitatipo.php?funcao_js=parent.js_preenchepesquisa|as13_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_visitatipo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>