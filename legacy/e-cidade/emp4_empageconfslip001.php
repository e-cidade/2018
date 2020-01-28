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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");

include ("classes/db_empagetipo_classe.php");
include ("classes/db_empage_classe.php");
include ("classes/db_empageslip_classe.php");
include ("classes/db_empagemov_classe.php");
include ("classes/db_empagegera_classe.php");
include ("classes/db_empageconf_classe.php");
include ("classes/db_empageconfche_classe.php");
include ("classes/db_empageconfgera_classe.php");
include ("classes/db_conplanoconta_classe.php");
include ("classes/db_empagepag_classe.php");
include ("classes/db_pagordem_classe.php");
include ("classes/db_db_config_classe.php");
include ("classes/db_cfautent_classe.php");
include ("classes/db_db_bancos_classe.php");
include ("libs/db_libcaixa.php");
$clempage = new cl_empage;
$clempageslip = new cl_empageslip;
$clconplanoconta = new cl_conplanoconta;
$clempagetipo = new cl_empagetipo;
$clempagemov = new cl_empagemov;
$clempagegera = new cl_empagegera;
$clempageconf = new cl_empageconf;
$clempageconfche = new cl_empageconfche;
$clempageconfgera = new cl_empageconfgera;
$clempagepag = new cl_empagepag;
$clpagordem = new cl_pagordem;
$cldb_config = new cl_db_config;
$clcfautent = new cl_cfautent;
$cldb_bancos = new cl_db_bancos;

db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;
$db_botao = false;
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));

//rotina que pega o nome do prefeito
$result00 = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit"), "pref as prefeito,munic as municipio"));
db_fieldsmemory($result00, 0);

//rotina que pega o nome do tesoureiro
$resu = $clcfautent->sql_record($clcfautent->sql_query_file(null, "k11_tipoimpcheque,k11_portaimpcheque,k11_tesoureiro as tesoureiro", "", "k11_ipterm='".db_getsession("DB_ip")."' and k11_instit=".db_getsession("DB_instit")));
if ($clcfautent->numrows > 0) {
	db_fieldsmemory($resu, 0);
	if (trim($tesoureiro) == "") {
		$mensagem_mostra = "Preencha o Nome/cargo no cadastro de autenticadoras.";
	}
} else {
	$mensagem_mostra = "Cadastre seu IP como autenticadora.";
	if (isset ($atualizar)) {
		unset ($atualizar);
	}
	if (isset ($prever)) {
		unset ($prever);
	}
}

if (isset ($e80_data_ano)) {
	$data = "$e80_data_ano-$e80_data_mes-$e80_data_dia";
}

