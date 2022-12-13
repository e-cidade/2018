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

if(@$cod_matricula!=""){
  $where = "j01_matric = $cod_matricula";
}elseif(@$cod_matricularegimo!=""){
  $where =  "j04_matricregimo = $cod_matricularegimo ";
}

$areaconst1 = 0;
$sql = "select proprietario.*, j50_descr,
               round(((round((select rnfracao
    				                    from fc_iptu_fracionalote(j01_matric,".db_getsession("DB_datausu").",true,false)),10) * lote.j34_area)/100),10) as area_matric,
							 ll.j34_descr ,
							 c.z01_nome as promitente,
							 c.z01_ender as ender_promitente,
							 j.z01_nome as imobiliaria,
							 j.z01_ender as ender_imobiliaria,
							 j.z01_numcgm as z01_numimob,
							 lote.j34_totcon,
							 iptubaseregimovel.*,
							 ruastipo.j88_descricao as ruadescricao
          from proprietario
        			 inner join lote              on proprietario.j01_idbql   = lote.j34_idbql
               left outer join cgm c        on j41_numcgm               = c.z01_numcgm
               left outer join cgm j        on j44_numcgm               = j.z01_numcgm
        			 left outer join loteloteam l on l.j34_idbql              = proprietario.j01_idbql
        			 left outer join loteam ll    on ll.j34_loteam            = l.j34_loteam
               left join iptubaseregimovel  on j01_matric               = j04_matric
        			 left join zonas              on lote.j34_zona            = zonas.j50_zona
        			 left join ruas               on proprietario.j14_codigo  = ruas.j14_codigo
               left join ruastipo           on ruastipo.j88_codigo      = ruas.j14_tipo
 			   where $where limit 1";

$matriculaSelecionada    = db_query($sql) or die($sql);
$numMatriculaSelecionada = pg_numrows($matriculaSelecionada);

if($numMatriculaSelecionada > 0){

  db_fieldsmemory($matriculaSelecionada,0);
  $cod_matricula= $j01_matric;
}

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
<script>
function js_Impressao1() {
  window.open('cad3_conscadastro_impressao.php?tipo=1&parametro=<?=$cod_matricula?>','','location=0,HEIGHT=600,WIDTH=600');
}

function js_Impressao() {
  var geracalculo = confirm('Imprimir Demonstrativo de Cálculo?');
  window.open('cad3_conscadastro_impressao.php?tipo=2&geracalculo='+geracalculo+'&parametro=<?=$cod_matricula?>','','location=0,HEIGHT=600,WIDTH=600');
}
</script>
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

 $sqlareatotal = "
           select sum(j34_area) as areatotal from
            (
           select distinct j34_idbql, j34_area
           from lote
           inner join iptubase on  j01_idbql = j34_idbql
           where j34_setor = '$j34_setor'
             and j34_quadra = '$j34_quadra'
             and j34_lote = '$j34_lote'
             and j01_baixa is null
            ) as x";

  $resultareatotal = db_query($sqlareatotal);
  $linhasareatotal = pg_num_rows($resultareatotal);
  if ($linhasareatotal>0){
	db_fieldsmemory($resultareatotal,0);
  }

$sqlareaconst = "
	select sum(j39_area) as areaconst
  	from iptuconstr
    inner join iptubase on j01_matric = j39_matric
	where j39_matric = $cod_matricula
   	and j39_dtdemo is null
   	and j01_baixa is null";

$resultareaconst = db_query($sqlareaconst);
$linhasareaconst = pg_num_rows($resultareaconst);
if ($linhasareaconst>0){
	db_fieldsmemory($resultareaconst,0);
}

  ?>
