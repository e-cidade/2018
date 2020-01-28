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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_liborcamento.php");
include("classes/db_orcduplicacao_classe.php");
include("classes/db_orcorgao_classe.php");
include("classes/db_orcduplicacaodotacao_classe.php");
include("classes/db_orcduplicacaoreceita_classe.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_conaberturaexe_classe.php");
include("dbforms/db_funcoes.php");

$get                     = db_utils::postmemory($_GET);
$post                    = db_utils::postmemory($_POST);
$clorcduplicacao         = new cl_orcduplicacao;
$clorcduplicacaoreceita  = new cl_orcduplicacaoreceita;
$clorcduplicacaodotacao  = new cl_orcduplicacaodotacao;
$clorcorgao              = new cl_orcorgao;
$clconaberturaexe        = new cl_conaberturaexe;
$clorcdotacao            = new cl_orcdotacao;
(integer)$db_opcao       = 1;
(bool)$db_botao          = true;
(string)$sWh             = null;
(string)$lblDescr        = null;
(int)$iNumRows           = 0;
if (isset($post->atualizar)){

   foreach ($post->chkitens as $key => $val){

      $var = "vdupli$val"; 
      if ($post->$var != '0'){
        
				$vPercentual = "percen$val";
				$vImport     = "imp$val";
        if (isset($post->$vImport) && $post->$vImport  == 't'){

          $lImporta = 'true';
				}else{
       
          $lImporta = 'false';
				}
				$clorcduplicacao->o75_percentual    = @$post->$vPercentual;
				$clorcduplicacao->o75_sequencial    = $val;
				$clorcduplicacao->o75_importar      = $lImporta;
				$clorcduplicacao->o75_valorduplicar = $post->$var; 
				$clorcduplicacao->alterar($val);
				
			}
  } 

}
if ($get->o75_conaberturaexe != null){

  $rs1  = $clconaberturaexe->sql_record($clconaberturaexe->sql_query($get->o75_conaberturaexe));
  if ($clconaberturaexe->numrows > 0){

      $oCon  = db_utils::fieldsMemory($rs1,0); 
      $iTipo = $oCon->c91_tipo;
			if ($iTipo == 2 ){

         $campos = " fc_estruturaldotacao(o58_anousu,o58_coddot) as descr,
				             o58_coddot as reduz ";
				 $join   = " inner join orcduplicacaodotacao on
				               o75_sequencial = o76_orcduplicacao 
										 inner join orcdotacao on 
				               o58_coddot = o76_coddot 
										   and o58_anousu = o76_anousu
									 inner join orcorgao on o40_anousu = o58_anousu
									     and o58_anousu = o40_anousu
									 ";
				if (isset($post->o40_orgao) && $post->o40_orgao != '-'){
					
				    $sWh = " and o58_orgao = ".$post->o40_orgao;
					
				}							 
				//$sWh   .= " and o58_instit = ".db_getsession("DB_instit");
				$order    = "descr";							 
				$lblDescr = "Dotação";
				$sDescr	= "Duplicação do Orçamento - Despesa Orçamentária - ".$oCon->c91_anousuorigem."/".$oCon->c91_anousudestino." - Configuração";						 

			}else if ($iTipo == 3){				
         $campos = " o57_fonte||' - '||o57_descr as descr ,
				             o70_codrec as reduz";
				 $join   = " inner join orcduplicacaoreceita on
                     o75_sequencial = o77_orcduplicacao
				             inner join orcreceita on 
				             o70_codrec = o77_codrec and o70_anousu = o77_anousu 
                     inner join orcfontes on o57_codfon = o70_codfon 
										       and o57_anousu = o70_Anousu";
				if (isset($post->strut) && $post->strut != ''){

           $sWh = " and o57_fonte like '".$post->strut."%'";   
				}
				$sWh   .= " and o70_instit = ".db_getsession("DB_instit");
        $order    = "descr";
				$sDescr	  = "Duplicação do Orçamento - Receita Orçamentária - ".$oCon->c91_anousuorigem."/".$oCon->c91_anousudestino." - Configuração";						 
				$lblDescr = "Receita";

          
			}	
	}

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
.cab  {color:white;font-weight:bold;text-align:center;
       padding:2px;
			 border-bottom:1px solid white;
			 border-left:1px solid white;           
       background-color:#000099;          
	
	}
</style>
<script>
function js_calculaPerc(campo1,campo2,campo3){

     fVlTotal = new Number(campo1.value);
     fPercent = new Number(campo2.value);
		 perct    =  ((fVlTotal*fPercent)/100)+fVlTotal;
		 campo3.value = js_round(perct,2);

}
function js_calculaPerc2(campo1,campo2,campo3){

     fVlTotal = new Number(campo1.value);
     fPercent = new Number(campo2.value);
		 fVlDup   = new Number(campo3.value);
		 if (fVlTotal != 0){
  		 perct    =  (((fVlDup-fVlTotal)*100)/fVlTotal);
		   campo2.value = js_round(perct,2);
		 }

}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<div class='cab'>
<?=$sDescr;?>
</div>
<table width="790" border="0" cellpadding="0" cellspacing="0">
<table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="360" height="5">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
  <center>
<form method='post' name='form1'>
<center>
<?
   if ($iTipo == 2){
      $rsOrg = $clorcorgao->sql_record($clorcorgao->sql_query(db_getsession("DB_anousu"),null,"o40_orgao,o40_descr"));
	    echo "<table><tr><td ><b> Filtrar por Órgão (Secretaria):</b></td>"; 
			echo "     <td>";
      db_selectrecord("o40_orgao", $rsOrg, true,1,null,null,null,'-',"document.form1.submit()");
			echo "     </td></tr></table>";
	 }else if ($iTipo == 3){


	    echo "<table><tr><td ><b> Filtrar Receitas por Categoria/Natureza:</b></td>"; 
			echo "     <td>";
      db_input("strut",20,null,1,'text',1,"onKeyPress='return js_teclas(event)'");
			echo "     </td></tr>";
			echo "     <td colspan='2' style='text-align:center'>";
      echo "     <input type='submit' value='filtrar'></table>";

	 }

?>
<br>
</center>
 <table cellspacing="0" width='95%'>
 <tr>
  <td class='cab' colspan=3>Dados da <?=$lblDescr;?></td>
  <td class='cab' colspan=3>Valores</td>
  <td class='cab'>Importar</td>
 </tr>
  <tr>
	<td bgcolor="#000099" class="cab">&nbsp;</td>
	<td bgcolor="#000099" class="cab">Descrição</td>
	<td bgcolor="#000099" class="cab">Reduzido</td>
<!--	<td bgcolor="blue" style='color:white;font-weight:bold;text-align:center'>acréscimos</td>
	<td bgcolor="blue" style='color:white;font-weight:bold;text-align:center'>reduções</td>-->
	<td bgcolor="#000099" class="cab">Atualizado</td>
	<td bgcolor="#000099" class="cab" size='15'>Percentual</td>
	<td bgcolor="#000099" class="cab">Valor a Duplicar</td>
	<td bgcolor="#000099" class="cab"><b>
	<input type='checkbox' style='display:none' id='mtodos' onclick='js_marca()'>
	<a onclick='js_marca()' style='cursor:pointer'>M</a></b></td>
</tr>	
<tbody  style='height:350;overflow:scroll;border:2px inset black;overflow-x:hidden'>
<?
if (isset($post->o40_orgao) or $iTipo == 3){

		$sSql = "select distinct orcduplicacao.*,$campos 
		           from orcduplicacao inner join conaberturaexe 
							   on o75_conaberturaexe = c91_sequencial
								 $join
							where o75_conaberturaexe = ".$get->o75_conaberturaexe." 
							$sWh
							order by $order";
  $rs       = pg_query($sSql);
	$iNumRows = pg_num_rows($rs);
  $pDisabled = '';
 for ($i = 0;$i < $iNumRows;$i++){
    
		$sBgcolor = $i % 2 == 0?"#EEEEEE":"#FFFFFF";
    $oRes = db_utils::fieldsMemory($rs,$i);
    if ($oRes->o75_importar == 'f'){

        $checked = '';
		}else{

       $checked = 'checked';
		}
		if ($oRes->o75_atualizado == '0'){
        $pDisabled = 'disabled';
		}else{
      
			 $pDisabled = '';

		}
		$sItens   = "chkitens[]";// nome do objeto dos itens.
		$sPrevini = "previni".$oRes->o75_sequencial; 
		$sAtuali  = "atuali".$oRes->o75_sequencial;
		$sPercen  = "percen".$oRes->o75_sequencial;
		$sVdupli  = "vdupli".$oRes->o75_sequencial;
	  $sImport  = "imp".$oRes->o75_sequencial;
		echo"<tr style='background-color:$sBgcolor'><td style='text-align:center'><input class='coddot' type='checkbox' name='$sItens'
		        value='".$oRes->o75_sequencial."' checked style='display:none'>
						
						<a href='#' onclick='js_mostraSaldo(".$oRes->reduz.",$iTipo)'><b>MI</b></a></td>";
		echo"<td>".$oRes->descr."</td>";
		echo"<td style='text-align:center'>".$oRes->reduz."</td>";
		echo"<td><input type='text' readonly size='12' id='$sAtuali' name='$sAtuali' style='text-align:right;width:100%' 
		     value='".round($oRes->o75_atualizado,2)."' onDblClick=\"document.getElementById('$sVdupli').value=this.value\"
				 ></td>";
		echo"<td style='width:15'><input type='text' onkeyPress='return js_teclas(event)' 
		      $pDisabled  maxlength='8' id='$sPercen' name='$sPercen' style='text-align:right;width:100%' 
		     value='".$oRes->o75_percentual."' onchange=\"js_calculaPerc(document.getElementById('$sAtuali'),this,document.getElementById('$sVdupli'))\">
				 </td>";
		echo"<td><input type='text' size='12' id='$sVdupli' name='$sVdupli' style='text-align:right;width:100%' 
		     onchange=\"js_calculaPerc2(document.getElementById('$sAtuali'),document.getElementById('$sPercen'),this)\"
		     value='".round($oRes->o75_valorduplicar,2)."'></td>";
		echo"<td  style='text-align:center'><input type='checkbox' class='chkimp' $checked  name='$sImport' id='$sImport' 
		     value='t'></td></tr>";
 }
}
?>
 <tr style='height: auto'><td>&nbsp;</td></tr>
</tbody>
<tr class='cab'>
	<td>&nbsp;</td>
	<td style='text-align:left'><b>Total de Registros:  <?=$iNumRows?></b></td>
	<td>&nbsp;</td>
	<td style='text-align:right'>Percentual Geral</td>
	<td style='width:15'><input type='text'
	     style='width:100%;text-align:right;font-weight:normal' name='perc' id='percgeral'></td>
	<td style='text-align:left'><input type='button' value='C' onclick="js_calcPercGeral(document.getElementById('percgeral').value)"></td>
	<td>&nbsp;</td>
</tr>	
</table>
<input type='submit' value='Atualizar' name='atualizar'>
</form> 
</body>
<script>
function js_mostraSaldo(chave,tipo){

    if (tipo == 2){

      arq = 'func_saldoorcdotacao.php?o58_coddot='+chave 

		}else{
      
      arq = 'func_saldoorcreceita.php?o70_codrec='+chave 
       

		}
  js_OpenJanelaIframe('top.corpo','db_iframe_saldos',arq,'Pesquisa',true);
}
function js_marca(){
  
	 obj = document.getElementById('mtodos');
	 if (obj.checked){
		 obj.checked = false;
	}else{
		 obj.checked = true;
	}
   itens = js_getElementbyClass(form1,'chkimp');
	 for (i = 0;i < itens.length;i++){

        if (obj.checked == true){
					itens[i].checked=true;
       }else{
					itens[i].checked=false;
			 }
	 }
}
function js_calcPercGeral(valor){

   itens = js_getElementbyClass(form1,'coddot');
	 for (i = 0;i < itens.length;i++){
      
			 oValorIni = document.getElementById('atuali'+itens[i].value);
			 oPercen   = document.getElementById('percen'+itens[i].value);
			 oVdupli   = document.getElementById('vdupli'+itens[i].value);
			 if (oValorIni.value != '0'){
			    oPercen.value = valor;
  			  js_calculaPerc(oValorIni,oPercen,oVdupli);
			    document.getElementById('imp'+itens[i].value).checked=true;
			 }

	 }
   

}
</script>