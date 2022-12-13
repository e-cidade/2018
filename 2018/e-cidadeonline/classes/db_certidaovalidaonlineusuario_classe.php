<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: prefeitura
//CLASSE DA ENTIDADE certidaovalidaonlineusuario
class cl_certidaovalidaonlineusuario { 
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
   var $w20_sequencial = 0; 
   var $w20_certidaovalidaonline = 0; 
   var $w20_id_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 w20_sequencial = int8 = Sequencial 
                 w20_certidaovalidaonline = int8 = Codigo Validacao On line 
                 w20_id_usuario = int4 = Id Usuario 
                 ";
   //funcao construtor da classe 
   function cl_certidaovalidaonlineusuario() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("certidaovalidaonlineusuario"); 
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
       $this->w20_sequencial = ($this->w20_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["w20_sequencial"]:$this->w20_sequencial);
       $this->w20_certidaovalidaonline = ($this->w20_certidaovalidaonline == ""?@$GLOBALS["HTTP_POST_VARS"]["w20_certidaovalidaonline"]:$this->w20_certidaovalidaonline);
       $this->w20_id_usuario = ($this->w20_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["w20_id_usuario"]:$this->w20_id_usuario);
     }else{
       $this->w20_sequencial = ($this->w20_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["w20_sequencial"]:$this->w20_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($w20_sequencial){ 
      $this->atualizacampos();
     if($this->w20_certidaovalidaonline == null ){ 
       $this->erro_sql = " Campo Codigo Validacao On line nao Informado.";
       $this->erro_campo = "w20_certidaovalidaonline";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w20_id_usuario == null ){ 
       $this->erro_sql = " Campo Id Usuario nao Informado.";
       $this->erro_campo = "w20_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($w20_sequencial == "" || $w20_sequencial == null ){
       $result = db_query("select nextval('certidaovalidaonlineusuario_w20_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: certidaovalidaonlineusuario_w20_sequencial_seq do campo: w20_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->w20_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from certidaovalidaonlineusuario_w20_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $w20_sequencial)){
         $this->erro_sql = " Campo w20_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->w20_sequencial = $w20_sequencial; 
       }
     }
     if(($this->w20_sequencial == null) || ($this->w20_sequencial == "") ){ 
       $this->erro_sql = " Campo w20_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into certidaovalidaonlineusuario(
                                       w20_sequencial 
                                      ,w20_certidaovalidaonline 
                                      ,w20_id_usuario 
                       )
                values (
                                $this->w20_sequencial 
                               ,$this->w20_certidaovalidaonline 
                               ,$this->w20_id_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valida Certidao On-line ($this->w20_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valida Certidao On-line já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valida Certidao On-line ($this->w20_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w20_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->w20_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       //$resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15032,'$this->w20_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2643,15032,'','".AddSlashes(pg_result($resaco,0,'w20_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2643,15033,'','".AddSlashes(pg_result($resaco,0,'w20_certidaovalidaonline'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2643,15034,'','".AddSlashes(pg_result($resaco,0,'w20_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($w20_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update certidaovalidaonlineusuario set ";
     $virgula = "";
     if(trim($this->w20_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w20_sequencial"])){ 
       $sql  .= $virgula." w20_sequencial = $this->w20_sequencial ";
       $virgula = ",";
       if(trim($this->w20_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "w20_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w20_certidaovalidaonline)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w20_certidaovalidaonline"])){ 
       $sql  .= $virgula." w20_certidaovalidaonline = $this->w20_certidaovalidaonline ";
       $virgula = ",";
       if(trim($this->w20_certidaovalidaonline) == null ){ 
         $this->erro_sql = " Campo Codigo Validacao On line nao Informado.";
         $this->erro_campo = "w20_certidaovalidaonline";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w20_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w20_id_usuario"])){ 
       $sql  .= $virgula." w20_id_usuario = $this->w20_id_usuario ";
       $virgula = ",";
       if(trim($this->w20_id_usuario) == null ){ 
         $this->erro_sql = " Campo Id Usuario nao Informado.";
         $this->erro_campo = "w20_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($w20_sequencial!=null){
       $sql .= " w20_sequencial = $this->w20_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->w20_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         //$resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15032,'$this->w20_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w20_sequencial"]) || $this->w20_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2643,15032,'".AddSlashes(pg_result($resaco,$conresaco,'w20_sequencial'))."','$this->w20_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w20_certidaovalidaonline"]) || $this->w20_certidaovalidaonline != "")
           $resac = db_query("insert into db_acount values($acount,2643,15033,'".AddSlashes(pg_result($resaco,$conresaco,'w20_certidaovalidaonline'))."','$this->w20_certidaovalidaonline',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w20_id_usuario"]) || $this->w20_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2643,15034,'".AddSlashes(pg_result($resaco,$conresaco,'w20_id_usuario'))."','$this->w20_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valida Certidao On-line nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->w20_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valida Certidao On-line nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->w20_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w20_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($w20_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($w20_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         //$resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15032,'$w20_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2643,15032,'','".AddSlashes(pg_result($resaco,$iresaco,'w20_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2643,15033,'','".AddSlashes(pg_result($resaco,$iresaco,'w20_certidaovalidaonline'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2643,15034,'','".AddSlashes(pg_result($resaco,$iresaco,'w20_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from certidaovalidaonlineusuario
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($w20_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " w20_sequencial = $w20_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valida Certidao On-line nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$w20_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valida Certidao On-line nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$w20_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$w20_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:certidaovalidaonlineusuario";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $w20_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from certidaovalidaonlineusuario ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = certidaovalidaonlineusuario.w20_id_usuario";
     $sql .= "      inner join certidaovalidaonline  on  certidaovalidaonline.w18_sequencial = certidaovalidaonlineusuario.w20_certidaovalidaonline";
     $sql2 = "";
     if($dbwhere==""){
       if($w20_sequencial!=null ){
         $sql2 .= " where certidaovalidaonlineusuario.w20_sequencial = $w20_sequencial "; 
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
   function sql_query_file ( $w20_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from certidaovalidaonlineusuario ";
     $sql2 = "";
     if($dbwhere==""){
       if($w20_sequencial!=null ){
         $sql2 .= " where certidaovalidaonlineusuario.w20_sequencial = $w20_sequencial "; 
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