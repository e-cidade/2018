<?php
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

//MODULO: pessoal
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_utils.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrhpesdoc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("db12_uf");
$clrotulo->label("db12_codigo");
if (isset($db_opcaoal)) {

  $db_opcao = 33;
  $db_botao = false;
} else if (isset($opcao) && $opcao == "alterar") {

  $db_botao = true;
  $db_opcao = 2;
} else if (isset($opcao) && $opcao == "excluir") {

  $db_opcao = 3;
  $db_botao = true;
} else {

  $db_opcao = 1;
  $db_botao = true;
  if (isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro == false ) ){

    $rh16_titele    = "";
    $rh16_zonael    = "";
    $rh16_secaoe    = "";
    $rh16_reserv    = "";
    $rh16_catres    = "";
    $rh16_ctps_n    = "";
    $rh16_ctps_s    = "";
    $rh16_ctps_d    = "";
    $rh16_ctps_uf   = "";
    $rh16_pis       = "";
    $rh16_carth_n   = "";
    $r16_carth_cat  = "";
    $rh16_carth_val = "";
    $rh16_emissao   = "";
  }
}

if ($db_opcao == 1 || $db_opcao == 2 || $db_opcao == 11 || $db_opcao == 22) {
  
  $oDaoRhPesDoc    = db_utils::getDao("rhpesdoc");
  $sSqlDocServidor = $oDaoRhPesDoc->sql_query_file($rh16_regist);
  $rsDocServidor   = $oDaoRhPesDoc->sql_record($sSqlDocServidor);
  
  if ($oDaoRhPesDoc->numrows <= 0) {
    
    $db_opcao = 1;
    $db_botao = true;
  }
  
  $sSqlNome = "select z01_nome 
                 from cgm 
           inner join rhpessoal on cgm.z01_numcgm = rhpessoal.rh01_numcgm 
                where rh01_regist = {$rh16_regist}";
  $rsNome   = $oDaoRhPesDoc->sql_record($sSqlNome);
  $z01_nome = db_utils::fieldsMemory($rsNome, 0)->z01_nome;
  
}

?>
<form name="form1" method="post" action="">

<table border='0'><tr><td><Fieldset><legend><b>DOCUMENTOS </b></legend>
<table border='0'>
  <tr>
    <td nowrap title="<?=@$Trh16_regist?>">
       <?=@$Lrh16_regist;?>
    </td>
    <td nowrap colspan='10'>
    <?php
      db_input('rh16_regist',6,$Irh16_regist,true,'text',3," onchange='js_pesquisarh16_regist(false);'");
      db_input('z01_nome',30,$Iz01_nome,true,'text',3,'');
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh16_titele?>">
       <?=@$Lrh16_titele?>
    </td>
    <td>
<?
db_input('rh16_titele',15,$Irh16_titele,true,'text',$db_opcao,"")
?>
</td>
<td>
       <?=@$Lrh16_zonael?>
			 </td><td>
<?
db_input('rh16_zonael',4,$Irh16_zonael,true,'text',$db_opcao,"")
?>
</td>
<td>
       <?=@$Lrh16_secaoe?>
			 </td><td>

