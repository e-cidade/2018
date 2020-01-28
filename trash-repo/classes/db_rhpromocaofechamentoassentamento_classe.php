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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE rhpromocaofechamentoassentamento
class cl_rhpromocaofechamentoassentamento { 
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
   var $h78_sequencial = 0; 
   var $h78_rhpromocaofechamento = 0; 
   var $h78_rhassentamento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h78_sequencial = int4 = Sequencial 
                 h78_rhpromocaofechamento = int4 = Fechamento da promoção 
                 h78_rhassentamento = int4 = Assentamento 
                 ";
   //funcao construtor da classe 
   function cl_rhpromocaofechamentoassentamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhpromocaofechamentoassentamento"); 
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
       $this->h78_sequencial = ($this->h78_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h78_sequencial"]:$this->h78_sequencial);
       $this->h78_rhpromocaofechamento = ($this->h78_rhpromocaofechamento == ""?@$GLOBALS["HTTP_POST_VARS"]["h78_rhpromocaofechamento"]:$this->h78_rhpromocaofechamento);
       $this->h78_rhassentamento = ($this->h78_rhassentamento == ""?@$GLOBALS["HTTP_POST_VARS"]["h78_rhassentamento"]:$this->h78_rhassentamento);
     }else{
       $this->h78_sequencial = ($this->h78_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h78_sequencial"]:$this->h78_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($h78_sequencial){ 
      $this->atualizacampos();
     if($this->h78_rhpromocaofechamento == null ){ 
       $this->erro_sql = " Campo Fechamento da promoção nao Informado.";
       $this->erro_campo = "h78_rhpromocaofechamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h78_rhassentamento == null ){ 
       $this->erro_sql = " Campo Assentamento nao Informado.";
       $this->erro_campo = "h78_rhassentamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h78_sequencial == "" || $h78_sequencial == null ){
       $result = db_query("select nextval('rhpromocaofechamentoassentamento_h78_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpromocaofechamentoassentamento_h78_sequencial_seq do campo: h78_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h78_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpromocaofechamentoassentamento_h78_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h78_sequencial)){
         $this->erro_sql = " Campo h78_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h78_sequencial = $h78_sequencial; 
       }
     }
     if(($this->h78_sequencial == null) || ($this->h78_sequencial == "") ){ 
       $this->erro_sql = " Campo h78_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpromocaofechamentoassentamento(
                                       h78_sequencial 
                                      ,h78_rhpromocaofechamento 
                                      ,h78_rhassentamento 
                       )
                values (
                                $this->h78_sequencial 
                               ,$this->h78_rhpromocaofechamento 
                               ,$this->h78_rhassentamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Fechamento das perdas ($this->h78_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Fechamento das perdas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Fechamento das perdas ($this->h78_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h78_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h78_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18734,'$this->h78_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3321,18734,'','".AddSlashes(pg_result($resaco,0,'h78_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3321,18735,'','".AddSlashes(pg_result($resaco,0,'h78_rhpromocaofechamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3321,18736,'','".AddSlashes(pg_result($resaco,0,'h78_rhassentamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h78_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhpromocaofechamentoassentamento set ";
     $virgula = "";
     if(trim($this->h78_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h78_sequencial"])){ 
       $sql  .= $virgula." h78_sequencial = $this->h78_sequencial ";
       $virgula = ",";
       if(trim($this->h78_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "h78_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h78_rhpromocaofechamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h78_rhpromocaofechamento"])){ 
       $sql  .= $virgula." h78_rhpromocaofechamento = $this->h78_rhpromocaofechamento ";
       $virgula = ",";
       if(trim($this->h78_rhpromocaofechamento) == null ){ 
         $this->erro_sql = " Campo Fechamento da promoção nao Informado.";
         $this->erro_campo = "h78_rhpromocaofechamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h78_rhassentamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h78_rhassentamento"])){ 
       $sql  .= $virgula." h78_rhassentamento = $this->h78_rhassentamento ";
       $virgula = ",";
       if(trim($this->h78_rhassentamento) == null ){ 
         $this->erro_sql = " Campo Assentamento nao Informado.";
         $this->erro_campo = "h78_rhassentamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h78_sequencial!=null){
       $sql .= " h78_sequencial = $this->h78_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h78_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18734,'$this->h78_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h78_sequencial"]) || $this->h78_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3321,18734,'".AddSlashes(pg_result($resaco,$conresaco,'h78_sequencial'))."','$this->h78_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h78_rhpromocaofechamento"]) || $this->h78_rhpromocaofechamento != "")
           $resac = db_query("insert into db_acount values($acount,3321,18735,'".AddSlashes(pg_result($resaco,$conresaco,'h78_rhpromocaofechamento'))."','$this->h78_rhpromocaofechamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h78_rhassentamento"]) || $this->h78_rhassentamento != "")
           $resac = db_query("insert into db_acount values($acount,3321,18736,'".AddSlashes(pg_result($resaco,$conresaco,'h78_rhassentamento'))."','$this->h78_rhassentamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fechamento das perdas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h78_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fechamento das perdas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h78_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h78_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h78_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h78_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18734,'$h78_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3321,18734,'','".AddSlashes(pg_result($resaco,$iresaco,'h78_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3321,18735,'','".AddSlashes(pg_result($resaco,$iresaco,'h78_rhpromocaofechamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3321,18736,'','".AddSlashes(pg_result($resaco,$iresaco,'h78_rhassentamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhpromocaofechamentoassentamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h78_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h78_sequencial = $h78_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fechamento das perdas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h78_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fechamento das perdas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h78_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h78_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpromocaofechamentoassentamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $h78_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpromocaofechamentoassentamento ";
     $sql .= "      inner join assenta  on  assenta.h16_codigo = rhpromocaofechamentoassentamento.h78_rhassentamento";
     $sql .= "      inner join rhpromocaofechamento  on  rhpromocaofechamento.h77_sequencial = rhpromocaofechamentoassentamento.h78_rhpromocaofechamento";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = assenta.h16_login";
     $sql .= "      inner join tipoasse  on  tipoasse.h12_codigo = assenta.h16_assent";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = assenta.h16_regist";
     $sql .= "      inner join rhpromocao  as a on   a.h72_sequencial = rhpromocaofechamento.h77_rhpromocao";
     $sql2 = "";
     if($dbwhere==""){
       if($h78_sequencial!=null ){
         $sql2 .= " where rhpromocaofechamentoassentamento.h78_sequencial = $h78_sequencial "; 
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
   function sql_query_file ( $h78_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpromocaofechamentoassentamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($h78_sequencial!=null ){
         $sql2 .= " where rhpromocaofechamentoassentamento.h78_sequencial = $h78_sequencial "; 
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