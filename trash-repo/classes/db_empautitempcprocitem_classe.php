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
//CLASSE DA ENTIDADE empautitempcprocitem
class cl_empautitempcprocitem { 
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
   var $e73_sequencial = 0; 
   var $e73_autori = 0; 
   var $e73_sequen = 0; 
   var $e73_pcprocitem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e73_sequencial = int4 = Código Sequencial 
                 e73_autori = int4 = Autorização 
                 e73_sequen = int4 = Código do Item da Autorização 
                 e73_pcprocitem = int4 = Código do Item do Processo 
                 ";
   //funcao construtor da classe 
   function cl_empautitempcprocitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empautitempcprocitem"); 
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
       $this->e73_sequencial = ($this->e73_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e73_sequencial"]:$this->e73_sequencial);
       $this->e73_autori = ($this->e73_autori == ""?@$GLOBALS["HTTP_POST_VARS"]["e73_autori"]:$this->e73_autori);
       $this->e73_sequen = ($this->e73_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["e73_sequen"]:$this->e73_sequen);
       $this->e73_pcprocitem = ($this->e73_pcprocitem == ""?@$GLOBALS["HTTP_POST_VARS"]["e73_pcprocitem"]:$this->e73_pcprocitem);
     }else{
       $this->e73_sequencial = ($this->e73_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e73_sequencial"]:$this->e73_sequencial);
       $this->e73_sequen = ($this->e73_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["e73_sequen"]:$this->e73_sequen);
     }
   }
   // funcao para inclusao
   function incluir ($e73_sequencial){ 
      $this->atualizacampos();
     if($this->e73_autori == null ){ 
       $this->erro_sql = " Campo Autorização nao Informado.";
       $this->erro_campo = "e73_autori";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e73_pcprocitem == null ){ 
       $this->erro_sql = " Campo Código do Item do Processo nao Informado.";
       $this->erro_campo = "e73_pcprocitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e73_sequencial == "" || $e73_sequencial == null ){
       $result = db_query("select nextval('empautitempcprocitem_e73_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empautitempcprocitem_e73_sequencial_seq do campo: e73_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e73_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from empautitempcprocitem_e73_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e73_sequencial)){
         $this->erro_sql = " Campo e73_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e73_sequencial = $e73_sequencial; 
       }
     }
     if(($this->e73_sequencial == null) || ($this->e73_sequencial == "") ){ 
       $this->erro_sql = " Campo e73_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empautitempcprocitem(
                                       e73_sequencial 
                                      ,e73_autori 
                                      ,e73_sequen 
                                      ,e73_pcprocitem 
                       )
                values (
                                $this->e73_sequencial 
                               ,$this->e73_autori 
                               ,$this->e73_sequen 
                               ,$this->e73_pcprocitem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "empautitempcprocitem ($this->e73_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "empautitempcprocitem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "empautitempcprocitem ($this->e73_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e73_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e73_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17337,'$this->e73_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3068,17337,'','".AddSlashes(pg_result($resaco,0,'e73_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3068,17340,'','".AddSlashes(pg_result($resaco,0,'e73_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3068,17341,'','".AddSlashes(pg_result($resaco,0,'e73_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3068,17342,'','".AddSlashes(pg_result($resaco,0,'e73_pcprocitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e73_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update empautitempcprocitem set ";
     $virgula = "";
     if(trim($this->e73_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e73_sequencial"])){ 
       $sql  .= $virgula." e73_sequencial = $this->e73_sequencial ";
       $virgula = ",";
       if(trim($this->e73_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "e73_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e73_autori)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e73_autori"])){ 
       $sql  .= $virgula." e73_autori = $this->e73_autori ";
       $virgula = ",";
       if(trim($this->e73_autori) == null ){ 
         $this->erro_sql = " Campo Autorização nao Informado.";
         $this->erro_campo = "e73_autori";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e73_sequen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e73_sequen"])){ 
       $sql  .= $virgula." e73_sequen = $this->e73_sequen ";
       $virgula = ",";
       if(trim($this->e73_sequen) == null ){ 
         $this->erro_sql = " Campo Código do Item da Autorização nao Informado.";
         $this->erro_campo = "e73_sequen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e73_pcprocitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e73_pcprocitem"])){ 
       $sql  .= $virgula." e73_pcprocitem = $this->e73_pcprocitem ";
       $virgula = ",";
       if(trim($this->e73_pcprocitem) == null ){ 
         $this->erro_sql = " Campo Código do Item do Processo nao Informado.";
         $this->erro_campo = "e73_pcprocitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e73_sequencial!=null){
       $sql .= " e73_sequencial = $this->e73_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e73_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17337,'$this->e73_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e73_sequencial"]) || $this->e73_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3068,17337,'".AddSlashes(pg_result($resaco,$conresaco,'e73_sequencial'))."','$this->e73_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e73_autori"]) || $this->e73_autori != "")
           $resac = db_query("insert into db_acount values($acount,3068,17340,'".AddSlashes(pg_result($resaco,$conresaco,'e73_autori'))."','$this->e73_autori',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e73_sequen"]) || $this->e73_sequen != "")
           $resac = db_query("insert into db_acount values($acount,3068,17341,'".AddSlashes(pg_result($resaco,$conresaco,'e73_sequen'))."','$this->e73_sequen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e73_pcprocitem"]) || $this->e73_pcprocitem != "")
           $resac = db_query("insert into db_acount values($acount,3068,17342,'".AddSlashes(pg_result($resaco,$conresaco,'e73_pcprocitem'))."','$this->e73_pcprocitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "empautitempcprocitem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e73_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "empautitempcprocitem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e73_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e73_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17337,'$e73_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3068,17337,'','".AddSlashes(pg_result($resaco,$iresaco,'e73_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3068,17340,'','".AddSlashes(pg_result($resaco,$iresaco,'e73_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3068,17341,'','".AddSlashes(pg_result($resaco,$iresaco,'e73_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3068,17342,'','".AddSlashes(pg_result($resaco,$iresaco,'e73_pcprocitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empautitempcprocitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e73_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e73_sequencial = $e73_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "empautitempcprocitem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e73_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "empautitempcprocitem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e73_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:empautitempcprocitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $e73_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empautitempcprocitem ";
     $sql .= "      inner join empautitem  on  empautitem.e55_autori = empautitempcprocitem.e73_autori and  empautitem.e55_sequen = empautitempcprocitem.e73_sequen";
     $sql .= "      inner join pcprocitem  on  pcprocitem.pc81_codprocitem = empautitempcprocitem.e73_pcprocitem";
     $sql .= "      inner join empautoriza  on  empautoriza.e54_autori = empautitem.e55_autori";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = empautitem.e55_item";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      inner join pcproc  as a on   a.pc80_codproc = pcprocitem.pc81_codproc";
     $sql2 = "";
     if($dbwhere==""){
       if($e73_sequencial!=null ){
         $sql2 .= " where empautitempcprocitem.e73_sequencial = $e73_sequencial "; 
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
   function sql_query_file ( $e73_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empautitempcprocitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($e73_sequencial!=null ){
         $sql2 .= " where empautitempcprocitem.e73_sequencial = $e73_sequencial "; 
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