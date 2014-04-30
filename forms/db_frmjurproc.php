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
?>
<center>
<form name="form1" method="post">
<table width="80%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="14%"><b>C&oacute;digo:</b></td>
    <td width="33%">
	  <input type="text" name="v50_codigo" value="<?=@$v50_codigo?>" size="6" maxlength="6" readonly>
    </td>
    <td width="17%"><b>R&eacute;u:</b></td>
    <td width="36%"> 
	  <input type="text" name="v50_reu" value="<?=@$v50_reu?>" size="40" maxlength="40"> 
    </td>
  </tr>
  <tr> 
    <td width="14%"><b>Num. Processo:</b></td>
    <td width="33%" nowrap> 
	  <input type="text" name="v50_numero" value="<?=@$v50_numero?>" size="30" maxlength="30"> 
    </td>
    <td width="17%"><b>Advogado:</b></td>
    <td width="36%"> 
	  <input type="text" name="v50_advoga" value="<?=@$v50_advoga?>" size="40" maxlength="40"> 
    </td>
  </tr>
  <tr> 
        <td width="14%"> 
          <?
	  db_label_blur('tipojur','tipo','tipo','tipodescr');
	?>
        </td>
    <td width="33%"> 
	<?
	  db_text_blur('tipojur','tipo','tipodescr',5,10,$v50_tipo,$v50_tipo);
	  db_text_blur('tipojur','tipodescr','tipo',15,15,$tipodescr,$tipodescr);	
	?>
    </td>
    <td width="17%"><b>Valor R$:</b></td>
    <td width="36%"> 
	  <input type="text" name="v50_valor" value="<?=@$v50_valor?>" size="15" maxlength="15"> 
    </td>
  </tr>
  <tr> 
        <td width="14%">
		<?
	      db_label_blur('localiza','localização','localizacao','localizacaodescr');	  
	    ?>
		</td>
    <td width="33%">
	<?
	  db_text_blur('localiza','localizacao','localizacaodescr',5,10,$v50_local,$v50_local);
	  db_text_blur('localiza','localizacaodescr','localizacao',15,15,$localizacaodescr,$localizacaodescr);
	?>
    </td>
        <td width="17%">
		<?
	      db_label_blur('situacao','situação','situacao','situacaodescr');	  
	    ?>		
		</td>
    <td width="36%"> 
	<?
	  db_text_blur('situacao','situacao','situacaodescr',5,10,$v50_situa,$v50_situa);
	  db_text_blur('situacao','situacaodescr','situacao',15,15,$situacaodescr,$situacaodescr);
	?>	
    </td>
  </tr>
  <tr> 
        <td width="14%">
		<?
	      db_label_blur('vara','vara','vara','varadescr');	  
	    ?>		
		</td>
    <td width="33%"> 
	<?
	  db_text_blur('vara','vara','varadescr',5,10,$v50_vara,$v50_vara);
	  db_text_blur('vara','varadescr','vara',15,15,$varadescr,$varadescr);
	?>	
    </td>
    <td width="17%"><b>Data:</b></td>
    <td width="36%">
	<?
	  db_data("data",@$data_dia,@$data_mes,@$data_ano);
	?>
      <b>at&eacute;</b> 
	<?
	  db_data("data_cons");
	?>  
    </td>
  </tr>
  <tr> 
<td width="14%" valign="middle" height="157"><b>Autor:</b></td>
<td width="33%" valign="top" align="left" height="157"> <table width="100%" border="1" cellspacing="0" cellpadding="0" height="151">
    <tr> 
      <td valign="top" align="left" height="145"> <table width="100%" border="0" cellspacing="0" cellpadding="0" height="130">
          <tr> 
            <td height="106" valign="top" align="left"> <table width="100%" border="0" cellspacing="0" cellpadding="0" height="105">
                <tr> 
                  <td valign="top" align="left"> <table width="17%" border="0" cellspacing="0" cellpadding="0" height="105">
                      <tr> 
                        <td valign="bottom" align="left">&nbsp;
						<input type="button" name="so" value="S" onClick="js_SobeAutor()"></td>
                      </tr>
                      <tr> 
                        <td valign="top" align="left">&nbsp;
						<input type="button" name="de" value="D" onClick="js_DesceAutor()"></td>
                      </tr>
                    </table></td>
                  <td width="90%">&nbsp;
				 <select style="width:130px" name="autor" size="5">
                    <?
					$aux_autor = "";
					if(isset($Alterar_PopularSelect)) {
					  $result = pg_exec("select v55_autor from autproc where v55_proces = $retorno order by  v55_seq");
					  for($i = 0;$i < pg_numrows($result);$i++) {
					    echo "<option>".pg_result($result,$i,0)."</option>\n";
					    $aux_autor .= "#".pg_result($result,$i,0);
					  }
					}					
					?>
                </select>
				<INPUT type="hidden" name="aux_autor" value="<?=@$aux_autor?>">
				</td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td height="35" align="left" valign="top" nowrap>&nbsp;
			  <input type="text" name="text_autor" size="20">
              <input type="button" name="mais" value="+" onClick="js_InsereAutor(document.form1.text_autor.value)">
			  <input type="button" name="menos" value="-" onClick="js_RemoveAutor()">
                  </td>
                </tr>
              </table></td>
          </tr>
        </table></td>
      <td width="17%" height="157">
	   <a href="" class="rotulos" onClick="js_abre_mov();return false"><strong>Movimentação</strong></a>
      </td>
      <td width="36%" height="157" valign="middle" nowrap> 
	  <textarea name="v50_movim" cols="37" rows="5"><?=@$v50_movim?></textarea> 
      </td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td height="30"><input type="submit" name="enviar" value="Enviar"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>	  	  	  
    </tr>
  </table>