if (isset ($atualizar) || isset ($prever)) {

    $sqlerro = false;

  //rotina que traz a sequencia do cheque
  $result = $clempagetipo->sql_record($clempagetipo->sql_query($codtipo, 'e83_sequencia as sequencia,e83_conta,e83_descr'));
  if($clempagetipo->numrows > 0){
    db_fieldsmemory($result, 0);
	$cheque_seq = '';
	$testa_sequencia_cheques = "";
	$vir = '';
	$arr_chequeseq = array ();
	for ($i = 0; $i < $cheques; $i ++) {
		
	  
	  $cheque_seq .= $vir.$sequencia;
	  $testa_sequencia_cheques .= $vir."'".$sequencia."'";
	  $vir = ',';
	  $arr_chequeseq[$i] = $sequencia;
	  $sequencia++;
	  	
	}
    $result_cheques_emitidos = $clempageconfche->sql_record($clempageconfche->sql_query_cheques_cancelados(null,"e91_cheque,e91_codmov,e86_data","e91_cheque","e85_codtipo=$codtipo and e91_cheque in ($testa_sequencia_cheques) and e93_codcheque is null"));
    if($clempageconfche->numrows > 0){
      $sqlerro = true;
      $erro_msg = "Impressão não poderá ser efetuada. Configure a sequência de cheques da conta: $codtipo (".$e83_descr.").";
      $erro_compl = "";
      for($i=0; $i<$clempageconfche->numrows; $i++){
      	db_fieldsmemory($result_cheques_emitidos,$i);
      	$erro_compl .= "\n- Cheque $e91_cheque já impresso para o movimento $e91_codmov (".db_formatar($e86_data,"d").")";
      }
      $erro_msg.= $erro_compl;
      $erro_msg = str_replace("\n","\\n",$erro_msg); 
    }
  }else{
  	$erro_msg = "Conta pagadora não encontrada.";
  	$sqlerro = false;
  }
  if($sqlerro == false){
    ///////////////////////////////////////////////////////////
    // Monta verso do cheque
    ///////////////////////////////////////////////////////////

	//variaveis criadas para testar quantos cgm diferentes existem... 
	$cgmprinc = '';
	$nome_nominal = false;

	$arr = split("XX", $movs);
	$tot_valor = '';
	$nomes = '';

    ///////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////
    ///////////////TESTAR VALOR DA CONTA///////////////////
    $data_valorConta = "$dtin_ano-$dtin_mes-$dtin_dia";
    $sql_valorConta = $clempagetipo->sql_query(null,
                                                         "
                                                          substr(fc_saltessaldo(e83_conta,'".$data_valorConta."','".$data_valorConta."',null,".db_getsession("DB_instit")."),41,13)::numeric as valoratualsaltes
                                                         ",
                                                         "",
                                                         " e83_codtipo = ".$codtipo
                                                      );
                                                      
    $result_valoConta = $clempagetipo->sql_record($sql_valorConta);
    
    if($clempagetipo->numrows > 0){
      db_fieldsmemory($result_valoConta, 0);
    }else{
      $sqlerro = true;
      $erro_msg = "Conta não encontrada. Verifique.\\n\\nCheque não gerado.";
    }
    $valoratualsaltes = str_replace(',','.',str_replace('.','',$vervaloratualsalteschequeliq));
    ///////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////

    if($sqlerro == false){
    // Rotina que pega os nomes
	$arr_m = array ();
	for ($i = 0; $i < count($arr); $i ++) {
		$mov = $arr[$i];

      // Rotina para calcular o valor total dos movimentos
		$result = $clempageslip->sql_record($clempageslip->sql_query_descr(null, null, "e81_valor as valor, z01_numcgm as numcgm_cre,z01_nome as nome_cre,z01_munic as munic_cre", null, " e80_instit = " . db_getsession("DB_instit") . " and e89_codmov = $mov"));
		if($clempageslip->numrows == 0){
		  db_msgbox("Movimento $mov não encontrado.");
		  exit;
		}

		db_fieldsmemory($result, 0);

		
		    $sqlpar = "select k29_saldoemitechq from caiparametro where k29_instit = ".db_getsession("DB_instit");
        $resultpar = pg_query($sqlpar);
        $linhaspar = pg_num_rows($resultpar);
        if($linhaspar>0){
          db_fieldsmemory($resultpar, 0);
          if($k29_saldoemitechq == 1){
          
	        ///////////////////////////////////////////////////////
	        ///////////////////////////////////////////////////////
	        ///////////////TESTAR VALOR DA CONTA///////////////////
	        // VERIFICAR SE HÁ VALOR DISPONÍVEL PARA PAGAR O CHEQUE
         if ((round(trim($valor),2) > round(trim($valoratualsaltes),2)) && isset($atualizar)){
	                      
	          $sqlerro = true;
	          $erro_msg = "Conta sem saldo disponível para este valor. Verifique.\\n\\nCheque não gerado.";
            
	          break;
	        }
	        
	        ///////////////////////////////////////////////////////
	        ///////////////////////////////////////////////////////
          }
        }
        $valoratualsaltes -= $valor;
        $tot_valor        += $valor;

		//rotina para pegar apenas os nomes diferentes 
		if (!array_key_exists($numcgm_cre, $arr_m)) {
			$arr_m[$numcgm_cre] = $numcgm_cre;
			$nomes .= "     ".$nome_cre.' - '.$munic_cre.'\n';
		}

		//rotina que verifica se exite numcgm diferentes
		if ($cgmprinc == "") {
			$cgmprinc = $numcgm_cre;
		}

		if ($cgmprinc != '' && $cgmprinc != $numcgm_cre) {
			$nome_nominal = true;
		}

	}
      }

    if($sqlerro == false){
	/*dados do fornecedor*/
	$sql04 = "select * from pcfornecon where pc63_numcgm=$numcgm_cre";
	$result04 = @ pg_query($sql04);
	$numrows04 = @ pg_numrows($result04);
	$dad_verso = '\n';
	if ($numrows04 > 0) {
		db_fieldsmemory($result04, 0);
	}

	$sql = "select c63_banco as codbco,
                   k13_descr 
		       from conplanoreduz
			    inner join conplanoconta on c61_codcon = c63_codcon and c61_anousu=c63_anousu
			    inner join saltes        on  k13_conta = c61_reduz
		       where c61_anousu = ".db_getsession("DB_anousu")." and c61_reduz = $e83_conta";

    $result = pg_query($sql);
    if($result == false || pg_numrows($result) == 0){
  	  $sqlerro = true;
  	  $erro_msg = "Conta não cadastrada no conplanoconta. Contate Contabilidade ($e83_conta).";
    }else{
      db_fieldsmemory($result, 0);
    }

    if($sqlerro == false){
	//verso----fornecedor
      $numrows_bancos = 0;
      if($numrows04 == 0){
        $k13_descr = '.';
      }else if(isset($pc63_banco) && trim($pc63_banco) != ""){
        $result_bancos = $cldb_bancos->sql_record($cldb_bancos->sql_query_file($pc63_banco, "db90_descr as k13_descr"));
        $numrows_bancos = $cldb_bancos->numrows;
        if($numrows_bancos > 0){
          db_fieldsmemory($result_bancos,0);
        }
      }

      if($numrows04 == 0 && $numrows_bancos == 0){
        if(isset($pc63_banco) && trim($pc63_banco) == '001'){
          $k13_descr = 'BANCO DO BRASIL S/A';
        }else if(isset($pc63_banco) && trim($pc63_banco) == '041'){
          $k13_descr = 'BANRISUL S/A';
        }else if(isset($pc63_banco) && trim($pc63_banco) == '104'){
          $k13_descr = 'CAIXA ECONÔMICA FEDERAL';
        }else if(isset($pc63_banco) && trim($pc63_banco) == '008'){
          $k13_descr = 'SANTANDER S/A';
        }else if(isset($pc63_banco) && trim($pc63_banco) == '237'){
          $k13_descr = 'BRADESCO S/A';
        }else{
          $k13_descr = '.';
        }
      }

      // Na frente do cheque
      $result_bancos2 = $cldb_bancos->sql_record($cldb_bancos->sql_query_file($codbco, "db90_descr as descr"));
      $numrows_bancos2 = $cldb_bancos->numrows;
      if($numrows_bancos2 > 0){
        db_fieldsmemory($result_bancos2,0);
      }

      if($numrows_bancos2 == 0){
        if(trim($codbco) == '001'){
          $descr = 'BANCO DO BRASIL S/A';
        }else if(trim($codbco) == '041'){
          $descr = 'BANRISUL S/A';
        }else if(trim($codbco) == '104'){
          $descr = 'CAIXA ECONÔMICA FEDERAL';
        }else if(trim($codbco) == '008'){
          $descr = 'SANTANDER S/A';
        }else if(trim($codbco) == '237'){
          $descr = 'BRADESCO S/A';
        }else{
          $descr = '.';
        }
      }

      // Rotina que define o nome do credor
      if(isset($credor) && $credor != ''){
        $nome = "$credor";
      }else if($nome_nominal == true){
        $nome = "$k13_descr";
      }else{
        $nome = "$nome_cre";
      }

      $nome = str_replace("\n", '', $nome);
      $nome = str_replace("\r", '', $nome);

      // Quando é clicado em '"Prever', limpa o que tinha no verso
      if(isset($prever)){
        $verso = '';
      }

	if ($verso == '') {
		if ($numrows04 > 0) {
			db_fieldsmemory($result04, 0);
			$pc63_agencia = str_replace("\n", '', $pc63_agencia);
			$ver = str_replace("\r", '', $pc63_agencia);
            $dad_verso .= '	   Agencia:'.$pc63_agencia." - ".$pc63_agencia_dig.'    Conta:'.$pc63_conta." - ".$pc63_conta_dig.' Banco:'.$k13_descr.' \n  ';
			$dad_verso .= '	   \n';
		}

        if(strlen($nomes) > 65){
          $nom = $nomes;
          $dad_verso .= '      ';

          while(strlen($nom) > 0){
            $dad_verso .= substr($nom, 0, 65).' \n';
            $nom = substr($nom, 65);
          }
        }else{
          $dad_verso .= '      '.$nomes;
        }

		$ver = str_replace("\n", ' \\n', $verso);
		$ver = str_replace("\r", '', $ver);

		$dad_verso .= "      ".$ver;
		$dad_verso .= '\n';
		$dad_verso .= '\n';
		$dad_verso .= "             Prefeito:$prefeito  $tesoureiro\n";
		$verso = $dad_verso;
		if (isset ($prever)) {
			$verso = str_replace('\n', "\n", $verso);
		} else {
			$verso = str_replace("\n", ' \\n', $verso);
			$verso = str_replace("\r", '', $verso);
		}
	} else {
		$verso = str_replace("\n", ' \\n', $verso);
		$verso = str_replace("\r", '', $verso);

		$dad_verso = $verso;
 	}
      }
    }
  }
}

