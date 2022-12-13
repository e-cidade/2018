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
$clrhfuncao->rotulo->label();

if ($db_opcao == 2) {
  $sTituloFormulario = "Alteração";  
} else if ($db_opcao == 3) {
  $sTituloFormulario = "Exclusão";
} else {
  $sTituloFormulario = "Cadastro";
}
?>
<form name="form1" method="post" action="">
<center>
  <fieldset style="width: 500px;">
    <legend style="font-weight: bold;">&nbsp;<?=$sTituloFormulario;?> de Cargos&nbsp;</legend>
    <table width="100%">
      <tr>
        <td width="100" nowrap title="<?=@$Trh37_funcao?>"><?=@$Lrh37_funcao?></td>
        <td>
          <?
            db_input('rh37_funcao', 10, $Irh37_funcao, true, 'text', $db_opcao, "")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Trh37_descr?>"><?=@$Lrh37_descr?></td>
        <td>
          <?
            db_input('rh37_descr', 44, $Irh37_descr, true, 'text', $db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Trh37_funcaogrupo?>">
          <? 
            db_ancora($Lrh37_funcaogrupo,'js_buscagrupo(true);',1); 
          ?>
        </td>
        <td>
          <?
            db_input('rh37_funcaogrupo'     , 10, $Irh37_funcaogrupo, true, 'text', $db_opcao, "onchange='js_buscagrupo(false);'");
            db_input('rh37_funcaogrupodescr', 30, '', true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Trh37_vagas?>"><?=@$Lrh37_vagas?></td>
        <td>
          <?
            db_input('rh37_vagas', 10, $Irh37_vagas, true, 'text', $db_opcao, "")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Trh37_cbo?>"><?=@$Lrh37_cbo?></td>
        <td>
          <?
            db_input('rh37_cbo', 10, $Irh37_cbo, true, 'text', $db_opcao, "")
          ?>
        </td>    
      </tr>
      <tr>
        <td nowrap title="<?=@$Trh37_class?>"><?=@$Lrh37_class?></td>
        <td>
          <?
            db_input('rh37_class', 10, $Irh37_class, true, 'text', $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Trh37_ativo?>"><?=@$Lrh37_ativo?></td>
        <td> 
          <?
            $aAtivo = array("t"=>"Sim","f"=>"Não");
            db_select('rh37_ativo', $aAtivo, true, $db_opcao,"");
          ?>
      </td>
    </tr>
    </table>
    <fieldset>
    <legend style="font-weight: bold;">&nbsp;Lei&nbsp;</legend>
      <?
        db_textarea('rh37_lei',5,60,$Irh37_lei,true,'text',$db_opcao,"");
      ?>
    </fieldset>
  </fieldset>
  </center>
  <br />
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
</form>

<script>

function js_buscagrupo(mostra) {
  if(mostra==true){
    var sUrlOpen = 'func_rhfuncaogrupo.php?funcao_js=parent.js_preencheGrupo|rh100_sequencial|rh100_descricao';
    js_OpenJanelaIframe('top.corpo', 'db_iframe_rhfuncao', sUrlOpen, 'Pesquisa', true);
  }else{
     if(document.form1.rh37_funcaogrupo.value != ''){
        var iFuncaoGrupo  = document.form1.rh37_funcaogrupo.value;
        var sUrlOpenGrupo = 'func_rhfuncaogrupo.php?pesquisa_chave='+iFuncaoGrupo+'&funcao_js=parent.js_mostrargrupos';
        js_OpenJanelaIframe('top.corpo', 'db_iframe_rhfuncao', sUrlOpenGrupo, 'Pesquisa', false);
     }else{
       document.form1.rh37_funcaogrupodescr.value = ''; 
     }
  }
}

function js_mostrargrupos(chave,erro) {
  document.form1.rh37_funcaogrupodescr.value   = chave; 
  if (erro==true) { 
    document.form1.rh37_funcaogrupo.focus(); 
    document.form1.rh37_funcaogrupo.value = ''; 
  }
}

function js_preencheGrupo(chave, descricao) {
  document.form1.rh37_funcaogrupo.value      = chave;
  document.form1.rh37_funcaogrupodescr.value = descricao;
  db_iframe_rhfuncao.hide();  
}


function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhfuncao','func_rhfuncao.php?funcao_js=parent.js_preenchepesquisa|rh37_funcao','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhfuncao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>