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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("classes/db_iptucalcpadrao_classe.php");
include("classes/db_iptucalcpadraoconstr_classe.php");
include("classes/db_iptucalcpadraoorigem_classe.php");
include("classes/db_iptucalcpadraolog_classe.php");
include("classes/db_iptutaxamatric_classe.php");

$cliptucalcpadrao       = new cl_iptucalcpadrao;
$cliptucalcpadraolog    = new cl_iptucalcpadraolog;
$cliptucalcpadraoconstr = new cl_iptucalcpadraoconstr;
$cliptucalcpadraoorigem = new cl_iptucalcpadraoorigem;
$cliptutaxamatric       = new cl_iptutaxamatric;

/*
 db_msgbox( "matric= $matric exec = $exec perc= $perc  excluir = $excluir" );
if($excluir== 'true'){
  echo "xxxxxxxxxxxxx true";
}else{
  echo "xxxxxxxxxxxxx false";
}
*/ 
/*
 * excluir da iptucalcpadraoconstr
 * excluir da iptutaxamatric
 * excluir da iptucalcpadraoorigem
 * excluir da iptucalcpadrao
 */
$sqlerro=false;
db_inicio_transacao();

$sqliptucalcpadrao       = "select j10_sequencial 
                              from iptucalcpadrao 
                             where j10_matric = $matric   
                               and j10_anousu = ".db_getsession("DB_anousu");
$resultiptucalcpadrao    = pg_query($sqliptucalcpadrao);
$linhasiptucalcpadrao    = pg_num_rows($resultiptucalcpadrao);

