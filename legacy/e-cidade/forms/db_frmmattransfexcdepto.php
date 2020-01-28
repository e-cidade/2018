<?
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

//MODULO: material
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clmatestoqueinimei->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("coddepto");
$clrotulo->label("descrdepto");
$clrotulo->label("m60_codmater");
$clrotulo->label("m60_descr");
$clrotulo->label("m70_codigo");
$clrotulo->label("m71_codmatestoque");
$clrotulo->label("m71_quant");
$clrotulo->label("m80_matestoqueitem");
$clrotulo->label("m80_obs");
$clrotulo->label("m80_codigo");
?>
<form name="form1" method="post" action="">
<BR><BR>
<fieldset><legend><b>Dados do lançamento</b></legend>
<table border="0">
  <tr>
    <td title="Código do lançamento" align="right">
      <strong>Código do lançamento:</strong>
    </td>
    <td align="left" nowrap colspan='3'>
       <? 
db_input('m80_codigo',10,$Im80_codigo,true,"text",3);
       ?>
    </td>
  </tr>
  <tr>
    <td title="Departamento origem" align="right">
      <strong>Departamento origem:</strong>
    </td>
    <td align="left" nowrap colspan='3'>
       <? 
db_input('coddepto',10,$Idescrdepto,true,"text",3,"","departamentoorigem");
       ?>
       <? 
db_input('descrdepto',43,$Im60_descr,true,"text",3,"","descrdepartamentoorigem");
       ?>
    </td>
  </tr>
  <tr>
    <td title="Departamento destino" align="right">
      <strong>Departamento destino:</strong>
    </td>
    <td align="left" nowrap colspan='3'>
       <? 
db_input('coddepto',10,$Idescrdepto,true,"text",3,"","departamentodestino");
       ?>
       <? 
db_input('descrdepto',43,$Im60_descr,true,"text",3,"","descrdepartamentodestino");
       ?>
    </td>
  </tr>
  <tr>
    <td title="<?=@$Tm80_obs?>" align="right">
      <?=@$Lm80_obs?>
    </td>
    <td align="left" nowrap colspan='3'>
       <? 
db_textarea('m80_obs',2,55,$Im80_obs,true,'text',3,"");
       ?>
    </td>
  </tr>
  </table>
</fieldset>
<fieldset><legend><b>Materiais</b></legend>
<table>
  <tr>
    <td align='center' colspan='4'><BR>
  <?
       $m80_codigo = (isset($m80_codigo)&&!empty($m80_codigo))?$m80_codigo:'null';
       $chavepri= array("m80_codigo"=>@$valores,"m82_codigo"=>@$m82_codigo);
       $cliframe_alterar_excluir->chavepri= $chavepri;
       $cliframe_alterar_excluir->sql     = $clmatestoqueinimei->sql_query_matestoque(null,"matestoqueinimei.m82_codigo,matestoqueini.m80_codigo,m60_codmater,m60_descr,sum(m82_quant) as m82_quant","m82_codigo"," matestoqueini.m80_codigo=$m80_codigo group by matestoqueinimei.m82_codigo,matestoqueini.m80_codigo,m60_codmater,m60_descr ");
       $cliframe_alterar_excluir->campos  = "m82_codigo,m60_codmater,m60_descr,m82_quant";
       $cliframe_alterar_excluir->legenda ="ITENS LANÇADOS";
       $cliframe_alterar_excluir->iframe_height ="190";
       $cliframe_alterar_excluir->iframe_width ="712";
       $cliframe_alterar_excluir->textocabec ="black";
       $cliframe_alterar_excluir->textocorpo ="black";
       $cliframe_alterar_excluir->fundocabec ="#999999";
       $cliframe_alterar_excluir->fundocorpo ="#cccccc";
       $cliframe_alterar_excluir->opcoes = 4;
       $cliframe_alterar_excluir->fieldset = false;
       $cliframe_alterar_excluir->iframe_alterar_excluir(1);//$db_opcao; 
       if(isset($confirma)){
         db_input('confirma',10,0,true,"hidden",3);
       }
  ?>
    </td>
  </tr>
</table>
</fieldset>
<table>
  <tr>
    <td colspan='4' align='center'>
      <input name='cancelar'  type='submit' id='db_opcao'  value="<?=(!isset($confirma)||(isset($confirma) && $confirma=="false")?'Cancelar transferência':'Confirmar transferência')?>" <?=($db_botao==false?"":"disabled")?>>
      <input name='pesquisar' type='button' id='pesquisar' value='Pesquisar' onclick='js_pesquisar();'>
    </td>
  </tr> 
</table>

</form>
<script>
function js_pesquisar(){
    js_OpenJanelaIframe('top.corpo','db_iframe_matestoquetransf','func_matestoquetransf.php?funcao_js=parent.js_preenchepesquisa|m83_matestoqueini&chave_m80_codtipo=7&<?=(isset($confirma)?'+chave_m83_coddepto='.db_getsession("DB_coddepto"):'chave_m80_coddepto='.db_getsession("DB_coddepto"))?>','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_matestoquetransf.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave".(isset($confirma)?"+'&confirma=$confirma'":"");
  }
  ?>
}
</script>