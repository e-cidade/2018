<?php
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

parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);

$oPost                        = (object) $_POST;
$clinssirf                    = new cl_inssirf;
$clrhrubricas                 = new cl_rhrubricas;
$oDaoRegimePrevidenciaInssirf = new cl_regimeprevidenciainssirf;
$oDaoAtoLegalPrevidencia      = new cl_atolegalprevidencia();

function manutencaoAtoLegalInssIrf($oPost, $lExcluir = false) {

  $lSucesso = true;

  $oDaoAtoLegalInssIrf = new cl_atolegalprevidenciainssirf();

  if(!empty($oPost->r33_codigo)) {

    $sWhereVinculoExclusao  = "     rh180_inssirf = {$oPost->r33_codigo}";
    $sWhereVinculoExclusao .= " AND rh180_instituicao = " . db_getsession("DB_instit");

    $oDaoAtoLegalInssIrf->excluir(null, $sWhereVinculoExclusao);

    if($oDaoAtoLegalInssIrf->erro_status == "0") {
      $lSucesso = false;
    }
  }

  if(!empty($oPost->rh180_atolegal) && !$lExcluir) {

    if($lSucesso) {

      $oDataPublicacao    = new DBDate($oPost->rh180_datapublicacao);
      $oDataInicioVigenca = new DBDate($oPost->rh180_datainiciovigencia);

      $oDaoAtoLegalInssIrf->rh180_instituicao        = db_getsession("DB_instit");
      $oDaoAtoLegalInssIrf->rh180_inssirf            = $oPost->r33_codigo;
      $oDaoAtoLegalInssIrf->rh180_atolegal           = $oPost->rh180_atolegal;
      $oDaoAtoLegalInssIrf->rh180_numero             = $oPost->rh180_numero;
      $oDaoAtoLegalInssIrf->rh180_ano                = $oPost->rh180_ano;
      $oDaoAtoLegalInssIrf->rh180_datapublicacao     = $oDataPublicacao->getDate();
      $oDaoAtoLegalInssIrf->rh180_datainiciovigencia = $oDataInicioVigenca->getDate();
      $oDaoAtoLegalInssIrf->incluir(null);

      if($oDaoAtoLegalInssIrf->erro_status == "0") {
        $lSucesso = false;
      }
    }
  }

  return $lSucesso;
}

/**
 * --------------------------------------------
 * INCLUIR
 * --------------------------------------------
 */
