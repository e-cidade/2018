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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

require_once("classes/db_obrasresp_classe.php");
require_once("classes/db_obraspropri_classe.php");
require_once("classes/db_obraslote_classe.php");
require_once("classes/db_obraslotei_classe.php");
require_once("classes/db_obras_classe.php");
require_once("classes/db_obrasiptubase_classe.php");

require_once("dbforms/verticalTab.widget.php");

$clobrasresp     = new cl_obrasresp;
$clobraspropri   = new cl_obraspropri;
$clobraslote     = new cl_obraslote;
$clobraslotei    = new cl_obraslotei;
$clobras         = new cl_obras;
$clobrasiptubase = new cl_obrasiptubase;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrotulo = new rotulocampo;
$clrotulo->label("ob01_codobra");
$clrotulo->label("ob01_nomeobra");
$clrotulo->label("ob01_tiporesp");
$clrotulo->label("ob02_descr");
$clrotulo->label("ob10_numcgm");
$clrotulo->label("ob03_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("ob05_ibql");
$clrotulo->label("ob06_setor");
$clrotulo->label("ob06_quadra");
$clrotulo->label("ob06_lote");
$clrotulo->label("j34_setor");
$clrotulo->label("j34_quadra");
$clrotulo->label("j34_lote");

if (!empty($iCodigoObra)) {
  $sSqlObras    =  $clobras->sql_query($iCodigoObra, 
                                       "ob01_codobra,ob01_nomeobra,ob01_tiporesp,ob01_regular,ob02_descr", 
                                       "", 
                                       "ob01_codobra = $iCodigoObra");

  $rsObras = $clobras->sql_record($sSqlObras);

  if($clobras->numrows > 0){
    $oObras = db_utils::fieldsMemory($rsObras, 0);
  } 

  $sqlObrasPropri = $clobraspropri->sql_query("", "ob03_numcgm as numcgmpropri, z01_nome as proprietario ",
                                              "",
                                              "ob03_codobra = $iCodigoObra"
                                             ); 
  $rsObrasPropri  = $clobraspropri->sql_record($sqlObrasPropri);

  if($clobraspropri->numrows > 0){
    $oObrasPropri = db_utils::fieldsMemory($rsObrasPropri, 0);
  }

  $sqlObrasResp = $clobrasresp->sql_query("", "z01_nome as responsavel", "", " ob10_codobra = $iCodigoObra");
  $rsObrasResp  = $clobrasresp->sql_record($sqlObrasResp);

  if($clobrasresp->numrows > 0){
    $oObrasResp = db_utils::fieldsMemory($rsObrasResp, 0);
  }

}

/**
 * Verifica se obra é regular          clobrasiptubase
 */   
$iSetor     = "";
$iQuadra    = "";
$iLote      = "";
$iMatricula = "";
if($oObras->ob01_regular == 't') {

  $sTipoObra = 'REGULAR';

  $sqlIptuBase = $clobrasiptubase->sql_query(null, "ob24_iptubase,j34_setor, j34_quadra, j34_lote ",null, "ob24_obras = ".$iCodigoObra);
  $rsIptuBase  = $clobrasiptubase->sql_record($sqlIptuBase);

  if($clobrasiptubase->numrows > 0) {

    $oObrasIPTUBase = db_utils::fieldsMemory($rsIptuBase, 0);

    /**
     * S/Q/L da tabela obraslote
     */   
    $iSetor     = $oObrasIPTUBase->j34_setor;
    $iQuadra    = $oObrasIPTUBase->j34_quadra;
    $iLote      = $oObrasIPTUBase->j34_lote;
    $iMatricula = $oObrasIPTUBase->ob24_iptubase;
  }


} else{

  $sTipoObra = 'IREGULAR';
  
  /**
   * S/Q/L da tabela obraslotei
   */   
  $sSqlObrasLotei = $clobraslotei->sql_query($iCodigoObra, "ob06_setor,ob06_quadra,ob06_lote", "", "ob06_codobra = $iCodigoObra");
  $rsObrasLoteI  = $clobraslotei->sql_record($sSqlObrasLotei);

  if($clobraslotei->numrows > 0){
    $oObrasLoteI = db_utils::fieldsMemory($rsObrasLoteI, 0);
  }

  $iSetor  = $oObrasLoteI->ob06_setor;
  $iQuadra = $oObrasLoteI->ob06_quadra;
  $iLote   = $oObrasLoteI->ob06_lote;

}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
  <style>
    #elemento_principal {
      width: 100%;
    } 
    #elemento_principal tr td:first-child {
      width: 150px;
    }
  </style>
