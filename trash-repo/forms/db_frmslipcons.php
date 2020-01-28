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
?>
<script>
function js_gravar(){
  if(document.form1.debito.value == document.form1.credito.value ){
    alert('Contas nao podem ser iguais.');
	return false;
  }
  if(document.form1.debito.value == '0' ){
    alert('Contas Debito nao pode estar Zerada.');
	return false;
  }
  if(document.form1.credito.value == '0' ){
    alert('Contas Credito nao pode estar Zerada.');
	return false;
  }
  
  if ($('estornarcheque')) {
    if ($('estornarcheque').checked) {
      if (!confirm('Confirma o Cancelamento do Cheque '+$F('e86_cheque')+'?')) {
        return false;
      }
    }
  }
  return true;
}

function js_atualiza1(qual){
  if(qual=='debito')
  document.form1.descr_debito.options[document.form1.debito.selectedIndex].selected = true;
  if(qual=='descr_debito')
  document.form1.debito.options[document.form1.descr_debito.selectedIndex].selected = true;
}
function js_atualiza2(qual){
  if(qual=='credito')
  document.form1.descr_credito.options[document.form1.credito.selectedIndex].selected = true;
  if(qual=='descr_credito')
  document.form1.credito.options[document.form1.descr_credito.selectedIndex].selected = true;
}
</script>
    <center>
    <form name="form1" method="post" onSubmit="return js_gravar()" >
      <br>
        <table border="0" >
          <tr> 
            <td>
            <fieldset>
            <table>
          <tr> 
           <td align="right" nowrap title="<?=@$Tk17_codigo?>"><?=@$Lk17_codigo?> </td>
            <td>
			<input name="numslip" type="text" id="numslip" readonly value="<?=$numslip?>" size="10" >
<?
            $result = $clempageslip->sql_record(
                      $clempageslip->sql_query_configura(
                                                         null,
                                                         null,
                                                         "e86_cheque,
                                                          e91_codcheque as e86_codmov,
                                                          e81_codmov",
                                                          null,
                                                          "e80_instit = " . db_getsession("DB_instit") . "
                                                           and empageslip.e89_codigo = $numslip"
                                                        )
                                                 );
            if ($clempageslip->numrows > 0) {
	          db_fieldsmemory($result,0);
	        }
	    
            echo "<b>&nbsp;&nbsp;&nbsp;&nbsp;Cheque:</b>";
            db_input('e86_cheque',10,'',true,'text',3);
            db_input('e86_codmov',10,'',true,'hidden',3);
            db_input('e81_codmov',10,'',true,'hidden',3);