if(isset($incluir)) {

  if (db_mesfolha() == "") {
    $erro_msg = "Não encontrados parâmetros configurados para esta instituição. [cfpess] ";
  } else {

    db_inicio_transacao();
    $sqlerro = false;
    $codigo  = (int)$codtab;

    if(!isset($r33_basfer) || (isset($r33_basfer) && trim($r33_basfer) != "")) {

      $r33_basfer = "";
      if($codigo > 2){
        $r33_basfer = "B002";
      }
    }

    if(!isset($r33_basfet) || (isset($r33_basfet) && trim($r33_basfet) != "")) {

      $r33_basfet = "";
      if($codigo > 2){
        $r33_basfet = "B977";
      }
    }

    if($codigo > 2) {
      $r33_deduzi = 0;
    } else {

      $r33_tipo = "";
      $r33_nome = "";
    }

    $clinssirf->r33_anousu = db_anofolha();
    $clinssirf->r33_mesusu = db_mesfolha();
    $clinssirf->r33_codtab = $codtab;
    $clinssirf->r33_inic   = $r33_inic;
    $clinssirf->r33_fim    = $r33_fim;
    $clinssirf->r33_perc   = $r33_perc;
    $clinssirf->r33_deduzi = $r33_deduzi;
    $clinssirf->r33_basfer = $r33_basfer;
    $clinssirf->r33_basfet = $r33_basfet;
    $clinssirf->r33_instit = db_getsession("DB_instit");
    $clinssirf->r33_codele = $r33_codele;
    $clinssirf->incluir(null,db_getsession("DB_instit"));

    $erro_msg = $clinssirf->erro_msg;
    if($clinssirf->erro_status == "0") {
      $sqlerro = true;
    }

    $oPost->r33_codigo = $clinssirf->r33_codigo;

    if(!manutencaoAtoLegalInssIrf($oPost)) {

      $sqlerro  = true;
      $erro_msg = "Erro ao salvar as informações do Ato Legal. Verifique se todos os campos foram preenchidos.";
    }

    /**
     * Na inserção, insere o registro de vinculacao
     */
    if (!empty($rh129_regimeprevidencia)) {

      $oDaoRegimePrevidenciaInssirf->rh129_codigo            = $clinssirf->r33_codigo;
      $oDaoRegimePrevidenciaInssirf->rh129_instit            = $clinssirf->r33_instit;
      $oDaoRegimePrevidenciaInssirf->rh129_regimeprevidencia = $rh129_regimeprevidencia;
      $oDaoRegimePrevidenciaInssirf->incluir(null);

      $rh129_regimeprevidencia = '';
      $rh127_descricao         = '';
    }

    if($sqlerro == false) {

      if($codigo > 2) {

        $clinssirf->r33_tipo   = $r33_tipo;
        $clinssirf->r33_rubmat = $r33_rubmat;
        $clinssirf->r33_ppatro = $r33_ppatro;
        $clinssirf->r33_rubsau = $r33_rubsau;
        $clinssirf->r33_rubaci = $r33_rubaci;
        $clinssirf->r33_tinati = $r33_tinati;

        $completa = 0;
        $ini      = (3 * $codigo) - 8;
        $fim      = $ini + 2;

        for($i = $ini; $i <= $fim; $i++) {

          $completa ++;
          $descricao = "% ".$r33_nome;

          if($completa == 1){
            $descricao.= " S/ SALÁRIO";
          }else if($completa == 2){
            $descricao.= " S/ 13o SALÁRIO";
          }else{
            $descricao.= " S/ FÉRIAS";
          }

          $clrhrubricas->rh27_descr  = substr($descricao,0,30);
          $clrhrubricas->rh27_rubric = "R9".db_formatar($i,"s","0",2,"e",0);
          $clrhrubricas->rh27_instit = db_getsession('DB_instit');
          $clrhrubricas->alterar("R9".db_formatar($i,"s","0",2,"e",0), db_getsession('DB_instit'));

          if($clrhrubricas->erro_status=="0") {

            $erro_msg = $clrhrubricas->erro_msg;
            $sqlerro  = true;
            break;
          }
        }
      }

      if ($sqlerro == false) {

        $clinssirf->r33_nome   = $r33_nome;
        $clinssirf->r33_basfer = $r33_basfer;
        $clinssirf->r33_basfet = $r33_basfet;

        $sCamposInssIrf       = "r33_codigo as codigo, r33_anousu, r33_mesusu, r33_codtab, r33_inic, r33_fim, r33_perc";
        $sCamposInssIrf      .= ", r33_deduzi";
        $sWhereInssIrf        = "     r33_anousu = ".db_anofolha();
        $sWhereInssIrf       .= " and r33_mesusu = ".db_mesfolha();
        $sWhereInssIrf       .= " and r33_codtab = '{$codtab}'";
        $sWhereInssIrf       .= " and r33_instit = ".db_getsession("DB_instit");
        $sSqlInssIrf          = $clinssirf->sql_query_file(null, null, $sCamposInssIrf, "", $sWhereInssIrf);
        $result_dadosinssirf  = $clinssirf->sql_record($sSqlInssIrf);
        $numrows_dadosinssirf = $clinssirf->numrows;

        for ($i=0; $i<$numrows_dadosinssirf; $i++) {

          db_fieldsmemory($result_dadosinssirf, $i);

          $clinssirf->r33_codigo = $codigo;
          $clinssirf->r33_anousu = $r33_anousu;
          $clinssirf->r33_mesusu = $r33_mesusu;
          $clinssirf->r33_codtab = $r33_codtab;
          $clinssirf->r33_inic   = $r33_inic;
          $clinssirf->r33_fim    = $r33_fim;
          $clinssirf->r33_perc   = $r33_perc;
          $clinssirf->r33_deduzi = $r33_deduzi;
          $clinssirf->r33_instit = db_getsession("DB_instit");
          $clinssirf->r33_codele = $r33_codele;
          $clinssirf->alterar($codigo,db_getsession("DB_instit"),db_anofolha(),db_mesfolha());

          if ($clinssirf->erro_status == "0") {

            $erro_msg = $clinssirf->erro_msg;
            $sqlerro  = true;
            break;
          }

          $oPost->r33_codigo = $codigo;
          if(!manutencaoAtoLegalInssIrf($oPost)) {

            $sqlerro  = true;
            $erro_msg = "Erro ao salvar as informações do Ato Legal. Verifique se todos os campos foram preenchidos.";
          }
        }
      }
    }

    db_fim_transacao($sqlerro);
  }
}

