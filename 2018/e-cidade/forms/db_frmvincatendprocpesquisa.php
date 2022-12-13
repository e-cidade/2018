<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: ouvidoria >> procedimentos >> vincular atendimento a Procressos

$clprotprocesso->rotulo->label("p58_codproc");
$clprotprocesso->rotulo->label("p58_requer");
$clouvidoriaatendimento->rotulo->label("ov01_sequencial");
$clouvidoriaatendimento->rotulo->label("ov01_requerente");

$sqlProcessoOuvidoria = " select ov09_sequencial, 
                                 ov01_sequencial, 
                                 fc_numeroouvidoria(ov01_sequencial) as ov01_numero, 
                                 ov01_requerente, 
                                 ov01_solicitacao, 
                                 ov01_dataatend 
                            from processoouvidoria 
                                 inner join ouvidoriaatendimento on processoouvidoria.ov09_ouvidoriaatendimento = ouvidoriaatendimento.ov01_sequencial 
                           where ov09_protprocesso = {$iP58_CodProc} ";

$rsProcessoOuvidoria = pg_query($sqlProcessoOuvidoria);
$iProcessoOuvidoria  = pg_num_rows($rsProcessoOuvidoria);

if ($iProcessoOuvidoria > 0) {
	$oProcessoOuvidoria = db_utils::fieldsMemory($rsProcessoOuvidoria,0);
}

$rsConsultaTipo = $clprotprocesso->sql_record($clprotprocesso->sql_query_file($p58_codproc,'p58_codigo'));
db_fieldsmemory($rsConsultaTipo,0);


?>
<form name="form1" method="post" action="">
<center>
<table border="0" style="margin-top: 20px;">
  <tr align="center">
    <td colspan="2">
     <fieldset>
     <legend><b>Vincular Atendimento a Processo</b></legend>
	  <table>
	    <tr>
		  <td nowrap title="<?=$Tp58_codproc?>" align="right">
		     <b>Processo:</b>
		  </td>
		  <td> 
		  <?
		    db_input("p58_codproc",10,$Ip58_codproc,true,'text',3,'');
		    db_input("p58_requer",50,$Ip58_requer,true,'text',3,'');
		    db_input("p58_codigo",50,'',true,'hidden',3,'');
		  ?>
		  </td>
	    </tr>
	    <tr>
		  <td nowrap title="<?=$Tov01_sequencial?>" align="right">
		  <?
		    db_ancora('<b>Atendimento:</b>',"js_pesquisaov01_sequencial(true);","");
		  ?>
		  </td>
		  <td> 
		  <?
		    db_input("ov01_sequencial",10,$Iov01_sequencial,true,'text',""," onchange='js_pesquisaov01_sequencial(false);'");
		    db_input("ov01_requerente",50,$Iov01_requerente,true,'text',3,'');
		  ?>
		  </td>
	    </tr>	    
      </table>
	  <table>
	    <tr>
		  <td> 
         <input type="submit" name="incluir" id="incluir" value="Incluir" OnClick="return js_validar();">
         <input type="button" name="voltar"  id="voltar"  value="Voltar"  OnClick="return js_voltar();">
		  </td>
	    </tr>
      </table>
     </fieldset>
      <br>
      <table>
  	  <tr>
	    <td valign="top"  align="center">  
	      <? 	      
		 	$chavepri                                = array("ov09_sequencial"=>@$oProcessoOuvidoria->ov09_sequencial);
		 	$cliframe_alterar_excluir->chavepri      = $chavepri;
		 	$cliframe_alterar_excluir->sql           = $sqlProcessoOuvidoria;
		 	$cliframe_alterar_excluir->campos        = "ov01_sequencial, ov01_numero, ov01_requerente,ov01_solicitacao,ov01_dataatend";
		 	$cliframe_alterar_excluir->legenda       = "Lista de Atendimentos";
		 	$cliframe_alterar_excluir->opcoes        = 3;
		 	$cliframe_alterar_excluir->iframe_height = "160";
		 	$cliframe_alterar_excluir->iframe_width  = "700";
		 	$cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
	      ?>
    	</td>
   	  </tr>
 	</table>
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_pesquisaov01_sequencial(mostra){
  var mostra = mostra;

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_ouvidoriaatendimento',
                        'func_ouvidoriaatendimentoprocesso.php?proc=false&tipo='+document.form1.p58_codigo.value+'&funcao_js=parent.js_mostra1|ov01_sequencial|ov01_requerente&situacao=1',
                        'Pesquisa',true);
  }else{
     if(document.form1.ov01_sequencial.value != ''){ 
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_ouvidoriaatendimento',
                            'func_ouvidoriaatendimentoprocesso.php?proc=false&requer=true&tipo='+document.form1.p58_codigo.value+'&pesquisa_chave='+document.form1.ov01_sequencial.value+'&funcao_js=parent.js_mostra&situacao=1',
                            'Pesquisa',false);
     }else{
         document.form1.ov01_sequencial.value = '';
         document.form1.ov01_requerente.value = ''; 
     }
  }
}

function js_mostra(chave1,chave2,erro){ 
  if(erro==true){ 
    document.form1.ov01_sequencial.focus(); 
    document.form1.ov01_sequencial.value  = '';
    document.form1.ov01_requerente.value  = ''; 
  } else {
    document.form1.ov01_requerente.value  = chave2;  
  }
}

function js_mostra1(chave1,chave2){
  document.form1.ov01_sequencial.value  = chave1;
  document.form1.ov01_requerente.value  = chave2;
  db_iframe_ouvidoriaatendimento.hide();
}

function js_validar(){
  var p58_codproc     = document.getElementById('p58_codproc').value;
  var p58_requer      = document.getElementById('p58_requer').value;
  var ov01_sequencial = document.getElementById('ov01_sequencial').value;
  var ov01_requerente = document.getElementById('ov01_requerente').value;
  
  if(p58_codproc == "" || p58_requer ==""){
    alert('Processo não informado!');
    return false;
  } else if(ov01_sequencial == "" || ov01_requerente == ""){
    alert('Atendimento não informado!');
    return false;  
  }
}

function js_voltar(){
  document.location.href = 'ouv4_ouvidoriavincatendprocesso001.php';
}


function js_arquivarProcesso(iCodProc){
  
  js_divCarregando('Aguarde, Arquivando Processo...','msgBox');
  
  var sQuery  = 'sMethod=arquivarProcesso';
      sQuery += '&iCodProcesso='+iCodProc;
      sQuery += "&sMotivo=Processo Finalizado, Atendimento vinculado a outro processo";
      
  var oAjax   = new Ajax.Request( 'ouv1_retornocliente.RPC.php', {
                                          method: 'post', 
                                          parameters: sQuery, 
                                          onComplete: js_retornoArquivarProcesso
                                        }
                                );  
}
  

function js_retornoArquivarProcesso(oAjax){
  
  js_removeObj("msgBox");
  
  var aRetorno = eval("("+oAjax.responseText+")");
  var sExpReg  = new RegExp('\\\\n','g');
    
  alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
  
  if ( aRetorno.lErro ) {
    return false;
  } else {
    js_voltar();
  }
        
}  


</script>