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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/DBNumber.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_jsplibwebseller.php"));
require_once(modification("std/DBDate.php"));

$iUsuarioLogado = db_getsession('DB_id_usuario');

db_postmemory( $_POST );

$claluno           = new cl_aluno;
$clalunoprimat     = new cl_alunoprimat;
$clalunobairro     = new cl_alunobairro;
$clpais            = new cl_pais;
$clescola          = new cl_escola;
$clcensouf         = new cl_censouf;
$clcensomunic      = new cl_censomunic;
$clcensoorgemissrg = new cl_censoorgemissrg;

$db_opcao       = 1;
$db_opcao1      = 1;
$db_botao       = true;
$erroconf       = false;
$iAluno         = null;
$iOpcaoFiliacao = 1;

$sCamposEscola = "ed261_i_codigo as ed47_i_censomunicend, ed260_i_codigo as ed47_i_censoufend,ed18_c_cep as ed47_v_cep";
$sWhereEscola  = " ed18_i_codigo = ".db_getsession("DB_coddepto");
$sSqlEscola   = $clescola->sql_query("", $sCamposEscola, "", $sWhereEscola);
$result       = $clescola->sql_record($sSqlEscola);

if( $clescola->numrows > 0 ) {
  db_fieldsmemory( $result, 0 );
}

$ed47_d_cadast_dia = date("d");
$ed47_d_cadast_mes = date("m");
$ed47_d_cadast_ano = date("Y");
$ed47_d_ultalt_dia = date("d");
$ed47_d_ultalt_mes = date("m");
$ed47_d_ultalt_ano = date("Y");

function TiraEspacoNome( $nome ) {

  $sep   = "";
  $str   = "";
  $parte = explode( " ", $nome );

  for( $i = 0; $i < count( $parte ); $i++ ) {

    if( trim( $parte[$i] ) != "" ) {

      $str .= $sep . trim( $parte[$i] );
      $sep  = " ";
    }
  }

  return $str;
}

