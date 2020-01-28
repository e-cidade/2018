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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhdevolucaofolharhemprubrica
class cl_rhdevolucaofolharhemprubrica { 
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
   var $rh87_sequencial = 0; 
   var $rh87_devolucaofolha = 0; 
   var $rh87_rhempenhofolharubrica = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh87_sequencial = int4 = Sequencial 
                 rh87_devolucaofolha = int4 = Devoluções da Folha 
                 rh87_rhempenhofolharubrica = int4 = Rubricas 
                 ";
   //funcao construtor da classe 
   function cl_rhdevolucaofolharhemprubrica() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhdevolucaofolharhemprubrica"); 
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
       $this->rh87_sequencial = ($this->rh87_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh87_sequencial"]:$this->rh87_sequencial);
       $this->rh87_devolucaofolha = ($this->rh87_devolucaofolha == ""?@$GLOBALS["HTTP_POST_VARS"]["rh87_devolucaofolha"]:$this->rh87_devolucaofolha);
       $this->rh87_rhempenhofolharubrica = ($this->rh87_rhempenhofolharubrica == ""?@$GLOBALS["HTTP_POST_VARS"]["rh87_rhempenhofolharubrica"]:$this->rh87_rhempenhofolharubrica);
     }else{
       $this->rh87_sequencial = ($this->rh87_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh87_sequencial"]:$this->rh87_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh87_sequencial){ 
      $this->atualizacampos();
     if($this->rh87_devolucaofolha == null ){ 
       $this->erro_sql = " Campo Devoluções da Folha nao Informado.";
       $this->erro_campo = "rh87_devolucaofolha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh87_rhempenhofolharubrica == null ){ 
       $this->erro_sql = " Campo Rubricas nao Informado.";
       $this->erro_campo = "rh87_rhempenhofolharubrica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh87_sequencial == "" || $rh87_sequencial == null ){
       $result = db_query("select nextval('rhdevolucaofolharhemprubrica_rh87_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhdevolucaofolharhemprubrica_rh87_sequencial_seq do campo: rh87_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh87_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhdevolucaofolharhemprubrica_rh87_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh87_sequencial)){
         $this->erro_sql = " Campo rh87_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh87_sequencial = $rh87_sequencial; 
       }
     }
     if(($this->rh87_sequencial == null) || ($this->rh87_sequencial == "") ){ 
       $this->erro_sql = " Campo rh87_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhdevolucaofolharhemprubrica(
                                       rh87_sequencial 
                                      ,rh87_devolucaofolha 
                                      ,rh87_rhempenhofolharubrica 
                       )
                values (
                                $this->rh87_sequencial 
                               ,$this->rh87_devolucaofolha 
                               ,$this->rh87_rhempenhofolharubrica 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ligaçao das rubricas com as devoluções da folha ($this->rh87_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ligaçao das rubricas com as devoluções da folha já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ligaçao das rubricas com as devoluções da folha ($this->rh87_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh87_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh87_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15066,'$this->rh87_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2649,15066,'','".AddSlashes(pg_result($resaco,0,'rh87_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2649,15067,'','".AddSlashes(pg_result($resaco,0,'rh87_devolucaofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2649,15068,'','".AddSlashes(pg_result($resaco,0,'rh87_rhempenhofolharubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh87_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhdevolucaofolharhemprubrica set ";
     $virgula = "";
     if(trim($this->rh87_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh87_sequencial"])){ 
       $sql  .= $virgula." rh87_sequencial = $this->rh87_sequencial ";
       $virgula = ",";
       if(trim($this->rh87_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh87_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh87_devolucaofolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh87_devolucaofolha"])){ 
       $sql  .= $virgula." rh87_devolucaofolha = $this->rh87_devolucaofolha ";
       $virgula = ",";
       if(trim($this->rh87_devolucaofolha) == null ){ 
         $this->erro_sql = " Campo Devoluções da Folha nao Informado.";
         $this->erro_campo = "rh87_devolucaofolha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh87_rhempenhofolharubrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh87_rhempenhofolharubrica"])){ 
       $sql  .= $virgula." rh87_rhempenhofolharubrica = $this->rh87_rhempenhofolharubrica ";
       $virgula = ",";
       if(trim($this->rh87_rhempenhofolharubrica) == null ){ 
         $this->erro_sql = " Campo Rubricas nao Informado.";
         $this->erro_campo = "rh87_rhempenhofolharubrica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh87_sequencial!=null){
       $sql .= " rh87_sequencial = $this->rh87_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh87_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15066,'$this->rh87_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh87_sequencial"]) || $this->rh87_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2649,15066,'".AddSlashes(pg_result($resaco,$conresaco,'rh87_sequencial'))."','$this->rh87_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh87_devolucaofolha"]) || $this->rh87_devolucaofolha != "")
           $resac = db_query("insert into db_acount values($acount,2649,15067,'".AddSlashes(pg_result($resaco,$conresaco,'rh87_devolucaofolha'))."','$this->rh87_devolucaofolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh87_rhempenhofolharubrica"]) || $this->rh87_rhempenhofolharubrica != "")
           $resac = db_query("insert into db_acount values($acount,2649,15068,'".AddSlashes(pg_result($resaco,$conresaco,'rh87_rhempenhofolharubrica'))."','$this->rh87_rhempenhofolharubrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligaçao das rubricas com as devoluções da folha nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh87_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligaçao das rubricas com as devoluções da folha nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh87_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh87_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh87_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh87_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15066,'$rh87_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2649,15066,'','".AddSlashes(pg_result($resaco,$iresaco,'rh87_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2649,15067,'','".AddSlashes(pg_result($resaco,$iresaco,'rh87_devolucaofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2649,15068,'','".AddSlashes(pg_result($resaco,$iresaco,'rh87_rhempenhofolharubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhdevolucaofolharhemprubrica
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh87_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh87_sequencial = $rh87_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligaçao das rubricas com as devoluções da folha nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh87_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligaçao das rubricas com as devoluções da folha nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh87_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh87_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhdevolucaofolharhemprubrica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh87_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhdevolucaofolharhemprubrica ";
     $sql .= "      inner join rhempenhofolharubrica  on  rhempenhofolharubrica.rh73_sequencial = rhdevolucaofolharhemprubrica.rh87_rhempenhofolharubrica";
     $sql .= "      inner join rhdevolucaofolha  on  rhdevolucaofolha.rh69_sequencial = rhdevolucaofolharhemprubrica.rh87_devolucaofolha";
     $sql .= "      inner join db_config  on  db_config.codigo = rhempenhofolharubrica.rh73_instit";
     $sql .= "      inner join rhpessoalmov  on  rhpessoalmov.rh02_seqpes = rhempenhofolharubrica.rh73_seqpes and  rhpessoalmov.rh02_instit = rhempenhofolharubrica.rh73_instit";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = rhempenhofolharubrica.rh73_rubric and  rhrubricas.rh27_instit = rhempenhofolharubrica.rh73_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = rhdevolucaofolha.rh69_recurso";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = rhdevolucaofolha.rh69_concarpeculiar";
     $sql2 = "";
     if($dbwhere==""){
       if($rh87_sequencial!=null ){
         $sql2 .= " where rhdevolucaofolharhemprubrica.rh87_sequencial = $rh87_sequencial "; 
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
   function sql_query_file ( $rh87_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhdevolucaofolharhemprubrica ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh87_sequencial!=null ){
         $sql2 .= " where rhdevolucaofolharhemprubrica.rh87_sequencial = $rh87_sequencial "; 
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