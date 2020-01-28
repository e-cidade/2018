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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_iptubase_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_iptunump_classe.php");
require_once ("classes/db_recibounica_classe.php");

$oDaoReciboUnica = new cl_recibounica();


(int)$parcelaini = 0;
(int)$parcelas   = 0;
(int)$mesini     = 0;
(float)$percentualdesconto = 0;
$diavenc         = '';

db_postmemory($HTTP_POST_VARS);
$cliptubase = new cl_iptubase;
$cliptubase->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
$clrotulo->label('z01_numcgm');
$clrotulo->label('k00_histtxt');

if(isset($calcular)){
  
  if(isset($HTTP_POST_VARS['j01_matric'])){

    /*
     * Verifica a situa��o do Calculo de IPTU
     *  Situa��es de bloqueio: 
     *   32 - Importado para Divida 
     */
    
    $sql = "select fc_iptu_verificacalculo($j01_matric::integer,$anousu::integer, 0, 0)";
    $result = db_query($sql);
    
    $retorno_result = pg_result($result,0,0);
    $retorno_erro   = substr($retorno_result,3,2);
          
    if ( $retorno_erro == 32 ) {

      $sMensagem = 'C�lculo de IPTU para este ano, j� esta em D�vida Ativa';
      db_msgbox($sMensagem);
      db_redireciona('cad4_calciptu.php');
    } 

    $sqlnextval = "select nextval('iptucalclog_j27_codigo_seq') as j27_codigo";
    $resultnextval = db_query($sqlnextval) or die($sqlnextval);
    if ($resultnextval == false) {
    	
      echo "<script>alert('Erro ao gerar sequencia!');</script>";
    } else {
    	
      db_fieldsmemory($resultnextval,0);
    
      $insert = "insert into iptucalclog values ($j27_codigo,$anousu,'".date('Y-m-d',db_getsession("DB_datausu"))."','".db_hora()."',".db_getsession('DB_id_usuario').",true,1)";
      $resultinsert = db_query($insert) or die($insert);
      if ($resultinsert == false) { 
        echo "<script>alert('Erro do gerar lancamento na tabela iptucalclog!');</script>";
      } else {
				$result=db_query("select distinct j18_anousu, j18_permvenc from cfiptu order by j18_anousu desc");
				$j18_permvenc = 1;
				if(pg_numrows($result) > 0){
					db_fieldsmemory($result,0);
				}
				if ($j18_permvenc == 0) {
					$j18_permvenc = 1;
				}
				
				if ($j18_permvenc == 1) {          
          // esta variavel e uma string no formato de um array plpgsql, nao altere seu conteudo se voce nao tem certeza do que esta fazendo
          $arraypl = "array['".(int)$parcelas."','".(int)$diavenc."','".(int)$mesini."']";
				} elseif ($j18_permvenc == 2) {
          // esta variavel e uma string no formato de um array plpgsql, nao altere seu conteudo se voce nao tem certeza do que esta fazendo
          $arraypl = "array['".(int)$parcelaini."','".(int)$parcelafinal."']";
				}
				$sql    = "select fc_calculoiptu($j01_matric::integer,$anousu::integer,true::boolean,false::boolean,false::boolean,false::boolean,false::boolean,$arraypl)";
     		
				$result = db_query($sql);
				
				if (!$result) {
				  
				  $sErro   = pg_last_error();
				  $iPosIni = strpos($sErro,"<erro>");
          $iPosFin = strpos($sErro,"</erro>");
          $sErro   = substr($sErro,$iPosIni,$iPosFin);
          $sErro   = str_replace("<erro>","",$sErro);
          $sErro   = str_replace("</erro>","",$sErro);
          
          $cliptubase->erro_msg    = $sErro;
          $cliptubase->erro_status = '0';				  
				  
				} else if (pg_numrows($result) > 0) {

					$retorno_result = pg_result($result,0,0);

          preg_match('/[0-9]*/', trim($retorno_result), $aTipoLogCalc);

					$retorno        = $aTipoLogCalc[0];
					
					if ($retorno != '001'){ 
						$cliptubase->erro_msg    = "Erro: ".$retorno_result;
						$cliptubase->erro_status = '0';
					} else {
						$cliptubase->erro_msg    = "C�lculo Efetuado.";
						$cliptubase->erro_status = '1';
					}    
					
					$insert = "insert into iptucalclogmat values ($j27_codigo,$j01_matric,$retorno,'".trim(preg_replace('/^[0-9]*/', '',trim($retorno_result)))."')";
					$resultinsert = db_query($insert) or die($insert);
					
				} else {
				  
					$cliptubase->erro_msg    = pg_last_error();
					$cliptubase->erro_status = '0';
				}
				
				if ((int) $percentualdesconto > 0) {
					
					$cliptunump = new cl_iptunump;
					$result = $cliptunump->sql_record($cliptunump->sql_query_file($anousu,$j01_matric,'j20_matric#j20_numpre'));
					if(!($result==false || $cliptunump->numrows == 0 )){
						$sqlunica = db_query("BEGIN");
						
                /*
                 * alteracao para incluir no cabe�alho criado para recibo unica
                 * recibounicageracao
                 */						
					      require_once("classes/db_recibounicageracao_classe.php");
                $oDaoReciboUnicaGeracao = new cl_recibounicageracao();
                
                $oDaoReciboUnicaGeracao->ar40_db_usuarios        = db_getsession("DB_id_usuario");
                $oDaoReciboUnicaGeracao->ar40_dtoperacao         = date("Y-m-d",db_getsession("DB_datausu"));
                $oDaoReciboUnicaGeracao->ar40_dtvencimento       = "{$anousu}-{$mesini}-{$diavenc}";
                $oDaoReciboUnicaGeracao->ar40_percentualdesconto = $percentualdesconto;
                $oDaoReciboUnicaGeracao->ar40_tipogeracao        = "G";
                $oDaoReciboUnicaGeracao->ar40_ativo              = 'true';
                $oDaoReciboUnicaGeracao->ar40_observacao         = 'Inclusao pelo calculo de iptu (cad4_calciptu.php)';
                $oDaoReciboUnicaGeracao->incluir(null);
                if($oDaoReciboUnicaGeracao->erro_status == 0){
                  
                  $descricao_erro = $oDaoReciboUnicaGeracao->erro_msg;
                }						
						
						for($i=0;$i<$cliptunump->numrows;$i++){
							
							db_fieldsmemory($result,$i);
							$sqlunica = db_query("select k00_dtvenc,k00_percdes
							from recibounica 
							where k00_numpre = $j20_numpre and k00_dtvenc = '$anousu-$mesini-$diavenc'");
							$erro = true;
							$perc = 0;
							if(pg_numrows($sqlunica)!=0){
								$perc = pg_result($sqlunica,0,'k00_percdes');
								$sqlresultunica = "delete from recibounica where k00_numpre = $j20_numpre and k00_dtvenc = '$anousu-$mesini-$diavenc'";
								$resultunica = db_query($sqlresultunica );
								$descricao_erro = "Vencimento Exclu�do.";
							}
							if(($perc!=$percentualdesconto) || (pg_numrows($sqlunica)==0)){
								
								//$sqlresultunica = "insert into recibounica values($j20_numpre,'$anousu-$mesini-$diavenc','" . date("Y-m-d",db_getsession("DB_datausu")) . "',$percentualdesconto, 'G',$oDaoReciboUnicaGeracao->ar40_sequencial )";
								//$resultunica = db_query($sqlresultunica );
							  $oDaoReciboUnica->k00_numpre             = $j20_numpre;
							  $oDaoReciboUnica->k00_dtvenc             = "$anousu-$mesini-$diavenc";
							  $oDaoReciboUnica->k00_dtoper             = date("Y-m-d",db_getsession("DB_datausu"));
							  $oDaoReciboUnica->k00_percdes            = $percentualdesconto;
							  $oDaoReciboUnica->k00_tipoger            = "G";
							  $oDaoReciboUnica->k00_recibounicageracao = $oDaoReciboUnicaGeracao->ar40_sequencial;
							  $oDaoReciboUnica->incluir(null);
								
								if($oDaoReciboUnica->erro_status == 0){
									$descricao_erro = $oDaoReciboUnica->erro_msg;
								}else{
									$descricao_erro = "Vencimento Inclu�do.";
								}
							}
							
							$histd  = "Data: ".date("Y-m-d",db_getsession("DB_datausu"));
							$histd .= " Perc: ".$percentualdesconto." Usu�rio: ".db_getsession("DB_login");
							$histd .= $k00_histtxt;
							
							$sqlresultunica = "insert into arrehist(k00_numpre,
							k00_numpar,
							k00_hist,
							k00_dtoper,
							k00_hora,
							k00_id_usuario,
							k00_histtxt,
							k00_idhist) 
							values ($j20_numpre,
							0,
							890,
							'".date("Y-m-d",db_getsession("DB_datausu"))."',
							'".date("G:i")."',
							".db_getsession("DB_id_usuario").",
							'$histd',
							nextval('arrehist_k00_idhist_seq'))";
							$resultunica = db_query($sqlresultunica );
							if($resultunica==false){
								$descricao_erro = "Erro ao incluir no arquivo historicos";
							} 
						}
						$sqlunica = db_query("COMMIT");
					}
					
				}

			}

	  }
    
  }else{
    $cliptubase->erro_msg = 'Matricula n�o informada.';
    $cliptubase->erro_status = '0';
  }
}

