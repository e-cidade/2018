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

//MODULO: vacinas
//CLASSE DA ENTIDADE vac_arquivopnireg
class cl_vac_arquivopnireg { 
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
   var $vc28_i_codigo = 0; 
   var $vc28_i_arquivopni = 0; 
   var $vc28_i_aplicalote = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 vc28_i_codigo = int4 = Código 
                 vc28_i_arquivopni = int4 = Arquivo PNI 
                 vc28_i_aplicalote = int4 = Aplica Lote 
                 ";
   //funcao construtor da classe 
   function cl_vac_arquivopnireg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vac_arquivopnireg"); 
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
       $this->vc28_i_codigo = ($this->vc28_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc28_i_codigo"]:$this->vc28_i_codigo);
       $this->vc28_i_arquivopni = ($this->vc28_i_arquivopni == ""?@$GLOBALS["HTTP_POST_VARS"]["vc28_i_arquivopni"]:$this->vc28_i_arquivopni);
       $this->vc28_i_aplicalote = ($this->vc28_i_aplicalote == ""?@$GLOBALS["HTTP_POST_VARS"]["vc28_i_aplicalote"]:$this->vc28_i_aplicalote);
     }else{
       $this->vc28_i_codigo = ($this->vc28_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc28_i_codigo"]:$this->vc28_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($vc28_i_codigo){ 
      $this->atualizacampos();
     if($this->vc28_i_arquivopni == null ){ 
       $this->erro_sql = " Campo Arquivo PNI nao Informado.";
       $this->erro_campo = "vc28_i_arquivopni";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc28_i_aplicalote == null ){ 
       $this->erro_sql = " Campo Aplica Lote nao Informado.";
       $this->erro_campo = "vc28_i_aplicalote";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($vc28_i_codigo == "" || $vc28_i_codigo == null ){
       $result = db_query("select nextval('vac_arquivopnireg_vc28_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vac_arquivopnireg_vc28_i_codigo_seq do campo: vc28_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->vc28_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vac_arquivopnireg_vc28_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $vc28_i_codigo)){
         $this->erro_sql = " Campo vc28_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->vc28_i_codigo = $vc28_i_codigo; 
       }
     }
     if(($this->vc28_i_codigo == null) || ($this->vc28_i_codigo == "") ){ 
       $this->erro_sql = " Campo vc28_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vac_arquivopnireg(
                                       vc28_i_codigo 
                                      ,vc28_i_arquivopni 
                                      ,vc28_i_aplicalote 
                       )
                values (
                                $this->vc28_i_codigo 
                               ,$this->vc28_i_arquivopni 
                               ,$this->vc28_i_aplicalote 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registro do Arquivo PNI ($this->vc28_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registro do Arquivo PNI já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registro do Arquivo PNI ($this->vc28_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc28_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->vc28_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17600,'$this->vc28_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3108,17600,'','".AddSlashes(pg_result($resaco,0,'vc28_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3108,17601,'','".AddSlashes(pg_result($resaco,0,'vc28_i_arquivopni'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3108,17602,'','".AddSlashes(pg_result($resaco,0,'vc28_i_aplicalote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($vc28_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update vac_arquivopnireg set ";
     $virgula = "";
     if(trim($this->vc28_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc28_i_codigo"])){ 
       $sql  .= $virgula." vc28_i_codigo = $this->vc28_i_codigo ";
       $virgula = ",";
       if(trim($this->vc28_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "vc28_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc28_i_arquivopni)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc28_i_arquivopni"])){ 
       $sql  .= $virgula." vc28_i_arquivopni = $this->vc28_i_arquivopni ";
       $virgula = ",";
       if(trim($this->vc28_i_arquivopni) == null ){ 
         $this->erro_sql = " Campo Arquivo PNI nao Informado.";
         $this->erro_campo = "vc28_i_arquivopni";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc28_i_aplicalote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc28_i_aplicalote"])){ 
       $sql  .= $virgula." vc28_i_aplicalote = $this->vc28_i_aplicalote ";
       $virgula = ",";
       if(trim($this->vc28_i_aplicalote) == null ){ 
         $this->erro_sql = " Campo Aplica Lote nao Informado.";
         $this->erro_campo = "vc28_i_aplicalote";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($vc28_i_codigo!=null){
       $sql .= " vc28_i_codigo = $this->vc28_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->vc28_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17600,'$this->vc28_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc28_i_codigo"]) || $this->vc28_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3108,17600,'".AddSlashes(pg_result($resaco,$conresaco,'vc28_i_codigo'))."','$this->vc28_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc28_i_arquivopni"]) || $this->vc28_i_arquivopni != "")
           $resac = db_query("insert into db_acount values($acount,3108,17601,'".AddSlashes(pg_result($resaco,$conresaco,'vc28_i_arquivopni'))."','$this->vc28_i_arquivopni',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc28_i_aplicalote"]) || $this->vc28_i_aplicalote != "")
           $resac = db_query("insert into db_acount values($acount,3108,17602,'".AddSlashes(pg_result($resaco,$conresaco,'vc28_i_aplicalote'))."','$this->vc28_i_aplicalote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro do Arquivo PNI nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc28_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro do Arquivo PNI nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc28_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc28_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($vc28_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($vc28_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17600,'$vc28_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3108,17600,'','".AddSlashes(pg_result($resaco,$iresaco,'vc28_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3108,17601,'','".AddSlashes(pg_result($resaco,$iresaco,'vc28_i_arquivopni'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3108,17602,'','".AddSlashes(pg_result($resaco,$iresaco,'vc28_i_aplicalote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vac_arquivopnireg
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($vc28_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " vc28_i_codigo = $vc28_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro do Arquivo PNI nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$vc28_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro do Arquivo PNI nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$vc28_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$vc28_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:vac_arquivopnireg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $vc28_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_arquivopnireg ";
     $sql .= "      inner join vac_aplicalote  on  vac_aplicalote.vc17_i_codigo = vac_arquivopnireg.vc28_i_aplicalote";
     $sql .= "      inner join vac_arquivopni  on  vac_arquivopni.vc27_i_codigo = vac_arquivopnireg.vc28_i_arquivopni";
     $sql .= "      inner join matestoqueitemlote  on  matestoqueitemlote.m77_sequencial = vac_aplicalote.vc17_i_matetoqueitemlote";
     $sql .= "      inner join vac_sala  on  vac_sala.vc01_i_codigo = vac_aplicalote.vc17_i_sala";
     $sql .= "      inner join vac_aplica  on  vac_aplica.vc16_i_codigo = vac_aplicalote.vc17_i_aplica";
     $sql2 = "";
     if($dbwhere==""){
       if($vc28_i_codigo!=null ){
         $sql2 .= " where vac_arquivopnireg.vc28_i_codigo = $vc28_i_codigo "; 
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
   function sql_query_file ( $vc28_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_arquivopnireg ";
     $sql2 = "";
     if($dbwhere==""){
       if($vc28_i_codigo!=null ){
         $sql2 .= " where vac_arquivopnireg.vc28_i_codigo = $vc28_i_codigo "; 
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