<?php
/**
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
require_once(modification("classes/db_movrel_classe.php"));
require_once(modification("classes/db_convenio_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_rharqbanco_classe.php"));
require_once(modification("libs/db_utils.php"));

db_postmemory($_POST);

$clmovrel     = new cl_movrel;
$clconvenio   = new cl_convenio;
$clrharqbanco = new cl_rharqbanco;
$clrharqbanco->rotulo->label();

$db_opcao = 1;
$db_botao = true;

if(isset($gerar)) {

  function testavalor($valor = null) {

    if(is_numeric($valor) == true) {
      return $valor;
    } else {
      return 0;
    }
  }

  $sqlerro      = false;
  $dbcontator   = 0;
  $dbrejeitados = 0;
  $r54_codrel   = trim($r54_codrel);

  $sWhereMovRel  = "     r54_instit = ".db_getsession('DB_instit');
  $sWhereMovRel .= " and r54_anomes = '".$r54_anousu . str_pad($r54_mesusu, 2, '0', STR_PAD_LEFT) . "'";
  $sWhereMovRel .= " and trim(r54_codrel) = '{$r54_codrel}' limit 1 ";

  $sSqlMovRel   = $clmovrel->sql_query_file(null, "r54_codeve", "", $sWhereMovRel);
  $result_dados = $clmovrel->sql_record($sSqlMovRel);

  if($clmovrel->numrows > 0) {

    db_fieldsmemory($result_dados, 0);
    $result_dados = $clconvenio->sql_record($clconvenio->sql_query_relac($r54_codeve));
  }

  $layouttxt = $db50_codigo;

  if($clconvenio->numrows > 0) {

    db_fieldsmemory($result_dados, 0);

    if(trim($r55_rubr01) != "") {

      include(modification("dbforms/db_layouttxt.php"));

      // Nome do novo arquivo
      $nomearq = $_FILES["r56_dirarq"]["name"];
         
      // Nome do arquivo temporário gerado no /tmp
      $nometmp = $_FILES["r56_dirarq"]["tmp_name"];
         
      // Seta o nome do arquivo destino do upload
      $arquivoprocessa = "/tmp/retbanriname.txt";
      $arquivogeracao = "/tmp/retbanri.txt";
         
      // Faz um upload do arquivo para o local especificado
      move_uploaded_file($nometmp,$arquivoprocessa) or $erro_msg = "ERRO: Problemas com upload, contate o suporte.";

      // Abre o arquivo
      $ponteiro = fopen("$arquivoprocessa","r") or $erro_msg = "ERRO: Arquivo não abre.";

      $cldb_layouttxt = new db_layouttxt($layouttxt, $arquivogeracao);
      $linhacorrente  = 0;
      $somavalmovrel  = 0;
      $matricula_     = 0;

      while(!feof($ponteiro)) {

        $poslinha = fgets($ponteiro, 4096);

				if($poslinha == "" || substr($poslinha, 4, 2) == "99") {
				  continue;
				}

    	  $linhacorrente ++;

        if($linhacorrente == 1) {

          if($layouttxt == 5) {

            $anoarquivo = db_formatar(trim(substr($poslinha, 22, 4)), "s", "0", 4, "e");
            $mesarquivo = db_formatar(trim(substr($poslinha, 20, 2)), "s", "0", 2, "e");

            $strCanal   = substr($poslinha, 17, 3);
            $strPeriodo = $anoarquivo . "-" . $mesarquivo . "-01";
          }

          if($layouttxt == 27) {

            $anoarquivo = db_formatar(trim(substr($poslinha, 64, 4)), "s", "0", 4, "e");
            $mesarquivo = db_formatar(trim(substr($poslinha, 68, 2)), "s", "0", 2, "e");

            $anomes         = $anoarquivo . "-" . $mesarquivo . "-01";
            $nomeconvenio   = trim(substr($poslinha,  14, 50));
            $codigoconvenio = trim(substr($poslinha,   6,  8));

            if ( $teste == "t") {
              $identificadorheader = "BCER01";
            }
          }

          $r54_anousu = db_formatar($r54_anousu, "s", "0", 4, "e");
          $r54_mesusu = db_formatar($r54_mesusu, "s", "0", 2, "e");

          if(($anoarquivo.$mesarquivo) != ($r54_anousu.$r54_mesusu)) {

            $erro_msg = "Período não confere com arquivo de envio.";
            $sqlerro  = true;

            break;
          }

          $sWhereMovRel  = "     r54_instit = ".db_getsession('DB_instit');
          $sWhereMovRel .= " and r54_anomes = '{$r54_anousu}{$r54_mesusu}'";
          $sWhereMovRel .= " and trim(r54_codrel) = '{$r54_codrel}'";
          $sWhereMovRel .= " and r54_regist <> 0 limit 1 ";

          $sSqlMovRel         = $clmovrel->sql_query_gerfsal(null," * ","", $sWhereMovRel ,$r54_anousu, $r54_mesusu, $r55_rubr01);
          $result_testa_folha = $clmovrel->sql_record($sSqlMovRel);

	        if($clmovrel->numrows == 0) {

	          $erro_msg = "Sem registro lançado na folha.";
	          $sqlerro  = true;

	          break;
	        }

          db_setaPropriedadesLayoutTxt($cldb_layouttxt,1);
	      } else {

          if($layouttxt == 5) {

            $AO               = trim(substr($poslinha,  0,  6)) + 0;
            $cpffuncionario   = trim(substr($poslinha,  6, 11)) + 0;
            $z01_nome         = trim(substr($poslinha, 17, 35));
            $matricula        = trim(substr($poslinha, 52, 14)) + 0; // O 15° CARACTER É O DIGITO DA MATRICULA
            $codigo           = trim(substr($poslinha, 67,  5)) + 0;
            $datatermi        = trim(substr($poslinha, 72,  6));
            $valorinc         = trim(substr($poslinha, 78, 11)) + 0;
            $valorexc         = trim(substr($poslinha, 89, 11)) + 0;
            $filler           = trim(substr($poslinha,100, 16));
            $matriculaselect  = $matricula;
            $matricula       .= db_CalculaDV("$matricula");

            $valorinc    /= 100;
            $valorexc    /= 100;
            $testavalinc  = number_format($valorinc, 2);
            $anomesterm   = substr($datatermi, 2, 4) . "-" . substr($datatermi, 0, 2) . "-01";

          }

          if($layouttxt == 27) {
          	
            $oa                   = trim(substr($poslinha,  6, 6))  + 0;
            $matriculafuncionario = trim(substr($poslinha, 12, 15)) + 0; // O 15° CARACTER É O DIGITO DA MATRICULA
            $cpffuncionario       = trim(substr($poslinha, 27, 11)) + 0;
            $nomefuncionario      = trim(substr($poslinha, 38, 35));
            $codigocanal          = trim(substr($poslinha, 73,  5)) + 0;
            $nrocontrato          = trim(substr($poslinha, 78, 20));
            $prestacao            = trim(substr($poslinha, 98, 07));
            $valorconsignar       = trim(substr($poslinha,105, 15));
            $valorconsignado      = 0;
            $filler               = trim(substr($poslinha,137, 64));
            $matriculaselect      = $matriculafuncionario;
            $testavalinc          = $valorconsignar / 100;
          }

          if($matricula_ != $matriculaselect) {

            $matricula_ = $matriculaselect;
            $total      = 0;

            $sWhereMovRel  = "     r54_instit       = ".db_getsession('DB_instit');
            $sWhereMovRel .= " and r54_anomes       = '{$r54_anousu}{$r54_mesusu}'";
            $sWhereMovRel .= " and trim(r54_codrel) = '{$r54_codrel}'";
            $sWhereMovRel .= " and r54_regist       = {$matriculaselect}";

            $sSqlMovRel = $clmovrel->sql_query_gerfsal(
              null,
              " * ",
              "",
              $sWhereMovRel,
              $r54_anousu,
              $r54_mesusu,
              $r55_rubr01
            );
            
            $result_testa_folha = $clmovrel->sql_record($sSqlMovRel);
            
            for($i = 0; $i < $clmovrel->numrows; $i++) {

              db_fieldsmemory($result_testa_folha, $i);

              if($layouttxt == 5) {

                $total   += $r54_quant1;
                $valorinc = $r14_valor;
              }

              if($layouttxt == 27) {
                $total = $r14_valor;
              }
            }
          }

          if($layouttxt == 5) {

            $testavaltot = number_format($total, 2);
            $valorinc2   = 0;

            if($testavalinc != $testavaltot) {

              $total     = 0;
              $valorinc2 = $valorinc;

              $dbrejeitados++;
            } else {
              $dbcontator++;
	          }

            $somavalmovrel += $total;
          }

          if($layouttxt == 27) {

            $testavaltot      = $total;
            $motivodarejeicao = "";

            /**
             * Quando não houve valor descontado, verifica a situação da matrícula
             */
            if (empty($total)) {

              $motivodarejeicao      = "H3";
              $sSqlVerificaRescisao  = " select rh05_recis,  ";
          	  $sSqlVerificaRescisao .= "        rh05_causa   ";
          	  $sSqlVerificaRescisao .= "   from rhpessoalmov ";
          	  $sSqlVerificaRescisao .= "        inner join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes "; 
          	  $sSqlVerificaRescisao .= "  where rh02_anousu = {$anoarquivo} ";
          	  $sSqlVerificaRescisao .= "    and rh02_mesusu = {$mesarquivo} ";
          	  $sSqlVerificaRescisao .= "    and rh02_regist = {$matriculafuncionario} ";
          	  $rsRescisao            = db_query($sSqlVerificaRescisao);
          	  
          	  if ( pg_num_rows($rsRescisao) > 0 ) {
          	  	
          	  	$oRescisao = db_utils::fieldsMemory($rsRescisao,0);
          	  	
          	  	if ($oRescisao->rh05_causa >= 60 && $oRescisao->rh05_causa <= 64) {
          	  	  $motivodarejeicao = "BI";
          	  	}

          	  	if ($oRescisao->rh05_causa < 60 || $oRescisao->rh05_causa > 64) {
          	  	  $motivodarejeicao = "H8";
          	  	}
          	  } else {

          	  	$sSqlAfastado  = " select r45_dtreto ";
          	  	$sSqlAfastado .= "   from afasta     ";
          	    $sSqlAfastado .= "  where r45_anousu = {$anoarquivo} ";
          	    $sSqlAfastado .= "    and r45_mesusu = {$mesarquivo} ";
          	    $sSqlAfastado .= "    and r45_regist = {$matriculafuncionario} ";
          	    $sSqlAfastado .= "    and ( r45_dtreto >= '$r54_anousu-{$r54_mesusu}-01' or r45_dtreto is null )";
          	    $rsRescisao    = db_query($sSqlAfastado);

          	    if (pg_num_rows($rsRescisao) > 0) {
          	      $motivodarejeicao = "H9";
          	    }
          	  }
          	   
          	  $sSqlVerificaCadastro = "select * from rhpessoal where rh01_regist = {$matriculafuncionario}";
          	  $rsVerificaCadastro   = db_query($sSqlVerificaCadastro);

          	  if(pg_num_rows($rsVerificaCadastro) == 0) {
          	    $motivodarejeicao = "HM";
          	  }
            }

            if($total > 0) {

              if($testavaltot >= $testavalinc ) {

                $valorconsignado  = $testavalinc;
                $total           -= $testavalinc;
              } else {
                $valorconsignado = $total;
	            }

              $somavalmovrel += $valorconsignado;
            }
          }

          db_setaPropriedadesLayoutTxt($cldb_layouttxt, 3);
        }
      }

      if($layouttxt == 27) {

        $valortotal   = $somavalmovrel;
 		    $linhacorrente++;
        $qtderegistro = $linhacorrente;
        db_setaPropriedadesLayoutTxt($cldb_layouttxt,5);
      }
    } else {
      $erro_msg = "Rubrica não informada. Verifique o relacionamento.";
    }
  } else {

    $erro_msg = "Convênio não encontrado ou sem relacionamento.";
    $sqlerro = true;
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
</head>
<body class="body-default" onLoad="a=1" >
  <div class="container">
    <?php
    include(modification("forms/db_frmaleretbanri.php"));
    db_menu();
    ?>
  </div>
</body>
<script>
js_tabulacaoforms("form1","r54_anousu",true,1,"r54_anousu",true);
</script>
</html>
<?php
if(isset($gerar) || isset($confirma)) {

  if($sqlerro == true) {
    db_msgbox($erro_msg);
  } else {

    $linhacorrente;
    db_msgbox($linhacorrente." registros incluídos.");

    echo "
          <script>
            (window.CurrentWindow || parent.CurrentWindow).bstatus.document.getElementById('st').innerHTML = '$texto';
            js_arquivo_abrir('$arquivogeracao');
          </script>
         ";
  };
}