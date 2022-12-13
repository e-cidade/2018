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


/***********************************************************************
 * Relat�rio lista todos as contas da tabela custoplano,
 * custoplanoanalitica e custoplanoanaliticabens. Listando os bens
 * somente quando existir algum associado a sua conta anal�tica conforme
 * o filtro do relat�rio.
 ***********************************************************************/

include ("fpdf151/pdf.php");
include ("libs/db_utils.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_custoplano_classe.php");
include ("classes/db_custoplanoanaliticabens_classe.php");
include ("classes/db_db_depart_classe.php");

$cldb_depart               = new cl_db_depart;
$clcustoplano              = new cl_custoplano();
$clcustoplanoanaliticabens = new cl_custoplanoanaliticabens();

db_postmemory($_GET);

/**
* formata a string para ter o tamanho m�ximo de caracteres definido
* @param {string}  - string que ser� formatada
* @param {tamanho} - tamanho m�ximo que a string poder� ter em n�mero de caracteres
* @return string
*/
function tamanhoString($string, $tamanho) {
	// formata o tamanho da string 
  if ( strlen($string) > $tamanho ) {
  	// corta a string deixando-a com tamanho m�ximo de caracteres definido
	  $string = substr($string,0,$tamanho);
		$string .= "...";
	} 
  return $string;
}

$head1 = "PLANO DE CUSTOS";
$head3 = "INSTITUI��O: {$instit}";

$pdf = new PDF();
$pdf->open();
$pdf->aliasNbPages();
$pdf->setFillColor(235);
$pdf->setFont("arial", "b", 10);
$pdf->addPage("L");
$alt = 4;

// define os t�tulos principais adicionando-os embaixo do head da p�gina
$pdf->cell(40,  5, "Estrutural",  "BT",  0, "C", 1);
$pdf->cell(100, 5, "Descri��o",    1,    0, "C", 1);
$pdf->cell(50,  5, "Departamento", 1,    0, "C", 1);
$pdf->cell(87,  5, "Observa��o",  "BTL", 1, "C", 1);

// seta a fonte e seu tamanho
$pdf->setFont("arial", "", 7);

// consulta os custos sint�ticos e anal�ticos tendo como filtro a institui��o
$sSqlCustos = $clcustoplano->sql_query_analitica(null, "cc01_estrutural, 
                                                        cc01_descricao, 
                                                        cc01_obs, 
                                                        cc04_sequencial,
                                                        cc04_coddepto,
																												descrdepto",
                                                       "cc01_estrutural",
                                                       "cc01_instit = {$instit}" );
																											 
$rsConsultaConta = $clcustoplano->sql_record($sSqlCustos);

// quando n�o h� nenhum registro encaminha para tela informando o usu�rio
if ($clcustoplano->numrows == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=N�o existem registros cadastrados para dos dados fornecidos!");
}

$iNumLinhas =	$clcustoplano->numrows;

