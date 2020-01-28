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

include("classes/db_rhsolicita_classe.php");

// Classes usadas no módulo COMPRAS para gerar solicitação de compras
include("classes/db_solicita_classe.php");
include("classes/db_solicitem_classe.php");
include("classes/db_solicitempcmater_classe.php");
include("classes/db_solicitemele_classe.php");
include("classes/db_solicitemunid_classe.php");

// Se houver dotacao (rh40_coddot)
include("classes/db_pcdotac_classe.php");
include("classes/db_orcreserva_classe.php");
include("classes/db_orcreservasol_classe.php");

// Orçamento da solicitação
include("classes/db_pcorcam_classe.php");
include("classes/db_pcorcamitem_classe.php");
include("classes/db_pcorcamitemsol_classe.php");
include("classes/db_pcorcamforne_classe.php");
include("classes/db_pcorcamval_classe.php");
include("classes/db_pcorcamjulg_classe.php");

$clrhsolicita       = new cl_rhsolicita;

$clsolicita         = new cl_solicita;
$clsolicitem        = new cl_solicitem;
$clsolicitempcmater = new cl_solicitempcmater;
$clsolicitemele     = new cl_solicitemele;
$clsolicitemunid    = new cl_solicitemunid;

$clpcdotac          = new cl_pcdotac;
$clorcreserva       = new cl_orcreserva;
$clorcreservasol    = new cl_orcreservasol;

$clpcorcam          = new cl_pcorcam;
$clpcorcamitem      = new cl_pcorcamitem;
$clpcorcamitemsol   = new cl_pcorcamitemsol;
$clpcorcamforne     = new cl_pcorcamforne;
$clpcorcamval       = new cl_pcorcamval;
$clpcorcamjulg      = new cl_pcorcamjulg;

