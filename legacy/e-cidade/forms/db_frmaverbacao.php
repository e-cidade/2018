<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

$claverbatipo = new cl_averbatipo;
$claverbacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("j77_codproc");
$clrotulo->label("p58_requer");
$clrotulo->label("j78_protocolo");
$clrotulo->label("j78_matric");
$clrotulo->label("p58_numero");
$db_opcaonome= $db_opcao;

?>
<form name="form1" method="post" action="" >

<fieldset>
  <legend>Averbação</legend>

<?
  db_input('j100_sequencial',5,"",true,'hidden',1);
  db_input('j101_sequencial',5,"",true,'hidden',1);
  db_input('j102_sequencial',5,"",true,'hidden',1);
  db_input('j103_sequencial',5,"",true,'hidden',1);
  db_input('j104_sequencial',5,"",true,'hidden',1);
?>
<table border="0">
  <tr>
    <td align="right" nowrap title="<?=@$Tj75_codigo?>">
       <?=@$Lj75_codigo?>
    </td>
    <td>
<?
db_input('j75_codigo',15,$Ij75_codigo,true,'text',3,"");
db_input('debitos',6,'',true,'hidden',3,"");
?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tj75_matric?>">
       <?
       db_ancora(@$Lj75_matric,"js_consulta_matric();",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('j75_matric',15,$Ij75_matric,true,'text',3," onchange='js_pesquisaj75_matric(false);'")
?>
       <?
db_input('z01_nome',50,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="">
       <strong>Setor/Quadra/Lote:</strong>
    </td>
    <td>
<?
if (isset($j75_matric)){
$result_info=$cliptubase->sql_record($cliptubase->sql_query($j75_matric));
if ($cliptubase->numrows>0){
	db_fieldsmemory($result_info,0);
	$set_qua_lot = "$j34_setor / $j34_quadra / $j34_lote";
}
}
db_input('set_qua_lot',15,"",true,'text',3,"")
?>
    </td>
  </tr>

  <tr>
    <td align="right" nowrap title="<?=@$Tj75_obs?>">
       <?=@$Lj75_obs?>
    </td>
    <td>
<?
db_textarea('j75_obs',0,66,$Ij75_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td align="right"  nowrap title="<?=@$Tj75_tipo?>">
       <?=@$Lj75_tipo?>
    </td>
    <td>
<?
if ($db_opcao==1){
	$opc=1;
}else{
	$opc=3;
}

$sqltipo ="select * from averbatipo where j93_datalimite >= current_date or j93_datalimite is null order by j93_codigo";
$result_tipo = db_query($sqltipo);
$linhas_tipo = pg_num_rows($result_tipo);
db_selectrecord('j75_tipo',$result_tipo,true,$opc,"","","","","js_submit();");
if (!isset($j75_tipo)){
	if($linhas_tipo>0){
		db_fieldsmemory($result_tipo,0);
		$j75_tipo = $j93_codigo;

	}
}
if(isset($j75_tipo)){
  $sqlgrupo ="select j93_averbagrupo from averbatipo where  j93_codigo=$j75_tipo";
  $result_grupo = db_query($sqlgrupo);
  db_fieldsmemory($result_grupo,0);

}
db_input('j93_averbagrupo',5,"",true,'hidden',3,'')

?>
<?=@$Lj75_dttipo?>
<?
db_inputdata('j75_dttipo',@$j75_dttipo_dia,@$j75_dttipo_mes,@$j75_dttipo_ano,true,'text',$db_opcao,"onChange='js_validata();'","","","","","","js_validata()");
$datahoje = date(("d/m/Y"),db_getsession("DB_datausu"));
db_input('datahoje',5,"",true,'hidden',3,'');
?>

    </td>
  </tr>

  <tr>
    <td align="right" nowrap title="<?=@$Tj77_codproc?>">
       <?php db_ancora(@$Lp58_numero,"js_pesquisaj77_codproc(true);",$db_opcao); ?>
    </td>
    <td>
      <?php
        db_input('p58_numero', 12, $Ip58_numero, true, 'text', $db_opcao," onchange='js_pesquisaj77_codproc(false);'");
        db_input('j77_codproc',15, 0, true, 'hidden', 1);
        db_input('p58_requer',50,$Ip58_requer,true,'text',3,'');
       ?>
    </td>
  </tr>
  <?
    if (isset($j93_averbagrupo)&&$j93_averbagrupo==2){
    ?>
    <tr>
    <td align="right" nowrap title="<?=@$Tj78_matric?>">
       <?=@$Lj78_matric?>
    </td>
    <td>
<?
db_input('j78_matric',15,$Ij78_matric,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
    <tr>
    <td align="right" nowrap title="<?=@$Tj78_protocolo?>">
       <?=@$Lj78_protocolo?>
    </td>
    <td>
<?
db_input('j78_protocolo',15,$Ij78_protocolo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
    <?
    }else if (isset($j93_averbagrupo)&&$j93_averbagrupo==1){
  	?>
    <tr>
    <td align="right" nowrap title="<?=@$Tj94_livro?>">
       <?=@$Lj94_livro?>
    </td>
    <td>
<?
db_input('j94_livro',15,$Ij94_livro,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
    <tr>
    <td align="right" nowrap title="<?=@$Tj94_folha?>">
       <?=@$Lj94_folha?>
    </td>
    <td>
<?
db_input('j94_folha',15,$Ij94_folha,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tj94_numero?>">
       <?=@$Lj94_numero?>
    </td>
    <td>
<?
db_input('j94_numero',15,$Ij94_numero,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
   <tr>
    <td align="right" nowrap title="<?=@$Tj94_tabelionato?>">
       <?=@$Lj94_tabelionato?>
    </td>
    <td>
<?
db_input('j94_tabelionato',15,$Ij94_tabelionato,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
    <?
    }else if (isset($j93_averbagrupo)&&$j93_averbagrupo==4){

		?>
		<tr>
            <td align="right" nowrap title="<?=@$Tj100_processojudicial?>">
           <?=@$Lj100_processojudicial?>
           </td>
           <td>
              <?
			  db_input('j100_processojudicial',15,$Ij100_processojudicial,true,'text',$db_opcao,"")
			  ?>
           </td>
       </tr>
	   <tr>
            <td align="right" nowrap title="<?=@$Tj101_nomeespolio?>">
           <?
		   db_ancora($Lj100_nomeespolio,"js_pesquisaz01_numcgm(true);",$db_opcao);
		   ?>
           </td>
           <td>
              <?
			  if(isset($z01_numcgm1) and $z01_numcgm1!=""){
			  	$db_opcaonome=3;
			  }
			  if($db_opcao == 3 || $db_opcao == 33){
			  	$db_opcaonome = 3;
				}

		      db_input("z01_numcgm1",15,$Iz01_numcgm,true,"text",$db_opcao,"onchange='js_pesquisaz01_numcgm(false);'");
	          db_input('j100_nomeespolio',50,$Ij100_nomeespolio,true,'text',$db_opcaonome,"")
			  ?>
           </td>
       </tr>
	   <?
	}else if (isset($j93_averbagrupo)&&$j93_averbagrupo==5){

		?>
		<tr>
            <td align="right" nowrap title="<?=@$Tj101_processojudicial?>">
           <?=@$Lj101_processojudicial?>
           </td>
           <td>
              <?
			  db_input('j101_processojudicial',15,$Ij101_processojudicial,true,'text',$db_opcao,"")
			  ?>
           </td>
       </tr>

	   <?
	}else if (isset($j93_averbagrupo)&&$j93_averbagrupo==6){

	   ?>
	    <tr>
           <td align="right" nowrap >
             <strong>Guia do sistema:</strong>
           </td>
           <td>
           <?

           $aOpcoes = array('1'=>'Sim','2'=>'Não');
           db_select('guia',$aOpcoes,true,$db_opcao,"onChange='js_mostraGuiaSistema(document.form1.guia.value);'");
           ?>
           </td>
        </tr>
	   <tr id="guia_sim" >
           <td align="right" nowrap title="<?=@$Tj104_guia?>">
           <?
           db_ancora(@$Lj104_guia,"js_pesquisait01_guia(true);",$db_opcao);
           ?>
           </td>
           <td>
           <?
           db_input('j104_guia',15,$Ij104_guia,true,'text',$db_opcao," onchange='js_pesquisait01_guia(false);'");
           db_input('nome',50,"",true,'text',3);
		       db_input('j103_itbi',10,"",true,'hidden',1);
           ?>
           </td>
        </tr>
		<tr id="guia_nao" >
           <td align="right" nowrap title="<?=@$Tj104_guia?>">
           <strong>Guia:</strong>
           </td>
           <td>
           <?
           db_input('guianao',15,$Ij104_guia,true,'text',$db_opcao,"");
           ?>
           </td>
        </tr>

  	<?
	}
	?>
    <tr>
    <td align="right"  nowrap title="<?=@$Tj75_situacao?>">
       <?=@$Lj75_situacao?>
    </td>
    <td>
    <?php

      if (!isset($j75_situacao)){
      	$j75_situacao = 1;
      }

      $aOpcoes = array('1'=>'Não Processado','2'=>'Processado');
      db_select('j75_situacao',$aOpcoes,true,3,"");

    ?></td>
  </tr>
    </table>
</fieldset>
  <?php

   if(!isset($cadastromunicipal)) {

     if(isset($botaoprocessar) && $botaoprocessar== true ){

       /**
        * Validamos se existe cgm vinculado a averbação para permitir o processamento
        */
       if(!empty($chavepesquisa)){

         $sSqlVerificaVinculoCgm = $claverbacgm->sql_query_file(null,"*",null,"j76_averbacao = $chavepesquisa");
         $rsVinculoAverbacaoCgm  = $claverbacgm->sql_record($sSqlVerificaVinculoCgm);

         if ($claverbacgm->numrows == 0) {

           db_msgbox('Não é permitido processamento de averbação sem CGM Vinculado.');
           $db_botao = false;
         }
       }
   ?>
      <input name="processar" type="submit" id="db_opcao" value="Processar" <?=($db_botao==false?"disabled":"")?> >
    <?}else{ ?>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    <?
    }
    ?>

    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" />

    <? if($db_opcao==2){ ?>
      <input name="novo" type="button" id="novo" value="Nova Averbação" onclick="js_novaAverbacao();" />
    <?
       }
   }
?>
</form>
<script type="text/javascript">

function js_submit(){
  document.form1.submit();
}

function js_consulta_matric(){
	js_OpenJanelaIframe('','db_iframe','cad3_conscadastro_002.php?cod_matricula='+document.form1.j75_matric.value,'Consulta Matrcula',true,0);
}

function js_pesquisaj77_codproc(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_protprocesso_protocolo.php?funcao_js=parent.js_mostraprotprocesso1|dl_código_do_processo|p58_numero|dl_nome_ou_razão_social','Pesquisa',true);
  }else{

    var iNumeroProcesso = document.getElementById('p58_numero').value;

    if ( empty(iNumeroProcesso) ) {
      return false;
    }

    js_OpenJanelaIframe('','db_iframe_cgm','func_protprocesso_protocolo.php?pesquisa_chave='+ iNumeroProcesso + '&funcao_js=parent.js_mostraprotprocesso&sCampoRetorno=p58_codproc','Pesquisa',false);
  }
}

function js_mostraprotprocesso(iCodigoProcesso, sNome, lErro){

  document.form1.p58_requer.value = sNome;

  if (lErro) {

    document.form1.p58_numero.focus();
    document.form1.p58_numero.value = '';
    document.form1.j77_codproc.value = '';
  }

  document.form1.j77_codproc.value = iCodigoProcesso;
}

function js_mostraprotprocesso1(iCodigoProcesso, sNumeroProcesso, sNome) {

  document.form1.j77_codproc.value = iCodigoProcesso;
  document.form1.p58_numero.value  = sNumeroProcesso;
  document.form1.p58_requer.value  = sNome;
  db_iframe_cgm.hide();
}

function js_pesquisaj75_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_iptubase','func_iptubase.php?funcao_js=parent.js_mostraiptubase1|j01_matric|j01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.j75_matric.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_iptubase','func_iptubase.php?pesquisa_chave='+document.form1.j75_matric.value+'&funcao_js=parent.js_mostraiptubase','Pesquisa',false);
     }else{
       document.form1.j01_numcgm.value = '';
     }
  }
}
function js_mostraiptubase(chave,erro){
  document.form1.j01_numcgm.value = chave;
  if(erro==true){
    document.form1.j75_matric.focus();
    document.form1.j75_matric.value = '';
  }
}
function js_mostraiptubase1(chave1,chave2){
  document.form1.j75_matric.value = chave1;
  document.form1.j01_numcgm.value = chave2;
  db_iframe_iptubase.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_averbacao','func_averbacao.php?funcao_js=parent.js_preenchepesquisa|j75_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_averbacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_pesquisaz01_numcgm(mostra){

  if(mostra==true)  {
    js_OpenJanelaIframe('','func_nome','func_nome.php?funcao_js=parent.js_mostranumcgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else {
     if(document.form1.z01_numcgm1.value != ''){

        js_OpenJanelaIframe('','func_nome','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm1.value+'&funcao_js=parent.js_mostranumcgm','Pesquisa',false);
     }else{
       document.form1.j100_nomeespolio.value = "";
	   document.form1.j100_nomeespolio.readOnly = false;
	   document.form1.j100_nomeespolio.style.backgroundColor='#FFFFFF';
     }
  }
}

function js_mostranumcgm(erro,chave){
  document.form1.j100_nomeespolio.value = chave;
  document.form1.j100_nomeespolio.readOnly = true;
  document.form1.j100_nomeespolio.style.backgroundColor='#DEB887';
  if(erro==true)  {
    document.form1.z01_numcgm1.value = '';
    document.form1.z01_numcgm1.focus();
	document.form1.j100_nomeespolio.readOnly = false;
	document.form1.j100_nomeespolio.style.backgroundColor='#FFFFFF';

  }
}

//Função que retorna a pesquisa para o formulario com os dois campos NUMCGM e NOME
//Caso a função js_pesquisaz01_numcgm tenha sido TRUE.
function js_mostranumcgm1(chave1,chave2){
  document.form1.z01_numcgm1.value = chave1;
  document.form1.j100_nomeespolio.value   = chave2;
  document.form1.j100_nomeespolio.readOnly = true;
  document.form1.j100_nomeespolio.style.backgroundColor='#DEB887';
  func_nome.hide();
}

function js_pesquisait01_guia(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_itbi','func_itbiliberadoalt.php?matric='+document.form1.j75_matric.value+'&funcao_js=parent.js_mostraitbi1|it01_guia|dl_comprador','Pesquisa',true);
  }else{
     if(document.form1.j104_guia.value != ''){
        js_OpenJanelaIframe('','db_iframe_itbi','func_itbiliberadoalt.php?pesquisa_chave='+document.form1.j104_guia.value+'&matric='+document.form1.j75_matric.value+'&funcao_js=parent.js_mostraitbi','Pesquisa',false);
     }else{
       document.form1.j104_guia.value = '';
     }
  }
}
function js_mostraitbi(chave,erro){
  if(erro==true){
  	alert('Guia não encontada no sistema para esta matricula ou não liberada.');
    //document.form1.j104_guia.focus();
	document.form1.j104_guia.value = "";
    document.form1.j103_itbi.value = "";
	document.form1.nome.value = chave;

  }else{
  	document.form1.nome.value = chave;
    document.form1.j103_itbi.value = document.form1.j104_guia.value ;

  }
}
function js_mostraitbi1(chave1,chave2){
  document.form1.j104_guia.value = chave1;
  document.form1.j103_itbi.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_itbi.hide();
}

function js_mostraGuiaSistema(id){

	if(id == 1){
		document.getElementById("guia_sim").style.display='';
		document.getElementById("guia_nao").style.display='none';
		document.form1.guianao.value = "";

	}else{
		document.getElementById("guia_sim").style.display='none';
		document.getElementById("guia_nao").style.display='';
		document.form1.j104_guia.value = "";
        document.form1.j103_itbi.value = "";
        document.form1.nome.value = "";

	}
}

function js_validata(){
	var retorno = js_comparadata(document.form1.j75_dttipo.value,document.form1.datahoje.value,"<=");
	if(retorno == false){
		alert ('A data informada deve ser igual ou menor que a data de hoje.');
		document.form1.j75_dttipo.value = "";
	}
}

function js_novaAverbacao(){
	parent.location.href = 'cad4_averbacao001.php';
}

<?
if (isset($j93_averbagrupo) && $j93_averbagrupo == 6) {
  echo "js_mostraGuiaSistema(document.form1.guia.value); ";
}
?>
</script>