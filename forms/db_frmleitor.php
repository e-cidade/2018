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

//MODULO: biblioteca
$clleitor->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("bi16_leitor");
$clrotulo->label("bi07_nome");
?>
<form name="form1" method="post" action="">
  <center>
    <table border="0" width="90%">
      <tr>
        <td nowrap title="<?=@$Tbi10_codigo?>">
          <?=@$Lbi10_codigo?>
        </td>
        <td>
          <?db_input('bi10_codigo',10,$Ibi10_codigo,true,'text',3,"")?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tbi10_codigo?>">
         <?db_ancora(@$Lbi16_leitor,"js_pesquisabi10_codigo(true);",$db_opcao1);?>
        </td>
        <td>
          <?db_input('codigo', 10, @$codigo, true, 'text', 3, "")?>
          <?db_input('nome', 50, @$nome, true, 'text', 3, '')?>
          <?db_input('tipo', 12, "", true, 'text', 3, '')?>
          <?db_input('seq', 12, "", true, 'hidden', 3, '')?>
          <?if ($db_opcao == 1) {?>
          <input name="novo_leitor" type="button" id="novo_leitor" value="Novo Leitor" onclick="js_novo_leitor();" >
          <?}?>
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center">
          <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
                 type=<?=$db_opcao==2||$db_opcao==22?"button":"submit"?>  
                 id="db_opcao" 
                 value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
                 <?=($db_botao==false?"disabled":"")?> 
                 <?=$db_opcao==2||$db_opcao==22?"onClick='js_carregaCidadao();'":""?>>
          <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
          <input name="novo" type="button" id="novo" value="Novo Registro" onclick="js_novo()" <?=$db_opcao==1?"disabled":""?>>
        </td>
      </tr>
    </table>
  </center>
</form>
<script>
function js_pesquisabi10_codigo(mostra) {
  
  if (mostra == true){
    js_OpenJanelaIframe('','db_iframe_leitorcadastro','func_leitorcadastro.php?funcao_js=parent.js_mostraleitor|dl_codigo|dl_nome|dl_tipo','Pesquisa',true);
  }
}

function js_mostraleitor(chave1,chave2,chave3) {
  
  document.form1.codigo.value = chave1;
  document.form1.nome.value   = chave2;
  document.form1.tipo.value   = chave3;
  db_iframe_leitorcadastro.hide();
  document.getElementById("fieldleitor").style.visibility = "visible";
}

function js_pesquisa() {
  js_OpenJanelaIframe('','db_iframe_leitor','func_leitorcidadao.php?funcao_js=parent.js_preenchepesquisa|bi10_codigo|ov02_sequencial','Pesquisa',true);
}

function js_novo_leitor() {
  js_OpenJanelaIframe('','db_iframe_alteradados','ouv1_cidadao001.php?lOrigemLeitor=true&leitor','Novo Leitor',true);
}

function js_preenchepesquisa(chave, chave2) {

  db_iframe_leitor.hide();
  location.href = 'bib1_leitor002.php?chavepesquisa='+chave;
}

function js_novo() {
  parent.location.href="bib1_leitor000.php?opcao=1";
}

function js_carregaCidadao() {

  js_OpenJanelaIframe('', 
      'db_iframe_alteradados',
      'ouv1_cidadao002.php?lOrigemLeitor=true&chavepesquisa='+$('codigo').value,
      'Dados Cidadão',
      true
     );
}

<?if ($db_opcao == 2 || $db_opcao == 3){?>
    document.getElementById("fieldleitor").style.visibility = "visible";
<?}?>
</script>