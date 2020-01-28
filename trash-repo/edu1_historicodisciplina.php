<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("model/educacao/DBEducacaoTermo.model.php");

db_postmemory($HTTP_POST_VARS);

$oDaoHistorico  = new cl_historico();
$clhistmpsdisc  = new cl_histmpsdisc;
$clhistoricomps = new cl_historicomps;
$oDaoDisciplina = new cl_disciplina();

?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style>
  .titulo {

    font-size: 11;
    color: #DEB887;
    background-color:#444444;
    font-weight: bold;

  }

  .cabec1 {

    font-size: 11;
    color: #000000;
    background-color:#999999;
    font-weight: bold;

  }

  .aluno {

    color: #000000;
    font-family : Tahoma;
    font-size: 10;

  }

 </style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
 <?
$campos = "ed65_i_codigo,
           ed232_c_descr,
           ed65_c_situacao,
           ed65_t_resultobtido,
           case when ed65_c_situacao = 'AMPARADO' OR ed65_c_situacao = 'N�O OPTANTE'
                     then null
                     else ed65_c_resultadofinal
                 end as ed65_c_resultadofinal,
           ed65_i_qtdch,
           ed65_c_tiporesultado,
           ed65_i_historicomps,
           ed29_c_descr,
           ed11_c_descr,
           ed11_i_ensino,
           ed62_i_anoref,
           ed65_i_ordenacao,
           ed65_c_termofinal
          ";
$sSqlHistMpsDisc = $clhistmpsdisc->sql_query_historico(null,
                                                       $campos,
                                                       "ed65_i_ordenacao",
                                                       " ed65_i_historicomps = $ed65_i_historicomps"
                                                      );
$rsHistMpsDisc   = $clhistmpsdisc->sql_record($sSqlHistMpsDisc);

if ($clhistmpsdisc->numrows == 0) {

  $sWhereHistoricoMps = " ed62_i_codigo = $ed65_i_historicomps";
  $sSqlHistoricoMps   = $clhistoricomps->sql_query("","ed29_c_descr,serie.ed11_c_descr","",$sWhereHistoricoMps);
  $rsHistMpsDisc      = $clhistoricomps->sql_record($sSqlHistoricoMps);

}

?>
<?
  if ($rsHistMpsDisc) {
    db_fieldsmemory($rsHistMpsDisc,0);
  ?>
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
     <tr class='titulo'>
      <td colspan="7">
       <?=$ed11_c_descr?> - <?=$ed29_c_descr?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       <input name="ordenar" type="button" value="Ordenar Disciplinas" onclick="js_abrir();">
      </td>
     </tr>
     <tr class='titulo'>
      <td>Disciplina</td>
      <td>Situa��o</td>
      <td>Aprov.</td>
      <td>RF</td>
      <td>CH</td>
      <td>TR</td>
      <td>TF</td>
     </tr>
    <?

      if ($clhistmpsdisc->numrows > 0) {

        $cor1 = "#f3f3f3";
        $cor2 = "#DBDBDB";
        $cor  = "";

        for ($x = 0; $x < $clhistmpsdisc->numrows; $x++) {

          db_fieldsmemory($rsHistMpsDisc,$x);
          if ($cor == $cor1) {
            $cor = $cor2;
          } else {
            $cor = $cor1;
          }
          $ed65_t_resultobtido = @$ed65_t_resultobtido;
          if (trim($ed65_c_situacao) == "AMPARADO") {
            $ed65_t_resultobtido = "&nbsp;";
          }
    ?>
          <tr height="18" bgcolor="<?=$cor?>"
              onclick="parent.dados.location.href='edu1_histmpsdisc002.php?ed65_i_historicomps=<?=$ed65_i_historicomps?>'"
              onmouseover="bgColor='#DEB887';" onmouseout="bgColor='<?=$cor?>';">
           <td class='aluno'><?=$ed232_c_descr?></td>
           <td class='aluno'><?=$ed65_c_situacao?></td>
           <td class='aluno' align="center"><?=$ed65_t_resultobtido?></td>
           <?
             $sResultadoFinal = '';
             if (!empty($ed11_i_ensino) &&
                 ($ed65_c_resultadofinal == 'A' || $ed65_c_resultadofinal == 'R' || $ed65_c_resultadofinal == 'P')) {

               $aDadosTermo     = DBEducacaoTermo::getTermoEncerramento($ed11_i_ensino, $ed65_c_resultadofinal, $ed62_i_anoref);
               if (count($aDadosTermo) > 0) {

                 if (isset($aDadosTermo[0])) {
                   $sResultadoFinal = $aDadosTermo[0]->sDescricao;
                 }
               } else {

                 if ($ed65_c_resultadofinal == 'A') {
                   $sResultadoFinal = 'APROVADO';
                 } else if ($ed65_c_resultadofinal == 'P') {
                   $sResultadoFinal = 'APROVADO PARCIALMENTE';
                 } else {
                   $sResultadoFinal = 'REPROVADO';
                 }
               }
             }
           ?>
           <td class='aluno'><?=$sResultadoFinal?></td>
           <td class='aluno' align="right"><?=$ed65_i_qtdch?></td>
           <td class='aluno' align="right"><?=trim($ed65_c_tiporesultado)?></td>
           <td class='aluno' align="right"><?=trim($ed65_c_termofinal)?></td>
          </tr>
          <?
        }
      } else {
      ?>
        <tr height="18" bgcolor="#f3f3f3">
         <td colspan="6" class="aluno" align="center">Nenhuma disciplina cadastrada para esta etapa.</td>
        </tr>
      <?
      }
      ?>
    </table>
<?}?>
 </body>
</html>
<script>
function js_abrir() {

  parent.js_OpenJanelaIframe('','db_iframe_ordenar',
		                     'edu1_baseorddischistmps001.php?ed65_i_historicomps=<?=$ed65_i_historicomps?>',
		                     'Ordenar Disciplinas ',true,60,400,400,230
		                    );

}
</script>