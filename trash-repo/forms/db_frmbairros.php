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

$clbairro->rotulo->label();
?>
<script>
    function js_verificabairros(){
  	  VisualizacaoNomeBairros.jan.location.href = 'func_bairros.php?funcao_js=parent.js_atualizabairro|0|1&nomeBairro=' + document.form1.j13_descr.value;
      VisualizacaoNomeBairros.show();
	  VisualizacaoNomeBairros.focus();	  
    }
	function js_atualizabairro(){
	  var args = js_atualizarua.arguments;
	  document.form1.j13_codi.value = args[0];
	  document.form1.j13_descr.value = args[1];
      VisualizacaoNome.hide();
	}
</script>
<form name="form1" method="post" action="">
  <table width="85%" height="119" border="0">
    <tr> 
      <td width="15%" title="<?=$Tj13_codi?>" nowrap> 
        <?=$Lj13_codi?>
      </td>
      <td width="85%"> 
          <?
		db_input('j13_codi',5,$Ij13_codi,true,'text',3);
		  ?>
      </td>
    </tr>
    <tr>
      <td title="<?=$Tj13_descr?>" nowrap>
        <?=$Lj13_descr?>
      </td>
      <td>
        <?
		  db_input('j13_descr',41,$Ij13_descr,true,'text',$db_opcao);
		  ?>
      </td>
    </tr>
    <tr>
      <td title="<?=$Tj13_codant?>" nowrap>
        <?=$Lj13_codant?>
      </td>
      <td>
        <?
		  db_input('j13_codant',16,$Ij13_codant,true,'text',$db_opcao);
		  ?>
      </td>
    </tr>
    <tr valign="middle">
      <td colspan="2"><input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_func_bairros();">
        <input name="volt" type="button" value="Voltar" onclick="js_volt();">  
    </tr>
  </table>
</form>
<script>
function js_volt(){
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>';
}
function js_preenche(chave){
  func_bairros.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
function js_func_bairros(){
  func_bairros.jan.location.href = 'func_bairro.php?nomeBairro='+document.form1.j13_descr.value+'&funcao_js=parent.js_preenche|0';
  func_bairros.mostraMsg();
  func_bairros.show();
  func_bairros.focus();
}
</script>
<?
  $func_bairros = new janela("func_bairros","");
  $func_bairros ->posX=1;
  $func_bairros ->posY=20;
  $func_bairros ->largura=780;
  $func_bairros ->altura=430;
  $func_bairros ->titulo="Pesquisa Nomes";
  $func_bairros ->iniciarVisivel = false;
  $func_bairros ->mostrar();
?>