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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcparametro
class cl_consistencia { 
  // cria variaveis de erro 
  var $rotulo     = null; 
  var $query_sql  = null; 
  var $numrows    = 0; 
  var $numrows_incluir = 0; 
  var $numrows_alterar = 0; 
  var $numrows_excluir = 0; 
  var $erro_status= null; 
  var $erro_sql   = null; 
  var $erro_banco = null;  
  var $erro_msg   = null;  
  var $erro_campo = null;  
  var $pagina_retorno = null; 
  // cria variaveis do arquivo 
  var $o50_anousu = 0; 
  var $o50_coddot = 0; 
  var $o50_subelem = 'f'; 
  var $o50_programa = 0; 
  var $o50_estrutdespesa = null; 
  var $o50_estrutelemento = null; 
  var $o50_estrutreceita = null; 
  var $o50_tipoproj = null; 
  // cria propriedade com as variaveis do arquivo 
  var $campos = "
  o50_anousu = int4 = Exercício 
  o50_coddot = int4 = Último Código 
  o50_subelem = bool = Usa Sub-Elemento 
  o50_programa = int4 = Ultimo código de programas 
  o50_estrutdespesa = varchar(50) = Estrutural Despesa 
  o50_estrutelemento = varchar(50) = Estrutural Elemento 
  o50_estrutreceita = varchar(50) = Estrutural Receita 
  o50_tipoproj = char(1) = Modelo Prj/Decreto 
  ";
  //funcao construtor da classe 
  function cl_orcparametro() { 
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("orcparametro"); 
    $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
  }
  //funcao erro 
  function erro($mostra,$retorna) { 
    if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
      echo "<script>alert(\"".$this->erro_msg."\");</script>";
      if($retorna==true){
        echo "<script>location.href='".$this->pagina_retorno."'</script>";
      }
    }
  }
  function processa($instit, $data_ini, $data_fim, $tribinst, $subelemento,$item) { 

    if ($item == "docempenho") {
      
			$clconhistdoc		  = new cl_conhistdoc;
			$clcontranslan	  = new cl_contranslan;

			global $c53_coddoc, $c53_descr, $c75_data, $c70_codlan;

      $result_histdoc = $clconhistdoc->sql_record($clconhistdoc->sql_query_file(null, "c53_coddoc, c53_descr", "c53_coddoc"));
      
			echo "<br>";

			$errogeral = false;

      for ($histdoc=0; $histdoc < $clconhistdoc->numrows; $histdoc++) {
				db_fieldsmemory($result_histdoc, $histdoc);
        
				//// pegar sempre a quantidade da instituicao prefeitura???
				$result_contranslan = $clcontranslan->sql_record($clcontranslan->sql_query_lr(null, "distinct c46_seqtranslan", null, " c45_coddoc = $c53_coddoc and c45_instit = 1 and c45_anousu = " . db_getsession("DB_anousu")));
				if ($clcontranslan->numrows > 0) {

					$sql = "
					select distinct c75_data, c70_codlan from (
					select * from (
					select c70_codlan,count(c69_codlan)
					from conlancam
					inner join conlancamval on c70_codlan = c69_codlan
					inner join conlancamdoc on c70_codlan = c71_codlan
					where c71_coddoc in ($c53_coddoc) and c70_anousu = " . db_getsession("DB_anousu") . "
					group by c70_codlan
					) as x
					inner join conlancamemp on c75_codlan = c70_codlan
					inner join empempenho   on c75_numemp = e60_numemp
					left join conlancamele on c67_codlan = c70_codlan
					where count <> " . $clcontranslan->numrows . ") as y";
					$result = pg_exec($sql) or die($sql);
					if (pg_numrows($result) > 0) {
						echo "doc $c53_coddoc - $c53_descr ... comparando com " . $clcontranslan->numrows . " lanc...";
						echo "erro - quantidade: " . pg_numrows($result) . "<br>";
						for ($i=0; $i < pg_numrows($result); $i++) {
							db_fieldsmemory($result, $i);
							echo $c75_data . " - " . $c70_codlan . "<br>";
						}
						$errogeral = true;
					//} else {
					//	echo "ok<br>";
					}

					flush();

				}
        
      }
      
      return $errogeral;
      
		}

  }
}
?>