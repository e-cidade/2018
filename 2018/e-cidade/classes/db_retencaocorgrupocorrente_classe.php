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

//MODULO: empenho
//CLASSE DA ENTIDADE retencaocorgrupocorrente
class cl_retencaocorgrupocorrente { 
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
   var $e47_sequencial = 0; 
   var $e47_corgrupocorrente = 0; 
   var $e47_retencaoreceita = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e47_sequencial = int4 = Código Sequencial 
                 e47_corgrupocorrente = int4 = Código do grupo 
                 e47_retencaoreceita = int4 = Código da Retencao 
                 ";
   //funcao construtor da classe 
   function cl_retencaocorgrupocorrente() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("retencaocorgrupocorrente"); 
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
       $this->e47_sequencial = ($this->e47_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e47_sequencial"]:$this->e47_sequencial);
       $this->e47_corgrupocorrente = ($this->e47_corgrupocorrente == ""?@$GLOBALS["HTTP_POST_VARS"]["e47_corgrupocorrente"]:$this->e47_corgrupocorrente);
       $this->e47_retencaoreceita = ($this->e47_retencaoreceita == ""?@$GLOBALS["HTTP_POST_VARS"]["e47_retencaoreceita"]:$this->e47_retencaoreceita);
     }else{
       $this->e47_sequencial = ($this->e47_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e47_sequencial"]:$this->e47_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e47_sequencial){ 
      $this->atualizacampos();
     if($this->e47_corgrupocorrente == null ){ 
       $this->erro_sql = " Campo Código do grupo nao Informado.";
       $this->erro_campo = "e47_corgrupocorrente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e47_retencaoreceita == null ){ 
       $this->erro_sql = " Campo Código da Retencao nao Informado.";
       $this->erro_campo = "e47_retencaoreceita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e47_sequencial == "" || $e47_sequencial == null ){
       $result = db_query("select nextval('retencaocorgrupocorrente_e47_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: retencaocorgrupocorrente_e47_sequencial_seq do campo: e47_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e47_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from retencaocorgrupocorrente_e47_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e47_sequencial)){
         $this->erro_sql = " Campo e47_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e47_sequencial = $e47_sequencial; 
       }
     }
     if(($this->e47_sequencial == null) || ($this->e47_sequencial == "") ){ 
       $this->erro_sql = " Campo e47_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into retencaocorgrupocorrente(
                                       e47_sequencial 
                                      ,e47_corgrupocorrente 
                                      ,e47_retencaoreceita 
                       )
                values (
                                $this->e47_sequencial 
                               ,$this->e47_corgrupocorrente 
                               ,$this->e47_retencaoreceita 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Liga a retencao ao grupo de autenticação ($this->e47_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Liga a retencao ao grupo de autenticação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Liga a retencao ao grupo de autenticação ($this->e47_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e47_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e47_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12455,'$this->e47_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2173,12455,'','".AddSlashes(pg_result($resaco,0,'e47_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2173,12456,'','".AddSlashes(pg_result($resaco,0,'e47_corgrupocorrente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2173,12457,'','".AddSlashes(pg_result($resaco,0,'e47_retencaoreceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e47_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update retencaocorgrupocorrente set ";
     $virgula = "";
     if(trim($this->e47_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e47_sequencial"])){ 
       $sql  .= $virgula." e47_sequencial = $this->e47_sequencial ";
       $virgula = ",";
       if(trim($this->e47_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "e47_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e47_corgrupocorrente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e47_corgrupocorrente"])){ 
       $sql  .= $virgula." e47_corgrupocorrente = $this->e47_corgrupocorrente ";
       $virgula = ",";
       if(trim($this->e47_corgrupocorrente) == null ){ 
         $this->erro_sql = " Campo Código do grupo nao Informado.";
         $this->erro_campo = "e47_corgrupocorrente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e47_retencaoreceita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e47_retencaoreceita"])){ 
       $sql  .= $virgula." e47_retencaoreceita = $this->e47_retencaoreceita ";
       $virgula = ",";
       if(trim($this->e47_retencaoreceita) == null ){ 
         $this->erro_sql = " Campo Código da Retencao nao Informado.";
         $this->erro_campo = "e47_retencaoreceita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e47_sequencial!=null){
       $sql .= " e47_sequencial = $this->e47_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e47_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12455,'$this->e47_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e47_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2173,12455,'".AddSlashes(pg_result($resaco,$conresaco,'e47_sequencial'))."','$this->e47_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e47_corgrupocorrente"]))
           $resac = db_query("insert into db_acount values($acount,2173,12456,'".AddSlashes(pg_result($resaco,$conresaco,'e47_corgrupocorrente'))."','$this->e47_corgrupocorrente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e47_retencaoreceita"]))
           $resac = db_query("insert into db_acount values($acount,2173,12457,'".AddSlashes(pg_result($resaco,$conresaco,'e47_retencaoreceita'))."','$this->e47_retencaoreceita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Liga a retencao ao grupo de autenticação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e47_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Liga a retencao ao grupo de autenticação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e47_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e47_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e47_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e47_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12455,'$e47_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2173,12455,'','".AddSlashes(pg_result($resaco,$iresaco,'e47_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2173,12456,'','".AddSlashes(pg_result($resaco,$iresaco,'e47_corgrupocorrente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2173,12457,'','".AddSlashes(pg_result($resaco,$iresaco,'e47_retencaoreceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from retencaocorgrupocorrente
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e47_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e47_sequencial = $e47_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Liga a retencao ao grupo de autenticação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e47_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Liga a retencao ao grupo de autenticação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e47_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e47_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:retencaocorgrupocorrente";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $e47_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from retencaocorgrupocorrente ";
     $sql .= "      inner join retencaoreceitas  on  retencaoreceitas.e23_sequencial = retencaocorgrupocorrente.e47_retencaoreceita";
     $sql .= "      inner join corgrupocorrente  on  corgrupocorrente.k105_sequencial = retencaocorgrupocorrente.e47_corgrupocorrente";
     $sql .= "      inner join retencaotiporec  on  retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec";
     $sql .= "      inner join retencaopagordem  on  retencaopagordem.e20_sequencial = retencaoreceitas.e23_retencaopagordem";
     $sql .= "      inner join corrente  on  corrente.k12_id = corgrupocorrente.k105_id and  corrente.k12_data = corgrupocorrente.k105_data and  corrente.k12_autent = corgrupocorrente.k105_autent";
     $sql .= "      inner join corgrupo  as a on   a.k104_sequencial = corgrupocorrente.k105_corgrupo";
     $sql .= "      inner join corgrupotipo  on  corgrupotipo.k106_sequencial = corgrupocorrente.k105_corgrupotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($e47_sequencial!=null ){
         $sql2 .= " where retencaocorgrupocorrente.e47_sequencial = $e47_sequencial "; 
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
  
 function sql_query_numpre ( $e47_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from retencaocorgrupocorrente ";
     $sql .= "      inner join retencaoreceitas  on  retencaoreceitas.e23_sequencial  = retencaocorgrupocorrente.e47_retencaoreceita";
     $sql .= "      inner join corgrupocorrente  on  corgrupocorrente.k105_sequencial = retencaocorgrupocorrente.e47_corgrupocorrente";
     $sql .= "      inner join corrente          on  corrente.k12_id                  = corgrupocorrente.k105_id     ";
     $sql .= "                                  and  corrente.k12_data                = corgrupocorrente.k105_data   ";
     $sql .= "                                  and  corrente.k12_autent              = corgrupocorrente.k105_autent ";
     $sql .= "      inner join cornump           on  corrente.k12_id                  = cornump.k12_id ";
     $sql .= "                                  and  corrente.k12_data                = cornump.k12_data ";
     $sql .= "                                  and  corrente.k12_autent              = cornump.k12_autent";
     $sql2 = "";
     if($dbwhere==""){
       if($e47_sequencial!=null ){
         $sql2 .= " where retencaocorgrupocorrente.e47_sequencial = $e47_sequencial "; 
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
  
 function sql_query_numpre_slip ( $e47_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from retencaocorgrupocorrente ";
     $sql .= "      inner join retencaoreceitas  on  retencaoreceitas.e23_sequencial  = retencaocorgrupocorrente.e47_retencaoreceita";
     $sql .= "      inner join corgrupocorrente  on  corgrupocorrente.k105_sequencial = retencaocorgrupocorrente.e47_corgrupocorrente";
     $sql .= "      inner join corrente          on  corrente.k12_id                  = corgrupocorrente.k105_id     ";
     $sql .= "                                  and  corrente.k12_data                = corgrupocorrente.k105_data   ";
     $sql .= "                                  and  corrente.k12_autent              = corgrupocorrente.k105_autent ";
     $sql .= "      inner join cornump           on  corrente.k12_id                  = cornump.k12_id ";
     $sql .= "                                  and  corrente.k12_data                = cornump.k12_data ";
     $sql .= "                                  and  corrente.k12_autent              = cornump.k12_autent";
     $sql .= "      inner join slipcorrente      on  cornump.k12_id                   = k112_id ";
     $sql .= "                                  and  cornump.k12_data                 = k112_data ";
     $sql .= "                                  and  cornump.k12_autent               = k112_autent";
     $sql2 = "";
     if($dbwhere==""){
       if($e47_sequencial!=null ){
         $sql2 .= " where retencaocorgrupocorrente.e47_sequencial = $e47_sequencial "; 
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
   function sql_query_file ( $e47_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from retencaocorgrupocorrente ";
     $sql2 = "";
     if($dbwhere==""){
       if($e47_sequencial!=null ){
         $sql2 .= " where retencaocorgrupocorrente.e47_sequencial = $e47_sequencial "; 
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