if($linhasiptucalcpadrao > 0 ){
  
  if($excluir== 'true'){
  //######################### EXCLUI OS DADOS ########################
  db_fieldsmemory($resultiptucalcpadrao,0);
  
  // ################## CONSTRUÇÕES #########################
  $sqliptucalcpadraoconstr = "select j11_sequencial 
                                from iptucalcpadraoconstr 
                               where j11_iptucalcpadrao = $j10_sequencial";
  
  $resultiptucalcpadraoconstr = pg_query($sqliptucalcpadraoconstr);
  $linhasiptucalcpadraoconstr = pg_num_rows($resultiptucalcpadraoconstr);
  if($linhasiptucalcpadraoconstr>0){
    for($c=0;$c<$linhasiptucalcpadraoconstr;$c++){
      db_fieldsmemory($resultiptucalcpadraoconstr,$c);
      //exclui as construções
      $cliptucalcpadraoconstr-> j11_sequencial = $j11_sequencial;
      $cliptucalcpadraoconstr->excluir($j11_sequencial);
      if($cliptucalcpadraoconstr->erro_status==0){
        $sqlerro=true;
        $erro_msg = $cliptucalcpadraoconstr->erro_msg; 
        db_msgbox($erro_msg);
      }
    }
  }
  // ################## TAXAS #########################
  $sqliptutaxamatric       = "select j09_iptutaxamatric 
                                from iptutaxamatric 
                               where j09_matric = $matric ";
  $resultiptutaxamatric    = pg_query($sqliptutaxamatric);
  $linhasiptutaxamatric    = pg_num_rows($resultiptutaxamatric); 
  
  if($linhasiptutaxamatric>0){
    
    for($t=0;$t<$linhasiptutaxamatric;$t++){
      
      db_fieldsmemory($resultiptutaxamatric,$t);
      //exclui as taxas
      $cliptutaxamatric->j09_iptutaxamatric = $j09_iptutaxamatric;
      $cliptutaxamatric->excluir($j09_iptutaxamatric);
      if($cliptutaxamatric->erro_status==0){
        $sqlerro=true;
        $erro_msg = $cliptutaxamatric->erro_msg; 
        db_msgbox($erro_msg);
      }
    }
  }
  // ################## ORIGEM #########################
  $sqliptucalcpadraoorigem = "select j27_sequencial 
                                from iptucalcpadraoorigem 
                               where j27_matric         = $matric 
                                 and j27_iptucalcpadrao = $j10_sequencial";
  $resultiptucalcpadraoorigem    = pg_query($sqliptucalcpadraoorigem);
  $linhasiptucalcpadraoorigem    = pg_num_rows($resultiptucalcpadraoorigem); 
  
  if($linhasiptucalcpadraoorigem>0){
    
    for($o=0;$o<$linhasiptucalcpadraoorigem;$o++){
      
      db_fieldsmemory($resultiptucalcpadraoorigem,$o);
      //exclui as origem
      $cliptucalcpadraoorigem->j27_sequencial = $j27_sequencial;
      $cliptucalcpadraoorigem->excluir($j27_sequencial);
      
      if($cliptucalcpadraoorigem->erro_status==0){        
        $sqlerro=true;
        $erro_msg = $cliptucalcpadraoorigem->erro_msg; 
        db_msgbox($erro_msg);
      }
    }
  }
  
  // ################## LOG #########################
  $sqliptucalcpadraolog    = "select j19_sequencial 
                                from iptucalcpadraolog 
                               where j19_iptucalcpadrao = $j10_sequencial";
  $resultiptucalcpadraolog = pg_query($sqliptucalcpadraolog);
  $linhasiptucalcpadraolog = pg_num_rows($resultiptucalcpadraolog); 
  if($linhasiptucalcpadraolog>0){
    db_fieldsmemory($resultiptucalcpadraolog,0);
    //exclui os logs
    $cliptucalcpadraolog->j19_sequencial = $j19_sequencial;
    $cliptucalcpadraolog->excluir($j19_sequencial);
    if($cliptucalcpadraolog->erro_status==0){
      $sqlerro=true;
      $erro_msg = $cliptucalcpadraolog->erro_msg; 
      db_msgbox($erro_msg);
    }
  }  
  // ################## PADRAO #########################
  $cliptucalcpadrao->j10_sequencial = $j10_sequencial;
  $cliptucalcpadrao->excluir($j10_sequencial);
  if($cliptucalcpadrao->erro_status==0){
    $sqlerro=true;
    $erro_msg = $cliptucalcpadrao->erro_msg; 
    db_msgbox($erro_msg);
  }
  }
}
  if($sqlerro==false){
    // ################## INCLUI #########################
    $sqliptucalc = "select j23_vlrter, 
                           j23_aliq 
                      from iptucalc 
                     where j23_matric = $matric 
                       and j23_anousu = $exec ";
                       
    $resultiptucalc = pg_query($sqliptucalc);
    $linhasiptucalc = pg_num_rows($resultiptucalc);
    if($linhasiptucalc>0){
      db_fieldsmemory($resultiptucalc,0);
      
      // INCLUIR NA IPTUCALCPADRAO  IPTUCALCPADRAOORIGEM IPTUCALCPADRAOLOG
      
      //
      // Se nao tiver escolhido percentual para importacao nao corrige valores
      //
      if (isset($perc) && $perc <> 0 && $perc != "") {
        $correcao = ($j23_vlrter * $perc) / 100;
        $valorcorrigido = round( ( $j23_vlrter + $correcao ) ,2);
      } else {
        $valorcorrigido = $j23_vlrter;      
      }
      
      $cliptucalcpadrao->j10_anousu    = db_getsession("DB_anousu");
      $cliptucalcpadrao->j10_matric    = $matric;
      $cliptucalcpadrao->j10_vlrter    = $valorcorrigido;
      $cliptucalcpadrao->j10_aliq      = $j23_aliq ;
      $cliptucalcpadrao->j10_perccorre = $perc ;
      $cliptucalcpadrao->incluir(null);
      if($cliptucalcpadrao->erro_status==0){
        $sqlerro=true;
        $erro_msg = $cliptucalcpadrao->erro_msg; 
        db_msgbox($erro_msg);
      }
      if($sqlerro==false){
             
        $cliptucalcpadraoorigem->j27_iptucalcpadrao = $cliptucalcpadrao->j10_sequencial;
        $cliptucalcpadraoorigem->j27_matric         = $matric;
        $cliptucalcpadraoorigem->j27_anousu         = $exec;
        $cliptucalcpadraoorigem->incluir(null);
        if($cliptucalcpadraoorigem->erro_status==0){
          $sqlerro=true;
          $erro_msg = $cliptucalcpadraoorigem->erro_msg; 
          db_msgbox($erro_msg);
        }
      
        $cliptucalcpadraolog->j19_iptucalcpadrao = $cliptucalcpadrao->j10_sequencial;
        $cliptucalcpadraolog->j19_usuario        = db_getsession("DB_id_usuario") ;
        $cliptucalcpadraolog->j19_data           = date("Y-m-d",db_getsession("DB_datausu"));
        $cliptucalcpadraolog->j19_hora           = db_hora(); 
        $cliptucalcpadraolog->incluir(null);
        if($cliptucalcpadraolog->erro_status==0){
          $sqlerro=true;
          $erro_msg = $cliptucalcpadraolog->erro_msg; 
          db_msgbox($erro_msg);
        }
       
        
     }//false
     // ########### inclui construções ###################
     $sqliptuconstr = "select j39_matric,j39_idcons,j22_valor 
                       from  iptuconstr 
                       inner join iptucale on j39_idcons = j22_idcons 
                                          and j39_matric = j22_matric  
                       where j39_matric = $matric  and j22_anousu = $exec";
     $resultiptuconstr = pg_query($sqliptuconstr);
     $linhasiptuconstr = pg_num_rows($resultiptuconstr);
     if($linhasiptuconstr>0){
       for($ic=0;$ic<$linhasiptuconstr;$ic++){         
         db_fieldsmemory($resultiptuconstr,$ic);
         
         if (isset($perc) && $perc <> 0 && $perc != "") {
           $correcaoconst       = ($j22_valor * $perc)/100;
           $valorcorrigidoconst = round($correcaoconst +$j22_valor,2); 
         } else {
           $valorcorrigidoconst = $j22_valor;
         }
         $cliptucalcpadraoconstr->j11_iptucalcpadrao = $cliptucalcpadrao->j10_sequencial;
         $cliptucalcpadraoconstr->j11_matric         = $matric;
         $cliptucalcpadraoconstr->j11_idcons         = $j39_idcons;
         $cliptucalcpadraoconstr->j11_vlrcons        = $valorcorrigidoconst;
         $cliptucalcpadraoconstr->incluir(null) ;
         if($cliptucalcpadraoconstr->erro_status==0){
           $sqlerro=true;
           $erro_msg = $cliptucalcpadraoconstr->erro_msg; 
           db_msgbox($erro_msg);
         }
         
       }
     }
     
     //############### inclui as taxas #################
     
     $sqliptucadtaxa  = "select ( select j08_iptucadtaxaexe 
                        						from iptucalv 
                                         inner join iptucadtaxaexe on j08_tabrec = j21_receit 
				                                                          and j08_anousu = j21_anousu
             			         			   where j21_matric = $matric 
                                     and j21_anousu = ".db_getsession('DB_anousu').") as j08_iptucadtaxaexe,     
                                j21_receit,
                                j21_valor 
              						 from iptucalv 
                                inner join iptucadtaxaexe on j08_tabrec = j21_receit 
				                                                 and j08_anousu = j21_anousu
             						  where j21_matric = $matric 
                            and j21_anousu = $exec ";
     
     $resultiptucadtaxa = pg_query($sqliptucadtaxa);
     $linhasiptucadtaxa = pg_num_rows($resultiptucadtaxa);
     if($linhasiptucadtaxa>0){
       for($it=0;$it<$linhasiptucadtaxa;$it++){
         db_fieldsmemory($resultiptucadtaxa,$it);
         
         $correcaotaxa       = ($j21_valor * $perc)/100;
         $valorcorrigidotaxa = round($j21_valor + $correcaotaxa,2);
         
         $cliptutaxamatric->j09_iptucadtaxaexe = $j08_iptucadtaxaexe;
         $cliptutaxamatric->j09_matric         = $matric;
         $cliptutaxamatric->j09_valor          = $valorcorrigidotaxa;
         $cliptutaxamatric->incluir(null) ;         
         if($cliptutaxamatric->erro_status==0){
           $sqlerro=true;
           $erro_msg = $cliptutaxamatric->erro_msg; 
           db_msgbox($erro_msg);
         }
       }
     }
     
   }
 
  }//false
   


