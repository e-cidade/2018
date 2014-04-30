<?php
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
require_once("std/db_stdClass.php");
require_once("libs/db_utils.php");
require_once("model/configuracao/DBEstrutura.model.php");
require_once("model/configuracao/DBEstruturaValor.model.php");

$oGet          = db_utils::postMemory($_GET);
$iInstituicao  = db_getsession("DB_instit");
$dtDataInicial = implode("/",array_reverse(explode('-', $oGet->dtDataInicial)));
$dtDataFinal   = implode("/",array_reverse(explode('-', $oGet->dtDataFinal)));

/**
 * Busca os dados relativos a instituição
 */
$oDaoInstit      = db_utils::getDao("db_config");
$oDaoDepart      = db_utils::getDao("db_depart");

/*
 * Validamos o vínculo com do tipo com o grupo de material.
 */
$oDaoMaterialTipoGrupoVinculo = db_utils::getDao('materialtipogrupovinculo');
$sSqlBuscaVinculoTipo         = $oDaoMaterialTipoGrupoVinculo->sql_query_file(null, "*", null, "m04_materialtipogrupo = 1");
$rsBuscaVinculoTipo           = $oDaoMaterialTipoGrupoVinculo->sql_record($sSqlBuscaVinculoTipo);
if ($oDaoMaterialTipoGrupoVinculo->numrows == 0) {
  
  $sMsg = _M('patrimonial.patrimonio.pat2_bensalmoxarifadoAnexoXX002.vinculo_nao_localizado');
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
  exit;
}
$oDadoVinculoTipo   = db_utils::fieldsMemory($rsBuscaVinculoTipo, 0);
$oEstruturaValor    = new DBEstruturaValor($oDadoVinculoTipo->m04_db_estruturavalor);
$oEstruturaValor->loadContasAnaliticas($oDadoVinculoTipo->m04_db_estruturavalor);
$aContasEncontradas = $oEstruturaValor->getContasAnaliticas();
$aContasBuscar      = array();
foreach ($aContasEncontradas as $oContaEstruturaValor) {
  $aContasBuscar[] = $oContaEstruturaValor->getCodigo();
}
$aWhereBuscaMaterial   = array();
$aWhereBuscaMaterial[] = "m65_db_estruturavalor in (".implode(', ',$aContasBuscar).")";
$aWhereBuscaMaterial[] = "(matestoqueitem.m71_data between '{$oGet->dtDataInicial}' and '{$oGet->dtDataFinal}')";
$aWhereBuscaMaterial[] = "db_depart.instit = {$iInstituicao}";

$sWhereBuscaMaterial = implode(" and ", $aWhereBuscaMaterial);

$sSqlBuscaMaterial  = "  select distinct "; 
$sSqlBuscaMaterial .= "         matmater.m60_codmater         as codigo_material,                  ";
$sSqlBuscaMaterial .= "         matmater.m60_descr            as descricao_material,               ";
$sSqlBuscaMaterial .= "         matunid.m61_descr             as descricao_unidade,                ";
$sSqlBuscaMaterial .= "         coalesce(matestoqueitem.m71_quant     , 0) as quantidade_entrada,  ";
$sSqlBuscaMaterial .= "         coalesce(matestoqueitem.m71_quantatend, 0) as quantidade_atendida, ";
$sSqlBuscaMaterial .= "         coalesce(matestoqueitem.m71_valor     , 0) as valor_total_entrada, ";
$sSqlBuscaMaterial .= "         (select coalesce(sum(m71_quant),0)                                 ";
$sSqlBuscaMaterial .= "           from matmater as mmater                                          ";
$sSqlBuscaMaterial .= "                inner join matestoque on m60_codmater   = m70_codmatmater   ";
$sSqlBuscaMaterial .= "                inner join matestoqueitem on m70_codigo = m71_codmatestoque ";
$sSqlBuscaMaterial .= "          where mmater.m60_codmater = matmater.m60_codmater                 ";
$sSqlBuscaMaterial .= "            and m71_data < '{$oGet->dtDataInicial}') as saldo_anterior      ";
$sSqlBuscaMaterial .= "    from matmater ";                                                                                                                                    
$sSqlBuscaMaterial .= "         inner join matmatermaterialestoquegrupo on matmatermaterialestoquegrupo.m68_matmater = matmater.m60_codmater                                   ";
$sSqlBuscaMaterial .= "         inner join materialestoquegrupo         on materialestoquegrupo.m65_sequencial       = matmatermaterialestoquegrupo.m68_materialestoquegrupo   ";
$sSqlBuscaMaterial .= "         inner join matunid                      on matunid.m61_codmatunid                    = matmater.m60_codmatunid                                 ";
$sSqlBuscaMaterial .= "         inner join matestoque                   on matestoque.m70_codmatmater                = matmater.m60_codmater                                   ";
$sSqlBuscaMaterial .= "         inner join db_depart                    on db_depart.coddepto                        = matestoque.m70_coddepto                                 ";
$sSqlBuscaMaterial .= "         inner join matestoqueitem               on matestoqueitem.m71_codmatestoque          = matestoque.m70_codigo                                   ";
$sSqlBuscaMaterial .= "         left  join matrequiitem                 on matrequiitem.m41_codmatmater              = matmater.m60_codmater                                   ";
$sSqlBuscaMaterial .= "         left  join matrequi                     on matrequi.m40_codigo                       = matrequiitem.m41_codmatrequi                            ";
$sSqlBuscaMaterial .= "                                                and matrequi.m40_data between '{$oGet->dtDataInicial}' and '{$oGet->dtDataFinal}'                       ";
$sSqlBuscaMaterial .= "   where {$sWhereBuscaMaterial} ";
$sSqlBuscaMaterial .= "order by matmater.m60_descr     ";
$rsBuscaMaterial = db_query($sSqlBuscaMaterial);
$iTotalMaterial  = pg_num_rows($rsBuscaMaterial); 

