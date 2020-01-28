<?
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
include(modification("dbforms/db_classesgenericas.php"));
include(modification("libs/db_libdicionario.php"));
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clpensao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("db90_descr");
$clrotulo->label("rh77_retencaotiporec");
$clrotulo->label("e21_descricao");

?>
<form name="form1" method="post" action="">
<center>
<table border="0" height="90%" width="95%">
  <tr>
    <td>
      <fieldset>
        <center>
        <table border="0" width="63%">
          <tr>
            <td align="right" nowrap title="Digite o Ano / Mes de competência" >
              <strong>Ano / Mês:</strong>
            </td>
            <td nowrap>
              <?
              $r52_anousu = db_anofolha();
              db_input('r52_anousu',4,$Ir52_anousu,true,'text',3,'');
              ?>
              &nbsp;/&nbsp;
              <?
              $r52_mesusu = db_mesfolha();
              db_input('r52_mesusu',2,$Ir52_mesusu,true,'text',3,'');
              ?>
            </td>
            <td nowrap title="<?=@$Tr52_dtincl?>" align="right">
              <?=@$Lr52_dtincl?>
            </td>
            <td nowrap>
              <?
              if(isset($r52_dtincl)){
                if((strpos(strtoupper("#".$r52_dtincl),'-')+0) > 0 ){
                  $arr_dtincl = split("-",$r52_dtincl);
      	          $r52_dtincl_dia = $arr_dtincl[2];
      	          $r52_dtincl_mes = $arr_dtincl[1];
      	          $r52_dtincl_ano = $arr_dtincl[0];
                }else{
              		$arr_dtincl = split("/",$r52_dtincl);
      	          $r52_dtincl_dia = $arr_dtincl[0];
      	          $r52_dtincl_mes = $arr_dtincl[1];
      	          $r52_dtincl_ano = $arr_dtincl[2];
                }
              }else if(!isset($r52_dtincl_dia)){
      	        $r52_dtincl_dia = date("d",db_getsession("DB_datausu"));
      	        $r52_dtincl_mes = date("m",db_getsession("DB_datausu"));
      	        $r52_dtincl_ano = date("Y",db_getsession("DB_datausu"));
              }
              db_inputdata('r52_dtincl',@$r52_dtincl_dia,@$r52_dtincl_mes,@$r52_dtincl_ano,true,'text',$db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td align="right" nowrap title="<?=@$Tr52_regist?>">
              <?
              db_ancora(@$Lr52_regist,"js_pesquisar52_regist(true);",($db_opcao==1?"1":"3"));
              ?>
            </td>
            <td colspan="3" nowrap>
              <?
              db_input('r52_regist',8,$Ir52_regist,true,'text',($db_opcao==1?"1":"3")," onchange='js_pesquisar52_regist(false);' tabIndex='1' ")
              ?>
              <?
              db_input('z01_nome', 60, $Iz01_nome, true, 'text', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td align="right" nowrap title="<?=@$Tr52_numcgm?>">
              <?
              db_ancora(@$Lr52_numcgm,"js_pesquisar52_numcgm(true);",($db_opcao==1?"1":"3"));
              ?>
            </td>
            <td colspan="3" nowrap>
              <?
              db_input('r52_numcgm',8,$Ir52_numcgm,true,'text',($db_opcao==1?"1":"3"),"onchange='js_pesquisar52_numcgm(false);' tabIndex='2' ")
              ?>
              <?
              db_input('z01_nome',60,$Iz01_nome,true,'text',3,'','z01_nome02')
              ?>
            </td>
          </tr>
          <tr>
            <td align="right" nowrap title="<?=@$Tr52_formul?>">
              <?=@$Lr52_formul?>
            </td>
            <td colspan="3"> 
              <?
              db_textarea('r52_formul',4,69,$Ir52_formul,true,'text',$db_opcao,"onchange='js_desabilita(this.value);' tabIndex='3' onFocus='js_mostrar_dados_formula(true);' onBlur='js_mostrar_dados_formula(false);'");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tr52_perc?>" align="right">
              <?=@$Lr52_perc?>
            </td>
            <td nowrap>
              <?
              db_input('r52_perc',8,$Ir52_perc,true,'text',$db_opcao," tabIndex='4' ");
              ?>
            </td>
            <td nowrap title="<?=@$Tr52_vlrpen?>" align="right">
              <?=@$Lr52_vlrpen?>
            </td>
            <td nowrap>
              <?
              db_input('r52_vlrpen',19,$Ir52_vlrpen,true,'text',$db_opcao," tabIndex='5' ");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="<?=@$Trh77_retencaotiporec?>">
              <?
                db_ancora(@$Lrh77_retencaotiporec,"js_pesquisarh77_retencaotiporec(true);",$db_opcao);
              ?>
            </td>
            <td colspan="5"> 
              <?php
                db_input('rh77_retencaotiporec',8,$Irh77_retencaotiporec,true,'text',$db_opcao," onchange='js_pesquisarh77_retencaotiporec(false);'");
                db_input('e21_descricao',60,'',true,'text',3,'');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="<?=$Tr52_relacaodependencia?>">
              <label for="r52_relacaodependencia">
                <?php
                 echo $Lr52_relacaodependencia;
                ?>
              </label>
            </td>
            <td>
              <?php
              $aOpcoes  = getValoresPadroesCampo('r52_relacaodependencia');
              ksort($aOpcoes);
              db_select('r52_relacaodependencia', $aOpcoes, true, $db_opcao);

              ?>
            </td>
          </tr>
        </table>      
        </center>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
      <input type="hidden" id="rh139_contabancaria" name="rh139_contabancaria" value="<?= (isset($rh139_contabancaria)) ? $rh139_contabancaria : null ?>" />
      <div id="ctnContaBancariaServidor"></div> 
    </td>
  </tr>
  <tr>
    <td>
      <fieldset>
        <legend><strong>DESCONTAR</strong></legend>
        <center>
        <table width="100%">
          <tr>
            <td nowrap title="<?=@$Tr52_pag13?>" align="right">
              <?=@$Lr52_pag13?>
            </td>
            <td nowrap align="left">
              <?
              $x = array("f"=>"NAO","t"=>"SIM");
              db_select('r52_pag13',$x,true,$db_opcao," tabIndex='11' onchange='js_desabilita();' ");
              ?>
            </td>
            <td nowrap title="<?=@$Tr52_pagfer?>" align="right">
              <?=@$Lr52_pagfer?>
            </td>
            <td nowrap align="left">
              <?
              $x = array("f"=>"NAO","t"=>"SIM");
              db_select('r52_pagfer',$x,true,$db_opcao," tabIndex='12' ");
              ?>
            </td>
            <td nowrap title="<?=@$Tr52_pagcom?>" align="right">
              <?=@$Lr52_pagcom?>
            </td>
            <td nowrap align="left">
              <?
              $x = array("f"=>"NAO","t"=>"SIM");
              db_select('r52_pagcom',$x,true,$db_opcao," tabIndex='13' ");
              ?>
            </td>
            <?php if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) { ?>
            
              <td nowrap title="<?=@$Tr52_pagasuplementar?>" align="right">
                <?=$Lr52_pagasuplementar?>
              </td>
              <td nowrap align="left">
                <?
                  $aSuplementar = array("f"=>"NAO", "t"=>"SIM");
                  db_select('r52_pagasuplementar',$aSuplementar,true,$db_opcao," tabIndex='15' onchange='js_desabilita();'");
                ?>
              </td>
            <?php } ?>

            <td nowrap title="<?=@$Tr52_pagres?>" align="right">
              <?=@$Lr52_pagres?>
            </td>
            <td nowrap align="left">
              <?
              $x = array("f"=>"NAO","t"=>"SIM");
              db_select('r52_pagres',$x,true,$db_opcao," tabIndex='14' ");
              ?>
            </td>
            <td nowrap title="<?=@$Tr52_adiantamento13?>" align="right">
              <?=@$Lr52_adiantamento13?>
            </td>
            <td nowrap align="left">
              <?
                $aAdiantamento13 = array("f"=>"NAO", "t"=>"SIM");
                db_select('r52_adiantamento13',$aAdiantamento13,true,$db_opcao," tabIndex='15' onchange='js_desabilita();' disabled ");
              ?>
            </td>
            <td nowrap title="<?=@$Tr52_percadiantamento13?>" align="right">
              <?=@$Lr52_percadiantamento13?>
            </td>
            <td nowrap align="left">
              <?
                db_input('r52_percadiantamento13',10,$Ir52_percadiantamento13,true,'text',$db_opcao," tabIndex='16' disabled ");
              ?>
            </td>
          </tr>
        </table>      
        </center>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="4" width="100%" align="center">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
             type="submit" id="db_opcao" 
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
             <?=($db_botao==false?"disabled":"")?> onclick="return js_enviar();" tabIndex="14">
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisar_pensao();" 
             tabIndex="15" onblur="<?=($db_opcao==1?'document.form1.r52_regist.select();':'document.form1.novo.select();')?>">
      <?
      if($db_opcao != 1 && (!isset($db_opcaoal) || (isset($db_opcaoal) && $db_opcaoal != 33))){
        echo '<input name="novo" type="button" id="novo" value="Novo" onclick="location.href=\'pes1_pensao001.php?clicar=clicar&db_opcaoal='.@$db_opcaoal.'&r52_regist='.@$r52_regist.'\'"  tabIndex="16" onblur="document.form1.r52_formul.select();">';
      }
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="4" width="100%" height="60%" valign="top"  align="center">
      <?
      $dbwhere = " r52_anousu=".$r52_anousu." and r52_mesusu=".$r52_mesusu." and r52_regist =".@$r52_regist;
      if(isset($r52_numcgm) && trim($r52_numcgm)!="" && !isset($incluir)){
        $dbwhere .= " and r52_numcgm <> ".$r52_numcgm;
      }

      $sSuplementar = '';
      if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
        $sSuplementar = 'case when r52_pagasuplementar = \'t\' then \'SIM\' else \'NÃO\' end as r52_pagasuplementar,';
      }

      $sql = $clpensao->sql_query_dados(
                                        null,
                                        null,
                                        null,
                                        null,
                                        "
                                         r52_anousu,
                                         r52_mesusu,
                                         r52_regist,
                                         r52_numcgm,
                                         cgm.z01_numcgm,
                                         cgm.z01_nome,
                                         r52_vlrpen,
                                         r52_perc,
                                         r52_codbco,
                                         r52_formul,
                                         case when r52_pag13='t' then 'SIM' else 'NÃO' end as r52_pag13,
                                         case when r52_pagfer='t' then 'SIM' else 'NÃO' end as r52_pagfer,
                                         case when r52_pagcom='t' then 'SIM' else 'NÃO' end as r52_pagcom,
                                         case when r52_pagres='t' then 'SIM' else 'NÃO' end as r52_pagres,
                                         {$sSuplementar}
                                         case when r52_adiantamento13='t' then 'SIM' else 'NÃO' end as r52_adiantamento13,
                                         r52_percadiantamento13
                                        ",
                                        "
                                         cgm.z01_nome
                                        ",
                                        $dbwhere
                                       );
      $sCampos  = "z01_numcgm, z01_nome, r52_vlrpen, r52_perc, r52_formul, r52_pag13, r52_pagfer, r52_pagres, "; 

      if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
        $sCampos .= 'r52_pagasuplementar, ';
      }
      
      $sCampos .= "r52_pagcom, r52_adiantamento13, r52_percadiantamento13";



      $chavepri= array("r52_anousu"=>$r52_anousu,"r52_mesusu"=>$r52_mesusu,"r52_regist"=>@$r52_regist,"r52_numcgm"=>@$r52_numcgm);
      $cliframe_alterar_excluir->chavepri = $chavepri;
      $cliframe_alterar_excluir->sql      = $sql;
      $cliframe_alterar_excluir->campos   = $sCampos;
      $opcoes_registros = 1;
      if(isset($db_opcaoal) && $db_opcaoal == 33){
        $opcoes_registros = 4;
      }
      $cliframe_alterar_excluir->opcoes   = $opcoes_registros;
      $cliframe_alterar_excluir->legenda  = "Pensões Lançadas";
      $cliframe_alterar_excluir->iframe_height = "90%";
      $cliframe_alterar_excluir->alignlegenda  = "left";
      $cliframe_alterar_excluir->iframe_width  = "95%";
      $cliframe_alterar_excluir->fieldset = true;
      $cliframe_alterar_excluir->iframe_alterar_excluir(1);

      ?>
    </td>
  </tr>
  <tr>
    <td colspan="4" align="center" id="mostrar_dados_formula">
    </td>
  </tr>
