<?
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

//MODULO: patrim
include("classes/db_bensbaix_classe.php");
global $t51_motivo;
$clMotbaixa     = new cl_bensbaix();

$clbensmotbaixa->rotulo->label();
if(isset($incluir) || isset($alterar) || isset($excluir)){
  $t51_motivo="";
  $t51_descr="";
}

?>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Cadastros - Tipo de Baixa</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tt51_motivo?>">
          <?=@$Lt51_motivo?>
        </td>
        <td> 
          <?
            db_input('t51_motivo',8,$It51_motivo,true,'text',3)
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt51_descr?>">
            <?=@$Lt51_descr?>
        </td>
        <td> 
          <?
            db_input('t51_descr',40,$It51_descr,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
<?
  /*
   * T.44208
   * Controle para o botao alterar,excluir,incluir
   * Patrimonio -> Cadastro -> Tipos de Baixa 
   * Se tiver algum Bem vinculado ao tipo selecionado
   * ou o Usuario nao for DBSELLER
   * Nao sera permitido alteração 
   * Se tiver vinculo nenhum usuario sera possivel excluir
   */
  $clMotbaixa->sql_record($clMotbaixa->sql_query("","t55_motivo",""," t55_motivo = $t51_motivo "));
  if ($clMotbaixa->numrows != 0 && $db_opcao != 3 && $db_opcao != 33 ) {
    
      if (db_getsession("DB_id_usuario") !=1 ) { ?>
  
          <input onclick="alert(_M('patrimonial.patrimonio.db_frmbensmotbaixa.bens_vinculados'));" name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="button" id="db_opcao" 
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >                   
<?    }else {?> 

          <input onclick="return confirm(_M('patrimonial.patrimonio.db_frmbensmotbaixa.baixa_vinculada_bens'));" name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" 
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >             
<?    }
  }else if ($db_opcao == 1) {?>
  
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" 
      value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >     
<?}else if ($clMotbaixa->numrows != 0 && $db_opcao == 3 || $db_opcao == 33 ) { ?> 
   
   <input onclick="alert(_M('patrimonial.patrimonio.db_frmbensmotbaixa.bens_vinculados'));" name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="button" id="db_opcao" 
     value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >      
<?}else {?>

    <input  name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" 
      value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?}?> 

<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_bensmotbaixa','func_bensmotbaixa.php?funcao_js=parent.js_preenchepesquisa|t51_motivo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_bensmotbaixa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
<script>

$("t51_motivo").addClassName("field-size2");
$("t51_descr").addClassName("field-size9");

</script>