if ($iTotalMaterial == 0 || !$rsBuscaMaterial) {
  
  $sMsg = _M('patrimonial.patrimonio.pat2_bensalmoxarifadoAnexoXX002.nenhum_material_encontrado');
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
}

/*
 * Configuro o objeto com mais duas propriedades para serem impressas no relatório
 * valor unitario e quantidade do inventario
 */
$aDadosImprimir = array();
for ($iRowMaterial = 0; $iRowMaterial < $iTotalMaterial; $iRowMaterial++) {
  
  $oDadoMaterial                        = db_utils::fieldsMemory($rsBuscaMaterial, $iRowMaterial);
  $oDadoMaterial->valor_unitario        = ($oDadoMaterial->valor_total_entrada / $oDadoMaterial->quantidade_entrada);
  $oDadoMaterial->quantidade_inventario = ($oDadoMaterial->saldo_anterior + $oDadoMaterial->quantidade_entrada) 
                                           - $oDadoMaterial->quantidade_atendida;
  $aDadosImprimir[] = $oDadoMaterial;
}

/**
 * Prepara sql para buscar os dados relativos a instituição e municipio
 */
$sCamposInstit   = "nomeinst, ";
$sCamposInstit  .= "munic||' - '||uf as municipio ";
$sSqlInstituicao = $oDaoInstit->sql_query(null, $sCamposInstit, null, "codigo = {$iInstituicao}");

/**
 * Busca a Instituição
 **/
$rsInstit        = $oDaoInstit->sql_record($sSqlInstituicao);
$sInstituicao    = db_utils::fieldsMemory($rsInstit, 0)->nomeinst;
$sMunicipio      = db_utils::fieldsMemory($rsInstit, 0)->municipio;

/**
 * Busca Departamento
 **/
$sSqlDepart      = $oDaoDepart->sql_query_file(db_getsession("DB_coddepto"));
$rsDepart        = $oDaoDepart->sql_record($sSqlDepart);
$sDepartamento   = db_utils::fieldsMemory($rsDepart, 0)->descrdepto;

$sOrgao           = $sInstituicao;
$sUnidadeControle = $sDepartamento;
$iDepartamento    = db_getsession("DB_coddepto");

/**
 * Seta propriedades iniciais do PDF
 **/
$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(235);


$head1 = "               RELATÓRIO LEGAL MODELO 20";
$head2 = "BENS EM ALMOXARIFADO - ARROLAMENTO DAS EXISTÊNCIAS ";
$head3 = "EM {$dtDataFinal}";
$head4 = "\nOrgão / Entidade      : {$sOrgao}";
$head5 = "Município                   : {$sMunicipio}";
$head6 = "Unidade de Controle : {$sUnidadeControle}";

$iRodape      = 0;
$iAlturalinha = 4;
$iFonte       = 6;
$lImprime     = true;

