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
//CLASSE DA ENTIDADE matmaterprecomedioini
class cl_matmaterprecomedioini { 
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
   var $m88_sequencial = 0; 
   var $m88_matestoqueini = 0; 
   var $m88_matmaterprecomedio = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m88_sequencial = int4 = Código Sequencial 
                 m88_matestoqueini = int4 = Código da movimentacao 
                 m88_matmaterprecomedio = int4 = Código do ajuste preço médio 
                 ";
   //funcao construtor da classe 
   function cl_matmaterprecomedioini() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matmaterprecomedioini"); 
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
       $this->m88_sequencial = ($this->m88_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m88_sequencial"]:$this->m88_sequencial);
       $this->m88_matestoqueini = ($this->m88_matestoqueini == ""?@$GLOBALS["HTTP_POST_VARS"]["m88_matestoqueini"]:$this->m88_matestoqueini);
       $this->m88_matmaterprecomedio = ($this->m88_matmaterprecomedio == ""?@$GLOBALS["HTTP_POST_VARS"]["m88_matmaterprecomedio"]:$this->m88_matmaterprecomedio);
     }else{
       $this->m88_sequencial = ($this->m88_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m88_sequencial"]:$this->m88_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m88_sequencial){ 
      $this->atualizacampos();
     if($this->m88_matestoqueini == null ){ 
       $this->erro_sql = " Campo Código da movimentacao nao Informado.";
       $this->erro_campo = "m88_matestoqueini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m88_matmaterprecomedio == null ){ 
       $this->erro_sql = " Campo Código do ajuste preço médio nao Informado.";
       $this->erro_campo = "m88_matmaterprecomedio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m88_sequencial == "" || $m88_sequencial == null ){
       $result = db_query("select nextval('matmaterprecomedioini_m88_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matmaterprecomedioini_m88_sequencial_seq do campo: m88_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m88_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matmaterprecomedioini_m88_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m88_sequencial)){
         $this->erro_sql = " Campo m88_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m88_sequencial = $m88_sequencial; 
       }
     }
     if(($this->m88_sequencial == null) || ($this->m88_sequencial == "") ){ 
       $this->erro_sql = " Campo m88_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matmaterprecomedioini(
                                       m88_sequencial 
                                      ,m88_matestoqueini 
                                      ,m88_matmaterprecomedio 
                       )
                values (
                                $this->m88_sequencial 
                               ,$this->m88_matestoqueini 
                               ,$this->m88_matmaterprecomedio 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ajuste preço medio ($this->m88_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ajuste preço medio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ajuste preço medio ($this->m88_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m88_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m88_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17942,'$this->m88_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3171,17942,'','".AddSlashes(pg_result($resaco,0,'m88_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3171,17944,'','".AddSlashes(pg_result($resaco,0,'m88_matestoqueini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3171,17945,'','".AddSlashes(pg_result($resaco,0,'m88_matmaterprecomedio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m88_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update matmaterprecomedioini set ";
     $virgula = "";
     if(trim($this->m88_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m88_sequencial"])){ 
       $sql  .= $virgula." m88_sequencial = $this->m88_sequencial ";
       $virgula = ",";
       if(trim($this->m88_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "m88_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m88_matestoqueini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m88_matestoqueini"])){ 
       $sql  .= $virgula." m88_matestoqueini = $this->m88_matestoqueini ";
       $virgula = ",";
       if(trim($this->m88_matestoqueini) == null ){ 
         $this->erro_sql = " Campo Código da movimentacao nao Informado.";
         $this->erro_campo = "m88_matestoqueini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m88_matmaterprecomedio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m88_matmaterprecomedio"])){ 
       $sql  .= $virgula." m88_matmaterprecomedio = $this->m88_matmaterprecomedio ";
       $virgula = ",";
       if(trim($this->m88_matmaterprecomedio) == null ){ 
         $this->erro_sql = " Campo Código do ajuste preço médio nao Informado.";
         $this->erro_campo = "m88_matmaterprecomedio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m88_sequencial!=null){
       $sql .= " m88_sequencial = $this->m88_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m88_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17942,'$this->m88_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m88_sequencial"]) || $this->m88_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3171,17942,'".AddSlashes(pg_result($resaco,$conresaco,'m88_sequencial'))."','$this->m88_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m88_matestoqueini"]) || $this->m88_matestoqueini != "")
           $resac = db_query("insert into db_acount values($acount,3171,17944,'".AddSlashes(pg_result($resaco,$conresaco,'m88_matestoqueini'))."','$this->m88_matestoqueini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m88_matmaterprecomedio"]) || $this->m88_matmaterprecomedio != "")
           $resac = db_query("insert into db_acount values($acount,3171,17945,'".AddSlashes(pg_result($resaco,$conresaco,'m88_matmaterprecomedio'))."','$this->m88_matmaterprecomedio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ajuste preço medio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m88_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ajuste preço medio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m88_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m88_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m88_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m88_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17942,'$m88_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3171,17942,'','".AddSlashes(pg_result($resaco,$iresaco,'m88_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3171,17944,'','".AddSlashes(pg_result($resaco,$iresaco,'m88_matestoqueini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3171,17945,'','".AddSlashes(pg_result($resaco,$iresaco,'m88_matmaterprecomedio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matmaterprecomedioini
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m88_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m88_sequencial = $m88_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ajuste preço medio nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m88_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ajuste preço medio nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m88_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m88_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:matmaterprecomedioini";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m88_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matmaterprecomedioini ";
     $sql .= "      inner join matestoqueini  on  matestoqueini.m80_codigo = matmaterprecomedioini.m88_matestoqueini";
     $sql .= "      inner join matmaterprecomedio  on  matmaterprecomedio.m85_sequencial = matmaterprecomedioini.m88_matmaterprecomedio";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matestoqueini.m80_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoqueini.m80_coddepto";
     $sql .= "      inner join matestoquetipo  on  matestoquetipo.m81_codtipo = matestoqueini.m80_codtipo";
     $sql .= "      inner join db_config  on  db_config.codigo = matmaterprecomedio.m85_instit";
     $sql .= "      inner join matmater  as a on   a.m60_codmater = matmaterprecomedio.m85_matmater";
     $sql .= "      inner join matestoqueinimei  on  matestoqueinimei.m82_codigo = matmaterprecomedio.m85_matestoqueinimei";
     $sql2 = "";
     if($dbwhere==""){
       if($m88_sequencial!=null ){
         $sql2 .= " where matmaterprecomedioini.m88_sequencial = $m88_sequencial "; 
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
   function sql_query_file ( $m88_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matmaterprecomedioini ";
     $sql2 = "";
     if($dbwhere==""){
       if($m88_sequencial!=null ){
         $sql2 .= " where matmaterprecomedioini.m88_sequencial = $m88_sequencial "; 
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