if (isset ($atualizar) && isset($sqlerro) && $sqlerro == false){
	$sqlerro = false;
	db_inicio_transacao();
	//------------------------------------------------------------------------------------------
	//valores

	$arr = split("XX", $movs);
	$arr_movs = array ();
	for ($i = 0; $i < count($arr); $i ++) {
		$mov = $arr[$i];
		$re = $clempagemov->sql_record($clempagemov->sql_query_file($mov, "e81_valor"));
		db_fieldsmemory($re, 0);
		$arr_movs[$mov] = $e81_valor;
	}

	$val = trim(db_formatar(($tot_valor / $cheques), 'p', '', 2));
	$tot_val = '0';

	$vals = '';
	$sep = '';
	$arr_cheque = array ();
	for ($s = 0; $s < $cheques; $s ++) {
		//rotina que define os valores
		$tot_val += $val;
		if (($s +1) == $cheques) {
			if ($tot_valor > $tot_val) {
				$resto = $tot_valor - $tot_val;
				$val = $val + $resto;
			} else if ($tot_val > $tot_valor) {
					$resto = $tot_val - $tot_valor;
					$val = $val - $resto;
				}
		}
		$arr_cheque[$s] = $val;

  
		$vals .= $sep.$val;
		$sep = '#';
		$cheq = $arr_chequeseq[$s];
	}

	//rotina para incluir no empageconfche
	$val = $arr_cheque[0];
	$cheque = $arr_chequeseq[0];
	$seqcheque = 0;

	reset($arr_movs);
	$valmov = $arr_movs[key($arr_movs)];

	$continua = true;
	$cods = '';
	$sep = '';
	while ($continua == true) {
		$mov = key($arr_movs);
		if (trim($valmov) == trim($val)) {
			$cods .= "$sep$cheque-$mov-$val";
			$sep = '#';

			$y = next($arr_movs);
			if ($y == false) {
				$continua = false;
			} else {
				$seqcheque += 1;
				$val = $arr_cheque[$seqcheque];
				$cheque = $arr_chequeseq[$seqcheque];
				$valmov = $arr_movs[key($arr_movs)];
			}
			continue;
		}

		if ($valmov > $val) {
			$cods .= "$sep$cheque-$mov-$val";
			$sep = '#';

			$valmov = ($valmov - $val);
			$seqcheque += 1;
			$val = $arr_cheque[$seqcheque];
			$cheque = $arr_chequeseq[$seqcheque];
		}

		if ($valmov < $val) {
			$cods .= "$sep$cheque-$mov-$valmov";
			$sep = '#';
			$val -= $valmov;
			$y = next($arr_movs);
			if ($y == false) {
				$continua = false;
			} else {
				$valmov = $arr_movs[key($arr_movs)];
			}
			continue;
		}
	}

	$arr_cods = split("#", $cods);
	for ($i = 0; $i < count($arr_cods); $i ++) {
		$arr = split("-", $arr_cods[$i]);
		$cheq = $arr[0];
		$mov = $arr[1];
		$val = $arr[2];
		//rotina que inclui na empageconfche
		if ($sqlerro == false) {
			$clempageconfche->e91_codmov = $mov;
			$clempageconfche->e91_cheque = "$cheq";
			$clempageconfche->e91_valor  = "$val";
			$clempageconfche->e91_ativo  = "true";
			$clempageconfche->incluir(null);
			$erro_msg = $clempageconfche->erro_msg;
			if ($clempageconfche->erro_status == 0) {
				$sqlerro = true;
			}
		}
	}

	//rotina que inclui os movimentos
	if ($sqlerro == false && $movs != '') {
		$clempagegera->e87_descgera = "Arquivo  de cheque gerado";
		$clempagegera->e87_data = "$dtin_ano-$dtin_mes-$dtin_dia";
		$clempagegera->e87_hora = db_hora();
		$clempagegera->e87_dataproc = "$dtin_ano-$dtin_mes-$dtin_dia";
		$clempagegera->incluir(null);
		$erro_msg = $clempagegera->erro_msg;
		if ($clempagegera->erro_status == 0) {
			$sqlerro = true;
		} else {
			$gera = $clempagegera->e87_codgera;
		}

		if ($sqlerro == false) {
			$arr = split("XX", $movs);
			for ($i = 0; $i < count($arr); $i ++) {
				$mov = $arr[$i];
				//inclui na tabela empageconf
				if ($sqlerro == false) {
					$clempageconf->e86_codmov = $mov;
					$clempageconf->e86_data = "$dtin_ano-$dtin_mes-$dtin_dia";
					$clempageconf->e86_cheque = $cheque_seq;
					$clempageconf->e86_correto = "true";
					$clempageconf->incluir($mov);
					$erro_msg = $clempageconf->erro_msg;
					if ($clempageconf->erro_status == 0) {
						$sqlerro = true;
					}
				}

				if ($sqlerro == false) {
					$clempageconfgera->e90_codmov = $mov;
					$clempageconfgera->e90_codgera = $gera;
					$clempageconfgera->e90_correto = "true";
					$clempageconfgera->incluir($mov, $gera);
					$erro_msg = $clempageconfgera->erro_msg;
					if ($clempageconfgera->erro_status == 0) {
						$sqlerro = true;
					}
				}
			}
		}

		//--rotina que acrecenta um numero a mais no campo sequencia do empagetipo
		if ($sqlerro == false) {
			$clempagetipo->e83_codtipo = $e83_codtipo;
			$clempagetipo->e83_sequencia = $sequencia;
			$clempagetipo->alterar($e83_codtipo);
			$erro_msg = $clempagetipo->erro_msg;
			if ($clempagetipo->erro_status == 0) {
				$sqlerro = true;
            }else{
                $resultatipo = $clempagetipo->sql_record($clempagetipo->sql_query($e83_codtipo, "e83_codmod"));
                db_fieldsmemory($resultatipo, 0);
			}
		}
	}
	db_fim_transacao($sqlerro);
}