$nValorAcumuladoTotal  = 0;
foreach ($aDadosImprimir as $iIndiceContas => $oConta) {
  
  if ( $oPdf->GetY() > $oPdf->h - 35 || $lImprime ) {

    if (!$lImprime) {
      imprimirRodape($oPdf, $iAlturalinha, $nValorAcumuladoTotal);
    }
    
    imprimirCabecalho($oPdf, $iAlturalinha, $dtDataInicial, $dtDataFinal);
    $lImprime = false;
  }

  //Acumula valor total
  $nValorAcumuladoTotal  +=  $oConta->valor_total_entrada;
  
  //Imprime colunas com valores do StdClass
  $oPdf->setfont('arial','',6);                                                                                   
  $oPdf->cell(20 ,  $iAlturalinha, $oConta->codigo_material                    , 1, 0, "C", 0); //Código de Classificaçãoo    
  $oPdf->cell(70 ,  $iAlturalinha, substr($oConta->descricao_material,0 , 40)  , 1, 0, "L", 0); //Especificação          
  $oPdf->cell(20 ,  $iAlturalinha, $oConta->descricao_unidade                  , 1, 0, "C", 0); //Unidade de Medida         
  $oPdf->cell(30 ,  $iAlturalinha, $oConta->saldo_anterior                     , 1, 0, "C", 0); //Saldo Periodo Anterior (quantidade) 
  $oPdf->cell(25 ,  $iAlturalinha, $oConta->quantidade_entrada                 , 1, 0, "C", 0); //Entradas Periodo       
  $oPdf->cell(25 ,  $iAlturalinha, $oConta->quantidade_atendida                , 1, 0, "C", 0); //SaidasPeriodo
  $oPdf->cell(30 ,  $iAlturalinha, $oConta->quantidade_inventario == '' ? "0" : $oConta->quantidade_inventario , 1, 0, "C", 0); //Quantidade Inventariada 
  $oPdf->cell(30 ,  $iAlturalinha, db_formatar($oConta->valor_unitario,"f")    , 1, 0, "R", 0); //Valor Unitario          
  $oPdf->cell(30 ,  $iAlturalinha, db_formatar($oConta->valor_total_entrada,"f")       , 1, 1, "R", 0); //Valor Total             

}
imprimirRodape($oPdf, $iAlturalinha, $nValorAcumuladoTotal);
//========  RODAPE FINAL COM ASSINATURAS  FIXO:
$oPdf->setfont('arial','b', 6);
$oPdf->Ln();
$oPdf->cell(90,  $iAlturalinha, "Elaborado por" , "LBTR",  0, "C", 0);
$oPdf->cell(90,  $iAlturalinha, "Conferido por" , "LBTR",  0, "C", 0);
$oPdf->cell(70,  $iAlturalinha, "Visto"         , "LBTR",  0, "C", 0);
$oPdf->cell(30,  $iAlturalinha, "Data"          , "LBTR",  1, "C", 0);

$oPdf->cell(90,  $iAlturalinha, "Nome", "LR",  0, "L", 0);
$oPdf->cell(90,  $iAlturalinha, ""    , "R" ,  0, "C", 0);
$oPdf->cell(70,  $iAlturalinha, ""    , "R" ,  0, "C", 0);
$oPdf->cell(30,  $iAlturalinha, ""    , "R" ,  1, "C", 0);

$oPdf->cell(90,  $iAlturalinha, "Matrícula", "LR",  0, "L", 0);
$oPdf->cell(90,  $iAlturalinha, ""         , "R" ,  0, "C", 0);
$oPdf->cell(70,  $iAlturalinha, ""         , "R" ,  0, "C", 0);
$oPdf->cell(30,  $iAlturalinha, ""         , "R" ,  1, "C", 0);

$oPdf->cell(90,  $iAlturalinha, "Assinatura", "LRB",  0, "L", 0);
$oPdf->cell(90,  $iAlturalinha, ""          , "RB" ,  0, "C", 0);
$oPdf->cell(70,  $iAlturalinha, ""          , "RB" ,  0, "C", 0);
$oPdf->cell(30,  $iAlturalinha, ""          , "RB" ,  1, "C", 0);

$oPdf->cell(280,  $iAlturalinha, "Correspondente ao modelo IGF/71" , "",  0, "R", 0);

$oPdf->Output();