</form>
</center>
<script>
function js_FocalizaMovimento() {
//  jan.focus();
//alert(jan.event.toElement);
}
function js_InserirDados() {
  document.form1.v50_movim.value = jan.document.getElementById("movimen").value;
  jan.close();
}
function js_abre_mov() {
  jan = window.open('','_blank','width=600,height=300');
  var corpo = jan.document.createElement("BODY");
  var textarea = jan.document.createElement("TEXTAREA");
  var botao = jan.document.createElement("input");
  var br = jan.document.createElement("br");
  
  corpo.setAttribute("id","corpo");

  botao.setAttribute("type","button");
  botao.setAttribute("id","botao");
  botao.setAttribute("value","Inserir");  
  
  textarea.setAttribute("name","movimen");
  textarea.setAttribute("id","movimen");
  textarea.setAttribute("cols","65");
  textarea.setAttribute("rows","15");
  textarea.setAttribute("value",document.form1.v50_movim.value);

  jan.document.body.appendChild(corpo);
  
  jan.document.body.appendChild(textarea);
  jan.document.body.appendChild(br);
  jan.document.body.appendChild(botao);
  
  jan.document.getElementById("corpo").onblur = js_FocalizaMovimento;
  jan.document.getElementById("botao").onclick = js_InserirDados;
  jan.document.getElementById("movimen").focus();  
}
function js_InsereAutor(valor) {
  F = document.form1;
  if(F.text_autor.value == "") {
    alert("Autor não pode estar em branco");
	F.text_autor.focus();
	return false;
  }
  var num_op = F.autor.length;
  F.autor.options[num_op] = new Option(valor);
  F.text_autor.value = "";
  F.text_autor.focus();	
  aux = "";
  for(var i = 0;i < num_op + 1;i++) {
    aux = aux + "#" + document.form1.autor.options[i].text;
  }
  F.aux_autor.value = aux;    
}
function js_RemoveAutor() {
  F = document.form1;
  var num_op = F.autor.length;
  F.autor.options[F.autor.selectedIndex] = null;	
  aux = "";	
  for(var i = 0;i < num_op - 1;i++) {
    aux = aux + "#" + F.autor.options[i].text;
  }
  F.aux_autor.value = aux;
}
function js_SobeAutor() {
  var num_op = document.form1.autor.length;
  if(document.form1.autor.selectedIndex > 0) {
    var aux = document.form1.autor.options[document.form1.autor.selectedIndex - 1].text
    document.form1.autor.options[document.form1.autor.selectedIndex - 1] = new Option(document.form1.autor.options[document.form1.autor.selectedIndex].text);
    document.form1.autor.options[document.form1.autor.selectedIndex] = new Option(aux);			
    aux = "";
    for(var i = 0;i < num_op;i++) {
      aux = aux + "#" + document.form1.autor.options[i].text;
    }
    document.form1.aux_autor.value = aux;
  }
}
function js_DesceAutor() {
  var num_op = document.form1.autor.length;
  if((document.form1.autor.selectedIndex + 1) < num_op) {
    var aux = document.form1.autor.options[document.form1.autor.selectedIndex + 1].text
    document.form1.autor.options[document.form1.autor.selectedIndex + 1] = new Option(document.form1.autor.options[document.form1.autor.selectedIndex].text);
    document.form1.autor.options[document.for