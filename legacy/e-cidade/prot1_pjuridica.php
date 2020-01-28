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
<script>
    function js_zerac(){
      document.form1.z01_cep.value = "";
      document.form1.z01_ender.value = "";
      document.form1.z01_munic.value = ""
      document.form1.z01_uf.value = "";
      document.form1.z01_bairro.value = "";
      document.form1.submit();
    }
    </script>
    <tr>
      <input type="hidden" name="municipio" value="<?=($municipio == "t"?'f':'t')?>">
      <?
      if(isset($z01_cgccpf) && $z01_cgccpf != ""){
        if(strlen($z01_cgccpf) == 14){
	  $cnpj = $z01_cgccpf;
	}elseif(strlen($z01_cgccpf) == 11){
	  $cnpj = "";
	}else{
	  $cnpj = "";
	}
      }elseif(!isset($cnpj)){
	$cnpj = "";
      }
      ?>
      <input type="hidden" name="cnpj" value="<?=$cnpj?>">
              <td nowrap title="<?=@$Tz01_cgc?>"> 
                <?=$LDBtxt31?>
                <?
		  db_input('z01_cgc',15,@$Iz01_cgc,true,'text',$db_opcao,"onBlur='js_verificaCGCCPF(this);js_testanome(\"\",\"\",this.value)'");
		?>
	      </td>	  
      <td align="left" title="<?=$TDBtxt29?>">
	<strong><?=$LDBtxt29?></strong>
	<?
	  $x = array("t"=>"SIM","f"=>"NÃO");
	  db_select('municipio',$x,true,$db_opcao,'onChange="js_zerac()"');
	?>
      </td>
    </tr>
    <tr align="left" valign="top"> 
      <td colspan="2"><table width="50%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="27%" title='<?=$Tz01_numcgm?>' nowrap> 
              <?=$Lz01_numcgm?>
            </td>
            <td width="73%" nowrap> 
              <?
		  db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',3);
		  ?>
            <td nowrap title=<?=@$Tz01_nome?>> 
              <?=@$Lz01_nome?>
            </td>
            <td nowrap title="<?=@$Tz01_nome?>" colspan=2 > 
             <?
	  	  db_input('z01_nome',60,$Iz01_nome,true,'text',$db_opcao,"onBlur='js_testanome(\"\",\"\",this.value)'; onChange='if(document.form1.z01_nomecomple.value == \"\") document.form1.z01_nomecomple.value = document.form1.z01_nome.value'");
	     ?>
            </td>
	    <td></td>
          </tr>
          <tr> 
            <td nowrap title=<?=@$Tz01_nomecomple?>> 
              <?=@$Lz01_nomecomple?>
            </td>
            <td nowrap title="<?=@$Tz01_nomecomple?>" colspan=4 > 
              <?
	  	  db_input('z01_nomecomple',100,$Iz01_nomecomple,true,'text',$db_opcao,"onBlur='js_testanome(\"\",\"\",this.value);");
	      ?>
            </td>
	    <td></td>
          </tr>
          <tr> 
            <td nowrap title="<?=$Tz01_tipcre?>">
              <?=$Lz01_tipcre?>
            </td>
            <td nowrap>
              <?
		    $x = array("2"=>"Empresa Privada","1"=>"Empresa Pública");
		    db_select('z01_tipcre',$x,true,$db_opcao);
		    ?>
            </td>
            <td nowrap title=<?=@$Tz01_contato?>> 
              <?=@$Lz01_contato?>
            </td>
            <td nowrap title="<?=@$Tz01_contato?>"  > 
              <?
	  	  db_input('z01_contato',40,$Iz01_contato,true,'text',$db_opcao,"");
	      ?>
            </td>
          </tr>
          <tr> 
            <td nowrap title="<?=@$Tz01_incest?>"> 
              <?=@$Lz01_incest?>
            </td>
            <td nowrap> 
              <?
		  db_input('z01_incest',15,$Iz01_incest,true,'text',$db_opcao);
	      ?>
            </td>
            <td nowrap title=<?=@$Tz01_nomefanta?>> 
              <?=@$Lz01_nomefanta?>
            </td>
            <td nowrap title="<?=@$Tz01_nomefanta?>"  > 
              <?
	          db_input('z01_nomefanta',40,$Iz01_nomefanta,true,'text',$db_opcao,"");
	      ?>
            </td>
          </tr>
        </table></tr>
	  <?
  	  db_input('z01_cpf',15,@$Iz01_cpf,true,'hidden',$db_opcao,'');
	  ?>
	  <?
	    if($cnpj != ""){
	      echo "<script>document.form1.z01_cgc.value = '$cnpj'</script>";
	    }
	    $pesa='j';
	  ?>