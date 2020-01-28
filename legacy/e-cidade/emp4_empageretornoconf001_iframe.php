<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_empage_classe.php"));
require_once(modification("classes/db_empageconf_classe.php"));
require_once(modification("classes/db_errobanco_classe.php"));
$clempage     = new cl_empage;
$clempageconf = new cl_empageconf;
$clerrobanco  = new cl_errobanco;

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
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">
        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
        <script type="text/javascript" src="scripts/prototype.js"></script>
        <link href="estilos.css" rel="stylesheet" type="text/css">
<style>
<?$cor ="#999999 "?>.bordas {
	border: 1px solid #cccccc;
	border-top-color: <?=$cor?>;
	border-right-color: <?=$cor?>;
	border-left-color: <?=$cor?>;
	border-bottom-color: <?=$cor?>;
	background-color: #cccccc;
}

.bordas01 {
	border: 1px solid #cccccc;
	border-top-color: #999999;
	border-right-color: #999999;
	border-left-color: #999999;
	border-bottom-color: #999999;
	background-color: #DEB887;
}

.bordas02 {
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"
	onLoad="a=1">
	<table width="100%" height="100%" border="0" cellspacing="2"
		cellpadding="0">
		<tr>
			<td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
				<form name="form1" method="post" action="">
					<table align="center" border='7' height="100%" width="100%">
						<?
						$totalmovs = 0;
						$valormovs = 0;
						$valordebs = 0;
						$valoragen = 0;
						$totalagen = 0;
						db_input("retornoarq",10,'',true,'hidden',3);
						if(isset($retornoarq) && trim($retornoarq)!=""){
						  $dbwhere = "    e80_instit = " . db_getsession("DB_instit") . "
            						  and e75_codret in ( {$retornoarq})
            						  and e90_correto ='t'
            						  and (((e53_valor-e53_vlranu-e53_vlrpag) > 0
            						  and e60_instit=".db_getsession("DB_instit").")
            						  or (k17_situacao = 1 and k17_instit = " . db_getsession("DB_instit") . "))";
            						  if(isset($contapaga)){
						    $dbwhere .= " and e83_codtipo=$contapaga ";
						  }

              if (isset($lCancelado) && $lCancelado == '0') {

               $dbwhere .= " and empageconfgera.e90_cancelado is false ";
              }

              $dbwhere .= " and corempagemov.k12_codmov is null ";
						  $sSqlEmpAgeCons = $clempage->sql_query_cons(null,
						      "distinct e87_codgera,
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
						      e76_codret,
						      e81_codmov,
						      fc_valorretencaomov(e81_codmov, false,e87_dataproc) as vlrretencao,
						      case when e60_codemp is null then 'slip'
						      else e60_codemp end as e60_codemp,
						      case when e82_codord is null then e89_codigo
						      else e82_codord end as e82_codord,
						      e86_codmov,
						      case when a.z01_numcgm is null  and cgmslip.z01_numcgm is null
						      then cgm.z01_numcgm
						      when a.z01_numcgm is not null then a.z01_numcgm
						      when cgmslip.z01_numcgm is not null then  cgmslip.z01_numcgm
						      end as z01_numcgm,
						      case when (a.z01_nome = '' or a.z01_nome  is null)
						      and (cgmslip.z01_nome = '' or cgmslip.z01_nome is null)
						      then cgm.z01_nome
						      when cgmslip.z01_nome <> '' then  cgmslip.z01_nome
						      when  a.z01_nome <> '' then a.z01_nome end as z01_nome,
						      e81_valor,
						      e83_codtipo,
						      e83_descr",
						      "e83_codtipo,e76_lote,e76_movlote",
						      $dbwhere);

						  $result_arq  = $clempage->sql_record($sSqlEmpAgeCons);
						  $numrows_arq = $clempage->numrows;
						  $arr_valorcontas = Array();
						  $arr_valorproces = Array();
						  $arr_valoragenda = Array();
						  if ($numrows_arq > 0) {

						    for ($i = 0; $i < $numrows_arq; $i++) {

						      db_fieldsmemory($result_arq,$i);
						      $sSqlDadosOcorrencia      = "select e92_coderro, ";
						      $sSqlDadosOcorrencia     .= "       e92_sequencia, ";
						      $sSqlDadosOcorrencia     .= "       e92_descrerro, ";
						      $sSqlDadosOcorrencia     .= "       e92_processa ";
						      $sSqlDadosOcorrencia     .= "  from empagedadosretmovocorrencia  " ;
						      $sSqlDadosOcorrencia     .= "       inner join errobanco on e92_sequencia = empagedadosretmovocorrencia.e02_errobanco ";
						      $sSqlDadosOcorrencia     .= " where empagedadosretmovocorrencia.e02_empagedadosret    in ({$e76_codret})";
						      $sSqlDadosOcorrencia     .= "   and empagedadosretmovocorrencia.e02_empagedadosretmov in ({$e81_codmov})";
						      $rsDadoOcorrencia         = db_query($sSqlDadosOcorrencia);
						      $iTotalLinhasOcorrencias  =  pg_num_rows($rsDadoOcorrencia);
						      if ($iTotalLinhasOcorrencias) {
						        db_fieldsmemory($rsDadoOcorrencia, 0);
						      }
						      $valormovs += $e81_valor;
						      if(!isset($arr_valorcontas[$e83_codtipo])){
						        $arr_valorcontas[$e83_codtipo] = 0;
						      }
						      $arr_valorcontas[$e83_codtipo] += $e81_valor;

						      if(!isset($arr_valorproces[$e83_codtipo])){
						        $arr_valorproces[$e83_codtipo] = 0;
						      }
						      if(!isset($arr_valorret[$e83_codtipo])){
						        $arr_valorret[$e83_codtipo] = $vlrretencao;
						      } else {
						        $arr_valorret[$e83_codtipo] += $vlrretencao;
						      }
						      if($e92_processa=='t' && $e92_sequencia!=35){
						        $totalmovs++;
						        $arr_valorproces[$e83_codtipo] += $e76_valorefet;
						        $valordebs += $e76_valorefet;
						      }else if($e92_sequencia == 35 || ProcessamentoPagamentoFornecedor::retornoAgendamento($e92_sequencia)){
						        $totalagen ++;
						        if (isset($arr_valoragenda[$e83_codtipo])){
						          $arr_valoragenda[$e83_codtipo] += $e76_valorefet;
						        } else {
						          $arr_valoragenda[$e83_codtipo] = $e76_valorefet;
						        }
						        $valoragen += $e76_valorefet;
						      }
						    }
						    echo "
						    <thead>
						    <tr>
						    <td colspan='11' align='center'>
						    <b>Arquivo enviado &nbsp;&nbsp;&nbsp;&nbsp;$e87_codgera - $e87_descgera</b><br>
						    <b>Valor debitado:</b>".trim(db_formatar(@$valordebs,'f'))."&nbsp;&nbsp;&nbsp;&nbsp;
						    <b>Valor movimentos:</b>".trim(db_formatar(@$valormovs,'f'))."</span>&nbsp;&nbsp;&nbsp;&nbsp;
						    <b>Valor agendado:</b>".trim(db_formatar(@$valoragen,'f'))."</span>&nbsp;&nbsp;&nbsp;&nbsp;
						    <b>Total efetuados:</b>".$totalmovs."</span>&nbsp;&nbsp;&nbsp;&nbsp;
						    <b>Total agendados:</b>".$totalagen."</span>
						    </td>
						    </tr>
						    <tr>
						    <td class='bordas02' align='left' colspan='11'><b>Conta pagadora</b></td>
						    </tr>
						    <tr>
						    <td class='bordas02' align='center' title='Inverte Marcação'>
						    ";
						    db_ancora("M",'js_marca(this)',1);
						    echo "
						    </td>
						    <td class='bordas02' align='center'><b>$RLe82_codord</b></td>
						    <td class='bordas02' align='center'><b>$RLe60_codemp</b></td>
						    <td class='bordas02' align='center'><b>$RLz01_nome</b></td>
						    <td class='bordas02' align='center'><b>Retorno</b></td>
						    <td class='bordas02' align='center'><b>$RLe80_data</b></td>
						    <td class='bordas02' align='center'><b>Data processo</b></td>
						    <td class='bordas02' align='center'><b>Valor movimentos</b></td>
						    <td class='bordas02' align='center'><b>Valor Ret</b></td>
						    <td class='bordas02' align='center'><b>Valor processo</b></td>
						    </tr>
						    </thead>
						    <tbody style='overflow:auto;' height='100%'>
						    ";
						  }else{
						    echo "<tr><td><b>Movimentos já baixados ou cancelados.</b></td></tr>";
						  }

						  $pagadora = "";
						  for($i = 0;$i<$numrows_arq;$i++){

						    db_fieldsmemory($result_arq,$i);

						    $disab02 = false;
						    $sSqlDadosOcorrencia   = "select e92_coderro, ";
						    $sSqlDadosOcorrencia  .= "       e92_sequencia, ";
						    $sSqlDadosOcorrencia  .= "       e92_descrerro, ";
						    $sSqlDadosOcorrencia  .= "       e92_processa ";
						    $sSqlDadosOcorrencia  .= "  from empagedadosretmovocorrencia  " ;
						    $sSqlDadosOcorrencia  .= "       left  join errobanco on e92_sequencia = empagedadosretmovocorrencia.e02_errobanco ";
						    $sSqlDadosOcorrencia  .= " where empagedadosretmovocorrencia.e02_empagedadosret    in ({$e76_codret})";
						    $sSqlDadosOcorrencia  .= "   and empagedadosretmovocorrencia.e02_empagedadosretmov in ({$e81_codmov})";
						    //$sSqlDadosOcorrencia  .= "   and e92_processa is true";
						    $rsDadoOcorrencia      = db_query($sSqlDadosOcorrencia);
						    $iTotalLinhas          = pg_num_rows($rsDadoOcorrencia);
						    if ($iTotalLinhas > 0) {
						      db_fieldsmemory($rsDadoOcorrencia,0);
						    }
						    if($e86_codmov== ''){
						      $disab02 = true;
						    }
						    $class = "";
						    $disab = "";
						    $disab01 = false;
						    $check = " checked ";
						    if($e92_processa=='f' || $e92_sequencia==35){
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
						      <td colspan='1' class='bordas' align='left'>
						      <b>".db_formatar($arr_valorret[$e83_codtipo],"f")."</b>
						      </td>
						      <td colspan='3' class='bordas' align='left'>
						      <b>".db_formatar($arr_valorproces[$e83_codtipo],"f")."</b>
						      </td>
						      </tr>";
						    }
						    echo "
						    <tr>
						    <td class='bordas$class' nowrap>


						    <input $disab $check value='{$e81_codmov}' name='CHECK_$e81_codmov' type='checkbox'>

						    </td>
						    <td class='bordas$class'><small>$e82_codord ";
						    if($disab01==true){
						      echo "<span style=\"color:darkblue;\">**</span>";
						    }
						    echo "
						    </small>
						    </td>
						    <td class='bordas$class'><small>$e60_codemp</small></td>
						    <td class='bordas$class'><small>$z01_nome</small></td>
						    <td class='bordas$class'><small>".@$e92_descrerro."</small></td>
						    <td class='bordas$class'><small>".db_formatar($e87_data,"d")."</small></td>
						    <td class='bordas$class'><small>".db_formatar($e76_dataefet,"d")."</small></td>
						    <td class='bordas$class'><small>".db_formatar($e81_valor,"f")."</small></td>
						    <td class='bordas$class'><small>".db_formatar($vlrretencao,"f")."</small></td>
						    <td class='bordas$class'><small>".db_formatar($e76_valorefet,"f")."</small></td>
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
				</form>
			</td>
		</tr>
	</table>
</body>
</html>
