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
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once("dbforms/verticalTab.widget.php");

$oGet            = db_utils::postMemory( $_GET );
$oAluno          = new Aluno($oGet->iAluno);
$oDataNascimento = new DBDate( $oAluno->getDataNascimento() );
$sSexo           = $oAluno->getSexo() == 'F' ? 'Feminino' : 'Masculino';

$sMunicipioResidencial = '';
$sUFResidencial        = '';
if ( $oAluno->getMunicipioResidencia() instanceof CensoMunicipio ) {

  $sMunicipioResidencial = $oAluno->getMunicipioResidencia()->getNome();
  $sUFResidencial        = $oAluno->getMunicipioResidencia()->getUf()->getUF();
}

$sMunicipioNascimento = '';
$sUfNascimento        = '';
if ( $oAluno->getNaturalidade() instanceof CensoMunicipio ) {

  $sMunicipioNascimento = $oAluno->getNaturalidade()->getNome();
  $sUfNascimento        = $oAluno->getNaturalidade()->getUf()->getUF();
}

$sNacionalidade = "Brasileira";
switch ($oAluno->getNacionalidade()) {

  case Aluno::NACIONALIDADE_NATURALIZADA:
    break;
  case Aluno::NACIONALIDADE_ESTRANGEIRA:
    $sNacionalidade = "Estrangeira";
    break;
}

$sEstadoCivil = "Solteiro";
switch ($oAluno->getEstadoCivil()) {

  case 2:
    $sEstadoCivil = "Casado";
    break;
  case 3:
    $sEstadoCivil = "Viúvo";
    break;
  case 4:
    $sEstadoCivil = "Divorciado";
    break;
}

$mFotoAluno = "";
db_inicio_transacao();

$mFotoAluno = $oAluno->getFoto();
if ( is_bool($mFotoAluno) ) {
  $mFotoAluno = "imagens/none1.jpeg";
}

db_fim_transacao();

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <style type="text/css">
    .valores {
       background-color: #FFFFFF;
       padding: 3px;
       padding-left: 5px;
       padding-right: 5px;
       min-width: 100px !important;
    }

    .tabela-cabecalho {
       width: 100%;
       border-spacing: 1;
       width: 200px;
    }

    #dados-aluno tr td:nth-child(odd) {
      font-weight: bold;
    }

    .nome-aluno {
      position: relative;
      text-align: left;
      margin-bottom: 0;
      padding-bottom: 0;
    }

    .nome-aluno h3 {
      margin-top: 11px;
      margin-bottom: 10px;
      padding-bottom: 0;
    }

    .btn-fechar {
      position: fixed;
      right: 10px;
      top:10px;
    }
    #foto fieldset {

      display:block;
      float:left;
      min-height: 150px;
      width:150px;
    }

  </style>
</head>
<body class='body-default'>

  <div style="margin-left: 10px;">

    <div class='btn-fechar'>
      <input type="button" name="fechar" value="Fechar" id='fechar-consulta'  />
    </div>
  </div>
  <div class='subcontainer'  style="width: 99%;">

    <div id='foto' style="display:block; float:left ">
      <fieldset style='  '>
        <legend>Foto</legend>
        <img src="<?=$mFotoAluno?>" width="120" height="150" >
      </fieldset>
    </div>

    <div >
      <div class="nome-aluno" >
        <h3><?="{$oGet->iAluno}  - {$oGet->sNome}"?></h3>
      </div>
      <fieldset>
        <legend>Dados Aluno</legend>
        <table id='dados-aluno'>
          <tr>
            <td>INEP / ID Aluno:</td>
            <td class="valores" >&nbsp;<?=$oAluno->getCodigoInep()?></td>
            <td>Nascimento:</td>
            <td class="valores">&nbsp;<?=$oDataNascimento->convertTo(DBDate::DATA_PTBR);?></td>
            <td>Sexo:</td>
            <td class="valores" >&nbsp;<?=$sSexo?></td>
            <td>Estado Civil:</td>
            <td class="valores">&nbsp;<?=$sEstadoCivil?></td>
          </tr>
          <tr>
            <td>Endereço:</td>
            <td class="valores">&nbsp;<?=$oAluno->getEnderecoResidencia()?></td>
            <td>Número:</td>
            <td class="valores">&nbsp;<?=$oAluno->getNumeroResidencia()?></td>
            <td>Complemento:</td>
            <td class="valores">&nbsp;<?=$oAluno->getComplementoResidencia()?></td>
            <td>Bairro:</td>
            <td class="valores">&nbsp;<?=$oAluno->getBairroResidencia()?></td>
          </tr>
          <tr>

            <td>Município:</td>
            <td class="valores">&nbsp;<?=$sMunicipioResidencial?></td>
            <td>CEP:</td>
            <td class="valores">&nbsp;<?=$oAluno->getCepResidencia()?></td>
            <td>UF:</td>
            <td class="valores">&nbsp;<?=$sUFResidencial?></td>
            <td>País:</td>
            <td class="valores">&nbsp;<?=$oAluno->getPaisNaturalidade()->getDescricao()?></td>
          </tr>
          <tr>
            <td>Zona:</td>
            <td class="valores">&nbsp;<?=$oAluno->getZonaResidencia()?></td>
            <td>Nascionalidade:</td>
            <td class="valores">&nbsp;<?=$sNacionalidade?></td>
            <td>Naturalidade:</td>
            <td class="valores">&nbsp;<?=$sMunicipioNascimento?></td>
            <td>UF de Nascimento:</td>
            <td class="valores">&nbsp;<?=$sUfNascimento?></td>
          </tr>
          <tr>
            <td>Raça:</td>
            <td class="valores">&nbsp;<?=$oAluno->getRaca()?></td>
            <td>Tipo Sanguíneo:</td>
            <td class="valores">&nbsp;<?=$oAluno->getTipoSanguineo()?></td>
            <td>Telefone:</td>
            <td class="valores">&nbsp;<?=$oAluno->getNumeroTelefone()?></td>
            <td>Celular:</td>
            <td class="valores">&nbsp;<?=$oAluno->getNumeroCelular()?></td>
          </tr>
        </table>
      </fieldset>
    </div>
  </div>
  <!-- Container das abas  -->
  <div class="subcontainer" style="width: 99%;">
    <fieldset>
      <legend>Opções</legend>
      <?php
        $oTabDetalhes = new verticalTab("consultaAluno", 500, 15);
        $oTabDetalhes->add("abaProgressaoParcial", "Progressões Parciais","edu3_progressoesparciais.php?iAluno={$oGet->iAluno}");

        $oTabDetalhes->show();
      ?>
    </fieldset>
  </div>
</body>
</html>
<script type="text/javascript">

$('fechar-consulta').observe('click', function() {
  parent.db_iframe_consulta.hide();
});

</script>
