<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: material
//CLASSE DA ENTIDADE matestoqueinimeipm
class cl_matestoqueinimeipm { 
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
   var $m89_sequencial = 0; 
   var $m89_matestoqueinimei = 0; 
   var $m89_precomedio = 0; 
   var $m89_valorunitario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m89_sequencial = int4 = Código Sequencial 
                 m89_matestoqueinimei = int4 = Código da Movimentacao 
                 m89_precomedio = float8 = Preco médio da novimentacao 
                 m89_valorunitario = float8 = Valor Unitário 
                 ";
   //funcao construtor da classe 
   function cl_matestoqueinimeipm() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoqueinimeipm"); 
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
       $this->m89_sequencial = ($this->m89_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m89_sequencial"]:$this->m89_sequencial);
       $this->m89_matestoqueinimei = ($this->m89_matestoqueinimei == ""?@$GLOBALS["HTTP_POST_VARS"]["m89_matestoqueinimei"]:$this->m89_matestoqueinimei);
       $this->m89_precomedio = ($this->m89_precomedio == ""?@$GLOBALS["HTTP_POST_VARS"]["m89_precomedio"]:$this->m89_precomedio);
       $this->m89_valorunitario = ($this->m89_valorunitario == ""?@$GLOBALS["HTTP_POST_VARS"]["m89_valorunitario"]:$this->m89_valorunitario);
     }else{
       $this->m89_sequencial = ($this->m89_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m89_sequencial"]:$this->m89_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m89_sequencial){ 
      $this->atualizacampos();
     if($this->m89_matestoqueinimei == null ){ 
       $this->erro_sql = " Campo Código da Movimentacao nao Informado.";
       $this->erro_campo = "m89_matestoqueinimei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m89_precomedio == null ){ 
       $this->m89_precomedio = "0";
     }
     if($this->m89_valorunitario == null ){ 
       $this->m89_valorunitario = "0";
     }
     if($m89_sequencial == "" || $m89_sequencial == null ){
       $result = db_query("select nextval('matestoqueinimeipm_m89_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matestoqueinimeipm_m89_sequencial_seq do campo: m89_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m89_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matestoqueinimeipm_m89_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m89_sequencial)){
         $this->erro_sql = " Campo m89_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m89_sequencial = $m89_sequencial; 
       }
     }
     if(($this->m89_sequencial == null) || ($this->m89_sequencial == "") ){ 
       $this->erro_sql = " Campo m89_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoqueinimeipm(
                                       m89_sequencial 
                                      ,m89_matestoqueinimei 
                                      ,m89_precomedio 
                                      ,m89_valorunitario 
                       )
                values (
                                $this->m89_sequencial 
                               ,$this->m89_matestoqueinimei 
                               ,$this->m89_precomedio 
                               ,$this->m89_valorunitario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Preco médio da novimentacao ($this->m89_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Preco médio da novimentacao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Preco médio da novimentacao ($this->m89_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m89_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m89_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17992,'$this->m89_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3179,17992,'','".AddSlashes(pg_result($resaco,0,'m89_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3179,17993,'','".AddSlashes(pg_result($resaco,0,'m89_matestoqueinimei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3179,17994,'','".AddSlashes(pg_result($resaco,0,'m89_precomedio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3179,17995,'','".AddSlashes(pg_result($resaco,0,'m89_valorunitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m89_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update matestoqueinimeipm set ";
     $virgula = "";
     if(trim($this->m89_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m89_sequencial"])){ 
       $sql  .= $virgula." m89_sequencial = $this->m89_sequencial ";
       $virgula = ",";
       if(trim($this->m89_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "m89_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m89_matestoqueinimei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m89_matestoqueinimei"])){ 
       $sql  .= $virgula." m89_matestoqueinimei = $this->m89_matestoqueinimei ";
       $virgula = ",";
       if(trim($this->m89_matestoqueinimei) == null ){ 
         $this->erro_sql = " Campo Código da Movimentacao nao Informado.";
         $this->erro_campo = "m89_matestoqueinimei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m89_precomedio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m89_precomedio"])){ 
        if(trim($this->m89_precomedio)=="" && isset($GLOBALS["HTTP_POST_VARS"]["m89_precomedio"])){ 
           $this->m89_precomedio = "0" ; 
        } 
       $sql  .= $virgula." m89_precomedio = $this->m89_precomedio ";
       $virgula = ",";
     }
     if(trim($this->m89_valorunitario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m89_valorunitario"])){ 
        if(trim($this->m89_valorunitario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["m89_valorunitario"])){ 
           $this->m89_valorunitario = "0" ; 
        } 
       $sql  .= $virgula." m89_valorunitario = $this->m89_valorunitario ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($m89_sequencial!=null){
       $sql .= " m89_sequencial = $this->m89_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m89_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17992,'$this->m89_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m89_sequencial"]) || $this->m89_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3179,17992,'".AddSlashes(pg_result($resaco,$conresaco,'m89_sequencial'))."','$this->m89_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m89_matestoqueinimei"]) || $this->m89_matestoqueinimei != "")
           $resac = db_query("insert into db_acount values($acount,3179,17993,'".AddSlashes(pg_result($resaco,$conresaco,'m89_matestoqueinimei'))."','$this->m89_matestoqueinimei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m89_precomedio"]) || $this->m89_precomedio != "")
           $resac = db_query("insert into db_acount values($acount,3179,17994,'".AddSlashes(pg_result($resaco,$conresaco,'m89_precomedio'))."','$this->m89_precomedio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m89_valorunitario"]) || $this->m89_valorunitario != "")
           $resac = db_query("insert into db_acount values($acount,3179,17995,'".AddSlashes(pg_result($resaco,$conresaco,'m89_valorunitario'))."','$this->m89_valorunitario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Preco médio da novimentacao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m89_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Preco médio da novimentacao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m89_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m89_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m89_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m89_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17992,'$m89_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3179,17992,'','".AddSlashes(pg_result($resaco,$iresaco,'m89_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3179,17993,'','".AddSlashes(pg_result($resaco,$iresaco,'m89_matestoqueinimei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3179,17994,'','".AddSlashes(pg_result($resaco,$iresaco,'m89_precomedio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3179,17995,'','".AddSlashes(pg_result($resaco,$iresaco,'m89_valorunitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoqueinimeipm
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m89_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m89_sequencial = $m89_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Preco médio da novimentacao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m89_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Preco médio da novimentacao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m89_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m89_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoqueinimeipm";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m89_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueinimeipm ";
     $sql .= "      inner join matestoqueinimei  on  matestoqueinimei.m82_codigo = matestoqueinimeipm.m89_matestoqueinimei";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem";
     $sql .= "      inner join matestoqueini  on  matestoqueini.m80_codigo = matestoqueinimei.m82_matestoqueini";
     $sql2 = "";
     if($dbwhere==""){
       if($m89_sequencial!=null ){
         $sql2 .= " where matestoqueinimeipm.m89_sequencial = $m89_sequencial "; 
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
   function sql_query_file ( $m89_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueinimeipm ";
     $sql2 = "";
     if($dbwhere==""){
       if($m89_sequencial!=null ){
         $sql2 .= " where matestoqueinimeipm.m89_sequencial = $m89_sequencial "; 
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