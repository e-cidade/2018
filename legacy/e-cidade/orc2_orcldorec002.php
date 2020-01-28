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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_orcppa_classe.php");
include("classes/db_orcppaval_classe.php");
include("classes/db_orcppatiporec_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clorcppa    = new cl_orcppa;
$clorcppaval = new cl_orcppaval;
$clorcppatiporec = new cl_orcppatiporec;
$clorcppa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('j14_nome');

$texto['0'] = 'Descrição das Contas Integradas do Cálculo';
$texto['1'] = 'RECEITAS FISCAIS';
$texto['2'] = 'RECEITAS FISCAIS CORRENTES(I)';
$texto['3'] = 'Receita Tributária';
$texto['4'] = 'Receita de Contribuições';
$texto['5'] = '  Receita Prevodenciária';
$texto['6'] = '  Outras Contribuições';
$texto['7'] = 'Receita Patrimonial Líquida';
$texto['8'] = '  Receita Patrimonial';
$texto['9'] = '  (-)Aplicações Financeiras';
$texto['10'] = 'Transferência Correntes';
$texto['11'] = 'Demais Receitas Correntes';
$texto['12'] = '  Dívida Ativa';
$texto['13'] = '  Demais Receitas Correntes';
$texto['14'] = 'RECITA DE CAPITAL(II)';
$texto['15'] = 'Opereções de Crédito(III)';
$texto['16'] = 'Amortização Empréstimos(IV)';
$texto['17'] = 'Alienação de Bens(V)';
$texto['18'] = 'Transferência de Capital';
$texto['19'] = 'Outras Receitas de Capital';
$texto['20'] = 'RECEITAS FISCAIS DE CAPITAL(VI)=(II - III - IV - V)';

$texto['21'] = 'RECEITAS FISCAIS LÍQUIDAS(VII)=(I+VI)';

$texto['22'] = 'DESPESAS FISCAIS';
$texto['23'] = 'DESPESAS CORRENTES(VIII)';
$texto['24'] = 'Pessoal e Encargos Sociais';
$texto['25'] = 'Juro e Encargos da Dívida(IX)';
$texto['26'] = 'Outras Despesas Correntes';
$texto['27'] = 'DESPESAS FISCAIS CORRENTES(X)=(VIII - IX)';

$texto['28'] = 'DESPESAS DE CAPITAL (XI)';
$texto['29'] = 'Investimentos';
$texto['30'] = 'Inversões Financeiras';
$texto['31'] = '  Concessão de Empréstimos (XII)';
$texto['32'] = '  Aquisição de Titulo de Capital já Integralizado(XIII)';
$texto['33'] = '  Demais Inversões Financeiras';
$texto['34'] = 'Amortização da Divida(XIV)';
$texto['35'] = 'DESPESAS FISCAIS DE CAPITAL (XV)=(XI - XII - XIII - XIV)';
$texto['36'] = 'RESERVA DE CONTIGÊNCIA(XVI)';
$texto['37'] = 'RESERVA DE CONTIGÊNCIA DO RPPS(XVII)';
$texto['38'] = 'DESPESAS FISCAIS LÍQUIDAS (XVIII) = (X + XV + XVI + XVII)';

$texto['39'] = 'RESULTADO PRIMARIO (VII - XVIII)';

?>