function db_exclusao_solicitacao($dbwhere){
  global $pc11_codigo,     $pc20_codorc,         $pc22_orcamitem,    $pc21_orcamforne, $o80_codres,
         $res_pcorcamitem, $numrows_pcorcamitem, $res_orcreservasol, $numrows_orcreservasol, $erro, $erro_msg, $where;
         
  $erro                = false;
  $erro_msg            = "";
  $where               = $dbwhere;
  
  $clrhsolicita        = new cl_rhsolicita;

  $clsolicita          = new cl_solicita;
  $clsolicitem         = new cl_solicitem;
  $clsolicitempcmater  = new cl_solicitempcmater;
  $clsolicitemele      = new cl_solicitemele;
  $clsolicitemunid     = new cl_solicitemunid;

  $clpcdotac           = new cl_pcdotac;
  $clorcreserva        = new cl_orcreserva;
  $clorcreservasol     = new cl_orcreservasol;

  $clpcorcam           = new cl_pcorcam;
  $clpcorcamitem       = new cl_pcorcamitem;
  $clpcorcamitemsol    = new cl_pcorcamitemsol;
  $clpcorcamforne      = new cl_pcorcamforne;
  $clpcorcamval        = new cl_pcorcamval;
  $clpcorcamjulg       = new cl_pcorcamjulg;

  $res_pcorcamitem     = $clpcorcamitemsol->sql_record($clpcorcamitemsol->sql_query(null,null,"pc20_codorc,pc22_orcamitem,pc21_orcamforne,pc11_codigo",null,"pc10_numero in ($where)"));
  $numrows_pcorcamitem = $clpcorcamitemsol->numrows;

  if ($numrows_pcorcamitem > 0){
    $codorc   = "";
    $codforne = "";
    $virgula  = "";
    for ($i = 0; $i < $numrows_pcorcamitem; $i++){
      db_fieldsmemory($res_pcorcamitem,$i);

      if (trim($pc20_codorc) == "" || trim($pc21_orcamforne) == ""){
        break;
      }

      $codorc   .= $virgula.$pc20_codorc;
      $codforne .= $virgula.$pc21_orcamforne;
      $virgula   = ",";
    }

    for ($i = 0; $i < $numrows_pcorcamitem; $i++){
      db_fieldsmemory($res_pcorcamitem,$i);

// Exclusao de reservas
      $res_orcreservasol     = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(null,null,"o80_codres",null,"pc13_codigo = $pc11_codigo"));
      $numrows_orcreservasol = $clorcreservasol->numrows;

      if ($numrows_orcreservasol > 0){
        db_fieldsmemory($res_orcreservasol,0);

        $clorcreservasol->excluir(null,"o82_codres = {$o80_codres}");
        if ($clorcreservasol->erro_status == 0){
          $erro = true;
          $erro_msg = $clorcreservasol->erro_msg;
          break;
        }
        if ($erro == false){
          $clorcreserva->excluir($o80_codres);
          if ($clorcreserva->erro_status == 0){
            $erro = true;
            $erro_msg = $clorcreserva->erro_msg;
            break;
          } 
        }
      }
      
      if ($erro == false){
        $clpcdotac->excluir(null,"pc13_codigo = $pc11_codigo");
        if ($clpcdotac->erro_status == 0){
          $erro = true;
          $erro_msg = $clpcdotac->erro_msg;
          break;
        } 
      }
      
// Exclusao de orçamento
      if ($erro == false){
        $res_pcorcamjulg = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query_file($pc22_orcamitem,$pc21_orcamforne));
        if ($clpcorcamjulg->numrows > 0){
          $clpcorcamjulg->excluir($pc22_orcamitem,$pc21_orcamforne);
          if ($clpcorcamjulg->erro_status == 0){
            $erro = true;
            $erro_msg = $clpcorcamjulg->erro_msg;
            break;
          }
        }

        $res_pcorcamval = $clpcorcamval->sql_record($clpcorcamval->sql_query_file($pc21_orcamforne,$pc22_orcamitem));
        if ($clpcorcamval->numrows > 0){
          $clpcorcamval->excluir($pc21_orcamforne,$pc22_orcamitem);
          if ($clpcorcamval->erro_status == 0){
            $erro = true;
            $erro_msg = $clpcorcamval->erro_msg;
            break;
          }
        }
      }
      
      $clpcorcamitemsol->excluir($pc22_orcamitem,$pc11_codigo);
      if ($clpcorcamitemsol->erro_status == 0){
        $erro = true;
        $erro_msg = $clpcorcamitemsol->erro_msg;
        break;
      }

      $clpcorcamitem->excluir($pc22_orcamitem);
      if ($clpcorcamitem->erro_status == 0){
        $erro = true;
        $erro_msg = $clpcorcamitem->erro_msg;
        break;
      }
      
      $clsolicitemele->excluir(null,null,"pc18_solicitem = $pc11_codigo");
      if ($clsolicitemele->erro_status == 0){
        $erro = true;
        $erro_msg = $clsolicitemele->erro_msg;
        break;
      }

      $clsolicitempcmater->excluir(null,null,"pc16_solicitem = $pc11_codigo");
      if ($clsolicitempcmater->erro_status == 0){
        $erro = true;
        $erro_msg = $clsolicitempcmater->erro_msg;
        break;
      }
      
	    $clsolicitem->excluir(null,"pc11_codigo = $pc11_codigo ");
	    if ($clsolicitem->erro_status == 0){
	      $erro     = true;
	      $erro_msg = $clsolicitem->erro_msg;
	      break;
	    }
      
    }
    
    if (isset($codorc)   && trim($codorc)   != "" && 
        isset($codforne) && trim($codforne) != ""){
      $res_pcorcamforne = $clpcorcamforne->sql_record($clpcorcamforne->sql_query(null,"*",null,"pc21_orcamforne in ($codforne) and 
                                                                                                pc20_codorc not in ($codorc)"));
      if ($clpcorcamforne->numrows == 0){
        $clpcorcamforne->excluir($pc21_orcamforne);
        if ($clpcorcamforne->erro_status == 0){
          $erro = true;
          $erro_msg = $clpcorcamforne->erro_msg;
        }
        if ($erro == false){
          $clpcorcam->excluir(null,"pc20_codorc in ($codorc)");
          if ($clpcorcam->erro_status == 0){
            $erro = true;
            $erro_msg = $clpcorcam->erro_msg;
          }
        }
      }
    }
    
  }

  if ($erro == false){

//  db_msgbox("2 ".$erro_msg);
    $clrhsolicita->excluir(null,"rh33_solicita in ($where)");
    if ($clrhsolicita->erro_status == 0){
      $erro = true;
      $erro_msg = $clrhsolicita->erro_msg;
    }

//  db_msgbox("3 ".$erro_msg);
    $clsolicita->excluir(null,"pc10_numero in ($where)");
    if ($clsolicita->erro_status == 0){
      $erro = true;
      $erro_msg = $clsolicita->erro_msg;
    }
  
//  db_msgbox("4 ".$erro_msg);
  }
  
  if (trim($erro_msg) != ""){
    db_msgbox($erro_msg);
  }

  return $erro;
}

?>