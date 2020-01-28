<?
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

include("fpdf151/pdf.php");
require_once('libs/db_utils.php');
require_once('libs/db_stdlibwebseller.php');
require_once('libs/db_stdlib.php');
db_postmemory($HTTP_POST_VARS);
$iPaciente          = 1;
$aIni               = explode("/", $dIni);
$aFim               = explode("/", $dFim);
$dDateInicio        = "$aIni[2]-$aIni[1]-$aIni[0]";
$dDateFim           = "$aFim[2]-$aFim[1]-$aFim[0]";
$sWhere1            = " fa04_d_data between '$dDateInicio' and '$dDateFim' ";

$lExisteDep         = false;

if (isset($sMedicamentos) && $sMedicamentos != "") {
  $sWhere1 .= " and fa06_i_matersaude in ($sMedicamentos) ";
}

if (isset($iIdadeIni) && $iIdadeIni != ""){

  $iTimeStamp    = mktime(0,  0, 0, date("m", db_getsession("DB_datausu")),
                                      date("d",  db_getsession("DB_datausu")),
                                      date("Y",  db_getsession("DB_datausu")));
  $iTimeStampIni = $iTimeStamp-($iIdadeIni*29030400);
  $aIni          = explode("/", date("d/m/Y", $iTimeStampIni));
  $iTimeStampFim = $iTimeStamp-($iIdadeFim*29030400);
  $aFim          = explode("/", date("d/m/Y", $iTimeStampFim));
  $sWhere1      .= " and z01_d_nasc between '$aFim[2]-$aFim[1]-$aFim[0]' and '$aIni[2]-$aIni[1]-$aIni[0]' ";

}
 //caso o usuario defina os almoxerifados,  inclui na pesquisa aqueles selecionados;
if ($sDepartamentos != null) { 

  $sWhere1   .= " and fa04_i_unidades in ($sDepartamentos) ";
  $lExisteDep = true;

}//fim do if que inclui na pesquisa os departamentos selecionados pelo usuario 

if ($iPadrao == 2) {
  $sRela = "Não Padronizado";
} else {
    $sRela = "NORMAL";
}
$head1        = " Relátorio de Retiradas: $sRela ";
$head2        = "Período: $dIni até $dFim";

