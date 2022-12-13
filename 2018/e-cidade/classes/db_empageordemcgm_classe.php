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

//MODULO: Empenho
//CLASSE DA ENTIDADE empageordemcgm
class cl_empageordemcgm { 
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
   var $e94_sequencial = 0; 
   var $e94_empageordem = 0; 
   var $e94_numcgm = 0; 
   var $e94_historico = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e94_sequencial = int4 = Código Sequencial 
                 e94_empageordem = int4 = Código da Ordem 
                 e94_numcgm = int4 = Código do CGM 
                 e94_historico = text = Histórico 
                 ";
   //funcao construtor da classe 
   function cl_empageordemcgm() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empageordemcgm"); 
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
       $this->e94_sequencial = ($this->e94_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e94_sequencial"]:$this->e94_sequencial);
       $this->e94_empageordem = ($this->e94_empageordem == ""?@$GLOBALS["HTTP_POST_VARS"]["e94_empageordem"]:$this->e94_empageordem);
       $this->e94_numcgm = ($this->e94_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["e94_numcgm"]:$this->e94_numcgm);
       $this->e94_historico = ($this->e94_historico == ""?@$GLOBALS["HTTP_POST_VARS"]["e94_historico"]:$this->e94_historico);
     }else{
       $this->e94_sequencial = ($this->e94_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e94_sequencial"]:$this->e94_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e94_sequencial){ 
      $this->atualizacampos();
     if($this->e94_empageordem == null ){ 
       $this->erro_sql = " Campo Código da Ordem nao Informado.";
       $this->erro_campo = "e94_empageordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e94_numcgm == null ){ 
       $this->erro_sql = " Campo Código do CGM nao Informado.";
       $this->erro_campo = "e94_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e94_sequencial == "" || $e94_sequencial == null ){
       $result = db_query("select nextval('empageordemcgm_e94_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empageordemcgm_e94_sequencial_seq do campo: e94_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e94_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from empageordemcgm_e94_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e94_sequencial)){
         $this->erro_sql = " Campo e94_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e94_sequencial = $e94_sequencial; 
       }
     }
     if(($this->e94_sequencial == null) || ($this->e94_sequencial == "") ){ 
       $this->erro_sql = " Campo e94_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empageordemcgm(
                                       e94_sequencial 
                                      ,e94_empageordem 
                                      ,e94_numcgm 
                                      ,e94_historico 
                       )
                values (
                                $this->e94_sequencial 
                               ,$this->e94_empageordem 
                               ,$this->e94_numcgm 
                               ,'$this->e94_historico' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ordem de pagamento por Credor ($this->e94_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ordem de pagamento por Credor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ordem de pagamento por Credor ($this->e94_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e94_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e94_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15672,'$this->e94_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2748,15672,'','".AddSlashes(pg_result($resaco,0,'e94_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2748,15673,'','".AddSlashes(pg_result($resaco,0,'e94_empageordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2748,15674,'','".AddSlashes(pg_result($resaco,0,'e94_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2748,15675,'','".AddSlashes(pg_result($resaco,0,'e94_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e94_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update empageordemcgm set ";
     $virgula = "";
     if(trim($this->e94_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e94_sequencial"])){ 
       $sql  .= $virgula." e94_sequencial = $this->e94_sequencial ";
       $virgula = ",";
       if(trim($this->e94_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "e94_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e94_empageordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e94_empageordem"])){ 
       $sql  .= $virgula." e94_empageordem = $this->e94_empageordem ";
       $virgula = ",";
       if(trim($this->e94_empageordem) == null ){ 
         $this->erro_sql = " Campo Código da Ordem nao Informado.";
         $this->erro_campo = "e94_empageordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e94_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e94_numcgm"])){ 
       $sql  .= $virgula." e94_numcgm = $this->e94_numcgm ";
       $virgula = ",";
       if(trim($this->e94_numcgm) == null ){ 
         $this->erro_sql = " Campo Código do CGM nao Informado.";
         $this->erro_campo = "e94_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e94_historico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e94_historico"])){ 
       $sql  .= $virgula." e94_historico = '$this->e94_historico' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($e94_sequencial!=null){
       $sql .= " e94_sequencial = $this->e94_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e94_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15672,'$this->e94_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e94_sequencial"]) || $this->e94_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2748,15672,'".AddSlashes(pg_result($resaco,$conresaco,'e94_sequencial'))."','$this->e94_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e94_empageordem"]) || $this->e94_empageordem != "")
           $resac = db_query("insert into db_acount values($acount,2748,15673,'".AddSlashes(pg_result($resaco,$conresaco,'e94_empageordem'))."','$this->e94_empageordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e94_numcgm"]) || $this->e94_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,2748,15674,'".AddSlashes(pg_result($resaco,$conresaco,'e94_numcgm'))."','$this->e94_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e94_historico"]) || $this->e94_historico != "")
           $resac = db_query("insert into db_acount values($acount,2748,15675,'".AddSlashes(pg_result($resaco,$conresaco,'e94_historico'))."','$this->e94_historico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ordem de pagamento por Credor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e94_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ordem de pagamento por Credor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e94_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e94_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e94_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e94_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15672,'$e94_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2748,15672,'','".AddSlashes(pg_result($resaco,$iresaco,'e94_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2748,15673,'','".AddSlashes(pg_result($resaco,$iresaco,'e94_empageordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2748,15674,'','".AddSlashes(pg_result($resaco,$iresaco,'e94_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2748,15675,'','".AddSlashes(pg_result($resaco,$iresaco,'e94_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empageordemcgm
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e94_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e94_sequencial = $e94_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ordem de pagamento por Credor nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e94_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ordem de pagamento por Credor nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e94_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e94_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:empageordemcgm";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $e94_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empageordemcgm ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empageordemcgm.e94_numcgm";
     $sql .= "      inner join empageordem  on  empageordem.e42_sequencial = empageordemcgm.e94_empageordem";
     $sql2 = "";
     if($dbwhere==""){
       if($e94_sequencial!=null ){
         $sql2 .= " where empageordemcgm.e94_sequencial = $e94_sequencial "; 
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
   function sql_query_file ( $e94_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empageordemcgm ";
     $sql2 = "";
     if($dbwhere==""){
       if($e94_sequencial!=null ){
         $sql2 .= " where empageordemcgm.e94_sequencial = $e94_sequencial "; 
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