/**
 * --------------------------------------------
 * ALTERAR
 * --------------------------------------------
 */
if(isset($alterar)) {

  if (db_mesfolha() == "") {
    $erro_msg = "Não encontrados parâmetros configurados para esta instituição. [cfpess] ";
  } else {

    db_inicio_transacao();
    $sqlerro = false;
    $codigo = (int)$codtab;

    if ($codigo > 2) {
      $r33_deduzi = 0;
    } else {

      $r33_tipo = "";
      $r33_nome = "";
    }

    $clinssirf->r33_codigo = $r33_codigo;
    $clinssirf->r33_anousu = db_anofolha();
    $clinssirf->r33_mesusu = db_mesfolha();
    $clinssirf->r33_codtab = $codtab;
    $clinssirf->r33_inic   = $r33_inic;
    $clinssirf->r33_fim    = $r33_fim;
    $clinssirf->r33_perc   = $r33_perc;
    $clinssirf->r33_deduzi = $r33_deduzi;
    $clinssirf->r33_instit = db_getsession("DB_instit");
    $clinssirf->r33_codele = $r33_codele;
    $clinssirf->alterar($r33_codigo, db_getsession("DB_instit"), db_anofolha(), db_mesfolha());

    $oPost->r33_codigo = $r33_codigo;
    if(!manutencaoAtoLegalInssIrf($oPost)) {

      $sqlerro  = true;
      $erro_msg = "Erro ao salvar as informações do Ato Legal. Verifique se todos os campos foram preenchidos.";
    }

    /**
     * Na alteração, apagas os registros atuais e insere de novo
     */
    $oDaoRegimePrevidenciaInssirf->excluir(null, "rh129_codigo = " . $r33_codigo . " and rh129_instit = " . db_getsession("DB_instit"));

    if (!empty($rh129_regimeprevidencia)) {

      $oDaoRegimePrevidenciaInssirf->rh129_codigo            = $clinssirf->r33_codigo;
      $oDaoRegimePrevidenciaInssirf->rh129_instit            = $clinssirf->r33_instit;
      $oDaoRegimePrevidenciaInssirf->rh129_regimeprevidencia = $rh129_regimeprevidencia;
      $oDaoRegimePrevidenciaInssirf->incluir(null);

      $rh129_regimeprevidencia = '';
      $rh127_descricao         = '';
    }

    $erro_msg = $clinssirf->erro_msg;
    if ($clinssirf->erro_status == "0") {
      $sqlerro = true;
    }

    if ($codigo > 2 && $sqlerro == false) {

      $completa = 0;
      $ini      = (3 * $codigo) - 8;
      $fim      = $ini + 2;

      for ($i = $ini; $i <= $fim; $i++) {

        $completa++;
        $descricao = "%" . $r33_nome;

        if ($completa == 1) {
          $descricao .= " S/ SALÁRIO";
        } else if ($completa == 2) {
          $descricao .= " S/13o SALÁRIO";
        } else {
          $descricao .= " S/ FÉRIAS";
        }

        $clrhrubricas->rh27_descr  = $descricao;
        $clrhrubricas->rh27_rubric = "R9" . db_formatar($i, "s", "0", 2, "e", 0);
        $clrhrubricas->rh27_instit = db_getsession('DB_instit');
        $clrhrubricas->alterar("R9" . db_formatar($i, "s", "0", 2, "e", 0), db_getsession('DB_instit'));

        if ($clrhrubricas->erro_status == "0") {

          $erro_msg = $clrhrubricas->erro_msg;
          $sqlerro  = true;
          break;
        }
      }

      if ($sqlerro == false) {

        $clinssirf->r33_nome   = $r33_nome;
        $clinssirf->r33_tipo   = $r33_tipo;
        $clinssirf->r33_rubmat = $r33_rubmat;
        $clinssirf->r33_ppatro = $r33_ppatro;
        $clinssirf->r33_rubsau = $r33_rubsau;
        $clinssirf->r33_basfer = $r33_basfer;
        $clinssirf->r33_basfet = $r33_basfet;
        $clinssirf->r33_rubaci = $r33_rubaci;
        $clinssirf->r33_tinati = $r33_tinati;

        $sCamposInssIrf       = "r33_codigo as codigo, r33_anousu, r33_mesusu, r33_codtab, r33_inic, r33_fim, r33_perc";
        $sCamposInssIrf      .= ", r33_deduzi";
        $sWhereInssIrf        = "     r33_instit = " . db_getsession("DB_instit");
        $sWhereInssIrf       .= " and r33_anousu = " . db_anofolha();
        $sWhereInssIrf       .= " and r33_mesusu = " . db_mesfolha();
        $sWhereInssIrf       .= " and r33_codtab = '{$codtab}'";
        $sSqlInssIrf          = $clinssirf->sql_query_file(null, null, $sCamposInssIrf, "", $sWhereInssIrf);
        $result_dadosinssirf  = $clinssirf->sql_record($sSqlInssIrf);
        $numrows_dadosinssirf = $clinssirf->numrows;

        for ($i = 0; $i < $numrows_dadosinssirf; $i++) {

          db_fieldsmemory($result_dadosinssirf, $i);

          $clinssirf->r33_codigo = $codigo;
          $clinssirf->r33_anousu = $r33_anousu;
          $clinssirf->r33_mesusu = $r33_mesusu;
          $clinssirf->r33_codtab = $r33_codtab;
          $clinssirf->r33_inic   = $r33_inic;
          $clinssirf->r33_fim    = $r33_fim;
          $clinssirf->r33_perc   = $r33_perc;
          $clinssirf->r33_deduzi = $r33_deduzi;
          $clinssirf->r33_instit = db_getsession("DB_instit");
          $clinssirf->r33_codele = $r33_codele;
          $clinssirf->alterar($codigo, db_getsession("DB_instit"), db_anofolha(), db_mesfolha());

          if ($clinssirf->erro_status == "0") {

            $erro_msg = $clinssirf->erro_msg;
            $sqlerro  = true;
            break;
          }

          $oPost->r33_codigo = $codigo;
          if(!manutencaoAtoLegalInssIrf($oPost)) {

            $sqlerro  = true;
            $erro_msg = "Erro ao salvar as informações do Ato Legal. Verifique se todos os campos foram preenchidos.";
          }
        }
      }
    }

    if ($sqlerro == false) {
      unset($r33_codigo);
    }

    db_fim_transacao($sqlerro);
  }
}