?>
			
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              <?
			   if($k17_autent>0){
			   ?>
              <font color="#FF0000">Slip Já Autenticado</font> 
              <?
			   }
			  ?>
            </td>
          </tr>

           <?
	     if($k17_autent>0){
	      
  	        echo "<tr>"; 	 
	          echo "<td align='right'>$Lk18_motivo</td>";	 
	          echo "<td align='left'>"; 	 
	        db_textarea('k18_motivo',1,50,$Ik18_motivo,true,'text',1);
  	          echo "</td>"; 	 
                echo "</tr>"; 	 
             }		
	  ?>



	  
          <tr> 
           <td align="right" nowrap title="<?=@$Tk17_debito?>"><?=@$Lk17_debito?> </td>
            <td> 

 			<?
			  if($debito==0 || $debito == ""){
			    ?>
                <select onChange="js_atualiza1(this.name)" name="debito" id="debito">
                <?
				    for($i=0;$i<pg_numrows($result_conta1);$i++){
	                  db_fieldsmemory($result_conta1,$i,true,true);
  	                  echo "<option value=\"$c61_reduz\" ".(isset($debito)?($debito==$c61_reduz?"selected":""):"").">$c61_reduz</option>";
		            }
                    ?>
              </select>
              &nbsp;&nbsp; 
              <select onChange="js_atualiza1(this.name)" name="descr_debito" id="descr_debito"> 
              <?
 				    for($i=0;$i<pg_numrows($result_conta1);$i++){
	                  db_fieldsmemory($result_conta1,$i);
  	                  echo "<option value=\"$c61_reduz\" ".(isset($debito)?($debito==$c61_reduz?"selected":""):"").">$c60_descr</option>";
		            }
                    ?></select>
            <?
			}else{
			?>
			<input name="debito" type="text" id="debito" value="<?=$debito?>" size="4" maxlength="10" readonly>
            &nbsp;&nbsp; 
            <input name="descr_debito" type="text" readonly id="descr_debito" value="<?=$descr_debito?>" size="40"></td>
            <?
			}
			?>
          </tr>
          <tr> 
           <td align="right" nowrap title="<?=@$Tk17_credito?>"><?=@$Lk17_credito?> </td>
            <td>
 			<?
			  if($credito==0 || $credito == ""){
			    ?>
   			      <select onChange="js_atualiza2(this.name)" name="credito" id="credito">
                  <?
 				     for($i=0;$i<pg_numrows($result_conta2);$i++){
	                     db_fieldsmemory($result_conta2,$i);
  	                     echo "<option value=\"$k13_reduz\" ".(isset($credito)?($credito==$c01_reduz?"selected":""):"").">$k13_reduz</option>";
		              }
                   ?>
                   </select>
                   &nbsp;&nbsp; 
				   <select onChange="js_atualiza2(this.name)" <?=$read_only?> name="descr_credito" id="descr_credito">
				   <?
 				   for($i=0;$i<pg_numrows($result_conta2);$i++){
	                  db_fieldsmemory($result_conta2,$i);
  	                  echo "<option value=\"$k13_reduz\" ".(isset($credito)?($credito==$k13_reduz?"selected":""):"").">$k13_descr</option>";
		           }
                   ?>
                   </select>
			  <?
			  }else{
              ?>
			    <input name="credito" type="text" id="credito2" value="<?=$credito?>" size="4" maxlength="10" readonly> &nbsp;&nbsp; 
                <input name="descr_credito" type="text" id="descr_credito" readonly value="<?=$descr_credito?>" size="40"> 
			  <?
			  }
			  ?>
             </td>
          </tr>
          <tr> 
           <td align="right" nowrap title="<?=@$Tk17_hist?>"><?=@$Lk17_hist?> </td>
            <td> 
              <input name="db_hist" type="text" id="credito3" readonly value="<?=$db_hist?>" size="10"> &nbsp;&nbsp; 
              <input name="db_descrhist" type="text" readonly id="db_descrhist" value="<?=$descr_hist?>" size="40">
            </tr>
          <tr> 
            <td align="right" nowrap title="<?=@$Tk17_numcgm?>"><?=@$Lk17_numcgm?> </td>
            <td> <input type='text' size=10 readonly id='z01_numcgm' value="<?=@$z01_numcgm;?>">&nbsp;&nbsp;
						      <input name="db_nome" type="text" readonly id="db_nome" value="<?=@$db_nome?>" size="40">
            </td>
          </tr>
          <tr> 
           <td align="right" nowrap title="<?=@$Tk17_valor?>"><?=@$Lk17_valor?> </td>
            <td><input name="valor" type="text" readonly id="valor" value="<?=$k17_valor?>" size="20" maxlength="30"></td>
          </tr>
          <tr> 
            <td height="85" align="right" valign="top" nowrap title="<?=@$Tk17_texto?>"><?=@$Lk17_texto?> </td>
            <td align="left" valign="top"><textarea name="texto" readonly cols="60" rows="8" id="texto"><?=$k17_texto?></textarea></td>
          </tr>
          <? 
           if ($k17_autent != 0 && (isset($e86_codmov) and $e86_codmov != "")) {
             
             echo "<tr>";
             echo "  <td>&nbsp;</td>";
             echo "  <td><input type='checkbox' name='estornarcheque' id='estornarcheque'>";
             echo       "<label for='estornarcheque'><b>Estornar Cheque</b></label></td>";
             echo "</tr>";
           }
          ?>
          
          
          <tr align="center"> 
            <?
	      // echo($clempageslip->sql_query_file(null,$numslip,"e89_codmov as movimento"));
	      $resulttranca = $clempageslip->sql_record($clempageslip->sql_query_slip(null,null,"e89_codmov as movimento", null, " e80_instit = " . db_getsession("DB_instit") . " and e89_codigo = $numslip"));
	      $mensagem_agenda = "";
	      $disabled = "";
	      if($clempageslip->numrows > 0){
		db_fieldsmemory($resulttranca,0);
		// echo ($clempagemov->sql_query_file($movimento));
		$result_agenda_slipt = $clempagemov->sql_record($clempagemov->sql_query_file($movimento));
		if($clempagemov->numrows > 0){
		  db_fieldsmemory($result_agenda_slipt,0);
		}
		$mensagem_agenda = "<font color='#FF0000'><b>Slip na agenda $e81_codage.</b></font><BR>";
	      }
			?>
        </table>
        </fieldset>
        </td>
          </tr>
          <?
          if($k17_autent==0){
      ?>
            <td colspan="2" valign="top" style='text-align:center'>
         <?
         if(trim($mensagem_agenda)!=""){
          echo $mensagem_agenda;
         }
         ?>
         <input name="autentica" <?=($disabled)?> type="submit" id="autentica" value="Autenticar">
            &nbsp;&nbsp;&nbsp;
               <input name="retorna" type="button" id="estorna" onclick="location.href='cai4_auttransf.php'" value="Retornar">
            &nbsp;&nbsp;&nbsp;
               <input name="Imprime" type="button" id="imprime" onclick="window.open('cai1_slip003.php?<?=base64_encode(db_getsession()."&numslip=".$numslip)?>','','location=0')" value="Imprime">
      </td>
      <?
       } else{
      ?>
            <td colspan="2" valign="top" nowrap style='text-align:center'>
         <?
         if(trim($mensagem_agenda)!=""){
          echo $mensagem_agenda;
         }
         ?>
        <input name="estorna" <?=($disabled)?> type="submit" id="estorna"  value="Estornar">
      &nbsp;&nbsp;&nbsp;
              <input name="retorna" type="button" id="estorna" onclick="location.href='cai4_auttransf.php'" value="Retornar">
            &nbsp;&nbsp;&nbsp;
              <input name="Imprime" type="button" id="imprime" onclick="window.open('cai1_slip003.php?<?=base64_encode(db_getsession()."&numslip=".$numslip)?>','','location=0')" value="Imprime">
      </td>
      <?
      }
      ?>
          </tr>
        </table>
      </form>
</center>