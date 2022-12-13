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
//CLASSE DA ENTIDADE matanulitempedido
class cl_matanulitempedido { 
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
   var $m101_sequencial = 0; 
   var $m101_matanulitem = 0; 
   var $m101_matpedidoitem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m101_sequencial = int8 = Sequencial 
                 m101_matanulitem = int8 = Ítem 
                 m101_matpedidoitem = int8 = Pedido 
                 ";
   //funcao construtor da classe 
   function cl_matanulitempedido() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matanulitempedido"); 
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
       $this->m101_sequencial = ($this->m101_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m101_sequencial"]:$this->m101_sequencial);
       $this->m101_matanulitem = ($this->m101_matanulitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m101_matanulitem"]:$this->m101_matanulitem);
       $this->m101_matpedidoitem = ($this->m101_matpedidoitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m101_matpedidoitem"]:$this->m101_matpedidoitem);
     }else{
       $this->m101_sequencial = ($this->m101_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m101_sequencial"]:$this->m101_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m101_sequencial){ 
      $this->atualizacampos();
     if($this->m101_matanulitem == null ){ 
       $this->erro_sql = " Campo Ítem nao Informado.";
       $this->erro_campo = "m101_matanulitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m101_matpedidoitem == null ){ 
       $this->erro_sql = " Campo Pedido nao Informado.";
       $this->erro_campo = "m101_matpedidoitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m101_sequencial == "" || $m101_sequencial == null ){
       $result = db_query("select nextval('matanulitempedido_m101_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matanulitempedido_m101_sequencial_seq do campo: m101_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m101_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matanulitempedido_m101_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m101_sequencial)){
         $this->erro_sql = " Campo m101_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m101_sequencial = $m101_sequencial; 
       }
     }
     if(($this->m101_sequencial == null) || ($this->m101_sequencial == "") ){ 
       $this->erro_sql = " Campo m101_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matanulitempedido(
                                       m101_sequencial 
                                      ,m101_matanulitem 
                                      ,m101_matpedidoitem 
                       )
                values (
                                $this->m101_sequencial 
                               ,$this->m101_matanulitem 
                               ,$this->m101_matpedidoitem 
                      )";
     $result = db_query($sql);      
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "matanulitempedido ($this->m101_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "matanulitempedido já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "matanulitempedido ($this->m101_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m101_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m101_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15251,'$this->m101_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2688,15251,'','".AddSlashes(pg_result($resaco,0,'m101_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2688,15252,'','".AddSlashes(pg_result($resaco,0,'m101_matanulitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2688,15253,'','".AddSlashes(pg_result($resaco,0,'m101_matpedidoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m101_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update matanulitempedido set ";
     $virgula = "";
     if(trim($this->m101_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m101_sequencial"])){ 
       $sql  .= $virgula." m101_sequencial = $this->m101_sequencial ";
       $virgula = ",";
       if(trim($this->m101_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "m101_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m101_matanulitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m101_matanulitem"])){ 
       $sql  .= $virgula." m101_matanulitem = $this->m101_matanulitem ";
       $virgula = ",";
       if(trim($this->m101_matanulitem) == null ){ 
         $this->erro_sql = " Campo Ítem nao Informado.";
         $this->erro_campo = "m101_matanulitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m101_matpedidoitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m101_matpedidoitem"])){ 
       $sql  .= $virgula." m101_matpedidoitem = $this->m101_matpedidoitem ";
       $virgula = ",";
       if(trim($this->m101_matpedidoitem) == null ){ 
         $this->erro_sql = " Campo Pedido nao Informado.";
         $this->erro_campo = "m101_matpedidoitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m101_sequencial!=null){
       $sql .= " m101_sequencial = $this->m101_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m101_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15251,'$this->m101_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m101_sequencial"]) || $this->m101_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2688,15251,'".AddSlashes(pg_result($resaco,$conresaco,'m101_sequencial'))."','$this->m101_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m101_matanulitem"]) || $this->m101_matanulitem != "")
           $resac = db_query("insert into db_acount values($acount,2688,15252,'".AddSlashes(pg_result($resaco,$conresaco,'m101_matanulitem'))."','$this->m101_matanulitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m101_matpedidoitem"]) || $this->m101_matpedidoitem != "")
           $resac = db_query("insert into db_acount values($acount,2688,15253,'".AddSlashes(pg_result($resaco,$conresaco,'m101_matpedidoitem'))."','$this->m101_matpedidoitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matanulitempedido nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m101_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matanulitempedido nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m101_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m101_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m101_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m101_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15251,'$m101_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2688,15251,'','".AddSlashes(pg_result($resaco,$iresaco,'m101_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2688,15252,'','".AddSlashes(pg_result($resaco,$iresaco,'m101_matanulitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2688,15253,'','".AddSlashes(pg_result($resaco,$iresaco,'m101_matpedidoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matanulitempedido
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m101_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m101_sequencial = $m101_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matanulitempedido nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m101_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matanulitempedido nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m101_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m101_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:matanulitempedido";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m101_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matanulitempedido ";
     $sql .= "      inner join matpedidoitem  on  matpedidoitem.m98_sequencial = matanulitempedido.m101_matpedidoitem";
     $sql .= "      inner join matanulitem  on  matanulitem.m103_codigo = matanulitempedido.m101_matanulitem";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matpedidoitem.m98_matmater";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matpedidoitem.m98_matunid";
     $sql .= "      inner join matpedido  on  matpedido.m97_sequencial = matpedidoitem.m98_matpedido";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matanulitem.m103_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($m101_sequencial!=null ){
         $sql2 .= " where matanulitempedido.m101_sequencial = $m101_sequencial "; 
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
   function sql_query_file ( $m101_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matanulitempedido ";
     $sql2 = "";
     if($dbwhere==""){
       if($m101_sequencial!=null ){
         $sql2 .= " where matanulitempedido.m101_sequencial = $m101_sequencial "; 
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