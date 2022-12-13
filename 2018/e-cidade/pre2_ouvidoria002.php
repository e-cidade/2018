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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_db_ouvidoria_classe.php");

$cl_db_ouvidoria = new cl_db_ouvidoria();

$oGet = db_utils::postMemory($_GET);
$id_ouvidoria = $oGet->idOuvidoria;

$sCampos  = "to_char(po01_data,'DD-MM-YYYY')      as data,                  ";
$sCampos .= "po01_nome                            as nome,                  ";
$sCampos .= "w03_tipo                             as categoria,             ";
$sCampos .= "po01_datanascimento                  as datanascimento,        ";
$sCampos .= "po01_sexo                            as sexo,                  ";
$sCampos .= "po01_profissao                       as profissao,             ";
$sCampos .= "po01_escolaridade                    as escolaridade,          "; 
$sCampos .= "po01_cpf                             as cpf,                   ";
$sCampos .= "po01_rg                              as rg,                    ";
$sCampos .= "po01_telefone                        as telefone,              ";
$sCampos .= "po01_celular                         as celular,               ";
$sCampos .= "po01_enderecoresidencial             as enderecoresidencial,   ";
$sCampos .= "po01_enderecocomercial               as enderecocomercial,     ";
$sCampos .= "po01_cidade                          as cidade,                "; 
$sCampos .= "db12_uf                              as estado,                ";
$sCampos .= "po01_sigilo                          as sigilo,                "; 
$sCampos .= "po01_resposta                        as resposta,              ";
$sCampos .= "po01_tiporesposta                    as tiporesposta,          ";
$sCampos .= "po01_assunto                         as assunto,               ";
$sCampos .= "po01_mensagem                        as comentario,            ";
$sCampos .= "po01_url01                           as url01,                 ";
$sCampos .= "po01_url02                           as url02,                 ";
$sCampos .= "po01_email                           as email,                 ";
$sCampos .= "to_char(po01_revisado,'DD-MM-YYYY')  as revisado,              ";
$sCampos .= "nome                      						as login,                 "; 
$sCampos .= "po01_texto                           as texto                  ";

$result = $cl_db_ouvidoria->sql_record($cl_db_ouvidoria->sql_query($id_ouvidoria,$sCampos));
$registros = pg_num_rows($result);
if ($registros == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Consulta sem registros.');
}

db_fieldsmemory($result,0);

$head2  = "RELATÓRIO DE OUVIDORIA";
$head4  = "OUVIDORIA " . $id_ouvidoria;

// Impressao da Pagina Principal do Formulario;
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$alt = 4;
    
$pdf->addpage();
//cell(poshor,posver,nome,borda,quebra,alinhamento,preenchimento)
$pdf->cell(0, $alt, "Solicitação", 1, 1, "L", 1);
$pdf->cell(60, $alt, "Data", 1, 0, "L", 0);
$pdf->cell(0, $alt, $data, 1, 1, "L", 0);
$pdf->cell(60, $alt, "Nome", 1, 0, "L", 1);
$pdf->cell(0, $alt, $nome, 1, 1, "L", 1);
$pdf->cell(60, $alt, "E-mail", 1, 0, "L", 0);
$pdf->cell(0, $alt, $email, 1, 1, "L", 0);
$pdf->cell(60, $alt, "Categoria", 1, 0, "L", 1);
$pdf->cell(0, $alt, $categoria, 1, 1, "L", 1);
$pdf->cell(60, $alt, "Deseja manter o nome e dados em sigilo?", 1, 0, "L", 0);
$x = array('f'=>'Não','t'=>'Sim');
$pdf->cell(0, $alt, $x[$sigilo], 1, 1, "L", 0);
$pdf->cell(60, $alt, "Deseja receber resposta?", 1, 0, "L", 1);
$pdf->cell(0, $alt, $x[$resposta], 1, 1, "L", 1);
$x = array('0'=>'Carta','1'=>'E-mail','2'=>'Telefone');
$pdf->cell(60, $alt, "Tipo de resposta", 1, 0, "L", 0);
$pdf->cell(0, $alt, $x[$tiporesposta], 1, 1, "L", 0);
$pdf->cell(60, $alt, "Data de nascimento", 1, 0, "L", 1);
$pdf->cell(0, $alt, $datanascimento, 1, 1, "L", 1);
$x = array('f'=>'Feminino','t'=>'Masculino');
$pdf->cell(60, $alt, "Sexo", 1, 0, "L", 0);
$pdf->cell(0, $alt, $x[$sexo], 1, 1, "L", 0);
$pdf->cell(60, $alt, "Profisão", 1, 0, "L", 1);
$pdf->cell(0, $alt, $profissao, 1, 1, "L", 1);
$x = array('0'=>'Não alfabetizado','1'=>'Nível fundamental','2'=>'Nível médio','3'=>'Graduado');
$pdf->cell(60, $alt, "Escolaridade", 1, 0, "L", 0);
$pdf->cell(0, $alt, $x[$escolaridade], 1, 1, "L", 0);
$pdf->cell(60, $alt, "CPF", 1, 0, "L", 1);
$pdf->cell(0, $alt, $cpf, 1, 1, "L", 1);
$pdf->cell(60, $alt, "RG", 1, 0, "L", 0);
$pdf->cell(0, $alt, $rg, 1, 1, "L", 0);
$pdf->cell(60, $alt, "Telefone", 1, 0, "L", 1);
$pdf->cell(0, $alt, $telefone, 1, 1, "L", 1);
$pdf->cell(60, $alt, "Celular", 1, 0, "L", 0);
$pdf->cell(0, $alt, $celular, 1, 1, "L", 0);
$pdf->cell(60, $alt, "Endereço residencial", 1, 0, "L", 1);
$pdf->cell(0, $alt, $enderecoresidencial, 1, 1, "L", 1);
$pdf->cell(60, $alt, "Endereço comercial", 1, 0, "L", 0);
$pdf->cell(0, $alt, $enderecocomercial, 1, 1, "L", 0);
$pdf->cell(60, $alt, "Cidade", 1, 0, "L", 1);
$pdf->cell(0, $alt, $cidade, 1, 1, "L", 1);
$pdf->cell(60, $alt, "Estado", 1, 0, "L", 0);
$pdf->cell(0, $alt, $estado, 1, 1, "L", 0);
$pdf->cell(60, $alt, "Assunto", 1, 0, "L", 1);
$pdf->cell(0, $alt, $assunto, 1, 1, "L", 1);
$pdf->cell(0, $alt, "Mensagem:", 1, 1, "L", 1);
$pdf->multiCell(0, $alt, $comentario, 1, "L", 0, 0);

if(!empty($texto)){
  $pdf->cell(0, $alt, "Resposta", 1, 1, "C", 1);
  $pdf->multiCell(0, $alt, $texto, 1, "L", 0, 0);
  $pdf->cell(60, $alt, "Data", 1, 0, "L", 1);
  $pdf->cell(0, $alt, $revisado, 1, 1, "L", 1);
  $pdf->cell(60, $alt, "Respondido por", 1, 0, "L", 0);
  $pdf->cell(0, $alt, $login, 1, 1, "L", 0);
}
$desArq = "FichaOuvidoria".$id_ouvidoria.".pdf";
$pdf->Output($desArq,"I");
?>