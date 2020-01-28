<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/verticalTab.widget.php");
require_once("dbforms/db_funcoes.php");
require_once("model/CgmFactory.model.php");

/**
 * Carregamos o que foi passado no GET e verificamos se o numero do
 * CGM foi informado corretamente
 */
$oGet = db_utils::postMemory($_GET);
if (!isset($oGet->numcgm)) {

  db_redireciona("db_erros.php?fechar=true&db_erro=Número do CGM nao Informado");
  exit;
}

/**
 * Carregamos a DAO do CGM e verificamos a existência do CGM passado por GET
 */
$oDaoCgm      = db_utils::getDao('cgm');
$sSqlBuscaCGM = $oDaoCgm->sql_query_file($oGet->numcgm, "*", null, "");
$rsBuscaCGM   = $oDaoCgm->sql_record($sSqlBuscaCGM);
if ($rsBuscaCGM == false) {
  
  db_redireciona("db_erros.php?fechar=true&db_erro=Número do CGM Inválido");
  exit;
} else {
  $oCgm = db_utils::fieldsMemory($rsBuscaCGM, 0);
}

/**
 * Carregamos a CgmFactory que define se o usuário é Pessoa Física ou Jurídica
 * A factory retorna a instância correta do modelo (pessoa física ou jurídica)
 */
$oCgmModel = CgmFactory::getInstance('', $oCgm->z01_numcgm);

/**
 * Enter description here ...
 */
$clrotulo = new rotulocampo;
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_munic");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("z01_ender");
$clrotulo->label("z01_bairro");
$clrotulo->label("z01_nomefanta");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php 
  db_app::load("scripts.js, 
                prototype.js, 
                strings.js, 
                datagrid.widget.js, 
                dbmessageBoard.widget.js, 
                classes/dbViewCadastroDocumento.js, 
                widgets/windowAux.widget.js,
                widgets/dbtextField.widget.js,
                widgets/dbtextFieldData.widget.js");
  
  
  db_app::load("estilos.css,
                  grid.style.css,
               tab.style.css");
      
?>

<style type='text/css'>
.valores {background-color:#FFFFFF}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="" bgcolor="#cccccc">


	<fieldset>
		<legend><strong>Dados Cadastrais:</strong></legend>
		<table width="70%">
			<tr>
				<td>
					<?php
					  echo $Lz01_numcgm;
					?>
				</td>
				<td class="valores">
				  <?php 
				    echo $oCgmModel->getCodigo();
				  ?>
				</td>
				<td>
					<?php 
					  echo $Lz01_nome;
					?>
				</td>
				<td class="valores">
					<?php 
					  echo $oCgmModel->getNomeCompleto();
					?>
				</td>
				<td>
					<strong>
						<?php
							echo ($oCgmModel->isJuridico()) ? "CNPJ: " : "CPF: ";
						?>
					</strong>
				</td>
				<td class="valores">
				  <?php
					  echo ($oCgmModel->isJuridico()) ? $oCgmModel->getCnpj() : $oCgmModel->getCpf();
					?>
				</td>
			</tr>
			<?php if ($oCgmModel->isJuridico()) { ?>
			
  			<tr>
  			  <td>
  					<strong>Inscrição Estadual: </strong>
  				</td>
  				<td class="valores">
  					<?php 
  					  echo $oCgmModel->getInscricaoEstadual();
  					?>
  				</td>
  				<td>
  					<?php 
  					  echo $Lz01_nomefanta;
  					?>
  				</td>
  				<td class="valores">
  					<?php 
  					  echo $oCgmModel->getNomeFantasia();
  					?>
  				</td>
  				<td>
  					<strong>Contato: </strong>
  				</td>
  				<td class="valores">
  					<?php 
  					  echo $oCgmModel->getContato();
  					?>
  				</td>
  			</tr>
			<?php } else { ?>
  			<tr>
  				<td>
  					<strong>Nome Mãe: </strong>
  				</td>
  				<td class="valores">
  			  <?php
  			    echo $oCgmModel->getNomeMae();
  			  ?>
  			  </td>
  			  <td>
  			  	<strong>Nome Pai: </strong>
  			  </td>
  			  <td class="valores">
  			  	<?php 
			  			echo $oCgmModel->getNomePai();
			  		?>
			  	</td>
			  	<td>
			  		<strong>Data de Nascimento: </strong>
			  	</td>
			  	<td class="valores">
			  		<?php 
			  		  echo implode('/', array_reverse(explode('-', $oCgmModel->getDataNascimento())));
			  		?>
			  	</td>
			  </tr>
			  <tr>
			  	<td>
			  		<strong>Estado Civil: </strong>
			  	</td>
			  	<td class="valores">
			      <?php
			        echo $oCgmModel->getDescrEstadoCivil();
			      ?>
			    </td>
			    <td>
			     	<strong>Sexo: </strong>
			    </td>
			    <td class="valores">
			     	<?php 
			  			echo ($oCgmModel->getSexo() == "M") ? "Masculino" : "Feminino";
			  		?>
			  	</td>
			  	<td>
			  		<strong>Nacionalidade: </strong>
			  	</td>
			  	<td class="valores">
			  		<?php 
			  		  echo $oCgmModel->getDescrNacionalidade();
			  		?>
			  	</td>
			  </tr>
			<?php } ?>
			<tr>
				<td>
					<strong>Observação: </strong>
				</td>
				<td class="valores" colspan="5">
					<?php 
					  echo $oCgmModel->getObs();
					?>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset>
		<legend><strong>Detalhamento do Cadastro:</strong></legend>
		<?php 
		  $oTabDetalhes = new verticalTab('detalhesCGM', 300);
		  $sGetUrl      = "?cgm={$oGet->numcgm}";
		  
		  $oTabDetalhes->add('enderecoprincipal', 'Endereço Principal', 
		                     "prot3_consultacgmnovo003.php{$sGetUrl}");
		  
		  $oTabDetalhes->add('enderecosecundario', 'Endereço Secundário', 
		                     "prot3_consultacgmnovo004.php{$sGetUrl}");
		  
		  $oTabDetalhes->add('documemntos', 'Documentos',
		                     "prot1_lancdoc001.php?z06_numcgm={$oGet->numcgm}&consulta=true&createOnParent=true");

		  if ($oCgmModel->isFisico()) {
		    $oTabDetalhes->add('emprego', 'Emprego', "prot3_consultacgmnovo006.php{$sGetUrl}");
		  }
		  
		  $oTabDetalhes->add('outrasinformacoes', 'Outras Informações',
		                     "prot3_consultacgmnovo007.php{$sGetUrl}");
		  
		  $oTabDetalhes->show();
		?>
	</fieldset>
</body>
</html>