//quando entra pela primeira vez
if (empty ($e80_data_ano)) {
	$e80_data_ano = date("Y", db_getsession("DB_datausu"));
	$e80_data_mes = date("m", db_getsession("DB_datausu"));
	$e80_data_dia = date("d", db_getsession("DB_datausu"));
	$data = "$e80_data_ano-$e80_data_mes-$e80_data_dia";
}

if (isset ($data)) {
	$result01 = $clempage->sql_record($clempage->sql_query_file(null, 'e80_codage', '', "e80_data='$data' and e80_instit = " . db_getsession("DB_instit")));
	$numrows01 = $clempage->numrows;
}

// IP do local onde está instalada a impressora de cheques
$ip_imprime = db_getsession("DB_ip");
// $ip_imprime = "192.168.0.1";


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="375" align="left" valign="top" bgcolor="#CCCCCC">
   <?


$clrotulo = new rotulocampo;
$clrotulo->label("e80_data");

//sempre que ja existir agenda entra nesta opcao  
if (isset ($e80_codage) && empty ($pesquisar)) {
	include ("forms/db_frmempageconfslip.php");
}
?>    
    </td>
  </tr>
</table>
</body>
</html>
<script>
function js_cria(campo,valor){
       obj=document.createElement('input');
       obj.setAttribute('name',campo);
       obj.setAttribute('type','hidden');
       obj.setAttribute('value',valor);
       document.form1.appendChild(obj);
}	   
       function js_verso(ver){
       	 retorna = false;
       <? 

if (isset ($imprimirverso)) {
	//echo "alert('olha, imprimir verso ta setado');\n";
	echo "retorna = confirm('Emitir o verso do cheque?');\n";
}
?>
	   if(retorna == true){
	     obj=document.createElement('input');
	     obj.setAttribute('name','emiteverso');
	     obj.setAttribute('type','hidden');
	     obj.setAttribute('value',ver);
	     document.form1.appendChild(obj);
	     document.form1.submit();
	   }else{
	     parent.location.href='emp4_empage001.php?e80_codage=<?=$e80_codage?>&dtp_dia=<?=$dtin_dia?>&dtp_mes=<?=$dtin_mes?>&dtp_ano=<?=$dtin_ano?>';
	   }
      }	 
