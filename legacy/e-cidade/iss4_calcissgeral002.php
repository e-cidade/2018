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
require_once(modification("classes/db_cissqn_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_isscadlogcalc_classe.php"));
require_once(modification("classes/db_isscalclog_classe.php"));

require_once(modification("classes/db_isscalcloginscr_classe.php"));

require_once(modification("classes/db_recibounica_classe.php"));

$oDaoReciboUnica = new cl_recibounica();

db_postmemory($HTTP_POST_VARS);
$clcissqn          = new cl_cissqn;
$clisscadlocalc    = new cl_isscadlogcalc;
$clisscalclog      = new cl_isscalclog;
$clisscalcloginscr = new cl_isscalcloginscr;

?>
<html>
	<head>
		<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="Expires" CONTENT="0">
		<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
		<link href="estilos.css" rel="stylesheet" type="text/css">
	</head>
	<body class="body-default">
    <div class="container">
      <form name="form1" action="" method="post" >
        <table class="form-container">
          <tr>
            <td><?db_criatermometro('termometro','Concluido...','blue',1);?></td>
          </tr>
        </table>
      </form>
    </div>
		<?
		db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
		?>
	</body>
</html>
<?

flush();
if(isset($calcular)){

  $sDataCalculo = date("Y-m-d", db_getsession("DB_datausu"));
  $sDataCalculo = "'{$sDataCalculo}'::date";

	$sql1 = " select distinct                                                                          \n";
	$sql1.= "        q07_inscr                                                                         \n";
	$sql1.= "   from tabativ                                                                           \n";
	$sql1.= "        left  join tabativtipcalc on q11_inscr   = q07_inscr                              \n";
	$sql1.= "                                 and q11_seq     = q07_seq                                \n";
	$sql1.= "        inner join ativtipo       on q07_ativ    = q80_ativ                               \n";
	$sql1.= "        inner join tipcalc        on q80_tipcal  = q81_codigo                             \n";
	$sql1.= "        inner join ativid         on q07_ativ    = q03_ativ                               \n";
	$sql1.= "        inner join cadcalc        on q81_cadcalc = q85_codigo                             \n";
	$sql1.= "        inner join issbase        on q02_inscr   = q07_inscr                              \n";
	$sql1.= "  where q02_dtbaix is null                                                                \n";
	$sql1.= "    and q81_cadcalc = $tipo                                                               \n";
	$sql1.= "    and q07_datain <= {$sDataCalculo}                                                     \n";
	$sql1.= "    and (q07_datafi is null or q07_datafi >= {$sDataCalculo})                             \n";
	$sql1.= "    and (q07_databx is null or q07_databx >= {$sDataCalculo})                             \n";
  $sql1.= "    and not EXISTS ( select 1                                                             \n";
  $sql1.= "                       from issbasecaracteristica                                         \n";
  $sql1.= "                         where q138_caracteristica in(25, 27)                             \n";
  $sql1.= "                           and q138_inscr = q07_inscr)                                    \n";

	/**
   * Validação para ver se a empresa esta paralisada
	 */
	$sql1.= "  and not exists( select 1                                                                \n";
	$sql1.= "                    from issbaseparalisacao                                               \n";
  $sql1.= "                   where q140_issbase = q07_inscr                                         \n";
  $sql1.= "                     and (   {$sDataCalculo} >= q140_datainicio and q140_datafim is null  \n";
  $sql1.= "                          or {$sDataCalculo} between q140_datainicio and q140_datafim ) ) \n";

	$result1 = db_query($sql1) or die($sql1);
	$numrows = pg_numrows($result1);
	$sqlerro = false;
	$cont    = 0;

	db_inicio_transacao();

	$clisscalclog->q47_anousu     = $anousu;
  $clisscalclog->q47_data       = date("Y-m-d",db_getsession("DB_datausu"));
  $clisscalclog->q47_hora       = db_hora();
  $clisscalclog->q47_usuario    = db_getsession("DB_id_usuario");
  $clisscalclog->q47_parcial    = '0';
  $clisscalclog->q47_tipo       = $tipo;
  $clisscalclog->q47_quantaproc = $numrows;
  $clisscalclog->incluir(null);

  if ($clisscalclog->erro_status==0){
  	$sqlerro=true;
  }

  $codigolog = $clisscalclog->q47_codigo;
  db_fim_transacao($sqlerro);

	if ($sqlerro == false) {

		for ($x = 0;$x < $numrows ;$x++) {
			db_fieldsmemory($result1,$x);
			$cont++;
			db_atutermometro($x,$numrows,'termometro');
			db_inicio_transacao();
			$data=date('Y-m-d');
			$ano=date('Y');
			$instit=1;

      $sql_seq_ativ = " select distinct                                                                             \n";
      $sql_seq_ativ.= "        q07_seq                                                                              \n";
      $sql_seq_ativ.= "   from tabativ                                                                              \n";
      $sql_seq_ativ.= "        left  join tabativtipcalc on q11_inscr   = q07_inscr and q11_seq = q07_seq           \n";
      $sql_seq_ativ.= "        inner join ativtipo       on q07_ativ    = q80_ativ                                  \n";
      $sql_seq_ativ.= "        inner join tipcalc        on q80_tipcal  = q81_codigo                                \n";
      $sql_seq_ativ.= "        inner join ativid         on q07_ativ    = q03_ativ                                  \n";
      $sql_seq_ativ.= "        inner join cadcalc        on q81_cadcalc = q85_codigo                                \n";
      $sql_seq_ativ.= "  where q07_inscr = $q07_inscr                                                               \n";
      $sql_seq_ativ.= "    and q07_datain <= {$sDataCalculo}                                                        \n";
      $sql_seq_ativ.= "    and (q07_datafi is null or                                                               \n";
      $sql_seq_ativ.= "         q07_datafi >= {$sDataCalculo})                                                      \n";
      $sql_seq_ativ.= "    and (q07_databx is null or                                                               \n";
      $sql_seq_ativ.= "         q07_databx >= {$sDataCalculo})                                                      \n";

			$result_seq_ativ = db_query($sql_seq_ativ) or die($sql_seq_ativ);

      if (pg_numrows($result_seq_ativ) > 0) {

        $seqs = "";

        for ($seq=0; $seq < pg_numrows($result_seq_ativ); $seq++) {
          db_fieldsmemory($result_seq_ativ,$seq);
          $seqs .=  ($seqs != ""?",":"") . $q07_seq;
        }
        /**
         * Adicionado tipo "1" para PL calcular apenas ISSQN
         */
        $sql3    = "select fc_issqn($q07_inscr,'".$data."',".$anousu.",null,'true','true',".$instit.",'$seqs',1) as retorno";
        $result3 = db_query($sql3) or die($sql3);

        if ($result3 != false) {

          db_fieldsmemory($result3,0);

          $clisscalcloginscr->q48_inscr      = $q07_inscr;
          $clisscalcloginscr->q48_isscalclog = $codigolog;
          $clisscalcloginscr->q48_isscadlog  = substr($retorno,0,2);
          $clisscalcloginscr->q48_obs        = substr($retorno,2,strlen($retorno));
          $clisscalcloginscr->incluir(null);

          if ($clisscalcloginscr->erro_status == 0){
            $sqlerro = true;
            db_msgbox($clisscalcloginscr->erro_msg);
            break;
          }

          if ( substr($retorno,0,2) == '01' ) {

            if ( isset($data1)&&$data1 != "--" || isset($data2) && $data2 != "--" || isset($data3) && $data3 != "--" ) {

              $result_calc  = db_query("select distinct q01_numpre from isscalc where q01_inscr = $q07_inscr and q01_anousu = $anousu");
              $numrows_calc = pg_numrows($result_calc);
              $hoje = date('Y-m-d',db_getsession("DB_datausu"));
                /*
                 * alteracao para incluir no cabeçalho criado para recibo unica
                 * recibounicageracao
                 */
                require_once(modification("classes/db_recibounicageracao_classe.php"));
                $oDaoReciboUnicaGeracao = new cl_recibounicageracao();

                $oDaoReciboUnicaGeracao->ar40_db_usuarios        = db_getsession("DB_id_usuario");
                $oDaoReciboUnicaGeracao->ar40_dtoperacao         = "$hoje";
                $oDaoReciboUnicaGeracao->ar40_dtvencimento       = "$data1";
                $oDaoReciboUnicaGeracao->ar40_percentualdesconto = $perc1;
                $oDaoReciboUnicaGeracao->ar40_tipogeracao        = "G";
                $oDaoReciboUnicaGeracao->ar40_ativo              = 'true';
                $oDaoReciboUnicaGeracao->ar40_observacao         = 'Inclusao pelo Cálculo Geral ISSQN  (iss4_calcissgeral002.php)';
                $oDaoReciboUnicaGeracao->incluir(null);
                if($oDaoReciboUnicaGeracao->erro_status == 0){

                  $descricao_erro = $oDaoReciboUnicaGeracao->erro_msg;
                  $sqlerro = true;
                }

              for ($w=0; $w < $numrows_calc; $w++) {

                db_fieldsmemory($result_calc,$w);


                $p1   = $perc1 + 0;

                /*
                 * Excluímos a cota unica anterior.
                 */
                $sSqlCotaUnica = "delete from recibounica where k00_numpre = {$q01_numpre} and k00_tipoger = 'G'";
                $rsCotaUnica = db_query($sSqlCotaUnica);

                if (!$rsCotaUnica) {
                  db_msgbox("Erro excluindo a Cota Unica da Inscrição: {$q07_inscr} Numpre: {$q01_numpre}");
                  $sqlerro = true;
                  break;
                }

                if (isset($data1) && $data1 != "--" ) {
                  //db_query("insert into recibounica values($q01_numpre,'$data1','$hoje','$p1', 'G', $oDaoReciboUnicaGeracao->ar40_sequencial)");

	                $oDaoReciboUnica->k00_numpre             = $q01_numpre;
	                $oDaoReciboUnica->k00_dtvenc             = $data1;
	                $oDaoReciboUnica->k00_dtoper             = $hoje;
	                $oDaoReciboUnica->k00_percdes            = $p1;
	                $oDaoReciboUnica->k00_tipoger            = "G";
	                $oDaoReciboUnica->k00_recibounicageracao = $oDaoReciboUnicaGeracao->ar40_sequencial;
	                $oDaoReciboUnica->incluir(null);
	                if($oDaoReciboUnica->erro_status == 0){

	                	db_msgbox("ERRO_1 :" .$oDaoReciboUnica->erro_msg);
	                	$sqlerro = true;
	                }


                }
                if (isset($data2)&&$data2!="--"){

                	//db_query("insert into recibounica values($q01_numpre,'$data2','$hoje',$perc2+0, 'G', $oDaoReciboUnicaGeracao->ar40_sequencial)");
                  $oDaoReciboUnica->k00_numpre             = $q01_numpre;
                  $oDaoReciboUnica->k00_dtvenc             = $data2;
                  $oDaoReciboUnica->k00_dtoper             = $hoje;
                  $oDaoReciboUnica->k00_percdes            = $perc2+0;
                  $oDaoReciboUnica->k00_tipoger            = "G";
                  $oDaoReciboUnica->k00_recibounicageracao = $oDaoReciboUnicaGeracao->ar40_sequencial;
                  $oDaoReciboUnica->incluir(null);
                  if($oDaoReciboUnica->erro_status == 0){

                    db_msgbox("ERRO_2 :" .$oDaoReciboUnica->erro_msg);
                    $sqlerro = true;
                  }
                }
                if (isset($data3)&&$data3!="--"){

                	//db_query("insert into recibounica values($q01_numpre,'$data3','$hoje',$perc3+0, 'G', $oDaoReciboUnicaGeracao->ar40_sequencial)");
                  $oDaoReciboUnica->k00_numpre             = $q01_numpre;
                  $oDaoReciboUnica->k00_dtvenc             = $data3;
                  $oDaoReciboUnica->k00_dtoper             = $hoje;
                  $oDaoReciboUnica->k00_percdes            = $perc3+0;
                  $oDaoReciboUnica->k00_tipoger            = "G";
                  $oDaoReciboUnica->k00_recibounicageracao = $oDaoReciboUnicaGeracao->ar40_sequencial;
                  $oDaoReciboUnica->incluir(null);
                  if($oDaoReciboUnica->erro_status == 0){

                    db_msgbox("ERRO_3 :" .$oDaoReciboUnica->erro_msg);
                    $sqlerro = true;
                  }
                }
              }
            }
          }
        }
      }

			db_fim_transacao($sqlerro);
		}
	}
	db_msgbox("Operação Finalizada!!");
	echo "<script>location.href='iss4_calcissgeral001.php';</script>";
}
?>