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

//MODULO: Custos
//CLASSE DA ENTIDADE matrequiitemcriteriocustorateio
class cl_matrequiitemcriteriocustorateio { 
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
   var $cc13_sequencial = 0; 
   var $cc13_matrequiitem = 0; 
   var $cc13_custocriteriorateio = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cc13_sequencial = int4 = Código Sequencial 
                 cc13_matrequiitem = int4 = Código do Material 
                 cc13_custocriteriorateio = int4 = Código do Critério 
                 ";
   //funcao construtor da classe 
   function cl_matrequiitemcriteriocustorateio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matrequiitemcriteriocustorateio"); 
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
       $this->cc13_sequencial = ($this->cc13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc13_sequencial"]:$this->cc13_sequencial);
       $this->cc13_matrequiitem = ($this->cc13_matrequiitem == ""?@$GLOBALS["HTTP_POST_VARS"]["cc13_matrequiitem"]:$this->cc13_matrequiitem);
       $this->cc13_custocriteriorateio = ($this->cc13_custocriteriorateio == ""?@$GLOBALS["HTTP_POST_VARS"]["cc13_custocriteriorateio"]:$this->cc13_custocriteriorateio);
     }else{
       $this->cc13_sequencial = ($this->cc13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc13_sequencial"]:$this->cc13_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($cc13_sequencial){ 
      $this->atualizacampos();
     if($this->cc13_matrequiitem == null ){ 
       $this->erro_sql = " Campo Código do Material nao Informado.";
       $this->erro_campo = "cc13_matrequiitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc13_custocriteriorateio == null ){ 
       $this->erro_sql = " Campo Código do Critério nao Informado.";
       $this->erro_campo = "cc13_custocriteriorateio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cc13_sequencial == "" || $cc13_sequencial == null ){
       $result = db_query("select nextval('matrequiitemcriteriocustorateio_cc13_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matrequiitemcriteriocustorateio_cc13_sequencial_seq do campo: cc13_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cc13_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matrequiitemcriteriocustorateio_cc13_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cc13_sequencial)){
         $this->erro_sql = " Campo cc13_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cc13_sequencial = $cc13_sequencial; 
       }
     }
     if(($this->cc13_sequencial == null) || ($this->cc13_sequencial == "") ){ 
       $this->erro_sql = " Campo cc13_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matrequiitemcriteriocustorateio(
                                       cc13_sequencial 
                                      ,cc13_matrequiitem 
                                      ,cc13_custocriteriorateio 
                       )
                values (
                                $this->cc13_sequencial 
                               ,$this->cc13_matrequiitem 
                               ,$this->cc13_custocriteriorateio 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "matrequiitemcriteriocustorateio ($this->cc13_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "matrequiitemcriteriocustorateio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "matrequiitemcriteriocustorateio ($this->cc13_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc13_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cc13_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13479,'$this->cc13_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2359,13479,'','".AddSlashes(pg_result($resaco,0,'cc13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2359,13480,'','".AddSlashes(pg_result($resaco,0,'cc13_matrequiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2359,13481,'','".AddSlashes(pg_result($resaco,0,'cc13_custocriteriorateio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cc13_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update matrequiitemcriteriocustorateio set ";
     $virgula = "";
     if(trim($this->cc13_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc13_sequencial"])){ 
       $sql  .= $virgula." cc13_sequencial = $this->cc13_sequencial ";
       $virgula = ",";
       if(trim($this->cc13_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "cc13_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc13_matrequiitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc13_matrequiitem"])){ 
       $sql  .= $virgula." cc13_matrequiitem = $this->cc13_matrequiitem ";
       $virgula = ",";
       if(trim($this->cc13_matrequiitem) == null ){ 
         $this->erro_sql = " Campo Código do Material nao Informado.";
         $this->erro_campo = "cc13_matrequiitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc13_custocriteriorateio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc13_custocriteriorateio"])){ 
       $sql  .= $virgula." cc13_custocriteriorateio = $this->cc13_custocriteriorateio ";
       $virgula = ",";
       if(trim($this->cc13_custocriteriorateio) == null ){ 
         $this->erro_sql = " Campo Código do Critério nao Informado.";
         $this->erro_campo = "cc13_custocriteriorateio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cc13_sequencial!=null){
       $sql .= " cc13_sequencial = $this->cc13_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cc13_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13479,'$this->cc13_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc13_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2359,13479,'".AddSlashes(pg_result($resaco,$conresaco,'cc13_sequencial'))."','$this->cc13_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc13_matrequiitem"]))
           $resac = db_query("insert into db_acount values($acount,2359,13480,'".AddSlashes(pg_result($resaco,$conresaco,'cc13_matrequiitem'))."','$this->cc13_matrequiitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc13_custocriteriorateio"]))
           $resac = db_query("insert into db_acount values($acount,2359,13481,'".AddSlashes(pg_result($resaco,$conresaco,'cc13_custocriteriorateio'))."','$this->cc13_custocriteriorateio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matrequiitemcriteriocustorateio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matrequiitemcriteriocustorateio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cc13_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cc13_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13479,'$cc13_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2359,13479,'','".AddSlashes(pg_result($resaco,$iresaco,'cc13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2359,13480,'','".AddSlashes(pg_result($resaco,$iresaco,'cc13_matrequiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2359,13481,'','".AddSlashes(pg_result($resaco,$iresaco,'cc13_custocriteriorateio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matrequiitemcriteriocustorateio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cc13_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cc13_sequencial = $cc13_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matrequiitemcriteriocustorateio nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cc13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matrequiitemcriteriocustorateio nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cc13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cc13_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:matrequiitemcriteriocustorateio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $cc13_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matrequiitemcriteriocustorateio ";
     $sql .= "      inner join matrequiitem  on  matrequiitem.m41_codigo = matrequiitemcriteriocustorateio.cc13_matrequiitem";
     $sql .= "      inner join custocriteriorateio  on  custocriteriorateio.cc08_sequencial = matrequiitemcriteriocustorateio.cc13_custocriteriorateio";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matrequiitem.m41_codmatmater";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matrequiitem.m41_codunid";
     $sql .= "      inner join matrequi  on  matrequi.m40_codigo = matrequiitem.m41_codmatrequi";
     $sql .= "      inner join db_config  on  db_config.codigo = custocriteriorateio.cc08_instit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = custocriteriorateio.cc08_coddepto";
     $sql .= "      inner join matunid  as a on   a.m61_codmatunid = custocriteriorateio.cc08_matunid";
     $sql2 = "";
     if($dbwhere==""){
       if($cc13_sequencial!=null ){
         $sql2 .= " where matrequiitemcriteriocustorateio.cc13_sequencial = $cc13_sequencial "; 
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
   function sql_query_file ( $cc13_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matrequiitemcriteriocustorateio ";
     $sql2 = "";
     if($dbwhere==""){
       if($cc13_sequencial!=null ){
         $sql2 .= " where matrequiitemcriteriocustorateio.cc13_sequencial = $cc13_sequencial "; 
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