</head>
<body bgcolor=#CCCCCC onLoad="js_pesquisa();" >
  <center>
	<table width="800" border="0" cellspacing="0" cellpadding="0">
		<tr bgcolor="#CCCCCC">
      <td align="center" valign="top" bgcolor="#CCCCCC">
      
				<table width="790" border="0" >
				  <tr>
						<td>
							<fieldset>	
                <legend><b>Dados da obra:</b></legend>
								<table id="elemento_principal">
									<tr> 
										<td><strong>Código da Obra</strong></td>
                    <td nowrap bgcolor="#FFFFFF"><? echo $iCodigoObra?></td>
							    </tr>
                  <tr> 
										<td nowrap><strong>Nome da Obra: </strong></td>
                    <td bgcolor="#FFFFFF"><?php echo $oObras->ob01_nomeobra; ?></td>
									</tr>
									<tr> 
										<td><strong>Tipo de Obra</strong></td>
                    <td nowrap bgcolor="#FFFFFF"><?php echo $sTipoObra; ?></td>
							    </tr>
									<tr> 
										<td nowrap ><strong> Tipo do Responsável: </strong> </td>
                    <td nowrap bgcolor="#FFFFFF"> 
                      <?php echo $oObras->ob01_tiporesp ." - ". $oObras->ob02_descr; ?>
                    </td>
									</tr>
									<tr> 
										<td nowrap><strong>Reponsável da obra:</strong></td>
                    <td bgcolor="#FFFFFF"><?php echo $oObrasResp->responsavel; ?></td>
									</tr>
									<tr> 
										<td nowrap><strong>Proprietário da obra:</strong></td>
                    <td bgcolor="#FFFFFF"><?php echo $oObrasPropri->numcgmpropri . " - ". $oObrasPropri->proprietario; ?></td>
									</tr>
								</table>
							</fieldset>	
						</td>
					</tr>
					<tr width="100%"> 
					  <td colspan=4 width="100%" align="left">
						  <fieldset>  
                <legend><b>S/Q/L:</b></legend>
							<table border=0 width="100%" align="left">
							  <tr align="left"> 
<?php 
                  /**
                   * Se obra for regular mostra matricula
                   */   
                  if($oObras->ob01_regular == 't') { ?>

                    <td width="15%" align="left"><strong>Matricula:</strong></td>
                    <td width="10%" bgcolor="#FFFFFF"><?php echo $iMatricula; ?></td>

                    <?php 
                    /**
                     * Se obra for iregular mostra o código do lote
                     */   
                    } else { ?>

                    <td width="15%" align="left"><strong>Código do lote:</strong></td>
                    <td width="10%" bgcolor="#FFFFFF"><?php echo $oObrasLoteI->ob06_lote; ?></td>

                  <?php } ?>

                  <td width="15%" align="center" bgcolor="#CCCCCC"><strong>Setor: </strong></td>
								  <td width="10%" bgcolor="#FFFFFF"><?php echo $iSetor; ?></td>
                  <td width="15%" align="center" nowrap bgcolor="#CCCCCC"><strong>Quadra:</strong></td>
                  <td width="10%" align="left" nowrap bgcolor="#FFFFFF"><?php echo $iQuadra; ?></td>
								  <td width="15%" align="center" nowrap bgcolor="#CCCCCC"><strong>Lote:</strong></td>
								  <td width="10%" nowrap bgcolor="#FFFFFF"><?php echo $iLote; ?></td>
							  </tr>
						  </table>
						  </fieldset>  
						</td>
				  </tr>
				  <? 
					  if ((isset($iCodigoObra)) && (trim($iCodigoObra)!= "" )) {
				  ?>
				  <tr>
					  <td colspan="4" align="left">
              <fieldset>
                <legend><b>Detalhamento:</b></legend>
                <?
                $oTabDetalhes = new verticalTab("detalhes", 300);

                /**
                 * Aba construção
                 */   
                $oTabDetalhes->add("construcoesObra",
                                   "Dados da construção",
                                   "pro3_consultaobra002_construcao.php?parametro=".$iCodigoObra
                                  );

                /**
                 * Aba alvará
                 */   
                $oTabDetalhes->add("alvara",
                                   "Alvará",
                                   "pro3_consultaobra002_alvara.php?parametro=".$iCodigoObra
                                  );

                /**
                 * Aba habite-se
                 */   
                $oTabDetalhes->add("habite",
                                   "Habite-se",
                                   "pro3_consultaobra002_habite.php?parametro=".$iCodigoObra
                                  );

                /**
                 * Aba tecnico
                 */   
                $oTabDetalhes->add("tecnico",
                                   "Técnico",
                                   "pro3_consultaobra002_tecnico.php?parametro=".$iCodigoObra
                                  );

                $oTabDetalhes->show();
                ?>
	            </fieldset>
              <center>
                <input type="button" onClick="parent.db_iframe_consultaobra.hide();" value="Fechar" />
              </center>
            </td>
				  </tr>
			    <?
			      }
			    ?>
			  </table>
			</td>
	  </tr>
  </table>
  </center>
</body>
<script>
function js_pesquisa(){
<?
if(!isset($iCodigoObra) || trim($iCodigoObra)==""){
echo "
  js_OpenJanelaIframe('','db_iframe_obras','func_obras.php?funcao_js=parent.js_preenchepesquisa|ob01_codobra','Pesquisa',true);
";
}
?>
}
function js_preenchepesquisa(codigo){
   js_OpenJanelaIframe('','db_iframe_consultaobra', 'pro3_consultaobra002.php?codigo='+codigo,'.:Consulta Obras:.',true);
}
</script>

</html>