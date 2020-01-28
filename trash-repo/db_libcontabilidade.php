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

/**
 * classes e fun��es contabeis
 * @package contabilidade
 * Revis�o$Author: dbfabrizio $
 * @version $Revision: 1.1 $
*/
 
include ("classes/db_contranslan_classe.php");

//|00|//cl_despdesdobramento
//|10|// emite despesa por desdobramento 
//|10|// quando o orcamento e no elemento, o campo c60_estrut aponta para o elemento e o56_elemento para o desdobramento
//|15|//[variavel] = new cl_despdesdobramento();
class cl_desdobramento {
  
  function sql($where = "", $dtini, $dtfim, $w_instit = "(1) ", $w_elemento = "") {
    $sql = "select
    /* orcdotacao.o58_codele,*/
    conplano.c60_estrut,
    conplano.c60_descr,
    substr(ele.o56_elemento||'00',1,15) as o56_elemento,
    ele.o56_descr,
    sum(case when c53_tipo = 10  then c70_valor else 0 end ) as empenhado,
    sum(case when c53_tipo = 11  then c70_valor else 0 end ) as empenhado_estornado,
    sum(case when c53_tipo = 20  then c70_valor else 0 end ) as liquidado,
    sum(case when c53_tipo = 21  then c70_valor else 0 end ) as liquidado_estornado,
    sum(case when c53_tipo = 30  then c70_valor else 0 end ) as pagamento,
    sum(case when c53_tipo = 31  then c70_valor else 0 end ) as pagamento_estornado	             
    from conlancamele
    inner join conlancam on c67_codlan=c70_codlan
    inner join conlancamemp on c75_codlan = c70_codlan
    inner join empempenho on e60_numemp = c75_numemp and e60_anousu=".db_getsession("DB_anousu")."
    inner join orcdotacao on o58_coddot = empempenho.e60_coddot  and o58_anousu = e60_anousu
    $w_elemento                                 
    inner join conplano on c60_codcon = orcdotacao.o58_codele and c60_anousu=".db_getsession("DB_anousu")."
    inner join conlancamdoc on c71_codlan=c70_codlan
    inner join conhistdoc on c71_coddoc=c53_coddoc
    inner join orcelemento ele on ele.o56_codele=conlancamele.c67_codele and 
    ele.o56_anousu = o58_anousu                 
    where ";
    if ($where != "") {
      $sql .= " $where and ";
    }
    $sql .= "                         
    empempenho.e60_instit in $w_instit
    and ( conlancam.c70_data >='$dtini' and conlancam.c70_data <='$dtfim' )
    and conhistdoc.c53_tipo in (10,11,20,21,30,31)
    group by /* o58_codele, */
    c60_estrut,
    c60_descr,
    o56_elemento,
    o56_descr	         
    order by 
    o56_elemento
    ";
    return $sql;
  }
}

class cl_receita_saldo_mes {
  //|00|//cl_receita_saldo_mes
  //|10|//calcula a receita arrecadada por mes
  //|15|//[variavel] = new cl_receita_saldo_mes;
  var $receita = null;
  var $anousu = null;
  var $sql = null;
  var $numrows = 0;
  var $result = false;
  var $dtini = null;
  var $dtfim = null;
  var $estrut = null; // string de estruturais
  var $instit = null;
  var $usa_datas = null; // essa variavel indica o usuo das variaveis dtini e dtfim
  
  function sql_query($receita) {
    //		echo("dtini: " . $this->dtini . " - dtfim: " . $this->dtfim . " - instit: " . $this->instit . " - ano: " . $this->anousu . "<br>");
    if ($this->anousu == null)
    $this->anousu = db_getsession("DB_anousu");
    if ($this->dtini == null)
    db_msgbox('Data inicio n�o informada.');
    if ($this->dtfim == null)
    db_msgbox('Data final n�o informada.');
    
    if ($this->instit == null) {
      if (db_getsession("DB_instit") == 1)
      $ins = " (1)";
      else
      if (db_getsession("DB_instit") == 2)
      $ins = "(2)";
      else
      $ins = " ( 3 ) ";
    } else {
      $ins = "(".$this->instit.")";
    }
    
    $this->sql = " select * from (
    SELECT O70_ANOUSU,O70_CODREC,
    o70_instit,
    O57_FONTE,O57_DESCR,
    O70_VALOR::float8 as o70_valor,		      
    sum(ADICIONAL)::float8 as adicional,		      
    SUM(JANEIRO) AS JANEIRO,
    SUM(FEVEREIRO) AS FEVEREIRO,
    SUM(MARCO) AS MARCO,
    SUM(ABRIL) AS ABRIL,
    SUM(MAIO) AS MAIO,
    SUM(JUNHO) AS JUNHO,
    SUM(JULHO) AS JULHO,
    SUM(AGOSTO) AS AGOSTO,
    SUM(SETEMBRO) AS SETEMBRO,
    SUM(OUTUBRO) AS OUTUBRO,
    SUM(NOVEMBRO) AS NOVEMBRO,
    SUM(DEZEMBRO) AS DEZEMBRO,
    prev_jan::float8 as prev_jan,
    prev_fev::float8 as prev_fev,
    prev_mar::float8 as prev_mar,
    prev_abr::float8 as prev_abr,
    prev_mai::float8 as prev_mai,
    prev_jun::float8 as prev_jun,
    prev_jul::float8 as prev_jul,
    prev_ago::float8 as prev_ago,
    prev_set::float8 as prev_set,
    prev_out::float8 as prev_out,
    prev_nov::float8 as prev_nov,
    prev_dez::float8 as prev_dez
    
    FROM (
    SELECT O70_ANOUSU,
    O70_CODREC,
    o70_instit,
    o57_fonte,
    o57_descr,
    O70_VALOR,
    ADICIONAL,
    
    sum(case when o34_mes=1 then o34_valor else 0.0 end) as  prev_jan,
    sum(case when o34_mes=2 then o34_valor else 0.0 end) as  prev_fev,
    sum(case when o34_mes=3 then o34_valor else 0.0 end) as  prev_mar,
    sum(case when o34_mes=4 then o34_valor else 0.0 end) as  prev_abr,
    sum(case when o34_mes=5 then o34_valor else 0.0 end) as  prev_mai,
    sum(case when o34_mes=6 then o34_valor else 0.0 end) as  prev_jun,
    sum(case when o34_mes=7 then o34_valor else 0.0 end) as  prev_jul,
    sum(case when o34_mes=8 then o34_valor else 0.0 end) as  prev_ago,
    sum(case when o34_mes=9 then o34_valor else 0.0 end) as  prev_set,
    sum(case when o34_mes=10 then o34_valor else 0.0 end) as prev_out,
    sum(case when o34_mes=11 then o34_valor else 0.0 end) as prev_nov,
    sum(case when o34_mes=12 then o34_valor else 0.0 end) as prev_dez,															    
    
    CASE WHEN O71_MES = 1 THEN ARRECADADO ELSE 0::FLOAT8 END AS JANEIRO,
    CASE WHEN O71_MES = 2 THEN ARRECADADO ELSE 0::FLOAT8 END AS FEVEREIRO,
    CASE WHEN O71_MES = 3 THEN ARRECADADO ELSE 0::FLOAT8 END AS MARCO,
    CASE WHEN O71_MES = 4 THEN ARRECADADO ELSE 0::FLOAT8 END AS ABRIL,
    CASE WHEN O71_MES = 5 THEN ARRECADADO ELSE 0::FLOAT8 END AS MAIO,
    CASE WHEN O71_MES = 6 THEN ARRECADADO ELSE 0::FLOAT8 END AS JUNHO,
    CASE WHEN O71_MES = 7 THEN ARRECADADO ELSE 0::FLOAT8 END AS JULHO,
    CASE WHEN O71_MES = 8 THEN ARRECADADO ELSE 0::FLOAT8 END AS AGOSTO,
    CASE WHEN O71_MES = 9 THEN ARRECADADO ELSE 0::FLOAT8 END AS SETEMBRO,
    CASE WHEN O71_MES =10 THEN ARRECADADO ELSE 0::FLOAT8 END AS OUTUBRO,
    CASE WHEN O71_MES =11 THEN ARRECADADO ELSE 0::FLOAT8 END AS NOVEMBRO,
    CASE WHEN O71_MES =12 THEN ARRECADADO ELSE 0::FLOAT8 END AS DEZEMBRO
    FROM (
    SELECT O70_ANOUSU,O70_CODREC,
    o70_instit,
    o57_fonte,
    o57_descr,
    TO_CHAR(C70_DATA,'MM')::integer AS O71_MES,
    O70_VALOR,
    o34_mes,
    o34_valor,
    round(SUM(CASE C53_TIPO WHEN 110 THEN                               
    case when fc_conplano_grupo( O70_ANOUSU, substr(o57_fonte,1,2) || '%', 9000 ) is false then ROUND(C70_VALOR,2)::FLOAT8 else ROUND(C70_VALOR*-1,2)::FLOAT8 end
    WHEN 111 THEN 	                      
    case when fc_conplano_grupo( O70_ANOUSU, substr(o57_fonte,1,2) || '%', 9000 ) is false then ROUND(C70_VALOR*-1,2)::FLOAT8 else ROUND(C70_VALOR,2)::FLOAT8 end			      
    ELSE 0::FLOAT8 END ),2) AS ADICIONAL,
    round(SUM( CASE C53_TIPO WHEN 100 THEN 
    case when fc_conplano_grupo( O70_ANOUSU, substr(o57_fonte,1,2) || '%', 9000 ) is false then ROUND(C70_VALOR,2)::FLOAT8 
    else ROUND(C70_VALOR*-1,2)::FLOAT8 end			      
    WHEN 101 THEN 	                      
    case when fc_conplano_grupo( O70_ANOUSU, substr(o57_fonte,1,2) || '%', 9000 ) is false then ROUND(C70_VALOR*-1,2)::FLOAT8 
    else ROUND(C70_VALOR,2)::FLOAT8 end
    ELSE 0::FLOAT8 END ),2) AS ARRECADADO
    FROM ORCRECEITA
    left JOIN CONLANCAMREC ON C74_ANOUSU = O70_ANOUSU AND C74_CODREC = O70_CODREC ".($this->usa_datas != null?"AND c74_data between '".$this->dtini."' and '".$this->dtfim."'":"")."
    left JOIN CONLANCAM    ON C74_CODLAN = C70_CODLAN
    left JOIN CONLANCAMDOC ON C71_CODLAN = C70_CODLAN
    left JOIN ORCFONTES ON O70_CODFON = O57_CODFON AND O57_ANOUSU = O70_ANOUSU
    left JOIN CONHISTDOC ON C53_CODDOC = C71_CODDOC
    left join (
    select
    o34_mes,o34_valor,o34_codrec,o34_anousu
    from orcprevrec
    ) as rc on rc.o34_anousu = orcreceita.o70_anousu
    and rc.o34_codrec = orcreceita.o70_codrec
    
    
    WHERE O70_ANOUSU = ".$this->anousu."  and o70_instit in $ins          
    ";
    if ($this->usa_datas != null) {
      $this->sql .= " AND ( c70_data between '".$this->dtini."' and '".$this->dtfim."'  or c70_data is null)";
    }
    
    if ($this->receita != null) {
      $this->sql .= " AND O70_CODREC = ".$this->receita;
    }
    
    $this->sql .= " GROUP BY O70_ANOUSU,O70_CODREC,o70_instit,O71_MES,O57_FONTE,O57_DESCR, O70_VALOR,o34_mes,o34_valor
    ) AS X 
    
    group by O70_ANOUSU, O70_CODREC, o70_instit, O71_MES,ARRECADADO,o57_fonte, o57_descr, O70_VALOR, ADICIONAL  
    
    
    ";
    
    $this->sql .= ") AS Y
    GROUP BY O70_ANOUSU,O70_CODREC,o70_instit,O57_FONTE,O57_DESCR,O70_VALOR,
    prev_jan,
    prev_fev,
    prev_mar,
    prev_abr,
    prev_mai,
    prev_jun,
    prev_jul,
    prev_ago,
    prev_set,
    prev_out,
    prev_nov,
    prev_dez																						  
    
    ) as X
    
    ";
    if ($this->receita != null) {
      $this->sql .= " AND O70_CODREC = ".$this->receita;
      
    }
    
    $this->sql .= " order by o57_fonte ";
    
  }
  function sql_record_file() {
    if ($this->sql == null)
    $this->sql_query($this->receita);
    $this->result = pg_query($this->sql);
    if ($this->result != false)
    $this->numrows = pg_numrows($this->result);
    else
    $this->numrows = 0;
  }
  function sql_record() {
    global $o70_anousu, $o70_codrec, $o57_fonte, $o57_descr, $janeiro, $fevereiro, $marco, $abril, $maio, $junho, $julho, $agosto, $setembro, $outubro, $novembro, $dezembro, $o70_valor, $adicional;
    global $prev_jan, $prev_fev, $prev_mar, $prev_abr, $prev_mai, $prev_jun, $prev_jul, $prev_ago, $prev_set, $prev_out, $prev_nov, $prev_dez;
    
    if ($this->sql == null)
    $this->sql_query($this->receita);
    
    //    echo($this->sql . "<br>");
    
    
    pg_exec("begin");
    pg_exec("create temporary table work_plano as ".$this->sql);
    pg_exec("create index work_plano_estrut on work_plano(o57_fonte)");
    $result = pg_query("select * from work_plano");
    for ($i = 0; $i < pg_numrows($result); $i ++) {
      db_fieldsmemory($result, $i);
      $estrutural = $o57_fonte;
      for ($ii = 1; $ii < 10; $ii ++) {
        ///o z� colocou isso... 19042005
        if ($estrutural == "") {
          continue;
        }
        $estrutural = db_le_mae_conplano($estrutural);
        $nivel = db_le_mae_conplano($estrutural, true);
        $result_estrut = pg_query("select o57_descr from work_plano where o57_fonte = '$estrutural'");
        // db_criatabela($result_estrut); exit;
        //if ($estrutural == '411229900000000'){
          //   echo ($estrutural." ->" .$o70_valor ." <br>"); 
        // }  
        if (pg_numrows($result_estrut) == 0) {
          $result_estrut = pg_query("select o57_descr from orcfontes where o57_anousu = ".$this->anousu." and o57_fonte = '$estrutural'");
          
          if (pg_numrows($result_estrut) == 0) {
            echo "Conta n�o encontrada nas fontes de Receita Comando:"."select o57_descr from orcfontes where o57_anousu = ".$this->anousu." and o57_fonte = '$estrutural'";
            exit;
          }
          db_fieldsmemory($result_estrut, 0);
          
          $result_1 = pg_query("insert into work_plano values(
          ".$this->anousu.",
          0,
          0,
          '$estrutural',
          '$o57_descr',
          $o70_valor,
          $adicional,
          $janeiro,
          $fevereiro,
          $marco,
          $abril,
          $maio,
          $junho,
          $julho,
          $agosto,
          $setembro,
          $outubro,
          $novembro,
          $dezembro,
          $prev_jan,
          $prev_fev,
          $prev_mar,
          $prev_abr,
          $prev_mai,
          $prev_jun,
          $prev_jul,
          $prev_ago,
          $prev_set,
          $prev_out,
          $prev_nov,
          $prev_dez
          )
          ");
        } else {
          pg_query("update work_plano set 
          o70_valor = o70_valor + $o70_valor,
          adicional= adicional  +$adicional,
          janeiro  = janeiro    +$janeiro,
          fevereiro= fevereiro  +$fevereiro,
          marco    = marco      +$marco,
          abril    = abril      +$abril,
          maio     = maio       +$maio,
          junho    = junho      +$junho,
          julho    = julho      +$julho,
          agosto   = agosto     +$agosto,
          setembro = setembro   +$setembro,
          outubro  = outubro    +$outubro,
          novembro = novembro   +$novembro,
          dezembro = dezembro   +$dezembro,
          prev_jan  = prev_jan  +$prev_jan,
          prev_fev  = prev_fev  +$prev_fev+0.0,
          prev_mar  = prev_mar  +$prev_mar+0.0,
          prev_abr  = prev_abr  +$prev_abr+0.0,
          prev_mai  = prev_mai  +$prev_mai+0.0,
          prev_jun  = prev_jun  +$prev_jun+0.0,
          prev_jul  = prev_jul  +$prev_jul+0.0,
          prev_ago  = prev_ago  +$prev_ago+0.0,
          prev_set  = prev_set  +$prev_set+0.0,
          prev_out  = prev_out  +$prev_out+0.0,
          prev_nov  = prev_nov  +$prev_nov+0.0,
          prev_dez  = prev_dez  +$prev_dez+0.0			    
          
          
          where o57_fonte = '$estrutural'");
        }
        /*
        o70_valor = o70_valor+$o70_valor,
        adicional= adicional+$adicional,
        
        */
        
        if ($nivel == 1)
        break;
      }
    }
    //exit;   
    $sql = " 
    SELECT O70_ANOUSU,O70_CODREC,o70_instit,O57_FONTE,O57_DESCR,round(O70_VALOR,2) as o70_valor,
    round(adicional,2) as adicional,
    round(JANEIRO,2 ) as JANEIRO,
    round(FEVEREIRO,2) as FEVEREIRO,
    round(MARCO,2  )  as MARCO,
    round(ABRIL,2  )  as ABRIL,
    round(MAIO, 2  )  as MAIO,
    round(JUNHO,2  )  as JUNHO,
    round(JULHO,2  )  as JULHO,
    round(AGOSTO,2 )  as AGOSTO,
    round(SETEMBRO,2)  as SETEMBRO,
    round(OUTUBRO,2 )  as OUTUBRO,
    round(NOVEMBRO,2)  as NOVEMBRO,
    round(DEZEMBRO,2)  as DEZEMBRO,
    prev_jan,
    prev_fev,
    prev_mar,
    prev_abr,
    prev_mai,
    prev_jun,
    prev_jul,
    prev_ago,
    prev_set,
    prev_out,
    prev_nov,
    prev_dez
    
    from work_plano ";
    //--            		
    if ($this->estrut != null) {
      $sql .= "where O57_FONTE IN  ".$this->estrut;
    }
    $sql .= "order by o57_fonte ";
    $this->result = pg_query($sql);
    //db_criatabela($this->result);
    if ($this->result != false) {
      $this->numrows = pg_numrows($this->result);
    } else
    $this->numrows = 0;
  }
  
} //fim classe

//|00|//cl_translan
//|10|//pega a picture de um determinado campo do orcparametro e gera um input text com a formatacao da mesma
//|15|//[variavel] = new cl_estrutura;
class cl_translan extends cl_contranslan {
  
  var $arr_credito = null;
  var $arr_debito = null;
  var $arr_histori = null;
  var $arr_seqtranslr = null;
  var $coddoc = null;
  var $sql = null;
  var $numrows = null;
  var $result = null;
  var $conta_emp = null; //variavel usada no pagamento de RP
  var $sqlerro = false;
  
  var $it = null;
  // var $it  = "sapiranga";
  // var $it  = "alegrete";
  // var $it  = "guaiba";
  
  function cl_translan() {
    // carlos, alterando libs
  }
  
  function cl_zera_variaveis() {
    $this->arr_credito = null;
    $this->arr_debito = null;
    $this->arr_histori = null;
    $this->arr_seqtranslr = null;
    $this->coddoc = null;
    $this->sql = null;
    $this->numrows = null;
    $this->result = null;
    $this->conta_emp = null; //variavel usada no pagamento de RP
    $this->sqlerro = false;
  }
  
  /* 
  *  Fun��o que retorna o os creditos e os debitos do empenho
  */
  function db_trans_empenho($codcom = null, $anousu) {
    global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c47_seqtranslr, $c46_seqtranslan;
    
    $this->cl_zera_variaveis();
    
    $this->coddoc = 1;
    $this->sql = $this->sql_query_lr(null, "c46_seqtranslan,c47_seqtranslr,c46_codhist,c47_credito,c47_debito,c47_ref", '', "c45_coddoc = ".$this->coddoc." and c45_anousu=".db_getsession("DB_anousu")." and c47_anousu=$anousu");
    $this->result = $this->sql_record($this->sql);
    $cont = 0;
    $arr_lans = array ();
    for ($i = 0; $i < $this->numrows; $i ++) {
      db_fieldsmemory($this->result, $i);
      if ($c47_ref == 0 || ($c47_ref != 0 && $c47_ref == $codcom)) {
        
        //------------------------------------------------------------------------
        //verifica��o para naum incluir duas vezes o mesmo seqtranslan    
        if (array_key_exists($c46_seqtranslan, $arr_lans)) {
          continue;
        } else {
          $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
        }
        //------------------------------------------------------------------------
        
        $this->arr_credito[$cont] = $c47_credito;
        $this->arr_debito[$cont] = $c47_debito;
        $this->arr_histori[$cont] = $c46_codhist;
        $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
        $cont ++;
      }
    }
    
  }
  /* 
  *  Fun��o que retorna o os creditos e os debitos  do estorno do empenho
  */
  function db_trans_estorna_empenho($codcom = null, $anousu) {
    global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c47_seqtranslr, $c46_seqtranslan;
    $this->cl_zera_variaveis();
    $this->coddoc = 2;
    $this->sql = $this->sql_query_lr(null, "c46_seqtranslan,c47_seqtranslr,c46_codhist,c47_credito,c47_debito,c47_ref", '', "c45_coddoc = ".$this->coddoc." and c45_anousu=".db_getsession("DB_anousu")." and c47_anousu=$anousu");
    $this->result = $this->sql_record($this->sql);
    $cont = 0;
    $arr_lans = array ();
    for ($i = 0; $i < $this->numrows; $i ++) {
      db_fieldsmemory($this->result, $i);
      if ($c47_ref == 0 || ($c47_ref != 0 && $c47_ref == $codcom)) {
        
        //------------------------------------------------------------------------
        //verifica��o para naum incluir duas vezes o mesmo seqtranslan    
        if (array_key_exists($c46_seqtranslan, $arr_lans)) {
          continue;
        } else {
          $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
        }
        //------------------------------------------------------------------------
        
        $this->arr_credito[$cont] = $c47_credito;
        $this->arr_debito[$cont] = $c47_debito;
        $this->arr_histori[$cont] = $c46_codhist;
        $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
        $cont ++;
      }
    }
  }
  