if  ($iPadrao == 1) { //Se o padrão selecionado para o relatorio for igual a "NORMAL"

  $head1        = " Relátorio de Retiradas: $sRela ";
  $head2        = '';
  $head3        = "Período: $dIni até $dFim";

  if (isset($iIdadeIni) && $iIdadeIni != ""){
    $head5 = "idade: $iIdadeIni à $iIdadeFim ";
  }

  $iQuant            = quantDias($dIni, $dFim);
  $oFarRetiradaItens = db_utils::getdao("far_retiradaitens");
  $sCampos           = "fa04_i_unidades,  ";
  $sCampos          .= "descrdepto ";
  $sCampos          .= "count(fa06_i_codigo) as itotaldepart";

  if (isset($sMedicamentos) && $sMedicamentos != "") {//if que verifica se existe medicamentos selecionados
    $sWhere1 .= " and fa06_i_matersaude in ($sMedicamentos) ";
  }//fim do if que verifica e adiciona os medicamentos selecionados na consulta

  $sWhere            = $sWhere1." group by fa04_i_unidades, descrdepto ";
  $sSql              = $oFarRetiradaItens->sql_query_historicoretiradas("", $sCampos, "", $sWhere);
  $rsItensAgrupado   = $oFarRetiradaItens->sql_record($sSql);
  $iItensAgrupLinhas = $oFarRetiradaItens->numrows;

  if ($iPaciente == 1 ) {// verifica se a opção "imprimir paciente" foi seleciona como sim 

    $sCampos      = "distinct fa04_i_unidades, ";
    $sCampos     .= "z01_v_nome";
    $sSql         = $oFarRetiradaItens->sql_query_historicoretiradas("", $sCampos, "", $sWhere1);
    $rsItens      = $oFarRetiradaItens->sql_record($sSql);
    $iItensLinhas = $oFarRetiradaItens->numrows;
    $lCor2        = 1;

    //se o resultudado de linhas for igual a zero,  nenhuma consulta foi encontrada; 
    if ($iItensLinhas == 0) {

      ?>
        <table width='100%'>
          <tr>
            <td align='center'>
              <font color='#FF0000' face='arial'>
                <b>Nenhum registro encontrado.<br>
                  <input type='button' value='Fechar' onclick='window.close()'>
                </b>
              </font>
            </td>
          </tr>
        </table>
      <?
      exit;

    }//fim do if que verifica se o numero de linhas da consulta é igual a zero

  } else {
    $lCor2            = 0;
  }//fim da condição que verifica se a opção verificar paciente é igual a sim 

 
  $sCampos          = "distinct nome, ";
  $sCampos         .= "count(fa06_i_codigo) as itotaldepart";
  $sWhere           = $sWhere1." group by nome ";
  $sSql             = $oFarRetiradaItens->sql_query_historicoretiradas("", $sCampos, "", $sWhere);
  $rsItensUser      = $oFarRetiradaItens->sql_record($sSql);
  $iItensUserLinhas = $oFarRetiradaItens->numrows;
  //se o resultudado de linhas for igual a zero,  nenhuma consulta foi encontrada;
  if ($iItensUserLinhas == 0) {

    ?>
      <table width='100%'>
        <tr>
          <td align='center'>
            <font color='#FF0000' face='arial'>
              <b>Nenhum registro encontrado.<br>
                <input type='button' value='Fechar' onclick='window.close()'>
              </b>
            </font>
          </td>
        </tr>
      </table>
    <?
    exit;

  }//fim do if que verifica se o numero de linhas da consulta é igual a zero

  $oPdf = new PDF();
  $oPdf->Open();
  $oPdf->AliasNbPages();
  $lCor          = '0';
  $iCountLinhas  = 35;
  $iTotalMedia   = 0;
  $iTotalGeral   = 0;
  
  //for que percorre a quantidade de linhas que retornou no pesquisa no banco
  for ($iCount = 0; $iCount < $iItensAgrupLinhas; $iCount++) {

    $oItensAgrupado = db_utils::fieldsmemory($rsItensAgrupado, $iCount);
    
    if ($iCountLinhas == 35){ //verifica se a quantidade de linhas do relatorio é igual a 35

      $oPdf->setfillcolor(200);
      $oPdf->setfont('arial', '', 11);
      $oPdf->Addpage('P');
    
      if ($iPaciente == 1 ){ //verifica se a opção "imprimir paciente" no formulario esta selecionado com SIM
        $sStr = " Departamento com Pacientes";
      }else{
        $sStr = " Departamento";
      }//fim da condição que verifica se a opção de imprimir paciente está como sim 

      $oPdf->cell(70, 5, $sStr, 1, 0, "L", 1);
      $oPdf->cell(40, 5, " Atendimento ", 1, 0, "L", 1);
      $oPdf->cell(40, 5," Média/Dia ", 1, 1, "L", 1);
      $iCountLinhas=0;

    }

    $oPdf->setfont('arial', '',9);
    
    if ($iPaciente == 1) {
      $oPdf->setfillcolor(223);
    }
  
    $oPdf->cell(70, 5, substr($oItensAgrupado->descrdepto, 0, 35), 1, 0, "L", $lCor2);
    $oPdf->cell(40, 5, $oItensAgrupado->itotaldepart, 1, 0, "L", $lCor2);
    $iTotalGeral += $oItensAgrupado->itotaldepart;
    $iMedia       = $oItensAgrupado->itotaldepart/$iQuant;
    $iTotalMedia += $iMedia;
    $oPdf->cell(40, 5, number_format($iMedia, 2, '.', ''), 1, 1, "L", $lCor2);
  
    if ($iPaciente == 1) {
    
      for ($iX=0; $iX<$iItensLinhas; $iX++) {

        $oItens = db_utils::fieldsmemory($rsItens, $iX);
      
        if ($oItensAgrupado->fa04_i_unidades == $oItens->fa04_i_unidades) {
        
          $oPdf->setfillcolor(223);
          $oPdf->cell(150, 5,$oItens->z01_v_nome,1,1,"L",$lCor);

        }

      }

    }

    $iCountLinhas++;
  }//fim do for que percorre a quantidade de linhas que retornou a colsuta,  $iCount < $iItensAgrupLinhas

  if ($iPaciente == 1 ) {//verifica se a opção imprimir paciente,  no relatorio, está selecionada como sim;
    $sStr = " Total dos Departamentos ";
  } else{
    $sStr = " Total ";
  }//fim da comdição que verifica se a opção imprimir paciente esta selecionada como sim 

  $oPdf->cell(70, 5,$sStr,1,0,"L",$lCor);
  $oPdf->cell(40, 5,$iTotalGeral,1,0,"L",$lCor);
  $oPdf->cell(40, 5,number_format($iTotalMedia, 2, '.', ''), 1, 1, "L", $lCor);
  $oPdf->cell(70, 5," ", 0, 1, "L", 0);
  $iTotalMedia  = 0;
  $iCountLinhas = 35;

  //for que percorre o numero de linhas que retornou a consulta
  for ($iCount = 0; $iCount < $iItensUserLinhas; $iCount++) {

    $oItensUser = db_utils::fieldsmemory($rsItensUser, $iCount);

    //se o numero de impressões no relatorio for igual a 35 quebra a pagina e adiciona um novo cabeçalio
    if($iCountLinhas == 35){

      $oPdf->setfillcolor(200);
      $oPdf->setfont('arial', '', 11);
      $oPdf->Addpage('P');
      $oPdf->cell(80, 5," Usuários ", 1, 0, "L", 1);
      $oPdf->cell(40, 5," Atendimento ", 1, 0, "L", 1);
      $oPdf->cell(40, 5," Média/Dia ", 1, 1, "L", 1);
      $iCountLinhas = 0;

    }//fim do if que verifica o numero de linhas que foram impressas no relatorio

    $oPdf->setfont('arial', '', 9);
    $oPdf->cell(80, 5, $oItensUser->nome, 1, 0, "L", $lCor);
    $oPdf->cell(40, 5, $oItensUser->itotaldepart, 1, 0, "L", $lCor);
    $iMedia       = $oItensUser->itotaldepart/$iQuant;
    $iTotalMedia += $iMedia;
    $oPdf->cell(40, 5, number_format($iMedia, 2, '.', ''), 1, 1, "L", $lCor);
    $iCountLinhas++;

  }// fim do for que percorre o numero de linhas da consulta $iCount<iItensUserLinhas
  
  $oPdf->Output();

}//fim do if que verifica se o padrao selecionado pelo usuario é "NORMAL"

