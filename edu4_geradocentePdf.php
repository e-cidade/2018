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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("classes/db_sau_fecharquivo_classe.php");
require_once("classes/db_lab_bpamagnetico_classe.php");
require_once("classes/db_tfd_bpamagnetico_classe.php");
require_once ("dbforms/db_layouttxt.php");
require_once("classes/db_db_layoutcampos_classe.php");
require_once ("libs/db_utils.php");


       //Seta as variavel do multicell
  $iAlturaRow           = 
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
  $oDaoRecHumano    = db_utils::getdao("rechumano");
  $sCampos          = "z01_nasc, z01_mae, z01_cgccpf, z01_munic,z01_ufcon,z01_nome,ed20_i_codigo";//verificar o ed20_i_codigo
  $sWhere           = "";
  $sWhere          .= " ed18_i_codigo = $iEscola and ed20_i_codigoinep is null and ed52_i_ano = $ed52_i_ano ";
  $sSqlRecHumano    = $oDaoRecHumano->sql_query_solicitaseminep("",$sCampos,"",$sWhere);
  $rsRecHumano      = $oDaoRecHumano->sql_record($sSqlRecHumano);
  $iLinhasRecHumano = $oDaoRecHumano->numrows;
  $oPdf2 = new PDF();
  $oPdf2->Open();
  $oPdf2->AliasNbPages();
  $iAlturaRow = $oPdf2->h -32;

  $dData      = date("d/m/Y");
  $head2      = "Data do relátorio:".$dData; 
  $head1      = "Relátorio Docente sem codigo inep";
  $head3      = "Hora do relátorio ".date("G:i:s");
  
  if ($iLinhasRecHumano == 0) {
    
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
       
       $oPdf2->SetWidths(array(35, 75, 20, 70, 7, 20, 30, 25));
       $oPdf2->SetAligns(array("L", "L", "L", "L", "L","L","L","L"));      
  
       for ($iContar = 0; $iContar < $iLinhasRecHumano; $iContar++) {

         $oRecHumano                  = db_utils::fieldsmemory($rsRecHumano, $iContar);

         $oDados->idinep              = "";
         $oDados->numerocpf           = $oRecHumano->z01_cgccpf;
         $oDados->municipionascimento = $oRecHumano->z01_munic;
         $oDados->ufnascimento        = $oRecHumano->z01_ufcon;
         $oDados->nomemaedocente      = $oRecHumano->z01_mae;
         $oDados->datadenascimento    = db_formatar($oRecHumano->z01_nasc, "d");
         $oDados->nomedocente         = $oRecHumano->z01_nome;
         $oDados->codigodocenteescola = $oRecHumano->ed20_i_codigo;
        
         if (($oPdf2->gety() > $oPdf2->h -30) || $lPri == true) { //if que verifica primeira ou quebra de pagina

           $oPdf2->addpage('L');
           $oPdf2->setfillcolor(235);
           $oPdf2->setfont('arial', '', 9);
           $oPdf2->cell(35, 6, "Cod. Docente Escola", 1, 0, "L", 1);
           $oPdf2->cell(75, 6, "Nome do Docente ", 1, 0, "L", 1);
           $oPdf2->cell(20, 6," Data Nasc.", 1, 0, "L", 1);
           $oPdf2->cell(70, 6, "MÃE DOCENTE", 1, 0, "L", 1);
           $oPdf2->cell(7, 6, "UF", 1, 0, "L", 1);
           $oPdf2->cell(20, 6, "MUN. NASC", 1, 0, "L", 1);
           $oPdf2->cell(30, 6, "Numero do CPF", 1, 0, "L", 1);
           $oPdf2->cell(25, 6, "Codigo INEP", 1, 1, "L", 1);
           $lPri = false;

         } //if que verifica a primeira pagina ou quebra de pagina

         $aDados    = Array();
         $aDados[0] = $oDados->codigodocenteescola;
         $aDados[1] = $oDados->nomedocente;
         $aDados[2] = $oDados->datadenascimento;
         $aDados[3] = $oDados->nomemaedocente;
         $aDados[4] = $oDados->ufnascimento;
         $aDados[5] = $oDados->municipionascimento;
         $aDados[6] = $oDados->numerocpf;
         $aDados[7] = $oDados->idinep;

         for ($iConta = 0; $iConta < count($aDados); $iConta++) {//for que percorre o tamanho do array
             
           //calcula o tamanho da linha em relação a coluna

           if ($iLines <  $oPdf2->NbLines($oPdf2->widths[$iConta], $aDados[$iConta])) {
             $iLines   =   $oPdf2->NbLines($oPdf2->widths[$iConta], $aDados[$iConta]);
           } // fim do if que calcula o tamanho da linha em relalção a coluna;

         }//fim do for que percorre o array de dados

         $iHeight = $iLines * $iEspaco;
                 
         $oPdf2->Row_multicell($aDados,     $iAltura,
                                            $lBorda,
                                            $iHeight,
                                            $lPreenchimento,
                                            $lNaoUsarEspaco,
                                            $lUsarQuebra,
                                            $sCampoTestar,
                                            $iAlturaRow,
                                            $iLarguraFixa
                                           );

        

      
       }//for do ilinhas, resultado do sql

       $oPdf2->Output();
       

?>