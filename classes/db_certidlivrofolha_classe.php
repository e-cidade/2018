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

//MODULO: divida
//CLASSE DA ENTIDADE certidlivrofolha
class cl_certidlivrofolha { 
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
   var $v26_sequencial = 0; 
   var $v26_certidlivro = 0; 
   var $v26_certid = 0; 
   var $v26_numerofolha = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v26_sequencial = int4 = Código Sequencial 
                 v26_certidlivro = int4 = Código do livro 
                 v26_certid = int4 = Código da CDA 
                 v26_numerofolha = int4 = Número da Folha 
                 ";
   //funcao construtor da classe 
   function cl_certidlivrofolha() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("certidlivrofolha"); 
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
       $this->v26_sequencial = ($this->v26_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v26_sequencial"]:$this->v26_sequencial);
       $this->v26_certidlivro = ($this->v26_certidlivro == ""?@$GLOBALS["HTTP_POST_VARS"]["v26_certidlivro"]:$this->v26_certidlivro);
       $this->v26_certid = ($this->v26_certid == ""?@$GLOBALS["HTTP_POST_VARS"]["v26_certid"]:$this->v26_certid);
       $this->v26_numerofolha = ($this->v26_numerofolha == ""?@$GLOBALS["HTTP_POST_VARS"]["v26_numerofolha"]:$this->v26_numerofolha);
     }else{
       $this->v26_sequencial = ($this->v26_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v26_sequencial"]:$this->v26_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v26_sequencial){ 
      $this->atualizacampos();
     if($this->v26_certidlivro == null ){ 
       $this->erro_sql = " Campo Código do livro nao Informado.";
       $this->erro_campo = "v26_certidlivro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v26_certid == null ){ 
       $this->erro_sql = " Campo Código da CDA nao Informado.";
       $this->erro_campo = "v26_certid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v26_numerofolha == null ){ 
       $this->erro_sql = " Campo Número da Folha nao Informado.";
       $this->erro_campo = "v26_numerofolha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v26_sequencial == "" || $v26_sequencial == null ){
       $result = db_query("select nextval('certidlivrofolha_v26_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: certidlivrofolha_v26_sequencial_seq do campo: v26_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v26_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from certidlivrofolha_v26_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v26_sequencial)){
         $this->erro_sql = " Campo v26_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v26_sequencial = $v26_sequencial; 
       }
     }
     if(($this->v26_sequencial == null) || ($this->v26_sequencial == "") ){ 
       $this->erro_sql = " Campo v26_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into certidlivrofolha(
                                       v26_sequencial 
                                      ,v26_certidlivro 
                                      ,v26_certid 
                                      ,v26_numerofolha 
                       )
                values (
                                $this->v26_sequencial 
                               ,$this->v26_certidlivro 
                               ,$this->v26_certid 
                               ,$this->v26_numerofolha 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "certidlivrofolha ($this->v26_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "certidlivrofolha já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "certidlivrofolha ($this->v26_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v26_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v26_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14839,'$this->v26_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2612,14839,'','".AddSlashes(pg_result($resaco,0,'v26_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2612,14840,'','".AddSlashes(pg_result($resaco,0,'v26_certidlivro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2612,14841,'','".AddSlashes(pg_result($resaco,0,'v26_certid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2612,14842,'','".AddSlashes(pg_result($resaco,0,'v26_numerofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v26_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update certidlivrofolha set ";
     $virgula = "";
     if(trim($this->v26_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v26_sequencial"])){ 
       $sql  .= $virgula." v26_sequencial = $this->v26_sequencial ";
       $virgula = ",";
       if(trim($this->v26_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "v26_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v26_certidlivro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v26_certidlivro"])){ 
       $sql  .= $virgula." v26_certidlivro = $this->v26_certidlivro ";
       $virgula = ",";
       if(trim($this->v26_certidlivro) == null ){ 
         $this->erro_sql = " Campo Código do livro nao Informado.";
         $this->erro_campo = "v26_certidlivro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v26_certid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v26_certid"])){ 
       $sql  .= $virgula." v26_certid = $this->v26_certid ";
       $virgula = ",";
       if(trim($this->v26_certid) == null ){ 
         $this->erro_sql = " Campo Código da CDA nao Informado.";
         $this->erro_campo = "v26_certid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v26_numerofolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v26_numerofolha"])){ 
       $sql  .= $virgula." v26_numerofolha = $this->v26_numerofolha ";
       $virgula = ",";
       if(trim($this->v26_numerofolha) == null ){ 
         $this->erro_sql = " Campo Número da Folha nao Informado.";
         $this->erro_campo = "v26_numerofolha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v26_sequencial!=null){
       $sql .= " v26_sequencial = $this->v26_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v26_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14839,'$this->v26_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v26_sequencial"]) || $this->v26_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2612,14839,'".AddSlashes(pg_result($resaco,$conresaco,'v26_sequencial'))."','$this->v26_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v26_certidlivro"]) || $this->v26_certidlivro != "")
           $resac = db_query("insert into db_acount values($acount,2612,14840,'".AddSlashes(pg_result($resaco,$conresaco,'v26_certidlivro'))."','$this->v26_certidlivro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v26_certid"]) || $this->v26_certid != "")
           $resac = db_query("insert into db_acount values($acount,2612,14841,'".AddSlashes(pg_result($resaco,$conresaco,'v26_certid'))."','$this->v26_certid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v26_numerofolha"]) || $this->v26_numerofolha != "")
           $resac = db_query("insert into db_acount values($acount,2612,14842,'".AddSlashes(pg_result($resaco,$conresaco,'v26_numerofolha'))."','$this->v26_numerofolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "certidlivrofolha nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v26_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "certidlivrofolha nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v26_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v26_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v26_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v26_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14839,'$v26_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2612,14839,'','".AddSlashes(pg_result($resaco,$iresaco,'v26_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2612,14840,'','".AddSlashes(pg_result($resaco,$iresaco,'v26_certidlivro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2612,14841,'','".AddSlashes(pg_result($resaco,$iresaco,'v26_certid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2612,14842,'','".AddSlashes(pg_result($resaco,$iresaco,'v26_numerofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from certidlivrofolha
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v26_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v26_sequencial = $v26_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "certidlivrofolha nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v26_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "certidlivrofolha nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v26_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v26_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:certidlivrofolha";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $v26_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from certidlivrofolha ";
     $sql .= "      inner join certidlivro  on  certidlivro. = certidlivrofolha.v26_certidlivro";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = certidlivro.v25_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($v26_sequencial!=null ){
         $sql2 .= " where certidlivrofolha.v26_sequencial = $v26_sequencial "; 
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
   function sql_query_file ( $v26_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from certidlivrofolha ";
     $sql2 = "";
     if($dbwhere==""){
       if($v26_sequencial!=null ){
         $sql2 .= " where certidlivrofolha.v26_sequencial = $v26_sequencial "; 
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
  
  function sql_query_certidao( $v13_certid=null,$campos="*",$ordem=null,$dbwhere="") {
     
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
    $sql .= " from certid ";
    $sql .= "       left join certdiv          on certid.v13_certid  = certdiv.v14_certid ";
    $sql .= "       left join certter          on certid.v13_certid  = certter.v14_certid ";
    $sql .= "       left join divida           on certdiv.v14_coddiv = divida.v01_coddiv ";
    $sql .= "       left join termo            on certter.v14_parcel = termo.v07_parcel ";
    $sql .= "       left join cgm cgmtermo     on termo.v07_numcgm   = cgmtermo.z01_numcgm ";
    $sql .= "       left join cgm cgmdivida    on divida.v01_numcgm  = cgmdivida.z01_numcgm ";
    $sql .= "       left join certidlivrofolha on certid.v13_certid  = certidlivrofolha.v26_certid ";
    $sql .= "       left join certidlivro      on v25_sequencial     = certidlivrofolha.v26_certidlivro ";
    $sql2 = "";
    if($dbwhere==""){
      if($v13_certid!=null ){
        $sql2 .= " where certid.v13_certid = $v13_certid "; 
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