</script>

<?
// Rotina que alerta caso tenha ocorrido algum problema nas transações
if(isset($atualizar) && $sqlerro == true){
  db_msgbox($erro_msg);
}else if(isset($mensagem_mostra)){
  db_msgbox($mensagem_mostra);
}

// Rotina responsável de preparar os dados para impressão
if ((isset ($atualizar) && $sqlerro == false)) {

	$nome = str_replace("\n", '', "$nome");
	$nome = str_replace("\r", '', "$nome");

	$tot_valor = trim(db_formatar($tot_valor, 'p', '', 2));

	$datain = "$dtin_dia-$dtin_mes-".substr($dtin_ano, 2, 2);

	if ($nome == '') {
		db_msgbox("Campo Nome não foi informado");
		$nome = '.....';
	}else if ($valor == '') {
		db_msgbox("Campo Valor não foi informado");
		$valor = '.....';
	}else if ($codbco == '') {
		db_msgbox("Campo Nome não foi informado");
		$codbco = '.....';
	}else if ($data == '') {
		db_msgbox("Campo Data não foi informado");
		$data = '.....';
	}

	$dad_verso = str_replace("\n", '', "$dad_verso");
	$dad_verso = str_replace("\r", '', "$dad_verso");
	echo "<script>";
	echo "  document.form1.nome_imp.value   = '$nome';\n
		    document.form1.codbco_imp.value = '$codbco';\n
		    document.form1.data_imp.value   = '$datain';\n
		    document.form1.cheque_imp.value   = '$cheque_seq';\n
		    document.form1.verso_imp.value  = '$dad_verso';\n";
	echo "</script>";

	$emite_vals = $vals;
	$verso_imp = $dad_verso;
	$nome_imp = $nome;
	$codbco_imp = $codbco;
	$data_imp = $datain;
	$cheque_imp = $cheque_seq;
}