<?
db_input('rh16_secaoe',4,$Irh16_secaoe,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh16_reserv?>">
       <?=@$Lrh16_reserv?>
    </td>
    <td>
<?
db_input('rh16_reserv',15,$Irh16_reserv,true,'text',$db_opcao,"")
?>
 </td>
 <td>

 <?=@$Lrh16_catres?>
 </td><td>
 <?db_input('rh16_catres',4,$Irh16_catres,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh16_ctps_n?>">
       <?=@$Lrh16_ctps_n?>
    </td>
    <td>
<?
db_input('rh16_ctps_n',15,$Irh16_ctps_n,true,'text',$db_opcao,"")
?>
</td>
<td>
     <?=@$Lrh16_ctps_s?>
</td>
<td>
    <?
db_input('rh16_ctps_s',4,$Irh16_ctps_s,true,'text',$db_opcao,"")
?>
 </td>
 <td>
       <?=@$Lrh16_ctps_d?>
			 </td>
			 <td>
<?
db_input('rh16_ctps_d',4,$Irh16_ctps_d,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh16_ctps_uf?>">
       <?
       db_ancora(@$Lrh16_ctps_uf,"",3);
       ?>
    </td>
    <td colspan='3'>
<?
$result_uf = $cldb_uf->sql_record($cldb_uf->sql_query_file(null,"db12_codigo as rh16_ctps_uf,db12_uf"));
db_selectrecord("rh16_ctps_uf",$result_uf,true,$db_opcao,"","","","0-Nenhum...");
?>
    </td>
    <td nowrap title="<?=@$Trh16_emissao?>">
       <?=@$Lrh16_emissao?>
    </td>
    <td>
			<?
			$rh16_emissao_val_ano = '';
			$rh16_emissao_val_mes = '';
			$rh16_emissao_val_dia = '';
			
			if( isset($rh16_emissao) && $rh16_emissao != ""){
				list( $rh16_emissao_val_ano, $rh16_emissao_val_mes, $rh16_emissao_val_dia ) = split( "[-]", $rh16_emissao );
			}
			db_inputdata('rh16_emissao',$rh16_emissao_val_dia,$rh16_emissao_val_mes,$rh16_emissao_val_ano,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh16_pis?>">
       <?=@$Lrh16_pis?>
    </td>
    <td>
			<?
			db_input('rh16_pis',15,$Irh16_pis,true,'text', 3,"onblur = js_validaPis(this.value);")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh16_carth_n?>">
       <?=@$Lrh16_carth_n?>
    </td>
    <td>
			<?
			db_input('rh16_carth_n',15,$Irh16_carth_n,true,'text',$db_opcao,"")
			?>
    </td>
    <td nowrap title="<?=@$Tr16_carth_cat?>">
       <?=@$Lr16_carth_cat?>
    </td>
    <td>
			<?
			db_input('r16_carth_cat',4,$Ir16_carth_cat,true,'text',$db_opcao,"")
			?>
    </td>
    <td nowrap title="<?=@$Trh16_carth_val?>">
       <?=@$Lrh16_carth_val?>
    </td>
    <td>
			 <?
			  db_inputdata('rh16_carth_val',@$rh16_carth_val_dia,@$rh16_carth_val_mes,@$rh16_carth_val_ano,true,'text',$db_opcao,"")
			 ?>
    </td>
  </tr>
  <tr>

	</fieldset></td></tr></table>
	</tr>
	<tr>
    <td colspan="6" align="center">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" onclick="js_validaDatas();" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
    </td>
  </tr>
  </table>
</form>
<script>
function js_pesquisarh16_regist(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo.iframe_rhpesdoc','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostrarhpessoal1|rh01_regist|rh01_numcgm','Pesquisa',true,'0');
  } else {
     if (document.form1.rh16_regist.value != '') {
        js_OpenJanelaIframe('top.corpo.iframe_rhpesdoc','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.rh16_regist.value+'&funcao_js=parent.js_mostrarhpessoal','Pesquisa',false,'0');
     } else {
       document.form1.rh01_numcgm.value = '';
     }
  }
}

function js_mostrarhpessoal(chave, erro) {

  document.form1.rh01_numcgm.value = chave;
  if (erro == true) {

    document.form1.rh16_regist.focus();
    document.form1.rh16_regist.value = '';
  }
}

function js_mostrarhpessoal1(chave1, chave2) {

  document.form1.rh16_regist.value = chave1;
  document.form1.rh01_numcgm.value = chave2;
  db_iframe_rhpessoal.hide();
}

function js_pesquisarh16_ctps_uf(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo.iframe_rhpesdoc','db_iframe_db_uf','func_db_uf.php?funcao_js=parent.js_mostradb_uf1|db12_codigo|db12_uf','Pesquisa',true,'0');
  } else {

     if (document.form1.rh16_ctps_uf.value != '') {
        js_OpenJanelaIframe('top.corpo.iframe_rhpesdoc','db_iframe_db_uf','func_db_uf.php?pesquisa_chave='+document.form1.rh16_ctps_uf.value+'&funcao_js=parent.js_mostradb_uf','Pesquisa',false,'0');
     } else {
       document.form1.db12_uf.value = '';
     }
  }
}

function js_mostradb_uf(chave, erro) {

  document.form1.db12_uf.value = chave;
  if (erro == true) {

    document.form1.rh16_ctps_uf.focus();
    document.form1.rh16_ctps_uf.value = '';
  }
}

function js_mostradb_uf1(chave1, chave2) {

  document.form1.rh16_ctps_uf.value = chave1;
  document.form1.db12_uf.value = chave2;
  db_iframe_db_uf.hide();
}

function js_validaPis(pis) {

  if (pis != '') {

    if (!js_ChecaPIS(pis)) {

      alert("Pis inválido. Verifique.");
      document.form1.rh16_pis.focus();
      document.form1.rh16_pis.value = '';
      return(false);
    } else {
      return(true);
    }
  }
}
</script>