<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("dbforms/db_funcoes.php");
require_once("classes/db_empage_classe.php");
require_once("classes/db_empageconf_classe.php");
require_once("classes/db_errobanco_classe.php");
$clempage      = new cl_empage;
$clempageconf  = new cl_empageconf;
$clerrobanco   = new cl_errobanco;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = false;

$clrotulo = new rotulocampo;
$clrotulo->label("e82_codord");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("e60_emiss");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e83_codtipo");
$clrotulo->label("e81_valor");
$clrotulo->label("e81_codmov");
$clrotulo->label("e86_cheque");
$clrotulo->label("e76_lote");
$clrotulo->label("e76_movlote");
$clrotulo->label("e80_data");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
<?$cor="#999999"?>
.bordas{
          border: 1px solid #cccccc;
          border-top-color: <?=$cor?>;
          border-right-color: <?=$cor?>;
          border-left-color: <?=$cor?>;
          border-bottom-color: <?=$cor?>;
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
          border-top-color: <?=$cor?>;
          border-right-color: <?=$cor?>;
          border-left-color: <?=$cor?>;
          border-bottom-color: <?=$cor?>;
          background-color: #999999;
}
</style>
<script>
function js_marca(obj){ 
  var OBJ = document.form1;
  soma=new Number();
  for(i=0;i<OBJ.length;i++){
    if(OBJ.elements[i].type == 'checkbox' && OBJ.elements[i].disabled==false){
      OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
    }
  }
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" height="100%" border="0" cellspacing="2" cellpadding="0">
  <tr> 
    <td height="100%" width="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
      <form name="form1" method="post" action="">
      <center>
      <table align="center" border='7' height="100%" width="100%">
<?
  $totalmovs = 0;
  $valormovs = 0;
  $valordebs = 0;
  $valoragen = 0;
  $totalagen = 0;
  db_input("retornoarq",10,'',true,'hidden',3);  
  if(isset($retornoarq) && trim($retornoarq)!=""){
    $dbwhere = " e80_instit = " . db_getsession("DB_instit") . " and e75_codret = ".$retornoarq." and e90_correto='t' and e53_vlrpag > 0 ";
    if(isset($contapaga)){
      $dbwhere .= " and e83_codtipo=$contapaga ";
    }
    
    if (isset($lCancelado) && $lCancelado == '0') {
    
      $dbwhere .= " and empageconfgera.e90_cancelado is false ";
    }
    
    $sSqlArquivoRetorno = $clempage->sql_query_rel_arqretorno(null,"e87_codgera,
                                                                    e87_descgera,
                                                                    e87_data,
                                                                    e87_hora,
                                                                    e83_descr,
                                                                    pc63_conta,
                                                                    pc63_conta_dig,
                                                                    pc63_agencia,        
                                                                    pc63_agencia_dig,
                                                                    e75_arquivoret, 
                                                                    e76_lote,
                                                                    e76_movlote,
                                                                    e76_dataefet,
                                                                    e76_valorefet,
                                                                    e02_errobanco,
                                                                    e81_codmov,
                                                                    e60_codemp,
                                                                    e82_codord,
                                                                    e86_codmov,
                                                                    case 
                                                                       when a.z01_numcgm is null 
                                                                         then cgm.z01_numcgm 
                                                                         else a.z01_numcgm end as z01_numcgm,
                                                                           
                                                                           case when a.z01_nome='' 
                                                                           or a.z01_nome is null 
                                                                         then 
                                                                           cgm.z01_nome else a.z01_nome 
                                                                         end as z01_nome,
                                                                         e81_valor,
                                                                         e83_codtipo,
                                                                         e83_descr",
                                                                         "e83_codtipo,
                                                                         e76_lote,
                                                                         e76_movlote,
                                                                         e82_codord",
                                                                    $dbwhere);
    //die($sSqlArquivoRetorno);
    $result_arq  = $clempage->sql_record($sSqlArquivoRetorno);
    
    $numrows_arq = $clempage->numrows;
    $arr_valorcontas = Array();
    $arr_valorproces = Array();
    $arr_valoragenda = Array();
    if($numrows_arq>0){
      for($i = 0;$i<$numrows_arq;$i++){
	db_fieldsmemory($result_arq,$i);
	$result_errobanco = $clerrobanco->sql_record($clerrobanco->sql_query_file($e02_errobanco,"e92_sequencia,e92_descrerro,e92_processa"));
	if($clerrobanco->numrows>0){
	  db_fieldsmemory($result_errobanco,0);
	}
        $valormovs += $e81_valor;
	if(!isset($arr_valorcontas[$e83_codtipo])){
	  $arr_valorcontas[$e83_codtipo] = 0;
	}
        $arr_valorcontas[$e83_codtipo] += $e81_valor ;

	if(!isset($arr_valorproces[$e83_codtipo])){
	  $arr_valorproces[$e83_codtipo] = 0;
	}
	
	if($e92_processa=='f' && $e92_sequencia!=35){
	  $totalmovs++;	  
	  $arr_valorproces[$e83_codtipo] += $e81_valor - $e76_valorefet;
	  $valordebs += $e81_valor - $e76_valorefet;
      }else if($e92_sequencia == 35){
	$totalagen ++;
	$arr_valoragenda[$e83_codtipo] += $e76_valorefet;
	$valoragen += $e76_valorefet;
	}
      }
      echo "
      <thead>
      <tr>
	<td colspan='11' align='center'>
          <b>Arquivo enviado &nbsp;&nbsp;&nbsp;&nbsp;$e87_codgera - $e87_descgera</b><br>
	  <b>Valor a cancelar:</b>".trim(db_formatar(@$valordebs,'f'))."&nbsp;&nbsp;&nbsp;&nbsp;
	  <b>Valor movimentos:</b>".trim(db_formatar(@$valormovs,'f'))."</span>&nbsp;&nbsp;&nbsp;&nbsp;
	              <b>Valor agendado:</b>".trim(db_formatar(@$valoragen,'f'))."</span>&nbsp;&nbsp;&nbsp;&nbsp;
	  <b>Movimentos a cancelar:</b>".$totalmovs."</span>&nbsp;&nbsp;&nbsp;&nbsp;
	              <b>Total agendados:</b>".$totalagen."</span>
	</td>
      </tr>
      <tr>
	<td class='bordas02' align='left' colspan='11'><b>Conta pagadora</b></td>
      </tr>
      <tr>
	<td class='bordas02' align='center' title='Inverte Marcação'>";
          db_ancora("M",'js_marca(this)',1);
	echo "
	</td>
	<td class='bordas02' align='center'><b>$RLe60_codemp</b></td>
	<td class='bordas02' align='center'><b>$RLe82_codord</b></td>
	<td class='bordas02' align='center'><b>$RLz01_nome</b></td>
	<td class='bordas02' align='center'><b>Retorno</b></td>
	<td class='bordas02' align='center'><b>$RLe80_data</b></td>
	<td class='bordas02' align='center'><b>Data processo</b></td>
	<td class='bordas02' align='center'><b>Valor movimentos</b></td>
	<td class='bordas02' align='center'><b>Valor a cancelar</b></td>
      </tr>
      </thead>
      <tbody style='overflow:auto;' height='100%'>
      ";
      /*
	<td class='bordas02' align='center'><b>Lote Nro.</b></td>
	<td class='bordas02' align='center'><b>Movimento Lote</b></td>
      */
    }else{
      echo "<tr><td><b>Movimentos já baixados ou cancelados.</b></td></tr>";
    }

    $pagadora = "";
    for($i = 0;$i<$numrows_arq;$i++){
      db_fieldsmemory($result_arq,$i);
      $disab02 = false;

      $result_errobanco = $clerrobanco->sql_record($clerrobanco->sql_query_file($e02_errobanco,"e92_sequencia,e92_descrerro,e92_processa"));
      
      if($clerrobanco->numrows>0){
        db_fieldsmemory($result_errobanco,0);
      }
      if($e86_codmov== ''){
	$disab02 = true;
      }
      $class = "";
      $disab = "";
      $disab01 = false;
      $check = " checked ";
      if($e92_processa=='t' || $e92_sequencia==35){
        
      	$class = "01";
      	$disab = " disabled ";
      	$disab01 = true;
      	$check = "";
      }

      if($pagadora!=$e83_codtipo){
	$pagadora = $e83_codtipo;
	if($i!=0){
	  echo "<tr><td colspan='11' align='left'>&nbsp;</td></tr>";
        }
	echo "<tr>
	        <td colspan='7' class='bordas' align='left'>
		  <b>$e83_descr</b>
		</td>
	        <td colspan='1' class='bordas' align='left'>
		  <b>".db_formatar($arr_valorcontas[$e83_codtipo],"f")."</b>
		</td>
	        <td colspan='3' class='bordas' align='left'>
		  <b>".db_formatar($arr_valorproces[$e83_codtipo],"f")."</b>
		</td>
	      </tr>";
      }
      echo "
      <tr>
        <td class='bordas$class' nowrap>
        
        
	  <input $disab $check value='$e81_codmov' name='CHECK_$e81_codmov' type='checkbox'>
        </td>
	<td class='bordas$class'><small>$e60_codemp</small></td>
	<td class='bordas$class'><small>$e82_codord ";
	  if($disab01==true){
	    echo "<span style=\"color:darkblue;\">**</span>";
	  }
      echo "
          </small>
	</td>
	<td class='bordas$class'><small>$z01_nome</small></td>
	<td class='bordas$class'><small>".@$e92_descrerro."</small></td>
	<td class='bordas$class'><small>".db_formatar($e87_data,"d")."</small></td>
	<td class='bordas$class'><small>".db_formatar($e76_dataefet,"d")."</small></td>
	<td class='bordas$class'><small>".db_formatar($e81_valor,"f")."</small></td>
	<td class='bordas$class'><small>".db_formatar(($e81_valor-$e76_valorefet),"f")."</small></td>
      </tr>
      ";
      /*
	<td class='bordas'><small>$e76_lote</small></td>
	<td class='bordas'><small>$e76_movlote</small></td>
      */
    }
    if($numrows_arq>0){
      echo "</tbody>";
    }
  }
?>
      </table>
      </center>
      </form>
    </td>
  </tr>
</table>
</body>
</html>
<script>
</script>