<table width="790" height="100%" border="0" align="center" cellpadding="0" cellspacing="2">
  <tr bgcolor="#CCCCCC">
    <td colspan="6" align="center"><font color="#333333"><strong>&nbsp;DADOS CADASTRAIS
      DO IM&Oacute;VEL (&nbsp;<?=$j01_tipoimp?> &nbsp;)&nbsp;</strong>
	  <?
	  if(!empty($j01_baixa))
	     echo "</font><font color=\"red\"><strong> Matrícula Baixada </strong></font>";
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
    <td width="278" nowrap bgcolor="#FFFFFF" colspan="3"> <font color="#666666"><strong>&nbsp;
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
    <td align="left" nowrap bgcolor="#FFFFFF" colspan="3"> <font color="#666666"><strong>&nbsp;
      <?=$proprietario?>
      &nbsp; </strong></font></td>
  </tr>
  <?
  $sqlimo = "select * from imobil where j44_matric = $cod_matricula";
  $resultimo = db_query($sqlimo);
  $linhasimo = pg_numrows($resultimo);
  ?>
  <tr>
    <td width="73" align="right" bgcolor="#CCCCCC">
    <?
      if ($linhasimo > 0){
      db_ancora('Imobiliária',"js_JanelaAutomatica('cgm','$z01_numimob')",2);
    ?>
    &nbsp; </td>
    <td align="left" bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;
      <?=$imobiliaria?>
      &nbsp; </strong></font></td>
      <?
      }else{
      ?>
      	Imobiliária &nbsp; </td>
    <td align="left" bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;
      Matricula sem Imobiliária vinculada.
      &nbsp; </strong></font></td>
      <?
      }
      ?>

    <td align="right" bgcolor="#CCCCCC" width="35">Zona Fiscal:</td>
    <td align="left" bgcolor="#FFFFFF" width="35"><font color="#666666"><strong>&nbsp;<?=$j34_zona  . " - " . $j50_descr?></strong></font></td>
    <?
    $rsSetfis = db_query("select * from lotesetorfiscal inner join cadastro.setorfiscal on j90_codigo = j91_codigo inner join iptubase on j01_idbql = j91_idbql where j01_matric = $cod_matricula");
    if (pg_numrows($rsSetfis)>0){
    	db_fieldsmemory($rsSetfis,0);
    }
    ?>
    <td align="right" bgcolor="#CCCCCC" width="35"colspan="">Setor Fiscal:</td>
    <td align="left" bgcolor="#FFFFFF" width="35"> <font color="#666666"><strong>&nbsp;<?=@$j91_codigo . " - " . @$j90_descr?></strong></font></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#CCCCCC">Setor/quadra/lote:&nbsp;</td>
    <td bgcolor="#FFFFFF"><font color="#666666" colspan="2"><strong>&nbsp;
      <?=$j34_setor?>
      /&nbsp;
      <?=$j34_quadra?>
      / <font color="#666666">
      <?=$j34_lote?>
      <?
       include("classes/db_setor_classe.php");
       $clsetor = new cl_setor;

       $rsSetor = $clsetor->sql_record($clsetor->sql_query_file($j34_setor, 'j30_descr'));
       if( $clsetor->numrows > 0 ) {
         db_fieldsmemory($rsSetor, 0);
         echo " - ".trim($j30_descr)." ";
       }

        if(@$areaconst>0){
        	$areaconst1 = db_formatar($areaconst,"f");
        }
        if ($j34_totcon>0){
        	$j34_totcon1= db_formatar($j34_totcon,"f");
        }
      ?>
      <strong><font color="#666666">
      </font></strong></font></strong></font></td>
    <td align="right" bgcolor="#CCCCCC">Construído no lote:</td>
    <td bgcolor="#FFFFFF" colspan="3"><font color="#666666"><strong>&nbsp; <font color="#666666"><strong><font color="#666666">
      <?=$areaconst1?> -  &Aacute;rea real construida no lote:<?=@$j34_totcon1?>
      </font></strong></font></strong></font></td>
  </tr>

