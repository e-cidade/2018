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

$clsuspensaofinaliza->rotulo->label();
?>
<form name="form1" method="post" action="">
  <fieldset>
    <legend align="center">
      <b>Finaliza Suspensão</b>
    </legend>
    <table border="0">
      <tr>
        <td>
          <?
			db_ancora($Lar19_suspensao,"js_pesquisaar19_suspensao(true)",$db_opcao);
          ?>
        </td>
        <td>
          <?
			db_input("ar19_suspensao",10,$Iar19_suspensao,true,"text",$db_opcao,"onChange='js_pesquisaar19_suspensao(false)'"); 
          ?>
        </td>
      </tr>    
      <tr>
        <td>
          <b>Status Débito:</b>
        </td>
        <td>
          <?
            $aStatusDebito = array("c"=>"Cancelar","r"=>"Reativar");
			db_select("statusDebito",$aStatusDebito,true,1,""); 
          ?>
        </td>
      </tr> 
	  <tr>
	    <td nowrap title="<?=@$Tar19_obs?>" width='110px;'>
	      <?=@$Lar19_obs?>
	    </td>
	    <td> 
	      <?
		    db_textarea('ar19_obs',5,51,$Iar19_obs,true,'text',$db_opcao,"");
	      ?>
	    </td>
	  </tr>       
     </table>
  </fieldset>
<input name="finalizar" type="submit" id="db_opcao" value="Finalizar">
</form>
<script>

function js_pesquisaar19_suspensao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_suspensao','func_suspensao.php?situacao=1&funcao_js=parent.js_mostrasuspensao1|ar18_sequencial','Pesquisa',true);
  }else{
     if(document.form1.ar19_suspensao.value != ''){
        js_OpenJanelaIframe('','db_iframe_suspensao','func_suspensao.php?pesquisa_chave='+document.form1.ar19_suspensao.value+'&funcao_js=parent.js_mostrasuspensao','Pesquisa',false);
     }
  }
}

function js_mostrasuspensao(iCod,lErro){

  if(lErro==true){ 
    document.form1.ar19_suspensao.focus(); 
    document.form1.ar19_suspensao.value = ''; 
  } else {
  	document.form1.ar19_suspensao.value = iCod;
  }
  
  db_iframe_suspensao.hide();
  
}

function js_mostrasuspensao1(iSeq){

  document.form1.ar19_suspensao.value = iSeq;
  
  db_iframe_suspensao.hide();
  
}

</script>