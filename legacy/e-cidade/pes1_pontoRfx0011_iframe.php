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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_pessoal_classe.php");
include("classes/db_pontofx_classe.php");
include("classes/db_pontofs_classe.php");
include("classes/db_pontofa_classe.php");
include("classes/db_pontofe_classe.php");
include("classes/db_pontofr_classe.php");
include("classes/db_pontof13_classe.php");
include("classes/db_pontocom_classe.php");
include("classes/db_rhrubricas_classe.php");
include("classes/db_lotacao_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$clrhpessoal   = new cl_rhpessoal;
$clpessoal   = new cl_pessoal;
$clpontofx   = new cl_pontofx;
$clpontofs   = new cl_pontofs;
$clpontofa   = new cl_pontofa;
$clpontofe   = new cl_pontofe;
$clpontofr   = new cl_pontofr;
$clpontof13  = new cl_pontof13;
$clpontocom  = new cl_pontocom;
$clrhrubricas= new cl_rhrubricas;
$cllotacao   = new cl_lotacao;
$clrotulo = new rotulocampo;

$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");
$clrotulo->label("r29_tpp");
$clrotulo->label("rh27_descr");
$clrotulo->label("r90_lotac");
$clrotulo->label("r90_datlim");
$clrotulo->label("r90_quant");
$clrotulo->label("r90_valor");
$clrotulo->label("r90_rubric");

if(isset($ponto)){
  $ponto = strtolower($ponto);
}

// Se variáveis anouso e mesusu não existem, ele pegará as variáveis atuais da folha
if(!isset($r90_anousu)){
	$r90_anousu = db_anofolha();
}
if(!isset($r90_mesusu)){
	$r90_mesusu = db_mesfolha();
}
////////////
if(isset($registro)){

    // Rotina para buscar os dados da matrícula
    $dbwhere = " rh01_regist = $registro ";
	$result_registro = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm(null,"rh01_regist as r90_regist,z01_nome,rh02_lota as r90_lotac,r70_descr,rh01_admiss as data_da_admissao","",$dbwhere));
	if($clrhpessoal->numrows > 0){
		db_fieldsmemory($result_registro,0);
	}
	////////////

}