// Rotina que responsável de enviar os dados para impressão
if (isset ($emite_vals) && $emite_vals != '' && empty ($prever)) {

	$verso_imp = str_replace("\n", '\n', "$verso_imp");
	$verso_imp = str_replace("\r", '', $verso_imp);
	echo "<script>\n
		    document.form1.verso_imp.value  = '$verso_imp';\n";
	echo "</script>";

	$arr_vals = split("#", $emite_vals);
	$arr_cheque = split(",", $cheque_imp);

	$tot_vals = '';
	$tot_cheques = '';
	$sep = '';
    $sep1 = '';
	$sep2 = '';
	$cont = 0;
	for ($i = 0; $i < count($arr_vals); $i ++) {
		if ($i == 0) {
			$val = $arr_vals[$i];
			$cheque = $arr_cheque[$i] + 1;
		} else {
			$tot_vals .= $sep.$arr_vals[$i];
			$sep = '#';
			$tot_cheques .= $sep1.$arr_cheque[$i];
			$sep1 = ',';
			$cont ++;
		}
	}

	db_imprimecheque ($nome_imp, $codbco_imp, $val, $data_imp, $k11_tipoimpcheque,$ip_imprime, $k11_portaimpcheque,$municipio );

	if ($tot_vals != '') {

		echo "<script>";
		echo "  retorna = confirm('Emitir o cheque $cheque?');\n";
		echo "  if(retorna == true){\n";
		echo "    document.form1.cheque_imp.value   = '$tot_cheques';\n";
		echo "    obj=document.createElement('input');\n
				  obj.setAttribute('name','emite_vals');\n
				  obj.setAttribute('type','hidden');\n
				  obj.setAttribute('value','$tot_vals');\n
				  document.form1.appendChild(obj);\n
				  document.form1.submit();\n
			    }else{\n
		          document.form1.emite_vals.value   = '';\n
			      js_verso('$verso_imp');\n
			    }\n";
		echo "</script>";
	} else {
		//db_msgbox('pergunta se imprime verso?');
		echo "<script>";
		echo "  document.form1.emite_vals.value   = '';";
		echo "  js_verso('$verso_imp');";
		echo "</script>";
	}
}

