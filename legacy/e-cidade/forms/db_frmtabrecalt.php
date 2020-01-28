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
?
   if(isset($HTTP_POST_VARS)) {
     db_postmemory($HTTP_POST_VARS);
	 if(!empty($verfEstrut)) {
	   $verfEstrut = "";
	   if($k02_tipo == "O") {
	     $tipo = "Orçamentária";
	     $result = pg_exec("select o02_descr 
	                        from orcam 
				where o02_anousu = ".db_getsession("DB_anousu")." 
				  and o02_codigo = '$k02_estrut'");
	   } else if($k02_tipo == "E") {
	     $tipo = "Extra-orçamentária";
	     $result = pg_exec("select c01_descr 
	                        from conplano 
				where c01_anousu = ".db_getsession("DB_anousu")." 
				and c01_estrut = '$k02_estrut'");	   
       }		 
	   if(pg_numrows($result) == 0) {
	     echo "<script>alert('Código da receita $tipo não encontrado!')</script>\n";
	   } else {	   
	     $k02_drecei = pg_result($result,0,0);
	   }
	 }	
   }
   
$cltabrec->rotulo->label();

?>
  <form name="form1" method="post" id="form1">
    <table width="679" border="0" cellspacing="0" cellpadding="0">
      <tr>
	<td nowrap title="<?=@$Tk02_codigo?>">
	   <?=@$Lk02_codigo?>
	</td>
	<td> 
         <?
            db_input('k02_codigo',8,$Ik02_codigo,true,'text',$db_opcao);
         ?>
	</td>
      </tr>

	  
          <tr> 
            <td height="25" nowrap><strong>Tipo da receita:</strong></td>
            <td height="25" nowrap> <select name="k02_tipo" id="k02_tipo" onChange="this.form.k02_estrut.value='';this.form.k02_drecei.value=''">
                <option value="O" <? echo isset($k02_tipo)?($k02_tipo=="O"?"selected":""):"" ?>>Or&ccedil;ament&aacute;ria</option>
                <option value="E" <? echo isset($k02_tipo)?($k02_tipo=="E"?"selected":""):"" ?>>Extra-or&ccedil;ament&aacute;ria</option>
              </select></td>
          </tr>

	  
          <tr> 
            <td height="25" nowrap><a href="" style="color:black" onClick="js_lista('dbforms/db_receita.php',document.forms[0].k02_tipo.options[document.forms[0].k02_tipo.selectedIndex].value,'','','','550');return false"><strong>Código da receita:</strong></a></td>
            <td height="25" nowrap><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="10%" height="25"> 
                    <!--input name="k02_estrut" type="text" id="k02_estrut" onblur="document.form1.verfEstrut.value='t';document.form1.submit()" value="<?=@$k02_estrut?>" size="13" maxlength="13"-->
                    <input name="k02_estrut" type="text" id="k02_estrut"  value="<?=@$k02_estrut?>" size="13" maxlength="13"> 
                    <input type="hidden" name="verfEstrut" value="<?=@$verfEstrut?>"> 
                  </td>
                  <td width="90%" height="25">&nbsp;&nbsp; <input name="k02_drecei" type="text" id="k02_drecei" value="<?=@$k02_drecei?>" size="40" maxlength="40" readonly> 
                  </td>
                </tr>
              </table></td>
          </tr>

          <tr> 
            <td height="25" nowrap><strong>Descricao Resumida:</strong></td>
            <td height="25" nowrap><input name="k02_descr" type="text" id="k02_descr3" value="<?=@$k02_descr?>" size="15" maxlength="15"></td>
          </tr>
          <tr> 
            <td height="25" nowrap><strong>Codigo Acr&eacute;scimo:</strong></td>
            <td height="25" nowrap><input name="k02_codjm" type="text" id="k02_codjm" value="<?=@$k02_codjm?>" size="15" maxlength="15"></td>
          </tr>
          <tr> 
            <td height="25" nowrap>
              <?=$Lk02_recjur?>
            </td>
            <td height="25" nowrap> 
              <input name="k02_recjur" title="<?=$Tk02_recjur?>" type="text" id="k02_recjur" value="<?=@$k02_recjur?>" size="5" maxlength="5">
              </td>
          </tr>
          <tr>
            <td height="25" nowrap>
              <?=$Lk02_recmul?>
            </td>
            <td height="25" nowrap><input name="k02_recmul"  title="<?=$Tk02_recmul?>" type="text" id="k02_recmul" value="<?=@$k02_recmul?>" size="5" maxlength="5"></td>
          </tr>
        </table></td>
      </tr>
      <tr> 
        
      <td align="center">           
	  <input name="enviar" type="button" onClick="document.forms[0].submit()" id="enviar2" value="Enviar"></td>
 </td>
      </tr>
      <tr> 
        
      <td>&nbsp;</td>
      </tr>
    </table>
  </form> 
<script>
js_Ipassacampo();
document.forms[0].elements[0].focus();
/*
for(i=0;i<document.form1.elements.length;i++)
ww.innerHTML += document.form1.elements[i].name + '<br>';
*/
</script>