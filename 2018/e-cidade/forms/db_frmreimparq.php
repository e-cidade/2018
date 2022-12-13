<?
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

$clrotulo = new rotulocampo;
$clempagegera->rotulo->label();
$clempagetipo->rotulo->label();
?>
<center>
<form name="form1" method="post">
<div style="width: 550px;">
	<fieldset style="margin-top:50px;">
	<legend><strong>Reimpressão de Arquivo Txt</strong></legend>
		<table border='0'>
		  <tr>
		    <td  align="left" nowrap title="<?=$Te87_codgera?>"> <? db_ancora(@$Le87_codgera,"js_pesquisa_gera(true);",1);?>  </td>
		    <td align="left" nowrap>
				  <?
				     db_input("e87_codgera",10,$Ie87_codgera,true,"text",1,"onchange='js_pesquisa_gera(false);'");
				     db_input("e87_descgera",40,$Ie87_descgera,true,"text",3);
				  ?>
		    </td>
		  </tr>
		  <tr>
		    <td align='left'>
		      <b>Data geração:</b>
		    </td>
		    <td>
		      <?
		      if(!isset($dtin_dia)){
		        $dtin_dia = date('d',db_getsession('DB_datausu'));
		      }
		      if(!isset($dtin_mes)){
		        $dtin_mes = date('m',db_getsession('DB_datausu'));
		      }
		      if(!isset($dtin_ano)){
		        $dtin_ano = date('Y',db_getsession('DB_datausu'));
		      }
		      db_inputdata('dtin',@$dtin_dia,@$dtin_mes,@$dtin_ano,true,'text',1);
		      ?>
		    </td>
		  </tr>
		  <tr>
		    <td align='left'>
		      <b>Autoriza pgto.:</b>
		    </td>
		    <td>
		      <?
		        db_inputdata('deposito',@$deposito_dia,@$deposito_mes,@$deposito_ano,true,'text',1);
		      ?>
		    </td>
		  </tr>
		  <tr>
		    <td colspan="2" align="center"><br>
		    </td>
		  </tr>
		</table>
	</fieldset>
	<div style= 'margin-top:10px;'>
    <input name="imprime" type="submit" value="Imprimir arquivo">
	</div>
</div>
</form>
</center>

<script>
//--------------------------------
function js_pesquisa_gera(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empagegera','func_empagegera.php?filtrocnab=1&funcao_js=parent.js_mostragera1|e87_codgera|e87_descgera','Pesquisa',true);
  }else{
     if(document.form1.e87_codgera.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_empagegera','func_empagegera.php?filtrocnab=1&pesquisa_chave='+document.form1.e87_codgera.value+'&funcao_js=parent.js_mostragera','Pesquisa',false);
     }else{
       document.form1.e87_descgera.value = '';
     }
  }
}
function js_mostragera(chave,erro){
  document.form1.e87_descgera.value = chave;
  if(erro==true){
    document.form1.e87_codgera.focus();
    document.form1.e87_codgera.value = '';
  }
}
function js_mostragera1(chave1,chave2){
  document.form1.e87_codgera.value = chave1;
  document.form1.e87_descgera.value = chave2;
  db_iframe_empagegera.hide();
}
//--------------------------------
</script>