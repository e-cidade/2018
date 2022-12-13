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
$clpactovalorsaldo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o87_pactotipoitem");
$clrotulo->label("104_descricao");
?>
<form name="form1" method="post" action="">
<center>
<table>
<tr>
<td>
<fieldset>
<legend><b>Planejamento de Gastos</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To103_pactovalor?>">
       <?
       db_input('o103_sequencial',10,$Io103_sequencial,true,'hidden',$db_opcao,"");
       db_ancora(@$Lo103_pactovalor,"js_pesquisao103_pactovalor(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o103_pactovalor',10,$Io103_pactovalor,true,'text',$db_opcao," onchange='js_pesquisao103_pactovalor(false);'")
?>
       <?
db_input('o109_descricao',40,$Io87_pactotipoitem,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To103_anousu?>">
       <?=@$Lo103_anousu?>
    </td>
    <td> 
<?
$o103_anousu = db_getsession('DB_anousu');
db_input('o103_anousu',10,$Io103_anousu,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap >
       <b>Tipo Planejamento:</b>
    </td>
    <td> 
    <?
    $aTipos = array(
                     1 => "Planejamento",
                     3 => "CP 100% Reconhecida",
                   );
    db_select('o103_pactovalorsaldotipo', $aTipos,true, $db_opcao);
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To103_mesusu?>">
       <?=@$Lo103_mesusu?>
    </td>
    <td> 
<?
db_input('o103_mesusu',10,$Io103_mesusu,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To103_valor?>">
       <?=@$Lo103_valor?>
    </td>
    <td> 
<?
db_input('o103_valor',10,$Io103_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </fieldset>
  </td>
  </tr>
  </table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</center>
</form>
<script>

function js_pesquisao103_pactovalor(mostra) {

  var sFiltro  = "&programa=1&projeto=1";
  if(mostra==true){
  
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_pactovalor',
                        'func_pactovalor.php?funcao_js=parent.js_mostrapactovalor1|o87_sequencial|dl_item'+sFiltro,
                        'Pesquisa',
                        true);
  }else{
     if(document.form1.o103_pactovalor.value != ''){ 
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_pactovalor',
                            'func_pactovalor.php?pesquisa_chave='+document.form1.o103_pactovalor.value+
                            '&funcao_js=parent.js_mostrapactovalor'+sFiltro,'Pesquisa'
                            ,false);
     }else{
       document.form1.o109_descricao.value = ''; 
     }
  }
}
function js_mostrapactovalor(chave,erro){

  document.form1.o109_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o103_pactovalor.focus(); 
    document.form1.o103_pactovalor.value = ''; 
  }
  
}

function js_mostrapactovalor1(chave1,chave2) {

  document.form1.o103_pactovalor.value = chave1;
  document.form1.o109_descricao.value = chave2;
  db_iframe_pactovalor.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_pactovalorsaldo',
                      'func_pactovalorsaldo.php?funcao_js=parent.js_preenchepesquisa|o103_sequencial&iTipo=1,3',
                      'Pesquisa Valores Lançados',true);
}
function js_preenchepesquisa(chave){
  db_iframe_pactovalorsaldo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>