</table>
</center>
<?
db_input('db_opcaoal',2,0,true,'hidden',3,"");
db_input('r52_valor',15,$Ir52_valor,true,'hidden',$db_opcao,"");
db_input('r52_valcom',15,$Ir52_valcom,true,'hidden',$db_opcao,"");
db_input('r52_val13',15,$Ir52_val13,true,'hidden',$db_opcao,"");
if(isset($r52_limite) && trim($r52_limite) != ""){
  $arr_limite = split("-",$r52_limite);
  $r52_limite_dia = $arr_limite[2];
  $r52_limite_mes = $arr_limite[1];
  $r52_limite_ano = $arr_limite[0];
}
db_input('r52_limite_dia',2,$Ir52_limite,true,'hidden',$db_opcao,"");
db_input('r52_limite_mes',2,$Ir52_limite,true,'hidden',$db_opcao,"");
db_input('r52_limite_ano',4,$Ir52_limite,true,'hidden',$db_opcao,"");
?>
</form>

<?php 

 $lExcluir = 'false';

 if ($db_opcao == 3 || $db_opcao == 33){
   $lExcluir = 'true';
 }

 $lAlterar = 'false';

 if($db_opcao == 2){
  $lAlterar = 'true';
 }
?>

<script>

var oContaBancariaServidor = new DBViewContaBancariaServidor($F('rh139_contabancaria'), 'oContaBancariaServidor', <?=$lExcluir?>);
    oContaBancariaServidor.show('ctnContaBancariaServidor');
  
    if (<?=$lAlterar?> || <?=$lExcluir?>) {
      oContaBancariaServidor.getDados($F('rh139_contabancaria')); 
    }

