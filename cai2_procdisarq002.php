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
include("classes/db_diversos_classe.php");
include("classes/db_arreinscr_classe.php");
include("classes/db_arrematric_classe.php");
include("classes/db_arrecad_classe.php");
include("classes/db_disbancodiver_classe.php");
$cldiversos      = new cl_diversos;
$clarrematric    = new cl_arrematric;
$clarreinscr     = new cl_arreinscr;
$clarrecad       = new cl_arrecad;
$cldisbancodiver = new cl_disbancodiver;
db_postmemory($HTTP_POST_VARS);

$sql="select x33_dtvenc as datavenc from aguaconfvenc where x33_exerc = ".db_getsession("DB_anousu")." and x33_parcela = $parcela"; 
$result = pg_query($sql);
db_fieldsmemory($result, 0);

$data = split("-",$datavenc);
$dia = $data[2];
$historico = base64_decode($historico);
//echo " codret = $codret <br> data  = $datavenc <br> dia = $dia";
$sqlerro = false;
echo "<br>";

?>
<link href="estilos.css" rel="stylesheet" type="text/css"> 
<?


db_criatermometro('termometro', 'Concluido...', 'blue', 1);
echo "<br>";
$linhadesprocess = 0;
//$procedencia     = 52;
$instit          = db_getsession("DB_instit");
$sqldiferenca = "
	select disbanco.idret, 
	       disbanco.k00_numpre, 
	       disbanco.k00_numpar, 
	       disbanco.dtpago, 
	       disbanco.vlrpago, 
	       disbanco.vlrcalc, 
	       round(disbanco.vlrcalc - disbanco.vlrpago, 2) as diferenca 
	from disbanco 
	      inner join disarq on disarq.codret = disbanco.codret 
	where disbanco.codret = $codret 
	  and cast(round(disbanco.vlrcalc - disbanco.vlrpago, 2) as numeric) > cast(0 as numeric)
	  and disbanco.classi is true 
	  and disarq.autent   is false  
    and disbanco.instit = $instit";
