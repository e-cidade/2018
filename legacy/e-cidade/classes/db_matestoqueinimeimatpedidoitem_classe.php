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

//MODULO: material
//CLASSE DA ENTIDADE matestoqueinimeimatpedidoitem
class cl_matestoqueinimeimatpedidoitem { 
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
   var $m99_codigo = 0; 
   var $m99_matpedidoitem = 0; 
   var $m99_matestoqueinimei = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m99_codigo = int8 = C�digo 
                 m99_matpedidoitem = int8 = �tem 
                 m99_matestoqueinimei = int8 = Estoque 
                 ";
   //funcao construtor da classe 
   function cl_matestoqueinimeimatpedidoitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoqueinimeimatpedidoitem"); 
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
       $this->m99_codigo = ($this->m99_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m99_codigo"]:$this->m99_codigo);
       $this->m99_matpedidoitem = ($this->m99_matpedidoitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m99_matpedidoitem"]:$this->m99_matpedidoitem);
       $this->m99_matestoqueinimei = ($this->m99_matestoqueinimei == ""?@$GLOBALS["HTTP_POST_VARS"]["m99_matestoqueinimei"]:$this->m99_matestoqueinimei);
     }else{
       $this->m99_codigo = ($this->m99_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m99_codigo"]:$this->m99_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($m99_codigo){ 
      $this->atualizacampos();
     if($this->m99_matpedidoitem == null ){ 
       $this->erro_sql = " Campo �tem nao Informado.";
       $this->erro_campo = "m99_matpedidoitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m99_matestoqueinimei == null ){ 
       $this->erro_sql = " Campo Estoque nao Informado.";
       $this->erro_campo = "m99_matestoqueinimei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m99_codigo == "" || $m99_codigo == null ){
       $result = db_query("select nextval('matestoqueinimeimatpedidoitem_m98_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matestoqueinimeimatpedidoitem_m98_codigo_seq do campo: m99_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m99_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matestoqueinimeimatpedidoitem_m98_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $m99_codigo)){
         $this->erro_sql = " Campo m99_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m99_codigo = $m99_codigo; 
       }
     }
     if(($this->m99_codigo == null) || ($this->m99_codigo == "") ){ 
       $this->erro_sql = " Campo m99_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoqueinimeimatpedidoitem(
                                       m99_codigo 
                                      ,m99_matpedidoitem 
                                      ,m99_matestoqueinimei 
                       )
                values (
                                $this->m99_codigo 
                               ,$this->m99_matpedidoitem 
                               ,$this->m99_matestoqueinimei 
                      )";
                             
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "matestoqueinimeimatpedidoitem ($this->m99_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "matestoqueinimeimatpedidoitem j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "matestoqueinimeimatpedidoitem ($this->m99_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m99_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m99_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15245,'$this->m99_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2686,15245,'','".AddSlashes(pg_result($resaco,0,'m99_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2686,15246,'','".AddSlashes(pg_result($resaco,0,'m99_matpedidoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2686,15247,'','".AddSlashes(pg_result($resaco,0,'m99_matestoqueinimei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m99_codigo=null) { 
      $this->atualizacampos();
     $sql = " update matestoqueinimeimatpedidoitem set ";
     $virgula = "";
     if(trim($this->m99_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m99_codigo"])){ 
       $sql  .= $virgula." m99_codigo = $this->m99_codigo ";
       $virgula = ",";
       if(trim($this->m99_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "m99_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m99_matpedidoitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m99_matpedidoitem"])){ 
       $sql  .= $virgula." m99_matpedidoitem = $this->m99_matpedidoitem ";
       $virgula = ",";
       if(trim($this->m99_matpedidoitem) == null ){ 
         $this->erro_sql = " Campo �tem nao Informado.";
         $this->erro_campo = "m99_matpedidoitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m99_matestoqueinimei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m99_matestoqueinimei"])){ 
       $sql  .= $virgula." m99_matestoqueinimei = $this->m99_matestoqueinimei ";
       $virgula = ",";
       if(trim($this->m99_matestoqueinimei) == null ){ 
         $this->erro_sql = " Campo Estoque nao Informado.";
         $this->erro_campo = "m99_matestoqueinimei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m99_codigo!=null){
       $sql .= " m99_codigo = $this->m99_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m99_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15245,'$this->m99_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m99_codigo"]) || $this->m99_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2686,15245,'".AddSlashes(pg_result($resaco,$conresaco,'m99_codigo'))."','$this->m99_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m99_matpedidoitem"]) || $this->m99_matpedidoitem != "")
           $resac = db_query("insert into db_acount values($acount,2686,15246,'".AddSlashes(pg_result($resaco,$conresaco,'m99_matpedidoitem'))."','$this->m99_matpedidoitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m99_matestoqueinimei"]) || $this->m99_matestoqueinimei != "")
           $resac = db_query("insert into db_acount values($acount,2686,15247,'".AddSlashes(pg_result($resaco,$conresaco,'m99_matestoqueinimei'))."','$this->m99_matestoqueinimei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matestoqueinimeimatpedidoitem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m99_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matestoqueinimeimatpedidoitem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m99_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m99_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m99_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m99_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15245,'$m99_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2686,15245,'','".AddSlashes(pg_result($resaco,$iresaco,'m99_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2686,15246,'','".AddSlashes(pg_result($resaco,$iresaco,'m99_matpedidoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2686,15247,'','".AddSlashes(pg_result($resaco,$iresaco,'m99_matestoqueinimei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoqueinimeimatpedidoitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m99_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m99_codigo = $m99_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matestoqueinimeimatpedidoitem nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m99_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matestoqueinimeimatpedidoitem nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m99_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m99_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoqueinimeimatpedidoitem";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m99_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueinimeimatpedidoitem ";
     $sql .= "      inner join matestoqueinimei  on  matestoqueinimei.m82_codigo = matestoqueinimeimatpedidoitem.m99_matestoqueinimei";
     $sql .= "      inner join matpedidoitem  on  matpedidoitem.m98_sequencial = matestoqueinimeimatpedidoitem.m99_matpedidoitem";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem";
     $sql .= "      inner join matestoqueini  on  matestoqueini.m80_codigo = matestoqueinimei.m82_matestoqueini";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matpedidoitem.m98_matmater";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matpedidoitem.m98_matunid";
     $sql .= "      inner join matpedido  as a on   a.m97_sequencial = matpedidoitem.m98_matpedido";
     $sql2 = "";
     if($dbwhere==""){
       if($m99_codigo!=null ){
         $sql2 .= " where matestoqueinimeimatpedidoitem.m99_codigo = $m99_codigo "; 
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
   function sql_query_file ( $m99_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueinimeimatpedidoitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($m99_codigo!=null ){
         $sql2 .= " where matestoqueinimeimatpedidoitem.m99_codigo = $m99_codigo "; 
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