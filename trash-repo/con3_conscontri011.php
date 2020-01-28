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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$sql = "select proprietario.*,ll.j34_descr , c.z01_nome as promitente, c.z01_ender as ender_promitente,
                                  j.z01_nome as imobiliaria,j.z01_ender as ender_imobiliaria, j.z01_numcgm as z01_numimob
                                  from proprietario
                                  left outer join cgm c on j41_numcgm = c.z01_numcgm              
                                  left outer join cgm j on j44_numcgm = j.z01_numcgm              
								  left outer join loteloteam l on l.j34_idbql = proprietario.j01_idbql
								  left outer join loteam ll on ll.j34_loteam = l.j34_loteam
 			                      where j01_matric = $cod_matricula limit 1";

$matriculaSelecionada = pg_exec($sql);
$numMatriculaSelecionada = pg_numrows($matriculaSelecionada);

 /***********************************************************************************************/
 // Verifica se encontrou a matrícula. Caso não tenha encontrado exibe a mensagem abaixo.

$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
?>
<html>
<head>
<title>Dados da matricula - BCI</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
if ($numMatriculaSelecionada == 0) {
   $db_erro = "Matrícula não cadastrada.";
?>
<center>
<table width="85%" border="1" cellpadding="0" cellspacing="0">
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
 db_fieldsmemory($matriculaSelecionada,0,true);
  ?>
<table width="750" height="100%" border="0" align="center" cellpadding="0" cellspacing="2">
  <tr bgcolor="#CCCCCC"> 
    <td colspan="4" align="center"><font color="#333333"><strong>&nbsp;DADOS CADASTRAIS 
      DO IM&Oacute;VEL (&nbsp; 
      <?=$j01_tipoimp?>
      &nbsp;)&nbsp;</strong>
	  <?
	  if(!empty($j01_baixa))
	     echo "</font><font color=\"red\"><strong> Baixada :".$j01_baixa."</strong></font>";
	  ?> 
	  </td>
  </tr>
  <tr> 
    <td width="73" align="right" nowrap bgcolor="#CCCCCC">Matr&iacute;cula:&nbsp;</td>
    <td width="275" align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp; 
      <?=$j01_matric?>
      &nbsp; </strong></font></td>
    <td width="114" align="right" nowrap bgcolor="#CCCCCC">Refer&ecirc;ncia anterior:&nbsp; 
    </td>
    <td width="278" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp; 
      <?=$j40_refant?>
      &nbsp; </strong></font></td>
  </tr>
  <tr> 
    <td width="73" align="right" bgcolor="#CCCCCC" title='Clique aqui para outros dados do contribuinte'>
      <?
      db_ancora($Lz01_nome,"js_JanelaAutomatica('cgm','$z01_cgmpri')",2);
      ?> 
    
    </td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp; 
      <?=$z01_nome?>
      &nbsp; </strong></font></td>
    <td width="73" align="right" nowrap bgcolor="#CCCCCC" title='Clique aqui para outros dados do contribuinte'>
    <? 
      db_ancora('Proprietário',"js_JanelaAutomatica('cgm','$z01_numcgm')",2);
    ?>
    &nbsp;</td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp; 
      <?=$proprietario?>
      &nbsp; </strong></font></td>
  </tr>
  <tr> 
    <td width="73" align="right" bgcolor="#CCCCCC">
    <? 
      db_ancora('Imobiliária',"js_JanelaAutomatica('cgm','$z01_numimob')",2);
    ?>
    &nbsp; </td>
    <td align="left" bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp; 
      <?=$imobiliaria?>
      &nbsp; </strong></font></td>
    <td align="right" bgcolor="#CCCCCC"></td>
    <td align="left" bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp; 
      &nbsp; </strong></font></td>
  </tr>
  <tr> 
    <td align="right" bgcolor="#CCCCCC">Setor:&nbsp;</td>
    <td bgcolor="#FFFFFF"><font color="#666666"><strong>&nbsp; 
      <?=$j34_setor?>
      /&nbsp; 
      <?=$j34_quadra?>
      / <font color="#666666"> 
      <?=$j34_lote?>
      &nbsp;&nbsp;&nbsp;&nbsp;&#8212;&nbsp;&nbsp;&nbsp;<strong><font color="#666666"> 
      <?=$j01_idbql?>
      </font></strong></font></strong></font></td>
    <td align="right" bgcolor="#CCCCCC">Area Levantada:</td>
    <td bgcolor="#FFFFFF"><font color="#666666"><strong>&nbsp; <font color="#666666"><strong><font color="#666666">
      <?=$j34_areal?>
      </font></strong></font></strong></font></td>
  </tr>
  <tr> 
    <td align="right" bgcolor="#CCCCCC">&Aacute;rea Real:&nbsp;</td>
    <td bgcolor="#FFFFFF"> <strong><font color="#666666"> &nbsp; <strong><font color="#666666"> 
      <?=$j34_area?>
      </font></strong></font></strong></td>
    <td align="right" bgcolor="#CCCCCC">Loteamento:</td>
    <td bgcolor="#FFFFFF"> <font color="#666666">&nbsp;<strong>
      <?=$j34_descr?>
      </strong></font></td>
  </tr>
  <tr> 
    <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="10%" align="right" nowrap bgcolor="#CCCCCC">Logradouro:&nbsp;</td>
          <td width="90%" align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp; 
            <?=$codpri?>
            - 
            <?=$nomepri?>
            , 
            <?=$j39_numero?>
            <?=($j39_compl != ""?"/":"")?> 
            <?=$j39_compl?>
            &nbsp; </strong></font></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="4" align="left">
      <table width="100%" height="100%" border="0" align="left" cellpadding="0" cellspacing="0">
        <tr valign="top"> 
          <td width="16%">
	    <table width="80%" border="0" cellspacing="2" cellpadding="0">
              <tr> 
                <td title="Outras contribuições" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand" >
		  <a href="con3_conscontri012.php?solicitacao=outras&contri=<?=$contri?>&matric=<?=$j01_matric?>" target="iframeDetalhes">
		  Outras contribuições </a>
	        </td>
              </tr>
              <tr> 
                <td title="Dados do edital" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand" >
		  <a href="con3_conscontri012.php?solicitacao=edital&contri=<?=$contri?>&matric=<?=$j01_matric?>" target="iframeDetalhes">
		  Dados do edital </a>
	        </td>
              </tr>
              <tr> 
                <td title="Dados da contribuição" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand" >
		  <a href="con3_conscontri012.php?solicitacao=contri&contri=<?=$contri?>&matric=<?=$j01_matric?>" target="iframeDetalhes">
		  Dados da contribuição </a>
	        </td>
              </tr>
              <tr> 
                <td title="Dados do lote" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand" >
		  <a href="con3_conscontri012.php?solicitacao=lote&contri=<?=$contri?>&matric=<?=$j01_matric?>" target="iframeDetalhes">
		  Dados do lote </a>
	        </td>
              </tr>
              <tr> 
                <td title="Dados do edital" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand" >
		  <a href="con3_conscontri012.php?solicitacao=valores&contri=<?=$contri?>&matric=<?=$j01_matric?>" target="iframeDetalhes">
		  Valores </a>
	        </td>
              </tr>
            </table>
	  </td>
          <td width="84%" align="left">
	    <iframe align="middle" height="100%" frameborder="0" marginheight="0" marginwidth="0" name="iframeDetalhes" width="100%"> 
            </iframe>
	  </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
  <?
}  // fecha chave que mostra a descricao da propriedade
?>
</body>
</html>