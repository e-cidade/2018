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
require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");

db_postmemory($_POST);

$db_opcao = 1;
$db_botao = true;

if(isset($gerar)){

      $sNomeTemporario = $_FILES["r56_dirarq"]["tmp_name"];

      // Seta o nome do arquivo destino do upload
      $sArquivoProcessado = "tmp/retbanriname.txt";
      $sArquivoGeracao    = "tmp/retbanri.txt";

      db_inicio_transacao();

      // Faz um upload do arquivo para o local especificado
      move_uploaded_file($sNomeTemporario,$sArquivoProcessado) or $erro_msg = "ERRO: Problemas com upload, contate o suporte.";

      // Abre o arquivo
      $fPonteiro = fopen($sArquivoProcessado,"r") or $erro_msg = "ERRO: Arquivo não abre.";

      $iLinhaCorrente      = 0;
      $iServidoresComPonto = 0;
      $erro                = 0;
      $lTransacao          = false;

      $head3 = "SERVIDORES EM CONFLITO NO PONTO";

      $oPdf = new PDF();
      $oPdf->Open();
      $oPdf->AliasNbPages();
      $iAltura  = 4;
      $oPdf->setfillcolor(235);
      $oPdf->addpage();
      $oPdf->setfont('arial','b',8);
      $oPdf->cell(20,$iAltura,'MATRÍCULA',1,0,"C",1);
      $oPdf->cell(80,$iAltura,'NOME',1,0,"C",1);
      $oPdf->cell(20,$iAltura,'RUBRICA',1,0,"C",1);
      $oPdf->cell(20,$iAltura,'QUANTIDADE',1,0,"C",1);
      $oPdf->cell(20,$iAltura,'VALOR',1,1,"C",1);

      while(!feof($fPonteiro)){

        $iPosicaoLinha = fgets($fPonteiro,4096);

        $iMatricula    = trim(substr($iPosicaoLinha,  0,  5)) + 0;
        $sRubrica      = trim(substr($iPosicaoLinha,  5,  4)) + 0;
        $iQuantidade   = trim(substr($iPosicaoLinha,  9,  6)) + 0;

        if($iQuantidade == 0){
          continue;
        }

    	  $iLinhaCorrente++;

        $sSqlPessoal  = "select *                                                   ";
        $sSqlPessoal .= "  from rhpessoal                                           ";
        $sSqlPessoal .= "       inner join cgm          on rh01_numcgm = z01_numcgm ";
        $sSqlPessoal .= "       inner join rhpessoalmov on rh02_anousu = " . $anousu;
        $sSqlPessoal .= "                              and rh02_mesusu = " . $mesusu;
        $sSqlPessoal .= "                              and rh02_regist = rh01_regist";
        $sSqlPessoal .= "                              and rh02_instit = " . db_getsession('DB_instit');
        $sSqlPessoal .= " where rh01_regist = " . $iMatricula;

        $rsPessoal               = db_query($sSqlPessoal);
        $iTotalRegistrosPessoal  = pg_numrows($rsPessoal);

        if($iTotalRegistrosPessoal > 0){

          db_fieldsmemory($rsPessoal,0);

          $sSqlBuscaRubricas  = "select *                                                ";
          $sSqlBuscaRubricas .= "  from pontofs                                          ";
          $sSqlBuscaRubricas .= "       inner join rhpessoal on rh01_regist = r10_regist ";
          $sSqlBuscaRubricas .= "       inner join cgm       on rh01_numcgm = z01_numcgm ";
          $sSqlBuscaRubricas .= " where r10_anousu = " . $anousu;
          $sSqlBuscaRubricas .= "   and r10_mesusu = " . $mesusu;
          $sSqlBuscaRubricas .= "   and r10_regist = " . $iMatricula;
          $sSqlBuscaRubricas .= "   and r10_rubric = lpad($sRubrica,4,'0')                ";

          $rsBuscaRubricas         = db_query($sSqlBuscaRubricas);
          $iTotalRegistrosRubricas = pg_numrows($rsBuscaRubricas);

          if($iTotalRegistrosRubricas > 0){

             db_fieldsmemory($rsBuscaRubricas,0);

             $oPdf->cell(20,$iAltura,$rh01_regist,0,0,"C",0);
             $oPdf->cell(80,$iAltura,$z01_nome,0,0,"L",0);
             $oPdf->cell(20,$iAltura,$r10_rubric,0,0,"C",0);
             $oPdf->cell(20,$iAltura,db_formatar($r10_quant,'f'),0,0,"R",0);
             $oPdf->cell(20,$iAltura,db_formatar($r10_valor,'f'),0,1,"R",0);

             $iServidoresComPonto ++;
             $erro     = 1;
             $erro_msg = "ERRO: Existem servidores com rubricas a serem importadas no ponto.Verifique o relatório!";
          }else{

             $sSqlInsertPontofs  = "insert into pontofs (                          ";
             $sSqlInsertPontofs .= "                     r10_anousu,               ";
             $sSqlInsertPontofs .= "                     r10_mesusu,               ";
             $sSqlInsertPontofs .= "                     r10_regist,               ";
             $sSqlInsertPontofs .= "                     r10_rubric,               ";
             $sSqlInsertPontofs .= "                     r10_valor,                ";
             $sSqlInsertPontofs .= "                     r10_quant,                ";
             $sSqlInsertPontofs .= "                     r10_lotac,                ";
             $sSqlInsertPontofs .= "                     r10_datlim,               ";
             $sSqlInsertPontofs .= "                     r10_instit                ";
             $sSqlInsertPontofs .= "             )values(                          ";
             $sSqlInsertPontofs .= "                     $anousu,                  ";
             $sSqlInsertPontofs .= "                     $mesusu,                  ";
             $sSqlInsertPontofs .= "                     $iMatricula,              ";
             $sSqlInsertPontofs .= "                     lpad($sRubrica, 4, '0'),  ";
             $sSqlInsertPontofs .= "                     0,                        ";
             $sSqlInsertPontofs .= "                     ".($iQuantidade / 100).", ";
             $sSqlInsertPontofs .= "                     '$rh02_lota',             ";
             $sSqlInsertPontofs .= "                     '',                       ";
             $sSqlInsertPontofs .= "                     $rh02_instit           ); ";
             $rsInsertPontofs    = db_query($sSqlInsertPontofs);

             if(!$rsInsertPontofs){

               $erro_msg   = "ERRO no SQL : $sSqlInsertPontofs. Contate Suporte!";
               $lTransacao = true;
             }
          }

        }

      }

      /**
       * Erro ao inserir registros na pontofs
       */
      if(!$lTransacao){
        db_fim_transacao($lTransacao);
      }

      if($iServidoresComPonto > 0){

        db_fim_transacao($lTransacao);
        $oPdf->setfont('arial','b',8);
        $oPdf->cell(160,$iAltura,'TOTAL DE REGISTROS :  '.$iServidoresComPonto,"T",0,"C",0);
        $oPdf->Output();
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
        include("forms/db_frmsapimportarelogio.php");
        ?>
    </center>
        </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
js_tabulacaoforms("form1","anousu",true,1,"anousu",true);
</script>
</html>
<?
if(isset($gerar) || isset($confirma)){
  if($erro == 1){
    db_msgbox($erro_msg);
  }else{
    $iLinhaCorrente;
    db_msgbox($iLinhaCorrente." registros incluídos.");
  }
}
?>