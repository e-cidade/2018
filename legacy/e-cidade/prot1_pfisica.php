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
?>
<script>
	 function js_zerac(){
     document.form1.z01_cep.value    = "";
     document.form1.z01_ender.value  = "";
     document.form1.z01_munic.value  = "";
     document.form1.z01_uf.value		 = "";
     document.form1.z01_bairro.value = "";
     document.form1.submit();
   }
 </script>

 <tr>
   <input type="hidden" name="municipio" value="<?=($municipio == "t"?'f':'t')?>">
   <?

		 if(isset($z01_cgccpf) && $z01_cgccpf != ""){

			 if(strlen($z01_cgccpf) == 14){
				 $cpf = "";
			 }elseif(strlen($z01_cgccpf) == 11){
				 $cpf = $z01_cgccpf;
			 }else{
				 $cpf = "";
			 }

		 }elseif(!isset($cpf)){
		   $cpf = "";
     }

	?>
  <input type="hidden" name="cpf" value="<?=$cpf?>">
    <td nowrap title="<?=@$Tz01_cpf?>">
      <?=@$Lz01_cpf?>
      &nbsp;&nbsp;
			<?
	      db_input('z01_cpf',15,@$Iz01_cpf,true,'text',$db_opcao,"onBlur='js_verificaCGCCPF(this);js_testanome(\"\",this.value,\"\")'");
			?>
      &nbsp;&nbsp;&nbsp;&nbsp;
		</td>
    <td align="left" title="<?=$TDBtxt29?>">
			<strong>
				<?=$LDBtxt29?>
			</strong>
			<?
				$x = array("t"=>"SIM","f"=>"NÃO");
				//db_select('municipio',$x,true,$db_opcao,'onChange="document.form1.submit()"');
				db_select('municipio',$x,true,$db_opcao,'onChange="js_zerac();"');
			?>
    </td>
  </tr>
  <tr align="left" valign="top">
		<td>
      <table width="50%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td nowrap title=<?=@$Tz01_ident?>>
            <?=@$Lz01_ident?>
          </td>
          <td nowrap title="<?=@$Tz01_ident?>">
            <?
							db_input('z01_ident',20,$Iz01_ident,true,'text',$db_opcao);
            ?>
          </td>
        </tr>
        <tr>
          <td width="27%" title='<?=$Tz01_numcgm?>' nowrap>
            <?=$Lz01_numcgm?>
          </td>
          <td width="73%" nowrap>
			      <?
							db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',3);
						?>
          </td>
        </tr>
        <tr>
          <td nowrap title=<?=@$Tz01_nome?>>
            <?=@$Lz01_nome?>
          </td>
          <td nowrap title="<?=@$Tz01_nome?>">
            <?
							db_input('z01_nome',40,$Iz01_nome,true,'text',$db_opcao,"onBlur='js_testanome(this.value,\"\",\"\");");
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title=<?=@$Tz01_nomecomple?>>
            <?=@$Lz01_nomecomple?>
          </td>
          <td nowrap title="<?=@$Tz01_nomecomple?>">
            <?
              db_input('z01_nomecomple',40,$Iz01_nomecomple,true,'text',$db_opcao,"onBlur='js_testanome(this.value,\"\",\"\");");
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title=<?=@$Tz01_pai?>>
            <?=@$Lz01_pai?>
          </td>
					<td nowrap title="<?=@$Tz01_pai?>">
            <?
						  db_input('z01_pai',40,$Iz01_pai,true,'text',$db_opcao,"");
						?>
          </td>
        </tr>
        <tr>
				  <td nowrap title=<?=@$Tz01_mae?>>
            <?=@$Lz01_mae?>
          </td>
          <td nowrap title="<?=@$Tz01_mae?>">
            <?
							db_input('z01_mae',40,$Iz01_mae,true,'text',$db_opcao,"");
						?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Tz01_nasc?>">
            <?=$Lz01_nasc?>
          </td>
          <td nowrap title="<?=$Tz01_nasc?>">
						<?
							db_inputdata('z01_nasc',@$z01_nasc_dia,@$z01_nasc_mes,@$z01_nasc_ano,true,'text',$db_opcao);
						?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Tz01_dtfalecimento?>">
            <?=$Lz01_dtfalecimento?>
          </td>
          <td nowrap title="<?=$Tz01_dtfalecimento?>">
            <?
              db_inputdata('z01_dtfalecimento',@$z01_dtfalecimento_dia,@$z01_dtfalecimento_mes,@$z01_dtfalecimento_ano,true,'text',$db_opcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Tz01_estciv?>">
            <?=$Lz01_estciv?>
          </td>
          <td nowrap title="<?=$Tz01_estciv?>">
            <?
							$x = array("1"=>"Solteiro","2"=>"Casado","3"=>"Viúvo","4"=>"Divorciado");
							db_select('z01_estciv',$x,true,$db_opcao);
						?>
            <?=$Lz01_sexo?>
            <?
							$sex = array("M"=>"Masculino","F"=>"Feminino");
							db_select('z01_sexo',$sex,true,$db_opcao);
						?>
          </td>
        </tr>
			</table>
    </td>

	 <?/*Muda lado da tela*/ ?>

		<td>
			<table width="50%" border="0" cellspacing="0" cellpadding="0">
				<tr>
          <td nowrap title="<?=@$Tz01_identorgao?>">
            <?=@$Lz01_identorgao?>
          </td>
          <td nowrap>
           <?
             db_input('z01_identorgao',20,$Iz01_identorgao,true,'text',$db_opcao);
					   echo $Lz01_identdtexp;
						 db_inputdata('z01_identdtexp',@$z01_identdtexp_dia,@$z01_identdtexp_mes,@$z01_identdtexp_ano,true,'text',$db_opcao);
           ?>
          </td>
        </tr>
				<tr>
					<td width="27%" title="<?=$Tz01_profis?>" nowrap>
						<?=$Lz01_profis?>
					</td>
					<td nowrap>
					  <?
							db_input('z01_profis',40,$Iz01_profis,true,'text',$db_opcao);
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
					   echo $Lz01_pis;
						 db_input('z01_pis',10,$Iz01_pis,true,'text',$db_opcao,"onblur = js_validaPis(this.value);");
           ?>
            </td>
          </tr>
					<td nowrap title="<?=$Tz01_estciv?>">
						<?=$Lz01_nacion?>
					</td>
					<td nowrap title="<?=$Tz01_estciv?>">
					  <?
						  $x = array("1"=>"Brasileira","2"=>"Estrangeira");
						  db_select('z01_nacion',$x,true,$db_opcao);
					  ?>
				  </td>
				</tr>
				<tr>
				  <td>
				    <strong>
				    <?php
				      db_ancora("CBO", "js_pesquisaCbo(true);", $db_opcao);
				    ?>
				    </strong>
				  </td>
				  <td>
				    <?php
  				    db_input("rh70_sequencial",  4, "", true, "text", $db_opcao, "onchange='js_pesquisaCbo(false);'");
  				    db_input("rh70_descr",  30, "",  true, "text", 3, "");
				    ?>
				  </td>
				</tr>
				<tr>
					<td nowrap title=<?=@$Tz01_cnh?>>
						<?=@$Lz01_cnh?>
					</td>
					<td nowrap title="<?=@$Tz01_cnh?>">
					  <?
						  db_input('z01_cnh',15,$Iz01_cnh,true,'text',$db_opcao,"");
					  ?>
						<?=@$Lz01_categoria?>
					  <?
						  $y = array(""=>"","A"=>"A","B"=>"B","C"=>"C","D"=>"D","E"=>"E","AB"=>"AB","AC"=>"AC","AD"=>"AD","AE"=>"AE");
						  db_select('z01_categoria',$y,true,$db_opcao);
					  ?>
					</td>
				</tr>
				<tr>
					<td nowrap title=<?=@$Tz01_dtemissao?>>
						<?=@$Lz01_dtemissao?>
					</td>
					<td nowrap title="<?=@$Tz01_dtemissao?>">
					  <?
							db_inputdata('z01_dtemissao',@$z01_dtemissao_dia,@$z01_dtemissao_mes,@$z01_dtemissao_ano,true,'text',$db_opcao);
						?>
					</td>
				</tr>
				<tr>
					<td nowrap title=<?=@$Tz01_dthabilitacao?>>
						<?=@$Lz01_dthabilitacao?>
					</td>
					<td nowrap title="<?=@$Tz01_dthabilitacao?>">
					  <?
							db_inputdata('z01_dthabilitacao',@$z01_dthabilitacao_dia,@$z01_dthabilitacao_mes,@$z01_dthabilitacao_ano,true,'text',$db_opcao);
						?>
					</td>
				</tr>
				<tr>
					<td nowrap title=<?=@$Tz01_dtvencimento?>>
						<?=@$Lz01_dtvencimento?>
					</td>
					<td nowrap title="<?=@$Tz01_dtvencimento?>">
					  <?
							db_inputdata('z01_dtvencimento',@$z01_dtvencimento_dia,@$z01_dtvencimento_mes,@$z01_dtvencimento_ano,true,'text',$db_opcao);
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?
		db_input('z01_cgc',20,@$Iz01_cgc,true,'hidden',$db_opcao,'');
	?>
	<?
	if($cpf != ""){
		echo "<script>document.form1.z01_cpf.value = '$cpf'</script>";
	}
	$pesa='f';
	?>
	<script type="text/javascript">

		function js_validaPis(pis){

	    if (pis != ''){
	      if (!js_ChecaPIS(pis)){
	        alert("Pis inválido.Verifique.");
	        document.form1.z01_pis.focus();
	        document.form1.z01_pis.value = '';
	        return(false);
	      } else {
	        return(true);
	      }
	    }
	  }

		function js_pesquisaCbo(mostra){

		  if(mostra==true){
		    js_OpenJanelaIframe('','db_iframe_Cbo','func_rhcbo.php?funcao_js=parent.js_mostraCbo|rh70_sequencial|rh70_descr|rh70_estrutural','Pesquisa',true);
		  }else{
		    js_OpenJanelaIframe('','db_iframe_Cbo','func_rhcbo.php?lCadastroCgm=true&pesquisa_chave='+document.form1.rh70_sequencial.value+'&funcao_js=parent.js_mostraCboHide','Pesquisa', false);
		  }

		}

		function js_mostraCboHide(chave, chave2, chave3, erro){

		  if (chave2 != false) {

		    if(erro==true){

		      document.form1.rh70_sequencial.value = '';
		      document.form1.rh70_sequencial.focus();

		    }

		    document.form1.rh70_descr.value = chave3 + ' - ' + chave2;

		  } else {

		    document.form1.rh70_sequencial.value = '';
		    document.form1.rh70_descr.value      = '';

		  }

		}

		function js_mostraCbo(chave1,chave2,chave3){

		  document.form1.rh70_sequencial.value = chave1;
		  document.form1.rh70_descr.value      = chave3 + ' - ' + chave2;
		  db_iframe_Cbo.hide();

		}

  </script>