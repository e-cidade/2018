<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: Vacinas
$clvac_campanha->rotulo->label();
?>
<fieldset style='width: 65%;'> <legend><b>Camapanha de vacinação</b></legend>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tvc11_i_codigo?>">
       <?=@$Lvc11_i_codigo?>
    </td>
    <td> 
     <?db_input('vc11_i_codigo',10,$Ivc11_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc11_c_nome?>">
       <?=@$Lvc11_c_nome?>
    </td>
    <td> 
     <?db_input('vc11_c_nome',30,$Ivc11_c_nome,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc11_c_descr?>">
       <?=@$Lvc11_c_descr?>
    </td>
    <td> 
     <?db_input('vc11_c_descr',50,$Ivc11_c_descr,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc11_d_inicio?>">
       <?=@$Lvc11_d_inicio?>
    </td>
    <td> 
     <?db_inputdata('vc11_d_inicio',
                    @$vc11_d_inicio_dia,
                    @$vc11_d_inicio_mes,
                    @$vc11_d_inicio_ano,
                    true,
                    'text',
                    $db_opcao,
                    ""
                  )?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc11_d_fim?>">
       <?=@$Lvc11_d_fim?>
    </td>
    <td> 
     <?db_inputdata('vc11_d_fim',@$vc11_d_fim_dia,@$vc11_d_fim_mes,@$vc11_d_fim_ano,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  </table>
  </center>
<input name  = "<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type  = "submit" 
       id    = "db_opcao" 
       value = "<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa() {  

  js_OpenJanelaIframe('',
                      'db_iframe_vac_campanha',
                      'func_vac_campanha.php?funcao_js=parent.js_preenchepesquisa|vc11_i_codigo',
                      'Pesquisa',
                      true
                     );

}

function js_preenchepesquisa(chave) {

  db_iframe_vac_campanha.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>