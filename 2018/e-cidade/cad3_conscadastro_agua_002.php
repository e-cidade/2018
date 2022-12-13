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

require_once("agu3_conscadastro_002_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if( !isset($cod_matricula) ) {
  $matriculaSelecionada = 0;
} else {
  $matriculaSelecionada = $cod_matricula;

  $Consulta = new ConsultaAguaBase($matriculaSelecionada);

  if( !($result = $Consulta->RecordSetAguaBase()) ) {
    $matriculaSelecionada = 0;
  }

  // Rotulos
  $claguabase = $Consulta->GetAguaBaseDAO();
  $claguabase->rotulo->label();
}

$sql = "select * from aguaconstr where x11_matric = $matriculaSelecionada";
$res = db_query($sql);
if(pg_num_rows($res)>0) {
  $x01_tipoimovel="Predio";
} else {
  $x01_tipoimovel="Terreno";
}

 /***********************************************************************************************/
 // Verifica se encontrou a matrícula. Caso não tenha encontrado exibe a mensagem abaixo.
?>
<html>
<head>
<title>Dados do imóvel - BIC</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_Impressao1() {
  window.open('agu3_conscadastro_impressao.php?tipo=1&parametro=<?=$cod_matricula?>','','location=0,HEIGHT=600,WIDTH=600');
}
function js_Impressao() {
  var geracalculo = confirm('Imprimir Demonstrativo de Cálculo?');
  window.open('agu3_conscadastro_impressao.php?tipo=2&geracalculo='+geracalculo+'&parametro=<?=$cod_matricula?>','','location=0,HEIGHT=600,WIDTH=600');
}
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
if ($matriculaSelecionada == 0) {
   $db_erro = "Matricula nao cadastrada";
?>
<center>
<table width="75%" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center"><font color="#FF0000" size="3" face="Arial, Helvetica, sans-serif">Notifica&ccedil;&atilde;o do Sistema:</font></td>
  </tr>
  <tr>
    <td height="56" align="center"><font size="2" face="Arial, Helvetica, sans-serif"><br>
    <?
  echo @$db_erro;
  ?>
     </font></td>
  </tr>
  <tr>
      <td align="center">
</td>
  </tr>
</table>
</center>
<?
 /***********************************************************************************************/
 // Se encontrou a matrícula, exibe tabela com a descrição do imóvel.
} else {
 db_fieldsmemory($result, 0);

 $sSql  = " SELECT x06_codrota                                      ";
 $sSql .= "   FROM aguarotarua                                      ";
 $sSql .= "        inner join aguarota on x06_codrota = x07_codrota ";
 $sSql .= "  WHERE x07_codrua = {$x01_codrua}                       ";
 $sSql .= "    AND {$x01_numero} between x07_nroini and x07_nrofim  ";
 
 $rsResultado = db_query($sSql);
 db_fieldsmemory($rsResultado, 0);
 
 
 if($x01_entrega != '') {
  $rsZonaEntrega = db_query("select * from iptucadzonaentrega where j85_codigo = {$x01_entrega}");
   db_fieldsmemory($rsZonaEntrega, 0);
 }

  ?>
<table width="100%" height="100%" border="0" align="center" cellpadding="0" cellspacing="2">
  <tr bgcolor="#CCCCCC">
    <td colspan="4" align="center"><font color="#333333"><strong>&nbsp;DADOS CADASTRAIS
      DO IM&Oacute;VEL (&nbsp;
      <?=$x01_tipoimovel?>
      &nbsp;)&nbsp;</strong>
    <?
    //if(!empty($j01_baixa))
    //   echo "</font><font color=\"red\"><strong> Baixada :".$j01_baixa."</strong></font>";
    ?>
    </td>
  </tr>

  <tr>

    <!-- X01_MATRIC  :  Z01_NOME -->
    <td width=10% align="right" nowrap bgcolor="#CCCCCC">
    <?=$Lx01_matric?>;
  </td>

    <td align="left" nowrap bgcolor="#FFFFFF">
    <font color="#666666">
      <strong><?=$x01_matric?></strong>
    </font>
  </td>

   <td width=10% align="right" nowrap bgcolor="#CCCCCC" title='Clique aqui para outros dados do contribuinte'>
      <?db_ancora($Lx01_numcgm,"js_JanelaAutomatica('cgm','$x01_numcgm')",2);?>
  </td>

    <td align="left" nowrap bgcolor="#FFFFFF">
    <font color="#666666">
      <strong><?=$z01_nome?></strong>
    </font>
  </td>

  </tr>

  <!-- LOGRADOURO  :  BAIRRO -->
  <tr>
  <td width=10% align="right" bgcolor="#CCCCCC">
    <?=$Lx01_codrua?>
  </td>

  <td bgcolor="#FFFFFF">
    <font color="#666666">
      <strong>
        <?=$x01_codrua?>
        -
        <?=$j14_nome?>
        ,
        <?=$x01_numero?>
      </strong>
    </font>
  </td>

  <td width=10% align="right" bgcolor="#CCCCCC">
    <?=$Lx01_codbairro?>
  </td>
  <td bgcolor="#FFFFFF">
    <font color="#666666">
      <strong>
           <?=$j13_descr?>
      </strong>
    </font>
  </td>
  </tr>

<tr>
  <td width=10% align="right" bgcolor="#CCCCCC">
    <?=$Lx01_entrega?>
    </td>
  <td bgcolor="#FFFFFF">
      <font color="#666666">
        <strong><?=$x01_entrega.' - '.@$j85_descr?></strong>
      </font>
    </td>

    <td width=10% align="right" bgcolor="#CCCCCC">
      <!-- ZONA -->
      <?=$Lx01_letra?>
    </td>

    <td bgcolor="#FFFFFF">
    <font color="#666666">
        <strong><?=$x01_letra?></strong>
      </font>
    </td>
  </tr>


  <!-- DISTRITO  :  ZONA -->
  <tr>
  <td width=10% align="right" bgcolor="#CCCCCC">
    <?=$Lx01_distrito?>
    </td>
  <td bgcolor="#FFFFFF">
      <font color="#666666">
        <strong><?=$x01_distrito?></strong>
      </font>
    </td>

    <td width=10% align="right" bgcolor="#CCCCCC">
      <!-- ZONA -->
      <?=$Lx01_zona?>
    </td>

    <td bgcolor="#FFFFFF">
    <font color="#666666">
        <strong><?=$x01_zona?></strong>
      </font>
    </td>
  </tr>

  <!-- QUADRA  :  NUMERO -->
  <tr>
    <td width=10% align="right" bgcolor="#CCCCCC">
      <?=$Lx01_quadra?>
    </td>

    <td bgcolor="#FFFFFF">
      <font color="#666666">
        <strong><?=$x01_quadra?></strong>
      </font>
    </td>

    <td width=10% align="right" bgcolor="#CCCCCC">
      <?=$Lx01_numero?>
    </td>

    <td bgcolor="#FFFFFF">
      <font color="#666666">
        <strong><?=$x01_numero?></strong>
      </font>
    </td>
  </tr>

  <!-- ORIENTACAO  :  ROTA -->
  <tr>
    <td width=10% align="right" bgcolor="#CCCCCC">
      <?=$Lx01_orientacao?>
    </td>
    <td bgcolor="#FFFFFF">
      <font color="#666666">
        <strong><?=$x01_orientacao?></strong>
      </font>
    </td>

    <td width=10% align="right" bgcolor="#CCCCCC">
      <?=$Lx01_rota?>
    </td>
    <td bgcolor="#FFFFFF">
    <font color="#666666">
        <strong><?=$x06_codrota?></strong>
      </font>
    </td>
  </tr>

  <!-- QTDECONOMIA  :  DTCADASTRO -->
  <tr>
  <td width=10% align="right" bgcolor="#CCCCCC">
    <?=$Lx01_qtdeconomia?>
  </td>
  <td bgcolor="#FFFFFF">
    <font color="#666666">
      <strong>
           <?=$x01_qtdeconomia?>
      </strong>
    </font>
  </td>

  <td width=10% align="right" bgcolor="#CCCCCC">
    <?=$Lx01_dtcadastro?>
  </td>
  <td bgcolor="#FFFFFF">
    <font color="#666666">
      <strong>
        <?=db_formatar($x01_dtcadastro, 'd')?>
      </strong>
    </font>
  </td>
  </tr>

  <!-- QTDPONTO  :  OBS -->
  <tr>
  <td width=10% align="right" bgcolor="#CCCCCC">
    <?=$Lx01_qtdponto?>
  </td>
  <td bgcolor="#FFFFFF">
    <font color="#666666">
      <strong>
           <?=$x01_qtdponto?>
      </strong>
    </font>
  </td>

  <td width=10% align="right" bgcolor="#CCCCCC">
    <?=$Lx01_obs?>
  </td>
  <td bgcolor="#FFFFFF">
    <font color="#666666">
      <strong>
        <?=$x01_obs?>
      </strong>
    </font>
  </td>
  </tr>

  <tr>
    <td colspan="4" align="left"><table width="100%" height="100%" border="0" align="left" cellpadding="0" cellspacing="0">
        <tr valign="top">
          <td width="16%"><table width="80%" border="0" cellspacing="2" cellpadding="0">
              <tr>
                <td title="Caracteristicas do Imóvel" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand" ><a href="agu3_conscadastro_002_detalhes.php?solicitacao=CaracteristicasDoImovel&parametro=<?=$x01_matric?>" target="iframeDetalhes">
          &nbsp;Caracter&iacute;sticas</a>
        </td>
              </tr>
              <tr>
                <td title="Isenções Lançadas" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="agu3_conscadastro_002_detalhes.php?solicitacao=Isencoes&parametro=<?=$cod_matricula?>" target="iframeDetalhes">
          &nbsp;Isen&ccedil;&otilde;es</a>
        </td>
              </tr>
              <tr>
                <td title="Construções" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="agu3_conscadastro_002_detalhes.php?solicitacao=Construcoes&parametro=<?=$cod_matricula?>" target="iframeDetalhes">
          Constru&ccedil;&otilde;es</a>
        </td>
              </tr>
              <tr>
                <td title="Endereço de Entrega do Carnê" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="agu3_conscadastro_002_detalhes.php?solicitacao=EnderecoDeEntrega&parametro=<?=$cod_matricula?>" target="iframeDetalhes">
          Endere&ccedil;o entrega</a>
        </td>
              </tr>

        <tr>
                <td title="Hidrômetros Cadastrados" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="agu3_conscadastro_002_detalhes.php?solicitacao=Hidrometro&parametro=<?=$cod_matricula?>" target="iframeDetalhes">
          Hidrometros</a>
        </td>
              </tr>

              <tr>
                <td title="Leituras efetuadas" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="agu3_conscadastro_002_detalhes.php?solicitacao=Leitura&parametro=<?=$cod_matricula?>" target="iframeDetalhes">
          Leituras</a>
        </td>
              </tr>

        <tr>
                <td title="Cálculo Efetuado no Exercício" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="agu3_conscadastro_002_detalhes.php?solicitacao=Calculo&parametro=<?=$cod_matricula?>" target="iframeDetalhes">
          C&aacute;lculo</a>
        </td>
              </tr>
              <tr>
                <td title="Imprime Boletim Informações do Imóvel" align="center" title="Imprime Boletim de Informações Cadastrais Completa" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href='' onClick="js_Impressao();return false;" >
          Imprime BIC</a>
        </td>
              </tr>
              <tr>
                <td title="Histórico de Cortes" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="agu3_conscadastro_002_detalhes.php?solicitacao=Corte&parametro=<?=$cod_matricula?>" target="iframeDetalhes">
          Hist&oacute;rico de Cortes</a>
        </td>
              </tr>
              <tr>
            <td title="Condomínio" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="agu3_conscadastro_002_detalhes.php?solicitacao=Condominio&parametro=<?=$cod_matricula?>" target="iframeDetalhes">
              Condomínio</a>
          </td>
          </tr>
        <tr>
           <td title="Ocorrências" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="agu3_conscadastro_002_detalhes.php?solicitacao=Ocorrencia&parametro=<?=$cod_matricula?>" target="iframeDetalhes">
              Ocorrências</a>
           </td>
        </tr>
        <tr>
           <td title="Ocorrências" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="agu3_conscadastro_002_detalhes.php?solicitacao=BaixaImoveis&parametro=<?=$cod_matricula?>" target="iframeDetalhes">
              Baixas de Imóveis</a>
           </td>
        </tr>

            </table></td>
          <td width="84%" align="left"> <iframe align="middle" height="100%" frameborder="0" marginheight="0" marginwidth="0" name="iframeDetalhes" width="100%">
            </iframe> </td>
        </tr>
      </table></td>
  </tr>



</table>
  <?
}  // fecha chave que mostra a descricao da propriedade
?>
</body>
</html>