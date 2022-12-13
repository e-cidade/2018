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

//MODULO: orcamento
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clorcdotacaocontr->rotulo->label();
$clrotulo = new rotulocampo();
$clrotulo->label("o58_orgao");
$clrotulo->label("o58_orgao");
$clrotulo->label("o15_descr");
$clrotulo->label("o58_orgao");
$clrotulo->label("o58_orgao");
?>
<form name="form2" method="post" action="">
<table>
<tr>
<td>
<fieldset><legend><b>Contra-Partida</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To61_anousu?>">
      <?
       echo @$Lo61_anousu;
      ?>
    </td>
    <td> 
   <?
    db_input('o61_sequencial', 10, 0, true, 'hidden', 3);
    db_input('o61_anousu', 10, $Io61_anousu, true, 'text', 3);
   ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To61_coddot?>">
       <?
      echo @$Lo61_coddot;
      ?>
    </td>
    <td>
    <? 
       db_input('o61_coddot', 6, $Io61_coddot, true, 'text',3);
    ?>
      </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To61_codigo?>">
       <?
      db_ancora(@$Lo61_codigo, "js_pesquisao61_codigo(true);", $db_opcao);
      ?>
    </td>
    <td>
    <? 
     db_input('o61_codigo', 6, $Io61_codigo, true, 'text', $db_opcao, " onchange='js_pesquisao61_codigo(false);'");
     db_input('o15_descr', 30, $Io15_descr, true, 'text', 3, '');
    ?>
    </td>
  </tr>
</table>
</fieldset>
</td>
</tr>
</table>
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"") ?> >
</form>
  <?
  $db_opcao = 1;
  $chavepri = array ("o61_sequencial"     => @$o61_sequencial);
  $cliframe_alterar_excluir->chavepri      = $chavepri;
  $cliframe_alterar_excluir->sql           = $clorcdotacaocontr->sql_query_rec(null, null,"*", null,
                                             "o61_coddot = {$o61_coddot} and o61_anousu = {$o61_anousu}");
  
  $cliframe_alterar_excluir->campos        = "o61_sequencial,o61_coddot,o61_anousu,o61_codigo,o15_descr";
  $cliframe_alterar_excluir->legenda       = "lista";
  $cliframe_alterar_excluir->iframe_height = "370";
  $cliframe_alterar_excluir->iframe_width  = "100%";
  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
?>       
<script>
function js_pesquisao61_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcdotacaocontr','db_iframe_orctiporec','func_orctiporec.php?sFiltroTipo=2&funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_orcdotacaocontr','db_iframe_orctiporec','func_orctiporec.php?sFiltroTipo=2&pesquisa_chave='+$('o61_codigo').value+'&funcao_js=parent.js_mostraorctiporec','Pesquisa',false);
  }
}
function js_mostraorctiporec(chave,erro){
  $('o15_descr').value = chave; 
  if(erro==true){ 
    $('o61_codigo').focus(); 
    $('o61_codigo').value = ''; 
  }
}
function js_mostraorctiporec1(chave1,chave2){

  $('o61_codigo').value = chave1;
  $('o15_descr').value = chave2;
  db_iframe_orctiporec.hide();
  
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_orcdotacaocontr','db_iframe_orcdotacaocontr','func_orcdotacaocontr.php?funcao_js=parent.js_preenchepesquisa|o61_anousu|o61_coddot','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_orcdotacaocontr.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '" . basename($GLOBALS ["HTTP_SERVER_VARS"] ["PHP_SELF"]) . "?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>