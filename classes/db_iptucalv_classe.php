<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: cadastro
//CLASSE DA ENTIDADE iptucalv
class cl_iptucalv { 
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
   var $j21_anousu = 0; 
   var $j21_matric = 0; 
   var $j21_receit = 0; 
   var $j21_valor = 0; 
   var $j21_quant = 0; 
   var $j21_codhis = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j21_anousu = int4 = Exercicio 
                 j21_matric = int4 = Matricula 
                 j21_receit = int4 = Receita 
                 j21_valor = float8 = Valor 
                 j21_quant = float8 = Quantidade 
                 j21_codhis = int8 = Código do histórico 
                 ";
   //funcao construtor da classe 
   function cl_iptucalv() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptucalv"); 
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
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->j21_anousu = ($this->j21_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j21_anousu"]:$this->j21_anousu);
       $this->j21_matric = ($this->j21_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j21_matric"]:$this->j21_matric);
       $this->j21_receit = ($this->j21_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["j21_receit"]:$this->j21_receit);
       $this->j21_valor = ($this->j21_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["j21_valor"]:$this->j21_valor);
       $this->j21_quant = ($this->j21_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["j21_quant"]:$this->j21_quant);
       $this->j21_codhis = ($this->j21_codhis == ""?@$GLOBALS["HTTP_POST_VARS"]["j21_codhis"]:$this->j21_codhis);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->j21_anousu == null ){ 
       $this->erro_sql = " Campo Exercicio nao Informado.";
       $this->erro_campo = "j21_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j21_matric == null ){ 
       $this->erro_sql = " Campo Matricula nao Informado.";
       $this->erro_campo = "j21_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j21_receit == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "j21_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j21_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "j21_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j21_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "j21_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j21_codhis == null ){ 
       $this->erro_sql = " Campo Código do histórico nao Informado.";
       $this->erro_campo = "j21_codhis";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptucalv(
                                       j21_anousu 
                                      ,j21_matric 
                                      ,j21_receit 
                                      ,j21_valor 
                                      ,j21_quant 
                                      ,j21_codhis 
                       )
                values (
                                $this->j21_anousu 
                               ,$this->j21_matric 
                               ,$this->j21_receit 
                               ,$this->j21_valor 
                               ,$this->j21_quant 
                               ,$this->j21_codhis 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update iptucalv set ";
     $virgula = "";
     if(trim($this->j21_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j21_anousu"])){ 
       $sql  .= $virgula." j21_anousu = $this->j21_anousu ";
       $virgula = ",";
       if(trim($this->j21_anousu) == null ){ 
         $this->erro_sql = " Campo Exercicio nao Informado.";
         $this->erro_campo = "j21_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j21_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j21_matric"])){ 
       $sql  .= $virgula." j21_matric = $this->j21_matric ";
       $virgula = ",";
       if(trim($this->j21_matric) == null ){ 
         $this->erro_sql = " Campo Matricula nao Informado.";
         $this->erro_campo = "j21_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j21_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j21_receit"])){ 
       $sql  .= $virgula." j21_receit = $this->j21_receit ";
       $virgula = ",";
       if(trim($this->j21_receit) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "j21_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j21_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j21_valor"])){ 
       $sql  .= $virgula." j21_valor = $this->j21_valor ";
       $virgula = ",";
       if(trim($this->j21_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "j21_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j21_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j21_quant"])){ 
       $sql  .= $virgula." j21_quant = $this->j21_quant ";
       $virgula = ",";
       if(trim($this->j21_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "j21_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j21_codhis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j21_codhis"])){ 
       $sql  .= $virgula." j21_codhis = $this->j21_codhis ";
       $virgula = ",";
       if(trim($this->j21_codhis) == null ){ 
         $this->erro_sql = " Campo Código do histórico nao Informado.";
         $this->erro_campo = "j21_codhis";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from iptucalv
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:iptucalv";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $oid,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from iptucalv ";
     $sql2 = "";
     if($dbwhere==""){
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $oid,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from iptucalv ";
     $sql2 = "";
     if($dbwhere==""){
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_hist ( $oid,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from iptucalv ";
     $sql2 = "      inner join iptucalh on iptucalv.j21_codhis = iptucalh.j17_codhis";
     $sql2 = "";
     if($dbwhere==""){
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
  
  function sql_queryValoresCalculoIptu($iAnoCalculo) {
    
    $sSql  = "select ano_calculo,                                                                             ";
    $sSql .= "       codigo_receita,                                                                          ";
    $sSql .= "       descricao_receita,                                                                       ";
    $sSql .= "       coalesce(round(sum(valor_calculado), 2)  , 0.00) as valor_calculado,                     ";
    $sSql .= "       coalesce(round(sum(valor_isento)   , 2)  , 0.00) as valor_isento,                        ";
    $sSql .= "       coalesce(round(sum(valor_cancelado), 2)  , 0.00) as valor_cancelado,                     ";
    $sSql .= "       coalesce(round(sum(valor_pago)     , 2)  , 0.00) as valor_pago,                          ";
    $sSql .= "       coalesce(round(sum(valor_a_pagar)  , 2)  , 0.00) as valor_a_pagar,                       ";
    $sSql .= "       coalesce(round(sum(valor_importado)  , 2), 0.00) as valor_importado,                     ";
    $sSql .= "       (select count(j21_matric)                                                                ";
    $sSql .= "         from iptucalv                                                                          ";
    $sSql .= "        where j21_anousu = ano_calculo                                                          ";
    $sSql .= "          and j21_receit = codigo_receita                                                       ";
    $sSql .= "          and j21_valor > 0) as quantidade                                                      ";
    $sSql .= "  from (                                                                                        ";
    $sSql .= "       select tabrec.k02_codigo                                           as codigo_receita,    ";
    $sSql .= "              iptucalv.j21_anousu                                         as ano_calculo,       ";
    $sSql .= "              tabrec.k02_descr                                            as descricao_receita, ";
    $sSql .= "              sum(case when j21_valor > 0 then j21_valor      else 0 end) as valor_calculado,   ";
    $sSql .= "              sum(case when j21_valor < 0 then j21_valor * -1 else 0 end) as valor_isento,      ";
    $sSql .= "              (select sum(arrecant.k00_valor)                                                   ";
    $sSql .= "                 from arrecant                                                                  ";
    $sSql .= "                inner join cancdebitosreg on k21_numpre = k00_numpre                            ";
    $sSql .= "                                         and k21_numpar = k00_numpar                            ";
    $sSql .= "                                         and k21_receit = k00_receit                            ";
    $sSql .= "                inner join cancdebitosprocreg on k24_cancdebitosreg = k21_sequencia             ";
    $sSql .= "                where arrecant.k00_numpre = iptunump.j20_numpre                                 ";
    $sSql .= "                  and arrecant.k00_receit = tabrec.k02_codigo)            as valor_cancelado,   ";
    $sSql .= "              (select sum(arrecant.k00_valor)                                                   ";
    $sSql .= "                 from arrecant                                                                  ";
    $sSql .= "                where exists (select 1                                                          ";
    $sSql .= "                                from arrepaga                                                   ";
    $sSql .= "                               where                                                            ";
    $sSql .= "                                     k00_numpre = arrecant.k00_numpre                           ";
    $sSql .= "                                 and k00_numpar = arrecant.k00_numpar                           ";
    $sSql .= "                                 and k00_receit = arrecant.k00_receit)                          ";
    $sSql .= "                 and arrecant.k00_numpre = iptunump.j20_numpre                                  ";
    $sSql .= "                 and arrecant.k00_receit = tabrec.k02_codigo)            as valor_pago,         ";
    $sSql .= "              (select sum(arrecad.k00_valor)                                                    ";
    $sSql .= "                 from arrecad                                                                   ";
    $sSql .= "                where arrecad.k00_numpre = iptunump.j20_numpre                                  ";
    $sSql .= "                  and arrecad.k00_receit = tabrec.k02_codigo)             as valor_a_pagar,     ";
    $sSql .= "              (select sum(k00_valor)                                                            ";                                                                                            
    $sSql .= "                 from arreold                                                                   ";                                        
    $sSql .= "                where k00_numpre in (select distinct k10_numpre                                 ";                                        
    $sSql .= "                                       from divold                                              ";                                        
    $sSql .= "                                      where k10_numpre = iptunump.j20_numpre)                   ";
    $sSql .= "                  and k00_receit =  tabrec.k02_codigo                                           ";                          
    $sSql .= "                group by k00_receit) as valor_importado                                         ";                                                                 
    $sSql .= "         from tabrec                                                                            ";
    $sSql .= "              inner join iptucalv  on iptucalv.j21_receit = tabrec.k02_codigo                   ";
    $sSql .= "              left  join iptunump  on iptucalv.j21_matric = iptunump.j20_matric                 ";
    $sSql .= "                                  and iptucalv.j21_anousu = iptunump.j20_anousu                 ";
    $sSql .= "        where j21_anousu = {$iAnoCalculo}                                                       ";
    $sSql .= "        group by                                                                                ";
    $sSql .= "              tabrec.k02_codigo,                                                                ";
    $sSql .= "              iptucalv.j21_anousu,                                                              ";
    $sSql .= "              tabrec.k02_descr,                                                                 ";
    $sSql .= "              iptunump.j20_numpre ) as x                                                        ";
    $sSql .= " group by                                                                                       ";
    $sSql .= "       ano_calculo,                                                                             ";
    $sSql .= "       codigo_receita,                                                                          ";
    $sSql .= "       descricao_receita                                                                        ";
    $sSql .= " order by codigo_receita                                                                        ";
    
    return $sSql;
  
  }
}
?>