if(isset($demonstrativo)){

  if(isset($HTTP_POST_VARS['j01_matric'])){
    
    $result=db_query("select distinct j18_anousu, j18_permvenc from cfiptu order by j18_anousu desc");
    if(pg_numrows($result) > 0){
      db_fieldsmemory($result,0);
    } else {
      $j18_permvenc = 0;
    }

		if ($j18_permvenc == 1) {          
      // esta variavel e uma string no formato de um array plpgsql, nao altere seu conteudo se voce nao tem certeza do que esta fazendo
      $arraypl = "array['".(int)$parcelas."','".(int)$diavenc."','".(int)$mesini."']";
		} elseif ($j18_permvenc == 2) {
      // esta variavel e uma string no formato de um array plpgsql, nao altere seu conteudo se voce nao tem certeza do que esta fazendo
      $arraypl = "array['".(int)$parcelaini."','".(int)$parcelafinal."']";
		}else{
      $arraypl = "array['".(int)$parcelas."','".(int)$diavenc."','".(int)$mesini."']";
    }
    $sql = "select fc_calculoiptu($j01_matric::integer,$anousu::integer,true::boolean,false::boolean,false::boolean,false::boolean,true::boolean,".$arraypl.")";
    $result = db_query($sql);

    if (!$result) {
          
      $sErro   = pg_last_error();
      $iPosIni = strpos($sErro,"<erro>");
      $iPosFin = strpos($sErro,"</erro>");
      $sErro   = substr($sErro,$iPosIni,$iPosFin);
      $sErro   = str_replace("<erro>","",$sErro);
      $sErro   = str_replace("</erro>","",$sErro);
      
      $cliptubase->erro_msg    = $sErro;
      $cliptubase->erro_status = '0';         
          
    } else if (pg_numrows($result) > 0) {
      
      $retorno_result = @pg_result($result,0,0);

      preg_match('/[0-9]*/', trim($retorno_result), $aTipoLogCalc);

      $retorno        = $aTipoLogCalc[0];
      
      if($retorno != '001' and $retorno!=' '){
        $cliptubase->erro_msg    = "Demonstrativo efetuado!";
        $cliptubase->erro_status = '1';
      } else {
        $cliptubase->erro_msg    = "Erro: ".$retorno_result;
        $cliptubase->erro_status = '0';
      }
      
    } else {
      $cliptubase->erro_msg = pg_last_error();
      $cliptubase->erro_status = '0';
    }
  } else {
    $cliptubase->erro_msg = 'Matricula n�o informada.';
    $cliptubase->erro_status = '0';
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
<script>
function js_verificacalculo(){
  if(document.form1.j01_matric.value == ""){
	  
    alert('Informe uma Matr�cula.');
    return false;
  }
  return true;
}

</script>
<style>
textarea {
  font-family:Courier, Arial, Helvetica, sans-serif;
  font-size: 11px;
  color: #000000;
  background-color: #FFFFFF;
  border: 1px ;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.j01_matric.focus();" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
<tr> 
<td width="360" height="18">&nbsp;</td>
<td width="263">&nbsp;</td>
<td width="25">&nbsp;</td>
<td width="140">&nbsp;</td>
</tr>
</table>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td height="100%" align="center" valign="top" bgcolor="#CCCCCC">
<form name="form1" action="" method="post" onSubmit="return js_verificacalculo();">
<table width="387" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td width="27" height="25" title="<?=$Tz01_nunmcgm?>"> 
<?
db_ancora('<strong>Matricula:</strong>','js_mostranomes(true);',4)
?>
</td>
<td width="360" height="25"> 
<?
db_input("j01_matric",8,$Ij01_matric,true,'text',4," onchange='js_mostranomes(false);' ")
?>
</td>
</tr>
<tr>
<td height="25">
<?
db_ancora('<strong>Nome:</strong>','js_mostranomes(true);',4)
?>
</td>
<td height="25">
<?
db_input("z01_nome",40,$Iz01_nome,true,'text',3)
?>
</td>
</tr>
<tr>
<td height="25">
<strong>Ano:</strong>
</td>
<td height="25">
<?
$result=db_query("select distinct j18_anousu from cfiptu order by j18_anousu desc");
if(pg_numrows($result) > 0){
	$selected = "";
  ?>
  <select name="anousu">
  <?
  for($i=0;$i<pg_numrows($result);$i++){
    db_fieldsmemory($result,$i);
    if ($j18_anousu == $anousu) {
    	$selected = " selected ";
    } else {
    	$selected = ""; 
    }		
    ?>
    <option value='<?=$j18_anousu?>' <?=$selected?> ><?=$j18_anousu?></option>
    <?
  }
  ?>
  </select>
  <?
} else {
  $j18_permvenc = 0;
}
?>
</td>
</tr>

<?

$rsPar = db_query("select j18_permvenc from cfiptu where j18_anousu = ".db_getsession('DB_anousu')." ");
if(pg_num_rows($rsPar) > 0){
  db_fieldsmemory($rsPar,0);
}

if ($j18_permvenc == 1) {
  ?>
  
  <tr> 
  <td width="27" height="25"> 
  <b>Dia para vencimento:</b>
  </td>
  <td width="360" height="25"> 
  <?
  db_input("diavenc",8,"",true,'text',4,"")
  ?>
  </td>
  </tr>
  
  
  <tr> 
  <td width="27" height="25"> 
  <b>Parcelas:</b>
  </td>
  <td width="360" height="25"> 
  <?
  db_input("parcelas",8,"",true,'text',4,"")
  ?>
  </td>
  </tr>
  
  
  <tr> 
  <td width="27" height="25"> 
  <b>Mes inicial:</b>
  </td>
  <td width="360" height="25"> 
  <?
  db_input("mesini",8,"",true,'text',4,"")
  ?>
  </td>
  </tr>
  
  
  <tr> 
  <td width="27" height="25"> 
  <b>Percentual desconto da parcela unica:</b>
  </td>
  <td width="360" height="25"> 
  <?
  db_input("percentualdesconto",8,"",true,'text',4,"")
  ?>
  </td>
  </tr>
  
  
  <tr>
  <td height="25"><b>Hist&oacute;rico:</b></td>
  <td height="25">
  <?
  $k00_histtxt = trim(@$k00_histtxt);
  db_textarea('k00_histtxt',5,30,$Ik00_histtxt,true,'text',4);
  ?>
  </td>
  </tr>
  
  
  <?
} else if ($j18_permvenc == 2) {
  ?>
  
  
  <tr> 
  <td width="27" height="25"> 
  <b>Parcela inicial:</b>
  </td>
  <td width="360" height="25"> 
  <?
  db_input("parcelaini",8,"",true,'text',4,"")
  ?>
  </td>
  </tr>
  
  <tr> 
  <td width="27" height="25"> 
  <b>Parcela final:</b>
  </td>
  <td width="360" height="25"> 
  <?
  db_input("parcelafinal",8,"",true,'text',4,"")
  ?>
  </td>
  </tr>
  
  <tr> 
  <td width="27" height="25"> 
  <b>Percentual desconto da parcela unica:</b>
  </td>
  <td width="360" height="25"> 
  <?
  db_input("percentualdesconto",8,"",true,'text',4,"")
  ?>
  </td>
  </tr>
  
  <tr>
  <td height="25"><b>Hist&oacute;rico:</b></td>
  <td height="25">
  <?
  $k00_histtxt = trim(@$k00_histtxt);
  db_textarea('k00_histtxt',5,30,$Ik00_histtxt,true,'text',4);
  ?>
  </td>
  </tr>
  
  <?
}
?>

          <tr> 
            <td height="25">&nbsp;</td>
            <td height="25"> 
              <input name="calcular"  type="submit" id="calcular" value="Calcular" onClick="return js_verificaParametros();">
              <input name="demonstrativo"  type="submit" id="demonstrativo" value="Demonstrativo"> 
              <?
                if(isset($calcular) && $cliptubase->erro_status != '0' ){
                  ?>
                    <input name="Limpar" type="button" id="limpr"  value="Limpar"                       onClick="document.form1.j01_matric.value='';document.form1.z01_nome.value=''">
                    <input name="ultimo" type="button" id="ultimo" value="&Uacute;ltimo C&aacute;lculo" onClick="func_nome.show();  func_nome.focus();">
                  <?
                }
              ?>
            </td>
          </tr>
          <tr>
            <td colspan=3>
              <textarea id="text_demo" name="text_demo" rows=20 cols=95 style="visibility:hidden" disabled><?=$retorno_result?></textarea>
            </td>
          <tr>
        </table>
      </form>
    </td>
  <tr>
</table>
</body>
</html>

<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>

function js_verificaParametros(){
  
  var oMesini   = document.form1.mesini;
  var oParcelas = document.form1.parcelas;
  var oDiaVenc  = document.form1.diavenc;
  
  if (!oMesini && !oParcelas && !oDiaVenc) {
    return true;
  }

  var iMesIni   = new Number(oMesini.value);
  var iParcelas = new Number(oParcelas.value);
  if ( (iMesIni+iParcelas) > 13 ) {
    alert('N�o � permitido vencimento no ano posterior ao do calculo. ')
    return false;
  }

  return true;

}

function js_mostranomes(mostra){
  if(mostra==true){
    func_nome.jan.location.href = 'func_iptubase.php?funcao_js=parent.js_preenche|j01_matric|z01_nome';
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
  }else{
    func_nome.jan.location.href = 'func_iptubase.php?pesquisa_chave='+document.form1.j01_matric.value+'&funcao_js=parent.js_preenche1';	
  }
}
function js_preenche(chave,chave1){
  document.form1.j01_matric.value = chave;
  document.form1.z01_nome.value = chave1;
  func_nome.hide();
}
function js_preenche1(chave,chave1){
  document.form1.z01_nome.value = chave;
  if(chave1==false){
    document.form1.j01_matric.select();
    document.form1.j01_matric.focus();
  }
  func_nome.hide();
}

</script>
<?

$func_nome = new janela('func_nome','');
$func_nome->posX           = 1;
$func_nome->posY           = 20;
$func_nome->largura        = 770;
$func_nome->altura         = 430;
$func_nome->titulo         = "Pesquisa";
$func_nome->iniciarVisivel = false;
$func_nome->mostrar();

$cliptubase->erro(true,false);

if ( $cliptubase->erro_status != '0' ) {
  
  if(isset($calcular)){
    ?>
    <script>
   
    js_OpenJanelaIframe('top.corpo','db_iframe_funcnome','cad3_conscadastro_002_detalhes.php?solicitacao=Calculo&parametro=<?=$HTTP_POST_VARS['j01_matric']?>','Pesquisa',true);
    </script>
    <?
  } else if(isset($demonstrativo)){
    ?>
    <script>
    document.form1.text_demo.style.disabled   = true;
    document.form1.text_demo.style.visibility = "visible";
    </script>
    <?
  }
}

?>