function js_pesquisarh77_retencaotiporec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_retencaotiporec','func_retencaotiporec.php?tipo=2&funcao_js=parent.js_mostraretencaotiporec1|e21_sequencial|e21_descricao','Pesquisa',true);
  }else{
     if(document.form1.rh77_retencaotiporec.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_retencaotiporec','func_retencaotiporec.php?tipo=2&pesquisa_chave='+document.form1.rh77_retencaotiporec.value+'&funcao_js=parent.js_mostraretencaotiporec','Pesquisa',false,'0');
     }else{
       document.form1.e21_descricao.value = ''; 
     }
  }
}
          
function js_mostraretencaotiporec(chave,erro){
  document.form1.e21_descricao.value = chave;
  if(erro==true){ 
    document.form1.rh77_retencaotiporec.focus(); 
    document.form1.rh77_retencaotiporec.value = ''; 
  }
}
function js_mostraretencaotiporec1(chave1,chave2){
  document.form1.rh77_retencaotiporec.value = chave1;
  document.form1.e21_descricao.value        = chave2;
  db_iframe_retencaotiporec.hide();
}


function js_mostrar_dados_formula(TorF){
  if(TorF == true){
    document.getElementById('mostrar_dados_formula').innerHTML = "<font color='red'><b>9999-Bruto Folha&nbsp;&nbsp;&nbsp;&nbsp;8888-IRRF/Prev&nbsp;&nbsp;&nbsp;&nbsp;7777-Liquido</b></font>";
  }else{
    document.getElementById('mostrar_dados_formula').innerHTML = "";
  }
}
function js_enviar(){

  if(document.form1.r52_regist.value == ""){
  	alert("Informe o código do funcionário.");
  	document.form1.r52_regist.focus();
    return false;
  }else if(document.form1.r52_numcgm.value == ""){
  	alert("Informe o CGM do pensionista.");
  	document.form1.r52_numcgm.focus();
    return false;
  }else if(document.form1.r52_formul.value == "" && document.form1.r52_vlrpen.value == ""){
  	alert("Informe a fórmula ou o valor.");
  	document.form1.r52_formul.focus();
    return false;
  }else if(document.form1.r52_formul.value != "" && (document.form1.r52_perc.value == "" || document.form1.r52_perc.value == 0)){
  	alert("Informe o percentual.");
  	document.form1.r52_perc.select();
    return false;
  }else if(document.form1.r52_codbco.value != ""){
  	<?
  	if($db_opcao != 3){
  	  echo '
  	  if(document.form1.r52_codage.value == ""){
  	    alert("Agência não informada.");
  	    document.form1.r52_codage.focus();
        return false;
  	  }else if(document.form1.r52_dvagencia.value == ""){
  	    alert("Dígito verificador da agência não informado.");
  	    document.form1.r52_dvagencia.focus();
        return false;
  	  }else if(document.form1.r52_conta.value == ""){
  	    alert("Conta não informada.");
    	  document.form1.r52_conta.focus();
        return false;
  	  }else if(document.form1.r52_dvconta.value == ""){
  	    alert("Dígito verificador da conta não informado.");
  	    document.form1.r52_dvconta.focus();
        return false;
  	  }
      ';
  	}
  	?>
  }
  
  if (document.form1.r52_adiantamento13.value == 't') {
  
	  if (document.form1.r52_percadiantamento13.value <= 0) {
	  
	    alert("Informe um valor maior que zero para o percentual do adiantamento 13º!");
	    document.form1.r52_percadiantamento13.focus();
	    return false;
	  }
  }
  
  return true;
}
function js_desabilita() {

  if (document.form1.r52_formul.value == "") {
  	<?
  	if($db_opcao!=3){
  	  echo '
            document.form1.r52_vlrpen.readOnly=false;
            document.form1.r52_vlrpen.style.backgroundColor="";
           ';
  	}
    ?>
  } else {
  	document.form1.r52_vlrpen.value = "";
    document.form1.r52_vlrpen.readOnly=true;
    document.form1.r52_vlrpen.style.backgroundColor="#DEB887";
  }
  
  if (document.form1.r52_pag13.value == 't') {
    document.form1.r52_adiantamento13.disabled = false;
  } else {
  
    document.form1.r52_adiantamento13.disabled = true;
    document.form1.r52_adiantamento13.value    = 'f';
  }
  
  if (document.form1.r52_adiantamento13.value == 't') {
    document.form1.r52_percadiantamento13.disabled = false;
  } else {
  
    document.form1.r52_percadiantamento13.value    = '0';
    document.form1.r52_percadiantamento13.disabled = true;
  }
}
function js_pesquisar52_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm','func_nome.php?testanome=true&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.r52_numcgm.value != ''){ 
       js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm','func_nome.php?testanome=true&pesquisa_chave='+document.form1.r52_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false,'0');
     }else{
       document.form1.z01_nome02.value = '';
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome02.value = chave; 
  if(erro==true){ 
    document.form1.r52_numcgm.focus(); 
    document.form1.r52_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.r52_numcgm.value = chave1;
  document.form1.z01_nome02.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisar52_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=ra&funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
  }else{
     if(document.form1.r52_regist.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=ra&pesquisa_chave='+document.form1.r52_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
       location.href = "pes1_pensao001.php?db_opcaoal=<?=(@$db_opcaoal)?>";
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.r52_regist.focus(); 
    document.form1.r52_regist.value = ''; 
  }else{
    location.href = "pes1_pensao001.php?db_opcaoal=<?=(@$db_opcaoal)?>&r52_regist="+document.form1.r52_regist.value;
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.r52_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_rhpessoal.hide();
  location.href = "pes1_pensao001.php?db_opcaoal=<?=(@$db_opcaoal)?>&r52_regist="+chave1;
}
function js_pesquisar52_codbco(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_bancos','func_db_bancos.php?funcao_js=parent.js_mostrabancos1|db90_codban|db90_descr','Pesquisa',true);
  }else{
     if(document.form1.r52_codbco.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_bancos','func_db_bancos.php?pesquisa_chave='+document.form1.r52_codbco.value+'&funcao_js=parent.js_mostrabancos','Pesquisa',false,0);
     }else{
       document.form1.db90_descr.value = ''; 
     }
  }
}
function js_mostrabancos(chave,erro){
  document.form1.db90_descr.value = chave; 
  if(erro==true){ 
    document.form1.r52_codbco.focus(); 
    document.form1.r52_codbco.value = ''; 
  }
}
function js_mostrabancos1(chave1,chave2){
  document.form1.r52_codbco.value = chave1;
  document.form1.db90_descr.value = chave2;
  db_iframe_db_bancos.hide();
}

function js_pesquisar_pensao(mostra){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pensao','func_pensao.php?testarescisao=ra&funcao_js=parent.js_preenchepesquisa|r52_anousu|r52_mesusu|r52_regist|r52_numcgm&chave_r52_anousu=<?=$r52_anousu?>&chave_r52_mesusu=<?=$r52_mesusu?>&instit=<?=$instit?>','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2,chave3){
  db_iframe_pensao.hide();
  <?
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2+'&chavepesquisa3='+chave3+'&db_opcaoal=".@$db_opcaoal."&clicar=false&chave_r52_anousu=".$r52_anousu."&chave_r52_mesusu=".$r52_mesusu."'";
  ?>
}
js_desabilita();
</script>