$resultdiferenca = pg_query($sqldiferenca);
$linhasdiferenca = pg_num_rows($resultdiferenca);
if($linhasdiferenca >0){
  db_inicio_transacao();
  for($i=0;$i<$linhasdiferenca;$i++){
    db_fieldsmemory($resultdiferenca,$i);
    // verificar se  existe o disbanco.idret na disbancodiver
    // peger da disbancodiver o codigo do diversos
    // pegar o numpre da diversos e verificar se esta na arrecad
    // se estiver: deve excluir diversos e incluir novamente.
    db_atutermometro($i, $linhasdiferenca, 'termometro');

    $incluidiversos = false;
    $sqldisbancodiver  = "select k44_sequencial,k44_idret,k44_coddiver,dv05_coddiver, dv05_numpre,k00_numpre as numpre_arrecad ";
    $sqldisbancodiver .= "  from disbancodiver ";
    $sqldisbancodiver .= "       inner join diversos on  k44_coddiver = dv05_coddiver ";
    $sqldisbancodiver .= "       left  join arrecad  on  dv05_numpre  = k00_numpre ";
    $sqldisbancodiver .= " where k44_idret = $idret ";
    $resultdisbancodiver = pg_query($sqldisbancodiver);
    $linhasdisbancodiver = pg_num_rows($resultdisbancodiver);
    if($linhasdisbancodiver > 0){
      $linhadesprocess ++;
      db_fieldsmemory($resultdisbancodiver,0);
      if($numpre_arrecad != ""){
        //deve excluir diversos
        // ############ exclui do arrecad e inclui no arreold
        $result11=$clarrecad->sql_record($clarrecad->sql_query_file_instit("","arrecad.*","","arrecad.k00_numpre=$dv05_numpre and k00_instit = ".db_getsession('DB_instit') ));
        $numrows11=$clarrecad->numrows;
        if($numrows11>0){
          $clarrecad->excluir_arrecad($dv05_numpre);
          if($clarrecad->erro_status=="0"){
            $sqlerro=true;
          }
        }
        $result52=$clarreinscr->sql_record($clarreinscr->sql_query_file("","","*","","k00_numpre=$dv05_numpre"));
        $numrows52=$clarreinscr->numrows;
        if($numrows52>0){
          $clarreinscr->k00_numpre=$dv05_numpre;
          $clarreinscr->excluir($dv05_numpre);
          if($clarreinscr->erro_status==0){
            $sqlerro=true;
          }
        }
        $result22=$clarrematric->sql_record($clarrematric->sql_query_file("","","*","","k00_numpre=$dv05_numpre"));
        $numrows22=$clarrematric->numrows;
        if($numrows22>0){
          $clarrematric->k00_numpre=$dv05_numpre;
          $clarrematric->excluir($dv05_numpre);
          if($clarrematric->erro_status==0){
            $sqlerro=true;
          }
        }
         
        $cldisbancodiver->excluir($k44_sequencial);
        if($cldisbancodiver->erro_status=='0'){
           $sqlerro=true;
        }
        
        $cldiversos->excluir($dv05_coddiver);
        if($cldiversos->erro_status=='0'){
          $sqlerro=true;
        }
        // depois de exluir... incluir diversos
        //echo "<br>".pg_last_error()."<br>";
        //echo "<br>exclui o numpre $dv05_numpre ";
        $incluidiversos = true;
      }else{
        //não reprocessar
        //echo "<br> não processou ";
        $incluidiversos = false;
      }
       
    }else{
      // só incluir o diversos
      //echo "<br> incluir somente diversos ";
      $incluidiversos = true;
    }


    if(($incluidiversos == true) and ($botao == "processa")){
      //########## inclui diversos para as diferenças...###############
      // busca cgm
      $k00_numcgm = 0;

      $sqlcgm = " select k00_numcgm from arrenumcgm where k00_numpre = $k00_numpre";
      $resultcgm = pg_query($sqlcgm);
      $linhascgm = pg_num_rows($resultcgm);
      if($linhascgm>0){
        db_fieldsmemory($resultcgm,0);
      }

      if($k00_numcgm==0) {
        $sqlcgm  = " select arrenumcgm.k00_numcgm ";
        $sqlcgm .= "   from recibopaga ";
        $sqlcgm .= "        inner join arrenumcgm on arrenumcgm.k00_numpre = recibopaga.k00_numpre ";
        $sqlcgm .= "  where recibopaga.k00_numnov = $k00_numpre limit 1";
        $resultcgm = pg_query($sqlcgm);
        $linhascgm = pg_num_rows($resultcgm);
        if($linhascgm>0){
          db_fieldsmemory($resultcgm,0);
        }
      }

      if($k00_numcgm==0) {
        $sqlcgm = " select k00_numcgm from recibo where k00_numpre = $k00_numpre";
        $resultcgm = pg_query($sqlcgm);
        $linhascgm = pg_num_rows($resultcgm);
        if($linhascgm>0){
          db_fieldsmemory($resultcgm,0);
        }
      }


      $sqlerro=false;
      $result06=pg_query("select nextval('numpref_k03_numpre_seq')");
      db_fieldsmemory($result06,0);
      //echo "<br>erro = ".pg_last_error()."<br>";
      $dataatual = date("Y-m-d",db_getsession("DB_datausu"));
      $cldiversos->dv05_numcgm     = $k00_numcgm;
      $cldiversos->dv05_dtinsc     = $dataatual ;
      $cldiversos->dv05_exerc      = db_getsession("DB_anousu");
      $cldiversos->dv05_numpre     = $nextval;
      $cldiversos->dv05_vlrhis     = $diferenca;
      $cldiversos->dv05_procdiver  = $procedencia; //Procedência deste diverso.
      $cldiversos->dv05_numtot     = 1; // Uma parcela
      $cldiversos->dv05_privenc    = $datavenc; //Primeiro vencimento
      $cldiversos->dv05_provenc    = $datavenc; //Próximo vencimento.
      $cldiversos->dv05_diaprox    = $dia; //Dia dos próximos vencimentos
      $cldiversos->dv05_oper       = $dataatual;
      $cldiversos->dv05_valor      = $diferenca;
      $cldiversos->dv05_obs        = db_geratexto($historico);
      $cldiversos->dv05_instit     = db_getsession('DB_instit');
      $cldiversos->incluir(null);
      if($cldiversos->erro_status=='0'){
        $sqlerro=true;
        $msg = $cldiversos->erro_sql;
        db_msgbox("erro diversos .... ".$msg);
        exit;
      }else{
        $cod = $cldiversos->dv05_coddiver;
        //echo "<br>diversos incluidos = $cod  ";
      }

      //verificar se é de matricula ou inscrição
      $sqlmatric = "
			select arrematric.k00_matric 
			from recibo
			inner join arrematric on arrematric.k00_numpre = recibo.k00_numnov
			where recibo.k00_numnov = $k00_numpre
			union
			select arrematric.k00_matric 
			from recibopaga
			inner join arrematric on arrematric.k00_numpre = recibopaga.k00_numpre
			where recibopaga.k00_numnov = $k00_numpre
			union 
			select arrematric.k00_matric 
			from arrecant 
			inner join arrematric on arrematric.k00_numpre = arrecant.k00_numpre
			where arrecant.k00_numpre = $k00_numpre";
      $resultmatric = pg_query($sqlmatric);
      $linhasmatric = pg_num_rows($resultmatric);
      if($linhasmatric>0){
        db_fieldsmemory($resultmatric,0);
        //inclui na arrematric
       // echo "<br>incluiu matricula = $k00_matric";
        $clarrematric->k00_numpre=$nextval;
        $clarrematric->k00_matric=$k00_matric;
        $clarrematric->incluir($nextval,$k00_matric);
        if($clarrematric->erro_status=='0'){
          $sqlerro=true;
          $msg = $clarrematric->erro_sql;
          db_msgbox("erro ".$msg);
          exit;
        }
      }
      // verifica se é de inscricao

      $sqlinscr = "
select arreinscr.k00_inscr 
from recibo
inner join arreinscr on arreinscr.k00_numpre = recibo.k00_numnov
where recibo.k00_numnov = $k00_numpre
union
select arreinscr.k00_inscr 
from recibopaga
inner join arreinscr on arreinscr.k00_numpre = recibopaga.k00_numpre
where recibopaga.k00_numnov = $k00_numpre
union 
select arreinscr.k00_inscr 
from arrecant 
inner join arreinscr on arreinscr.k00_numpre = arrecant.k00_numpre
where arrecant.k00_numpre = $k00_numpre";
      $resultinscr = pg_query($sqlinscr);
      $linhasinscr = pg_num_rows($resultinscr);
      if($linhasinscr>0){
        db_fieldsmemory($resultinscr,0);
        $clarreinscr->k00_numpre=$nextval;
        $clarreinscr->k00_inscr=$k00_inscrr;
        $clarreinscr->incluir($nextval,$k00_inscr);
        if($clarreinscr->erro_status=='0'){
          $sqlerro=true;
          $msg = $clarreinscr->erro_sql;
          db_msgbox("erro ".$msg);
          exit;
        }
      }


      $sqlArretipo = " select dv09_tipo as arretipo from procdiver where dv09_procdiver = $procedencia and dv09_instit = ".db_getsession('DB_instit') ;
      $rsArretipo  = pg_query($sqlArretipo);
      if (pg_num_rows($rsArretipo) > 0 ){
        db_fieldsmemory($rsArretipo,0);
      }else{
        db_msgbox("Configure o tipo de debitos destino para a procedencia selecionada ! ");
        //db_redireciona('dvr3_diversos005.php');
        exit;
      }

      $result09 = pg_query("select fc_geraarrecad(7,$nextval,true,2) as retorno") ;
      if (pg_num_rows($result09) > 0 ) {
        db_fieldsmemory($result09,0);
        $iRetorno = substr(trim($retorno),0,1);
        if ($iRetorno != '9') {
          $cldiversos->erro_msg = $retorno;
          $sqlerro=true;
        }
      }else{
        db_msgbox("Ocorreu um problema durante a gereção do diverso, contate suporte ");
        $sqlerro=true;
      }

      $cldisbancodiver->k44_idret     = $idret;
      $cldisbancodiver->k44_coddiver  = $cldiversos->dv05_coddiver;
      $cldisbancodiver->incluir(null);
      if($cldisbancodiver->erro_status=='0'){
        $sqlerro=true;
        $msg = $cldisbancodiver->erro_sql;
        db_msgbox("erro ".$msg);
        exit;
      }

      // ################ fim do incluir diversos ####################
    }
  }//for

  db_fim_transacao($sqlerro);
  if($botao == "processa" and $sqlerro== false){
    db_msgbox("Arquivo processado com sucesso.");
  }elseif($botao == "desprocessa" and $sqlerro== false and $linhadesprocess==0){
    db_msgbox("Arquivo sem registros a desprocessar.");
  }elseif($botao == "desprocessa" and $sqlerro== false){
    db_msgbox("Arquivo desprocessado com sucesso.");
  }
 
  

}else{
  db_msgbox("Não existe diferenças a serem processadas.");
}

echo "<script>parent.db_iframe_relatorio.hide();</script>";

?>