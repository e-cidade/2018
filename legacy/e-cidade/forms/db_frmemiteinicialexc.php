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
?>
<form class="container" name="form1" method="post" action="">

<fieldset>
 <legend>Exclusão de Inicial</legend>
 
  <fieldset class="separator"> 
   <legend>Intervalo de Iniciais</legend>
	 <table class="form-container">
		 <tr> 
       <td  title="<?=@$Tv50_inicial?>">
				 <?
					db_ancora("<b>Inicial</b>",' js_inicialini(true); ',$opcao);
				 ?>
       </td>
       <td>
				 <?
					db_input('v50_inicialini',10,$Iv50_inicial,true,'text',$opcao,"onchange='js_inicialini(false)'");
				 ?>
       </td>
			 <td  title="<?=@$Tv50_inicial?>">
				 <?
					db_ancora("<b> à </b>",' js_inicialfim(true); ',$opcao);
				 ?>
       </td>
       <td>
				 <?
				  db_input('v50_inicialfim',10,$Iv50_inicial,true,'text',$opcao,"onchange='js_inicialfim(false)'");
				 ?>
       </td>
     </tr> 
    </table> 
  </fieldset> 
  
<? if (isset($v50_inicialini)) {  ?>

  <fieldset class="separator">
   <legend><b>Certidões Emitidas</b></legend>
		<?
			$rsInicialCert      = $clinicialcert->sql_record($clinicialcert->sql_query_file("",
			                                                                                "",
			                                                                                "v51_certidao as certidao",
			                                                                                "",
			                                                                                "v51_inicial between $v50_inicialini and $v50_inicialfim"));
			$iLinhasInicialCert = $clinicialcert->numrows;

			$iControleColunas = -1;
			
			echo "<table>";
			echo "<tr>"; 

			for ($iInd=0;$iInd<$iLinhasInicialCert;$iInd++) {
				$oDadosInicialCert = db_utils::fieldsMemory($rsInicialCert,$iInd);

				if ($iControleColunas > 8) {
					echo "</tr>"; 
					echo "<tr>";
					$iControleColunas = 0;
				} else {
					$iControleColunas++;
				}
								
				$idCertidao="certid".$iInd;
				$sInput = ""; 
				$sInput .= "<td width='40px;'> ";
				$sInput .= "	<input type=\"checkbox\" name=\"certid{$iInd}\" value=\"{$oDadosInicialCert->certidao}\" checked ";  
				$sInput .= " disabled ";  
				$sInput .= ">\n ";
				$sInput .="	<input type=\"hidden\"   name=\"veri_certid{$iInd}\" value=\"{$oDadosInicialCert->certidao}\"> {$oDadosInicialCert->certidao} ";
				
				echo $sInput;
				echo "</td>"; 
			} 
				
			echo "</tr>";
			echo "</table>"; 
		?>
  </fieldset>    
   
<? } ?>

   <fieldset id="fieldProcessamento" style="display: none;">
    <legend><b>Andamento do processamento</b></legend>
     <table>
       <tr>
	       <td>
	         <input name="termometro" style='background: transparent' id="termometro" type="text" value="" size=50>
	       </td>
	     </tr>   
     </table>
   </fieldset>

</fieldset>
   
<br>
      
<? if (isset($v50_inicialini)) { ?> 
   <input name="excluir"  type="submit" id="db_opcao" value="Excluir" onclick="return js_mostraProcessamento();"  <?=($botao==3?'disabled':'')?>>
   <input name="retornar" type="button" id="retornar" value="Retornar" onclick="js_retorno();" >
<? } else {?>
   <input name="processar" type="button" id="processar" value="Processar" onclick="js_processar();" >
<? } ?>
</form>
<script>
function js_retorno(){
  location.href = "jur1_emiteinicial003.php";
}

function js_processar() {
  if (document.form1.v50_inicialini.value == "") {
	  
    alert(_M('tributario.juridico.db_frmemiteinicialexc.informe_inicial'));
    return false;
  }
    
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?inicialini="+document.form1.v50_inicialini.value+"&inicialfim="+document.form1.v50_inicialfim.value;
}

function js_inicialini(mostra){
  var inicial=document.form1.v50_inicialini.value;
  if(mostra==true){
    db_iframe.jan.location.href = 'func_inicial.php?funcao_js=parent.js_mostrainicialini|0|2';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_inicial.php?pesquisa_chave='+inicial+'&funcao_js=parent.js_mostrainicialini1';
  }
}

function js_mostrainicialini(chave1,chave2){
  document.form1.v50_inicialini.value = chave1;
  document.form1.v50_inicialfim.value = chave1;
  db_iframe.hide();
}

function js_mostrainicialini1(chave,erro){
  if(erro==true){ 
    document.form1.v50_inicialini.focus(); 
  }
  document.form1.v50_inicialfim.value = chave;
}

function js_inicialfim(mostra){
  var inicial=document.form1.v50_inicialfim.value;
  if(mostra==true){
    db_iframe.jan.location.href = 'func_inicial.php?funcao_js=parent.js_mostrainicialfim|0|2';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_inicial.php?pesquisa_chave='+inicial+'&funcao_js=parent.js_mostrainicialfim1';
  }
}

function js_mostrainicialfim(chave1,chave2){
  document.form1.v50_inicialfim.value = chave1;
  db_iframe.hide();
}

function js_mostrainicialfim1(chave,erro){
  if(erro==true){ 
    document.form1.v50_inicialfim.focus(); 
  }
}

function js_mostraProcessamento(){
  
  document.getElementById("fieldProcessamento").style.display = ''; 
  return true;
  
}

function termo(qual,total){
  document.getElementById('termometro').innerHTML='processando registro... '+qual+' de '+total;
}
</script>
<script>

$("v50_inicialini").addClassName("field-size2");
$("v50_inicialfim").addClassName("field-size2");

</script>