<?
if (@$areatotal>0){
	$areatotal1= db_formatar($areatotal,"f");
}
?>

  <tr>
    <td align="right" bgcolor="#CCCCCC">&Aacute;rea do lote:&nbsp;</td>
    <td bgcolor="#FFFFFF"> <strong><font color="#666666"> &nbsp; <strong><font color="#666666">
      <?=db_formatar($area_matric,"f")?> -  &Aacute;rea real do lote:<?=@$areatotal1?>
      </font></strong></font></strong></td>
    <td align="right" bgcolor="#CCCCCC">Loteamento:</td>
    <td bgcolor="#FFFFFF" colspan="3"> <font color="#666666">&nbsp;<strong>
      <?=$j34_descr?>
      </strong></font></td>
  </tr>

  <tr>
    <td align="right" bgcolor="#CCCCCC">Logradouro:&nbsp; </td>

    <td bgcolor="#FFFFFF">
      <font color="#666666"> &nbsp;
        <strong>
          <?=$codpri .'- '. $tipopri.', ' . $nomepri .', '. $j39_numero?>
          <?
            if (isset($j39_compl) && $j39_compl != '') {

            	echo ", {$j39_compl}";
            }
          ?>
        </strong>
      </font>
    </td>

    <td align="right" bgcolor="#CCCCCC">Setor/Quadra/Lote de localiza&ccedil;&atilde;o</td>
    <td bgcolor="#FFFFFF" colspan="4">
      <font color="#666666"> &nbsp;
        <strong>
          <?
            //busca informações do loteloc se o campo j18_utilizaloc da tabela cfiptu estiver habilitado
            include("classes/db_loteloc_classe.php");
            include("classes/db_cfiptu_classe.php");

            $clloteloc = new cl_loteloc;
            $clcfiptu  = new cl_cfiptu;
            $utilizaloc = $clcfiptu->sql_record($clcfiptu->sql_query_file("","j18_utilizaloc","","j18_anousu = ".db_getsession("DB_anousu")));

            if ($clcfiptu->numrows > 0) {
              db_fieldsmemory($utilizaloc,0);
            } else {
              $j18_utilizaloc = 'f';
            }
            if($j18_utilizaloc != 'f'){
              $resultloc = $clloteloc->sql_record($clloteloc->sql_query($j01_idbql,"j05_codigoproprio, j05_descr, j06_setorloc,j06_quadraloc,j06_lote"));
              if($clloteloc->numrows > 0){
                db_fieldsmemory($resultloc,0);
                echo $j05_codigoproprio . ' - ' . $j05_descr."/".$j06_quadraloc."/".$j06_lote;
              }
            }
          ?>
        </strong>
      </font>
    </td>
  </tr>

  <tr>
  <td align="right" bgcolor="#CCCCCC">Bairro:&nbsp;</td>
    <td bgcolor="#FFFFFF">
      <font color="#666666">
        <strong><?=$j13_descr?></strong>
      </font>
    </td>

  </tr>

  <Tr>
    <Td colspan="6" align="left"><table width="100%" height="100%" border="0" align="left" cellpadding="0" cellspacing="0">
        <tr valign="top">
          <td width="16%"><table width="80%" border="0" cellspacing="2" cellpadding="0">
              <tr>
                <td title="Caracteristicas do Lote do Imóvel" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand" ><a href="cad3_conscadastro_002_detalhes.php?solicitacao=CaracteristicasDoImovel&parametro1=<?=$j01_idbql?>" target="iframeDetalhes">
                &nbsp;Caracter&iacute;sticas do im&oacute;vel</a></td>
              </tr>
              <tr>
                <td title="Isenções Lançadas" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="cad3_conscadastro_002_detalhes.php?solicitacao=Isencoes&parametro=<?=$cod_matricula?>" target="iframeDetalhes">&nbsp;Isen&ccedil;&otilde;es</a></td>
              </tr>
              <tr>
                <td title="Construções ativas" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="cad3_conscadastro_002_detalhes.php?solicitacao=Construcoes&parametro=<?=$cod_matricula?>" target="iframeDetalhes">Constru&ccedil;&otilde;es
                  ativas</a></td>
              </tr>
              <tr>
                <td title="Construções demolidas" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="cad3_conscadastro_002_detalhes.php?solicitacao=Construcoesdemolidas&parametro=<?=$cod_matricula?>" target="iframeDetalhes">Constru&ccedil;&otilde;es demolidas</a></td>
              </tr>
              <tr>
                <td title="Construções Escrituradas" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="cad3_conscadastro_002_detalhes.php?solicitacao=ConstrucoesEscrituradas&parametro=<?=$cod_matricula?>" target="iframeDetalhes">Constru&ccedil;&otilde;es
                  escrituradas</a></td>
              </tr>
              <tr>
                <td title="Testadas do Lote (Logradouros)" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="cad3_conscadastro_002_detalhes.php?solicitacao=Testada&parametro=<?=$j01_idbql?>" target="iframeDetalhes">&nbsp;Testada</a></td>
              </tr>
              <tr>
                <td title="Testadas Internas (Entre Lotes)" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="cad3_conscadastro_002_detalhes.php?solicitacao=TestadasInternas&parametro=<?=$j01_idbql?>" target="iframeDetalhes">Testadas
                  internas </a></td>
              </tr>
              <tr>
                <td title="Fórmula do Cálculo" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="cad3_conscadastro_002_detalhes.php?solicitacao=Imagens&parametro=<?=$cod_matricula?>" target="iframeDetalhes">Demonstrativo
                  de C&aacute;lculo</a></td>
              </tr>
              <tr>
                <td title="Endereço de Entrega do Carnê" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="cad3_conscadastro_002_detalhes.php?solicitacao=EnderecoDeEntrega&parametro=<?=$cod_matricula?>" target="iframeDetalhes">Endere&ccedil;o
                  de entrega</a></td>
              </tr>
              <tr>
                <td title="Outros Proprietários do Imóvel" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="cad3_conscadastro_002_detalhes.php?solicitacao=OutrosProprietarios&parametro=<?=$cod_matricula?>" target="iframeDetalhes">&nbsp;Outros
                  propriet&aacute;rios</a></td>
              </tr>
              <tr>
                <td title="Promitentes Compradores Lançados" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="cad3_conscadastro_002_detalhes.php?solicitacao=OutrosPromitentes&parametro=<?=$cod_matricula?>" target="iframeDetalhes">&nbsp;Promitentes Compradores</a></td>
              </tr>
               <tr>
                <td title="Lista de ITBI" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="cad3_conscadastro_002_detalhes.php?solicitacao=ListaITBI&parametro=<?=$cod_matricula?>" target="iframeDetalhes">Lista de ITBI</a></td>
              </tr>
              <tr>
                <td title="Averbações Efetuadas no Imóvel" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="cad3_conscadastro_002_detalhes.php?solicitacao=Averbacao&parametro=<?=$cod_matricula?>" target="iframeDetalhes">Averba&ccedil;&atilde;o</a></td>
              </tr>
              <tr>
                <td title="Cálculo Efetuado no Exercício" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="cad3_conscadastro_002_detalhes.php?solicitacao=Calculo&parametro=<?=$cod_matricula?>" target="iframeDetalhes">C&aacute;lculo</a></td>
              </tr>
              <tr>
                <td title="Outros dados" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="cad3_conscadastro_002_detalhes.php?solicitacao=outros&parametro=<?=$cod_matricula?>" target="iframeDetalhes">Outros dados</a></td>
              </tr>
              <tr>
                <td title="Imprime Boletim Informações do Imóvel" align="center" title="Imprime Boletim de Informações Cadastrais Completa" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href='' onClick="js_Impressao();return false;" >Imprime BIC Completa</a></td>
              </tr>
              <tr>
                <td title="Imprime Boletim Informações do Imóvel Resumido" align="center" title="Imprime Boletim de Informações Cadastrais Resumida"nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href='' onClick="js_Impressao1();return false;" >Imprime BIC Resumida</a></td>
              </tr>
              <tr>
                <td title="Imprime Boletim de Informações Cadastrais Modelo Novo" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href='cad3_conscadastrodetalhesmodelonovo001.php?matricula=<?=$cod_matricula?>' target="iframeDetalhes">Imprime BIC - Modelo Novo</a></td>
              </tr>
              <tr>
                <td title="Ocorrências" align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="agu3_conscadastro_002_detalhes.php?solicitacao=Ocorrencia&parametro=<?=$cod_matricula?>" target="iframeDetalhes">Ocorrências</a></td>
              </tr>
              <?
					    $sqlreg = " select * from iptubaseregimovel where j04_matric = $j01_matric";
					    $resultreg = db_query($sqlreg);
					    $linhasreg = pg_num_rows($resultreg);
					    if($linhasreg>0){
					      db_fieldsmemory($resultreg,0);
					      ?>
              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand">
                <a href="cad3_conscadastro_002_detalhes.php?solicitacao=RegistroImovel&parametro=<?=$j04_sequencial?>" target="iframeDetalhes">
                Dados do Registro de Imóveis</a></td>
              </tr>
							<?
							}
							if(!empty($j01_baixa)){?>
                <tr>
                  <td align="center"nowrap bgcolor="#CCCCCC" style="cursor:hand">
								    <a href="cad3_conscadastro_002_detalhes.php?solicitacao=dadosbaixa&parametro=<?=$cod_matricula?>" target="iframeDetalhes" >Dados da Baixa</a>
								  </td>
                </tr>
							<?}?>
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