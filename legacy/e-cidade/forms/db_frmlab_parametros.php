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

//MODULO: TFD
$cllab_parametros->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tla49_i_codigo?>">
       <?=@$Lla49_i_codigo?>
    </td>
    <td> 
    <?db_input('la49_i_codigo',10,$Ila49_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla49_c_estrutural?>">
       <?=@$Lla49_c_estrutural?>
    </td>
    <td> 
    <?db_input('la49_c_estrutural',40,$Ila49_c_estrutural,true,'text',$db_opcao1,"onchange='js_laboratorio();'")?>   
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla49_i_exameduplo?>">
      <?=@$Lla49_i_exameduplo?>
    </td>
    <td>
      <?
      $aX = array('2'=>'NÃO','1'=>'SIM');
      db_select('la49_i_exameduplo', $aX, true, $db_opcao, "");
      ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_lab_parametros','func_lab_parametros.php?funcao_js=parent.js_preenchepesquisa|la49_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_parametros.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_laboratorio(){
  lFlag = false;
  iNivel = 1;
  str=document.form1.la49_c_estrutural.value;

  if(str==''){
    alert("Preencha um estrutural");
    return false;

  }
  if(str[0]=='.'){
	alert("nao pode comecar");
    return false;
  }	 
  if(str[str.length-1]=='.'){
	alert("nao pode comecar 00");
    return false;
  }
  for(i = 0; i < str.length; i++) {
	if(str[i]!='0' && str[i]!=".") {
      alert("Digite um estrutural válido"+str[i]);
	   return false;
	}
	if(str[i] == '.') {
	  if(lFlag){
        alert("pontos");
	  }
	  lFlag = true;
	  iNivel++;
	  continue;
	}
    lFlag = false;
  }
  if(iNivel<2){
	alert("nivel");
    return false;
  }
  return true;

}
</script>