  /* 
  *  Fun��o que retorna o os creditos e os debitos  da  liquidacao do empenho
  *    Quando converter base de dados deve ser indicado o seqtranslan do lan�amento de liquida��o  que tem varios elementos... 
  *   $seqtranslan = 15 na base dbseller 
  *   $seqtranslan = 3  na base guaiba_2112
  
  */
  
  function db_trans_liquida($codcom, $codele, $anousu) {
    global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c61_reduz, $c47_compara;
    $this->cl_zera_variaveis();
    $this->coddoc = 3;
    
    if ($codele != "") {
      $this->result = $this->sql_record("select c61_reduz from conplanoreduz where c61_codcon=$codele and c61_anousu=".db_getsession("DB_anousu")."  and c61_instit=".db_getsession("DB_instit"));
      if ($this->numrows > 0) {
        db_fieldsmemory($this->result, 0);
        $codele = $c61_reduz;
      }
    }
    $this->sql = $this->sql_query_lr(null, "c47_compara,c47_seqtranslr,c47_ref,c46_seqtranslan,c46_codhist,c47_credito,c47_debito", '', "c45_coddoc = ".$this->coddoc." and c45_anousu=".db_getsession("DB_anousu")." and c47_anousu=$anousu");
    $this->result = $this->sql_record($this->sql);
    $cont = 0;
    $arr_lans = array ();
    
    for ($i = 0; $i < $this->numrows; $i ++) {
      db_fieldsmemory($this->result, $i);
      if ($c47_compara == 1) {
        $comparador = $c47_debito;
      } else
      if ($c47_compara == 2) {
        $comparador = $c47_credito;
      } else
      if ($c47_compara == 3) {
        $comparador = $c47_ref;
      } else {
        $comparador = 0;
      }
      if (($c47_ref == '' || $c47_ref == 0 || ($c47_ref != 0 && ($c47_ref == $codcom || $c47_compara == 3))) && ($c47_compara == 0 || $comparador == $codele)) {
        
        //------------------------------------------------------------------------
        //verifica��o para naum incluir duas vezes o mesmo seqtranslan    
        if (array_key_exists($c46_seqtranslan, $arr_lans)) {
          continue;
        } else {
          $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
        }
        //------------------------------------------------------------------------
        
        $this->arr_credito[$cont] = $c47_credito;
        $this->arr_debito[$cont] = $c47_debito;
        $this->arr_histori[$cont] = $c46_codhist;
        $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
        $cont ++;
      }
    }
  }
  /* 
  *  Fun��o que retorna o os creditos e os debitos  da liquidaca��o de empenho de capital  quando for o inicio do estrut for 34
  *   Preciso indicar o seqtranslan do primeiro lan�amento da liquida��o capital, o que tem varios elementos
  *   $seqtranslan = 22 na base dbseller 
  *   $seqtranslan = 4  na base guaiba_2112
  */
  function db_trans_liquida_capital($codcom, $codele, $anousu) {
    global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c61_reduz, $c47_compara;
    $this->cl_zera_variaveis();
    $this->coddoc = 23;
    if ($codele != "") {
      $this->result = $this->sql_record("select c61_reduz from conplanoreduz where c61_codcon=$codele and c61_anousu=".db_getsession("DB_anousu")." and c61_instit=".db_getsession("DB_instit"));
      if ($this->numrows > 0) {
        db_fieldsmemory($this->result, 0);
        $codele = $c61_reduz;
      }
    }
    $this->sql = $this->sql_query_lr(null, "c47_compara,c47_seqtranslr,c47_ref,c46_seqtranslan,c46_codhist,c47_credito,c47_debito", '', "c45_coddoc = ".$this->coddoc." and c45_anousu=".db_getsession("DB_anousu")." and c47_anousu=$anousu");
    $this->result = $this->sql_record($this->sql);
    $cont = 0;
    $arr_lans = array ();
    
    for ($i = 0; $i < $this->numrows; $i ++) {
      db_fieldsmemory($this->result, $i);
      //comparador deve ser por debito
      if ($c47_compara == 1) {
        $comparador = $c47_debito;
      } else
      if ($c47_compara == 2) {
        $comparador = $c47_credito;
      } else
      if ($c47_compara == 3) {
        $comparador = $c47_ref;
      } else {
        $comparador = 0;
      }
      if (($c47_ref == '' || $c47_ref == 0 || ($c47_ref != 0 && ($c47_ref == $codcom || $c47_compara == 3))) && ($c47_compara == 0 || $comparador == $codele)) {
        //------------------------------------------------------------------------
        //verifica��o para naum incluir duas vezes o mesmo seqtranslan    
        if (array_key_exists($c46_seqtranslan, $arr_lans)) {
          continue;
        } else {
          $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
        }
        //------------------------------------------------------------------------
        
        $this->arr_credito[$cont] = $c47_credito;
        $this->arr_debito[$cont] = $c47_debito;
        $this->arr_histori[$cont] = $c46_codhist;
        $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
        $cont ++;
      }
    }
  }
  /* 
  *  Fun��o que retorna o os creditos e os debitos  do estorno liquidaca��o de empenho   quando for o inicio do estrut for 33
  *    Necess�rio informar o seqtranslan de estorno de liquida��o
  *    $seqtranslan = 30 na base dbseller
  *    $seqtranslan = 34 na base guaiba_2112
  */
  function db_trans_estorna_liquida($codcom, $codele, $anousu) {
    global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c61_reduz, $c47_compara;
    $this->cl_zera_variaveis();
    $this->coddoc = 4;
    
    if ($codele != "") {
      $this->result = $this->sql_record("select c61_reduz from conplanoreduz where c61_codcon=$codele and c61_anousu=".db_getsession("DB_anousu")." and c61_instit=".db_getsession("DB_instit"));
      if ($this->numrows > 0) {
        db_fieldsmemory($this->result, 0);
        $codele = $c61_reduz;
      }
    }
    $this->sql = $this->sql_query_lr(null, "c47_compara,c47_seqtranslr,c47_ref,c46_seqtranslan,c46_codhist,c47_credito,c47_debito", '', "c45_coddoc = ".$this->coddoc." and c45_anousu=".db_getsession("DB_anousu")."  and c47_anousu=$anousu");
    $this->result = $this->sql_record($this->sql);
    $cont = 0;
    $arr_lans = array ();
    for ($i = 0; $i < $this->numrows; $i ++) {
      db_fieldsmemory($this->result, $i);
      
      //comparador deve sCVer por credito
      if ($c47_compara == 1) {
        $comparador = $c47_debito;
      } else
      if ($c47_compara == 2) {
        $comparador = $c47_credito;
      } else
      if ($c47_compara == 3) {
        $comparador = $c47_ref;
      } else {
        $comparador = 0;
      }
      if (($c47_ref == '' || $c47_ref == 0 || ($c47_ref != 0 && ($c47_ref == $codcom || $c47_compara == 3))) && ($c47_compara == 0 || $comparador == $codele)) {
        //------------------------------------------------------------------------
        //verifica��o para naum incluir duas vezes o mesmo seqtranslan    
        if (array_key_exists($c46_seqtranslan, $arr_lans)) {
          continue;
        } else {
          $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
        }
        //------------------------------------------------------------------------
        
        $this->arr_credito[$cont] = $c47_credito;
        $this->arr_debito[$cont] = $c47_debito;
        $this->arr_histori[$cont] = $c46_codhist;
        $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
        $cont ++;
      }
    }
  }
  
  /* *****************************  	*	**********************************	*	*****************	***
  *  Fun��o que retorna o os creditos e os debitos  do estorno  liquidaca��o de empenho de capital  quando for o inicio do estrut for 34
  *    Necess�rio informar o seqtranslan de estorno de liquida��o capital
  *    $seqtranslan = 38 a base dbseller
  *    $seqtranslan = 42 a base guaiba_2112
  */
  function db_trans_estorna_liquida_capital($codcom, $codele, $anousu) {
    global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c61_reduz, $c47_compara;
    $this->cl_zera_variaveis();
    $this->coddoc = 24;
    
    if ($codele != "") {
      $this->result = $this->sql_record("select c61_reduz from conplanoreduz where c61_codcon=$codele and c61_anousu=".db_getsession("DB_anousu")." and c61_instit=".db_getsession("DB_instit"));
      if ($this->numrows > 0) {
        db_fieldsmemory($this->result, 0);
        $codele = $c61_reduz;
      }
    }
    $this->sql = $this->sql_query_lr(null, "c47_compara,c47_seqtranslr,c47_ref,c46_seqtranslan,c46_codhist,c47_credito,c47_debito", '', "c45_coddoc = ".$this->coddoc." and c45_anousu=".db_getsession("DB_anousu")." and c47_anousu=$anousu");
    $this->result = $this->sql_record($this->sql);
    $cont = 0;
    $arr_lans = array ();
    for ($i = 0; $i < $this->numrows; $i ++) {
      db_fieldsmemory($this->result, $i);
      //comparador deve ser por credito
      if ($c47_compara == 1) {
        $comparador = $c47_debito;
      } else
      if ($c47_compara == 2) {
        $comparador = $c47_credito;
      } else
      if ($c47_compara == 3) {
        $comparador = $c47_ref;
      } else {
        $comparador = 0;
      }
      if (($c47_ref == '' || $c47_ref == 0 || ($c47_ref != 0 && ($c47_ref == $codcom || $c47_compara == 3))) && ($c47_compara == 0 || $comparador == $codele)) {
        //------------------------------------------------------------------------
        //verifica��o para naum incluir duas vezes o mesmo seqtranslan    
        if (array_key_exists($c46_seqtranslan, $arr_lans)) {
          continue;
        } else {
          $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
        }
        //------------------------------------------------------------------------
        
        $this->arr_credito[$cont] = $c47_credito;
        $this->arr_debito[$cont] = $c47_debito;
        $this->arr_histori[$cont] = $c46_codhist;
        $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
        $cont ++;
      }
    }
  }
  
  /* 
  *  Fun��o que retorna o os creditos e os debitos  do pagamento de empenho  
  *    Quando converter base de dados deve ser indicado o seqtranslan do lan�amento de liquida��o  que tem varios elementos... 
  *    normalmente � o primeiro lan�amento
  
  para pagamento � preciso indicar o primeiro lan�amento da liquidacao
  *   $seqtranslan = 15 na base dbseller 
  *   $seqtranslan = 3  na base guaiba_2112
  
  
  liquida��o capital
  *    $seqtranslan_liq_capital = 22 a base dbseller
  *    $seqtranslan_liq         = 4 a base guaiba_2112
  tambem � preciso indicar o codigo do historico
  
  */
  function db_trans_pagamento($codele, $reduzido, $anousu) {
    global $c46_codhist, $c47_credito, $c47_debito, $c47_seqtranslr, $c61_reduz;
    $this->cl_zera_variaveis();
    $this->coddoc = 5;
    
    $coddoc_liq = 3;
    $coddoc_liq_capital = 23;
    
    $codhist = 9005;
    if ($codele != "") {
      $this->result = $this->sql_record("select c61_reduz from conplanoreduz where c61_codcon=".$codele." and c61_anousu=".db_getsession("DB_anousu")." and c61_instit=".db_getsession("DB_instit"));
      if ($this->numrows > 0) {
        db_fieldsmemory($this->result, 0);
        $codele = $c61_reduz;
      }
    }
    $cont = 0;
    //rotina que pega o o valor que foi creditado na liquidacao para colocar no debit  do pagamento...
    $sql = "select c47_credito,c47_seqtranslr,c46_codhist 
    from contranslan 
    inner join contrans on c45_seqtrans = c46_seqtrans
    inner join contranslr on c47_seqtranslan = c46_seqtranslan
    where (c45_coddoc = $coddoc_liq_capital or c45_coddoc = $coddoc_liq) and
    c47_debito=$codele  and 
    c45_anousu =".db_getsession("DB_anousu")."  and 
    c47_instit = ".db_getsession("DB_instit");
    $result = @ pg_query($sql);
    $numrows = @ pg_numrows($result);
    if ($numrows > 0) {
      db_fieldsmemory($result, 0);
      $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
      $this->arr_credito[$cont] = $reduzido;
      $this->arr_debito[$cont] = $c47_credito;
      $this->arr_histori[$cont] = $codhist;
      $cont ++;
    } else {
      //db_msgbox('erro no lan�amento... conta credito da   liquidacao naum encontrado..');
    }
    
    $this->sql = $this->sql_query_lr(null, "c47_seqtranslr,c47_credito,c47_debito", '', "c45_coddoc = ".$this->coddoc." and c45_anousu=".db_getsession("DB_anousu")."  and c47_anousu=$anousu");
    $this->result = $this->sql_record($this->sql);
    for ($i = 0; $i < $this->numrows; $i ++) {
      db_fieldsmemory($this->result, $i);
      if ($c47_credito != 0) {
        $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
        $this->arr_credito[$cont] = $c47_credito;
        $this->arr_debito[$cont] = $c47_debito;
        $this->arr_histori[$cont] = $codhist;
        $cont ++;
      }
    }
  }
  
  /* 
  *  Fun��o que retorna o os creditos e os debitos  do estorno de  pagamento de empenho
  *    Quando converter base de dados deve ser indicado o seqtranslan do lan�amento de liquida��o  que tem varios elementos... 
  *    normalmente � o primeiro lan�amento
  
  para pagamento � preciso indicar o primeiro lan�amento da liquidacao
  *   $seqtranslan = 15 na base dbseller 
  *   $seqtranslan = 3  na base guaiba_2112
  
  
  liquida��o capital
  *    $seqtranslan_liq_capital = 22 a base dbseller
  *    $seqtranslan_liq         = 4 a base guaiba_2112
  //tambem � preciso indicar o codigo do historico
  
  */
  function db_trans_estorna_pagamento($codele, $reduzido, $anousu) {
    global $c46_codhist, $c47_credito, $c47_debito, $c47_seqtranslr, $c61_reduz;
    $this->cl_zera_variaveis();
    $this->coddoc = 6;
    $coddoc_liq = 3;
    $coddoc_liq_capital = 23;
    
    $codhist = 9006;
    if ($codele != "") {
      $this->result = $this->sql_record("select c61_reduz from conplanoreduz where c61_codcon=$codele and c61_anousu=".db_getsession("DB_anousu")." and c61_instit=".db_getsession("DB_instit"));
      if ($this->numrows > 0) {
        db_fieldsmemory($this->result, 0);
        $codele = $c61_reduz;
      }
    }
    $cont = 0;
    
    //rotina que pega o o valor que foi creditado na liquidacao para colocar no debit  do pagamento...
    $sql = "select c47_credito,c47_seqtranslr,c46_codhist 
    from contranslan 
    inner join contrans on c45_seqtrans = c46_seqtrans
    inner join contranslr on c47_seqtranslan = c46_seqtranslan
    where (c45_coddoc = $coddoc_liq_capital or c45_coddoc = $coddoc_liq) 
    and c47_debito=$codele
    and c45_anousu = ".db_getsession("DB_anousu")."
    ";
    $result = @ pg_query($sql);
    $numrows = @ pg_numrows($result);
    
    if ($numrows > 0) {
      db_fieldsmemory($result, 0);
      $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
      /* carlos - alterado em guaiba */
      // $this->arr_credito[0]  = $reduzido;
      // $this->arr_debito[0]   = $c47_credito;
      $this->arr_credito[$cont] = $c47_credito;
      $this->arr_debito[$cont] = $reduzido;
      $this->arr_histori[$cont] = $codhist;
      $cont ++;
    } else {
      // die('erro no lan�amento... conta creditp da   liquidacao naum encontrado..');
    }
    
    $this->sql = $this->sql_query_lr(null, "c47_seqtranslr,c47_credito,c47_debito", '', "c45_coddoc = ".$this->coddoc." and c45_anousu=".db_getsession("DB_anousu")."  and c47_anousu=$anousu");
    $this->result = $this->sql_record($this->sql);
    for ($i = 0; $i < $this->numrows; $i ++) {
      db_fieldsmemory($this->result, $i);
      if ($c47_debito != 0) {
        $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
        $this->arr_credito[$cont] = $c47_credito;
        $this->arr_debito[$cont] = $c47_debito;
        $this->arr_histori[$cont] = $codhist;
        $cont ++;
      }
    }
  }
  
