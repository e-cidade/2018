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
$clvac_sala->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
?>
<fieldset style='width: 75%;'> <legend><b>Salas de Vacinação</b></legend>
<form name="form2" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tvc01_i_codigo?>">
       <?=@$Lvc01_i_codigo?>
    </td>
    <td> 
    <?db_input('vc01_i_codigo',10,$Ivc01_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc01_i_unidade?>">
       <?db_ancora(@$Lvc01_i_unidade,"js_pesquisavc01_i_unidade(true);",$db_opcao);?>
    </td>
    <td> 
     <?
      db_input('vc01_i_unidade',10,$Ivc01_i_unidade,true,'text',$db_opcao,
               " onchange='js_pesquisavc01_i_unidade(false);' ");
      db_input('descrdepto',40,$Idescrdepto,true,'text',3,'');
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc01_c_nome?>">
       <?=@$Lvc01_c_nome?>
    </td>
    <td> 
     <?db_input('vc01_c_nome',20,$Ivc01_c_nome,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc01_c_descr?>">
       <?=@$Lvc01_c_descr?>
    </td>
    <td> 
     <?db_input('vc01_c_descr',50,$Ivc01_c_descr,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc01_i_situacao?>">
       <?=@$Lvc01_i_situacao?>
    </td>
    <td> 
     <? 
       $aTipos= Array("1"=>"ATIVO","2"=>"INATIVO");
       db_select("vc01_i_situacao",$aTipos,$Ivc01_i_situacao,$db_opcao,"");
     ?>
    </td>
  </tr>
  </table>
  </center>
<input name  ="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type  ="submit" 
       id    ="db_opcao" 
       value ="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> >
<input name    ="cancelar" 
       type    ="button" 
       id      ="cancelar" 
       value   ="Cancelar" 
       onclick ="location.href='vac1_vac_sala001.php';" 
       <?=($db_botao1==false?"disabled":"")?> >
</form>
</fieldset>
<br><br>

<?
  $chavepri                                = array("vc01_i_codigo"=>@$vc01_i_codigo);
  $cliframe_alterar_excluir->chavepri      = $chavepri;
  $cliframe_alterar_excluir->sql           = $clvac_sala->sql_query(null,'*',null,"");
  $cliframe_alterar_excluir->legenda       = "Registros Salas de vacinação";
  $cliframe_alterar_excluir->campos        ="vc01_i_codigo,vc01_c_nome,descrdepto";
  $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
  $cliframe_alterar_excluir->textocabec    = "darkblue";
  $cliframe_alterar_excluir->textocorpo    = "black";
  $cliframe_alterar_excluir->fundocabec    = "#aacccc";
  $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
  $cliframe_alterar_excluir->iframe_width  = "100%";
  $cliframe_alterar_excluir->iframe_height = "130";
  $cliframe_alterar_excluir->opcoes        = 1;
  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
?> 

<script>

function js_pesquisavc01_i_unidade(mostra) {

  if (mostra==true) {

    js_OpenJanelaIframe('',
                        'db_iframe_unidades',
                        'func_unidades.php?funcao_js=parent.js_mostraunidades1|sd02_i_codigo|descrdepto',
                        'Pesquisa',
                        true
                       );
  } else {

     if (document.form2.vc01_i_unidade.value != '') {

        js_OpenJanelaIframe('',
                            'db_iframe_unidades',
                            'func_unidades.php?pesquisa_chave='+document.form2.vc01_i_unidade.value+
                            '&funcao_js=parent.js_mostraunidades',
                            'Pesquisa',
                            false
                           );

     } else {
       document.form2.descrdepto.value = '';
     }
  }
}

function js_mostraunidades(chave,erro) {

  document.form2.descrdepto.value = chave;
  if (erro == true) {

    document.form2.vc01_i_unidade.focus();
    document.form2.vc01_i_unidade.value = '';

  }
}

function js_mostraunidades1(chave1,chave2) {

  document.form2.vc01_i_unidade.value = chave1;
  document.form2.descrdepto.value = chave2;
  db_iframe_unidades.hide();

}

function js_pesquisa() {

  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_vac_sala',
                      'func_vac_sala.php?funcao_js=parent.js_preenchepesquisa|vc01_i_codigo',
                      'Pesquisa',
                      true
                     );

}

function js_preenchepesquisa(chave) {

  db_iframe_vac_sala.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>