if ($iPadrao == 2) {//se o relatorio for "não padronizado"

  $oFarRetiradaItens  = db_utils::getdao("far_retiradaitens");
  $sCampos     = "";
  $sCampos    .= "fa01_i_codigo, descrdepto, fa04_i_cgsund,z01_v_nome,fa04_d_data,fa06_f_quant,fa06_t_posologia,m60_descr,coddepto";
  $sWhereOrder = $sWhere1." and fa07_i_codigo is null  order by m60_descr";
  $sSql        = $oFarRetiradaItens->sql_query_historicoretiradas("", $sCampos, "", $sWhereOrder);
  $rsSql       = $oFarRetiradaItens->sql_record($sSql);
  $iLinhas     = $oFarRetiradaItens->numrows;
  
  //se o resultudado de linhas for igual a zero,  nenhuma consulta foi encontrada;
  if ($iLinhas == 0) {
        
    ?>
      <table width='100%'>
        <tr>
          <td align='center'>
            <font color='#FF0000' face='arial'>
              <b>Nenhum registro encontrado.<br>
                <input type='button' value='Fechar' onclick='window.close()'>
              </b>
            </font>
          </td>
        </tr>
      </table>
    <?
    exit;
  }//fim do if que verifica se o numero de linhas da consulta é igual a zero

  $oPdf = new PDF();
  $oPdf->Open();
  $oPdf->AliasNbPages();
  $oPdf->SetWidths(array(40, 80, 30, 35, 95));
  $oPdf->SetAligns(array("C", "L", "C", "C", "L"));
  $lUltimaPagina = false;
    
  //Setar as variaveis do multiCelll
  $iAlturaRow           = $oPdf->h -32;
  $iAltura              = 4; //altura da linha
  $lBorda               = true;
  $iEspaco              = 4;
  $lPreenchimento       = false;
  $lNaoUsarEspaco       = true;//Utilizar. Como false da erro!
  $lUsarQuebra          = false;
  $sCampoTestar         = null;
  $iLarguraFixa         = 0; //0=false,  1=true;
  $lPri                 = true;//primeira linha
  $sMedicamentoAnterior = "";//guarda o nome do ultimo medicamento encontrado;
  $iSubTotal            = 0;//registros parciais de cada medicamento
  $iRegPar              = 0; 
  $lEspaco              = false;//determina se deve haver um espaço entre as linhas de consulta;
  $iQtdParcial          = 0;// contador parcial por medicamentos entregues
  
  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {//contador que percorre todas as linhas do resultados encontrados;
    
    $oPacienteEncont =  db_utils::fieldsmemory($rsSql, $iCont);

    //if que verifica e quabra o relatorio por medicamentos encontrados
    if ($sMedicamentoAnterior != $oPacienteEncont->m60_descr and $lPri != true) {

      $iRegPar += $iSubTotal;
      //Adicina no final de cada consula de medicamento os respectivos registros parciais;
      $oPdf->setfillcolor(235);
      $oPdf->setfont('arial', '', 8);
      $oPdf->cell(180, 5, "Totalizador:", 1, 0, "L", 1);
      $oPdf->cell(50, 5, "Qtd. de Pacientes:".$iSubTotal, 1, 0, "L", 1);
      $oPdf->cell(50, 5,"Qtd. total Dispensada:".$iQtdParcial, 1, 1, "L", 1);
      //fim da célula de registros parciais por medicamentos 
      $iSubTotal   = 0;
      $lEspaco     = true;
      $iQtdParcial = 0;

    } //fim do if que verifica a quebra do relatorio por medicamento
    //verifica se deve haver um espaço entre as linhas da consulta,  separando o relatorio por medicamentos
    if ($lEspaco) { 
       
      if(($oPdf->gety() > $oPdf->h -40)){
        $oPdf->addpage('L');  
      }

      $oPdf->setfont('', '',9);   
      $oPdf->cell(280, 4, "", 0, 1, "", 0);
      $oPdf->cell(280, 4,"Medicamento: $oPacienteEncont->fa01_i_codigo-$oPacienteEncont->m60_descr ", 1, 1, "L", 1);
      $oPdf->setfont('arial', '',9);
      $oPdf->cell(40, 4, "CGS ", 1, 0, "L", 1);
      $oPdf->cell(80, 4, "Paciente", 1, 0, "L", 1);
      $oPdf->cell(30, 4, "Data Retirada", 1, 0, "L", 1);
      $oPdf->cell(35, 4, "Quantidade",1, 0, "L", 1);
      $oPdf->cell(95, 4, "Posologia",1 , 1, "L", 1);
      $lEspaco = false;
 
    }//fim do if que adiciona um espaço entre as linhas da consulta
 
    $iQtdParcial += $oPacienteEncont->fa06_f_quant;
    $sMedicamentoAnterior = $oPacienteEncont->m60_descr;  
    $iSubTotal++;

    //se for a primeira pagina ou a pagina estiver acabando,  novo cabeçalio é colocado
    if (($oPdf->gety() > $oPdf->h -30)  || $lPri==true ) {
 
      $oPdf->addpage('L');
      $oPdf->setfillcolor(235);
      $oPdf->setfont('arial', '', '12');//seta o tamanho da celula de medicamentos
      $oPdf->cell(280, 5, $oPacienteEncont->coddepto."-".$oPacienteEncont->descrdepto, 0, 1, "", 1);
      $oPdf->setfont('', '', '9');
      $oPdf->cell(280, 4, "Medicamento: $oPacienteEncont->fa01_i_codigo-$sMedicamentoAnterior", 1, 1, "L", 1);
      $oPdf->setfont('arial', '', 9);
      $oPdf->cell(40, 4, "CGS ", 1, 0, "L", 1);
      $oPdf->cell(80, 4, "Paciente", 1, 0, "L", 1);
      $oPdf->cell(30, 4, "Data Retirada", 1, 0, "L", 1);
      $oPdf->cell(35, 4, "Quantidade", 1, 0, "L", 1);
      $oPdf->cell(95, 4, "Posologia", 1, 1, "L", 1);
      $oPdf->setfont('arial', '', 8);
      $lPri = false;

    }

    $iLines = 0;
    // array que coloca as variveis para mostrar no relatorio;
    $aDados    = Array();
    $aDados[0] = $oPacienteEncont->fa04_i_cgsund;
    $aDados[1] = $oPacienteEncont->z01_v_nome;
    $aDados[2] = db_formatar($oPacienteEncont->fa04_d_data, 'd', 0); //formata a data no modelo correto 
    $aDados[3] = $oPacienteEncont->fa06_f_quant;
    $aDados[4] = $oPacienteEncont->fa06_t_posologia;
    
    for ($iConta = 0; $iConta < count($aDados); $iConta++) {//for que percorre o tamanho do array
      //calcula o tamanho da linha em relação a coluna
      if ($iLines <  $oPdf->NbLines($oPdf->widths[$iConta], $aDados[$iConta])) { 
        $iLines   =   $oPdf->NbLines($oPdf->widths[$iConta], $aDados[$iConta]);

      } // fim do if que calcula o tamanho da linha em relalção a coluna;

    }//fim do for que percorre o array de dados

      $iHeight = $iLines * $iEspaco;
      $oPdf->Row_multicell($aDados,            $iAltura,
                                              $lBorda, 
                                              $iHeight, 
                                              $lPreenchimento, 
                                              $lNaoUsarEspaco, 
                                              $lUsarQuebra, 
                                              $sCampoTestar, 
                                              $iAlturaRow, 
                                              $iLarguraFixa
                                             );
    
   
  
  }//fim do for que percorre o numero de linhas do resultado obtido no sql,  iCont<Ilinhas;
   //adicina no final da lista de resultados do ultimo medicamento buscado,  totalizador refernete a este medicamento
  $oPdf->setfillcolor(235);
  $oPdf->setfont('arial', '', 8);
  $oPdf->cell(190, 5, "Totalizador:",1,0,"L",1);
  $oPdf->cell(40, 5, "Qtd. de Pacientes:".$iSubTotal,1,0,"L",1);
  $oPdf->cell(50, 5, "Qtd. total Dispensada:".$iQtdParcial, 1, 1, "L", 1); 
  $lUltimaPagina = true;
  
 /*Segunda parte do relatorio,  depois que todos os pacientes foram listados, na primeira parte do  relátorio,
  * na ultima pagina é gerado o relatorio de almoxerifado
  *mesmo que o usuário nao tenha selecionado os almoxarifados
  */

/*
 *if que verifica se é a ultima pagina dos dados dos pacientes! 
 *Ainda se o relatorio foi pedido na forma de "NAO PADRONIZADO";
 */

  if ($lUltimaPagina == true) {
    
    $iLinhas      = "";
    $sCampos      = "";
    $sCampos     .= "coddepto,  descrdepto,count(fa04_i_cgsund), sum(fa06_f_quant)";
    $sWhere1     .= "and fa07_i_codigo is null group by descrdepto, coddepto";
    $sSql         = $oFarRetiradaItens->sql_query_historicoretiradas("", $sCampos, "", $sWhere1);
    $rsSql2       = $oFarRetiradaItens->sql_record($sSql);
    $iLinhas     .= $oFarRetiradaItens->numrows;
    $sUltimoAlmox = "";
    
    //se a quantidade de linhas que retornou a consulta for igual  a zero,  nenhum registro encontrado;
    if ($iLinhas == 0) {

      ?>
        <table width='100%'>
          <tr>
            <td align='center'>
              <font color='#FF0000' face='arial'>
                <b>Nenhum registro encontrado.<br>
                  <input type='button' value='Fechar' onclick='window.close()'>
                </b>
              </font>
            </td>
          </tr>
        </table>
      <?
      exit;
    }//fim do if que verifica a quantidade de linhas que retornou a consulta 
    
    
    $oPdf->SetWidths(array(190, 40, 50));
    $oPdf->SetAligns(array("L", "C", "C"));
    $lPrimeira = true;
    $iRegPar   = 0;
    $iRegTotal = 0;   
    //Contador que percorre as linhas resultantes da consulta do relatorio de almoxerifado;
    for ($iContador = 0; $iContador < $iLinhas; $iContador++) {

      $oAlmoxEncont =  db_utils::fieldsmemory($rsSql2, $iContador);
      $iRegPar     += $oAlmoxEncont->count;
      $iRegTotal   += $oAlmoxEncont->sum; 
      $sUltimoAlmox = $oAlmoxEncont->descrdepto;
      //if que verifica se é a primeira pagina ou se não esta no final da pagina; 
      if (($oPdf->gety() > $oPdf->h -30) || $lPrimeira == true) {

        $oPdf->cell(280, 4, "", 0, 1, "", 0); 
        $oPdf->cell(280, 4, "", 0, 1, "", 0);
        $oPdf->setfont('arial', '', '12');
        $oPdf->cell(90, 4, "", 0, 0, "", 0);
        $oPdf->cell(80, 5, "RESUMO POR ALMOXARIFADO", 0, 1, "C", 1);
        $oPdf->cell(280, 4, "", 0, 1, "", 0); 
        $oPdf->setfont('arial', '', 9);
        $oPdf->setfillcolor(235);
        $oPdf->cell(190, 4, "Almoxarifado(s)", 1, 0, "L", 1);
        $oPdf->cell(40, 4, "Quantidade de Pacientes", 1, 0, "L", 1);
        $oPdf->cell(50, 4, "Quantidade de Medicamentos", 1, 1, "L", 1);
        $lPrimeira = false;
  
      }//fim do if que verifica se é o final da pagina ou a primeira pagina;
      
      $aDado    = Array();
      $aDado[0] = $oAlmoxEncont->coddepto."-".$oAlmoxEncont->descrdepto;
      $aDado[1] = $oAlmoxEncont->count;
      $aDado[2] = $oAlmoxEncont->sum;
      $iLines   = 0;      
  
      for ($iContar = 0; $iContar < count($aDado); $iContar++) {// percorre o numero de posiçoes do vetor $aDados

        //if que calcula o tamanho da linha em relação a coluna
        if ($iLines <  $oPdf->NbLines($oPdf->widths[$iContar], $aDado[$iContar])) {
          $iLines   =   $oPdf->NbLines($oPdf->widths[$iContar], $aDado[$iContar]);
        } // fim do if que calcula o tamanho da linha em relalção a coluna;

      }//fim do for $iConta < count 
  
      $iHeight = $iLines * $iEspaco;
      $oPdf->Row_multicell($aDado,                $iAltura,
                                                 $lBorda, 
                                                 $iHeight, 
                                                 $lPreenchimento, 
                                                 $lNaoUsarEspaco, 
                                                 $lUsarQuebra, 
                                                 $sCampoTestar, 
                                                 $iAlturaRow, 
                                                 $iLarguraFixa
                                                 );
   
    }//fecha o for do icont<ilinhas

  }//fecha o if que confirma ultima pagina
  
  $oPdf->setfont('arial', '', 8);
  $oPdf->cell(190, 5, "Total Geral:", 1, 0, "L", 1);
  $oPdf->cell(40, 5, "".$iRegPar, 1, 0, "C", 1);
  $oPdf->cell(50, 5, "".$iRegTotal, 1, 1, "C", 1); 
  $oPdf->Output();

}//fecha o if que verifica se o relatorio é "não padronizado"; 

?>