// VERSO
if (isset ($emiteverso)) {

	$ver = str_replace("\n", ' ###', $emiteverso);
	$emiteverso = str_replace("\r", '', $ver);

	$arr_i = split("###", $emiteverso);
    if($k11_tipoimpcheque == 5){
      $fd = fsockopen($ip_imprime, $k11_portaimpcheque);
    //  $imprimir_ver = chr(27).chr(119).'1';
    //  $imprimir_ver.= chr(27).chr(80);
      for($i=0; $i<count($arr_i); $i++){
        $te = $arr_i[$i];
        if(trim($te) != ""){
          $imprimir_ver.= "";
          $imprimir_ver .= "       ".trim($te).chr(10).chr(13);
          $imprimir_ver .= chr(10).chr(13);
        }
      }
      //$imprimir_ver .= chr(27).chr(119).'0';
  
      fputs($fd, "$imprimir_ver");
      fclose($fd);
    }else{
	  for ($i = 0; $i < count($arr_i); $i ++) {
		$fd = fsockopen($ip_imprime, $k11_portaimpcheque);
		$te = $arr_i[$i];
		fputs($fd, "$te\n");
		fclose($fd);
	  }
    }

	if ($cheques > 1) {
		$ver = str_replace("\n", ' \\n', $emiteverso);
		$emiteverso = str_replace("\r", '', $ver);
		echo "<script>\n
			     retorna = confirm('Emitir novamente o verso do cheque?');\n
			     if(retorna == true){\n
			       obj=document.createElement('input');\n
			       obj.setAttribute('name','emiteverso');\n
			       obj.setAttribute('type','hidden');\n
			       obj.setAttribute('value','$emiteverso');\n
			       document.form1.appendChild(obj);\n
			       document.form1.submit();\n
			     }else{\n
				   parent.location.href='emp4_empage001.php?e80_codage=$e80_codage';\n
			     }\n   
			     </script>";
	} else {
	  echo "<script>parent.location.href='emp4_empage001.php?e80_codage=$e80_codage';</script>";
	}
}
?>
<script>
//script responsavel para selecionar a agenda...
function js_empage(){
  js_OpenJanelaIframe('top.corpo','db_iframe_empage','func_empage.php?funcao_js=parent.js_mostra|e80_codage|e80_data','Pesquisa',true);
}

function js_mostra(codage,data){
  arr = data.split('-');
  
  obj = document.form1;
  obj.e80_data_ano.value = arr[0];
  obj.e80_data_mes.value = arr[1];
  obj.e80_data_dia.value = arr[2];
 
  obj = document.createElement('input');
  obj.setAttribute('name','pri_codage');
  obj.setAttribute('type','hidden');
  obj.setAttribute('value',codage);
  document.form1.appendChild(obj);
  document.form1.pesquisar.click();
 
  db_iframe_empage.hide();  
}
if(parent.document.form1.dtp_dia.value != ''){
  document.form1.dtin_dia.value = parent.document.form1.dtp_dia.value; 
  document.form1.dtin_mes.value = parent.document.form1.dtp_mes.value; 
  document.form1.dtin_ano.value = parent.document.form1.dtp_ano.value; 
}
</script>