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

//MODULO: Configuracoes
//CLASSE DA ENTIDADE caddocumentoatributovalor
class cl_caddocumentoatributovalor { 
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
   var $db43_sequencial = 0; 
   var $db43_documento = 0; 
   var $db43_caddocumentoatributo = 0; 
   var $db43_valor = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db43_sequencial = int4 = Código Sequencial 
                 db43_documento = int4 = Documento 
                 db43_caddocumentoatributo = int4 = Cadastro Documento Atributo 
                 db43_valor = varchar(100) = Valor 
                 ";
   //funcao construtor da classe 
   function cl_caddocumentoatributovalor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("caddocumentoatributovalor"); 
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
       $this->db43_sequencial = ($this->db43_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db43_sequencial"]:$this->db43_sequencial);
       $this->db43_documento = ($this->db43_documento == ""?@$GLOBALS["HTTP_POST_VARS"]["db43_documento"]:$this->db43_documento);
       $this->db43_caddocumentoatributo = ($this->db43_caddocumentoatributo == ""?@$GLOBALS["HTTP_POST_VARS"]["db43_caddocumentoatributo"]:$this->db43_caddocumentoatributo);
       $this->db43_valor = ($this->db43_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["db43_valor"]:$this->db43_valor);
     }else{
       $this->db43_sequencial = ($this->db43_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db43_sequencial"]:$this->db43_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db43_sequencial){ 
      $this->atualizacampos();
     if($this->db43_documento == null ){ 
       $this->erro_sql = " Campo Documento nao Informado.";
       $this->erro_campo = "db43_documento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db43_caddocumentoatributo == null ){ 
       $this->erro_sql = " Campo Cadastro Documento Atributo nao Informado.";
       $this->erro_campo = "db43_caddocumentoatributo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db43_sequencial == "" || $db43_sequencial == null ){
       $result = db_query("select nextval('caddocumentoatributovalor_db43_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: caddocumentoatributovalor_db43_sequencial_seq do campo: db43_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db43_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from caddocumentoatributovalor_db43_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db43_sequencial)){
         $this->erro_sql = " Campo db43_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db43_sequencial = $db43_sequencial; 
       }
     }
     if(($this->db43_sequencial == null) || ($this->db43_sequencial == "") ){ 
       $this->erro_sql = " Campo db43_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into caddocumentoatributovalor(
                                       db43_sequencial 
                                      ,db43_documento 
                                      ,db43_caddocumentoatributo 
                                      ,db43_valor 
                       )
                values (
                                $this->db43_sequencial 
                               ,$this->db43_documento 
                               ,$this->db43_caddocumentoatributo 
                               ,'$this->db43_valor' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "caddocumentoatributovalor ($this->db43_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "caddocumentoatributovalor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "caddocumentoatributovalor ($this->db43_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db43_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db43_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16609,'$this->db43_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2917,16609,'','".AddSlashes(pg_result($resaco,0,'db43_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2917,16610,'','".AddSlashes(pg_result($resaco,0,'db43_documento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2917,16611,'','".AddSlashes(pg_result($resaco,0,'db43_caddocumentoatributo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2917,16612,'','".AddSlashes(pg_result($resaco,0,'db43_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db43_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update caddocumentoatributovalor set ";
     $virgula = "";
     if(trim($this->db43_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db43_sequencial"])){ 
       $sql  .= $virgula." db43_sequencial = $this->db43_sequencial ";
       $virgula = ",";
       if(trim($this->db43_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "db43_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db43_documento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db43_documento"])){ 
       $sql  .= $virgula." db43_documento = $this->db43_documento ";
       $virgula = ",";
       if(trim($this->db43_documento) == null ){ 
         $this->erro_sql = " Campo Documento nao Informado.";
         $this->erro_campo = "db43_documento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db43_caddocumentoatributo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db43_caddocumentoatributo"])){ 
       $sql  .= $virgula." db43_caddocumentoatributo = $this->db43_caddocumentoatributo ";
       $virgula = ",";
       if(trim($this->db43_caddocumentoatributo) == null ){ 
         $this->erro_sql = " Campo Cadastro Documento Atributo nao Informado.";
         $this->erro_campo = "db43_caddocumentoatributo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db43_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db43_valor"])){ 
       $sql  .= $virgula." db43_valor = '$this->db43_valor' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db43_sequencial!=null){
       $sql .= " db43_sequencial = $this->db43_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db43_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16609,'$this->db43_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db43_sequencial"]) || $this->db43_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2917,16609,'".AddSlashes(pg_result($resaco,$conresaco,'db43_sequencial'))."','$this->db43_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db43_documento"]) || $this->db43_documento != "")
           $resac = db_query("insert into db_acount values($acount,2917,16610,'".AddSlashes(pg_result($resaco,$conresaco,'db43_documento'))."','$this->db43_documento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db43_caddocumentoatributo"]) || $this->db43_caddocumentoatributo != "")
           $resac = db_query("insert into db_acount values($acount,2917,16611,'".AddSlashes(pg_result($resaco,$conresaco,'db43_caddocumentoatributo'))."','$this->db43_caddocumentoatributo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db43_valor"]) || $this->db43_valor != "")
           $resac = db_query("insert into db_acount values($acount,2917,16612,'".AddSlashes(pg_result($resaco,$conresaco,'db43_valor'))."','$this->db43_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "caddocumentoatributovalor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db43_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "caddocumentoatributovalor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db43_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db43_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db43_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db43_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16609,'$db43_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2917,16609,'','".AddSlashes(pg_result($resaco,$iresaco,'db43_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2917,16610,'','".AddSlashes(pg_result($resaco,$iresaco,'db43_documento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2917,16611,'','".AddSlashes(pg_result($resaco,$iresaco,'db43_caddocumentoatributo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2917,16612,'','".AddSlashes(pg_result($resaco,$iresaco,'db43_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from caddocumentoatributovalor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db43_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db43_sequencial = $db43_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "caddocumentoatributovalor nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db43_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "caddocumentoatributovalor nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db43_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db43_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:caddocumentoatributovalor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db43_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from caddocumentoatributovalor ";
     $sql .= "      inner join caddocumentoatributo  on  caddocumentoatributo.db45_sequencial = caddocumentoatributovalor.db43_caddocumentoatributo";
     $sql .= "      inner join documento  on  documento.db58_sequencial = caddocumentoatributovalor.db43_documento";
     $sql .= "      left  join db_syscampo  on  db_syscampo.codcam = caddocumentoatributo.db45_codcam";
     $sql .= "      inner join caddocumento  on  caddocumento.db44_sequencial = caddocumentoatributo.db45_caddocumento";
     $sql2 = "";
     if($dbwhere==""){
       if($db43_sequencial!=null ){
         $sql2 .= " where caddocumentoatributovalor.db43_sequencial = $db43_sequencial "; 
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
   function sql_query_file ( $db43_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from caddocumentoatributovalor ";
     $sql2 = "";
     if($dbwhere==""){
       if($db43_sequencial!=null ){
         $sql2 .= " where caddocumentoatributovalor.db43_sequencial = $db43_sequencial "; 
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