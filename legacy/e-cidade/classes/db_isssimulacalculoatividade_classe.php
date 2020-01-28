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

//MODULO: issqn
//CLASSE DA ENTIDADE isssimulacalculoatividade
class cl_isssimulacalculoatividade { 
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
   var $q131_sequencial = 0; 
   var $q131_atividade = 0; 
   var $q131_issimulacalculo = 0; 
   var $q131_principal = 'f'; 
   var $q131_quantidade = 0; 
   var $q131_seq = 0; 
   var $q131_permanente = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q131_sequencial = int4 = sequencial 
                 q131_atividade = int4 = Atividade 
                 q131_issimulacalculo = int4 = Simulação calculo ISSQN 
                 q131_principal = bool = Principal 
                 q131_quantidade = int4 = Quantidade 
                 q131_seq = int4 = Sequência 
                 q131_permanente = bool = Permanente 
                 ";
   //funcao construtor da classe 
   function cl_isssimulacalculoatividade() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("isssimulacalculoatividade"); 
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
       $this->q131_sequencial = ($this->q131_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q131_sequencial"]:$this->q131_sequencial);
       $this->q131_atividade = ($this->q131_atividade == ""?@$GLOBALS["HTTP_POST_VARS"]["q131_atividade"]:$this->q131_atividade);
       $this->q131_issimulacalculo = ($this->q131_issimulacalculo == ""?@$GLOBALS["HTTP_POST_VARS"]["q131_issimulacalculo"]:$this->q131_issimulacalculo);
       $this->q131_principal = ($this->q131_principal == "f"?@$GLOBALS["HTTP_POST_VARS"]["q131_principal"]:$this->q131_principal);
       $this->q131_quantidade = ($this->q131_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["q131_quantidade"]:$this->q131_quantidade);
       $this->q131_seq = ($this->q131_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["q131_seq"]:$this->q131_seq);
       $this->q131_permanente = ($this->q131_permanente == "f"?@$GLOBALS["HTTP_POST_VARS"]["q131_permanente"]:$this->q131_permanente);
     }else{
       $this->q131_sequencial = ($this->q131_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q131_sequencial"]:$this->q131_sequencial);
       $this->q131_seq = ($this->q131_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["q131_seq"]:$this->q131_seq);
     }
   }
   // funcao para inclusao
   function incluir ($q131_sequencial){ 
      $this->atualizacampos();
     if($this->q131_atividade == null ){ 
       $this->erro_sql = " Campo Atividade nao Informado.";
       $this->erro_campo = "q131_atividade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q131_issimulacalculo == null ){ 
       $this->erro_sql = " Campo Simulação calculo ISSQN nao Informado.";
       $this->erro_campo = "q131_issimulacalculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q131_principal == null ){ 
       $this->erro_sql = " Campo Principal nao Informado.";
       $this->erro_campo = "q131_principal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q131_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "q131_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q131_permanente == null ){ 
       $this->erro_sql = " Campo Permanente nao Informado.";
       $this->erro_campo = "q131_permanente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q131_sequencial == "" || $q131_sequencial == null ){
       $result = db_query("select nextval('isssimulacalculoatividade_q131_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: isssimulacalculoatividade_q131_sequencial_seq do campo: q131_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q131_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from isssimulacalculoatividade_q131_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q131_sequencial)){
         $this->erro_sql = " Campo q131_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q131_sequencial = $q131_sequencial; 
       }
     }
     if(($this->q131_sequencial == null) || ($this->q131_sequencial == "") ){ 
       $this->erro_sql = " Campo q131_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into isssimulacalculoatividade(
                                       q131_sequencial 
                                      ,q131_atividade 
                                      ,q131_issimulacalculo 
                                      ,q131_principal 
                                      ,q131_quantidade 
                                      ,q131_seq 
                                      ,q131_permanente 
                       )
                values (
                                $this->q131_sequencial 
                               ,$this->q131_atividade 
                               ,$this->q131_issimulacalculo 
                               ,'$this->q131_principal' 
                               ,$this->q131_quantidade 
                               ,$this->q131_seq 
                               ,'$this->q131_permanente' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Simulação calculo atividades ISSQN ($this->q131_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Simulação calculo atividades ISSQN já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Simulação calculo atividades ISSQN ($this->q131_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q131_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ($q131_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update isssimulacalculoatividade set ";
     $virgula = "";
     if(trim($this->q131_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q131_sequencial"])){ 
       $sql  .= $virgula." q131_sequencial = $this->q131_sequencial ";
       $virgula = ",";
       if(trim($this->q131_sequencial) == null ){ 
         $this->erro_sql = " Campo sequencial nao Informado.";
         $this->erro_campo = "q131_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q131_atividade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q131_atividade"])){ 
       $sql  .= $virgula." q131_atividade = $this->q131_atividade ";
       $virgula = ",";
       if(trim($this->q131_atividade) == null ){ 
         $this->erro_sql = " Campo Atividade nao Informado.";
         $this->erro_campo = "q131_atividade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q131_issimulacalculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q131_issimulacalculo"])){ 
       $sql  .= $virgula." q131_issimulacalculo = $this->q131_issimulacalculo ";
       $virgula = ",";
       if(trim($this->q131_issimulacalculo) == null ){ 
         $this->erro_sql = " Campo Simulação calculo ISSQN nao Informado.";
         $this->erro_campo = "q131_issimulacalculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q131_principal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q131_principal"])){ 
       $sql  .= $virgula." q131_principal = '$this->q131_principal' ";
       $virgula = ",";
       if(trim($this->q131_principal) == null ){ 
         $this->erro_sql = " Campo Principal nao Informado.";
         $this->erro_campo = "q131_principal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q131_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q131_quantidade"])){ 
       $sql  .= $virgula." q131_quantidade = $this->q131_quantidade ";
       $virgula = ",";
       if(trim($this->q131_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "q131_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q131_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q131_seq"])){ 
       $sql  .= $virgula." q131_seq = $this->q131_seq ";
       $virgula = ",";
       if(trim($this->q131_seq) == null ){ 
         $this->erro_sql = " Campo Sequência nao Informado.";
         $this->erro_campo = "q131_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q131_permanente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q131_permanente"])){ 
       $sql  .= $virgula." q131_permanente = '$this->q131_permanente' ";
       $virgula = ",";
       if(trim($this->q131_permanente) == null ){ 
         $this->erro_sql = " Campo Permanente nao Informado.";
         $this->erro_campo = "q131_permanente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q131_sequencial!=null){
       $sql .= " q131_sequencial = $this->q131_sequencial";
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Simulação calculo atividades ISSQN nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q131_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Simulação calculo atividades ISSQN nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q131_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q131_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q131_sequencial=null,$dbwhere=null) { 
     $sql = " delete from isssimulacalculoatividade
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q131_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q131_sequencial = $q131_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Simulação calculo atividades ISSQN nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q131_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Simulação calculo atividades ISSQN nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q131_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q131_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:isssimulacalculoatividade";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q131_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from isssimulacalculoatividade ";
     $sql .= "      inner join ativid  on  ativid.q03_ativ = isssimulacalculoatividade.q131_atividade";
     $sql .= "      inner join isssimulacalculo  on  isssimulacalculo.q130_sequencial = isssimulacalculoatividade.q131_issimulacalculo";
     $sql .= "      inner join bairro  on  bairro.j13_codi = isssimulacalculo.q130_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = isssimulacalculo.q130_logradouro";
     $sql .= "      left  join ruascep on  ruascep.j29_codigo = ruas.j14_codigo";
     $sql .= "      left  join cadescrito  on  cadescrito.q86_numcgm = isssimulacalculo.q130_cadescrito";
     $sql .= "      inner join zonas  on  zonas.j50_zona = isssimulacalculo.q130_zona";
     $sql2 = "";
     if($dbwhere==""){
       if($q131_sequencial!=null ){
         $sql2 .= " where isssimulacalculoatividade.q131_sequencial = $q131_sequencial "; 
       } 
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
   // funcao do sql 
   function sql_query_file ( $q131_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from isssimulacalculoatividade ";
     $sql2 = "";
     if($dbwhere==""){
       if($q131_sequencial!=null ){
         $sql2 .= " where isssimulacalculoatividade.q131_sequencial = $q131_sequencial "; 
       } 
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
}
?>