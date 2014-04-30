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


//MODULO: pessoal
$clrhlocaltrab->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh86_criteriorateio");
$clrotulo->label("cc08_descricao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh55_codigo?>">
      <?=@$Lrh55_codigo?>
    </td>
    <td> 
      <?
      db_input('rh55_codigo',5,$Irh55_codigo,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <?
    $retorno_cfpess = db_sel_cfpess(db_anofolha(),db_mesfolha(),"r11_localtrab");
    if(isset($r11_localtrab) && trim($r11_localtrab) != ""){
      $cldb_estrut->autocompletar = true;
      $cldb_estrut->mascara = true;
      $cldb_estrut->reload  = false;
      $cldb_estrut->input   = false;
      $cldb_estrut->size    = 22;
      $cldb_estrut->nome    = "rh55_estrut";
      $opcaoestrut = 1;
      if($db_opcao!=1){
        $opcaoestrut = 3;
      }
      $cldb_estrut->db_opcao= $opcaoestrut;
      $cldb_estrut->db_mascara("$r11_localtrab");
    }else{
      $erro_msg = 'Estrutural de locais de trabalho não configurados no cfpess para o ano / mês '.db_anofolha().' / '.db_mesfolha().'. Verifique!';
      $sem_parametro_configurado = true;
    }
    ?>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh55_descr?>">
      <?=@$Lrh55_descr?>
    </td>
    <td> 
      <?
      db_input('rh55_descr',40,$Irh55_descr,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh86_criteriorateio?>">
       <?
       db_ancora(@$Lrh86_criteriorateio,"js_adicionaCentroCusto('');",$db_opcao); 
       ?>
    </td>
    <td> 
      <?
      db_input('rh86_criteriorateio',10,$Irh86_criteriorateio,true,'text',$db_opcao," onchange='js_adicionaCentroCusto(this.value);'");
      db_input('cc08_descricao',30,$Icc08_descricao,true,'text',3,'')
       ?>
    </td>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_adicionaCentroCusto(iValor) {
 
  var iOrigem  = 3;
  var sUrl     = 'iOrigem=&iNumEmp=&iCodigoDaLinha=0';
  var lMostrar = true;
  if (iValor != "") {
  
     sUrl += "&iCodigoCriterio="+iValor;
     var lMostrar = false;
  }
  js_OpenJanelaIframe('',
                      'db_iframe_centroCusto',
                      'cus4_escolhercentroCusto.php?'+sUrl,
                      'Centro de Custos',
                      lMostrar,
                      '25',
                      '1',
                      (document.body.scrollWidth-10),
                      (document.body.scrollHeight-100)
                     );
  
   
}

function js_completaCustos(iCodigo, iCriterio, iDescr) {
  
  $('rh86_criteriorateio').value = iCriterio;
  $('cc08_descricao').value  = iDescr;
  db_iframe_centroCusto.hide();

}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhlocaltrab','func_rhlocaltrab.php?funcao_js=parent.js_preenchepesquisa|rh55_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhlocaltrab.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_validaCadastro() {

  var iValidaCentroCusto= <?=$iTipoControleCustos?>;
  
  if (iValidaCentroCusto == 1 && $F('rh86_criteriorateio') == '') {
    
    if (!confirm('Centro de Custo não informado.\nDeseja Continuar?')) {
      return false;
    }
    
  } else if (iValidaCentroCusto == 2 && $F('rh86_criteriorateio') == '') {
     if (!confirm('Centro de Custo não informado.')) {
       return false;
    }
  }
  
}
<?
  if ($db_opcao == 1 || $db_opcao == 2) {
    echo "\$('db_opcao').onclick = js_validaCadastro;\n";    
  }
?>
</script>