// faz o for varrendo todos �ndices das contas
for ($i=0; $i < $iNumLinhas; $i++) {	

  $oRetornoCustos = db_utils::fieldsMemory($rsConsultaConta, $i);		
 
  // retorna o n�vel da conta
  $sCustoNivel = $clcustoplano->sql_query_file(null,
	                            						    "fc_estrutural_nivel(cc01_estrutural) as nivel",
																					     null,
														 							    "cc01_estrutural = '{$oRetornoCustos->cc01_estrutural}' ");

  $rsConsultaNivel = $clcustoplano->sql_record($sCustoNivel);
  $oConsultaRecuo  = db_utils::fieldsMemory($rsConsultaNivel,0);

  /*
   * insere recuo conforme o n�mero do n�vel da conta
   * Exemplo: 
   * 
   * | estrutural | nivel |
   * |------------+-------|
   * | 01.00.00   | 1     |  primeiro n�vel sem recuo 
   * | 01.01.00   | 2     |  2 recuos 
   * | 01.01.01   | 3     |  3 recuos
   * | 01.01.02   | 3     |  3 recuos
   *
   */

  // restaura valor de origem	para o valor n�o ficar acumulativo
  $recuoEstrutural = 40;
  $recuoDescricao  = 100;

  if ($oConsultaRecuo->nivel > 1) { 
    // regula o recuo do relat�rio. Todas as contas que possuem um novo n�vel no seu estrutural ganham um recuo
	  $recuoEstrutural += $oConsultaRecuo->nivel;
		$recuoDescricao  -= $oConsultaRecuo->nivel;
  }

  if ($oRetornoCustos->cc04_coddepto != null) {	
	
	  // obt�m o coddepto e a descri��o do departamento	
		$cc04_coddepto = $oRetornoCustos->cc04_coddepto;
  	$descrdepto    = $oRetornoCustos->descrdepto;
	
	} else {
		$cc04_coddepto = "";
  	$descrdepto    = "";
	}
	
  // preenche o fundo cinza quando a conta for anal�tica
  $iPreenche = $oRetornoCustos->cc04_sequencial != null ? 1 : 0; 
	
  // exibe o estrutural e a descri��o das contas
	$pdf->cell($recuoEstrutural, $alt, "{$oRetornoCustos->cc01_estrutural}", 0, 0, "L", $iPreenche);
	$pdf->cell($recuoDescricao,  $alt, "{$oRetornoCustos->cc01_descricao}",  0, 0, "L", $iPreenche);

	// formata o tamanho m�ximo da string
  $sObservacoes = tamanhoString("{$oRetornoCustos->cc01_obs}", 55);
  $sDescrdepto  = tamanhoString("{$descrdepto}", 25);
		
	// exibe o c�digo do departamento e sua descri��o
	$pdf->cell(10, $alt, "{$cc04_coddepto}", 0, 0, "L", $iPreenche);
	$pdf->cell(40, $alt, "{$sDescrdepto}",   0, 0, "L", $iPreenche);
	$pdf->cell(87, $alt, "{$sObservacoes}",  0, 1, "L", $iPreenche);

  /*
  * 1 - verifica se a conta � anal�tica se for verifica se existem bens ligados a ela
	* 2 - verifica se o usu�rio selecinou a op��o de listar os bens das contas
	* obs: v�riavel listabens vem do $_GET 
	*/
  if ($oRetornoCustos->cc04_sequencial != null && $listabens == "s") {
    $sSqlBens = $clcustoplanoanaliticabens->sql_query(null,
		                                                 "t52_bem, 
																				              t52_descr",
		                                                  null,
		                                                 "cc05_custoplanoanalitica = {$oRetornoCustos->cc04_sequencial}");
			
		$rsBensConta = $clcustoplanoanaliticabens->sql_record($sSqlBens);
	  $iQtdBens = $clcustoplanoanaliticabens->numrows;
	
	  // se existir bens � feita a listagem de todos bens associados a conta anal�tica
	  if ($iQtdBens > 0) {

      // seta a fonte como negrita
      $pdf->setFont("arial", "B", 7);
			
			// coloca os t�tulos nas colunas dos bens da conta
			$pdf->cell(40, 4,  null,               0,    0, "L", 0);
			$pdf->cell(10, 4, "BEM",              "BTR", 0, "L", 0);
      $pdf->cell(87, 4, "DESCRI��O DO BEM", "BT",  1, "L", 0);

      // seta a o style da fonte null 
      $pdf->setFont("arial", "", 7);
			
		  // listamos os bens
      for ($chave=0; $chave < $iQtdBens; $chave++) {		
        $oRetornoBens = db_utils::fieldsMemory($rsBensConta, $chave);

				// exibe o conte�do das contas
				$pdf->cell(40,  $alt,  null,                        0, 0, "L", 0);
	      $pdf->cell(10,  $alt, "{$oRetornoBens->t52_bem}",   0, 0, "L", 0);
	      $pdf->cell(100, $alt, "{$oRetornoBens->t52_descr}", 0, 1, "L", 0);
      }
	  } 
  }
}

$pdf->Output();

?>