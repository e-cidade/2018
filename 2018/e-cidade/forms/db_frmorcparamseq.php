<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: orcamento
$clorcparamseq->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o42_descrrel");
      if($db_opcao==1){
 	   $db_action="orc1_orcparamseq004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="orc1_orcparamseq005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="orc1_orcparamseq006.php";
      }
?>
<form name="form1" method="post" action="<?=$db_action?>">
	<fieldset style="width:600px;">
<center>
<table border="0">

  <tr>
    <td nowrap title="<?=@$To69_codseq?>">
       <?=@$Lo69_codseq?>
    </td>
    <td>
		<?
		// controla se os campos o69_codparamrel, o69_codseq e a âncora do campo
		// o69_codparamrel deverão estar bloqueados ou somente read only para o usuário
		$opcao1 = $opcao = $db_opcao;
		if ($db_opcao == 2 ) {
		  $opcao  = 1;
			$opcao1 = 3;
		} else if ($db_opcao == 1){
		  $opcao  = 3;
			$opcao1 = 1;
		}

			db_input('o69_codseq',10,$Io69_codseq,true,'text',$opcao,"");
			if (isset($o69_codseq) && $o69_codseq != "") {
				$o69_codseq_anterior = $o69_codseq;
			}
    	db_input('o69_codseq_anterior',10,null,true,'hidden',$opcao,"");
		?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$To69_codparamrel?>">
       <?
       db_ancora(@$Lo69_codparamrel,"js_pesquisao69_codparamrel(true);",$opcao1);
       ?>
    </td>
    <td>
			<?
			  db_input('o69_codparamrel',10,$Io69_codparamrel,true,'text',$opcao1," onchange='js_pesquisao69_codparamrel(false);'");
			  db_input('o42_descrrel',50,$Io42_descrrel,true,'text',3,'')
			?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$To69_descr?>">
       <?=@$Lo69_descr?>
    </td>
    <td>
			<?
				db_input('o69_descr',64,$Io69_descr,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To69_grupo?>">
       <?=@$Lo69_grupo?>
    </td>
    <td>
			<?
			db_input('o69_grupo',30,$Io69_grupo,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To69_grupoexclusao?>">
       <?=@$Lo69_grupoexclusao?>
    </td>
    <td>
			<?
			db_input('o69_grupoexclusao',30,$Io69_grupoexclusao,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To69_nivel?>">
       <?=@$Lo69_nivel?>
    </td>
    <td>
			<?
			db_input('o69_nivel',30,$Io69_nivel,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
	<tr>
    <td nowrap title="<?=@$To69_labelrel?>">
      <?=@$Lo69_labelrel?>
    </td>
    <td>
			<?
			db_input('o69_labelrel',30,$Io69_labelrel,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To69_libnivel?>">
       <?=@$Lo69_libnivel?>
    </td>
    <td>
		<?
		$x = array("f"=>"NAO","t"=>"SIM");
		db_select('o69_libnivel',$x,true,$db_opcao,"");
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To69_librec?>">
       <?=@$Lo69_librec?>
    </td>
    <td>
			<?
			$x = array("f"=>"NAO","t"=>"SIM");
			db_select('o69_librec',$x,true,$db_opcao,"");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To69_libsubfunc?>">
       <?=@$Lo69_libsubfunc?>
    </td>
    <td>
			<?
			$x = array("f"=>"NAO","t"=>"SIM");
			db_select('o69_libsubfunc',$x,true,$db_opcao,"");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To69_libfunc?>">
       <?=@$Lo69_libfunc?>
    </td>
    <td>
			<?
			$x = array("f"=>"NAO","t"=>"SIM");
			db_select('o69_libfunc',$x,true,$db_opcao,"");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To69_verificaano?>">
       <?=@$Lo69_verificaano?>
    </td>
    <td>
			<?
			$x = array("f"=>"NAO","t"=>"SIM");
			db_select('o69_verificaano',$x,true,$db_opcao,"");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To69_manual?>">
       <?=@$Lo69_manual?>
    </td>
    <td>
			<?
			$x = array("f"=>"NAO","t"=>"SIM");
			db_select('o69_manual',$x,true,$db_opcao,"");
			?>
    </td>
  </tr>
    <tr >
    <td nowrap title="<?=@$To69_totalizador?>">
       <?=@$Lo69_totalizador?>
    </td>
    <td>
      <?
      $x = array("f"=>"NAO","t"=>"SIM");
      db_select('o69_totalizador',$x,true,$db_opcao,"onchange='js_verificaLinhaTotalizadora()'");
      ?>
    </td>
  </tr>
  <tr id="trorigem">
    <td nowrap title="<?=$To69_origem?>">
      <?=$Lo69_origem;?>
    </td>
    <td>
      <?php
      $aOrigemDados = array(
        0 => "Sem Origem",
        1 => "Balancete da Receita",
        2 => "Balancete da Despesa",
        3 => "Balancete de Verificação",
        4 => "Restos à Pagar"
      );
      db_select('o69_origem', $aOrigemDados, true, $db_opcao);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To69_desdobrarlinha?>">
       <?=@$Lo69_desdobrarlinha?>
    </td>
    <td>
      <?
      $x = array("f"=>"NAO","t"=>"SIM");
      db_select('o69_desdobrarlinha',$x,true,$db_opcao,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To69_ordem?>">
      <?=@$Lo69_ordem?>
    </td>
    <td>
      <?
      db_input('o69_ordem',10,$Io69_ordem,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To69_nivellinha?>">
      <?=@$Lo69_nivellinha?>
    </td>
    <td>
      <?
      db_input('o69_nivellinha',10,$Io69_nivellinha,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
   <tr>
      <td valign="top" colspan="2">
        <fieldset>
          <legend><b>Observação</b></legend>
          <textarea rows="4" style='width: 100%' id='txtObservacao' name="o69_observacao"><?=@$o69_observacao;?></textarea>
        </fieldset>
      </td>
    </tr>
  </table>
  </center>
	</fieldset>

<?
 if ($db_opcao != 1) {
   echo "<input name='novo' type='button'value='Novo' onclick='js_novo();' >";
 }
?>

<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao69_codparamrel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcparamseq','db_iframe_orcparamrel','func_orcparamrel.php?funcao_js=parent.js_mostraorcparamrel1|o42_codparrel|o42_descrrel|o69_codseq','Pesquisa',true,'0','1');
  }else{
     if(document.form1.o69_codparamrel.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_orcparamseq','db_iframe_orcparamrel','func_orcparamrel.php?chave_composta='+document.form1.o69_codparamrel.value+'&funcao_js=parent.js_chavecomposta','Pesquisa',false,'0','1');
     }else{
       document.form1.o42_descrrel.value = '';
     }
  }
}

function js_chavecomposta(o69_codseq, o42_descrel, erro){

	document.form1.o69_codseq.value   = o69_codseq;
  document.form1.o42_descrrel.value = o42_descrel;
  if(erro==true){
    document.form1.o69_codparamrel.focus();
    document.form1.o69_codparamrel.value = '';
  }
}

function js_novo(){
  parent.document.location.href = "orc1_orcparamseq001.php";
}

function js_mostraorcparamrel(chave,erro){
	document.form1.o42_descrrel.value = chave;
  if(erro==true){
    document.form1.o69_codparamrel.focus();
    document.form1.o69_codparamrel.value = '';
  }
}
function js_mostraorcparamrel1(chave1,chave2,chave3){

  document.form1.o69_codparamrel.value = chave1;
  document.form1.o42_descrrel.value    = chave2;
	// somamos mais 1 para o registro sempre incrementar a próxima
	// linha da tabela automaticamentes
  document.form1.o69_codseq.value      = chave3;
  document.form1.o69_codseq_anterior.value = ( chave3-1 );
  db_iframe_orcparamrel.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_orcparamseq','db_iframe_orcparamseq',
                      'func_orcparamseq.php?funcao_js=parent.js_preenchepesquisa|o69_codparamrel|o69_codseq'+
                       '&codigo_relatorio='+document.form1.o69_codparamrel.value,
                       'Pesquisa',true,'0','1');
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_orcparamseq.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}

  function js_verificaLinhaTotalizadora() {

    var sDisplayOrigem = '';
    if ($F('o69_totalizador') == 't') {

      sDisplayOrigem = 'none';
      $('o69_origem').value = '0';
    }
    $('trorigem').style.display = sDisplayOrigem;
  }
  js_verificaLinhaTotalizadora();
</script>