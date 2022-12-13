<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

session_start();
require("libs/db_stdlib.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//busca base de cálculo

$sql_base = "select issbase.q02_inscr,
                    tabativ.q07_ativ,
                    ativtipo.q80_tipcal,
                    tipcalc.q81_valexe,
                    tipcalc.q81_tipo
             from issbase
              inner join tabativ   on tabativ.q07_inscr   = issbase.q02_inscr
              inner join ativtipo  on ativtipo.q80_ativ   = tabativ.q07_ativ
              inner join tipcalc   on tipcalc.q81_codigo  = ativtipo.q80_tipcal
             where issbase.q02_inscr = $inscr
               and tipcalc.q81_tipo = 1
            ";
$query = db_query($sql_base);
$linhas = pg_num_rows($query);
if($linhas==0){
         ?>
         <script>
          alert("Base de cálculo NÃO pode ser encontrada!\n\nComunique a Prefeitura.");
         </script>
         <?
}else{
        ?>
        <html>
        <head>
        <title> Prefeitura On-Line - Cálculo do ISSQN </title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link href="config/estilos.css" rel="stylesheet" type="text/css">
        <script language="JavaScript" src="scripts/scripts.js"></script>
				<script>

     function js_teclas(event){
	  	   tecla = document.all ? event.keyCode : event.which;

			    if (tecla > 47 && tecla < 58){
						   return true;
				  }else{
				      if (tecla != 8 && tecla != 0 && tecla != 46 & tecla != 13){ // backspace
					        return false;
			 	      }else{
							    return true;
							}
				  }
			}
			 function js_validatamanho(valor){

				    regexp = new RegExp('.');
				    tempto = valor.indexOf(".");
				    if (valor.length > 9){
				      if (tempto == -1){
	  			       alert('Valor inválido. Verifique.');
								// document.getElementById(btn).disabled = true;
								 return false;
						  }else if(valor.length > 12) {
	  			       alert('Valor inválido. Verifique.');
							  //  document.getElementById(btn).disabled = false;
							    return false;
				      }else{

                 return true;
							}
					 }else{
 				      // document.getElementById(btn).disabled = false;
							 return true;
					 }

			 }
																															 
				</script>
        <body bgcolor="#ccffcc" onblur="foco()">
         <form name="form">
                <Table width="100%" border="1" cellpadding="0" cellspacing="0">
                  <caption align="center" class="bold3">Cálculo do ISSQN</caprion>
                 <tr height="100%"></tr>
                  <td align="center" class="texto" id="calcula">
                   Valor Bruto: R$<br>
                   <input type="text"   onkeypress="return js_teclas(event)" name="valorbruto" id="valorbruto" size="20" value="" style="text-align:right;"><br><br>
                   Informe a Alíquota:<br>
                   <select name="base1" onChange="EscolheAliquota();calcular()">
                    <option value="">Escolha</option>
                    <?
                    for($x=0;$x<$linhas;$x++){
                      // eu robson alterei a linha abaixo pois estava com 0 fixo no db_fieldsmemory
                      db_fieldsmemory($query,$x);
                    ?>
                    <option value="<?=$q81_valexe?>"><?=$q81_valexe?></option>
                    <?
                    }
                    ?>
                   </select>
                   <input type="text" name="base2" size="5" value="" style="text-align:center"><br><br>
                   Valor à Pagar: R$<br>
                   <input type="text" name="valorpagar" id="valorpagar" size="15" value="0.00" readonly style="text-align:right; BACKGROUND-COLOR: #eaeaea; font-weight: bold;"><br><br><br>
                   <input type="button" name="calc" id='calc' value="Calcular" onclick="calcular()"><br><br>
                  </td>
                  
				   <?
		   // faz consulta para ver se o parâmetro do movimento do ISSQN está habilitado nas 
		   // configurações se estiver exibe tela normalmente, se não é exibida a mensagem do usuário	   
		   $sql  = " select configdbpref.w13_liberalancisssemmov, db_confmensagem.mens ";
		   $sql .= " from configdbpref "; 
		   $sql .= " inner join db_confmensagem on db_confmensagem.cod = 'issqnsemmov_cab' and ";
		   $sql .= " db_confmensagem.instit = (select codigo from db_config where prefeitura is true limit 1) ";
			   
		    $rsResult = db_query($sql);
			$sRetorno = pg_fetch_assoc($rsResult);			  
			 
      		      if( $sRetorno["w13_liberalancisssemmov"] == "f" ){ 
				   ?>
				   <td width="65%">
                  <div style="position:relative; width:377px; heigth:206px; text-align:center;"> 
				   <?=$sRetorno["mens"]?>                 	
				  </div> 
                   </td >	              
				   <?
			       } else{		   
				   ?>
         		   <td width="65%" id="sem_movimento">
    		       <br>&nbsp;&nbsp;Declara lançamento sem Movimento:<input type="checkbox" name="chk_movimento" id="chk_movimento" onclick="sem_movimento()"><p>
                   <fieldset id="fld_movimento" disabled><Legend>&nbsp;Justificativa&nbsp;</legend>
                   <div id="caracteres" name="caracteres" align="center">256 caracteres disponíveis</div>
                   <textarea name="txt_movimento" id="txt_movimento"="txt_movimento" rows="6" cols="45%" onKeyUp="js_caracteres(this)" disabled></textarea>
				   </fieldset>
                   </td >
                  <? 
				  }
				  			  
				  ?>
                 </tr>
                </Table>
                <p align="center">
                   <input type="button" name="confirma" value="Confirmar" onclick="return gravar('<?=$id?>')">
         </form>
        </body>

        <script>
         function sem_movimento(){
           document.getElementById("txt_movimento").disabled = !(document.getElementById("chk_movimento").checked==true);
           document.getElementById("fld_movimento").disabled = !(document.getElementById("chk_movimento").checked==true);
           if( document.getElementById("chk_movimento").checked==true ){
             document.getElementById("valorbruto").value = '';
             document.getElementById("valorpagar").value = '';
             document.getElementById("txt_movimento").focus();
           }else{
              document.getElementById("txt_movimento").value='';
           }
           document.getElementById("calcula").disabled = (document.getElementById("chk_movimento").checked==true);
         }
         function js_caracteres(textarea){
          var tamanho = 256;
          caracteres.innerHTML = (tamanho-textarea.value.length)+" caracteres disponiveis";
          if(textarea.value.length>=tamanho){
           textarea.value = textarea.value.substr(0,256)
           caracteres.innerHTML = "Nenhum caractere disponivel";
           alert("Caracteres excedidos!");
           return false;
          }
         }
         
         function EscolheAliquota(){
          base1 = document.form.base1.value;
          document.form.base2.value = base1;
         }
         
         function calcular(){
					
          if(document.form.base2.value==""){
           alert("Selecione a Alíquota!");
           document.form.base2.focus();
           return false;
          }

          if( document.form.valorbruto.value=="" ){
           alert("Informe o valor bruto corretamente!");
           document.form.valorbruto.focus();
           return false;
          }
          var valor = document.form.valorbruto.value.replace(",",".");
          var base  = document.form.base2.value.replace(",",".");
          total = (Number(valor) * Number(base)) / 100;
          document.form.valorpagar.value = total.toFixed(2);

          document.getElementById("txt_movimento").value = '';
          document.getElementById("txt_movimento").disabled = true;
          document.getElementById("fld_movimento").disabled = true;
          document.getElementById("chk_movimento").checked = false;
         }
         
         function gravar(campo){
           var xx = document.getElementById("chk_movimento") != null;
           if( xx == true && document.getElementById("chk_movimento").checked==true ){
             if( document.form.txt_movimento.value == "" ){
                 alert('É obrigatóriamente o preenchimento da Justificativa');
                 document.getElementById("txt_movimento").focus();
                 return false;
             }else{
             		
                  window.opener.document.form1.target = '';
                  if(confirm("Confirma Sem Movimento?")){
                       var campo3 = 'sem_movimento'+campo;
                       var campo4 = 'calculavalor'+campo;
                       window.opener.document.form1[campo3].value = document.form.txt_movimento.value;
                       window.opener.document.form1[campo4].value = campo;
                       window.opener.js_submit(campo4);
                       //window.opener.document.form1[campo4].click();
                       //window.opener.document.getElementById(campo4).click();
                       
                  }
             }
          }else{
          		
               if(document.form.valorpagar.value<=0){
                     alert("Informe o Valor Bruto e clique em calcula!");
                     return false;
               }else{
								     if (js_validatamanho(document.form.valorpagar.value)){;
                         var campo1 = 'VAL'+campo;
                         var campo2 = 'valor'+campo;
                         opener.document.form1[campo1].value = document.form.valorpagar.value;
                         opener.document.form1[campo2].value = document.form.valorpagar.value;
                         opener.document.form1.target = ''
                         if(confirm("Desejas emitir a Guia agora?")){
                           opener.parent.document.getElementById('confirm_guia').value = "true";
                         }
                         var campo4 = 'calculavalor'+campo;
                         opener.document.form1[campo4].disabled = false;
                         opener.document.form1[campo4].value = campo;
                         opener.document.form1[campo4].click();
												 //return true;
                     }else{

	                    return false;

										 }
							  }
            }
                window.close();
		}
         function foco(){
          setTimeout('close()',60000);
         }
         
         function js_clicou(obj){
           obj.click();
         }
        </script>
        </html>
<?}?>