$mostrar_check_repassa = false;
if($ponto == "fx" || $ponto == "fs" || $ponto == "fe" || $ponto == "fr"){
  $mostrar_check_repassa = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.bordas{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #cccccc;
}
.bordas01{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #DEB887;
}
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<form name="form1" method="post" action="">
<center>
<table border="6" width="100%">
  <?
  if(isset($registro) && trim($registro)!=""){
  	$arr_rubricas = split(",",$rubricas_selecionadas_enviar);
  	db_input('r90_lotac', 10, $Ir90_lotac, true, 'hidden', 3, '');
    db_input('DBtxt23', 4, $IDBtxt23, true, 'hidden', 3, "", 'r90_anousu');
    db_input('DBtxt25', 2, $IDBtxt25, true, 'hidden', 3, "", 'r90_mesusu');
  	db_input('data_da_admissao', 10, 0, true, 'hidden', 3, '');
  ?>

  <tr>
    <?
    if($mostrar_check_repassa == true){
    ?>
    <td align="center" valign="top" nowrap class='bordas02' width="2%"  height="3%" title="Marque se deseja repassar ponto">
      <strong>R</strong>
    </td>
    <?
    }
    ?>
    <td align="center" valign="top" nowrap class='bordas02' width="5%" height="3%">
      <strong><?=@$RLr90_rubric?></strong>
    </td>
    <td align="center" valign="top" nowrap class='bordas02' width="40%" height="3%">
      <strong><?=@$RLrh27_descr?></strong>
    </td>
    <?
    if($ponto == "fx" || $ponto == "fs"){
    ?>
    <td align="center" valign="top" nowrap class='bordas02' width="10%" height="3%">
      <strong><?=@$RLr90_datlim?></strong>
    </td>
    <?
    }else if($ponto == "fe" || $ponto == "fr"){
    ?>
    <td align="center" valign="top" nowrap class='bordas02' width="10%" height="3%">
      <strong><?=@$RLr29_tpp?></strong>
    </td>
    <?
    }
    ?>
    <td align="center" valign="top" nowrap class='bordas02' width="10%" height="3%">
      <strong>Opções</strong>
    </td>
    <td align="center" valign="top" nowrap class='bordas02' width="10%" height="3%">
      <strong><?=@$RLr90_quant?></strong>
    </td>
    <td align="center" valign="top" nowrap class='bordas02' width="10%" height="3%">
      <strong><?=@$RLr90_valor?></strong>
    </td>
  </tr>
    <?
    // $sigla - É a sigla a ser utilizada no select.
    // $campoextra - É para quando as tabelas tiverem campos como o DATLIM ou o TPP
    // $mostracamp - É para quando o DATLIM ou o TPP forem apresentados no Select, serem mostrados no IFRAME_SELECIONA

    // $whereextra - Como o campo TPP é PK juntamente com o REGISTRO, ANOUSU e MESUSU em algumas tabelas, essas tabelas
    // poderão retornar o mesmo registro, mesmo anousu e mesmo mesusu com diferentes TPP, assim, quando o usuário clicar
    // em A ('alteração') ou E ('exclusão'), a linha q foi clicada, não deverá mais aparecer e as outras com diferentes
    // TPP devem continuar aparecendo... $whereextra controla isso.

    $campoextra = "";
    if($ponto == "fx"){
      $sigla = "r90_";
      $campoextra = ", r90_datlim as datlim ";
    }else if($ponto == "fs"){
      $sigla = "r10_";
      $campoextra = ", r10_datlim as datlim ";
    }else if($ponto == "fa"){
      $sigla = "r21_";
    }else if($ponto == "com"){
      $sigla = "r47_";
    }else if($ponto == "f13"){
      $sigla = "r34_";


    // ESTE PROGRAMA NÃO USA FR E NEM FE, MAS DEIXEI O CÓDIGO PREVENDO UM DIA SER NECESSÁRIO
    }else if($ponto == "fr"){
      $sigla = "r19_";
      $campoextra = ", r19_tpp as tpp";
      $whereextra222 = true;
    }else if($ponto == "fe"){
      $sigla = "r29_";
      $campoextra = ", r29_tpp as tpp";
      $whereextra222 = true;
    }
    $dbwhere = "      ".$sigla."regist = ".@$registro ;
    $dbwhere .= " and ".$sigla."anousu = $r90_anousu ";
    $dbwhere .= " and ".$sigla."mesusu = $r90_mesusu ";

    // Para controlar a INSTITUIÇÃO
    $dbwhere .= " and ".$sigla."instit = ".db_getsession("DB_instit");

    $campos = $sigla."quant as quant, ".$sigla."valor as valor".$campoextra;

    $tabIndex = 1;
    $dbwhere2 = "";
  	for($i=0;$i<count($arr_rubricas);$i++){
  	  $rubrica_corrente = $arr_rubricas[$i];

      $dbwhere2 = " and ".$sigla."rubric = '$rubrica_corrente' ";

  	  $result_dados_rubrica = $clrhrubricas->sql_record($clrhrubricas->sql_query_file(null,null,"rh27_rubric,rh27_descr,rh27_limdat,rh27_presta,rh27_form,rh27_tipo,rh27_obs, rh27_valorpadrao, rh27_quantidadepadrao","","rh27_rubric='$rubrica_corrente' and rh27_instit=".db_getsession("DB_instit")));
      if($clrhrubricas->numrows > 0){
      	db_fieldsmemory($result_dados_rubrica,0);
      }else{
      	continue;
      }

      $rubrica_tem_formula = false;
      if(isset($rh27_form) && trim($rh27_form)!=""){
      	$rubrica_tem_formula = true;
      }

      $mostrar_ano_mes_rub = 3;
      if(isset($rh27_limdat) && trim($rh27_limdat)=="t"){
      	$mostrar_ano_mes_rub = 1;
      }

      $liberar_para_repassar_ponto = "disabled";
      $mostrar_class_bordas01 = "bordas";
      if($ponto == "fx"){
      	$liberar_para_repassar_ponto = "";
        $mostrar_class_bordas01 = "bordas";
      }else if($ponto == "fs" && $rh27_tipo == "1"){
      	$liberar_para_repassar_ponto = "";
        $mostrar_class_bordas01 = "bordas";
      }else if($ponto != "fx" && $ponto != "fs"){
      	$liberar_para_repassar_ponto = "";
        $mostrar_class_bordas01 = "bordas";
      }

      $quant = "";
      $valor = "";
      $datlim= "";
      $tpp   = "";

      if($ponto == "fx"){
        $sql = $clpontofx->sql_query_seleciona(
                                               null,
                                               null,
                                               null,
                                               null,
                                               $campos,
                                               "",
                                               $dbwhere.$dbwhere2
                                              );
      }else if($ponto == "fs"){
        $sql = $clpontofs->sql_query_seleciona(
                                               null,
                                               null,
                                               null,
                                               null,
                                               $campos,
                                               "",
                                               $dbwhere.$dbwhere2
                                              );
      }else if($ponto == "fa"){
        $sql = $clpontofa->sql_query_seleciona(
                                               null,
                                               null,
                                               null,
                                               null,
                                               $campos,
                                               "",
                                               $dbwhere.$dbwhere2
                                              );
      }else if($ponto == "f13"){
        $sql = $clpontof13->sql_query_seleciona(
                                                null,
                                                null,
                                                null,
                                                null,
                                                $campos,
                                                "",
                                                $dbwhere.$dbwhere2
                                               );
      }else if($ponto == "com"){
        $sql = $clpontocom->sql_query_seleciona(
                                                null,
                                                null,
                                                null,
                                                null,
                                                $campos,
                                                "",
                                                $dbwhere.$dbwhere2
                                               );


      // ESTE PROGRAMA NÃO USA FR E NEM FE, MAS DEIXEI O CÓDIGO PREVENDO UM DIA SER NECESSÁRIO
      }else if($ponto == "fe"){
        $sql = $clpontofe->sql_query_seleciona(
                                               null,
                                               null,
                                               null,
                                               null,
                                               null,
                                               $campos,
                                               "",
                                               $dbwhere.$dbwhere2
                                              );
      }else if($ponto == "fr"){
        $sql = $clpontofr->sql_query_seleciona(
                                               null,
                                               null,
                                               null,
                                               null,
                                               null,
                                               $campos,
                                               "",
                                               $dbwhere.$dbwhere2
                                              );
      }
      $result_busca_dados_ponto = db_query($sql);
      if(pg_numrows($result_busca_dados_ponto)){
      	db_fieldsmemory($result_busca_dados_ponto,0);
      }
    ?>
  <tr>
    <?
      $imprime_tabIndex = "";
      if($mostrar_check_repassa == true){
      	if(trim($liberar_para_repassar_ponto)==""){
          $imprime_tabIndex = " tabIndex='$tabIndex' ";
          $tabIndex ++;
//           db_msgbox(AddSlashes($imprime_tabIndex) . " -- " . $tabIndex);
      	}

      	$mostrar_box_checked = "";
      	if(isset($repassar_rubricas) && trim($repassar_rubricas)!=""){
      	  $arr_repassar_rubricas = split(",",$repassar_rubricas);
      	  if(in_array("chk_".$rh27_rubric,$arr_repassar_rubricas)){
      	  	$mostrar_box_checked = " checked ";
      	  }
      	}
    ?>
      <td align="center" valign="top" nowrap class='<?=$mostrar_class_bordas01?>' width="2%"  height="3%" title="Marque se deseja repassar ponto">
        <input type="checkbox" name="chk_<?=$rh27_rubric?>" <?=$imprime_tabIndex?> <?=$mostrar_box_checked?> value="chk_<?=$rh27_rubric?>" <?=$liberar_para_repassar_ponto?> onBlur="js_ativa_passa_proximo_campo(this.name);" onChange="js_adiciona_itens_array(this.name,this.value);" onfocus="js_mudar_caixa_de_texto('<?=($rh27_obs)?>');">
      </td>
    <?
        if($i == 0 && $liberar_para_repassar_ponto != "disabled"){
          $setar_foco_campo = "chk_".$rh27_rubric;
        }
      }
    ?>
      <td align="center" valign="top" nowrap class='<?=$mostrar_class_bordas01?>' width="5%" height="3%">
        <?=$rh27_rubric?>
        <?
        $campo_recebe_formula = 'form_'.$rh27_rubric;
        $$campo_recebe_formula = 'f';
        if($rubrica_tem_formula == true){
          $$campo_recebe_formula = 't';
        }
        db_input('form_'.$rh27_rubric, 15, 0, true, 'hidden', 3, "onfocus='js_mudar_caixa_de_texto(\"$rh27_obs\");'");
        ?>
      </td>
      <td align="left"   valign="top" nowrap class='<?=$mostrar_class_bordas01?>' width="40%" height="3%">
        <?=$rh27_descr?>
      </td>
    <?
      if($ponto == "fx" || $ponto == "fs"){
      	$datlim_recebe_valor_select = "datlim_".$rh27_rubric;
      	if(isset($datlim)){
      	  $$datlim_recebe_valor_select = $datlim;
      	}
      	if($i == 0 && !isset($setar_foco_campo) && $mostrar_ano_mes_rub != 3){
          $setar_foco_campo = $datlim_recebe_valor_select;
        }
        $imprime_tabIndex = "";
      	if($mostrar_ano_mes_rub != 3){
          $imprime_tabIndex = " tabIndex='$tabIndex' ";
          $tabIndex ++;
      	}
    ?>
      <td align="center" valign="top" nowrap class='<?=$mostrar_class_bordas01?>' width="10%" height="3%">
        <?
          db_input('datlim_'.$rh27_rubric, 15, $Ir90_datlim, true, 'text', $mostrar_ano_mes_rub, "onKeyUp='js_mascaradata(this.value,this.name);' $imprime_tabIndex onChange='js_calculaQuant(this,quant_{$rh27_rubric},\"{$rh27_presta}\",\"{$rh27_limdat}\");' onfocus='js_mudar_caixa_de_texto(\"$rh27_obs\");'");
        ?>
      </td>
    <?


      // ESTE PROGRAMA NÃO USA FR E NEM FE, MAS DEIXEI O CÓDIGO PREVENDO UM DIA SER NECESSÁRIO
      }else if($ponto == "fe" || $ponto == "fr"){
      	$tpp_recebe_valor_select = "tpp_".$rh27_rubric;
      	if(isset($tpp)){
      	  $$tpp_recebe_valor_select = $tpp;
      	}
      	if($i == 0 && !isset($setar_foco_campo)){
          $setar_foco_campo = $tpp_recebe_valor_select;
        }
    ?>
      <td align="center" valign="top" nowrap class='<?=$mostrar_class_bordas01?>' width="10%" height="3%">
        <?
        //db_input('tpp_'.$rh27_rubric, 5, $Ir29_tpp, true, 'text', 1, "onKeyPress='js_passa_para_proximo_campo(this.name,this,event);'");
        db_input('tpp_'.$rh27_rubric, 5, $Ir29_tpp, true, 'text', 1, " tabIndex='$tabIndex' onfocus='js_mudar_caixa_de_texto(\"$rh27_obs\");'");
        $tabIndex ++;
        ?>
      </td>
    <?
      }
      $quant_recebe_valor_select = "quant_".$rh27_rubric;
      if(isset($quant) && trim($quant) != ""){
      	$$quant_recebe_valor_select = $quant;
      }else{
        $$quant_recebe_valor_select = $rh27_quantidadepadrao;
      }
      if($i == 0 && !isset($setar_foco_campo)){
        $setar_foco_campo = $quant_recebe_valor_select;
      }
    ?>
      <td align="center" valign="top" nowrap class='<?=$mostrar_class_bordas01?>' width="10%" height="3%">
        <?
	$arr_opcoes = Array("al"=>"Alterar","so"=>"Somar","su"=>"Subtrair");
        db_select("opc_".$rh27_rubric, $arr_opcoes, true, 1, " tabIndex='$tabIndex' onfocus='js_mudar_caixa_de_texto(\"$rh27_obs\");' onchange='js_zera_valores(document.form1.quant_".$rh27_rubric.",document.form1.valor_".$rh27_rubric.")' ");
        $tabIndex ++;
        ?>
      </td>
      <td align="center" valign="top" nowrap class='<?=$mostrar_class_bordas01?>' width="15%" height="3%">
        <?
        db_input('quant_'.$rh27_rubric, 15, $Ir90_quant, true, 'text', 1, " tabIndex='$tabIndex' onChange='js_calculaDataLimit(this,datlim_{$rh27_rubric},\"{$rh27_presta}\",\"{$rh27_limdat}\");' onfocus='js_mudar_caixa_de_texto(\"$rh27_obs\");'");
        $tabIndex ++;        
        $aCampos[] = "'quant_{$rh27_rubric}'";
        ?>
      </td>
      <td align="center" valign="top" nowrap class='<?=$mostrar_class_bordas01?>' width="15%" height="3%">
        <?
        $valor_recebe_valor_select = "valor_".$rh27_rubric;
        if(isset($valor) && trim($valor) != ""){
      	  $$valor_recebe_valor_select = $valor;
        }else{
          $$valor_recebe_valor_select = $rh27_valorpadrao;
        }
        db_input('valor_'.$rh27_rubric, 15, $Ir90_valor, true, 'text', 1, "onBlur='js_ativa_passa_proximo_campo(this.name);' tabIndex='$tabIndex' onfocus='js_mudar_caixa_de_texto(\"$rh27_obs\");'");
        $tabIndex ++;
        ?>
      </td>
    </tr>
  <?
	}
  }else{
  ?>
  <tr>
    <td align="center" nowrap>
      <strong>Informe a matrícula</strong>
    </td>
  </tr>
  <?
  }
  ?>
</table>
</form>
</body>
</html>

<?php
$sCampos = '[]';

if ( !empty($aCampos) && is_array($aCampos) ) {

  $sCampos = implode(",",$aCampos);
  $aCampos ='';
  $sCampos ="[".$sCampos."]"; 
}
?>
<script>

js_setNullCampoQtd();

function js_setNullCampoQtd(){
	var aCampos = <?php echo $sCampos; ?>;	
	for(var i=0; i < aCampos.length; i++){		
		var campoQtd = document.getElementById(aCampos[i]);		
		campoQtd.select();
	}
}
  
function js_calculaDataLimit(objQuant,objData,lParc,lCompl){

  var doc       = document.form1;
  var iQuant    = new Number(objQuant.value);
  
  if ( lParc == 't' && lCompl == 't' ) {
  
    var iMesAtu   = new Number(doc.r90_mesusu.value);
    var iAnoLimit = new Number(doc.r90_anousu.value);
    var iMesLimit = iMesAtu + (iQuant-1);
    
    while ( iMesLimit > 12  ) {
      iMesLimit -= 12;
      iAnoLimit++;
    }
    
    if ( iMesLimit.toString().length < 2 ) {
      iMesLimit = "0"+iMesLimit;
    }
    
    objData.value = iAnoLimit+'/'+iMesLimit;
     
  }
   
}

function js_calculaQuant(objData,objQuant,lCompl,lParc){
  
  var doc        = document.form1;
  var aDataLimit = objData.value.split('/');
  var iAnoLimit  = new Number(aDataLimit[0]);
  var iMesLimit  = new Number(aDataLimit[1]);
  var iAnoAtu    = new Number(doc.r90_anousu.value);
  var iMesAtu    = new Number(doc.r90_mesusu.value);
    
  if ( lParc == 't' && lCompl == 't' ) {
    
    var iQuant     = new Number(0);
    
    if ( iAnoLimit > iAnoAtu ) {
    
      while ( iAnoLimit > (iAnoAtu+1)  ) {
        iQuant += 12;
        --iAnoLimit;
      }
      
      var iMesRest  = new Number(12 - iMesAtu);
      
      iQuant += iMesRest + iMesLimit;
      
    } else {
      iQuant += iMesLimit - iMesAtu;
    }
    
    objQuant.value = iQuant+1;
    
  }  
}


var y = parent.document.form1;
function js_mudar_caixa_de_texto(OBS){
  parent.document.getElementById('caixa_de_texto').innerHTML = "<font color='red'><b>"+OBS+"</b></font>";
}
function js_zera_valores(campoqtd, campoval){
  campoqtd.value = "0";
  campoval.value = "0";
}
function js_verificaposicoes(valor,TorF,campo){

  var expr = new RegExp("[^0-9]+");
  localbarra = valor.search("/");
  splita_nome_campo = campo.split("_");
  erro = 0;
  errm = "";
  if(localbarra == -1){
   	if(valor.match(expr)){
      erro ++;
  	}
  }else{
    ano = valor.substr(0,4);
    mes = valor.substr(5,2);
    anoi = new Number(ano);
    mesi = new Number(mes);
    anot = new Number(document.form1.r90_anousu.value);
    mest = new Number(document.form1.r90_mesusu.value);

   	if(ano.match(expr)){
      erro ++;
  	}else if(mes.match(expr)){
      erro ++;
  	}else if(anoi < anot || (anoi <= anot && mesi < mest)){
  	  if(mesi > 1 || anoi < anot || TorF == 'true'){
        errm = "\nAno e mês devem ser maior ou igual ao corrente da folha.";
        erro ++;
      }
  	}else if(mesi > 12){
      errm = "\nMês inexistente.";
      erro ++;
  	}else if(TorF == 'true' && mes == 0){
      errm = "\nMês não informado.";
      erro ++;
  	}
  }

  if(erro > 0 || (eval("document.form1."+campo+".readOnly == false") && eval("document.form1."+campo+".value == ''") && TorF == 'true')){
	alert("Rubrica "+splita_nome_campo[1]+":\nCampo Ano/mês deve ser preenchido com números e uma '/' no seguinte formato (aaaa/mm)! " + errm);
    eval("document.form1."+campo+".select()");
    eval("document.form1."+campo+".focus()");
    return false;
  }

  if(valor.length == 7 && 1==2){
  	js_ativa_passa_proximo_campo(campo);
  }

  return true;

}
function js_mascaradata(valor,campo){

  total = valor.length;
  if(total > 0){
    digit = valor.substr(total-1,1);
    if(digit != "/"){
      if(total == 4){
        valor += "/";
  	  }
    }
  }
  eval("document.form1."+campo+".value = valor");
  return js_verificaposicoes(valor,'false',campo);

}
function js_enviar_dados_inclui(){
  x = document.form1;
  q = x.length;
  contador_exclusoes = 0;
  erro = 0;
  dat_virgula = "";
  tpp_virgula = "";
  qtd_virgula = "";
  val_virgula = "";
  opc_virgula = "";
  js_limpar_campos_formulario();
  y.lotacao_matricula.value = x.r90_lotac.value;
  y.admissa_matricula.value = x.data_da_admissao.value;
  for(i=0; i<q; i++){
    if(x.elements[i].name != "r90_lotac" && x.elements[i].name != "r90_anousu" && x.elements[i].name != "r90_mesusu" && x.elements[i].name != "data_da_admissao"){
      variavel_splitei = x.elements[i].name.split("_")
      rubrica_corrente = variavel_splitei[1];
      quant_rubrica_corrente = eval("new Number(x.quant_"+rubrica_corrente+".value)");
      valor_rubrica_corrente = eval("new Number(x.valor_"+rubrica_corrente+".value)");
      formu_rubrica_corrente = eval("x.form_"+rubrica_corrente+".value");

      if(quant_rubrica_corrente != 0 || valor_rubrica_corrente != 0){
        if(x.elements[i].name.substr(0,7) == "datlim_" && x.elements[i].readOnly == false){
          teste = js_verificaposicoes(x.elements[i].value,'true',x.elements[i].name);
          if(teste == false){
            erro ++;
            break;
          }else{
          }
        }else if(x.elements[i].name.substr(0,4) == "tpp_"){
          if(x.elements[i].value == ""){
            alert("Rubrica "+rubrica_corrente+":\nTipo não informado");
            x.elements[i].select();
            x.elements[i].focus();
            erro ++;
            break;
          }
        }
        
        if(quant_rubrica_corrente == 0 && (formu_rubrica_corrente == 't' || formu_rubrica_corrente == 'T')){
          alert("Rubrica "+rubrica_corrente+":\nQuantidade não informada");
          eval("x.quant_"+rubrica_corrente+".select()");
          eval("x.quant_"+rubrica_corrente+".focus()");
          erro ++;
          break;
        }
        if(valor_rubrica_corrente == 0 && (formu_rubrica_corrente == 'f' || formu_rubrica_corrente == 'F')){
          alert("Rubrica "+rubrica_corrente+":\nValor não informado");
          eval("x.valor_"+rubrica_corrente+".select()");
          eval("x.valor_"+rubrica_corrente+".focus()");
          erro ++;
          break;
        }
      }
      if(erro == 0){
        if(x.elements[i].name.substr(0,7) == "datlim_"){
          datlim = x.elements[i].value;
          if(datlim == ""){
            datlim = "#";
          }
          y.datlim_rubricas_selecionadas_enviar.value += dat_virgula+datlim;
          dat_virgula = ",";
        }else if(x.elements[i].name.substr(0,4) == "tpp_"){
          tpp = x.elements[i].value;
          if(tpp == ""){
            tpp = "#";
          }
          y.tpp_rubricas_selecionadas_enviar.value += tpp_virgula+tpp;
          tpp_virgula = ",";
        }else if(x.elements[i].name.substr(0,6) == "quant_"){
          quantidade = x.elements[i].value;
          if(quantidade == ""){
            quantidade = " ";
          }
          y.quantidade_rubricas_selecionadas_enviar.value += qtd_virgula+quantidade;
          qtd_virgula = ",";
        }else if(x.elements[i].name.substr(0,6) == "valor_"){
          valores = x.elements[i].value;
          if(valores == ""){
            valores = " ";
          }
          y.valores_rubricas_selecionadas_enviar.value += val_virgula+valores;
          val_virgula = ",";
        }else if(x.elements[i].name.substr(0,4) == "opc_"){
          valores = x.elements[i].value;
          y.opcoes_rubricas.value += opc_virgula+valores;
          opc_virgula = ",";
        }
      }
    }
  }
  if(erro == 0){
    return true;
  }else{
    return false;
  }
}
function js_passa_para_proximo_campo(campo_origem,campo_evento,ativa_evento){
  var ativa_evento = (ativa_evento) ? ativa_evento : (window.event) ? window.event : "";

  if(ativa_evento.keyCode == 9 || ativa_evento.keyCode == 13){
    js_ativa_passa_proximo_campo(campo_origem);
  }
}

var time;
function js_ativa_passa_proximo_campo(campo_origem){
  x = document.form1;
  q = document.form1.length;
  for(i=0; i<q; i++){
    if((i+1) == q){
      time = setInterval(js_seleciona_campo_confirma,10);
      break;
    }else if(campo_origem == x.elements[i].name){
      soma = i+1;
//      alert(x.elements[soma].disabled + ' *- *- -* -* ' + x.elements[soma].name);
      if(x.elements[soma].disabled == true){
        soma ++;
      }
      if(x.elements[soma].readOnly == true){
        soma ++;
      }
      if(x.elements[soma].readOnly == true){
        soma ++;
      }
      x.elements[soma].select();
      x.elements[soma].focus();
      break;
    }
  }
}
function js_adiciona_itens_array(campo_origem,valor_origem){
  x = document.form1;
  if(campo_origem.substr(0,4) == "chk_"){
    rubricas_a_repassar = parent.document.form1.repassar_rubricas.value;
    
    if(rubricas_a_repassar != ""){
      arr_rubricas_a_repassar = rubricas_a_repassar.split(",");
    }else{
      arr_rubricas_a_repassar = new Array();
    }

    if(eval("x."+campo_origem+".checked == true")){
      erro = 0;
      for(i=0; i<arr_rubricas_a_repassar.length; i++){
        if(valor_origem == arr_rubricas_a_repassar[i]){
          erro ++;
        }
      }
      if(erro == 0){
        arr_rubricas_a_repassar.push(valor_origem);
      }
    }else{
      for(i=0; i<arr_rubricas_a_repassar.length; i++){
        if(valor_origem == arr_rubricas_a_repassar[i]){
          arr_rubricas_a_repassar.splice(i,1);
          break;
        }
      }
    }
    parent.document.form1.repassar_rubricas.value = arr_rubricas_a_repassar.valueOf();
  }
}
function js_seleciona_campo_confirma(){
  parent.document.form1.enviar.focus();
  clearInterval(time);
}

function js_setar_foco_campo(){
<?
if(isset($setar_foco_campo)){
  echo "  document.form1.$setar_foco_campo.focus();\n";
}
?>
}
function js_limpar_campos_formulario(){
  y.datlim_rubricas_selecionadas_enviar.value = "";
  y.tpp_rubricas_selecionadas_enviar.value    = "";
  y.quantidade_rubricas_selecionadas_enviar.value = "";
  y.valores_rubricas_selecionadas_enviar.value = "";
  y.opcoes_rubricas.value = "";
}




js_setar_foco_campo();
js_limpar_campos_formulario();
</script>