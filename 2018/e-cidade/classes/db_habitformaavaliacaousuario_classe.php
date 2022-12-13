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

//MODULO: Habitacao
//CLASSE DA ENTIDADE habitformaavaliacaousuario
class cl_habitformaavaliacaousuario { 
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
   var $ht08_sequencial = 0; 
   var $ht08_habitformaavaliacao = 0; 
   var $ht08_id_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ht08_sequencial = int4 = Sequencial 
                 ht08_habitformaavaliacao = int4 = Forma de Avalia��o 
                 ht08_id_usuario = int4 = Usu�rio 
                 ";
   //funcao construtor da classe 
   function cl_habitformaavaliacaousuario() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("habitformaavaliacaousuario"); 
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
       $this->ht08_sequencial = ($this->ht08_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht08_sequencial"]:$this->ht08_sequencial);
       $this->ht08_habitformaavaliacao = ($this->ht08_habitformaavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ht08_habitformaavaliacao"]:$this->ht08_habitformaavaliacao);
       $this->ht08_id_usuario = ($this->ht08_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ht08_id_usuario"]:$this->ht08_id_usuario);
     }else{
       $this->ht08_sequencial = ($this->ht08_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht08_sequencial"]:$this->ht08_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ht08_sequencial){ 
      $this->atualizacampos();
     if($this->ht08_habitformaavaliacao == null ){ 
       $this->erro_sql = " Campo Forma de Avalia��o nao Informado.";
       $this->erro_campo = "ht08_habitformaavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht08_id_usuario == null ){ 
       $this->erro_sql = " Campo Usu�rio nao Informado.";
       $this->erro_campo = "ht08_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ht08_sequencial == "" || $ht08_sequencial == null ){
       $result = db_query("select nextval('habitformaavaliacaousuario_ht08_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: habitformaavaliacaousuario_ht08_sequencial_seq do campo: ht08_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ht08_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from habitformaavaliacaousuario_ht08_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ht08_sequencial)){
         $this->erro_sql = " Campo ht08_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ht08_sequencial = $ht08_sequencial; 
       }
     }
     if(($this->ht08_sequencial == null) || ($this->ht08_sequencial == "") ){ 
       $this->erro_sql = " Campo ht08_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into habitformaavaliacaousuario(
                                       ht08_sequencial 
                                      ,ht08_habitformaavaliacao 
                                      ,ht08_id_usuario 
                       )
                values (
                                $this->ht08_sequencial 
                               ,$this->ht08_habitformaavaliacao 
                               ,$this->ht08_id_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Usu�rio da Forma de Avalia��o da Habita��o ($this->ht08_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Usu�rio da Forma de Avalia��o da Habita��o j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Usu�rio da Forma de Avalia��o da Habita��o ($this->ht08_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht08_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ht08_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16976,'$this->ht08_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2996,16976,'','".AddSlashes(pg_result($resaco,0,'ht08_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2996,16977,'','".AddSlashes(pg_result($resaco,0,'ht08_habitformaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2996,16978,'','".AddSlashes(pg_result($resaco,0,'ht08_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ht08_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update habitformaavaliacaousuario set ";
     $virgula = "";
     if(trim($this->ht08_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht08_sequencial"])){ 
       $sql  .= $virgula." ht08_sequencial = $this->ht08_sequencial ";
       $virgula = ",";
       if(trim($this->ht08_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ht08_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht08_habitformaavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht08_habitformaavaliacao"])){ 
       $sql  .= $virgula." ht08_habitformaavaliacao = $this->ht08_habitformaavaliacao ";
       $virgula = ",";
       if(trim($this->ht08_habitformaavaliacao) == null ){ 
         $this->erro_sql = " Campo Forma de Avalia��o nao Informado.";
         $this->erro_campo = "ht08_habitformaavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht08_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht08_id_usuario"])){ 
       $sql  .= $virgula." ht08_id_usuario = $this->ht08_id_usuario ";
       $virgula = ",";
       if(trim($this->ht08_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usu�rio nao Informado.";
         $this->erro_campo = "ht08_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ht08_sequencial!=null){
       $sql .= " ht08_sequencial = $this->ht08_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ht08_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16976,'$this->ht08_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht08_sequencial"]) || $this->ht08_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2996,16976,'".AddSlashes(pg_result($resaco,$conresaco,'ht08_sequencial'))."','$this->ht08_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht08_habitformaavaliacao"]) || $this->ht08_habitformaavaliacao != "")
           $resac = db_query("insert into db_acount values($acount,2996,16977,'".AddSlashes(pg_result($resaco,$conresaco,'ht08_habitformaavaliacao'))."','$this->ht08_habitformaavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht08_id_usuario"]) || $this->ht08_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2996,16978,'".AddSlashes(pg_result($resaco,$conresaco,'ht08_id_usuario'))."','$this->ht08_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Usu�rio da Forma de Avalia��o da Habita��o nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht08_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Usu�rio da Forma de Avalia��o da Habita��o nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht08_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht08_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ht08_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ht08_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16976,'$ht08_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2996,16976,'','".AddSlashes(pg_result($resaco,$iresaco,'ht08_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2996,16977,'','".AddSlashes(pg_result($resaco,$iresaco,'ht08_habitformaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2996,16978,'','".AddSlashes(pg_result($resaco,$iresaco,'ht08_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from habitformaavaliacaousuario
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ht08_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ht08_sequencial = $ht08_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Usu�rio da Forma de Avalia��o da Habita��o nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ht08_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Usu�rio da Forma de Avalia��o da Habita��o nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ht08_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ht08_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:habitformaavaliacaousuario";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ht08_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitformaavaliacaousuario ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = habitformaavaliacaousuario.ht08_id_usuario";
     $sql .= "      inner join habitformaavaliacao  on  habitformaavaliacao.ht07_sequencial = habitformaavaliacaousuario.ht08_habitformaavaliacao";
     $sql2 = "";
     if($dbwhere==""){
       if($ht08_sequencial!=null ){
         $sql2 .= " where habitformaavaliacaousuario.ht08_sequencial = $ht08_sequencial "; 
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
   function sql_query_file ( $ht08_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitformaavaliacaousuario ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht08_sequencial!=null ){
         $sql2 .= " where habitformaavaliacaousuario.ht08_sequencial = $ht08_sequencial "; 
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