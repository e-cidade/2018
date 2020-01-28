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

require_once("fpdf151/scpdf.php");
require_once("fpdf151/impcarne.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_sql.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_isscalc_classe.php");
require_once("classes/db_arrecad_classe.php");
require_once("dbforms/db_funcoes.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$erro           = false;
$descricao_erro = false;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_verifica(){

  var iTipo      = document.getElementById('k03_tipo').value;
  var sLabelTipo = "";
  var sImprimir  = document.getElementById('impr').value;
 
  if(iTipo == 3 || iTipo == 5 || iTipo == 19){
 
    if(sImprimir == 'socotunica'){
   
      if(iTipo == 3){
        sLabelTipo = "Variável";
      } else if(iTipo == 5){
        sLabelTipo = "Vistorias sem ISSQN";
      } else if(iTipo == 19){
        sLabelTipo = "Vistorias";
      }
     
      alert('Impressão não permitida favor verificar os filtros. \n - '+sLabelTipo+' \n - Só Cota Única');
      return false;
     
    } else {
   
      js_mostra_processando(); 
      parent.iframe_g2.js_mandadados();
      return true;
      
    }
   
  } else {
 
    js_mostra_processando(); 
    parent.iframe_g2.js_mandadados(); 
    return true;
   
  }
}

function js_mostraTipoImp(obj){

	if ( obj.value == "txt" || obj.value == "bsjtxt" ) {
		document.getElementById('idTipoImp').style.display  = "none";
    document.getElementById('m_imprimir').style.display = "none";
	} else {
	  document.getElementById('idTipoImp').style.display = "";
	}
	
}


function js_controlaSelectTipo(){
  document.form1.k00_tipoant.value = document.form1.k00_tipo.value;
}

function js_submitform(){
  document.form1.submit();  
}

function termo(qual, total, sql){
  if (sql==0) {
    document.getElementById('termometro').innerHTML='processando registro... '+qual+' de '+total;
  } else {
    document.getElementById('termometro').innerHTML='processando select...';
  }
}

function js_mostraordem(){

  if ( document.form1.emis.value == 'semescr') {
   
    document.form1.ord.options[0] = null;
    parent.document.formaba.g2.disabled = true;

  } else {
   
    if(document.form1.emis.value == 'comescr'){
      parent.document.formaba.g2.disabled = false;
    } else {
      parent.document.formaba.g2.disabled = true;
    }
    
    if (document.form1.ord.options[0].value != 'escritorio'){
      document.form1.ord.options[0] = new Option('Inscrição','inscricao');
      document.form1.ord.options[1] = new Option('Escritório','escritorio');
      document.form1.ord.options[2] = new Option('Nome','nome');
    }
    
  }
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_mostraTipoImp(document.form1.arq);">
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
   <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
   <form name="form1" action="" method="post" >
	  <table width="400" border="0" cellpadding="0" cellspacing="3">
	  <tr>
	    <td colspan=2 align='right'>
	    <b> Tipo para impressão : </b>
	    </td>
	    <td>
	      <?	
		      $xy = array ("2"  => "Fixo", 
		                   "3"  => "Variável", 
		                   "19" => "Vistorias", 
		                   "5"  => "Vistorias sem ISSQN");
		      
		      db_select('k03_tipo', $xy, true, 1,"style='width:150px' onchange='js_submitform();'");
	      ?>      
	    </td>
	  </tr>
	  <tr>
	      <?
				if (!isset($k03_tipo)) {
					$k03_tipo = 2; // na primeira vez que acessa, monta tudo como se tivesse escolhido a primeira opcao
				}
				if (isset($k03_tipo)) {
				?>
	    <td colspan=2 align='right'>
	    <b> Tipo de débito : </b>
	    </td>
	    <td>
	      <?

					if ($k03_tipo == 19 or $k03_tipo == 5) {
						$sql = " select distinct arrecad.k00_tipo, arretipo.k00_descr 
										 from vistorias
													 inner join vistorianumpre on vistorianumpre.y69_codvist = vistorias.y70_codvist
													 inner join arrecad on k00_numpre = y69_numpre
																						 and extract (year from y70_data) = ".db_getsession('DB_anousu')." 
													 inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo";
					} else {
						$sql = " select distinct arrecad.k00_tipo, arretipo.k00_descr 
										 from isscalc
													 inner join arrecad on k00_numpre = q01_numpre 
																						 and q01_anousu = ".db_getsession('DB_anousu')." 
													 inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo 
										 where k03_tipo = $k03_tipo ";
					}

					$result    = db_query($sql) or die($sql);
					$numrows   = pg_numrows($result);
					
					$elementos = array("0" => " Selecione o tipo de débito ");
					
					for($i=0;$i<$numrows;$i++){
						$oTipoDebito = db_utils::fieldsMemory($result, $i);
						//db_fieldsmemory($result,$i);
						$elementos[$oTipoDebito->k00_tipo] = $oTipoDebito->k00_tipo." - ".$oTipoDebito->k00_descr;
					}
					
					if(isset($k00_tipoant) && $k00_tipoant != ""){
					  $k00_tipo = $k00_tipoant; 
					} else {
					  //$k00_tipo = 0;
					}
					db_select('k00_tipo', $elementos, true, 1,"style='width:150px' onchange='js_controlaSelectTipo();'");
					
        ?>
	    </td>
	  </tr>
    <?
     if ($k03_tipo == 3){
    ?>
    <tr>
      <td colspan='2' align='right'>
          <b>Parcelas de: </b>
       </td>
       <td>
	        <input type='text' id='numparini' name='numparini' size='5'  value=<?=(isset($numparini)?$numparini:"1")?>>
          <b>A</b>
	        <input type='text' id='numparfim' name='numparfim' size='5'  value=<?=(isset($numparfim)?$numparfim:"12")?>>
      </td>
    </tr>
   
	  <tr>
	    <td colspan=2 align='right'>
	    <b> Emite Valores : </b>
	    </td>
	    <td>
	      <?	
		      $xy = array ( "0" => "Nenhum", 
		                    "1" => "Emite Valor Lançado  ", 
		                    "2" => "Emite Valor Zerado");
		      
		      db_select('emiteVal', $xy, true, 1,"style='width:150px' onchange='js_submitform();'");
	      ?>      
	    </td>
	  </tr>
	 
	 <?
   }
    ?>
	  
		
		<tr>
	    <td colspan='2' align='right'>
	    <b> Arquivo : </b>
	    </td>
	    <td>
	      <?	
		      $arqi= array ("pdf" => "PDF",
		                    "txt" => "TXT",
                        "bsjtxt" => "TXT/BSJ");
		      db_select('arq', $arqi, true, 1,"style='width:150px' onchange='js_mostraordem(); js_submitform();'");
	      ?>      
	    </td>
	  </tr>
    <?
  		}
		?>
	  <tr>
	    <td colspan=2 align='right'>
	    <b> Tipo emissão : </b>
	    </td>
	    <td>
	      <?	
		      $emisi = array ("geral"   => "Geral",
		                      "comescr" => "Com os escritórios",
		                      "semescr" => "Sem os escritórios");
		      
		      db_select('emis', $emisi, true, 1,"style='width:150px' onchange='js_mostraordem();'");
	      ?>      
	    </td>
	  </tr>
    <tr id="m_imprimir">
      <td colspan=2 align='right'>
      <b> Imprimir : </b>
      </td>
      <td>
        <?  
	        $imprimir = array ("todas"      => "Todas",
	                           "socotunica" => "Só Cota Única",
	                           "soparcela"  => "Só Parcelas");
	        db_select('impr', $imprimir, true, 1,"style='width:150px'");
        ?>      
      </td>
    </tr>    
	  <tr>
	    <td colspan=2 align='right'>
	    <div id="divlabel">
           	    <b> Ordem : </b>
            </div>
	    </td>
	    <td>
	     <div id="divordem">
	      <?	
		      $ordi = array ("inscricao" => "Inscricão", 
		                     "nome"      => "Nome", 
		                     "escritorio"=> "Escritorio");
		      db_select('ord', $ordi, true, 1,"style='width:150px'");
	      ?>  
	      </div>
	    </td>
	  </tr>

	  <tr>
	    <td colspan=2 align='right' nowrap >
       <b> Quantidade de registros do select: </b>
	   </td>
     <td nowrap>
	     <input type='text' name='quantidade' style='width:150px' value=<?=(isset($quantidade)?$quantidade:"")?>>
       <b> * Deixe em branco para processar todos  </b>
	   </td>
	  </tr>

	  <tr>
	    <td colspan=2 align='right' nowrap >
       <b> Quantidade de registros a gerar no txt: </b>
	   </td>
     <td nowrap>
	     <input type='text' name='quantidade_registros_real' style='width:150px' value=<?=(isset($quantidade_registros_real)?$quantidade_registros_real:"")?>>
       <b> * Deixe em branco para processar todos  </b>
	   </td>
	  </tr>

		<tr id="idTipoImp"> 
      <td height="25"colspan=2 align='right' nowrap> 
	       <b>Imprimir parcelas: </b>
      </td>
      <td nowrap>
        <?	
	        $imprimirparcelas = array ("s" => "Sim",
	                                   "n" => "Não");
	        db_select('imprimeparcelas', $imprimirparcelas, true, 1,"style='width:150px'");
				?>  
      </td>
    </tr>

  <tr> 
  <td height="25" colspan=3>
  <?

	  if (isset($k03_tipo)) {
    
    $sql  =  " select distinct * from "; 
    $sql .=  "      (select min(recibounica.k00_dtvenc) as k00_dtvenc, ";
    $sql .=  "              min(recibounica.k00_dtoper) as k00_dtoper, ";
    $sql .=  "              recibounica.k00_percdes ";
    $sql .=  "         from recibounica ";
    $sql .=  "              inner join isscalc  on q01_numpre = recibounica.k00_numpre ";
    $sql .=  "                                 and q01_anousu = ".db_getsession('DB_anousu');
    $sql .=  "              inner join arrecad  on q01_numpre = arrecad.k00_numpre ";
    $sql .=  "              inner join arretipo on arretipo.k00_tipo = arrecad.k00_tipo ";
    $sql .=  "                                 and arretipo.k03_tipo = $k03_tipo ";
    $sql .=  "       where k00_tipoger = 'G' group by k00_percdes) as x ";
    $sql .=  " where k00_dtvenc > '".date('Y-m-d',db_getsession('DB_datausu'))."' order by k00_dtvenc, k00_percdes ";
    $result = db_query($sql) or die($sql);

    if (pg_numrows($result) > 0) { ?>
      <b>Unicas : </b>
 <? } ?>
  </td>
  </tr>
  <tr>
  <td height="25" colspan=3 align="center">
  <?
    if (pg_numrows($result) > 0) {
      for ($i = 0; $i < pg_numrows($result); $i ++) {
        db_fieldsmemory($result, $i);
        $expressao = $k00_dtvenc . "=" . $k00_dtoper . "=" . $k00_percdes;
        ?>
        <input type="checkbox" value="<?=$expressao?>" name="check_<?=$i?>" checked><?="Vencimento: ".db_formatar($k00_dtvenc,"d")."- Lançamento: ".db_formatar($k00_dtoper,"d")."- Desconto: ".$k00_percdes."<br>"?>
        <?
      }
    }
	}
  ?>
  <input name="totcheck" type="hidden" id="totcheck" value="<?=pg_numrows($result)?>"> 
  </td>
  </tr>
         <tr> 
            <td height="25"colspan=3 align='center'> 
	         <input name="geracarnes"  type="submit" id="geracarnes" value="Gerar Carnes " onclick="return js_verifica();"> 
            </td>
          </tr>
          <tr> 
	    <td colspan=3 align='center'>
	      <input name="termometro" style='background: transparent' id="termometro" type="text" value="" size=50>
	    </td>
          </tr> 
        <script>
					 function js_mostra_processando(){
							document.form1.processando.style.visibility='visible';
           }
     	  </script>
          <tr> 
            <td height="25" align="center" colspan="3" > 
        	    <input name="processando" id="processando" style='color:red;border:none;visibility:hidden' type="button"  readonly value="Processando. Aguarde..."> 
       	      <input type='hidden' name='cgmescrito' value="">
              <input type='hidden' name='k00_tipoant' value="">
            </td>
          </tr>
        </table>
      </form>
     </td>
  </tr>
</table>
</body>
</html>
<?

if (isset($geracarnes)) {
  
  if(isset($emiteVal) && $emiteVal == 0 ){
		$processa = false;
	  echo $processa;
	} else {
    $processa = true;
	  echo $processa;
	}
	
  $unica = "";
  $U     = 'U';
  
  for ($i=0; $i < $totcheck; $i++) {
    $check = "check_".$i;
    if (isset($$check) and $$check != "--") {
      if ($i == $totcheck-1) {
        $U = "";
      }
      $unica .= $$check.$U;
    }
  }
  
  if( $processa == true ){
		if (isset($arq) && ($arq == "txt" or $arq == "bsjtxt")) {
      
      echo " <script>
							 	 js_OpenJanelaIframe('','db_iframe_carne','iss4_emitetxtissqn003.php?quantidade=$quantidade&quantidade_registros_real=$quantidade_registros_real&selunica=$unica&k03_tipo=$k03_tipo&k00_tipo=".(isset($k00_tipoant)&&$k00_tipoant!=""?$k00_tipoant:$k00_tipo)."&arq=$arq&emis=$emis&ord=$ord&cgmescrito=$cgmescrito&imprimir=$impr','Emitindo carnes...',true,5);
						 </script> ";
    } else {
       
      if (isset($numparini)){
  
        if ($numparfim == ''){
          $numparfim = $numparini;
        }
         
        $fparc = "numparini={$numparini}&numparfim={$numparfim}";
			}
      
			if(isset($emiteVal)){
				$femite = "&emiteVal={$emiteVal}";
			} else {
        $femite = "";
			}
		  
			echo " <script>
								js_OpenJanelaIframe('','db_iframe_carne','iss4_emiteissqn003.php?quantidade=$quantidade".$femite."&unica=$unica&quantidade_registros_real=$quantidade_registros_real&k03_tipo=$k03_tipo&imprimeparcelas=$imprimeparcelas&k00_tipo=".(isset($k00_tipoant)&&$k00_tipoant!=""?$k00_tipoant:$k00_tipo)."&arq=$arq&emis=$emis&ord=$ord&cgmescrito=$cgmescrito&imprimir=$impr&{$fparc}','Emitindo carnes...',true,5);
						 </script> ";
		}
		
  } else {
    echo "<script>alert('Selecionar uma opção do campo Emite Valor!');</script>";
  }
  
}	

///////////////////////////// G E R A Ç Ã O   D O S  C A R N E S ////////////////////////////////////

if($erro==true){
  echo "<script>alert('$descricao_erro');</script>";
}


?>