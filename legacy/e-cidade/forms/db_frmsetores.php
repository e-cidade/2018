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

$clsetor->rotulo->label();
?>
<form name="form1" method="post" action="">
  <table width="84%" height="119" border="0">
    <tr>
      <td nowrap width="23%" title="<?=$Tj30_codi?>">
        <?=$Lj30_codi?>
      </td>
      <td>
        <?
	if(isset($chavepesquisa)&&($db_opcao!=1)){
		db_input('j30_codi',5,$Ij30_codi,true,'text',3);
	}else{
		db_input('j30_codi',5,$Ij30_codi,true,'text',$db_opcao);
	}
		  ?>
      </td>
    </tr>
    <tr> 
      <td nowrap title="<?=$Tj30_descr?>"> 
        <?=$Lj30_descr?>
      </td>
      <td> 
        <?
		  db_input('j30_descr',41,$Ij30_descr,true,'text',$db_opcao);
		  ?>
      </td>
    </tr>
    <tr> 
      <td nowrap title="<?=$Tj30_alipre?>"> 
        <?=$Lj30_alipre?>
      </td>
      <td> 
        <?
		  db_input('j30_alipre',16,$Ij30_alipre,true,'text',$db_opcao);
		  ?>
      </td>
    </tr>
    <tr> 
      <td nowrap title="<?=$Tj30_aliter?>"> 
        <?=$Lj30_aliter?>
      </td>
      <td> 
        <?
		  db_input('j30_aliter',16,$Ij30_aliter,true,'text',$db_opcao);
		  ?>
      </td>
    </tr>
    <tr valign="middle"> 
      <td colspan="2"> <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> > 
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_func_nome();"> 
      </td>
    </tr>
  </table>

</form>
<script>
function js_preenche(chave){
  func_setores.hide();
//  alert('<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave);
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
function js_func_nome(){
  func_setores.jan.location.href = 'func_setores.php?nomeSetor='+document.form1.j30_descr.value+'&funcao_js=parent.js_preenche|0';
  func_setores.mostraMsg();
  func_setores.show();
  func_setores.focus();
}
</script>
<?
  $func_setores = new janela("func_setores","");
  $func_setores ->posX=1;
  $func_setores ->posY=20;
  $func_setores ->largura=785;
  $func_setores ->altura=430;
  $func_setores ->titulo="Pesquisa Setores";
  $func_setores ->iniciarVisivel = false;
  $func_setores ->mostrar();
?>