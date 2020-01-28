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
    include("dbforms/db_funcoes.php");
    include("dbforms/db_classesgenericas.php");

if( isset($HTTP_GET_VARS["data1"]) && isset( $HTTP_GET_VARS["data2"])){

    db_postmemory($HTTP_POST_VARS);
    /*
    $str_sql = "select q50_data,q50_hora,q50_numpre, q50_numpar,q50_vlrinf,k00_inscr, cgm2.z01_cgccpf as cpf_cont , cgm2.z01_nome as contrib,cgm.z01_numcgm as cgm_log, cgm.z01_cgccpf as cpf_log,cgm.z01_nome as logado
				from issvarlancval 
				inner join db_usuacgm on id_usuario = q50_idusuario
				inner join cgm on cgm.z01_numcgm = db_usuacgm.cgmlogin
				inner join arreinscr on arreinscr.k00_numpre = issvarlancval.q50_numpre
				left join issbase on k00_inscr=q02_inscr
				left join cgm cgm2 on q02_numcgm= cgm2.z01_numcgm
				left join db_usuarios on db_usuarios.id_usuario = q50_idusuario 
				where q50_data between '$data1' and '$data2' ";
	*/			
				
	$str_sql ="
			select 
				max(k00_dtpaga) as data_pago,
				issvarlancval.q50_data,
				issvarlancval.q50_hora,
				issvarlancval.q50_numpre, 
				issvarlancval.q50_numpar,
				issvarlancval.q50_vlrinf,
				arreinscr.k00_inscr, 
				cgm2.z01_nome as contrib,
				cgm.z01_numcgm as cgm_log, 
				cgm.z01_nome as logado 
			from (
				 select max(q50_seq) as q50_seq, q50_codigo, q50_numpre, q50_numpar 
				 from issvarlancval 
				 where q50_data between '$data1' and '$data2' 
				 group by q50_codigo, q50_numpre, q50_numpar 
				 ) as x 
			inner join issvarlancval on issvarlancval.q50_seq = x.q50_seq 
			inner join db_usuacgm on id_usuario = issvarlancval.q50_idusuario 
			inner join cgm on cgm.z01_numcgm = db_usuacgm.cgmlogin 
			inner join arreinscr on arreinscr.k00_numpre = issvarlancval.q50_numpre 
			left join issbase on k00_inscr=q02_inscr 
			left join cgm cgm2 on q02_numcgm= cgm2.z01_numcgm 
			left join db_usuarios on db_usuarios.id_usuario = issvarlancval.q50_idusuario
			left join arrepaga on arrepaga.k00_numpre = issvarlancval.q50_numpre and arrepaga.k00_numpar = issvarlancval.q50_numpar
			group by
				issvarlancval.q50_data,
				issvarlancval.q50_hora,
				issvarlancval.q50_numpre, 
				issvarlancval.q50_numpar,
				issvarlancval.q50_vlrinf,
				arreinscr.k00_inscr, 
				cgm2.z01_nome,
				cgm.z01_numcgm, 
				cgm.z01_nome			
	";			
				
    $str_sql .= " order by ";
    
    if( $ordenar == "1"){
      $str_sql .= " issvarlancval.q50_data, issvarlancval.q50_hora ";
    }else{
      $str_sql .= " arreinscr.k00_inscr";
    }

/*
                 left join arrecant on arrecant.k00_numpre = q50_numpre
                                   and arrecant.k00_numpar = q50_numpar
                 left join arrepaga on arrepaga.k00_numpre = q50_numpre
                                   and arrepaga.k00_numpar = q50_numpar
                  and ( arrecant.k00_numpre is null and
                        arrepaga.k00_numpre is null )
*/
//die($str_sql);

    $result = pg_exec($str_sql) or die("FALHA: <br>$str_sql" );
    if(pg_numrows($result)==0){
      db_redireciona('db_erros.php?fechar=true&db_erro=Não existem informações pata gerar o relatório.');
    }
    $head2 = "Lançamentos efetuados no DBPREF/DBPORTAL";
    $head3 = "Issqn Variável";

    $pdf = new PDF();
    $pdf->Open(); // abre o relatorio
	$pdf->AliasNbPages(); // gera alias para as paginas
	$pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(220);
    $pdf->SetFont('Arial','B',7);

    $pag = 1;

    for ($x = 0 ; $x < pg_numrows($result);$x++){
        db_fieldsmemory($result,$x);
        if (($pdf->gety() > $pdf->h - 30) || $pag == 1 ){
            $pdf->addpage("L");
            $pag = 0;
            
           
            //q50_data,q50_hora,q50_numpre, q50_numpar,q50_vlrinf,k00_inscr, cgm2.z01_cgccpf as cpf_cont , cgm2.z01_nome as contrib,cgm.z01_numcgm as cgm_log, cgm.z01_cgccpf as cpf_log,cgm.z01_nome as logado
            
            $pdf->Cell(20,5,'Data',1,0,"C",1);
            $pdf->Cell(15,5,'Hora',1,0,"C",1);
            $pdf->cell(20,5,'Data pgto',1,0,"C",1);
            $pdf->cell(15,5,'Sit.',1,0,"C",1);
            $pdf->cell(15,5,'Numpre',1,0,"C",1);
            $pdf->cell(10,5,'Parcela',1,0,"C",1);
            $pdf->cell(20,5,'Valor',1,0,"C",1);
            $pdf->cell(15,5,'Inscrição',1,0,"C",1);
            $pdf->Cell(65,5,'Nome',1,0,"C",1);
            $pdf->cell(20,5,'CGM Usuário',1,0,"C",1);
            $pdf->Cell(65,5,'Nome Usuário',1,1,"C",1);
            
            
        }

 		if(($data_pago=="")&&($q50_vlrinf== 0)){
            $sit = "Canc.";
            	
        }
        if(($data_pago=="")&&($q50_vlrinf!= 0)){
            $sit = "Aberto";
            	
        }
        if($data_pago!=""){
            $sit = "Pago";
            	
        }
            

        $pdf->Cell(20,5,db_formatar($q50_data,"d"),0,0,"C",0);
        $pdf->Cell(15,5,$q50_hora,0,0,"R",0);
        $pdf->Cell(20,5,db_formatar($data_pago,"d"),0,0,"C",0);
        $pdf->cell(15,5,$sit,0,0,"C",0);
        $pdf->Cell(15,5,$q50_numpre,0,0,"R",0);
        $pdf->Cell(10,5,$q50_numpar,0,0,"R",0);
        $pdf->cell(20,5,db_formatar($q50_vlrinf,'f'),0,0,"R",0);
        $pdf->Cell(15,5,$k00_inscr,0,0,"R",0);
        $pdf->Cell(65,5,$contrib,0,0,"L",0);
        $pdf->Cell(20,5,$cgm_log,0,0,"R",0);
        $pdf->Cell(65,5,$logado,0,1,"L",0);
    }

    $pdf->Output();

}
?>