db_fim_transacao($sqlerro);

if($sqlerro==false){
   $sqlpadrao = " select j10_sequencial,j10_anousu,j10_matric,j10_vlrter,j10_aliq,j10_perccorre,j23_vlrter,j23_anousu 
                   from iptucalcpadrao 
                   left join iptucalcpadraoorigem on j10_sequencial = j27_iptucalcpadrao 
                   left join iptucalc             on j23_anousu     = j27_anousu 
                                                 and j27_matric     = j23_matric 
                   where j10_matric = $matric and j10_anousu = ".db_getsession("DB_anousu");
    $resultpadrao = pg_query($sqlpadrao);
    $linhaspadrao = pg_num_rows($resultpadrao);
    if($linhaspadrao>0){
      db_fieldsmemory($resultpadrao,0);
      
    }
    
    db_msgbox("Inclusão efetuada com sucesso.");
    
    // sleep(10); 

    echo "<script>                      
                    parent.iframe_iptucalcpadrao.document.form1.j10_matric.value   = $j10_matric;
                    parent.iframe_iptucalcpadrao.document.form1.j10_vlrter.value   = $j10_vlrter;
					parent.iframe_iptucalcpadrao.document.form1.j10_aliq.value     = $j10_aliq;
					parent.iframe_iptucalcpadrao.document.form1.j10_perccorre.value= $j10_perccorre;
    				parent.iframe_iptucalcpadrao.document.form1.j23_anousu.value   = $j23_anousu;
					parent.iframe_iptucalcpadrao.document.form1.j23_vlrter.value   = $j23_vlrter;   
                    parent.iframe_iptucalcpadrao.document.form1.chavepesquisa.value = $j10_sequencial;
                    parent.iframe_iptucalcpadrao.js_db_libera();
          </script>";  
  
}
/*
$sql = "select j23_vlrter, j23_aliq from iptucalc where j23_matric=$j01_matric and j23_anousu =$exec ";
  $result = pg_query($sql);
  $linhas = pg_num_rows($result);
  if($linhas>0){
    db_fieldsmemory($result,0);
    $j10_vlrter = $j23_vlrter;
    $j10_aliq = $j23_aliq;
  }
*/
?>