if( isset( $incluir ) ) {

  if ($ed47_v_nome != "") {

    $erroconf         = false;
    $ed47_v_nome      = TiraEspacoNome($ed47_v_nome);
    $ed47_v_mae       = TiraEspacoNome($ed47_v_mae);
    $ed47_v_pai       = TiraEspacoNome($ed47_v_pai);
    $ed47_c_nomeresp  = TiraEspacoNome($ed47_c_nomeresp);
    $sCampos          = "ed47_i_codigo as jatem, ed47_d_nasc as datatem, ed47_c_certidaonum as certidaonumexiste";
    $sCampos         .= ", ed47_v_mae as maesim";
    $result2          = $claluno->sql_record($claluno->sql_query("", $sCampos, "", " ed47_v_nome = '$ed47_v_nome'"));

    if( $claluno->numrows > 0 ) {

      db_fieldsmemory( $result2, 0 );

      if( $ed47_d_nasc_ano . "-" . $ed47_d_nasc_mes . "-" . $ed47_d_nasc_dia == $datatem ) {

        $erroconf = true;

        $sMensagemNome  = "Este nome ({$ed47_v_nome}) já possui cadastro com a mesma data de nascimento digitada";
        $sMensagemNome .= "({$ed47_d_nasc})! Redirecionando para visualização...";

        db_msgbox( $sMensagemNome );

        if( !isset( $leitor ) ) {
          db_redireciona("edu1_alunodados002.php?chavepesquisa={$jatem}");
        } else {
          db_redireciona("bib1_alunoabas000.php?leitor&chavepesquisa={$jatem}");
        }

        exit;
      }

      if( trim( $ed47_v_mae) == trim( $maesim ) && trim( $ed47_v_mae ) != "" && trim( $maesim ) != "" ) {

        $erroconf = true;

        $sMensagemNome  = "Este nome ({$ed47_v_nome}) já possui cadastro com o mesmo nome da mae digitado ({$maesim})!";
        $sMensagemNome .= " Redirecionando para visualização...";

        db_msgbox( $sMensagemNome );

        if( !isset( $leitor ) ) {
          db_redireciona("edu1_alunodados002.php?chavepesquisa={$jatem}");
        } else {
          db_redireciona("bib1_alunoabas000.php?leitor&chavepesquisa={$jatem}");
        }

        exit;
      }
    }

    if ($erroconf == false) {

      $ed47_c_foto = @trim($GLOBALS["HTTP_POST_VARS"]["ed47_o_oid"]);
      $ed47_o_oid  = "tmp/".@trim($GLOBALS["HTTP_POST_VARS"]["ed47_o_oid"]);

      if ($ed47_c_foto != "") {

        db_query("begin");
        $oid_imagem = pg_loimport($conn,$ed47_o_oid) or die("Erro(15) importando imagem");
        db_query("end");
        $ed47_o_oid = $oid_imagem;
      } else {
        $oid_imagem = "0";
      }

      try {

        db_inicio_transacao();

        /**
        * Verifica se o campo ed47_celularresponsavel já possui DDD
        */
        if ( strlen($ed47_celularresponsavel) <= 9 ) {
          $ed47_celularresponsavel = "{$dddcelularresponsavel}{$ed47_celularresponsavel}";
        }

        $claluno->ed47_c_foto               = $ed47_c_foto;
        $claluno->ed47_o_oid                = $oid_imagem;
        $claluno->ed47_v_nome               = $ed47_v_nome;
        $claluno->ed47_v_mae                = $ed47_v_mae;
        $claluno->ed47_v_pai                = $ed47_v_pai;
        $claluno->ed47_c_nomeresp           = $ed47_c_nomeresp;
        $claluno->ed47_celularresponsavel   = preg_replace("/[^0-9]/", "", $ed47_celularresponsavel);
        $claluno->ed47_v_telef              = preg_replace("/[^0-9]/", "", $ed47_v_telef);
        $claluno->ed47_v_telcel             = preg_replace("/[^0-9]/", "", $ed47_v_telcel);
        $claluno->ed47_v_fax                = preg_replace("/[^0-9]/", "", $ed47_v_fax);
        $claluno->ed47_i_login              = $iUsuarioLogado;
        $claluno->ed47_situacaodocumentacao = "0";
        $claluno->incluir($ed47_i_codigo);

        if ( $claluno->erro_status == 0 ) {
          throw new BusinessException( $claluno->erro_msg );
        }

        $iAluno = $claluno->ed47_i_codigo;
        $ultimo = $claluno->ed47_i_codigo;

        if ($j13_codi != "") {

         $clalunobairro->ed225_i_aluno  = $ultimo;
         $clalunobairro->ed225_i_bairro = $j13_codi;
         $clalunobairro->incluir(null);
        }

        if ($ed76_i_escola != "") {

          $clalunoprimat->ed76_i_aluno = $ultimo;
          $clalunoprimat->incluir(null);
        }

        /**
         * Verifica se o aluno já possui um cadastro de cidadão, buscando pelo nome, data de nascimento e nome da mãe.
         * Caso não exista, cria um novo cidadão
         */
        $oDataNascimento     = new DBDate($ed47_d_nasc);
        $aCidadao            = CidadaoRepository::getCidadaoPorNomeDataNascimento($ed47_v_nome, $oDataNascimento, $ed47_v_mae);
        $oCidadao            = null;
        $oCidadaoMae         = null;
        $oCidadaoPai         = null;
        $oCidadaoResponsavel = null;

        if (empty($aCidadao)) {

          $oCidadao = new Cidadao();
          $oCidadao->setNome($ed47_v_nome);
          $oCidadao->setDataNascimento($oDataNascimento->getDate(DBDate::DATA_PTBR));
          $oCidadao->setSexo($ed47_v_sexo);
          $oCidadao->setAtivo(true);
          $oCidadao->setEndereco($ed47_v_ender);
          $oCidadao->setCEP($ed47_v_cep);
          $oCidadao->setBairro($ed47_v_bairro);

          if (!DBNumber::isInteger($ed47_c_numero)) {

            $ed47_v_compl .= " N: {$ed47_c_numero}";
            $ed47_c_numero = '';
          }

          $oCidadao->setComplemento($ed47_v_compl);
          $oCidadao->setNumero($ed47_c_numero);
          $oCidadao->setSituacaoCidadao(2);

          /**
           * Busca a descrição do município para setar no cidadão caso tenha sido selecionada uma opção
           */
          $ed47_i_censomunicnat = trim($ed47_i_censomunicnat);
          if ( !empty($ed47_i_censomunicnat) ) {

            $oDaoCensoMunicipio = new cl_censomunic();
            $sSqlCensoMunicipio = $oDaoCensoMunicipio->sql_query_file($ed47_i_censomunicend, "ed261_c_nome");
            $rsCensoMunicipio   = $oDaoCensoMunicipio->sql_record($sSqlCensoMunicipio);

            if ($oDaoCensoMunicipio->numrows > 0) {
              $oCidadao->setMunicipio(db_utils::fieldsMemory($rsCensoMunicipio, 0)->ed261_c_nome);
            }
          }

          /**
           * Busca a descrição do estado para setar no cidadão caso tenha sido selecionada uma opção
           */
          $ed47_i_censomunicnat = trim($ed47_i_censomunicnat);
          if ( !empty($ed47_i_censomunicnat) ) {

            $oDaoCensoUF = new cl_censouf();
            $sSqlCensoUF = $oDaoCensoUF->sql_query_file($ed47_i_censoufend, "ed260_c_sigla");
            $rsCensoUF   = $oDaoCensoUF->sql_record($sSqlCensoUF);

            if ($oDaoCensoUF->numrows > 0) {
              $oCidadao->setUF(db_utils::fieldsMemory($rsCensoUF, 0)->ed260_c_sigla);
            }
          }

          /**
           * Caso o código de cidadão da mãe e do pai seja diferente de vazio, setamos o pai e mãe do aluno como cidadão
           */
          if (!empty($oInputCodigoMae)) {

            $oCidadaoMae = CidadaoRepository::getCidadaoByCodigo($oInputCodigoMae);
            $oCidadao->setMae($oCidadaoMae);
          }

          if (!empty($oInputCodigoPai)) {

            $oCidadaoPai = CidadaoRepository::getCidadaoByCodigo($oInputCodigoPai);
            $oCidadao->setPai($oCidadaoPai);
          }

          $oCidadao->salvar();
        } else {

          /**
           * Caso retorne mais de um cidadão utiliza o de código menor
           */
          $iCidadaoMenor = null;
          foreach ($aCidadao as $oCidadaoTemporario) {

            if (empty($iCidadaoMenor) || $iCidadaoMenor > $oCidadaoTemporario->getCodigo()) {
              $oCidadao = $oCidadaoTemporario;
            }
          }
        }

        $sWhere           = "ed330_cidadao = {$oCidadao->getCodigo()} and ed330_cidadao_seq = {$oCidadao->getSequencialInterno()}";
        $oDaoAlunoCidadao = new cl_alunocidadao();
        $sSqlAlunoCidadao = $oDaoAlunoCidadao->sql_query_file(null, 'ed330_sequencial', null, $sWhere);
        $rsAlunoCidadao   = $oDaoAlunoCidadao->sql_record($sSqlAlunoCidadao);

        if ($oDaoAlunoCidadao->numrows <= 0) {

          $oDaoAlunoCidadao->ed330_aluno       = $iAluno;
          $oDaoAlunoCidadao->ed330_cidadao     = $oCidadao->getCodigo();
          $oDaoAlunoCidadao->ed330_cidadao_seq = $oCidadao->getSequencialInterno();
          $oDaoAlunoCidadao->incluir(null);

          if ($oDaoAlunoCidadao->erro_status == 0) {
            throw new BusinessException($oDaoAlunoCidadao->erro_msg);
          }
        }

        /**
         * Salva o vinculo do alunoCidadao com o Responsavel
         */
        if (!empty($oInputCodigoResponsavel)) {

          $oCidadaoResponsavel                     = CidadaoRepository::getCidadaoByCodigo($oInputCodigoResponsavel);
          $oDaoAlunoResponsavel                    = new cl_alunocidadaoresponsavel();
          $oDaoAlunoResponsavel->ed331_aluno       = $iAluno;
          $oDaoAlunoResponsavel->ed331_cidadao     = $oCidadaoResponsavel->getCodigo();
          $oDaoAlunoResponsavel->ed331_cidadao_seq = $oCidadaoResponsavel->getSequencialInterno();
          $oDaoAlunoResponsavel->incluir(null);

          if ($oDaoAlunoResponsavel->erro_status == 0) {
            throw new BusinessException($oDaoAlunoResponsavel->erro_msg);
          }
        }

        /**
         * Verifica se algum contato foi selecionado, caso tenha sido verifica qual foi selecionado e se possui um cidadão.
         * Caso exista o cidadao salva a vinculação
         */
        if (!empty($oSelectContato)) {

          $oDaoAlunoContato              = new cl_alunocidadaocontato();
          $oDaoAlunoContato->ed332_aluno = $iAluno;

          if ($oSelectContato == 1 && !empty($oCidadaoMae)) {

            $oDaoAlunoContato->ed332_cidadao     = $oCidadaoMae->getCodigo();
            $oDaoAlunoContato->ed332_cidadao_seq = $oCidadaoMae->getSequencialInterno();
          }

          if ($oSelectContato == 2 && !empty($oCidadaoPai)) {

            $oDaoAlunoContato->ed332_cidadao     = $oCidadaoPai->getCodigo();
            $oDaoAlunoContato->ed332_cidadao_seq = $oCidadaoPai->getSequencialInterno();
          }

          if ($oSelectContato == 3 && !empty($oCidadaoResponsavel)) {

            $oDaoAlunoContato->ed332_cidadao     = $oCidadaoResponsavel->getCodigo();
            $oDaoAlunoContato->ed332_cidadao_seq = $oCidadaoResponsavel->getSequencialInterno();
          }

          if (!empty($oDaoAlunoContato->ed332_cidadao)) {

            $oDaoAlunoContato->incluir(null);

            if ($oDaoAlunoContato->erro_status == 0) {
              throw new BusinessException($oDaoAlunoContato->erro_msg);
            }
          }
        }

        db_fim_transacao();
      } catch( Exception $oErro ) {
        db_fim_transacao( true );
      }
    }
  } else {

    $ed47_c_foto = @trim($GLOBALS["HTTP_POST_VARS"]["ed47_o_oid"]);
    $ed47_o_oid  = "tmp/".@trim($GLOBALS["HTTP_POST_VARS"]["ed47_o_oid"]);

    if ($ed47_c_foto != "") {

      db_query("begin");
      $oid_imagem = pg_loimport($conn,$ed47_o_oid) or die("Erro(15) importando imagem");
      db_query("end");
      $ed47_o_oid = $oid_imagem;
    } else {
      $oid_imagem = "0";
    }

    try {

      db_inicio_transacao();

      $claluno->ed47_c_foto               = $ed47_c_foto;
      $claluno->ed47_o_oid                = $oid_imagem;
      $claluno->ed47_i_login              = $iUsuarioLogado;
      $claluno->ed47_situacaodocumentacao = 0;
      $claluno->incluir($ed47_i_codigo);
      $ultimo = $claluno->ed47_i_codigo;

      if ($j13_codi != "") {

        $clalunobairro->ed225_i_aluno  = $ultimo;
        $clalunobairro->ed225_i_bairro = $j13_codi;
        $clalunobairro->incluir(null);
      }

      if ($ed76_i_escola != "") {

        $clalunoprimat->ed76_i_aluno = $ultimo;
        $clalunoprimat->incluir(null);
      }

      db_fim_transacao();
    } catch( Exception $oErro ) {
      db_fim_transacao( true );
    }
  }
  $db_botao = false;
} else if (isset($chavepesquisa)) {
  db_redireciona("edu1_alunodados002.php?chavepesquisa=$chavepesquisa");
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<?php
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("strings.js");
  db_app::load("dbmessageBoard.widget.js");
  db_app::load("windowAux.widget.js");
  db_app::load("dbtextFieldData.widget.js");
  db_app::load("datagrid.widget.js");
?>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <center>
   <fieldset style="width:95%"><legend><b>Inclusão de Aluno</b></legend>
    <?include(modification("forms/db_frmalunodados.php"));?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?php
if( isset( $incluir ) ) {

  if( $claluno->erro_status == "0" ) {

    $claluno->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>";

    if( $claluno->erro_campo != "" ) {

      echo "<script> document.form1.".$claluno->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$claluno->erro_campo.".focus();</script>";
    }
  } else {

    $claluno->erro( true, false );

    if( !isset( $leitor ) ) {
      db_redireciona("edu1_alunodados002.php?chavepesquisa=$ultimo");
    } else {
      db_redireciona("bib1_alunoabas000.php?leitor&chavepesquisa=$ultimo");
    }
  }
}