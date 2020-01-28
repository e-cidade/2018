<?php
  /*
   *     E-cidade Software Publico para Gestao Municipal
   *  Copyright (C) 2014  DBseller Servicos de Informatica
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
  require_once("agu3_conscadastro_002_classe.php");
  require_once("dbforms/verticalTab.widget.php");
  
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  
  $clrotulo = new rotulocampo;
  $clrotulo->label('j01_matric');
  $clrotulo->label('x01_numcgm');
  $clrotulo->label('z01_nome');
  $clrotulo->label('x01_codrua');
  $clrotulo->label('j14_nome');
  $clrotulo->label('x01_numero');
  $clrotulo->label('x01_codbairro');
  $clrotulo->label('x01_distrito');
  $clrotulo->label('x01_zona');
  $clrotulo->label('x01_quadra');
  $clrotulo->label('x01_orientacao');
  $clrotulo->label('x01_rota');
  $clrotulo->label('x01_qtdeconomia');
  $clrotulo->label('x01_dtcadastro');
  $clrotulo->label('x01_qtdponto');
  $clrotulo->label('x01_obs');
  
  if(isset($cod_matricula) ) {
    
    $iMatriculaSelecionada = $cod_matricula;
  
    $oConsulta  = new ConsultaAguaBase($iMatriculaSelecionada);
    $oResultado = $oConsulta->RecordSetAguaBase();
    
    if (empty($oResultado)) {
      db_redireciona("db_erros.php?db_erro=Matrícula não cadastrada.");
    }
    
    $oDadosMatricula = db_utils::fieldsMemory($oResultado, 0, null);
  
  } else {
    db_redireciona("db_erros.php?db_erro=Nenhuma Matrícula Informada.");
  }
  
  $oDaoImobiliaria  = new cl_imobil;
  
  $sSql  = "SELECT j44_numcgm, z01_nome                       \n";
  $sSql .= "  FROM imobil                                     \n";
  $sSql .= "       inner join cgm on z01_numcgm = j44_numcgm  \n";
  $sSql .= " WHERE j44_matric = {$iMatriculaSelecionada}      \n";
  
  $rsImobiliaria = $oDaoImobiliaria->sql_record($sSql);
  
  $sDadosImobiliaria = ' - ';
  if($oDaoImobiliaria->numrows > 0) {
    
    $oDadosImobiliaria  = db_utils::fieldsMemory($rsImobiliaria, 0);
    
    $sDadosImobiliaria = "{$oDadosImobiliaria->j44_numcgm} - {$oDadosImobiliaria->z01_nome}";
  }
  
  $sSqlTipoImovel    = "SELECT * FROM aguaconstr WHERE x11_matric = {$iMatriculaSelecionada}";
  $oResultTipoImovel = db_query($sSqlTipoImovel);
   
  $sTipoImovel = "Terreno";
   
  if (pg_num_rows($oResultTipoImovel) > 0) {
    $sTipoImovel = "Prédio";
  } 
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
    <link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <style>
      .valores {
        background-color: #FFFFFF;
        padding-left: 10px;
      }
    </style>
    <script type="text/javascript">
      
      function js_Impressao() { 
        
        var lGeraCalculo = confirm('Imprimir Demonstrativo de Cálculo?');
        window.open('agu3_conscadastro_impressao.php?tipo=2&geracalculo=' + lGeraCalculo + 
                    '&parametro=<?php echo $oDadosMatricula->x01_matric; ?>','','location=0,HEIGHT=600,WIDTH=600');
      }
    </script>
  </head>

  <body>
    <fieldset style="width: 99%">
      <legend>
        <b>Dados Cadastrais do Imóvel ( <?php echo $sTipoImovel?> ) </b>
        <?php
          if (!empty($oDadosMatricula->j01_baixa)) {
            
            $sAviso  = "<span class='aviso'>";
            $sAviso .= "  <font color='red'><b>Matrícula Baixada</b></font>";
            $sAviso .= "</span>";
            
            echo $sAviso;
          }
        ?>
      </legend>
      <table>
        <tr>
          <td title="<?php echo $Tj01_matric; ?>" style="width: 120px;">
            <?php echo $Lj01_matric; ?>
          </td>
          <td title="<?php echo $Tj01_matric; ?>" nowrap class='valores' style="width: 300px;">
            <?php echo $oDadosMatricula->x01_matric; ?>
          </td>
          <td style="width: 10px;"></td>
          <td title="<?php echo $Tx01_numcgm; ?>" style="width: 110px;">
            <?php db_ancora($Lx01_numcgm,"js_JanelaAutomatica('cgm',' . $oDadosMatricula->x01_numcgm . ')", 2); ?>
          </td>
          <td title="<?php echo $Tz01_nome; ?>" nowrap class='valores' style="width: 300px;">
            <?php echo $oDadosMatricula->z01_nome; ?>
          </td>
        </tr>
        <tr>
          <td title="<?php echo $Tx01_codbairro; ?>">
            <?php echo $Lx01_codrua; ?>
          </td>
          <td title="<?php echo $Tx01_codrua; ?>" nowrap class='valores'>
            <?php echo $oDadosMatricula->x01_codrua; ?>
            -
            <?php echo $oDadosMatricula->j14_nome; ?>
            ,
            <?php echo $oDadosMatricula->x01_numero; ?>
          </td>
          <td></td>
          <td title="<?php echo $Tx01_codbairro; ?>">
            <b><?php echo $Lx01_codbairro; ?></b>
          </td>
          <td title="<?php echo $Tx01_codbairro; ?>" nowrap class='valores'>
            <?php echo $oDadosMatricula->j13_descr; ?>
          </td>
        </tr>
        <tr>
          <td title="<?php echo $Tx01_distrito; ?>">
          <?php echo $Lx01_distrito; ?>  
          </td>
          <td title="<?php echo $Tx01_distrito; ?>" nowrap class='valores'>
            <?php echo $oDadosMatricula->x01_distrito; ?>
          </td>
          <td></td>
          <td title="<?php echo $Tx01_zona; ?>">
            <?php echo $Lx01_zona; ?>
          </td>
          <td title="<?php echo $Tx01_zona; ?>" nowrap class='valores'>
            <?php echo $oDadosMatricula->x01_zona; ?> 
          </td>
        </tr>
        <tr>
          <td title="<?php echo $Tx01_quadra;?>">
            <b><?php echo $Lx01_quadra; ?></b>
          </td>
          <td nowrap class='valores'>
            <?php echo $oDadosMatricula->x01_quadra; ?>
          </td>
          <td></td>
          <td title="<?php echo $Tx01_numero; ?>">
            <b><?php echo $Lx01_numero; ?></b>
          </td>
          <td nowrap class='valores' title="<?php echo $Tx01_numero; ?>">
            <?php echo $oDadosMatricula->x01_numero; ?>
          </td>
        </tr>
        <tr>
          <td title="<?php echo $Tx01_orientacao; ?>">
            <b><?php echo $Lx01_orientacao; ?></b>
          </td>
          <td title="<?php echo $Tx01_orientacao; ?>" nowrap class='valores'>
            <?php echo $oDadosMatricula->x01_orientacao; ?>
          </td>
          <td></td>
          <td title="<?php echo $Tx01_rota; ?>">
            <b><?php echo $Lx01_rota; ?></b>
          </td>
          <td title="<?php echo $Lx01_rota; ?>" nowrap class='valores'>
            <?php echo $oDadosMatricula->x01_rota; ?>
          </td>
        </tr>
        <tr>
          <td title="<?php echo $Tx01_qtdeconomia; ?>">
            <b><?php echo $Lx01_qtdeconomia; ?><b>
          </td>
          <td title="<?php echo $Tx01_qtdeconomia; ?>" nowrap class='valores'>
            <?php echo $oDadosMatricula->x01_qtdeconomia; ?>
          </td>
          <td></td>
          <td title="<?php echo $Tx01_dtcadastro; ?>">
            <b><?php echo $Lx01_dtcadastro; ?></b>
          </td>
          <td style="padding-right: 10px" title="<?php echo $Tx01_dtcadastro; ?>" nowrap class='valores'>
            <?php echo db_formatar($oDadosMatricula->x01_dtcadastro, 'd'); ?>
          </td>
        </tr>
        <tr>
          <td title="<?php echo $Tx01_qtdponto; ?>">
            <b><?php echo $Lx01_qtdponto; ?></b>
          </td>
          <td title="<?php echo $Tx01_qtdponto; ?>" nowrap class='valores'>
            <?php echo $oDadosMatricula->x01_qtdponto; ?>
          </td>
          <td></td>
          <td title="Imobiliária">
            <b>Imobiliária:</b>
          </td>
          <td style="padding-right: 10px;" width="500px" title="<?php echo $Lx01_obs; ?>" nowrap class='valores'>
            <?php echo $sDadosImobiliaria; ?>
          </td>
        </tr>
        <tr>
          <td colspan="5"></td>
        </tr>
        <tr>
          <td title="<?php echo $Tx01_obs; ?>">
            <b><?php echo $Lx01_obs; ?></b>
          </td>
          <td colspan="4" style="padding-right: 10px;" title="<?php echo $Lx01_obs; ?>" nowrap class='valores'>
            <?php echo $oDadosMatricula->x01_obs; ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <fieldset style="width: 99%">
      <legend>
        <b>Detalhamento</b>
      </legend>
      <?php
        $sLink = 'agu3_conscadastro_002_detalhes.php?solicitacao=';  
      
        $oTabDetalhes = new verticalTab("detalhesemp", 300);
        
        $oTabDetalhes->add("CaracteristicaImovel", "Caracteristicas do Imóvel",
            "{$sLink}CaracteristicasDoImovel&parametro={$oDadosMatricula->x01_matric}");
        
        $oTabDetalhes->add("Isencoes", "Isenções",
            "{$sLink}Isencoes&parametro={$oDadosMatricula->x01_matric}");
        
        $oTabDetalhes->add("Construcao", "Construções",
            "{$sLink}Construcoes&parametro={$oDadosMatricula->x01_matric}");
        
        $oTabDetalhes->add("Hidrometros", "Hidrômetros",
            "{$sLink}Hidrometro&parametro={$oDadosMatricula->x01_matric}");
        
        $oTabDetalhes->add("Leituras", "Leituras" ,
            "{$sLink}Leitura&parametro={$oDadosMatricula->x01_matric}");
        
        $oTabDetalhes->add("Calculo", "Cálculo",
            "{$sLink}Calculo&parametro={$oDadosMatricula->x01_matric}");
        
        $oTabDetalhes->add("ImprimeBIC", "Imprime BIC", "javascript:");
        
        $oTabDetalhes->add("HistoricoDeCortes", "Histórico de Cortes",
            "{$sLink}Corte&parametro={$oDadosMatricula->x01_matric}");
        
        $oTabDetalhes->add("Condominio", "Condomínio",
            "{$sLink}Condominio&parametro={$oDadosMatricula->x01_matric}");
        
        $oTabDetalhes->add("Ocorrencias", "Ocorrências",
            "{$sLink}Ocorrencia&parametro={$oDadosMatricula->x01_matric}");
        
        $oTabDetalhes->add("BaixasDeImoveis", "Baixas de Imóveis",
            "{$sLink}BaixaImoveis&parametro={$oDadosMatricula->x01_matric}");
        
        $oTabDetalhes->show();
      ?>
    </fieldset>
  </body>
<script type="text/javascript">

  $('ImprimeBIC').observe("click", function() {
    js_Impressao();
  });
</script>
</html>