/**
 * --------------------------------------------
 * EXCLUIR
 * --------------------------------------------
 */
if(isset($excluir)) {

  if (db_mesfolha() == "") {
    $erro_msg = "Não encontrados parâmetros configurados para esta instituição. [cfpess] ";
  } else {

    db_inicio_transacao();
    $sqlerro               = false;
    $clinssirf->r33_instit = db_getsession("DB_instit")	;

    /**
     * Na exclusão, apagas os registros atuais
     */
    $oDaoRegimePrevidenciaInssirf->excluir(null, "rh129_codigo = " . $r33_codigo . " and rh129_instit = " . db_getsession("DB_instit"));

    if(!manutencaoAtoLegalInssIrf($oPost, true)) {

      $sqlerro  = true;
      $erro_msg = "Erro ao excluir as informações do Ato Legal.";
    }

    if(!$sqlerro) {

      $clinssirf->excluir($r33_codigo,db_getsession("DB_instit"));
      $erro_msg = $clinssirf->erro_msg;

      if($clinssirf->erro_status == "0") {
        $sqlerro = true;
      }
    }

    if ($sqlerro == false) {
      unset($r33_codigo);
    }

    db_fim_transacao($sqlerro);
  }
}

if(isset($r33_codigo) && trim($r33_codigo) != "") {

  $dbwhere  = "     r33_anousu = ".db_anofolha();
  $dbwhere .= " and r33_mesusu = ".db_mesfolha();
  $dbwhere .= " and r33_codigo = {$r33_codigo}";
  $dbwhere .= " and r33_instit = ".db_getsession("DB_instit");
  $dbwhere .= " and r33_codtab = '{$codtab}'";

  $sCamposInssIrf  = "r33_codigo, r33_codtab as codtab, round(r33_inic,2) as r33_inic, round(r33_fim,2) as r33_fim";
  $sCamposInssIrf .= ", round(r33_perc,2) as r33_perc, round(r33_deduzi,2) as r33_deduzi, r33_nome";
  $sSqlInssIrf     = $clinssirf->sql_query_file(null, null, $sCamposInssIrf,null,$dbwhere);
  $result          = $clinssirf->sql_record($sSqlInssIrf);

  db_fieldsmemory($result, 0);
  $db_botao = true;

  /**
   * Busca os dados do regimeprevidencia inssirf
   */
  $rh129_regimeprevidencia    = '';
  $rh127_descricao            = '';
  $sWhereGetDados             = " rh129_codigo = {$r33_codigo} and rh129_instit = ".db_getsession("DB_instit");
  $sSqlRegime                 = $oDaoRegimePrevidenciaInssirf->sql_query(null, "rh129_regimeprevidencia, rh127_descricao", null, $sWhereGetDados);
  $rsRegimePrevidenciaInssirf = $oDaoRegimePrevidenciaInssirf->sql_record($sSqlRegime);

  if ($oDaoRegimePrevidenciaInssirf->numrows > 0) {

    $oDadosRegime             = db_utils::fieldsMemory($rsRegimePrevidenciaInssirf,0);
    $rh129_regimeprevidencia  = $oDadosRegime->rh129_regimeprevidencia;
    $rh127_descricao          = $oDadosRegime->rh127_descricao;
  }

  $oDaoAtoLegalInssIrf     = new cl_atolegalprevidenciainssirf();
  $sCamposAtoLegalInssIrf  = "rh180_sequencial, rh180_atolegal, rh180_numero, rh180_ano, rh180_datapublicacao";
  $sCamposAtoLegalInssIrf .= ", rh180_datainiciovigencia";
  $sWhereAtoLegalInssIrf   = "rh180_inssirf = {$r33_codigo} AND rh180_instituicao = " . db_getsession("DB_instit");
  $sSqlAtoLegalInssIrf     = $oDaoAtoLegalInssIrf->sql_query_file(null, $sCamposAtoLegalInssIrf, null, $sWhereAtoLegalInssIrf);
  $rsAtoLegalInssIrf       = db_query($sSqlAtoLegalInssIrf);

  if($rsAtoLegalInssIrf && pg_num_rows($rsAtoLegalInssIrf) > 0) {
    db_fieldsmemory($rsAtoLegalInssIrf, 0);
  }
}

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <center>
        <?
        include(modification("forms/db_frminssirf001.php"));
        ?>
      </center>
    </td>
  </tr>
</table>
<?php
db_menu();
?>
</body>
</html>
<?php
if(isset($incluir) || isset($alterar) || isset($excluir)) {

  db_msgbox($erro_msg);

  if($sqlerro == true) {

    if($clinssirf->erro_campo != "") {

      echo "<script> document.form1.".$clinssirf->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clinssirf->erro_campo.".focus();</script>";
    }
  } else {
    echo "<script>location.href='pes1_inssirf002.php?codtab=$codtab'</script>";
  }
}
?>
<script>
  js_setar_foco();
</script>