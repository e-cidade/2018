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

/*
 *Rotina que gera o txt e o pdf com as informações dos alunos e docentes sem inep
 */

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("classes/db_sau_fecharquivo_classe.php");
require_once("classes/db_lab_bpamagnetico_classe.php");
require_once("classes/db_tfd_bpamagnetico_classe.php");
require_once ("dbforms/db_layouttxt.php");
require_once("classes/db_db_layoutcampos_classe.php");
require_once ("libs/db_utils.php");


  //Seta as variavel do multicell
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
  $iLines               = 0;
  $dData      = date("d/m/Y");
  $head2      = "Data do relátorio:".$dData;
  $head1      = "Relátorio Docente sem codigo inep";
  $head3      = "Hora do relátorio ".date("G:i:s");

    $oPdf = new PDF();
    $oPdf->Open();
    $oPdf->AliasNbPages();
    $iAlturaRow     = $oPdf->h -32;
    $sSqlBuscaInfo  =  " select ed47_d_nasc,";
    $sSqlBuscaInfo .= "         ed47_v_nome,";
    $sSqlBuscaInfo .= "         ed47_v_pai, ";
    $sSqlBuscaInfo .= "         ed47_v_mae, ";
    $sSqlBuscaInfo .= "         ed47_i_codigo,";
    $sSqlBuscaInfo .= "         ed261_c_nome,";//censomunic(ed261_i_codigo)
    $sSqlBuscaInfo .= "         ed260_c_nome, ";//censouf(ed260_i_codigo)
    $sSqlBuscaInfo .= "         ed47_c_codigoinep ";
    $sSqlBuscaInfo .= "         from aluno";
    $sSqlBuscaInfo .= "              inner join censomunic on censomunic.ed261_i_codigo = ed47_i_censomuniccert";
    $sSqlBuscaInfo .= "              inner join escola on escola.ed18_i_censomunic = ed261_i_codigo ";
    $sSqlBuscaInfo .= "              inner join censouf on censouf.ed260_i_codigo = ed47_i_censoufnat";
    $sSqlBuscaInfo .= "              inner join calendarioescola on calendarioescola.ed38_i_escola = escola.ed18_i_codigo";
    $sSqlBuscaInfo .= "              inner join calendario on calendario.ed52_i_codigo = calendarioescola.ed38_i_calendario";
    $sSqlBuscaInfo .= "         where ed47_c_codigoinep = '' ";
    $sSqlBuscaInfo .= "           and ed18_i_codigo = $iEscola ";
    $sSqlBuscaInfo .= "           and ed52_i_ano = $ed52_i_ano"; 
    $rsBuscaInfo    = pg_query($sSqlBuscaInfo);
    $iLinhas        = pg_num_rows($rsBuscaInfo);

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
    }

    
    $oPdf->SetWidths(array(17, 20, 35, 50, 50,20,50,17));
    $oPdf->SetAligns(array("L", "L", "L", "L", "L","L","L","L"));

   for ($iCont = 0; $iCont < $iLinhas ; $iCont++) {
   
    if (($oPdf->gety() > $oPdf->h -30)  || $lPri == true ) {

      $oPdf->addpage('L');
      $oPdf->setfillcolor(235);
      $oPdf->setfont('arial', '', 9);
      $oPdf->cell(17, 6, "Cod.Aluno", 1, 0, "L", 1);
      $oPdf->cell(20, 6, 'MUN. NASC.', 1, 0, 'L', 1);
      $oPdf->cell(35, 6, "UF", 1, 0, "L", 1);
      $oPdf->cell(50, 6, "Nome Pai", 1, 0, "L", 1);
      $oPdf->cell(50, 6, "Nome Mãe", 1, 0, "L", 1);
      $oPdf->cell(20, 6, "NASC", 1, 0, "L", 1);
      $oPdf->cell(50, 6, "Aluno", 1, 0, "L", 1);
      $oPdf->cell(17, 6, "Cod. INEP", 1, 1, "L", 1);
      $lPri = false;
    
    }

      $oBuscaInfo                   = db_utils::fieldsMemory($rsBuscaInfo,$iCont);
      $oDadosA->idalunoinep         = $oBuscaInfo->ed47_i_codigo;
      $oDadosA->municipionascimento = $oBuscaInfo->ed261_c_nome;
      $oDadosA->ufnascimento        = $oBuscaInfo->ed260_c_nome;
      $oDadosA->nomepaialuno        = $oBuscaInfo->ed47_v_pai;
      $oDadosA->nomemaealuno        = $oBuscaInfo->ed47_v_mae;
      $oDadosA->datanascimento      = db_formatar($oBuscaInfo->ed47_d_nasc, "d");
      $oDadosA->nomealuno           = $oBuscaInfo->ed47_v_nome;
      $oDadosA->codigoalunoinep     = $oBuscaInfo->ed47_c_codigoinep;

      $aDado    = Array();
      $aDado[0] = $oDadosA->idalunoinep;
      $aDado[1] = $oDadosA->municipionascimento;
      $aDado[2] = $oDadosA->ufnascimento;
      $aDado[3] = $oDadosA->nomepaialuno;
      $aDado[4] = $oDadosA->nomemaealuno;
      $aDado[5] = $oDadosA->datanascimento;
      $aDado[6] = $oDadosA->nomealuno;
      $aDado[7] = $oDadosA->codigoalunoinep;

      for ($iConta = 0; $iConta < count($aDado); $iConta++) {//for que percorre o tamanho do array
              //calcula o tamanho da linha em relação a coluna
             if ($iLines <  $oPdf->NbLines($oPdf->widths[$iConta], $aDado[$iConta])) {
               $iLines   =   $oPdf->NbLines($oPdf->widths[$iConta], $aDado[$iConta]);
             } // fim do if que calcula o tamanho da linha em relalção a coluna;

           }//fim do for que percorre o array de dados
           
           $iHeight = $iLines * $iEspaco;
           $oPdf->Row_multicell($aDado,      $iAltura,
                                              $lBorda,
                                              $iHeight,
                                              $lPreenchimento,
                                              $lNaoUsarEspaco,
                                              $lUsarQuebra,
                                              $sCampoTestar,
                                              $iAlturaRow,
                                              $iLarguraFixa
                                             );


     
  }

     $oPdf->Output();
?>