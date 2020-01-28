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
//CLASSE DA ENTIDADE matpedidotransf
class cl_matpedidotransf { 
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
   var $m100_sequencial = 0; 
   var $m100_matpedidoitem = 0; 
   var $m100_matestoqueini = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m100_sequencial = int8 = Sequencial 
                 m100_matpedidoitem = int8 = Ítem 
                 m100_matestoqueini = int8 = Estoque 
                 ";
   //funcao construtor da classe 
   function cl_matpedidotransf() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matpedidotransf"); 
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
       $this->m100_sequencial = ($this->m100_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m100_sequencial"]:$this->m100_sequencial);
       $this->m100_matpedidoitem = ($this->m100_matpedidoitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m100_matpedidoitem"]:$this->m100_matpedidoitem);
       $this->m100_matestoqueini = ($this->m100_matestoqueini == ""?@$GLOBALS["HTTP_POST_VARS"]["m100_matestoqueini"]:$this->m100_matestoqueini);
     }else{
       $this->m100_sequencial = ($this->m100_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m100_sequencial"]:$this->m100_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m100_sequencial){ 
      $this->atualizacampos();
     if($this->m100_matpedidoitem == null ){ 
       $this->erro_sql = " Campo Ítem nao Informado.";
       $this->erro_campo = "m100_matpedidoitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m100_matestoqueini == null ){ 
       $this->erro_sql = " Campo Estoque nao Informado.";
       $this->erro_campo = "m100_matestoqueini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m100_sequencial == "" || $m100_sequencial == null ){
       $result = db_query("select nextval('matpedidotransf_m100_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matpedidotransf_m100_sequencial_seq do campo: m100_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m100_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matpedidotransf_m100_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m100_sequencial)){
         $this->erro_sql = " Campo m100_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m100_sequencial = $m100_sequencial; 
       }
     }
     if(($this->m100_sequencial == null) || ($this->m100_sequencial == "") ){ 
       $this->erro_sql = " Campo m100_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matpedidotransf(
                                       m100_sequencial 
                                      ,m100_matpedidoitem 
                                      ,m100_matestoqueini 
                       )
                values (
                                $this->m100_sequencial 
                               ,$this->m100_matpedidoitem 
                               ,$this->m100_matestoqueini 
                      )";
                                
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "matpedidotransf ($this->m100_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "matpedidotransf já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "matpedidotransf ($this->m100_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m100_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m100_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15248,'$this->m100_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2687,15248,'','".AddSlashes(pg_result($resaco,0,'m100_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2687,15249,'','".AddSlashes(pg_result($resaco,0,'m100_matpedidoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2687,15250,'','".AddSlashes(pg_result($resaco,0,'m100_matestoqueini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m100_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update matpedidotransf set ";
     $virgula = "";
     if(trim($this->m100_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m100_sequencial"])){ 
       $sql  .= $virgula." m100_sequencial = $this->m100_sequencial ";
       $virgula = ",";
       if(trim($this->m100_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "m100_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m100_matpedidoitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m100_matpedidoitem"])){ 
       $sql  .= $virgula." m100_matpedidoitem = $this->m100_matpedidoitem ";
       $virgula = ",";
       if(trim($this->m100_matpedidoitem) == null ){ 
         $this->erro_sql = " Campo Ítem nao Informado.";
         $this->erro_campo = "m100_matpedidoitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m100_matestoqueini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m100_matestoqueini"])){ 
       $sql  .= $virgula." m100_matestoqueini = $this->m100_matestoqueini ";
       $virgula = ",";
       if(trim($this->m100_matestoqueini) == null ){ 
         $this->erro_sql = " Campo Estoque nao Informado.";
         $this->erro_campo = "m100_matestoqueini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m100_sequencial!=null){
       $sql .= " m100_sequencial = $this->m100_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m100_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15248,'$this->m100_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m100_sequencial"]) || $this->m100_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2687,15248,'".AddSlashes(pg_result($resaco,$conresaco,'m100_sequencial'))."','$this->m100_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m100_matpedidoitem"]) || $this->m100_matpedidoitem != "")
           $resac = db_query("insert into db_acount values($acount,2687,15249,'".AddSlashes(pg_result($resaco,$conresaco,'m100_matpedidoitem'))."','$this->m100_matpedidoitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m100_matestoqueini"]) || $this->m100_matestoqueini != "")
           $resac = db_query("insert into db_acount values($acount,2687,15250,'".AddSlashes(pg_result($resaco,$conresaco,'m100_matestoqueini'))."','$this->m100_matestoqueini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matpedidotransf nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m100_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matpedidotransf nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m100_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m100_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m100_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m100_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15248,'$m100_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2687,15248,'','".AddSlashes(pg_result($resaco,$iresaco,'m100_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2687,15249,'','".AddSlashes(pg_result($resaco,$iresaco,'m100_matpedidoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2687,15250,'','".AddSlashes(pg_result($resaco,$iresaco,'m100_matestoqueini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matpedidotransf
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m100_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m100_sequencial = $m100_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matpedidotransf nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m100_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matpedidotransf nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m100_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m100_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:matpedidotransf";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m100_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matpedidotransf ";
     $sql .= "      inner join matestoqueini  on  matestoqueini.m80_codigo = matpedidotransf.m100_matestoqueini";
     $sql .= "      inner join matpedidoitem  on  matpedidoitem.m98_sequencial = matpedidotransf.m100_matpedidoitem";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matestoqueini.m80_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoqueini.m80_coddepto";
     $sql .= "      inner join matestoquetipo  on  matestoquetipo.m81_codtipo = matestoqueini.m80_codtipo";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matpedidoitem.m98_matmater";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matpedidoitem.m98_matunid";
     $sql .= "      inner join matpedido  as a on   a.m97_sequencial = matpedidoitem.m98_matpedido";
     $sql2 = "";
     if($dbwhere==""){
       if($m100_sequencial!=null ){
         $sql2 .= " where matpedidotransf.m100_sequencial = $m100_sequencial "; 
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
 function sql_query_inill ( $m100_matestoqueini=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matpedidotransf ";
     $sql .= "      inner join matpedidoitem on matpedidoitem.m98_sequencial = matpedidotransf.m100_matpedidoitem";
     $sql .= "      inner join matpedido on matpedido.m97_sequencial = matpedidoitem.m98_matpedido";
     $sql .= "      inner join db_depart a  on  a.coddepto = matpedido.m97_coddepto";
     $sql .= "      inner join db_almox  on  db_almox.m91_depto = a.coddepto";
     $sql .= "      inner join matanulitempedido  on  matanulitempedido.m101_matpedidoitem = matpedidoitem.m98_sequencial";
     $sql .= "      inner join matanulitem  on  matanulitem.m103_codigo = matanulitempedido.m101_matanulitem";     
     $sql .= "      left join matestoqueini  on  matestoqueini.m80_codigo = matpedidotransf.m100_matestoqueini";
     $sql .= "      left join db_depart   on  db_depart.coddepto = matestoqueini.m80_coddepto";
     $sql .= "      left join db_usuarios   on  db_usuarios.id_usuario = matestoqueini.m80_login";
     $sql .= "      inner join matestoqueinimei  on  matestoqueinimei.m82_matestoqueini = matestoqueini.m80_codigo";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem";
     $sql .= "      inner join matestoque  on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql .= "      left  join matestoqueinil  on  matestoqueinil.m86_matestoqueini = matestoqueini.m80_codigo";
     $sql .= "      left  join matestoqueinill  on  matestoqueinill.m87_matestoqueinil = matestoqueinil.m86_codigo";
     $sql .= "      left  join matestoqueini b on  b.m80_codigo = matestoqueinill.m87_matestoqueini";
     $sql .= "      inner  join matestoqueinimeimatpedidoitem  on  matestoqueinimeimatpedidoitem.m99_matpedidoitem = matpedidoitem.m98_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($m100_matestoqueini!=null ){
         $sql2 .= " where matpedidotransf.m100_matestoqueini = $m100_matestoqueini "; 
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
   function sql_query_file ( $m100_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matpedidotransf ";
     $sql2 = "";
     if($dbwhere==""){
       if($m100_sequencial!=null ){
         $sql2 .= " where matpedidotransf.m100_sequencial = $m100_sequencial "; 
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