  /////////////////////////////////////////////////////////////////////////
  /*RESTOS � PAGAR
  /**/
  //revisar  0|0
  //          �  
  //os parametros codcom e codele n�o estam sendo utilizados ainda
  //porem s�o passados caso no futuro venham  ser utilizados
  function db_trans_liquida_resto($codcom, $codele, $anousu, $numemp) {
    global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_instit, $c47_seqtranslr, $c61_reduz, $c47_tiporesto;
    $this->cl_zera_variaveis();
    $this->coddoc = 33;
    if ($codele != "") {
      $this->result = $this->sql_record("select c61_reduz 
      from conplanoreduz 
      where c61_codcon=$codele and 
      c61_anousu=".db_getsession("DB_anousu")." and 
      c61_instit=".db_getsession("DB_instit"));
      if ($this->numrows > 0) {
        db_fieldsmemory($this->result, 0);
        $codele = $c61_reduz;
      }
    }
    
    //------------------------------------------------------------------------------------
    //rotina que pega o codigo do tiporesto
    $sql = "select e91_codtipo from empresto where e91_numemp=$numemp";
    $result = @ pg_query($sql);
    $numrows = @ pg_numrows($result);
    if ($numrows > 0) {
      $e91_codtipo = @ pg_result($result, 0, 0);
    } else {
      $this->sqlerro = true;
      $this->erro_msg = "Resto a pagar n�o encotrado na tabela empresto... Contate suporte.";
    }
    //------------------------------------------------------------------------------------
    
    $this->sql = $this->sql_query_lr(null, "c47_tiporesto,c47_seqtranslr,c47_ref,c46_seqtranslan,
    c46_codhist,c47_credito,c47_debito,c47_instit", '', "c45_coddoc = ".$this->coddoc." and
    c45_anousu=".db_getsession("DB_anousu")." and 
    c47_anousu=$anousu");
    $this->result = $this->sql_record($this->sql);
    $cont = 0;
    //declara array para verifica��o
    $arr_lans = array ();
    
    for ($i = 0; $i < $this->numrows; $i ++) {
      db_fieldsmemory($this->result, $i);
      
      if ($c47_tiporesto != '' && $c47_tiporesto != 0 && $e91_codtipo != $c47_tiporesto) {
        continue;
      }
      
      //deixa passar de a institui��o for zero  ou entao se ela vier preenchida deve ser iqual � do db_getsession('DB_instit');
      if (($c47_instit == 0 || $c47_instit == '') || (($c47_instit != '' && $c47_instit != 0) && $c47_instit == db_getsession('DB_instit'))) {
        
        //------------------------------------------------------------------------
        //verifica��o para naum incluir duas vezes o mesmo seqtranslan    
        if (array_key_exists($c46_seqtranslan, $arr_lans)) {
          continue;
        } else {
          $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
        }
        //------------------------------------------------------------------------
        
        $this->arr_credito[$cont] = $c47_credito;
        $this->arr_debito[$cont] = $c47_debito;
        $this->arr_histori[$cont] = $c46_codhist;
        $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
        $cont ++;
      }
    }
  }
  
  //os parametros codcom e codele n�o estam sendo utilizados ainda                                    
  function db_trans_estorna_liquida_resto($codcom, $codele, $anousu, $numemp) {
    global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c47_tiporesto, $c46_seqtranslan, $c47_instit, $c47_seqtranslr, $c61_reduz;
    $this->cl_zera_variaveis();
    $this->coddoc = 34;
    if ($codele != "") {
      $this->result = $this->sql_record("select c61_reduz 
      from conplanoreduz 
      where c61_codcon=$codele and 
      c61_anousu=".db_getsession("DB_anousu")." and 
      c61_instit=".db_getsession("DB_instit"));
      if ($this->numrows > 0) {
        db_fieldsmemory($this->result, 0);
        $codele = $c61_reduz;
      }
    }
    $this->sql = $this->sql_query_lr(null, "c47_tiporesto,c47_seqtranslr,c47_ref,
    c46_seqtranslan,c46_codhist,c47_credito,c47_debito", '', "c45_coddoc = ".$this->coddoc." and 
    c45_anousu=".db_getsession("DB_anousu")."  and 
    c47_anousu=$anousu");
    $this->result = $this->sql_record($this->sql);
    
    //----------------------------------------------------------------------
    //pega c�digo do tiporesto
    $sql = "select e91_codtipo from empresto where e91_numemp=$numemp";
    $result = @ pg_query($sql);
    $numrows = @ pg_numrows($result);
    if ($numrows > 0) {
      $e91_codtipo = @ pg_result($result, 0, 0);
    } else {
      $this->sqlerro = true;
      $this->erro_msg = "Resto a pagar n�o encotrado na tabela empresto... Contate suporte.";
    }
    //----------------------------------------------------------------------
    
    //declara array para verifica��o
    $arr_lans = array ();
    
    $cont = 0;
    for ($i = 0; $i < $this->numrows; $i ++) {
      db_fieldsmemory($this->result, $i);
      
      if ($c47_tiporesto != '' && $c47_tiporesto != 0 && $e91_codtipo != $c47_tiporesto) {
        continue;
      }
      
      //deixa passar de a institui��o for zero  ou entao se ela vier preenchida deve ser iqual � do db_getsession('DB_instit');
      if (($c47_instit == 0 || $c47_instit == '') || (($c47_instit != '' && $c47_instit != 0) && $c47_instit == db_getsession('DB_instit'))) {
        //------------------------------------------------------------------------
        //verifica��o para naum incluir duas vezes o mesmo seqtranslan    
        if (array_key_exists($c46_seqtranslan, $arr_lans)) {
          continue;
        } else {
          $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
        }
        //------------------------------------------------------------------------
        
        $this->arr_credito[$cont] = $c47_credito;
        $this->arr_debito[$cont] = $c47_debito;
        $this->arr_histori[$cont] = $c46_codhist;
        $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
        $cont ++;
      }
    }
  }
  
  //o parametro codele ainda naum esta sendo utilizado
  function db_trans_pagamento_resto($codele, $reduz, $anousu, $numemp, $iCodDoc = 35) {
    global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c61_reduz, $c47_tiporesto;
    $this->cl_zera_variaveis();
    $this->coddoc = $iCodDoc;
    if ($codele != "") {
      $this->result = $this->sql_record("select c61_reduz 
      from conplanoreduz 
      where c61_codcon=$codele and 
      c61_anousu = ".db_getsession("DB_anousu")." and 
      c61_instit=".db_getsession("DB_instit"));
      if ($this->numrows > 0) {
        db_fieldsmemory($this->result, 0);
        $codele = $c61_reduz;
      }
    }
    $this->sql = $this->sql_query_lr(null, "c47_tiporesto,c47_seqtranslr,c47_ref,c46_seqtranslan,c46_codhist,c47_credito,c47_debito", '', "c45_coddoc = ".$this->coddoc." and c45_anousu=".db_getsession("DB_anousu")." and c47_anousu=$anousu");
    $this->result = $this->sql_record($this->sql);
    
    //----------------------------------------------------------------------
    $sql = "select e91_codtipo from empresto where e91_numemp=$numemp";
    $result = @ pg_query($sql);
    $numrows = @ pg_numrows($result);
    if ($numrows > 0) {
      $e91_codtipo = @ pg_result($result, 0, 0);
    } else {
      $this->sqlerro = true;
      $this->erro_msg = "Resto a pagar n�o encontrado na tabela empresto... Contate suporte.";
    }
    //----------------------------------------------------------------------
    
    $cont = 0;
    
    //declara array para verifica��o
    $arr_lans = array ();
    
    for ($i = 0; $i < $this->numrows; $i ++) {
      db_fieldsmemory($this->result, $i);
      
      if ($c47_tiporesto != '' && $c47_tiporesto != 0 && $e91_codtipo != $c47_tiporesto) {
        continue;
      }
      
      //------------------------------------------------------------------------
      //verifica��o para naum incluir duas vezes o mesmo seqtranslan    
      if (array_key_exists($c46_seqtranslan, $arr_lans)) {
        continue;
      } else {
        $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
      }
      //------------------------------------------------------------------------
      
      if ($c47_credito == 0 || $c47_credito == '') {
        $c47_credito = $reduz;
        $this->conta_emp = $c47_debito;
      }
      $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
      $this->arr_credito[$cont] = $c47_credito;
      $this->arr_debito[$cont] = $c47_debito;
      $this->arr_histori[$cont] = $c46_codhist;
      $cont ++;
    }
  }
  
  function db_trans_estorna_pagamento_resto($codele, $reduz, $anousu, $numemp,$iCodDoc = 36) {
    global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c61_reduz, $c47_tiporesto;
    $this->cl_zera_variaveis();
    $this->coddoc = $iCodDoc;
    if ($codele != "") {
      $this->result = $this->sql_record("select c61_reduz from conplanoreduz where c61_codcon=$codele and c61_anousu=".db_getsession("DB_anousu")." and c61_instit=".db_getsession("DB_instit"));
      if ($this->numrows > 0) {
        db_fieldsmemory($this->result, 0);
        $codele = $c61_reduz;
      }
    }
    $this->sql = $this->sql_query_lr(null, "c47_tiporesto,c47_seqtranslr,c47_ref,c46_seqtranslan,c46_codhist,c47_credito,c47_debito", '', "c45_coddoc = ".$this->coddoc." and c45_anousu=".db_getsession("DB_anousu")." and c47_anousu=$anousu");
    $this->result = $this->sql_record($this->sql);
    
    $sql = "select e91_codtipo from empresto where e91_numemp=$numemp";
    $result = @ pg_query($sql);
    $numrows = @ pg_numrows($result);
    if ($numrows > 0) {
      $e91_codtipo = @ pg_result($result, 0, 0);
    } else {
      $this->sqlerro = true;
      $this->erro_msg = "Resto a pagar n�o encotrado na tabela empresto... Contate suporte.";
    }
    
    $cont = 0;
    
    //declara array para verifica��o
    $arr_lans = array ();
    
    for ($i = 0; $i < $this->numrows; $i ++) {
      db_fieldsmemory($this->result, $i);
      
      if ($c47_tiporesto != '' && $c47_tiporesto != 0 && $e91_codtipo != $c47_tiporesto) {
        continue;
      }
      
      //------------------------------------------------------------------------
      //verifica��o para naum incluir duas vezes o mesmo seqtranslan    
      if (array_key_exists($c46_seqtranslan, $arr_lans)) {
        continue;
      } else {
        $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
      }
      //------------------------------------------------------------------------
      
      //echo $c47_debito."==".$c47_credito."<br><br>";
      if ($c47_debito == 0 || $c47_debito == '') {
        $c47_debito = $reduz;
        $this->conta_emp = $c47_credito;
      }
      $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
      $this->arr_credito[$cont] = $c47_credito;
      $this->arr_debito[$cont] = $c47_debito;
      $this->arr_histori[$cont] = $c46_codhist;
      $cont ++;
    }
  }
  
  function db_trans_estorna_empenho_resto($codcom = null, $anousu, $numemp) {
    global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c47_tiporesto;
    $this->cl_zera_variaveis();
    $this->coddoc = 32;
    $this->sql = $this->sql_query_lr(null, "c47_tiporesto,c47_seqtranslr,c47_ref,c46_seqtranslan,c46_codhist,c47_credito,c47_debito", '', "c45_coddoc = ".$this->coddoc." and c45_anousu=".db_getsession("DB_anousu")." and c47_anousu=$anousu");
    $this->result = $this->sql_record($this->sql);
    
    $sql = "select e91_codtipo from empresto where e91_numemp=$numemp";
    $result = @ pg_query($sql);
    $numrows = @ pg_numrows($result);
    if ($numrows > 0) {
      $e91_codtipo = @ pg_result($result, 0, 0);
    } else {
      $this->sqlerro = true;
      $this->erro_msg = "Resto a pagar n�o encotrado na tabela empresto... Contate suporte.";
    }
    
    $cont = 0;
    $arr_lans = array ();
    // echo $this->sql;
    for ($i = 0; $i < $this->numrows; $i ++) {
      db_fieldsmemory($this->result, $i);
      
      if ($c47_tiporesto != '' && $c47_tiporesto != 0 && $e91_codtipo != $c47_tiporesto) {
        continue;
      }
      
      //------------------------------------------------------------------------
      //verifica��o para naum incluir duas vezes o mesmo seqtranslan    
      if (array_key_exists($c46_seqtranslan, $arr_lans)) {
        continue;
      } else {
        $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
      }
      //------------------------------------------------------------------------
      
      $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
      $this->arr_credito[$cont] = $c47_credito;
      $this->arr_debito[$cont] = $c47_debito;
      $this->arr_histori[$cont] = $c46_codhist;
      $cont ++;
    }
  }
  
  function db_trans_estorna_empenho_resto_processado($codcom = null, $anousu, $numemp) {
    global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c47_tiporesto;
    $this->cl_zera_variaveis();
    $this->coddoc = 31;
    $this->sql = $this->sql_query_lr(null, "c47_tiporesto,c47_seqtranslr,c47_ref,c46_seqtranslan,c46_codhist,c47_credito,c47_debito", '', "c45_coddoc = ".$this->coddoc." and c45_anousu=".db_getsession("DB_anousu")." and c47_anousu=$anousu");
    $this->result = $this->sql_record($this->sql);
    
    $sql = "select e91_codtipo from empresto where e91_numemp=$numemp";
    $result = @ pg_query($sql);
    $numrows = @ pg_numrows($result);
    if ($numrows > 0) {
      $e91_codtipo = @ pg_result($result, 0, 0);
    } else {
      $this->sqlerro = true;
      $this->erro_msg = "Resto a pagar n�o encotrado na tabela empresto... Contate suporte.";
    }
    
    $cont = 0;
    $arr_lans = array ();
    // echo $this->sql;
    for ($i = 0; $i < $this->numrows; $i ++) {
      db_fieldsmemory($this->result, $i);
      
      if ($c47_tiporesto != '' && $c47_tiporesto != 0 && $e91_codtipo != $c47_tiporesto) {
        continue;
      }
      
      //------------------------------------------------------------------------
      //verifica��o para naum incluir duas vezes o mesmo seqtranslan    
      if (array_key_exists($c46_seqtranslan, $arr_lans)) {
        continue;
      } else {
        $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
      }
      //------------------------------------------------------------------------
      
      $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
      $this->arr_credito[$cont] = $c47_credito;
      $this->arr_debito[$cont] = $c47_debito;
      $this->arr_histori[$cont] = $c46_codhist;
      $cont ++;
    }
  }
  
  // adicionada este metodo 03jul2006
  // fun��o unica para retornar lan�amentos de RP
  function db_trans_rp($documento, $numemp) {
    
    global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c47_tiporesto, $c46_obrigatorio;
    $this->cl_zera_variaveis();
    
    $this->coddoc = $documento;
    
    $arr_obrigatorio = array (); // quarda os lan�amentos que s�o obrigatorios
    
    $sql = "select e60_anousu from empempenho where e60_numemp=$numemp";
    $result = @ pg_query($sql);
    $numrows = @ pg_numrows($result);
    if ($numrows > 0) {
      $anousu = @ pg_result($result, 0, 0);
    } else {
      $this->sqlerro = true;
      $this->erro_msg = "Falha ao pesquisar na tabela de empenhos... Contate suporte ! ";
    }
    
    $sql = "select e91_codtipo from empresto where e91_numemp=$numemp and e91_anousu=".db_getsession("DB_anousu");
    $result = @ pg_query($sql);
    $numrows = @ pg_numrows($result);
    if ($numrows > 0) {
      $e91_codtipo = @ pg_result($result, 0, 0);
    } else {
      $this->sqlerro = true;
      $this->erro_msg = "Resto a pagar n�o encotrado na tabela empresto... Contate suporte !";
    }
    
    // seleciona os lan�amentos
    $sql = " select c46_seqtranslan,c46_codhist,c46_obrigatorio
    from contrans
    inner join contranslan on c46_seqtrans = contrans.c45_seqtrans
    where c45_coddoc = ".$this->coddoc." and 
    c45_anousu = ".db_getsession("DB_anousu")."  and
    c45_instit = ".db_getsession("DB_instit")."		
    ";
    $result = @ pg_query($sql);
    
    // db_criatabela($result);
    
    $numrows = @ pg_numrows($result);
    if ($numrows > 0) {
      
      $cont = 0;
      for ($i = 0; $i < $numrows; $i ++) {
        db_fieldsmemory($result, $i);
        
        $res_lancam = "select c47_seqtranslr,c47_debito,c47_credito,c47_tiporesto
        from contranslr
        where c47_seqtranslan = $c46_seqtranslan and
        c47_anousu = $anousu   /* anousu = empresto.anousu  */
        ";
        
        $this->result = $this->sql_record($res_lancam);
        // db_criatabela($this->result);
        
        if ($this->numrows > 0) {
          for ($x = 0; $x < $this->numrows; $x ++) {
            db_fieldsmemory($this->result, $x);
            
            // se tiver tiporesto configurado ent�o ele deve ser igual ao Tipo Resto do RP
            if ($c47_tiporesto != '' && $c47_tiporesto != 0 && $e91_codtipo != $c47_tiporesto) {
              continue;
            }
            
            if ($c46_obrigatorio == 't' || $c46_obrigatorio == 'true') {
              $arr_obrigatorio[] = $c47_seqtranslr;
            }
            $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
            $this->arr_credito[$cont] = $c47_credito;
            $this->arr_debito[$cont] = $c47_debito;
            $this->arr_histori[$cont] = $c46_codhist;
            $cont ++;
            
          } //end loop
          
        } else {
          if ($c46_obrigatorio == 't' || $c46_obrigatorio == 'true') {
            
            $this->sqlerro = true;
            $this->erro_msg = "Lan�amento obrigat�rio, por�m n�o retornou registros ! ";
            break;
          }
          
        } // end if 	
        
      } // end loop
      
      // seleciona quantos lan�amentos s�o obrigatorios
      $sql = " select count(c46_seqtranslan) as quantidade_obrigatoria
      from contrans
      inner join contranslan on c46_seqtrans = contrans.c45_seqtrans
      where c45_coddoc = ".$this->coddoc." and 
      c45_anousu = ".db_getsession("DB_anousu")."  and
      c45_instit = ".db_getsession("DB_instit")."	 and
      c46_obrigatorio ='t'
      ";
      $result = @ pg_query($sql);
      $numrows = pg_numrows($result);
      if ($numrows > 0) {
        $qtd_obrigatoria = pg_result($result, 0, 0);
        if ($qtd_obrigatoria != sizeof($arr_obrigatorio)) {
          
          $this->sqlerro = true;
          $this->erro_msg = "Lan�amento obrigatorio sem registro. Verifique (Contabilidade,documentos,transa��es) ! ";
          
        }
        
      }
      
    } else {
      $this->sqlerro = true;
      $this->erro_msg = "Bloco de lan�amentos n�o localizados ( documento $documento, Empenho $numemp )";
    }
    /*
    echo "<br><br><br><br><br>";
    print_r($this->arr_credito);
    print_r($this->arr_debito);
    
    echo $this->erro_msg;
    echo "<br> lanc obrigatorio ".sizeof($arr_obrigatorio);
    print_r($arr_obrigatorio);
    */
    
  } // end method    
  
  function db_trans_arrecada_receita($conta, $codcon, $anousu) {
    global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c47_compara;
    $this->cl_zera_variaveis();
    $this->coddoc = 100;
    
    $this->sql = $this->sql_query_receita(null, "c47_seqtranslr,c47_ref,c46_seqtranslan,c46_codhist,c47_credito,c47_debito,c47_compara", 'c46_seqtranslan', "c45_coddoc = ".$this->coddoc." and c45_anousu=".db_getsession("DB_anousu")." and c47_anousu=".db_getsession("DB_anousu"));
    $this->result = $this->sql_record($this->sql);
    
    // codcon � o reduzido da receita no conplanoreduz ( c61_reduz )
    
    $cont = 0;
    for ($i = 0; $i < $this->numrows; $i ++) {
      db_fieldsmemory($this->result, $i);
      
      if ($c47_compara == 0) {
        // sem comparador ( debito , credito ) retorna lan�amentos encontrados
        $this->arr_credito[$cont] = $c47_credito;
        $this->arr_debito[$cont] = $c47_debito;
        $this->arr_histori[$cont] = $c46_codhist;
        $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
        
      }
      elseif ($c47_compara == 1) {
        // comparador a debito
        // arrecada��o � sempre a credito, estorno � a debito
        
      }
      elseif ($c47_compara == 2) {
        // comparador = credito
        $this->arr_credito[$cont] = $codcon; // reduzido da receita
        $this->arr_debito[$cont] = $conta; // reduzido caixa ou banco
        $this->arr_histori[$cont] = $c46_codhist;
        $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
      }
      $cont ++;
      
    }
    
  }
  
  function db_trans_estorno_receita($conta, $codcon, $anousu) {
    
    global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c47_compara;
    $this->cl_zera_variaveis();
    $this->coddoc = 101;
    
    $this->sql = $this->sql_query_lr(null, "c47_seqtranslr,c47_ref,c46_seqtranslan,c46_codhist,c47_credito,c47_debito,c47_compara", 'c46_seqtranslan', "c45_coddoc = ".$this->coddoc." and c45_anousu=".db_getsession("DB_anousu")." and c47_anousu=".db_getsession("DB_anousu"));
    $this->result = $this->sql_record($this->sql);
    
    //db_criatabela($this->result);
    //exit;
    
    $cont = 0;
    for ($i = 0; $i < $this->numrows; $i ++) {
      db_fieldsmemory($this->result, $i);
      
      if ($c47_compara == 0) {
        // sem comparador ( debito , credito ) retorna lan�amentos encontrados
        $this->arr_credito[$cont] = $c47_credito;
        $this->arr_debito[$cont] = $c47_debito;
        $this->arr_histori[$cont] = $c46_codhist;
        $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
        
      }
      elseif ($c47_compara == 1) {
        // comparador a debito
        // arrecada��o � sempre a credito, estorno � a debito
        $this->arr_credito[$cont] = $conta; // reduzido caixa ou banco 
        $this->arr_debito[$cont] = $codcon; // reduzido da receita
        $this->arr_histori[$cont] = $c46_codhist;
        $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
        
      }
      elseif ($c47_compara == 2) {
        // comparador = credito
        // estorno � sempre a debito
      }
      $cont ++;
    }
    
  }
  /*  suplemeta��es  
  $anousu - normal
  $tipo - tipo de suplementa��(1001,1002,..etc)
  $red  - se � redu��o(true), valor negativo
  */
  function db_trans_suplem($anousu, $tipo, $red = false) {
    global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c47_seqtranslr, $doc_suplementacao, $doc_reducao;
    $this->cl_zera_variaveis();
    /**
    * alterar, para pegar os docs da tabela orcsuplemtipo
    */
    
    $this->arr_credito = array ();
    $this->arr_debito = array ();
    $this->arr_histori = array ();
    $this->arr_seqtranslr = array ();
    //------ seleciona documentos -------------
    $sql = "select o48_coddocsup as doc_suplementacao,
    case when o48_coddocred > 0 then
    o48_coddocred
    else o48_arrecadmaior end as doc_reducao
    from orcsuplemtipo
    where o48_tiposup = $tipo
    ";
    $this->result = $this->sql_record($sql);
    db_fieldsmemory($this->result, 0);
    if ($red == false)
    $this->coddoc = $doc_suplementacao;
    else
    $this->coddoc = $doc_reducao; // reduzido ou arrecada��o a maior ( parte que informa receita )
    // ------------ ------------ --------------
    $this->sql = $this->sql_query_lr(null, "c47_seqtranslr,c46_codhist,c47_credito,
    c47_debito,c47_ref", '', "c45_coddoc = ".$this->coddoc." and
    c45_anousu=".db_getsession("DB_anousu")." and 
    c45_instit =".db_getsession("DB_instit")." and
    c47_anousu=".db_getsession("DB_anousu"));
    $this->result = $this->sql_record($this->sql);
    $cont = 0;
    for ($i = 0; $i < $this->numrows; $i ++) {
      db_fieldsmemory($this->result, $i);
      if ($c47_ref == 0 || ($c47_ref != 0 && $c47_ref == $codcom)) {
        $this->arr_credito[$cont] = $c47_credito;
        $this->arr_debito[$cont] = $c47_debito;
        $this->arr_histori[$cont] = $c46_codhist;
        $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
        $cont ++;
      }
    }
  }
  /////////////////
  /** 
  * recebe o numero do documento e retorna os lan�amentos 
  */
  function db_trans_documento($documento, $conta) {
    global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c47_compara;
    $this->cl_zera_variaveis();
    $this->coddoc = $documento;
    
    $this->sql = $this->sql_query_lr(null, "c47_seqtranslr,c47_ref,c46_seqtranslan,c46_codhist,c47_credito,c47_debito,c47_compara", '', "c45_coddoc = ".$this->coddoc." and c45_anousu=".db_getsession("DB_anousu"));
    $this->result = $this->sql_record($this->sql);
    
    $cont = 0;
    for ($i = 0; $i < $this->numrows; $i ++) {
      db_fieldsmemory($this->result, $i);
      
      if ($c47_credito == 0) {
        $this->arr_credito[$cont] = $conta;
      } else {
        $this->arr_credito[$cont] = $c47_credito;
      }
      if ($c47_debito == 0) {
        $this->arr_debito[$cont] = $conta;
      } else {
        $this->arr_debito[$cont] = $c47_debito;
      }
      $this->arr_histori[$cont] = $c46_codhist;
      $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
      $cont ++;
    }
    
  }
  function db_trans_inscricao_rp($codcom, $codele, $anousu) {
    global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c61_reduz, $c47_compara;
    $this->cl_zera_variaveis();
    $this->coddoc = 1007;
    
    if ($codele != "") {
      $this->result = $this->sql_record("select c61_reduz from conplanoreduz where c61_codcon=$codele and c61_anousu=".db_getsession("DB_anousu")."  and c61_instit=".db_getsession("DB_instit"));
      if ($this->numrows > 0) {
        db_fieldsmemory($this->result, 0);
        $codele = $c61_reduz;
      }
    }
    $this->sql = $this->sql_query_lr(null, "c47_compara,c47_seqtranslr,c47_ref,c46_seqtranslan,c46_codhist,c47_credito,c47_debito", '', "c45_coddoc = ".$this->coddoc." and c45_anousu=".db_getsession("DB_anousu")." and c47_anousu=$anousu");
   # echo $this->sql;
    $this->result = $this->sql_record($this->sql);
    $cont = 0;
    $arr_lans = array ();
    
    for ($i = 0; $i < $this->numrows; $i ++) {
      db_fieldsmemory($this->result, $i);
      if ($c47_compara == 1) {
        $comparador = $c47_debito;
      } else
      if ($c47_compara == 2) {
        $comparador = $c47_credito;
      } else
      if ($c47_compara == 3) {
        $comparador = $c47_ref;
      } else {
        $comparador = 0;
      }
      if (($c47_ref == '' || $c47_ref == 0 || ($c47_ref != 0 && ($c47_ref == $codcom || $c47_compara == 3))) && ($c47_compara == 0 || $comparador == $codele)) {
        
        //------------------------------------------------------------------------
        //verifica��o para naum incluir duas vezes o mesmo seqtranslan    
        if (array_key_exists($c46_seqtranslan, $arr_lans)) {
          continue;
        } else {
          $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
        }
        //------------------------------------------------------------------------
        
        $this->arr_credito[$cont] = $c47_credito;
        $this->arr_debito[$cont] = $c47_debito;
        $this->arr_histori[$cont] = $c46_codhist;
        $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
        $cont ++;
      }
    }
  }
  
}
//|00|//cl_estrutura
//|10|//pega a picture de um determinado campo do orcparametro e gera um input text com a formatacao da mesma
//|15|//[variavel] = new cl_estrutura;
class cl_estrutura_sistema {
  // cria variaveis de erro 
  var $nomeform = "form1";
  var $reload = false;
  var $size = '50';
  var $mascara = true;
  var $input = false;
  var $db_opcao = 1;
  var $funcao_onchange = null;
  var $autocompletar = false;
  var $botao = false;
  function estrutura_sistema($picture = null) {
    $rotuloc = new rotulocampo;
    $clconparametro = new cl_conparametro;
    $rotuloc->label($picture);
    $title = "T".$picture;
    $label = "L".$picture;
    
    global $$label, $$title, $$picture, $mascara;
    if (!class_exists('cl_conparametro')) {
      db_msgbox('Classe conparametro n�o incluida!');
      exit;
    }
    $result = $clconparametro->sql_record($clconparametro->sql_query_file("", "$picture as mascara"));
    if ($clconparametro->numrows > 0) {
      db_fieldsmemory($result, 0);
      $tamanho = strlen($mascara);
    } else {
      db_msgbox('Configura��o de Parametros n�o encontrada ! Contate o suporte !');
      exit;
    }
    
    if ($this->funcao_onchange != null) {
      if ($this->autocompletar == false && $this->reload == false) {
        $funcao = $this->funcao_onchange;
      } else {
        $funcao = "onChange='js_mascara02_$picture(this.value);".$this->funcao_onchange.";'";
      }
    } else {
      $funcao = "onChange=\"js_mascara02_$picture(this.value);\"";
    }
    if ($this->mascara == true && $this->input == false) {
      ?>    
      <tr>
      <td nowrap title="M�scara do campo <?=@$picture?>">
      <b>M�scara:</b>
      </td>
      <td> 
      
      <input name="mascara_<?=$picture?>"  readonly disabled size='<?=$this->size?>' type="text"  value="<?=$mascara?>"    >
      
      </td>
      </tr>
      <?
      
      
      
    }
    if ($this->input == false) {
      ?>
      <tr>
      <td nowrap title="<?=@$$title?>">
      <?=@$$label?>
      </td>
      <td> 
      <?
      
      
      
    }
    ?>
    <input title="<?=@$$title?>" name="<?=$picture?>" maxlength='<?=$tamanho?>' size='<?=$this->size?>' type="text"  value="<?=@$$picture?>" onKeyPress="return js_mascara01_<?=$picture?>(event,this.value);"  <?=$funcao?> <?=($this->db_opcao==22||$this->db_opcao==33||$this->db_opcao==3?"readonly style=\"background-color:#DEB887\" ":"")?> >
    <?
    
    
    
    if ($this->botao == true) {
      ?>       
      <input name='verifica' type="button" value='Verificar' onclick="js_mascara02_<?=$picture?>(document.<?=$this->nomeform?>.<?=$picture?>.value);" <?=($this->db_opcao==22||$this->db_opcao==33||$this->db_opcao==3?"disabled ":"")?>  >
      <?
      
      
      
    }
    ?>  
    <? 
    
    
    if ($this->input == false) {
      ?>     
      </td>  
      </tr>  
      <?
      
      
      
    }
    ?>   
    <script>    
    function js_mascara01_<?=$picture?>(evt,obj){
      var evt = (evt) ? evt : (window.event) ? window.event : "";
      if(evt.charCode >47 && evt.charCode <58 ){//8:backspace|46:delete|190:. 
      var  str='<?=$mascara?>';
      var  tam=obj.length;
      var  dig=str.substr(tam,1); 
        if(dig=="."){
          document.<?=$this->nomeform?>.<?=$picture?>.value=obj+".";
        }
        return true;
      }else if(evt.charCode=='0'){
        return true;
      }else{
        return false;
      }  
    }
    function js_mascara02_<?=$picture?>(obj){
    
      var str='<?=$mascara?>';
      var obj=document.<?=$this->nomeform?>.<?=$picture?>.value;
      while(obj.search(/\./)!='-1'){
        obj=obj.replace(/\./,''); 
      }
      <?
      
      
      
      if ($this->autocompletar == true) {
        ?>
        if(obj!=''){
        var  tam=<?=strlen(str_replace(".","",$mascara))?>;
          for(var i=obj.length; i<tam; i++){
            obj=obj+"0";
          }
        }  
        <?
        
        
        
      }
      ?>
      //analise da estrutura passada
     var nada='';
     var matriz=str.split(nada);
     var tam=matriz.length;
     var arr=new Array();
     var cont=0;
      for(i=0; i<tam; i++){
        if(matriz[i]=='.'){
          arr[cont]=i;
          cont++;
        }  
      }
      //fim
      for(var i=0; i<arr.length; i++){
        var pos=arr[i]; 
        var strpos=obj.substr(pos,1); 
        if(strpos!='' && strpos!='.'){
          ini=obj.slice(0,pos);
          fim=obj.slice(pos);
          obj=ini+"."+fim;
        }
      } 
      document.<?=$this->nomeform?>.<?=$picture?>.value=obj;
      <?
      
      
      
      if ($this->reload == true) {
        ?>      
        obj=document.createElement('input');
        obj.setAttribute('name','atualizar');
        obj.setAttribute('type','hidden');
        obj.setAttribute('value',"atualizar");
        document.<?=$this->nomeform?>.appendChild(obj);
        document.<?=$this->nomeform?>.submit();
        <?
        
        
        
      }
      ?>      
    }
    function js_mascara03_<?=$picture?>(obj){
      obj=document.<?=$this->nomeform?>.<?=$picture?>.value;
      while(obj.search(/\./)!='-1'){
        obj=obj.replace(/\./,''); 
      }
      <?
      
      
      
      if ($this->autocompletar == true) {
        ?>
        tam=<?=strlen(str_replace(".","",$picture))?>;
        for(i=obj.length; i<tam; i++){
          obj=obj+"0";
        }
        <?
        
        
        
      }
      ?>
      //analise da estrutura passada
      str='<?=$mascara?>';
      nada='';
      matriz=str.split(nada);
      tam=matriz.length;
      arr=new Array();
      cont=0;
      for(i=0; i<tam; i++){
        if(matriz[i]=='.'){
          arr[cont]=i;
          cont++;
        }  
      }
      //fim
      for(i=0; i<arr.length; i++){
        pos=arr[i]; 
        strpos=obj.substr(pos,1); 
        if(strpos!='' && strpos!='.'){
          ini=obj.slice(0,pos);
          fim=obj.slice(pos);
          obj=ini+"."+fim;
        }
      } 
      document.<?=$this->nomeform?>.<?=$picture?>.value=obj;
    }
    </script>    
    <?
    
    
    
    $this->nomeform = "form1";
    $this->reload = false;
    $this->size = '50';
    $this->mascara = true;
    $this->input = false;
    $this->db_opcao = 1;
    $this->funcao_onchange = null;
    $this->autocompletar = false;
    $this->botao = false;
  }
}

function db_le_mae_sistema($codigo, $nivel = false) {
  $retorno = "";
  if (substr($codigo, 11, 2) != '00') {
    if ($nivel == true) {
      $retorno = 9;
    } else {
      $retorno = substr($codigo, 0, 11).'00';
    }
  }
  if ($retorno == "" && substr($codigo, 9, 4) != '0000') {
    if ($nivel == true) {
      $retorno = 8;
    } else {
      $retorno = substr($codigo, 0, 9).'0000';
    }
  }
  if ($retorno == "" && substr($codigo, 7, 6) != '000000') {
    if ($nivel == true) {
      $retorno = 7;
    } else {
      $retorno = substr($codigo, 0, 7).'000000';
    }
  }
  if ($retorno == "" && substr($codigo, 5, 8) != '00000000') {
    if ($nivel == true) {
      $retorno = 6;
    } else {
      $retorno = substr($codigo, 0, 5).'00000000';
    }
  }
  if ($retorno == "" && substr($codigo, 4, 9) != '000000000') {
    if ($nivel == true) {
      $retorno = 5;
    } else {
      $retorno = substr($codigo, 0, 4).'000000000';
    }
  }
  if ($retorno == "" && substr($codigo, 3, 10) != '0000000000') {
    if ($nivel == true) {
      $retorno = 4;
    } else {
      $retorno = substr($codigo, 0, 3).'0000000000';
    }
  }
  if ($retorno == "" && substr($codigo, 2, 11) != '00000000000') {
    if ($nivel == true) {
      $retorno = 3;
    } else {
      $retorno = substr($codigo, 0, 2).'00000000000';
    }
  }
  if ($retorno == "" && substr($codigo, 1, 12) != '000000000000') {
    if ($nivel == true) {
      $retorno = 2;
    } else {
      $retorno = substr($codigo, 0, 1).'000000000000';
    }
  }
  if ($retorno == "") {
    if ($nivel == true) {
      $retorno = 1;
    } else {
      $retorno = $codigo;
    }
  }
  return $retorno;
}
//codigo seria o estrutural fornecido
//$nivel seria qual o nivel do estrutural que � para retornar...
//$full= true se desejar que seja retornado o nivel desejado e o resto com zero.. false ele retornara s� ate o nivel desejado... 
function db_le_corta_conplano($codigo, $nivel, $full = false) {
  $retorno = "";
  if ($nivel == 9) {
    if ($full == true) {
      $retorno = substr($codigo, 0, 13).'00';
    } else {
      $retorno = substr($codigo, 0, 13);
    }
  }
  if ($nivel == 8) {
    if ($full == 8) {
      $retorno = substr($codigo, 0, 11).'0000';
    } else {
      $retorno = substr($codigo, 0, 11);
    }
  }
  if ($nivel == 7) {
    if ($full == true) {
      $retorno = substr($codigo, 0, 9).'000000';
    } else {
      $retorno = substr($codigo, 0, 9);
    }
  }
  if ($nivel == 6) {
    if ($full == true) {
      $retorno = substr($codigo, 0, 7).'00000000';
    } else {
      $retorno = substr($codigo, 0, 7);
    }
  }
  if ($nivel == 5) {
    if ($full == true) {
      $retorno = substr($codigo, 0, 5).'0000000000';
    } else {
      $retorno = substr($codigo, 0, 5);
    }
  }
  if ($nivel == 4) {
    if ($full == true) {
      $retorno = substr($codigo, 0, 4).'00000000000';
    } else {
      $retorno = substr($codigo, 0, 4);
    }
  }
  if ($nivel == 3) {
    if ($full == true) {
      $retorno = substr($codigo, 0, 3).'000000000000';
    } else {
      $retorno = substr($codigo, 0, 3);
    }
  }
  if ($nivel == 2) {
    if ($full == true) {
      $retorno = substr($codigo, 0, 2).'0000000000000';
    } else {
      $retorno = substr($codigo, 0, 2);
    }
  }
  if ($nivel == "1") {
    if ($full == true) {
      $retorno = substr($codigo, 0, 1).'0000000000000';
    } else {
      $retorno = substr($codigo, 0, 1);
    }
  }
  return $retorno;
}
function db_le_mae_conplano($codigo, $nivel = false) {
  $retorno = "";

  if ($retorno == "" && substr($codigo, 13, 2) != '00') {
    if ($nivel == true) {
      $retorno = 10;
    } else {
      $retorno = substr($codigo, 0, 13).'00';
    }
  }
  if ($retorno == "" && substr($codigo, 11, 2) != '00') {
    if ($nivel == true) {
      $retorno = 9;
    } else {
      $retorno = substr($codigo, 0, 11).'0000';
    }
  }
  if ($retorno == "" && substr($codigo, 9, 6) != '000000') {
    if ($nivel == true) {
      $retorno = 8;
    } else {
      $retorno = substr($codigo, 0, 9).'000000';
    }
  }
  if ($retorno == "" && substr($codigo, 7, 8) != '00000000') {
    if ($nivel == true) {
      $retorno = 7;
    } else {
      $retorno = substr($codigo, 0, 7).'00000000';
    }
  }
  if ($retorno == "" && substr($codigo, 5, 10) != '0000000000') {
    if ($nivel == true) {
      $retorno = 6;
    } else {
      $retorno = substr($codigo, 0, 5).'0000000000';
    }
  }
  if ($retorno == "" && substr($codigo, 4, 11) != '00000000000') {
    if ($nivel == true) {
      $retorno = 5;
    } else {
      $retorno = substr($codigo, 0, 4).'00000000000';
    }
  }
  if ($retorno == "" && substr($codigo, 3, 12) != '000000000000') {
    if ($nivel == true) {
      $retorno = 4;
    } else {
      $retorno = substr($codigo, 0, 3).'000000000000';
    }
  }
  if ($retorno == "" && substr($codigo, 2, 13) != '0000000000000') {
    if ($nivel == true) {
      $retorno = 3;
    } else {
      $retorno = substr($codigo, 0, 2).'0000000000000';
    }
  }
  if ($retorno == "" && substr($codigo, 1, 14) != '00000000000000') {
    if ($nivel == true) {
      $retorno = 2;
    } else {
      $retorno = substr($codigo, 0, 1).'00000000000000';
    }
  }
  if ($retorno == "") {
    if ($nivel == true) {
      $retorno = 1;
    } else {
      $retorno = $codigo;
    }
  }
  return $retorno;
}

function db_planosissaldo($anousu, $dataini, $datafim, $retsql = false, $where = '') {
  
  if ($anousu == null)
  $anousu = db_getsession("DB_anousu");
  
  if ($dataini == null)
  $dataini = date('Y-m-d', db_getsession('DB_datausu'));
  
  if ($datafim == null)
  $datafim = date('Y-m-d', db_getsession('DB_datausu'));
  
  if ($where != '') {
    $condicao = " and ".$where;
  } else {
    $condicao = "";
  }
  
  $sql = "select   estrut_mae, 
  estrut, 
  c61_reduz,
  c60_descr,
  c60_finali,
  substr(fc_planosaldo,3,13)::float8 as saldo_anterior,
  substr(fc_planosaldo,16,13)::float8 as saldo_anterior_debito,
  substr(fc_planosaldo,29,13)::float8 as saldo_anterior_credito,
  substr(fc_planosaldo,42,13)::float8 as saldo_final,
  substr(fc_planosaldo,55,1)::varchar(1) as sinal_anterior,
  substr(fc_planosaldo,56,1)::varchar(1) as sinal_final
  from 
  (select c.c64_estrut as estrut_mae,
  p.c60_estrut as estrut,
  c61_reduz,
  p.c60_descr,
  p.c60_finali,
  fc_planosaldo(".db_getsession('DB_anousu').",c61_reduz,'$dataini','$datafim')
  from conplanoexe e
  inner join conplanoreduz r on r.c61_reduz = c62_reduz and r.c61_anousu=e.c62_anousu
  inner join conplanoref f on f.c65_codcon = c61_codcon  
  inner join conplano p on r.c61_codcon = p.c60_codcon and p.c60_anousu=r.c61_anousu
  inner join conplanosis c on c.c64_codpla = f.c65_codpla
  where c62_anousu = $anousu $condicao) as x
  ";
  
  //db_criatabela(pg_query($sql));exit;
  
  pg_exec("create temporary table work_planosis(estrut_mae varchar(15), 
  estrut varchar(15),
  c61_reduz integer,
  c60_descr varchar(60),
  c60_finali varchar(60),
  saldo_anterior float8,
  saldo_anterior_debito float8,
  saldo_anterior_credito float8,
  saldo_final float8,
  sinal_anterior varchar(1),
  sinal_final varchar(1))");
  
  pg_exec("create index work_planosis_estrut on work_planosis(estrut)");
  
  $result = pg_query($sql);
  
  //  db_criatabela($result);exit;
  $tot_anterior = 0;
  $tot_anterior_debito = 0;
  $tot_anterior_credito = 0;
  $tot_saldo_final = 0;
  GLOBAL $estrut_mae, $estrut, $c61_reduz, $c60_descr, $c60_finali, $saldo_anterior, $saldo_anterior_debito, $saldo_anterior_credito, $saldo_final, $result_estrut, $sinal_anterior, $sinal_final, $c64_descr;
  $nivel = 0;
  for ($i = 0; $i < pg_numrows($result); $i ++) {
    //  for($i = 0;$i < 4;$i++){
      db_fieldsmemory($result, $i);
      if ($sinal_anterior == "C")
      $saldo_anterior *= -1;
      if ($sinal_final == "C")
      $saldo_final *= -1;
      $tot_anterior = $saldo_anterior;
      $tot_anterior_debito = $saldo_anterior_debito;
      $tot_anterior_credito = $saldo_anterior_credito;
      $tot_saldo_final = $saldo_final;
      pg_query("insert into work_planosis values('$estrut_mae',
      '$estrut',
      $c61_reduz,
      '$c60_descr',
      '$c60_finali',
      $saldo_anterior,
      $saldo_anterior_debito,
      $saldo_anterior_credito,
      $saldo_final,
      '$sinal_anterior',
      '$sinal_final')");
      $estrutural = $estrut_mae;
      $nivel = 10;
      for ($ii = 1; $ii < 11; $ii ++) {
        if ($ii > 1) {
          $estrutural = db_le_mae_sistema($estrutural);
          $nivel = db_le_mae_sistema($estrutural, true);
        }
        $result_estrut = pg_query("select saldo_anterior from work_planosis where estrut = '$estrutural'");
        //db_criatabela($result_estrut);
        if (@ pg_numrows($result_estrut) != true) {
          $res = pg_query("select c64_descr from conplanosis where c64_estrut = '$estrutural'");
          db_fieldsmemory($res, 0);
          
          $result_1 = pg_query("insert into work_planosis values('$estrutural',
          '$estrutural',
          0,
          '$c64_descr',
          '$c64_descr',
          $saldo_anterior,
          $saldo_anterior_debito,
          $saldo_anterior_credito,
          $saldo_final,
          '$sinal_anterior',
          '$sinal_final')");
        } else {
          
          pg_query("update work_planosis set saldo_anterior = saldo_anterior + $tot_anterior ,
          saldo_anterior_debito = saldo_anterior_debito + $tot_anterior_debito ,
          saldo_anterior_credito = saldo_anterior_credito + $tot_anterior_credito ,
          saldo_final = saldo_final + $tot_saldo_final 
          where estrut = '$estrutural' ");
        }
        if ($nivel == 1)
        break;
      }
    }
    //db_criatabela(pg_query("select * from work_planosis"));exit;
    $sql = "select case when c61_reduz = 0 then estrut_mae else estrut end as estrutural,
    c61_reduz,
    c60_descr,
    c60_finali,
    abs(saldo_anterior) as saldo_anterior,
    abs(saldo_anterior_debito) as saldo_anterior_debito,
    abs(saldo_anterior_credito) as saldo_anterior_credito,
    abs(saldo_final) as saldo_final,
    case when saldo_anterior < 0 then 'C'
    when saldo_anterior > 0 then 'D'
    else ' ' end as  sinal_anterior,
    case when saldo_final < 0 then 'C'
    when saldo_final > 0 then 'D'
    else ' ' end as  sinal_final
    from work_planosis 
    order by estrut_mae,estrut";
    
    if ($retsql == false) {
      return $result_final = pg_exec($sql);
      //     db_criatabela(pg_query($sql)); 
    } else {
      return $sql;
    }
  }
  /**
  * @ atualiza��o dessa fun��o :14/04
  * @ A versao antiga consta abaixo com o nome "db_planocontassaldo_old()";
  * @ deixar false a op��o com encerramento 
  */
  function db_planocontassaldo_matriz($anousu, $dataini, $datafim, $retsql = false, $where = '', $estrut_inicial = '', $acumula_reduzido = 'true', $encerramento = 'false',$join = '',$aOrcParametro=array()) {

//echo "<pre>";
//print_r($aOrcParametro);
//echo "</pre>";

    if ($anousu == null)
    $anousu = db_getsession("DB_anousu");
    if ($dataini == null)
    $dataini = date('Y-m-d', db_getsession('DB_datausu'));
    if ($datafim == null)
    $datafim = date('Y-m-d', db_getsession('DB_datausu'));
    if ($where != '') {
      $condicao = " and ".$where;
    } else {
      $condicao = "";
    }
    $pesq_estrut = "";
    if ($estrut_inicial != "") {
      // oberve a concatena��o da vari�vel 
      $condicao .= "  and p.c60_estrut like '$estrut_inicial%' ";
    }
    
    if ($encerramento == '')
    $encerramento = false;
    
    $sql = "
    select   
    estrut_mae, 
    estrut, 
    c61_reduz,
    c61_codcon,
    c61_codigo,
    c60_descr,
    c60_finali,
    c61_instit,
    round(substr(fc_planosaldonovo,3,13)::float8,2)::float8 as saldo_anterior,
    round(substr(fc_planosaldonovo,16,13)::float8,2)::float8 as saldo_anterior_debito,
    round(substr(fc_planosaldonovo,29,13)::float8,2)::float8 as saldo_anterior_credito,
    round(substr(fc_planosaldonovo,42,13)::float8,2)::float8 as saldo_final,
    substr(fc_planosaldonovo,55,1)::varchar(1) as sinal_anterior,
    substr(fc_planosaldonovo,56,1)::varchar(1) as sinal_final
    from 
    --(select case when substr(p.c60_estrut,1,2) = '33' then '511100000000000' 
    --             when substr(p.c60_estrut,1,2) = '34' then '511200000000000'
    --             when substr(p.c60_estrut,1,2) = '41' then '611100000000000'
    --             when substr(p.c60_estrut,1,2) = '49' then '611100000000000'
    --			   when substr(p.c60_estrut,1,2) = '42' then '611200000000000'
    --		      else p.c60_estrut end as estrut_mae,
    --	              case when substr(p.c60_estrut,1,2) = '33' then '511100000000000'
    --	                   when substr(p.c60_estrut,1,2) = '34' then '511200000000000'
    --	                   when substr(p.c60_estrut,1,2) = '41' then '611100000000000'
    --	                   when substr(p.c60_estrut,1,2) = '49' then '611100000000000'
    --		 	   when substr(p.c60_estrut,1,2) = '42' then '611200000000000'
    --		      else p.c60_estrut end as estrut,
    
    (select p.c60_estrut as estrut_mae,
    p.c60_estrut as estrut,
    c61_reduz,
    c61_codcon,
    c61_codigo,
    p.c60_descr,
    p.c60_finali,
    r.c61_instit,
    /* fc_planosaldonovo($anousu,c61_reduz,'$dataini','$datafim') */                                    
    fc_planosaldonovo($anousu,c61_reduz,'$dataini','$datafim',$encerramento)
    
    from conplanoexe e
    inner join conplanoreduz r on   r.c61_anousu = c62_anousu  and  r.c61_reduz = c62_reduz 
    inner join conplano p on r.c61_codcon = c60_codcon and r.c61_anousu = c60_anousu
    left outer join consistema on c60_codsis = c52_codsis
		$join 
    $pesq_estrut  
    where c62_anousu = $anousu $condicao) as x  
    ";
#echo "<pre>$sql</pre>";    
    // db_criatabela(pg_query($sql));exit;
    
    pg_exec("create temporary table work_pl (
    estrut_mae varchar(15), 
    estrut varchar(15),
    c61_reduz integer,
    c61_codcon integer,
    c61_codigo integer,
    c60_descr varchar(50),
    c60_finali text,
    c61_instit integer,
    saldo_anterior float8,
    saldo_anterior_debito float8,
    saldo_anterior_credito float8,
    saldo_final float8,
    sinal_anterior varchar(1),
    sinal_final varchar(1)) ");
    //   pg_exec("create temporary table work_plano as $sql");
    pg_exec("create index work_pl_estrut on work_pl(estrut)");
    pg_exec("create index work_pl_estrutmae on work_pl(estrut_mae)");
    
    $result = pg_query($sql);
    //db_criatabela($result);exit;
    $tot_anterior = 0;
    $tot_anterior_debito = 0;
    $tot_anterior_credito = 0;
    $tot_saldo_final = 0;
    GLOBAL $seq;
    GLOBAL $estrut_mae;
    GLOBAL $estrut;
    GLOBAL $c61_reduz;
    GLOBAL $c61_codcon;
    GLOBAL $c61_codigo;
    GLOBAL $c60_codcon;
    GLOBAL $c60_descr;
    GLOBAL $c60_finali;
    GLOBAL $c61_instit;
    GLOBAL $saldo_anterior;
    GLOBAL $saldo_anterior_debito;
    GLOBAL $saldo_anterior_credito;
    GLOBAL $saldo_final;
    GLOBAL $result_estrut;
    GLOBAL $sinal_anterior;
    GLOBAL $sinal_final;
    
    $work_planomae = array ();
    $work_planoestrut = array ();
    $work_plano = array ();
    $seq = 0;
    
    for ($i = 0; $i < pg_numrows($result); $i ++) {
      //  for($i = 0;$i < 20;$i++){
        db_fieldsmemory($result, $i);
        if ($sinal_anterior == "C")
        $saldo_anterior *= -1;
        if ($sinal_final == "C")
        $saldo_final *= -1;
        $tot_anterior = $saldo_anterior;
        $tot_anterior_debito = $saldo_anterior_debito;
        $tot_anterior_credito = $saldo_anterior_credito;
        $tot_saldo_final = $saldo_final;
        
        if ($acumula_reduzido == true) {
          $key = array_search("$estrut_mae", $work_planomae);
        } else {
          $key = false;
        }
        if ($key === false) { // n�o achou  
          $work_planomae[$seq] = $estrut_mae;
          $work_planoestrut[$seq] = $estrut;
          $work_plano[$seq] = array (0 => "$c61_reduz", 1 => "$c61_codcon", 2 => "$c61_codigo", 3 => "$c60_descr", 4 => "$c60_finali", 5 => "$c61_instit", 6 => "$saldo_anterior", 7 => "$saldo_anterior_debito", 8 => "$saldo_anterior_credito", 9 => "$saldo_final", 10 => "$sinal_anterior", 11 => "$sinal_final");
          $seq = $seq +1;
        } else {
          $work_plano[$key][6] += $tot_anterior;
          $work_plano[$key][7] += $tot_anterior_debito;
          $work_plano[$key][8] += $tot_anterior_credito;
          $work_plano[$key][9] += $tot_saldo_final;
        }
        $estrutural = $estrut;

        for ($ii = 1; $ii < 10; $ii ++) {
          $estrutural = db_le_mae_conplano($estrutural);
          $nivel = db_le_mae_conplano($estrutural, true);

          $key = array_search("$estrutural", $work_planomae);
          if ($key === false) { // n�o achou  
            // busca no banco e inclui
            $res = pg_query("select c60_descr,c60_finali,c60_codcon 
            from conplano
            where c60_anousu=".$anousu." and c60_estrut = '$estrutural'");
            if ($res == false || pg_numrows($res) == 0) {
              db_redireciona("db_erros.php?fechar=true&db_erro=Est� faltando cadastrar esse estrutural na contabilidade. N�vel : $nivel  Estrutural : $estrutural");
              exit;
            }
            db_fieldsmemory($res, 0);
            
            $work_planomae[$seq] = $estrutural;
            $work_planoestrut[$seq] = '';
            /// Validar Parametros do Orcamento para Acumular as Sinteticas (Estrutura e Instituicao)
            $work_plano[$seq] = (array (0 => 0, 1 => 0, 2 => $c60_codcon, 3 => $c60_descr, 4 => $c60_finali, 5 => 0, 6 => $saldo_anterior, 7 => $saldo_anterior_debito, 8 => $saldo_anterior_credito, 9 => $saldo_final, 10 => $sinal_anterior, 11 => $sinal_final));
            if(count($aOrcParametro)>0) { // Se foram passados parametros...
              if(!in_array(array($estrutural, $c61_instit), $aOrcParametro)) {
                $work_plano[$seq] = (array (0 => 0, 1 => 0, 2 => $c60_codcon, 3 => $c60_descr, 4 => $c60_finali, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => '', 11 => ''));
              }
            }

            $seq ++;
          } else {

            /// Validar Parametros do Orcamento para Acumular as Sinteticas (Estrutura e Instituicao)
            if(count($aOrcParametro)>0) { // Se foram passados parametros...
              if(!in_array(array($estrutural, $c61_instit), $aOrcParametro)) {

                continue;
              } 
              //echo "<pre>";
              //print_r(array($estrutural, $c61_instit));
              //echo "</pre>";

              $work_plano[$key][6] += $tot_anterior;
              $work_plano[$key][7] += $tot_anterior_debito;
              $work_plano[$key][8] += $tot_anterior_credito;
              $work_plano[$key][9] += $tot_saldo_final;


            } else {
              $work_plano[$key][6] += $tot_anterior;
              $work_plano[$key][7] += $tot_anterior_debito;
              $work_plano[$key][8] += $tot_anterior_credito;
              $work_plano[$key][9] += $tot_saldo_final;
            }
          }
          if ($nivel == 1)
          break;
        }
      }
      for ($i = 0; $i < sizeof($work_planomae); $i ++) {
        $mae = $work_planomae[$i];
        $estrut = $work_planoestrut[$i];
        $c61_reduz = $work_plano[$i][0];
        $c61_codcon = $work_plano[$i][1];
        $c61_codigo = $work_plano[$i][2];
        $c60_descr = $work_plano[$i][3];
        $c60_finali = $work_plano[$i][4];
        $c61_instit = $work_plano[$i][5];
        $saldo_anterior = $work_plano[$i][6];
        $saldo_anterior_debito = $work_plano[$i][7];
        $saldo_anterior_credito = $work_plano[$i][8];
        $saldo_final = $work_plano[$i][9];
        $sinal_anterior = $work_plano[$i][10];
        $sinal_final = $work_plano[$i][11];
        
        $sql = "insert into work_pl 
        values ('$mae',
        '$estrut',
        $c61_reduz, 
        $c61_codcon,
        $c61_codigo,
        '$c60_descr',
        '$c60_finali',
        $c61_instit,
        $saldo_anterior,
        $saldo_anterior_debito,
        $saldo_anterior_credito,
        $saldo_final,
        '$sinal_anterior',
        '$sinal_final')
        
        ";
        pg_exec($sql);
      }
      
      $sql = "select 
      case when c61_reduz = 0 then 
      estrut_mae 
      else 
      estrut 
      end as estrutural,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      c60_descr,
      c60_finali,
      c61_instit,
      abs(saldo_anterior) as saldo_anterior,
      abs(saldo_anterior_debito) as saldo_anterior_debito,
      abs(saldo_anterior_credito) as saldo_anterior_credito,
      abs(saldo_final) as saldo_final,
      case when saldo_anterior < 0 then  'C'
      when saldo_anterior > 0 then 'D'
      else ' ' 
      end as  sinal_anterior,
      case when saldo_final < 0 then 'C'
      when saldo_final > 0 then 'D'
      else ' '
      end as  sinal_final
      from work_pl 
      order by estrut_mae,estrut";
      
      if ($retsql == false) {
        $result_final = pg_exec($sql);
        //db_criatabela($result_final); exit;
        return $result_final;
      } else {
        return $sql;
      }
    }
    /*
    * gera balancete com as contas de receita e despesa
    * como no modelos do plano de contas 
    */
    function db_planocontassaldo_desp_rec($anousu, $dataini, $datafim, $retsql = false, $where = '', $estrut_inicial = '', $encerramento = 'false') {
      // anousu
      // where[] :   condi��es adicionais de pesquisa
      // encerramento[false/true] :  considera documentos de encerramento de exercicio
      // retsql  [false/true] : retorna somente sql  
      // estrut_inicia []  :  pesquisa por estrutural
      //
      if ($anousu == null)
      $anousu = db_getsession("DB_anousu");
      if ($dataini == null)
      $dataini = date('Y-m-d', db_getsession('DB_datausu'));
      if ($datafim == null)
      $datafim = date('Y-m-d', db_getsession('DB_datausu'));
      if ($where != '') {
        $condicao = " and ".$where;
      } else {
        $condicao = "";
      }
      
      $pesq_estrut = "";
      if ($estrut_inicial != "") {
        $pesq_estrut = "  and p.c60_estrut like '$estrut_inicial%' ";
      }
      $sql = "
      select   estrut_mae, 
      estrut, 
      c61_reduz,
      c61_codcon,
      c61_codigo,
      c60_descr,
      c60_finali,
      c61_instit,
      round(substr(fc_planosaldonovo,3,13)::float8,2)::float8 as saldo_anterior,
      round(substr(fc_planosaldonovo,16,13)::float8,2)::float8 as saldo_anterior_debito,
      round(substr(fc_planosaldonovo,29,13)::float8,2)::float8 as saldo_anterior_credito,
      round(substr(fc_planosaldonovo,42,13)::float8,2)::float8 as saldo_final,
      substr(fc_planosaldonovo,55,1)::varchar(1) as sinal_anterior,
      substr(fc_planosaldonovo,56,1)::varchar(1) as sinal_final
      from 
      (select p.c60_estrut as estrut_mae,	             
      p.c60_estrut as estrut,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      p.c60_descr,
      p.c60_finali,
      r.c61_instit,                      
      fc_planosaldonovo(".db_getsession('DB_anousu').",c61_reduz,'$dataini','$datafim',$encerramento) 
      from conplanoexe e
      inner join conplanoreduz r on r.c61_reduz = e.c62_reduz and r.c61_anousu=e.c62_anousu
      inner join conplano p on r.c61_codcon = p.c60_codcon and r.c61_anousu=p.c60_anousu
      $pesq_estrut  
      where c62_anousu = $anousu $condicao
      and ( substr(p.c60_estrut,1,1)='3'
      or fc_conplano_grupo(".db_getsession("DB_anousu").", substr(p.c60_estrut,1,1)||'%', 9004) is true
      or fc_conplano_grupo(".db_getsession("DB_anousu").", substr(p.c60_estrut,1,1)||'%', 9000) is true)) as x  
      ";
      pg_exec("create temporary table work_plano (
      estrut_mae varchar(15), 
      estrut varchar(15),
      c61_reduz integer,
      c61_codcon integer,
      c61_codigo integer,
      c60_descr varchar(50),
      c60_finali text,
      c61_instit integer,
      saldo_anterior float8,
      saldo_anterior_debito float8,
      saldo_anterior_credito float8,
      saldo_final float8,
      sinal_anterior varchar(1),
      sinal_final varchar(1)) ");
      pg_exec("create index work_plano_estrut on work_plano(estrut)");
      pg_exec("create index work_plano_estrutmae on work_plano(estrut_mae)");
      $result = pg_query($sql);
      $tot_anterior = 0;
      $tot_anterior_debito = 0;
      $tot_anterior_credito = 0;
      $tot_saldo_final = 0;
      GLOBAL $seq;
      GLOBAL $estrut_mae;
      GLOBAL $estrut;
      GLOBAL $c61_reduz;
      GLOBAL $c61_codcon;
      GLOBAL $c61_codigo;
      GLOBAL $c60_codcon;
      GLOBAL $c60_descr;
      GLOBAL $c60_finali;
      GLOBAL $c61_instit;
      GLOBAL $saldo_anterior;
      GLOBAL $saldo_anterior_debito;
      GLOBAL $saldo_anterior_credito;
      GLOBAL $saldo_final;
      GLOBAL $result_estrut;
      GLOBAL $sinal_anterior;
      GLOBAL $sinal_final;
      $work_planomae = array ();
      $work_planoestrut = array ();
      $work_plano = array ();
      $seq = 0;
      for ($i = 0; $i < pg_numrows($result); $i ++) {
        db_fieldsmemory($result, $i);
        if ($sinal_anterior == "C")
        $saldo_anterior *= -1;
        if ($sinal_final == "C")
        $saldo_final *= -1;
        $tot_anterior = $saldo_anterior;
        $tot_anterior_debito = $saldo_anterior_debito;
        $tot_anterior_credito = $saldo_anterior_credito;
        $tot_saldo_final = $saldo_final;
        $key = array_search("$estrut_mae", $work_planomae);
        if ($key === false) { // n�o achou  
          $work_planomae[$seq] = $estrut_mae;
          $work_planoestrut[$seq] = $estrut;
          $work_plano[$seq] = array (0 => "$c61_reduz", 1 => "$c61_codcon", 2 => "$c61_codigo", 3 => "$c60_descr", 4 => "$c60_finali", 5 => "$c61_instit", 6 => "$saldo_anterior", 7 => "$saldo_anterior_debito", 8 => "$saldo_anterior_credito", 9 => "$saldo_final", 10 => "$sinal_anterior", 11 => "$sinal_final");
          $seq = $seq +1;
        } else {
          $work_plano[$key][6] += $tot_anterior;
          $work_plano[$key][7] += $tot_anterior_debito;
          $work_plano[$key][8] += $tot_anterior_credito;
          $work_plano[$key][9] += $tot_saldo_final;
        }
        $estrutural = $estrut;
        for ($ii = 1; $ii < 10; $ii ++) {
          $estrutural = db_le_mae_conplano($estrutural);
          $nivel = db_le_mae_conplano($estrutural, true);
          
          $key = array_search("$estrutural", $work_planomae);
          if ($key === false) { // n�o achou  
            // busca no banco e inclui
            $res = pg_query("select c60_descr,c60_finali,c60_codcon from conplano where c60_anousu=".db_getsession("DB_anousu")." and c60_estrut = '$estrutural'");
            if ($res == false || pg_numrows($res) == 0) {
              db_redireciona("db_erros.php?fechar=true&db_erro=Est� faltando cadastrar esse estrutural na contabilidade. N�vel : $nivel  Estrutural : $estrutural");
              exit;
            }
            db_fieldsmemory($res, 0);
            
            $work_planomae[$seq] = $estrutural;
            $work_planoestrut[$seq] = '';
            $work_plano[$seq] = (array (0 => 0, 1 => 0, 2 => $c60_codcon, 3 => $c60_descr, 4 => $c60_finali, 5 => 0, 6 => $saldo_anterior, 7 => $saldo_anterior_debito, 8 => $saldo_anterior_credito, 9 => $saldo_final, 10 => $sinal_anterior, 11 => $sinal_final));
            $seq ++;
          } else {
            $work_plano[$key][6] += $tot_anterior;
            $work_plano[$key][7] += $tot_anterior_debito;
            $work_plano[$key][8] += $tot_anterior_credito;
            $work_plano[$key][9] += $tot_saldo_final;
          }
          if ($nivel == 1)
          break;
        }
      }
      for ($i = 0; $i < sizeof($work_planomae); $i ++) {
        $mae = $work_planomae[$i];
        $estrut = $work_planoestrut[$i];
        $c61_reduz = $work_plano[$i][0];
        $c61_codcon = $work_plano[$i][1];
        $c61_codigo = $work_plano[$i][2];
        $c60_descr = $work_plano[$i][3];
        $c60_finali = $work_plano[$i][4];
        $c61_instit = $work_plano[$i][5];
        $saldo_anterior = $work_plano[$i][6];
        $saldo_anterior_debito = $work_plano[$i][7];
        $saldo_anterior_credito = $work_plano[$i][8];
        $saldo_final = $work_plano[$i][9];
        $sinal_anterior = $work_plano[$i][10];
        $sinal_final = $work_plano[$i][11];
        
        $sql = "insert into work_plano 
        values ('$mae',
        '$estrut',
        $c61_reduz, 
        $c61_codcon,
        $c61_codigo,
        '$c60_descr',
        '$c60_finali',
        $c61_instit,
        $saldo_anterior,
        $saldo_anterior_debito,
        $saldo_anterior_credito,
        $saldo_final,
        '$sinal_anterior',
        '$sinal_final')
        
        ";
        pg_exec($sql);
      }
      
      $sql = "select case when c61_reduz = 0 then estrut_mae else estrut end as estrutural,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      c60_descr,
      c60_finali,
      c61_instit,
      abs(saldo_anterior) as saldo_anterior,
      abs(saldo_anterior_debito) as saldo_anterior_debito,
      abs(saldo_anterior_credito) as saldo_anterior_credito,
      abs(saldo_final) as saldo_final,
      case when saldo_anterior < 0 then 'C'
      when saldo_anterior > 0 then 'D'
      else ' ' end as  sinal_anterior,
      case when saldo_final < 0 then 'C'
      when saldo_final > 0 then 'D'
      else ' ' end as  sinal_final
      from work_plano 
      order by estrut_mae,estrut";
      
      if ($retsql == false) {
        $result_final = pg_exec($sql);
        // db_criatabela($result_final); exit;
        return $result_final;
      } else {
        return $sql;
      }
    }
    /*
    * status : desativada
    * mostrava o balancete completo, com todas as contas de nivel 3 e 4 abertas
    */
    function db_planocontassaldo_completo($anousu, $dataini, $datafim, $retsql = false, $where = '', $aOrcParametro = array(), $estrut_inicial = '', $acumula_reduzido = false, $encerramento = 'false') {
      
      return db_planocontassaldo_matriz($anousu, $dataini, $datafim, $retsql, $where, $estrut_inicial, $acumula_reduzido, $encerramento, "", $aOrcParametro);
      
    }
    
    /**
    * status: desativada
    * foi a primeira fun��o criada. usando update em tabela tempor�ria
    */
    function db_planocontassaldo($anousu, $dataini, $datafim, $retsql = false, $where = '', $estrut_inicial = '', $acumula_reduzido = false, $encerramento = 'false') {
      
      return db_planocontassaldo_matriz($anousu, $dataini, $datafim, $retsql, $where, $estrut_inicial, $acumula_reduzido, $encerramento);
      
    }
    
    function db_elementosaldo($tipo_agrupa = 0, $tipo_saldo = 2, $where = '', $anousu = null, $dataini = null, $datafim = null, $retsql = false) {
      
      if ($tipo_agrupa == 1) {
        $agrupa = ' o58_orgao, o40_descr ,';
        $agrupa1 = ' o58_orgao integer, o40_descr varchar(50),';
      }
      elseif ($tipo_agrupa == 2) {
        $agrupa = ' o58_orgao, o40_descr, o58_unidade , o41_descr,';
        $agrupa1 = ' o58_orgao integer, o40_descr varchar(50), o58_unidade integer, o41_descr varchar(50),';
      } else {
        $agrupa = '';
        $agrupa1 = '';
      }
      
      if ($anousu == null)
      $anousu = db_getsession("DB_anousu");
      
      if ($dataini == null)
      $dataini = date('Y-m-d', db_getsession('DB_datausu'));
      
      if ($datafim == null)
      $datafim = date('Y-m-d', db_getsession('DB_datausu'));
      
      if ($where != '') {
        $condicao = " and ".$where;
      } else {
        $condicao = "";
      }
      
      if ($tipo_saldo == 1)
      $tipo_pa = 'dot_ini';
      else
      $tipo_pa = 'empenhado - anulado';
      
      $sql = "
      select $agrupa codele ,elemento , descr,
      sum(dot_ini) 			as dot_ini,
      sum(saldo_anterior) 		as saldo_anterior,
      sum(empenhado) 			as empenhado,
      sum(anulado) 			as anulado,
      sum(liquidado)		 	as liquidado,
      sum(pago) 			as pago,
      sum(suplementado) 		as suplementado,
      sum(reduzido) 			as reduzido,
      sum(atual) 			as atual,
      sum(reservado) 			as reservado,
      sum(atual_menos_reservado) 	as atual_menos_reservado,
      sum(atual_a_pagar) 		as atual_a_pagar,
      sum(atual_a_pagar_liquidado) 	as atual_a_pagar_liquidado,
      sum(empenhado_acumulado) 	as empenhado_acumulado,
      sum(anulado_acumulado) 		as anulado_acumulado,
      sum(liquidado_acumulado) 	as liquidado_acumulado,
      sum(pago_acumulado) 		as pago_acumulado,
      sum(suplementado_acumulado) 	as suplementado_acumulado,
      sum(reduzido_acumulado) 		as reduzido_acumulado,
      sum(suplemen)  		        as suplemen,
      sum(especial) 		        as especial,
      sum(especial_acumulado)	        as especial_acumulado
      
      from
      (select o58_anousu, $agrupa
      o56_codele as codele,
      o56_elemento as elemento,
      o56_descr as descr,
      substr(fc_dotacaosaldo,3,12)::float8   as dot_ini,
      substr(fc_dotacaosaldo,16,12)::float8  as saldo_anterior,
      substr(fc_dotacaosaldo,29,12)::float8  as empenhado,
      substr(fc_dotacaosaldo,42,12)::float8  as anulado,
      substr(fc_dotacaosaldo,55,12)::float8  as liquidado,
      substr(fc_dotacaosaldo,68,12)::float8  as pago,
      substr(fc_dotacaosaldo,81,12)::float8  as suplementado,
      substr(fc_dotacaosaldo,094,12)::float8 as reduzido,
      substr(fc_dotacaosaldo,107,12)::float8 as atual,
      substr(fc_dotacaosaldo,120,12)::float8 as reservado,
      substr(fc_dotacaosaldo,133,12)::float8 as atual_menos_reservado,
      substr(fc_dotacaosaldo,146,12)::float8 as atual_a_pagar,
      substr(fc_dotacaosaldo,159,12)::float8 as atual_a_pagar_liquidado,
      substr(fc_dotacaosaldo,172,12)::float8 as empenhado_acumulado,
      substr(fc_dotacaosaldo,185,12)::float8 as anulado_acumulado,
      substr(fc_dotacaosaldo,198,12)::float8 as liquidado_acumulado,
      substr(fc_dotacaosaldo,211,12)::float8 as pago_acumulado,
      substr(fc_dotacaosaldo,224,12)::float8 as suplementado_acumulado,
      substr(fc_dotacaosaldo,237,12)::float8 as reduzido_acumulado,          
      substr(fc_dotacaosaldo,250,12)::float8 as suplemen,
      substr(fc_dotacaosaldo,276,12)::float8 as especial,
      substr(fc_dotacaosaldo,289,12)::float8 as especial_acumulado
      from (select *, fc_dotacaosaldo($anousu,o58_coddot,$tipo_saldo,'$dataini','$datafim')
      from orcdotacao w
      inner join orcelemento e on w.o58_codele  = e.o56_codele 
      and w.o58_anousu  = e.o56_anousu
      and e.o56_orcado is true
      inner join orcorgao    o on w.o58_orgao   = o.o40_orgao
      and o.o40_anousu  = $anousu
      inner join orcunidade  u on w.o58_unidade = u.o41_unidade 
      and w.o58_orgao   = u.o41_orgao 
      and u.o41_anousu  = $anousu
      where o58_anousu = $anousu $condicao
      order by o58_anousu ,
      $agrupa
      o56_codele,
      o56_elemento,
      o58_coddot
      ) as x
      ) as xx
      group by $agrupa codele, elemento,descr
      ";
      
      pg_exec("create temporary table work_plano($agrupa1 
      codele integer, 
      elemento varchar(13),
      descr varchar(50),
      dot_ini float8,                  
      saldo_anterior float8,
      empenhado float8,
      anulado float8,
      liquidado float8,
      pago float8,
      suplementado float8,
      reduzido float8,
      atual float8,
      reservado float8,
      atual_menos_reservado float8,
      atual_a_pagar float8,
      atual_a_pagar_liquidado float8,
      empenhado_acumulado float8,
      anulado_acumulado float8,
      liquidado_acumulado float8,
      pago_acumulado float8,
      suplementado_acumulado float8,
      reduzido_acumulado float8,
      suplemen           float8,
      especial           float8,
      especial_acumulado float8 )");
      if ($tipo_agrupa == 1) {
        pg_exec("create index work_plano_orgao_elemento on work_plano(o58_orgao,elemento)");
      }
      elseif ($tipo_agrupa == 1) {
        pg_exec("create index work_plano_orgao_unidade_elemento on work_plano(o58_orgao,o58_unidade,elemento)");
      } else {
        pg_exec("create index work_plano_elemento on work_plano(elemento)");
      }
      
      $result = pg_query($sql);
      
      // db_criatabela($result);
      $tot_dot_ini = 0;
      $tot_saldo_anterior = 0;
      $tot_empenhado = 0;
      $tot_anulado = 0;
      $tot_liquidado = 0;
      $tot_pago = 0;
      $tot_suplementado = 0;
      $tot_reduzido = 0;
      $tot_atual = 0;
      $tot_reservado = 0;
      $tot_atual_menos_reservado = 0;
      $tot_atual_a_pagar = 0;
      $tot_atual_a_pagar_liquidado = 0;
      $tot_empenhado_acumulado = 0;
      $tot_anulado_acumulado = 0;
      $tot_liquidado_acumulado = 0;
      $tot_pago_acumulado = 0;
      $tot_suplementado_acumulado = 0;
      $tot_reduzido_acumulado = 0;
      $tot_suplemen = 0;
      $tot_especial = 0;
      $tot_especial_acumulado = 0;
      
      GLOBAL $o58_orgao, $o40_descr, $o58_unidade, $o41_descr, $o56_descr, $codele, $elemento, $descr, $dot_ini, $saldo_anterior, $empenhado, $anulado, $liquidado, $pago, $suplementado, $reduzido, $atual, $reservado, $atual_menos_reservado, $atual_a_pagar, $atual_a_pagar_liquidado, $empenhado_acumulado, $anulado_acumulado, $liquidado_acumulado, $pago_acumulado, $suplementado_acumulado, $reduzido_acumulado, $especial, $especial_acumulado, $suplemen;
      // for($i = 0;$i < 10;$i++){
        for ($i = 0; $i < pg_numrows($result); $i ++) {
          db_fieldsmemory($result, $i);
          $tot_dot_ini = $dot_ini;
          $tot_saldo_anterior = $saldo_anterior;
          $tot_empenhado = $empenhado;
          $tot_anulado = $anulado;
          $tot_liquidado = $liquidado;
          $tot_pago = $pago;
          $tot_suplementado = $suplementado;
          $tot_reduzido = $reduzido;
          $tot_atual = $atual;
          $tot_reservado = $reservado;
          $tot_atual_menos_reservado = $atual_menos_reservado;
          $tot_atual_a_pagar = $atual_a_pagar;
          $tot_atual_a_pagar_liquidado = $atual_a_pagar_liquidado;
          $tot_empenhado_acumulado = $empenhado_acumulado;
          $tot_anulado_acumulado = $anulado_acumulado;
          $tot_liquidado_acumulado = $liquidado_acumulado;
          $tot_pago_acumulado = $pago_acumulado;
          $tot_suplementado_acumulado = $suplementado_acumulado;
          $tot_reduzido_acumulado = $reduzido_acumulado;
          $tot_suplemen = $suplemen;
          $tot_especial = $especial;
          $tot_especial_acumulado = $especial_acumulado;
          
          if ($tipo_agrupa == 1) {
            $agrupa2 = $o58_orgao.",'".$o40_descr."',";
            $agrupa3 = ' and o58_orgao = '.$o58_orgao;
          }
          elseif ($tipo_agrupa == 2) {
            $agrupa2 = $o58_orgao.",'".$o40_descr."',".$o58_unidade.",'".$o41_descr."',";
            $agrupa3 = ' and o58_orgao = '.$o58_orgao.' and o58_unidade = '.$o58_unidade;
          } else {
            $agrupa2 = '';
            $agrupa3 = '';
          }
          pg_query("insert into work_plano values($agrupa2
          $codele, 
          '$elemento', 
          '$descr', 
          $dot_ini, 
          $saldo_anterior, 
          $empenhado, 
          $anulado, 
          $liquidado, 
          $pago, 
          $suplementado, 
          $reduzido, 
          $atual, 
          $reservado, 
          $atual_menos_reservado, 
          $atual_a_pagar, 
          $atual_a_pagar_liquidado, 
          $empenhado_acumulado, 
          $anulado_acumulado, 
          $liquidado_acumulado, 
          $pago_acumulado, 
          $suplementado_acumulado, 
          $reduzido_acumulado,
          $suplemen,
          $especial,
          $especial_acumulado
          )");
          $estrutural = $elemento;
          for ($ii = 1; $ii < 11; $ii ++) {
            $estrutural = db_le_mae_sistema($estrutural);
            $nivel = db_le_mae_sistema($estrutural, true);
            $result_estrut = pg_query("select dot_ini from work_plano where elemento = '$estrutural' $agrupa3");
            
            //       db_criatabela($result_estrut);
            if (@ pg_numrows($result_estrut) != true) {
              $res = pg_query("select o56_descr from orcelemento where o56_anousu = $anousu and o56_elemento = '$estrutural'");
              if (@ pg_numrows($res) != true)
              break;
              db_fieldsmemory($res, 0);
              
              $result_1 = pg_query("insert into work_plano values($agrupa2
              $codele, 
              '$estrutural', 
              '$o56_descr', 
              $dot_ini, 
              $saldo_anterior, 
              $empenhado, 
              $anulado, 
              $liquidado, 
              $pago, 
              $suplementado, 
              $reduzido, 
              $atual, 
              $reservado, 
              $atual_menos_reservado, 
              $atual_a_pagar, 
              $atual_a_pagar_liquidado,
              $empenhado_acumulado, 
              $anulado_acumulado, 
              $liquidado_acumulado, 
              $pago_acumulado, 
              $suplementado_acumulado, 
              $reduzido_acumulado,
              $suplemen,
              $especial,
              $especial_acumulado							     
              )");
            } else {
              
              pg_query("update work_plano set dot_ini = dot_ini + $tot_dot_ini,
              saldo_anterior = saldo_anterior + $tot_saldo_anterior,
              empenhado = empenhado + $tot_empenhado,
              anulado = anulado + $tot_anulado,
              liquidado = liquidado + $tot_liquidado,
              pago = pago + $tot_pago,
              suplementado = suplementado + $tot_suplementado,
              reduzido = reduzido + $tot_reduzido,
              atual = atual + $tot_atual,
              reservado = reservado + $tot_reservado,
              atual_menos_reservado = atual_menos_reservado + $tot_atual_menos_reservado,
              atual_a_pagar = atual_a_pagar + $tot_atual_a_pagar,
              atual_a_pagar_liquidado = atual_a_pagar_liquidado + $tot_atual_a_pagar_liquidado,
              empenhado_acumulado = empenhado_acumulado + $tot_empenhado_acumulado,
              anulado_acumulado = anulado_acumulado + $tot_anulado_acumulado,
              liquidado_acumulado = liquidado_acumulado + $tot_liquidado_acumulado,
              pago_acumulado = pago_acumulado + $tot_pago_acumulado,
              suplementado_acumulado = suplementado_acumulado + $tot_suplementado_acumulado,
              reduzido_acumulado = reduzido_acumulado + $tot_reduzido_acumulado,
              suplemen           = suplemen + $suplemen,
              especial           = especial     +   $tot_especial,
              especial_acumulado = especial_acumulado + $tot_especial_acumulado
              where elemento = '$estrutural' $agrupa3");
            }
            if ($nivel == 1)
            break;
          }
        }
        
        $sql = "select * 
        from work_plano 
        order by $agrupa elemento";
        
        if ($retsql == false) {
          return $result_final = pg_exec($sql);
        } else {
          return $sql;
        }
        
      }
      
      function db_rcl($mesini, $mesfim, $instit) {
        
        $clconrelinfo = new cl_conrelinfo;
        
        $w_instit = str_replace('-', ', ', $instit);
        $result_variaveis = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores('5', $w_instit));
        
        $linhaini = 18 * ($mesini -1);
        $linhafim = (18 * ($mesfim -1)) + 17;
        $numlin = 0;
        $mes = 0;
        for ($p = 0; $p < 18; $p ++) {
          $valor[$p] = 0;
        }
        global $c83_informacao;
        for ($i = $linhaini; $i < $linhafim; $i ++) {
          if ($numlin == 18) {
            $mes = $mes +1;
            $numlin = 0;
          }
          db_fieldsmemory($result_variaveis, $i);
          $valor[$numlin] += $c83_informacao;
          $numlin ++;
        }
        $valorrec = $valor[0] + $valor[1] + $valor[2] + $valor[3] + $valor[4] + $valor[5] + $valor[6] + $valor[7];
        
        $valorded = $valor[8] + $valor[9] + $valor[11] + $valor[12] + $valor[13] + $valor[14] + $valor[16] + $valor[17];
        $rcl = $valorrec - $valorded;
        return $rcl;
      }
      
      /*
      * 
      * 
      */
      function grupoconta($anousu, $dataini = '2005-01-01', $datafim = '2005-12-31', $db_selinstit = 1, $retsql = false, $orc = false) {
        /*
        *Esta Fun��o Agrupa as conta pela Sele��o do Relat�rio 21(tabela - orcparamrel) Sequencias (tabela - orcparamseq), os elemente s�o informados manualmente pelo usuario
        *OBS 1 ha fun��o pode retonar mais valores desde q tenha o cuidado de n�o mudar os nomes dos campos, tb se deve ter o cuidado de trazer valores em todos os SQL
        *     devido no final a fun��o sempre possuir um UNION SENDO ASSIM CUIDADO CUIDADO FAZER BACK TOMAR CUIDADO
        * OBS 2 n�o utilize mais fun��es como db_planocontassaldo_completo ou db_dotacaosaldo, utilize as q ja existe, se criar novas vai deixar o sistema mais lento 
        *     ent�o grupe trabalhe SQL � mais vantagem.    
        * ********Parmetros**************
        * $anousu  
        * $dataini - data inicial de pesquisa
        * $datafim - data final de pesquisa
        * $db_selinstit  - Institui��es
        * $retsql - Retornar o SQL ou a Tabela
        * $orc - Op��o de retonar� valores or�amentarios ou de execu��o
        */
        
        $selinstit = str_replace('-', ', ', $db_selinstit);
        $where_rec = " o70_instit in ($selinstit)";
        $sele_work = "c61_instit in ($selinstit)";
        $where = "w.o58_instit in ($selinstit) ";
        
        global $estrutural, $c60_descr, $saldo_anterior, $saldo_anterior_debito, $saldo_anterior_credito, $saldo_final, $o57_fonte, $o57_descr, $saldo_inicial, $saldo_arrecadado, $anterior, $inicial, $executado;
        
        $orcparamrel = new cl_orcparamrel;
        //******************************************************************************************************************
        // � necessario realizar um for na tabela orcparamseq para q ha mesma esteja sempre atualizada automaticamente
        //******************************************************************************************************************
        $paramconta['0'] = $orcparamrel->sql_parametro('21', '0', str_replace('-', ', ', $db_selinstit));
        $paramconta['1'] = $orcparamrel->sql_parametro('21', '1', str_replace('-', ', ', $db_selinstit));
        $paramconta['2'] = $orcparamrel->sql_parametro('21', '2', str_replace('-', ', ', $db_selinstit));
        $paramconta['3'] = $orcparamrel->sql_parametro('21', '3', str_replace('-', ', ', $db_selinstit));
        $paramconta['4'] = $orcparamrel->sql_parametro('21', '4', str_replace('-', ', ', $db_selinstit));
        $paramconta['5'] = $orcparamrel->sql_parametro('21', '5', str_replace('-', ', ', $db_selinstit));
        $paramconta['6'] = $orcparamrel->sql_parametro('21', '6', str_replace('-', ', ', $db_selinstit));
        $paramconta['7'] = $orcparamrel->sql_parametro('21', '7', str_replace('-', ', ', $db_selinstit));
        $paramconta['8'] = $orcparamrel->sql_parametro('21', '8', str_replace('-', ', ', $db_selinstit));
        $paramconta['9'] = $orcparamrel->sql_parametro('21', '9', str_replace('-', ', ', $db_selinstit));
        $paramconta['10'] = $orcparamrel->sql_parametro('21', '10', str_replace('-', ', ', $db_selinstit));
        $paramconta['11'] = $orcparamrel->sql_parametro('21', '11', str_replace('-', ', ', $db_selinstit));
        $paramconta['12'] = $orcparamrel->sql_parametro('21', '12', str_replace('-', ', ', $db_selinstit));
        $paramconta['13'] = $orcparamrel->sql_parametro('21', '13', str_replace('-', ', ', $db_selinstit));
        $paramconta['14'] = $orcparamrel->sql_parametro('21', '14', str_replace('-', ', ', $db_selinstit));
        $paramconta['15'] = $orcparamrel->sql_parametro('21', '15', str_replace('-', ', ', $db_selinstit));
        $paramconta['16'] = $orcparamrel->sql_parametro('21', '16', str_replace('-', ', ', $db_selinstit));
        $paramconta['17'] = $orcparamrel->sql_parametro('21', '17', str_replace('-', ', ', $db_selinstit));
        $paramconta['18'] = $orcparamrel->sql_parametro('21', '18', str_replace('-', ', ', $db_selinstit));
        $paramconta['19'] = $orcparamrel->sql_parametro('21', '19', str_replace('-', ', ', $db_selinstit));
        
        if ($orc == false) { // testa o parametro, se sim � valores de EXECU��O
          // Esta fun��o esta sendo utilizada para trazer as contas do RECEITA E DESPESA, ATIVO,PASSIVO,DIMINUTIVO E AUMENTATIVO 
          $sql1 = db_planocontassaldo_completo($anousu, $dataini, $datafim, true, $sele_work);
          $sql11 = "select "."bbb.estrutural as estrutural, "."bbb.c60_descr as c60_descr, "."bbb.saldo_anterior as saldo_anterior, "."bbb.saldo_anterior_debito as saldo_anterior_debito, "."bbb.saldo_anterior_credito as saldo_anterior_credito, "."bbb.saldo_final as saldo_final "."from ($sql1) as bbb "."where substr(bbb.estrutural,1,1)<>'3'";
          
          // Esta fun��o esta sendo utilizada para trazer as contas do DESPESA
          $sql2 = db_dotacaosaldo(8, 2, 4, true, $where, $anousu, $dataini, $datafim, null, null, true);
          $sql22 = "select "."ccc.o58_elemento||'00' as estrutural, "."ccc.o56_descr as c60_descr, "."sum(ccc.saldo_anterior) as saldo_anterior, "."sum(ccc.empenhado)-sum(anulado) as saldo_anterior_debito, "."sum(ccc.liquidado) as saldo_anterior_credito, "."sum(ccc.pago) as saldo_final "."from ($sql2) as ccc "."group by ccc.o58_elemento||'00',ccc.o56_descr ";
          
          // Esta tabela � criada para estruturar os valores de todos as contas de despesas,  
          $creat_sql = "create temp table work as 
          select o56_elemento||'00' as estrutural,o56_descr as c60_descr,0::float8 as valor1,0::float8 as valor2,0::float8 as valor3,0::float8 as valor4 
          from orcelemento 
          inner join conplano on c60_codcon = o56_codele and c60_anousu = o56_anousu
          where o56_anousu = $anousu";
          
          // Come�o da estrutura��o da tabela temporaria
          $result_rec = pg_exec($creat_sql);
          $result_rec = pg_exec($sql22);
          for ($i = 0; $i < pg_numrows($result_rec); $i ++) {
            db_fieldsmemory($result_rec, $i);
            //aqui � colocado os valores da DB_DOTA��OSALDO recomento e alerto ao mesmo tempo pode, pode se buscar mais valores desde tome o cuidado citado acima no come�o da fun��o 
            $valor1 = $saldo_anterior;
            $valor2 = $saldo_anterior_debito;
            $valor3 = $saldo_anterior_credito;
            $valor4 = $saldo_final;
            // n�o coloque o dedo nesses valores adcione abaixo e n�o esque�a de adiconar na tabela
            
            $sql = "update work set valor1 = valor1+$valor1,valor2 = valor2+$valor2,valor3 = valor3+$valor3,valor4 = valor4+$valor4 where work.estrutural = '$estrutural'";
            $result = pg_exec($sql);
            $executa = true;
            $conta = 0;
            while ($executa == true) {
              $estrutural = db_le_mae($estrutural, false);
              $sql = "update work set valor1 = valor1+$valor1,valor2 = valor2+$valor2,valor3 = valor3+$valor3,valor4 = valor4+$valor4 where work.estrutural = '".$estrutural."00"."'";
              $result = pg_exec($sql);
              if (substr($estrutural, 1, 12) == "0000000000000") {
                $executa = false;
              }
              $conta ++;
              if ($conta > 10)
              $executa = false;
            }
          }
          //to listando todas as contas de despesas com valores 
          $sql22 = "select * from work ";
          
          //nesse SQL � trabalhado novamente com a db_dota��o saldo como eu disse na OBS 2, caso n�o tenha lido recomento,
          // continuando eu agrupo as fun��es, quando eu fiz isso era pq eu queria os valores das fun�oes para colocar no Relat�rio BALAN�O FINANCEIRO 
          $sql33 = "select "."'F'||ddd.o58_funcao as estrutural, "."ddd.o52_descr as c60_descr, "."sum(ddd.saldo_anterior) as saldo_anterior, "."sum(ddd.empenhado)-sum(anulado) as saldo_anterior_debito, "."sum(ddd.liquidado) as saldo_anterior_credito, "."sum(ddd.pago) as saldo_final "."from ($sql2) as ddd "."group by 'F'||ddd.o58_funcao,ddd.o52_descr ";
          // CUIDADO 
          // CUIDADO 
          //AREA DE UNION, aqui estou unindo todos os sqls para que eu tenha um unico para ent�o eu executar e tirar apenas os dados solicitados pelo usuario
          //dados esse q foi comentado assim, leia os comentarios n�o to gostando meu tempo ha atoa
          $sql12 = $sql11." union ".$sql22;
          $sql = $sql12." union ".$sql33;
          $result = pg_exec($sql);
          
          // aqui � filtrado das as conta selecionadas pelo parametros CARLOS OU PAULO se for um de vc(s) acerta o for nos parametros e troca para array 
          $criatabela = 'create temp table work_grupconta('.'grupo 						varchar(4),'.'estrut 						varchar(20),'.'descr 						varchar(100),'.'valor_ant 				float8,'.'valor_debito    		float8,'.'valor_credito  	 	float8,'.'valor_final 				float8'.')';
          global $estrutural, $c60_descr, $saldo_anterior, $saldo_anterior_debito, $saldo_anterior_credito, $saldo_final;
          pg_exec($criatabela);
          for ($i = 0; $i < pg_numrows($result); $i ++) {
            db_fieldsmemory($result, $i);
            for ($x = 0; $x < count($paramconta); $x ++) {
              if (in_array($estrutural, $paramconta[$x])) {
                $g = $x;
                pg_exec("insert into work_grupconta values (' ".$g." ',"."'".$estrutural."',"."'".$c60_descr."',"."$saldo_anterior,"."$saldo_anterior_debito,"."$saldo_anterior_credito,"."$saldo_final)");
              }
              if (substr($estrutural, 0, 1) == 'F') {
                $estrutural = substr($estrutural, 1);
                $g = 'F';
                pg_exec("insert into work_grupconta values (' ".$g." ',"."'".$estrutural."',"."'".$c60_descr."',"."$saldo_anterior,"."$saldo_anterior_debito,"."$saldo_anterior_credito,"."$saldo_final)");
              }
            }
          }
          
        } else { // caso queira OR�AMENTARIA
          
          // vamos novamente para o SQL ja bem eu falei assim para cuidar bem dessas coisas ou seja tem um IF ha db_dotacaosaldo esta sendo utilizada uma vez e a receita tb 
          // eu ja disse pode adcionar valores, mas n�o retire 
          $sql1 = db_receitasaldo(11, 1, 3, true, $where_rec, $anousu, $dataini, $datafim, true);
          $grup_rec = "select ccc.o57_fonte as estrutural, "."ccc.o57_descr as c60_descr, "."sum(ccc.saldo_anterior) as anterior, "."sum(ccc.saldo_inicial) + sum(ccc.saldo_prevadic_acum)as inicial, "."sum(saldo_arrecadado) as executado "."from ($sql1) as ccc group by ccc.o57_fonte,ccc.o57_descr ";
          
          $sql2 = db_dotacaosaldo(8, 2, 4, true, $where, $anousu, $dataini, $datafim, null, null, true);
          $sql11 = "select ccc.o58_elemento||'00' as estrutural, "."ccc.o56_descr as c60_descr, "."sum(ccc.saldo_anterior) as anterior, "."sum(ccc.dot_ini) + sum(ccc.suplementado_acumulado) - sum(ccc.reduzido_acumulado) as inicial, "."sum(ccc.empenhado)-sum(anulado) as executado "."from ($sql2) as ccc group by ccc.o58_elemento||'00',ccc.o56_descr ";
          
          // preste aten�o aqui eu podia ter usado duas db_dotacaosaldo mas eu executaria ela duas vezes sendo assim eu agrupo 
          $sql33 = "select "."'F'||ddd.o58_funcao as estrutural, "."ddd.o52_descr as c60_descr, "."sum(ddd.saldo_anterior) as anterior, "."sum(ddd.dot_ini) + sum(ddd.suplementado_acumulado) - sum(ddd.reduzido_acumulado) as inicial, "."sum(ddd.empenhado)-sum(ddd.anulado) as executado "."from ($sql2) as ddd "."group by 'F'||ddd.o58_funcao,ddd.o52_descr ";
          
          //crio a trabela temporaria, nesse momento eu me pergunto, copio o comentario acima e col� aqui ou mando o comum ler a cima, bom � melhor copiar COMUM n�o l� 
          // Esta tabela � criada para estruturar os valores de todos as contas de despesas,
          $creat_sql = "create temp table work as 
          select o56_elemento||'00' as estrutural,o56_descr as c60_descr,0::float8 as valor1,0::float8 as valor2,0::float8 as valor3 
          from orcelemento 
          inner join conplano on c60_codcon = o56_codele and c60_anousu = o56_anousu
          where o56_anousu = $anousu";
          
          $result_rec = pg_exec($creat_sql);
          $result_rec = pg_exec($sql11);
          // novamente eu copio, da vontade de mandar ler o DICAS.PHP, torama q o comum q esteja dando manuten��o aqui nesse codigo seja um dos velhos IF velho THEN deve estar lembrando de mim ELSE pergunta para os velhos
          // Come�o da estrutura��o da tabela temporaria
          for ($i = 0; $i < pg_numrows($result_rec); $i ++) {
            db_fieldsmemory($result_rec, $i);
            // eu ja disse o que pode fazer aqui, se n�o sei acima vai ler 
            $valor1 = $anterior;
            $valor2 = $inicial;
            $valor3 = $executado;
            // troca por array 
            $sql = "update work set valor1 = valor1+$valor1,valor2 = valor2+$valor2,valor3 = valor3+$valor3 where work.estrutural = '$estrutural'";
            $result = pg_exec($sql);
            $executa = true;
            $conta = 0;
            while ($executa == true) {
              $estrutural = db_le_mae($estrutural, false);
              // troca por array 
              $sql = "update work set valor1 = valor1+$valor1,valor2 = valor2+$valor2,valor3 = valor3+$valor3 where work.estrutural = '".$estrutural."00"."'";
              $result = pg_exec($sql);
              if (substr($estrutural, 1, 12) == "0000000000000") {
                $executa = false;
              }
              $conta ++;
              if ($conta > 10)
              $executa = false;
            }
          }
          $sql22 = "select * from work";
          
          $sql12 = $grup_rec." union ".$sql22;
          $sql = $sql12." union ".$sql33;
          $result = pg_exec($sql);
          
          $criatabela = 'create temp table work_grupconta('.'grupo 						varchar(4),'.'estrut 						varchar(20),'.'descr 						varchar(100),'.'valor_anti 				float8,'.'valor_ini    		float8,'.'valor_exec  	 	float8'.')';
          global $estrutural, $c60_descr, $anterior, $inicial, $executado;
          pg_exec($criatabela);
          for ($i = 0; $i < pg_numrows($result); $i ++) {
            db_fieldsmemory($result, $i);
            for ($x = 0; $x < count($paramconta); $x ++) {
              if (in_array($estrutural, $paramconta[$x])) {
                $g = $x;
                // troca por array ME ORGULHE eu n�o tinha tempo
                pg_exec("insert into work_grupconta values (' ".$g." ',"."'".$estrutural."',"."'".$c60_descr."',"."$anterior,"."$inicial,"."$executado )");
              }
              if (substr($estrutural, 0, 1) == 'F') {
                $estrutural = substr($estrutural, 1);
                $g = 'F';
                //troca por array ME ORGULHE eu n�o tinha tempo
                pg_exec("insert into work_grupconta values (' ".$g." ',"."'".$estrutural."',"."'".$c60_descr."',"."$anterior,"."$inicial,"."$executado )");
              }
            }
          }
        }
        $sqlfim = "select * from work_grupconta order by grupo,estrut";
        if ($retsql == false) {
          $resultado = pg_exec($sqlfim);
        } else {
          $resultado = $sqlfim;
        }
        return $resultado;
      }
      
      function calcula_rcl($anousu, $dtini, $dtfin, $db_selinstit, $matriz = false) {
        global $o57_fonte, $janeiro, $fevereiro, $marco, $abril, $maio, $junho, $julho, $agosto, $setembro, $outubro, $novembro, $dezembro;
        
        $result_rec = new cl_receita_saldo_mes;
        $result_rec->anousu = $anousu;
        $result_rec->dtini = $dtini;
        $result_rec->dtfim = $dtfin;
        $result_rec->usa_datas = 'sim';
        $result_rec->instit = "".str_replace('-', ', ', $db_selinstit)." ";
        $result_rec->sql_record();
        $result_rec = $result_rec->result;
        @pg_exec("drop table work_plano");

        // pega parametros do relatorio de rcl
        $orcparamrel = new cl_orcparamrel;
        $param[1]  = $orcparamrel->sql_parametro('5', '1', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[2]  = $orcparamrel->sql_parametro('5', '2', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[3]  = $orcparamrel->sql_parametro('5', '3', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[4]  = $orcparamrel->sql_parametro('5', '4', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[5]  = $orcparamrel->sql_parametro('5', '5', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[6]  = $orcparamrel->sql_parametro('5', '6', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[7]  = $orcparamrel->sql_parametro('5', '7', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[8]  = $orcparamrel->sql_parametro('5', '8', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[9]  = $orcparamrel->sql_parametro('5', '9', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[10] = $orcparamrel->sql_parametro('5', '10', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[11] = $orcparamrel->sql_parametro('5', '11', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[12] = $orcparamrel->sql_parametro('5', '12', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[13] = $orcparamrel->sql_parametro('5', '13', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[14] = $orcparamrel->sql_parametro('5', '14', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[15] = $orcparamrel->sql_parametro('5', '15', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
				
        // inicio dedu��o
        $param[16] = $orcparamrel->sql_parametro('5', '16', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[17] = $orcparamrel->sql_parametro('5', '17', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[18] = $orcparamrel->sql_parametro('5', '18', 'f', str_replace('-', ', ', $db_selinstit), $anousu);

        $total = 0;

				$rcl_matriz['janeiro']		= 0;
				$rcl_matriz['fevereiro']	= 0;
				$rcl_matriz['marco']			= 0;
				$rcl_matriz['abril']			= 0;
				$rcl_matriz['maio']				= 0;
				$rcl_matriz['junho']			= 0;
				$rcl_matriz['julho']			= 0;
				$rcl_matriz['agosto']			= 0;
				$rcl_matriz['setembro']		= 0;
				$rcl_matriz['outubro']		= 0;
				$rcl_matriz['novembro']		= 0;
				$rcl_matriz['dezembro']		= 0;
        
        for ($p=1; $p <= 18; $p++) {
          // 18 � a quantidade de parametros ou linhas existentes nos parametros

          for ($i=0; $i < pg_numrows($result_rec); $i++) {
            db_fieldsmemory($result_rec, $i);
            
            $estrutural = $o57_fonte;
            
            if (in_array($estrutural, $param[$p])) {
              if ($p == 18 ) {
			  
                $janeiro   *= -1;
                $fevereiro *= -1;
                $marco     *= -1;
                $abril     *= -1;
                $maio      *= -1;
                $junho     *= -1;
                $julho     *= -1;
                $agosto    *= -1;
                $setembro  *= -1;
                $outubro   *= -1;
                $novembro  *= -1;
                $dezembro  *= -1;
         
              }  
              if ($p <= 15) {

                $rcl_matriz['janeiro']		+= $janeiro;
                $rcl_matriz['fevereiro']	+= $fevereiro;
                $rcl_matriz['marco']			+= $marco;
                $rcl_matriz['abril']			+= $abril;
                $rcl_matriz['maio']				+= $maio;
                $rcl_matriz['junho']			+= $junho;
                $rcl_matriz['julho']			+= $julho;
                $rcl_matriz['agosto']			+= $agosto;
                $rcl_matriz['setembro']		+= $setembro;
                $rcl_matriz['outubro']		+= $outubro;
                $rcl_matriz['novembro']		+= $novembro;
                $rcl_matriz['dezembro']		+= $dezembro;
								
              } else {
                
//                if (substr($estrutural,0,3) == "497") {
                if (db_conplano_grupo($anousu,substr($estrutural,0,3)."%",9001) == true) {

									$rcl_matriz['janeiro']		-= ($janeiro);
									$rcl_matriz['fevereiro']	-= ($fevereiro);
									$rcl_matriz['marco']			-= ($marco);
									$rcl_matriz['abril']			-= ($abril);
									$rcl_matriz['maio']				-= ($maio);
									$rcl_matriz['junho']			-= ($junho);
									$rcl_matriz['julho']			-= ($julho);
									$rcl_matriz['agosto']			-= ($agosto);
									$rcl_matriz['setembro']		-= ($setembro);
									$rcl_matriz['outubro']		-= ($outubro);
									$rcl_matriz['novembro']		-= ($novembro);
									$rcl_matriz['dezembro']		-= ($dezembro);
                  
                } else {

									$rcl_matriz['janeiro']		-= $janeiro;
									$rcl_matriz['fevereiro']	-= $fevereiro;
									$rcl_matriz['marco']			-= $marco;
									$rcl_matriz['abril']			-= $abril;
									$rcl_matriz['maio']				-= $maio;
									$rcl_matriz['junho']			-= $junho;
									$rcl_matriz['julho']			-= $julho;
									$rcl_matriz['agosto']			-= $agosto;
									$rcl_matriz['setembro']		-= $setembro;
									$rcl_matriz['outubro']		-= $outubro;
									$rcl_matriz['novembro']		-= $novembro;
									$rcl_matriz['dezembro']		-= $dezembro;
                  
                }
                
              }
              
            }
            
          }
          
        }

        $total = $rcl_matriz['janeiro'] + $rcl_matriz['fevereiro'] + $rcl_matriz['marco'] + $rcl_matriz['abril'] + $rcl_matriz['maio'] + $rcl_matriz['junho'] + $rcl_matriz['julho'] + $rcl_matriz['agosto'] + $rcl_matriz['setembro'] + $rcl_matriz['outubro'] + $rcl_matriz['novembro'] + $rcl_matriz['dezembro'];

        if ($matriz == true) {
          return $rcl_matriz;
        }
        
        return $total;
        
      }
      
      function calcula_rcl2($anousu, $dtini, $dtfin, $db_selinstit, $matriz = false, $codrel = 5,$data = 0) {
        global $o57_fonte, $janeiro, $fevereiro, $marco, $abril, $maio, $junho, $julho, $agosto, $setembro, $outubro, $novembro, $dezembro, $bimestre, $dt;

        if ($data == 0){
          $dt = split("-",$dtfin); 
        } else {
          $dt = split("-",$data);
        }

        $bimestre      = (int)substr(db_retorna_periodo($dt[1],"B"),0,1);
        $bimestre     *= 2;
        $flag_anterior = false;

        if ($anousu < db_getsession("DB_anousu")){  // Exercicio anterior
          $bimestre     += 1;
          $flag_anterior = true;
        }

        $result_rec = new cl_receita_saldo_mes;
        $result_rec->anousu    = $anousu;
        $result_rec->dtini     = $dtini;
        $result_rec->dtfim     = $dtfin;
        $result_rec->usa_datas = 'sim';
        $result_rec->instit = $db_selinstit;
        $result_rec->sql_record();
        $result_rec = $result_rec->result;
        @pg_exec("drop table work_plano");
        
        // pega parametros do relatorio de rcl
        $orcparamrel = new cl_orcparamrel;
        $param[1]  = $orcparamrel->sql_parametro($codrel, '1', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[2]  = $orcparamrel->sql_parametro($codrel, '2', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[3]  = $orcparamrel->sql_parametro($codrel, '3', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[4]  = $orcparamrel->sql_parametro($codrel, '4', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[5]  = $orcparamrel->sql_parametro($codrel, '5', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[6]  = $orcparamrel->sql_parametro($codrel, '6', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[7]  = $orcparamrel->sql_parametro($codrel, '7', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[8]  = $orcparamrel->sql_parametro($codrel, '8', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[9]  = $orcparamrel->sql_parametro($codrel, '9', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[10] = $orcparamrel->sql_parametro($codrel, '10', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[11] = $orcparamrel->sql_parametro($codrel, '11', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[12] = $orcparamrel->sql_parametro($codrel, '12', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[13] = $orcparamrel->sql_parametro($codrel, '13', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[14] = $orcparamrel->sql_parametro($codrel, '14', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[15] = $orcparamrel->sql_parametro($codrel, '15', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
				
        // inicio dedu��o em 2007
        $param[16] = $orcparamrel->sql_parametro($codrel, '16', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[17] = $orcparamrel->sql_parametro($codrel, '17', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[18] = $orcparamrel->sql_parametro($codrel, '18', 'f', str_replace('-', ', ', $db_selinstit), $anousu);

        if ($codrel == 27){
          $param[19] = $orcparamrel->sql_parametro($codrel, '19', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
          $param[20] = $orcparamrel->sql_parametro($codrel, '20', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
          $param[21] = $orcparamrel->sql_parametro($codrel, '21', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        }
        if ($codrel == 59) {
          
          $param[1]  = $orcparamrel->sql_parametro('59','1', 'f', str_replace('-', ', ', $db_selinstit), $anousu ,false);
          $param[2]  = $orcparamrel->sql_parametro('59','2', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          $param[3]  = $orcparamrel->sql_parametro('59','3', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          $param[4]  = $orcparamrel->sql_parametro('59','4', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          $param[5]  = $orcparamrel->sql_parametro('59','5', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          $param[6]  = $orcparamrel->sql_parametro('59','6', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          $param[7]  = $orcparamrel->sql_parametro('59','7', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          $param[8]  = $orcparamrel->sql_parametro('59','8', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          $param[9]  = $orcparamrel->sql_parametro('59','9', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          $param[10]  = $orcparamrel->sql_parametro('59','10', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          $param[11] = $orcparamrel->sql_parametro('59','11', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          $param[12] = $orcparamrel->sql_parametro('59','12', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          $param[13] = $orcparamrel->sql_parametro('59','13', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          $param[14] = $orcparamrel->sql_parametro('59','14', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          $param[15] = $orcparamrel->sql_parametro('59','15', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          $param[16] = $orcparamrel->sql_parametro('59','16', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          $param[17] = $orcparamrel->sql_parametro('59','17', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          $param[18] = $orcparamrel->sql_parametro('59','18', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          $param[19] = $orcparamrel->sql_parametro('59','19', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          //deducoes
          $param[20] = $orcparamrel->sql_parametro('59','20', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          $param[21] = $orcparamrel->sql_parametro('59','21', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
          $param[22] = $orcparamrel->sql_parametro('59','22', 'f', str_replace('-', ', ', $db_selinstit),$anousu,false);
        }
        
        $total = 0;
				
        $rcl_matriz2['janeiro']		= 0;
				$rcl_matriz2['fevereiro']	= 0;
				$rcl_matriz2['marco']			= 0;
				$rcl_matriz2['abril']			= 0;
				$rcl_matriz2['maio']			= 0;
				$rcl_matriz2['junho']			= 0;
				$rcl_matriz2['julho']			= 0;
				$rcl_matriz2['agosto']		= 0;
				$rcl_matriz2['setembro']	= 0;
				$rcl_matriz2['outubro']		= 0;
				$rcl_matriz2['novembro']	= 0;
				$rcl_matriz2['dezembro']	= 0;

// Arrecadacao
				$rcl_matriz[0][1]	 = 0;  // Janeiro
				$rcl_matriz[0][2]	 = 0;  // Fevereiro
				$rcl_matriz[0][3]	 = 0;  // Marco
				$rcl_matriz[0][4]	 = 0;  // Abril
				$rcl_matriz[0][5]	 = 0;  // Maio
				$rcl_matriz[0][6]	 = 0;  // Junho
				$rcl_matriz[0][7]	 = 0;  // Julho
				$rcl_matriz[0][8]	 = 0;  // Agosto
				$rcl_matriz[0][9]	 = 0;  // Setembro
				$rcl_matriz[0][10] = 0;  // Outubro
				$rcl_matriz[0][11] = 0;  // Novembro
				$rcl_matriz[0][12] = 0;  // Dezembro

// Deducoes
				$rcl_matriz[1][1]	 = 0;  // Janeiro
				$rcl_matriz[1][2]	 = 0;  // Fevereiro
				$rcl_matriz[1][3]	 = 0;  // Marco
				$rcl_matriz[1][4]	 = 0;  // Abril
				$rcl_matriz[1][5]	 = 0;  // Maio
				$rcl_matriz[1][6]	 = 0;  // Junho
				$rcl_matriz[1][7]	 = 0;  // Julho
				$rcl_matriz[1][8]	 = 0;  // Agosto
				$rcl_matriz[1][9]	 = 0;  // Setembro
				$rcl_matriz[1][10] = 0;  // Outubro
				$rcl_matriz[1][11] = 0;  // Novembro
				$rcl_matriz[1][12] = 0;  // Dezembro

        $tot_param = 18;
        $ult_param = 15;

        if ($codrel == 27){
          $tot_param = 21;
          $ult_param = 18;
          
        } else if ($codrel == 59) {
          
          $tot_param = 22;
          $ult_param = 19;
          
        }

        for ($p=1; $p <= $tot_param; $p++) {
          // 18 para 2007 e 21 para 2008 � a quantidade de parametros ou linhas existentes nos parametros

          for ($i=0; $i < pg_numrows($result_rec); $i++) {
            db_fieldsmemory($result_rec, $i);
            
            $estrutural = $o57_fonte;
            
            if (in_array($estrutural, $param[$p])) {
              
              if ($p == $tot_param ) {
			  
                $janeiro   *= -1;
                $fevereiro *= -1;
                $marco     *= -1;
                $abril     *= -1;
                $maio      *= -1;
                $junho     *= -1;
                $julho     *= -1;
                $agosto    *= -1;
                $setembro  *= -1;
                $outubro   *= -1;
                $novembro  *= -1;
                $dezembro  *= -1;
         
              }  
              if ($p <= $ult_param) {

                $rcl_matriz[0][1]	 += $janeiro;
                $rcl_matriz[0][2]	 += $fevereiro;
                $rcl_matriz[0][3]	 += $marco;
                $rcl_matriz[0][4]	 += $abril;
                $rcl_matriz[0][5]	 += $maio;
                $rcl_matriz[0][6]	 += $junho;
                $rcl_matriz[0][7]	 += $julho;
                $rcl_matriz[0][8]	 += $agosto;
                $rcl_matriz[0][9]  += $setembro;
                $rcl_matriz[0][10] += $outubro;
                $rcl_matriz[0][11] += $novembro;
                $rcl_matriz[0][12] += $dezembro;
								
              } else {
                
                if (db_conplano_grupo($anousu,substr($estrutural,0,3)."%",9001) == true) {

                  $rcl_matriz[1][1]	 += ($janeiro);
                  $rcl_matriz[1][2]	 += ($fevereiro);
                  $rcl_matriz[1][3]	 += ($marco);
                  $rcl_matriz[1][4]	 += ($abril);
                  $rcl_matriz[1][5]	 += ($maio);
                  $rcl_matriz[1][6]	 += ($junho);
                  $rcl_matriz[1][7]	 += ($julho);
                  $rcl_matriz[1][8]	 += ($agosto);
                  $rcl_matriz[1][9]	 += ($setembro);
                  $rcl_matriz[1][10] += ($outubro);
                  $rcl_matriz[1][11] += ($novembro);
                  $rcl_matriz[1][12] += ($dezembro);
                } else {

                  $rcl_matriz[1][1]	 += $janeiro;
                  $rcl_matriz[1][2]	 += $fevereiro;
                  $rcl_matriz[1][3]	 += $marco;
                  $rcl_matriz[1][4]	 += $abril;
                  $rcl_matriz[1][5]	 += $maio;
                  $rcl_matriz[1][6]	 += $junho;
                  $rcl_matriz[1][7]	 += $julho;
                  $rcl_matriz[1][8]	 += $agosto;
                  $rcl_matriz[1][9]	 += $setembro;
                  $rcl_matriz[1][10] += $outubro;
                  $rcl_matriz[1][11] += $novembro;
                  $rcl_matriz[1][12] += $dezembro;
                  
                }
                
              }
              
            }
            
          }
          
        }
       
        if ($flag_anterior == false) {  // Exercicio Atual
          for ($y=1; $y <= $bimestre; $y++) {
             $total += $rcl_matriz[0][$y] - ($rcl_matriz[1][$y]); 
          }
        } else {                       // Exercicio Anterior
          for($y=$bimestre; $y <= 12; $y++) {
            $total += $rcl_matriz[0][$y] - ($rcl_matriz[1][$y]); 
          }
        }

        if ($matriz == true) {
                                      // Arrecadacao - Deducoes  
          $rcl_matriz2['janeiro']	  = $rcl_matriz[0][1]  - ($rcl_matriz[1][1]);
  				$rcl_matriz2['fevereiro']	= $rcl_matriz[0][2]  - ($rcl_matriz[1][2]);
	  			$rcl_matriz2['marco']			= $rcl_matriz[0][3]  - ($rcl_matriz[1][3]);
  				$rcl_matriz2['abril']			= $rcl_matriz[0][4]  - ($rcl_matriz[1][4]);
  				$rcl_matriz2['maio']			= $rcl_matriz[0][5]  - ($rcl_matriz[1][5]);
  				$rcl_matriz2['junho']			= $rcl_matriz[0][6]  - ($rcl_matriz[1][6]);
  				$rcl_matriz2['julho']			= $rcl_matriz[0][7]  - ($rcl_matriz[1][7]);
  				$rcl_matriz2['agosto']		= $rcl_matriz[0][8]  - ($rcl_matriz[1][8]);
  				$rcl_matriz2['setembro']	= $rcl_matriz[0][9]  - ($rcl_matriz[1][9]);
  				$rcl_matriz2['outubro']		= $rcl_matriz[0][10] - ($rcl_matriz[1][10]);
	  			$rcl_matriz2['novembro']	= $rcl_matriz[0][11] - ($rcl_matriz[1][11]);
		  		$rcl_matriz2['dezembro']	= $rcl_matriz[0][12] - ($rcl_matriz[1][12]);

          return $rcl_matriz2;
        }
        
        return $total;
        
      }
     

//
// Funcao para verificar se um estrutura (ou parte dele) esta num grupo
//
function db_conplano_grupo($anousu=null, $estrut="", $grupo=0) {
  if ($anousu == "" || $anousu == null) {
    $anousu = db_getsession("DB_anousu");
  }
  
  $sql_result = "select fc_conplano_grupo($anousu, '$estrut', $grupo) as retorno";
  $result     = pg_query($sql_result);
  $numrows    = pg_numrows($result);
  if ($numrows != 0) {
    $retorno = pg_result($result,0,0);
    if ($retorno == 't') {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}
//funcao para montar a fonte e nota explicativa dos relatorios legais.
function notasExplicativas ($oPdf, $iCodRel, $sPeriodo,$iTam){

  if (!class_exists("cl_orcparamrelnota")){
     require_once("classes/db_orcparamrelnota_classe.php");
  }
  if (!class_exists("db_utils")){
     require_once("libs/db_utils.php");
  }
  
  $clorcparamrelnota = new cl_orcparamrelnota();
  $rsNotas           = $clorcparamrelnota->sql_record($clorcparamrelnota->sql_query($iCodRel,db_getsession("DB_anousu"),db_getsession("DB_instit")
                                                   ,$sPeriodo,"o42_nota, o42_fonte"));

  if ($clorcparamrelnota->numrows > 0 ){
       $oNotas = db_utils::fieldsMemory($rsNotas,0);
  } 
  $oPdf->setfont('arial','',8);
  if (isset($oNotas->o42_fonte) && trim($oNotas->o42_fonte) != "") {

    $oPdf->cell($iTam,3,"Fonte:",0,1,"L",0);
    $oPdf->multicell($iTam,3,$oNotas->o42_fonte,0,"J");
  } else {
    $oPdf->cell($iTam,5,'Fonte: Contabilidade',"",1,"L",0);
  }
   if (isset($oNotas->o42_nota) && trim($oNotas->o42_nota) != ""){

      $oPdf->ln(2);
      $oPdf->cell($iTam,5,"NOTAS EXPLICATIVAS:",0,1,"L",0);
      $oPdf->ln(2);
      $oPdf->multicell($iTam,3,$oNotas->o42_nota,0,"J");
      
  }
  $oPdf->setfont('arial','',6);
}



function db_varPatrimoniaisRpps($anousu,$dataini,$datafin,$iInstit) {

  $aVariacoesAtivo        = array();
  $aVariacoesPassivo      = array();
  $aVariacoesExtraAtivo   = array();
  $aVariacoesExtraPassivo = array();

  (float)$aVariacoesAtivo['ReceitasCorrentes']                  = 0;
  (float)$aVariacoesAtivo['ReceitasCapital']                    = 0;
  (float)$aVariacoesAtivo['IntraOrcamentarias']                 = 0;
  (float)$aVariacoesAtivo['TransferenciasFinanceirasRecebidas'] = 0;
  (float)$aVariacoesAtivo['IncorporacaoAtivos']                 = 0;
  (float)$aVariacoesAtivo['DesincorporacaoPassivos']            = 0;

  (float)$aVariacoesPassivo['DespesasCorrentes']     = 0;
  (float)$aVariacoesPassivo['DespesasCapital']       = 0;
  (float)$aVariacoesPassivo['IntraOrcamentarias']    = 0;
  (float)$aVariacoesPassivo['DesincorporacaoAtivos'] = 0;
  (float)$aVariacoesPassivo['IncorporacaoPassivos']  = 0;

  (float)$aVariacoesExtraAtivo['TransferenciasFinanceirasRecebidas'] = 0;
  (float)$aVariacoesExtraAtivo['MovimentoFundosDebito']              = 0;
  (float)$aVariacoesExtraAtivo['IncorporacaoAtivos']                 = 0;
  (float)$aVariacoesExtraAtivo['DesincorporacaoPassivos']            = 0;
  (float)$aVariacoesExtraAtivo['AjustesBensValoresCreditos']         = 0;
  (float)$aVariacoesExtraAtivo['AjustesExerciciosAnteriores']        = 0;

  (float)$aVariacoesExtraPassivo['TransferenciasFinanceirasConcedidas'] = 0;
  (float)$aVariacoesExtraPassivo['MovimentoFundosCredito']              = 0;
  (float)$aVariacoesExtraPassivo['DesincorporacaoAtivos']               = 0;
  (float)$aVariacoesExtraPassivo['AjustesBensValoresCreditos']          = 0;
  (float)$aVariacoesExtraPassivo['IncorporacaoPassivos']                = 0;

  //
  // Balancete de Receita (db_receitasaldo)
  //
  $sSqlFiltro = ' o70_instit = '.$iInstit;
  $rsReceitaSaldo = db_receitasaldo(3,1,3,true,$sSqlFiltro,$anousu,$dataini,$datafin);
  $iNumrowsReceita = pg_num_rows($rsReceitaSaldo);
  // db_criatabela($rsReceitaSaldo);exit;

  for ($i = 0; $i < $iNumrowsReceita; $i++) {

    $oReceitaSaldo = db_utils::fieldsMemory($rsReceitaSaldo,$i);     

    switch (substr($oReceitaSaldo->o57_fonte,0,4)) {
      case '4100':
        $aVariacoesAtivo['ReceitasCorrentes'] += (float)$oReceitaSaldo->saldo_arrecadado_acumulado;
        break;
      case '4200':
        $aVariacoesAtivo['ReceitasCapital'] += (float)$oReceitaSaldo->saldo_arrecadado_acumulado;
        break;
      case '4700':
        $aVariacoesAtivo['IntraOrcamentarias'] += (float)$oReceitaSaldo->saldo_arrecadado_acumulado;
        break;
      case '4800':
        $aVariacoesAtivo['IntraOrcamentarias'] += (float)$oReceitaSaldo->saldo_arrecadado_acumulado;
        break;
    }

  }

  //
  // Balancete de Despesa (db_dotacaosaldo)
  //
  $sCondicaoDotacao     = 'w.o58_instit = '.$iInstit;
  $rsDotacaoSaldo       = db_dotacaosaldo(7,3,4,true,$sCondicaoDotacao,$anousu,$dataini,$datafin);
  $iNumRowsDotacaoSaldo = pg_num_rows($rsDotacaoSaldo);
  //db_criatabela($rsDotacaoSaldo); exit;

  for($i = 0; $i < $iNumRowsDotacaoSaldo; $i++) {
    $oDotacaoSaldo = db_utils::fieldsMemory($rsDotacaoSaldo,$i);  

    if (substr($oDotacaoSaldo->o58_elemento,0,2) == '33' && substr($oDotacaoSaldo->o58_elemento,3,2) != '91' ) {
      $aVariacoesPassivo['DespesasCorrentes'] +=  (float)$oDotacaoSaldo->liquidado_acumulado;
    }
    if (substr($oDotacaoSaldo->o58_elemento,0,2) == '34' && substr($oDotacaoSaldo->o58_elemento,3,2) != '91' ) {
      $aVariacoesPassivo['DespesasCapital'] +=  (float)$oDotacaoSaldo->liquidado_acumulado;
    }
    if ( ( substr($oDotacaoSaldo->o58_elemento,0,2) == '33' || substr($oDotacaoSaldo->o58_elemento,0,2) == '34') && substr($oDotacaoSaldo->o58_elemento,3,2) == '91' ) {
      $aVariacoesPassivo['IntraOrcamentarias'] +=  (float)$oDotacaoSaldo->liquidado_acumulado;
    }

  }

  //
  // Balancete de verificacao (db_planocontassaldo_matriz)
  //
  $sCondicaoConta     = ' c61_instit = '.$iInstit;
  $rsContaSaldo       = db_planocontassaldo_matriz($anousu,$dataini,$datafin,false,$sCondicaoConta);
  $iNumRowsContaSaldo = pg_num_rows($rsContaSaldo);
  //db_criatabela($rsContaSaldo); exit;

  for($i = 0; $i < $iNumRowsContaSaldo; $i++) {

    $oContaSaldo = db_utils::fieldsMemory($rsContaSaldo,$i);  

    if (substr($oContaSaldo->estrutural,0,15) == '612000000000000' ) {
      $aVariacoesAtivo['TransferenciasFinanceirasRecebidas'] += (float)$oContaSaldo->saldo_final;
    }

    switch (substr($oContaSaldo->estrutural,0,15)) {
      case '613100000000000': 
        $aVariacoesAtivo['IncorporacaoAtivos'] += (float)$oContaSaldo->saldo_final;
        break;
      case '613300000000000':
        $aVariacoesAtivo['DesincorporacaoPassivos'] += (float)$oContaSaldo->saldo_final;
        break;
      case '622200000000000':
        $aVariacoesExtraAtivo['TransferenciasFinanceirasRecebidas'] += (float)$oContaSaldo->saldo_final;
        break;
      case '622300000000000':
        $aVariacoesExtraAtivo['MovimentoFundosDebito'] += (float)$oContaSaldo->saldo_final;
        break;
      case '623100000000000':
        $aVariacoesExtraAtivo['IncorporacaoAtivos'] += (float)$oContaSaldo->saldo_final;
        break;
      case '623200000000000':
        $aVariacoesExtraAtivo['AjustesBensValoresCreditos'] += (float)$oContaSaldo->saldo_final;
        break;
      case '623300000000000':
        $aVariacoesExtraAtivo['DesincorporacaoPassivos'] += (float)$oContaSaldo->saldo_final;
        break;
      case '623800000000000':
        $aVariacoesExtraAtivo['AjustesExerciciosAnteriores'] += (float)$oContaSaldo->saldo_final;
        break;
      case '513100000000000':
        $aVariacoesPassivo['DesincorporacaoAtivos'] += (float)$oContaSaldo->saldo_final;
        break;
      case '513300000000000':
        $aVariacoesPassivo['IncorporacaoPassivos'] += (float)$oContaSaldo->saldo_final;
        break;
      case '522200000000000':
        $aVariacoesExtraPassivo['TransferenciasFinanceirasConcedidas'] += (float)$oContaSaldo->saldo_final;
        break;
      case '522300000000000':
        $aVariacoesExtraPassivo['MovimentoFundosCredito'] += (float)$oContaSaldo->saldo_final;
        break;
      case '523100000000000':
        $aVariacoesExtraPassivo['DesincorporacaoAtivos'] += (float)$oContaSaldo->saldo_final;
        break;
      case '523200000000000':
        $aVariacoesExtraPassivo['AjustesBensValoresCreditos'] += (float)$oContaSaldo->saldo_final;
        break;
      case '523300000000000':
        $aVariacoesExtraPassivo['IncorporacaoPassivos'] += (float)$oContaSaldo->saldo_final;
        break;

    }

  }

  //------------------------------------------------------------------------------------------------------------------------------------------------//

  //
  // Totalizadores 
  //

  $aRetorno = array();

  // Receitas
  $aRetorno['Ativo']['ReceitasCorrentes']  = $aVariacoesAtivo['ReceitasCorrentes'];
  $aRetorno['Ativo']['ReceitasCapital']    = $aVariacoesAtivo['ReceitasCapital'];
  $aRetorno['Ativo']['IntraOrcamentarias'] = $aVariacoesAtivo['IntraOrcamentarias'];
  $aRetorno['Ativo']['Receitas']           = ( $aVariacoesAtivo['ReceitasCorrentes'] + $aVariacoesAtivo['ReceitasCapital'] + $aVariacoesAtivo['IntraOrcamentarias'] );

  // Interferencias Ativas
  $aRetorno['Ativo']['TransferenciasFinanceirasRecebidas'] = $aVariacoesAtivo['TransferenciasFinanceirasRecebidas'];
  $aRetorno['Ativo']['InterferenciasAtivas']               = $aVariacoesAtivo['TransferenciasFinanceirasRecebidas'];
  
  // Mutacoes Ativas
  $aRetorno['Ativo']['IncorporacaoAtivos']      = $aVariacoesAtivo['IncorporacaoAtivos'];
  $aRetorno['Ativo']['DesincorporacaoPassivos'] = $aVariacoesAtivo['DesincorporacaoPassivos']; 
  $aRetorno['Ativo']['MutacoesAtivas']          = ( $aVariacoesAtivo['IncorporacaoAtivos'] + $aVariacoesAtivo['DesincorporacaoPassivos'] );

  //Total Orcamentaria Ativa
  $aRetorno['Ativo']['TotalOrcamentariaAtiva']  = ( $aRetorno['Ativo']['Receitas'] + $aRetorno['Ativo']['InterferenciasAtivas'] + $aRetorno['Ativo']['MutacoesAtivas'] );
  
  $aRetorno['Passivo']['DespesasCorrentes']     = $aVariacoesPassivo['DespesasCorrentes'];
  $aRetorno['Passivo']['DespesasCapital']       = $aVariacoesPassivo['DespesasCapital'];
  $aRetorno['Passivo']['IntraOrcamentarias']    = $aVariacoesPassivo['IntraOrcamentarias'] ;
  $aRetorno['Passivo']['Despesas']              = ( $aVariacoesPassivo['DespesasCorrentes'] + $aVariacoesPassivo['DespesasCapital'] + $aVariacoesPassivo['IntraOrcamentarias'] );

  $aRetorno['Passivo']['DesincorporacaoAtivos'] = $aVariacoesPassivo['DesincorporacaoAtivos'];
  $aRetorno['Passivo']['IncorporacaoPassivos']  = $aVariacoesPassivo['IncorporacaoPassivos'];
  $aRetorno['Passivo']['MutacoesPassivas']      = ( $aVariacoesPassivo['DesincorporacaoAtivos'] + $aVariacoesPassivo['IncorporacaoPassivos'] );
   
  // Total Orcamentaria Passiva 
  $aRetorno['Passivo']['TotalOrcamentariaPassiva'] = ( $aRetorno['Passivo']['Despesas'] + $aRetorno['Passivo']['MutacoesPassivas'] );
  
  //
  //Extra-Orcamentario
  //

  // Interferencias Ativas
  $aRetorno['AtivoExtra']['TransferenciasFinanceirasRecebidas'] = $aVariacoesExtraAtivo['TransferenciasFinanceirasRecebidas'];
  $aRetorno['AtivoExtra']['MovimentoFundosDebito']              = $aVariacoesExtraAtivo['MovimentoFundosDebito'];
  $aRetorno['AtivoExtra']['InterferenciasAtivas']               = ( $aVariacoesExtraAtivo['TransferenciasFinanceirasRecebidas'] + $aVariacoesExtraAtivo['MovimentoFundosDebito'] );
  // Acrescimos Patrimoniais
  $aRetorno['AtivoExtra']['IncorporacaoAtivos']                 = $aVariacoesExtraAtivo['IncorporacaoAtivos']; 
  $aRetorno['AtivoExtra']['AjustesBensValoresCreditos']         = $aVariacoesExtraAtivo['AjustesBensValoresCreditos'] ;
  $aRetorno['AtivoExtra']['DesincorporacaoPassivos']            = $aVariacoesExtraAtivo['DesincorporacaoPassivos'] ;
  $aRetorno['AtivoExtra']['AjustesExerciciosAnteriores']        = $aVariacoesExtraAtivo['AjustesExerciciosAnteriores'];
  $aRetorno['AtivoExtra']['AcrescimosPatrimoniais']             = (   $aVariacoesExtraAtivo['IncorporacaoAtivos'] 
                                                                    + $aVariacoesExtraAtivo['AjustesBensValoresCreditos'] 
                                                                    + $aVariacoesExtraAtivo['DesincorporacaoPassivos'] ) ;
  // Total Ativo Extra-Orcamentario  
  $aRetorno['AtivoExtra']['TotalAtivoExtra'] = ( $aRetorno['AtivoExtra']['InterferenciasAtivas'] + $aRetorno['AtivoExtra']['AcrescimosPatrimoniais'] );
  
  // Interferencias Passivas
  $aRetorno['PassivoExtra']['TransferenciasFinanceirasConcedidas'] = $aVariacoesExtraPassivo['TransferenciasFinanceirasConcedidas'];
  $aRetorno['PassivoExtra']['MovimentoFundosCredito']              = $aVariacoesExtraPassivo['MovimentoFundosCredito'];
  $aRetorno['PassivoExtra']['InterferenciasPassivas']              = (  $aVariacoesExtraPassivo['TransferenciasFinanceirasConcedidas'] 
                                                                      + $aVariacoesExtraPassivo['MovimentoFundosCredito'] );
  //Descrescimos Patrimoniais
  $aRetorno['PassivoExtra']['DesincorporacaoAtivos']       = $aVariacoesExtraPassivo['DesincorporacaoAtivos'];
  $aRetorno['PassivoExtra']['AjustesBensValoresCreditos']  = $aVariacoesExtraPassivo['AjustesBensValoresCreditos'];
  $aRetorno['PassivoExtra']['IncorporacaoPassivos']        = $aVariacoesExtraPassivo['IncorporacaoPassivos'];
  $aRetorno['PassivoExtra']['DecrescimosPatrimoniais']     = (  $aVariacoesExtraPassivo['DesincorporacaoAtivos'] 
                                                              + $aVariacoesExtraPassivo['AjustesBensValoresCreditos'] 
                                                              + $aVariacoesExtraPassivo['IncorporacaoPassivos'] );
  // Total Passivo Extra-Orcamentario 
  $aRetorno['PassivoExtra']['TotalPassivoExtra'] = ( $aRetorno['PassivoExtra']['InterferenciasPassivas'] + $aRetorno['PassivoExtra']['DecrescimosPatrimoniais'] );
   
  $aRetorno['TotaisAtivo']['Soma']   = ( $aRetorno['Ativo']  ['TotalOrcamentariaAtiva']   + $aRetorno['AtivoExtra']  ['TotalAtivoExtra'] );
  $aRetorno['TotaisPassivo']['Soma'] = ( $aRetorno['Passivo']['TotalOrcamentariaPassiva'] + $aRetorno['PassivoExtra']['TotalPassivoExtra'] );
  
  $nSomaAtivo   = $aRetorno['TotaisAtivo']['Soma'];
  $nSomaPassivo = $aRetorno['TotaisPassivo']['Soma'];

  if ( ($nSomaAtivo - $nSomaPassivo ) < 0 ) {
    $nDeficitPatrimonial = abs( $nSomaAtivo - $nSomaPassivo );
    (float)$nTotalAtivo  = ( $nSomaAtivo + abs($nSomaAtivo - $nSomaPassivo ) );
  } else {
    $nDeficitPatrimonial = '-';
    (float)$nTotalAtivo  = ( $nSomaAtivo ) ;
  }
  
  if ( ($nSomaAtivo - $nSomaPassivo ) > 0 ) {
    $nSuperavitPatrimonial = ( $nSomaAtivo - $nSomaPassivo ) ;
    (float)$nTotalPassivo  = ( $nSomaPassivo + abs($nSomaAtivo - $nSomaPassivo ) );
  } else {
    $nSuperavitPatrimonial = '-';
    (float)$nTotalPassivo  = ( $nSomaPassivo ); 
  }
  
  $aRetorno['TotaisAtivo']['TotalAtivo']         = $nTotalAtivo;
  $aRetorno['TotaisAtivo']['DeficitPatrimonial'] = $nDeficitPatrimonial;

  $aRetorno['TotaisPassivo']['TotalPassivo']         = $nTotalPassivo;
  $aRetorno['TotaisPassivo']['SuperavitPatrimonial'] = $nSuperavitPatrimonial;

  return $aRetorno;

}

?>