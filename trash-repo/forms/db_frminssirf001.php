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
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo                 = new rotulocampo;
$clinssirf->rotulo->label();
$clrotulo->label("rh27_descr");
$clrotulo->label("rh32_descr"); 
$clrotulo->label("o56_descr");  

$clrotulo->label("rh129_regimeprevidencia");
$clrotulo->label("rh127_descricao");

$db_opcao = 1;
if(isset($opcao) && $opcao == "alterar" && isset($r33_codigo)){
  $db_opcao = 2;
}else if(isset($opcao) && $opcao == "excluir" && isset($r33_codigo)){
  $db_opcao = 3;
}
$alteraform = true;
$campofocoi = "r33_nome";
$codigo_tab     = (int)$codtab;
if(isset($codtab) && $codigo_tab <= 2){
  $alteraform = false;
  $campofocoi = "r33_inic";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="50%">
  <tr>
    <td align="center">
      <fieldset>
        <legend>
          <strong>Previd�ncia e IRRF</strong>
        </legend>
        <table width="100%">
	  <?
	  if($alteraform == true){
			
			$sSqlInssirf = $clinssirf->sql_query_dados(null,
																								 "r33_nome, 
																									r33_tipo, 
																									r33_rubmat, 
																									a.rh27_descr as rh27_descrmat, 
																									r33_rubsau, 
																									b.rh27_descr as rh27_descrsau, 
																									r33_rubaci, 
																									c.rh27_descr as rh27_descraci, 
																									r33_basfer, 
																									d.r08_descr as rh32_descrfer,
																									r33_basfet,
																									e.r08_descr as rh32_descrfet,
																									r33_ppatro, 
																									r33_tinati,
																									r33_codele,
																									o56_descr", 
																									"",
																									"r33_anousu    = ".db_anofolha()." 
																									and r33_mesusu = ".db_mesfolha()." 
																									and r33_instit = ".db_getsession('DB_instit')." 
																									and r33_codtab = '".$codtab."' 
																									limit 1");
	    $result_inssirf = $clinssirf->sql_record($sSqlInssirf);
//	    $result_inssirf = $clinssirf->sql_record($clinssirf->sql_query_dados(null,"r33_nome,r33_tipo,r33_rubmat,a.rh27_descr as rh27_descrmat,r33_rubsau,b.rh27_descr as rh27_descrsau,r33_rubaci,c.rh27_descr as rh27_descraci,r33_basfer,d.rh32_descr as rh32_descrfer,r33_basfet,e.rh32_descr as rh32_descrfet,r33_ppatro,r33_tinati","","r33_anousu = ".db_anofolha()." and r33_mesusu = ".db_mesfolha()." and r33_instit = ".db_getsession('DB_instit')." and r33_codtab = '".$codtab."' limit 1"));
	    if($clinssirf->numrows > 0){
	      db_fieldsmemory($result_inssirf, 0);
	    }
	  ?>
          <tr>
            <td nowrap title="<?=$Tr33_nome?>" >
	      <?=$Lr33_nome?>
            </td>
            <td>
              <?
	      db_input('r33_nome',20,$Ir33_nome,true,'text',$db_opcao,"");
	      ?>
	    </td>
            <td nowrap title="<?=$Tr33_tipo?>" >
	      <?=$Lr33_tipo?>
            </td>
            <td>
              <?
	      $arr_tipos = array("O"=>"Oficial","P"=>"Privada");
	      db_select("r33_tipo", $arr_tipos, true, $db_opcao);
	      ?>
	    </td>
	  </tr>
	  <?
	  }
	  ?>
          <tr>
            <td nowrap title="<?=$Tr33_inic?>" >
	      <?=$Lr33_inic?>
            </td>
            <td>
              <?
              db_input('r33_inic',12,$Ir33_inic,true,'text',$db_opcao,"onchange='js_faixas(this.value,this.name);'");
	      db_input('r33_codigo',4,$Ir33_codigo,true,'hidden',3,"");
	      db_input('codtab',4,0,true,'hidden',3,"");
              ?>
	    </td>
            <td nowrap title="<?=$Tr33_fim?>" >
	      <?=$Lr33_fim?>
            </td>
            <td>
              <?
              db_input('r33_fim',12,$Ir33_fim,true,'text',$db_opcao,"onchange='js_faixas(this.value,this.name);'");
              ?>
	    </td>
	  </tr>
	  <tr>
            <td nowrap title="<?=$Tr33_perc?>" >
	      <?=$Lr33_perc?>
            </td>
            <td>
              <?
              db_input('r33_perc',12,$Ir33_perc,true,'text',$db_opcao,"");
              ?>
	    </td>
            <td nowrap title="<?=$Tr33_deduzi?>" >
	      <?=$Lr33_deduzi?>
            </td>
            <td>
              <?
              db_input('r33_deduzi',12,$Ir33_deduzi,true,'text',($alteraform==false?$db_opcao:"3"),"");
              ?>
	    </td>
	    <?
            if($alteraform == true){
	    ?>
                    </tr>
                  </table>
                </fieldset>
	      </td>
      </tr>
	    <tr>
        <td>
   <fieldset>
		<!--<legend><b>Configura��o</b></legend>-->
		<table width="100%">
      <tr>
        <td nowrap title="<?=$Tr33_rubmat?>" >
          <?
            db_ancora(@$Lr33_rubmat, "js_pesquisarubricas(true,'mat');", $db_opcao);
					?>
        </td>
          <td nowrap>
            <?
              db_input('r33_rubmat',5,$Ir33_rubmat,true,'text',$db_opcao,"onchange='js_pesquisarubricas(false,\"mat\");'");
              db_input('rh27_descr',30,$Irh27_descr,true,'text',3,"","rh27_descrmat");
            ?>
          </td>
        </tr>

		    <tr>
          <td nowrap title="<?=$Tr33_rubsau?>" >
            <?
             db_ancora(@$Lr33_rubsau, "js_pesquisarubricas(true,'sau');", $db_opcao);
						?>
          </td>
          </td>
          <td nowrap>
            <?
              db_input('r33_rubsau',5,$Ir33_rubsau,true,'text',$db_opcao,"onchange='js_pesquisarubricas(false,\"sau\");'");
              db_input('rh27_descr',30,$Irh27_descr,true,'text',3,"","rh27_descrsau");
            ?>
          </td>
        </tr>

		    <tr>
          <td nowrap title="<?=$Tr33_rubaci?>" >
            <?
              db_ancora(@$Lr33_rubaci, "js_pesquisarubricas(true,'aci');", $db_opcao);
						?>
          </td>
          </td>
          <td nowrap>
            <?
              db_input('r33_rubaci',5,$Ir33_rubaci,true,'text',$db_opcao,"onchange='js_pesquisarubricas(false,\"aci\");'");
              db_input('rh27_descr',30,$Irh27_descr,true,'text',3,"","rh27_descraci");
            ?>
          </td>
        </tr>

		    <tr>
          <td nowrap title="<?=$Tr33_basfer?>" >
            <?
							db_ancora(@$Lr33_basfer,"js_pesquisabases(true,'fer');",$db_opcao);
						?>
          </td>
          <td nowrap>
            <?
              db_input('r33_basfer',5,$Ir33_basfer,true,'text',$db_opcao,"onchange='js_pesquisabases(false,\"fer\");'");
              db_input('rh32_descr',30,$Irh32_descr,true,'text',3,"","rh32_descrfer");
            ?>
          </td>
		    </tr>

		    <tr>
          <td nowrap title="<?=$Tr33_basfet?>" >
            <?
							db_ancora(@$Lr33_basfet,"js_pesquisabases(true,'fet');",$db_opcao);
					  ?>
          </td>
          <td nowrap>
            <?
              db_input('r33_basfet',5,$Ir33_basfet,true,'text',$db_opcao,"onchange='js_pesquisabases(false,\"fet\");'");
              db_input('rh32_descr',30,$Irh32_descr,true,'text',3,"","rh32_descrfet");
            ?>
          </td>
        </tr>

		    <tr>
          <td nowrap title="<?=$Tr33_ppatro?>" >
	          <?=$Lr33_ppatro?>
          </td>
          <td>
            <?
              db_input('r33_ppatro',5,$Ir33_ppatro,true,'text',$db_opcao,"");
            ?>
          </td>
        </tr>

		    <tr>
          <td nowrap title="<?=$Tr33_tinati?>">
	          <?=$Lr33_tinati?>
          </td>
          <td>
            <?
              db_input('r33_tinati',5,$Ir33_tinati,true,'text',$db_opcao,"");
            ?>
          </td>
				</tr>

				<tr>
					<td nowrap title="<?php echo $Tr33_codele; ?>">
						<?php
							db_ancora(@$Lr33_codele, "js_pesquisar_codele(true);",$db_opcao);
						?>
					</td>
					<td>
						<?php
							db_input('r33_codele',5,$Ir33_codele,true,'text',$db_opcao," onchange='js_pesquisar_codele(false);'");
							db_input('o56_descr',30,$Io56_descr,true,'text',3,'');
						?>
					</td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Trh129_regimeprevidencia; ?>">
            <?php
              db_ancora(@$Lrh129_regimeprevidencia, "js_pesquisarRegimePrevidencia(true);",$db_opcao);
            ?>
          </td>
          <td>
            <?php
              db_input('rh129_regimeprevidencia',5,$Irh129_regimeprevidencia,true,'text', 4," onchange='js_pesquisarRegimePrevidencia(false);'", "", "#E6E4F1");
              db_input('rh127_descricao',30,$Irh127_descricao,true,'text',3,'');
            ?>
          </td>
        </tr>

      <?
      }
      ?>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
<input name="<?=(!isset($opcao)?"incluir":$opcao)?>" type="submit" id="db_opcao" value="<?=(!isset($opcao)?"Incluir":ucfirst($opcao))?>" onclick="return js_verificar_campos();">
<?
if(isset($opcao)){
?>
<input name="novo" type="button" id="novo" value="Novo" onclick="location.href='pes1_inssirf002.php?codtab=<?=@$codtab?>'">
<?
}
?>
<input name="voltar" type="button" id="voltar" value="Voltar" onclick="location.href='pes1_inssirf001.php'" onblur='js_setar_foco();'>
<table border="0" width="50%" valign="top">
  <tr>
    <td align="center">
      <?
      $dbwhere = "    r33_instit = ".db_getsession("DB_instit");
      $dbwhere .= " and    r33_anousu = ".db_anofolha();
      $dbwhere.= " and r33_mesusu = ".db_mesfolha();
      if(isset($codtab) && trim($codtab) != ""){
	$dbwhere.= " and r33_codtab = '".$codtab."'";
      }
      if(isset($r33_codigo) && trim($r33_codigo) != ""){
  	$dbwhere .= " and r33_codigo <> ".$r33_codigo;
      }

      $sql_iframe = $clinssirf->sql_query_file(null,null,"r33_codigo,r33_anousu,r33_mesusu,r33_codtab,r33_inic,r33_fim,r33_perc,r33_deduzi","r33_inic",$dbwhere);

      $chavepri= array("r33_codigo"=>@$r33_codigo,"r33_anousu"=>@$r33_anousu,"r33_mesusu"=>@$r33_mesusu);
      $cliframe_alterar_excluir->chavepri = $chavepri;
      $cliframe_alterar_excluir->opcoes   = 1;
      $cliframe_alterar_excluir->sql      = $sql_iframe;
      $cliframe_alterar_excluir->campos   = "r33_inic,r33_fim,r33_perc,r33_deduzi";
      $cliframe_alterar_excluir->legenda  = "FAIXAS LAN�ADAS";
      $cliframe_alterar_excluir->alignlegenda  = "left";
      $cliframe_alterar_excluir->iframe_height = "200";
      $cliframe_alterar_excluir->iframe_width  = "100%";
      $cliframe_alterar_excluir->iframe_alterar_excluir(1);

      $result_iframe = $clinssirf->sql_record($sql_iframe);

      $arr_faixasini = Array();
      $arr_faixasfim = Array();
      for($i=0; $i<$clinssirf->numrows; $i++){
	db_fieldsmemory($result_iframe, $i);
	$arr_faixasini[$i] = $r33_inic;
	$arr_faixasfim[$i] = $r33_fim;
      }
      ?>
    </td>
  </tr>
</table>
</form>
</center>
<script>

function js_pesquisarRegimePrevidencia(mostra) {
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_regimeprevidencia','func_regimeprevidencia.php?funcao_js=parent.js_mostraRegimePrevidencia1|rh127_sequencial|rh127_descricao','Pesquisa',true);
  } else {
    var  valor = document.form1.rh129_regimeprevidencia.value;
    if ( valor != '') {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_regimeprevidencia', 'func_regimeprevidencia.php?pesquisa_chave='+valor+'&funcao_js=parent.js_mostraRegimePrevidencia','Pesquisa',false);
    } else {
      document.form1.rh129_regimeprevidencia.value = '';
      document.form1.rh127_descricao.value = '';
    }
  }
}

function js_mostraRegimePrevidencia1(chave1, chave2) {
  document.form1.rh129_regimeprevidencia.value = chave1;
  document.form1.rh127_descricao.value = chave2;

  db_iframe_regimeprevidencia.hide();
}

function js_mostraRegimePrevidencia(chave1, erro) {

  if (!erro) {
    document.form1.rh127_descricao.value = chave1;
  } else {
    document.form1.rh129_regimeprevidencia.value = '';
    document.form1.rh127_descricao.value = '';
  }
}

function js_pesquisabases(mostra,opcao){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhbases','func_bases.php?funcao_js=parent.js_mostrabases'+opcao+'1|r08_codigo|r08_descr','Pesquisa',true);
  }else{
     eval("valor = document.form1.r33_bas"+opcao+".value");
     if(valor != ''){
       js_OpenJanelaIframe('top.corpo','db_iframe_rhbases','func_bases.php?pesquisa_chave='+valor+'&funcao_js=parent.js_mostrabases'+opcao,'Pesquisa',false);
     }else{
       eval("document.form1.rh32_descr"+opcao+".value = ''"); 
     }
  }
}
function js_mostrabasesfer(chave,erro){
  document.form1.rh32_descrfer.value  = chave;
  if(erro==true){
    document.form1.r33_basfer.value = '';
    document.form1.r33_basfer.focus();
  }
}
function js_mostrabasesfer1(chave1,chave2){
  document.form1.r33_basfer.value  = chave1;
  document.form1.rh32_descrfer.value  = chave2;
  db_iframe_rhbases.hide();
}
function js_mostrabasesfet(chave,erro){
  document.form1.rh32_descrfet.value  = chave;
  if(erro==true){
    document.form1.r33_basfet.value = '';
    document.form1.r33_basfet.focus();
  }
}
function js_mostrabasesfet1(chave1,chave2){
  document.form1.r33_basfet.value  = chave1;
  document.form1.rh32_descrfet.value  = chave2;
  db_iframe_rhbases.hide();
}
function js_pesquisarubricas(mostra,opcao){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrarubricas'+opcao+'1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
     campo = eval('document.form1.r33_rub'+opcao);
     js_completa_rubricas(campo);
     eval("valor = document.form1.r33_rub"+opcao+".value");
     if(valor != ''){
       js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+valor+'&funcao_js=parent.js_mostrarubricas'+opcao,'Pesquisa',false);
     }else{
       eval("document.form1.rh27_descr"+opcao+".value = ''"); 
     }
  }
}
function js_mostrarubricassau(chave,erro){
  document.form1.rh27_descrsau.value  = chave;
  if(erro==true){
    document.form1.r33_rubsau.value = '';
    document.form1.r33_rubsau.focus();
  }
}
function js_mostrarubricassau1(chave1,chave2){
  document.form1.r33_rubsau.value  = chave1;
  document.form1.rh27_descrsau.value  = chave2;
  db_iframe_rhrubricas.hide();
}
function js_mostrarubricasmat(chave,erro){
  document.form1.rh27_descrmat.value  = chave;
  if(erro==true){
    document.form1.r33_rubmat.value = '';
    document.form1.r33_rubmat.focus();
  }
}
function js_mostrarubricasmat1(chave1,chave2){
  document.form1.r33_rubmat.value  = chave1;
  document.form1.rh27_descrmat.value  = chave2;
  db_iframe_rhrubricas.hide();
}
function js_mostrarubricasaci(chave,erro){
  document.form1.rh27_descraci.value  = chave;
  if(erro==true){
    document.form1.r33_rubaci.value = '';
    document.form1.r33_rubaci.focus();
  }
}
function js_mostrarubricasaci1(chave1,chave2){
  document.form1.r33_rubaci.value  = chave1;
  document.form1.rh27_descraci.value  = chave2;
  db_iframe_rhrubricas.hide();
}
function js_verificar_campos(){

  if(document.form1.r33_inic.value == ""){
    alert("Informe a faixa de valor inicial.");
    document.form1.r33_inic.focus();
  }else if(document.form1.r33_fim.value == ""){
    alert("Informe a faixa de valor final.");
    document.form1.r33_fim.focus();
  }else{

    if (document.form1.rh129_regimeprevidencia.value != '') {
      document.form1.rh129_regimeprevidencia.onkeyup(new Event(Event.CHANGE));

      if (document.form1.rh129_regimeprevidencia.value == '') {
        return false;
      }
    }

    faixaini = new Number(document.form1.r33_inic.value);
    faixafim = new Number(document.form1.r33_fim.value);
    if(faixafim > faixaini){
      if(document.form1.r33_perc.value == ""){
        document.form1.r33_perc.value = 0;
      }
      if(document.form1.r33_deduzi.value == ""){
        document.form1.r33_deduzi.value = 0;
      }
      return true;
    }else{
      alert("A faixa de valor inicial deve ser superior �\n faixa de valor final. Verifique.");
    }
  }

  return false;
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_inssirf','func_inssirf.php?funcao_js=parent.js_preenchepesquisa|r33_anousu|r33_mesusu|r33_codtab','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2){
  db_iframe_inssirf.hide();
  <?
  if(isset($opcao)){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2";
  }
  ?>
}
function js_faixas(valor,campo){
  valor = new Number(valor);
  valor = valor.toFixed(2);
  erro = 0;
  <?
  for($i=0; $i<count($arr_faixasini); $i++){
    $ini = $arr_faixasini[$i];
    $fim = $arr_faixasfim[$i];
    echo "
          ini".$i." = new Number(".$ini.");\n
          fim".$i." = new Number(".$fim.");\n
	  if(valor >= ini".$i." && valor <= fim".$i."){\n
	    alert('Alguma faixa cadastrada j� abrange este valor. Verifique.');\n
	    eval('document.form1.'+campo+'.value = \"\";');
	    eval('document.form1.'+campo+'.focus();');
	    erro++;
	  }\n
	 ";
  }
  ?>
  if(erro == 0){
    eval('document.form1.'+campo+'.value = "'+valor+'";');
  }
}
function js_setar_foco(){
  <?
  if(!isset($opcao) || (isset($opcao) && trim($opcao) == "alterar")){
    echo 'js_tabulacaoforms("form1","'.$campofocoi.'",true,1,"'.$campofocoi.'",true);';
  }else{
    echo 'js_tabulacaoforms("form1","excluir",true,1,"excluir",true);';
  }
  ?>
}


function js_pesquisar_codele(mostra) {

	if ( mostra == true) {
		js_OpenJanelaIframe('top.corpo', 'db_iframe_orcelemento','func_rhelementoemp.php?funcao_js=parent.js_mostraorcelemento1|rh38_codele|o56_descr','Pesquisa',true);
		return;
	} 

	if ( document.form1.r33_codele.value != '') { 
		js_OpenJanelaIframe('top.corpo', 'db_iframe_orcelemento','func_rhelementoemp.php?pesquisa_chave='+document.form1.r33_codele.value+'&funcao_js=parent.js_mostraorcelemento','Pesquisa',false);
		return;
	}

	document.form1.o56_descr.value = ''; 
}

function js_mostraorcelemento(chave, erro) {

	document.form1.o56_descr.value = chave; 

	if ( erro == true ) {  

    document.form1.r33_codele.focus(); 
    document.form1.r33_codele.value = ''; 
  }
}

function js_mostraorcelemento1(chave1, chave2) {

  document.form1.r33_codele.value = chave1;
  document.form1.o56_descr.value   = chave2;
  db_iframe_orcelemento.hide();
}
</script>