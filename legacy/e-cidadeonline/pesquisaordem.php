<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
$sqlerro=false;
//echo "planilha = $planilha"; 
if(isset($codord)){
  //verificar se este codord ja tem planilha.
   $sqlpla = "select q20_planilha from issplanitop 
              inner join issplanit on q96_issplanit = q21_sequencial 
              inner join issplan on q20_planilha = q21_planilha 
              where q96_pagordem = $codord and q20_situacao <> 5 limit 1";
   $resultpla = db_query($sqlpla);
   $linhaspla = pg_num_rows($resultpla);
   if($linhaspla > 0){
     db_fieldsmemory($resultpla,0);
     echo "<script>parent.js_erropesquisaordem('Ja existe planilha ($q20_planilha) para esta ordem de pagamento')</script>";
     exit;
   }
  
  //verifica se tem ordem
  $sqlop = "select * from pagordem where e50_codord = $codord ";
  $resultop = db_query($sqlop);
  $linhasop = pg_num_rows($resultop);
  if($linhasop > 0){
    //verifica se tem nota
    $sqlnota = "select * from pagordemnota where e71_codord= $codord ";
    $resultnota = db_query($sqlnota);
    $linhasnota = pg_num_rows($resultnota);
    if($linhasnota > 0){
      // tem nota
      $sql = "  select e50_codord,e60_numemp,e60_numcgm,e69_dtnota, e69_numero as nota,e70_valor as valornota,
				a.z01_nome as nome_emp,a.z01_cgccpf as cnpj_emp, b.z01_nome as nome_pag,b.z01_cgccpf as cnpj_pag
				from  pagordem
				inner join empempenho    on e50_numemp   = e60_numemp
				inner join cgm a         on a.z01_numcgm = e60_numcgm
				left  join pagordemconta on e49_codord   = e50_codord
				left join cgm b         on b.z01_numcgm = e49_numcgm
				inner join pagordemnota  on e71_codord   = e50_codord
				inner join empnota       on e69_codnota  = e71_codnota
				inner join empnotaele    on e70_codnota  = e71_codnota
				where e50_codord = $codord ";
           
      $result = db_query($sql);
      $linhas = pg_num_rows($result);
      if($linhas > 0){
        include("classes/db_issplan_classe.php");
        include("classes/db_issplanit_classe.php");
        include("classes/db_issplanitop_classe.php");
        $cl_issplan = new cl_issplan;
        $cl_issplanit = new cl_issplanit;
        $cl_issplanitop = new cl_issplanitop;
        $sqlpref = "select numcgm,codigo,nomeinst,telef,cgc from db_config where prefeitura is true";
        $resultpref= db_query($sqlpref);
        db_fieldsmemory($resultpref,0);
        if($planilha == ""){
           $cl_issplan-> q20_numcgm = $numcgm;
           $cl_issplan-> q20_ano = $ano;
           $cl_issplan-> q20_mes = $mes;
           $cl_issplan-> q20_nomecontri = @$nomeinst;
           $cl_issplan-> q20_fonecontri = @$telef;
           $cl_issplan-> q20_numpre = 0;
           $cl_issplan-> q20_numbco = 0;
           $cl_issplan-> q20_situacao = 1;
           $cl_issplan->incluir(null);
           if ($cl_issplan->erro_status == 0) {
            $sqlerro = true;
            $erro_msg = $cl_issplan->erro_msg;
            echo "<script>parent.js_erropesquisaordem('$erro_msg')</script>";
           }else{
             $planilha = $cl_issplan->q20_planilha;
           }
         }else{
           
		   //verifica se esta planilha ja tem ordem
		   $sqlpla = "select q96_pagordem from issplanitop 
		              inner join issplanit on q96_issplanit = q21_sequencial 
		              inner join issplan on q20_planilha = q21_planilha 
		              where q20_planilha = $planilha and q20_situacao <> 5 limit 1";
		   $resultpla = db_query($sqlpla);
		   $linhaspla = pg_num_rows($resultpla);
		   if($linhaspla > 0){
		     db_fieldsmemory($resultpla,0);
		     echo "<script>parent.js_erropesquisaordem('Ja existe Ordem de pagamento ($q96_pagordem) para esta planilha')</script>";
		     exit;
		   }else{
		     // se esta planilha ja tem notas sem ordem não pode incluir um nota com ordem..
             echo "<script>parent.js_erropesquisaordem('Esta planilha ja possui notas lançadas sem ordem de pagamento.')</script>";
		     exit;
		   }
		   
         }
     
        if($sqlerro==false){
          for($i=0;$i<$linhas;$i++){
            db_fieldsmemory($result,$i);
             
            // buscar serviço
			$sqlservico = "
            select c60_descr as servico from empelemento
			inner join empempenho    on e60_numemp = e64_numemp 
			inner join conplanoreduz on c61_anousu = e60_anousu 
			                        and c61_codcon = e64_codele 
			                        and c61_instit = e60_instit 
			inner join conplano      on c60_codcon = c61_codcon 
			                        and c60_anousu = c61_anousu 
			where e64_numemp = $e60_numemp";
			$resultservico = db_query($sqlservico);
			$linhasservico = pg_num_rows($resultservico);
			if($linhasservico >0){
			  db_fieldsmemory($resultservico,0);
			}
			$sqlaliquota = "select distinct q81_valexe as aliquota  from tipcalc where q81_cadcalc = 3 and q81_usaretido is true limit 1";
			$resultaliquota = db_query($sqlaliquota);
			$linhasaliquota = pg_num_rows($resultaliquota);
			if($linhasaliquota >0){
			  db_fieldsmemory($resultaliquota,0);
			   
			}else{
			  $aliquota = 0;
			  
			}
			
			$valorretido = ($valornota * $aliquota)/100;
            if($nome_pag == null or $nome_pag==""){
              $z01_nome   = $nome_emp;
              $z01_cgccpf = $cnpj_emp;
            }else{
              $z01_nome   = $nome_pag;
              $z01_cgccpf = $cnpj_pag;
            }
			
			
			
             $cl_issplanit->q21_planilha    = $planilha;
             $cl_issplanit->q21_cnpj        = $z01_cgccpf;
             $cl_issplanit->q21_nome        = $z01_nome;
             $cl_issplanit->q21_servico     = substr($servico,0,39);
             $cl_issplanit->q21_nota        = $nota;
             $cl_issplanit->q21_serie       = null;
             $cl_issplanit->q21_valorser    = $valornota;
             $cl_issplanit->q21_aliq        = $aliquota;       
             $cl_issplanit->q21_valor       = $valorretido;    
             $cl_issplanit->q21_valorimposto= $valorretido;    
             $cl_issplanit->q21_dataop      = date("Y-m-d");
             $cl_issplanit->q21_horaop      = date("H:i");
             $cl_issplanit->q21_tipolanc    = 1;
             $cl_issplanit->q21_situacao    = '0';
             $cl_issplanit->q21_valordeducao= '0';
             $cl_issplanit->q21_valorbase   = $valornota;
             $cl_issplanit->q21_retido      = 't';         
             $cl_issplanit->q21_obs         = $servico;
             $cl_issplanit->q21_datanota    = $e69_dtnota;
						 $cl_issplanit->q21_status      = 1;
             $cl_issplanit->incluir(null);
             if ($cl_issplanit->erro_status == 0) {
               $sqlerro = true;
               $erro_msg = $cl_issplanit->erro_msg;
               echo "<script>parent.js_erropesquisaordem('$erro_msg')</script>";
             }else{
               // grava na issplanitop
                $cl_issplanitop->q96_issplanit = $cl_issplanit->q21_sequencial;
                $cl_issplanitop->q96_pagordem  = $codord;
                $cl_issplanitop->incluir(null);
                if ($cl_issplanitop->erro_status == 0) {
                 $sqlerro = true;
                 $erro_msg = $cl_issplanitop->erro_msg;
                 echo "<script>parent.js_erropesquisaordem('$erro_msg')</script>";
                }
                
                // tinha q mostrar na tabela
                echo "<script>
                parent.document.form1.nova.value = 1;
                          
                parent.document.form1.cgc.value = $cgc;
                parent.document.form1.planilha.value = $planilha;
                parent.document.form1.submit();

                </script>";
             }
            
          }
        }
        
       
      }
    }else{
      // não tem nota
      echo "<script>parent.js_erropesquisaordem('Esta ordem ($codord) não possui nota.')</script>";
      exit;
    }
  }else{
    // não existe a ordem
    echo "<script>parent.js_erropesquisaordem('Código da ordem $codord não encontrado.')</script>";
    exit;
  }

}
?>