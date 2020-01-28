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

//MODULO: caixa
//CLASSE DA ENTIDADE cancdebitosprocconcarpeculiar
class cl_cancdebitosprocconcarpeculiar { 
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
   var $k74_sequencial = 0; 
   var $k74_cancdebitosproc = 0; 
   var $k74_concarpeculiar = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k74_sequencial = int4 = Código 
                 k74_cancdebitosproc = int4 = Código cancdebitosproc 
                 k74_concarpeculiar = varchar(100) = Código peculiar 
                 ";
   //funcao construtor da classe 
   function cl_cancdebitosprocconcarpeculiar() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cancdebitosprocconcarpeculiar"); 
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
       $this->k74_sequencial = ($this->k74_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k74_sequencial"]:$this->k74_sequencial);
       $this->k74_cancdebitosproc = ($this->k74_cancdebitosproc == ""?@$GLOBALS["HTTP_POST_VARS"]["k74_cancdebitosproc"]:$this->k74_cancdebitosproc);
       $this->k74_concarpeculiar = ($this->k74_concarpeculiar == ""?@$GLOBALS["HTTP_POST_VARS"]["k74_concarpeculiar"]:$this->k74_concarpeculiar);
     }else{
       $this->k74_sequencial = ($this->k74_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k74_sequencial"]:$this->k74_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k74_sequencial){ 
      $this->atualizacampos();
     if($this->k74_cancdebitosproc == null ){ 
       $this->erro_sql = " Campo Código cancdebitosproc nao Informado.";
       $this->erro_campo = "k74_cancdebitosproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k74_concarpeculiar == null ){ 
       $this->erro_sql = " Campo Código peculiar nao Informado.";
       $this->erro_campo = "k74_concarpeculiar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k74_sequencial == "" || $k74_sequencial == null ){
       $result = db_query("select nextval('cancdebitosprocconcarpeculiar_k74_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cancdebitosprocconcarpeculiar_k74_sequencial_seq do campo: k74_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k74_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cancdebitosprocconcarpeculiar_k74_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k74_sequencial)){
         $this->erro_sql = " Campo k74_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k74_sequencial = $k74_sequencial; 
       }
     }
     if(($this->k74_sequencial == null) || ($this->k74_sequencial == "") ){ 
       $this->erro_sql = " Campo k74_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cancdebitosprocconcarpeculiar(
                                       k74_sequencial 
                                      ,k74_cancdebitosproc 
                                      ,k74_concarpeculiar 
                       )
                values (
                                $this->k74_sequencial 
                               ,$this->k74_cancdebitosproc 
                               ,'$this->k74_concarpeculiar' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cancdebitosprocconcarpeculiar ($this->k74_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cancdebitosprocconcarpeculiar já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cancdebitosprocconcarpeculiar ($this->k74_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k74_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k74_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11746,'$this->k74_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2025,11746,'','".AddSlashes(pg_result($resaco,0,'k74_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2025,11747,'','".AddSlashes(pg_result($resaco,0,'k74_cancdebitosproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2025,11748,'','".AddSlashes(pg_result($resaco,0,'k74_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k74_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cancdebitosprocconcarpeculiar set ";
     $virgula = "";
     if(trim($this->k74_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k74_sequencial"])){ 
       $sql  .= $virgula." k74_sequencial = $this->k74_sequencial ";
       $virgula = ",";
       if(trim($this->k74_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "k74_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k74_cancdebitosproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k74_cancdebitosproc"])){ 
       $sql  .= $virgula." k74_cancdebitosproc = $this->k74_cancdebitosproc ";
       $virgula = ",";
       if(trim($this->k74_cancdebitosproc) == null ){ 
         $this->erro_sql = " Campo Código cancdebitosproc nao Informado.";
         $this->erro_campo = "k74_cancdebitosproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k74_concarpeculiar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k74_concarpeculiar"])){ 
       $sql  .= $virgula." k74_concarpeculiar = '$this->k74_concarpeculiar' ";
       $virgula = ",";
       if(trim($this->k74_concarpeculiar) == null ){ 
         $this->erro_sql = " Campo Código peculiar nao Informado.";
         $this->erro_campo = "k74_concarpeculiar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k74_sequencial!=null){
       $sql .= " k74_sequencial = $this->k74_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k74_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11746,'$this->k74_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k74_sequencial"]) || $this->k74_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2025,11746,'".AddSlashes(pg_result($resaco,$conresaco,'k74_sequencial'))."','$this->k74_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k74_cancdebitosproc"]) || $this->k74_cancdebitosproc != "")
           $resac = db_query("insert into db_acount values($acount,2025,11747,'".AddSlashes(pg_result($resaco,$conresaco,'k74_cancdebitosproc'))."','$this->k74_cancdebitosproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k74_concarpeculiar"]) || $this->k74_concarpeculiar != "")
           $resac = db_query("insert into db_acount values($acount,2025,11748,'".AddSlashes(pg_result($resaco,$conresaco,'k74_concarpeculiar'))."','$this->k74_concarpeculiar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cancdebitosprocconcarpeculiar nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k74_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cancdebitosprocconcarpeculiar nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k74_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k74_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k74_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k74_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11746,'$k74_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2025,11746,'','".AddSlashes(pg_result($resaco,$iresaco,'k74_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2025,11747,'','".AddSlashes(pg_result($resaco,$iresaco,'k74_cancdebitosproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2025,11748,'','".AddSlashes(pg_result($resaco,$iresaco,'k74_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cancdebitosprocconcarpeculiar
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k74_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k74_sequencial = $k74_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cancdebitosprocconcarpeculiar nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k74_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cancdebitosprocconcarpeculiar nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k74_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k74_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cancdebitosprocconcarpeculiar";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k74_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cancdebitosprocconcarpeculiar ";
     $sql .= "      inner join cancdebitosproc  on  cancdebitosproc.k23_codigo = cancdebitosprocconcarpeculiar.k74_cancdebitosproc";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = cancdebitosprocconcarpeculiar.k74_concarpeculiar";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = cancdebitosproc.k23_usuario";
     $sql .= "      inner join cancdebitostipo  on  cancdebitostipo.k73_sequencial = cancdebitosproc.k23_cancdebitostipo";
     $sql2 = "";
     if($dbwhere==""){
       if($k74_sequencial!=null ){
         $sql2 .= " where cancdebitosprocconcarpeculiar.k74_sequencial = $k74_sequencial "; 
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
   function sql_query_file ( $k74_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cancdebitosprocconcarpeculiar ";
     $sql2 = "";
     if($dbwhere==""){
       if($k74_sequencial!=null ){
         $sql2 .= " where cancdebitosprocconcarpeculiar.k74_sequencial = $k74_sequencial "; 
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