function imprimirCabecalho ($oPdf, $iAlturalinha,$dtDataInicial, $dtDataFinal) {

  $oPdf->AddPage("L");
  
  //Primeira linha cabeçalho
  $oPdf->setfont('arial','b',8);
  $oPdf->cell(20 ,  $iAlturalinha, ""              , "LTR" ,  0, "C", 1);  //Código de Classificação     
  $oPdf->cell(70 ,  $iAlturalinha, ""              , "LTR" ,  0, "C", 1);  //Especificação               
  $oPdf->cell(20 ,  $iAlturalinha, "Unidade"       , "LTR" ,  0, "C", 1);  //Unidade de medida           
  $oPdf->cell(30 ,  $iAlturalinha, "Saldo do"      , "LTR" ,  0, "C", 1);  //Saldo do Período Anterior   
  $oPdf->cell(50 ,  $iAlturalinha, "Movimento de"  , "LTR" ,  0, "C", 1);  //Movimento de _/_/_ a _/_/_  
  $oPdf->cell(30 ,  $iAlturalinha, ""              , "LTR" ,  0, "C", 1);  //Quantidade
  $oPdf->cell(60 ,  $iAlturalinha, ""              , "LTR" ,  1, "C", 1);  //Valor
  
  //Segunda Linha cabeçalho
  $oPdf->cell(20 ,  $iAlturalinha, "Código da"       , "LR" ,  0, "C", 1); //Código de Classificação   
  $oPdf->cell(70 ,  $iAlturalinha, "Especificação"   , "LR" ,  0, "C", 1); //Especificação             
  $oPdf->cell(20 ,  $iAlturalinha, "de"              , "LR" ,  0, "C", 1); //Unidade de medida         
  $oPdf->cell(30 ,  $iAlturalinha, "Período"         , "LR" ,  0, "C", 1); //Saldo do Período Anterior 
  $oPdf->cell(50 ,  $iAlturalinha, "{$dtDataInicial} a {$dtDataFinal}" , "LR" ,  0, "C", 1); //Movimento de _/_/_ a _/_/_
  $oPdf->cell(30 ,  $iAlturalinha, "Quantidade"      , "LR" ,  0, "C", 1); //Quantidade                
  $oPdf->cell(60 ,  $iAlturalinha, "Valor R$"        , "LR" ,  1, "C", 1); //Valor                     
  
  //Terceira Linha cabeçalho
  $oPdf->cell(20 ,  $iAlturalinha, "Classificação"   , "LR" ,  0, "C", 1); //Código de Classificação
  $oPdf->cell(70 ,  $iAlturalinha, ""                , "LR" ,  0, "C", 1); //Especificação
  $oPdf->cell(20 ,  $iAlturalinha, "Medida"          , "LR" ,  0, "C", 1); //Unidade de medida
  $oPdf->cell(30 ,  $iAlturalinha, "Anterior"        , "LR" ,  0, "C", 1); //Saldo Periodo Anterior (quantidade) 
  $oPdf->setfont('arial','b',6);
  $oPdf->cell(50 ,  $iAlturalinha, "Em Quantidade"   , "LR" ,  0, "C", 1); //Movimento
  $oPdf->setfont('arial','b',8);
  $oPdf->cell(30 ,  $iAlturalinha, "Inventariada"    , "LR" ,  0, "C", 1); //Quantidade
  $oPdf->cell(60 ,  $iAlturalinha, ""                , "LR" ,  1, "C", 1); //Valor

  //Quarta Linha cabeçalho
  $oPdf->cell(20 ,  $iAlturalinha, ""            , "LBR" ,  0, "C", 1);  //Código de Classificaçãoo                                                   
  $oPdf->cell(70 ,  $iAlturalinha, ""            , "LBR" ,  0, "C", 1);  //Especificação                       
  $oPdf->cell(20 ,  $iAlturalinha, ""            , "LBR" ,  0, "C", 1);  //Unidade de Medida
  $oPdf->setfont('arial','b',6);
  $oPdf->cell(30 ,  $iAlturalinha, "(Quantidade)", "LBR" ,  0, "C", 1);  //Saldo Periodo Anterior (quantidade)
  $oPdf->setfont('arial','b',8);
  $oPdf->cell(25 ,  $iAlturalinha, "Entradas"    , "LBTR",  0, "C", 1); //Entradas Periodo                    
  $oPdf->cell(25 ,  $iAlturalinha, "Saídas"      , "LBTR",  0, "C", 1); //SaidasPeriodo                       
  $oPdf->cell(30 ,  $iAlturalinha, ""            , "LR"  ,  0, "C", 1);   //Quantidade Inventariada            
  $oPdf->cell(30 ,  $iAlturalinha, "Unitário"    , "LBTR",  0, "C", 1); //Valor Unitario                     
  $oPdf->cell(30 ,  $iAlturalinha, "Total"       , "LBTR",  1, "C", 1); //Valor Total                        
}

//=========  RODAPÉ COM TOTAL POR PAGINA
function imprimirRodape($oPdf,$iAlturalinha, $nValorAcumuladoTotal) {
  
  $oPdf->setfont('arial','b', 6);
  $oPdf->cell(140 , $iAlturalinha, "", 0, 0, "R", 0);
  $oPdf->cell(110 , $iAlturalinha, "A TRANSPORTAR/TOTAL", "LTBR" , 0, "R", 1);
  
  $oPdf->cell(30 , $iAlturalinha, db_formatar($nValorAcumuladoTotal  , "f") , "TBR"  , 1, "R", 0);

}
?>