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

//MODULO: fiscal
//CLASSE DA ENTIDADE tiafprazo
class cl_tiafprazo { 
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
   var $y96_codigo = 0; 
   var $y96_codtiaf = 0; 
   var $y96_prazo_dia = null; 
   var $y96_prazo_mes = null; 
   var $y96_prazo_ano = null; 
   var $y96_prazo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y96_codigo = int8 = Codigo do prazo 
                 y96_codtiaf = int4 = Código Tiaf 
                 y96_prazo = date = Data do prazo 
                 ";
   //funcao construtor da classe 
   function cl_tiafprazo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tiafprazo"); 
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
       $this->y96_codigo = ($this->y96_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["y96_codigo"]:$this->y96_codigo);
       $this->y96_codtiaf = ($this->y96_codtiaf == ""?@$GLOBALS["HTTP_POST_VARS"]["y96_codtiaf"]:$this->y96_codtiaf);
       if($this->y96_prazo == ""){
         $this->y96_prazo_dia = ($this->y96_prazo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y96_prazo_dia"]:$this->y96_prazo_dia);
         $this->y96_prazo_mes = ($this->y96_prazo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y96_prazo_mes"]:$this->y96_prazo_mes);
         $this->y96_prazo_ano = ($this->y96_prazo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y96_prazo_ano"]:$this->y96_prazo_ano);
         if($this->y96_prazo_dia != ""){
            $this->y96_prazo = $this->y96_prazo_ano."-".$this->y96_prazo_mes."-".$this->y96_prazo_dia;
         }
       }
     }else{
       $this->y96_codigo = ($this->y96_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["y96_codigo"]:$this->y96_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($y96_codigo){ 
      $this->atualizacampos();
     if($this->y96_codtiaf == null ){ 
       $this->erro_sql = " Campo Código Tiaf nao Informado.";
       $this->erro_campo = "y96_codtiaf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y96_prazo == null ){ 
       $this->erro_sql = " Campo Data do prazo nao Informado.";
       $this->erro_campo = "y96_prazo_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($y96_codigo == "" || $y96_codigo == null ){
       $result = db_query("select nextval('tiafprazo_y96_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tiafprazo_y96_codigo_seq do campo: y96_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->y96_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tiafprazo_y96_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $y96_codigo)){
         $this->erro_sql = " Campo y96_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y96_codigo = $y96_codigo; 
       }
     }
     if(($this->y96_codigo == null) || ($this->y96_codigo == "") ){ 
       $this->erro_sql = " Campo y96_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tiafprazo(
                                       y96_codigo 
                                      ,y96_codtiaf 
                                      ,y96_prazo 
                       )
                values (
                                $this->y96_codigo 
                               ,$this->y96_codtiaf 
                               ,".($this->y96_prazo == "null" || $this->y96_prazo == ""?"null":"'".$this->y96_prazo."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Prazo do Tiaf ($this->y96_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Prazo do Tiaf já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Prazo do Tiaf ($this->y96_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y96_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y96_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7353,'$this->y96_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1225,7353,'','".AddSlashes(pg_result($resaco,0,'y96_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1225,7354,'','".AddSlashes(pg_result($resaco,0,'y96_codtiaf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1225,7355,'','".AddSlashes(pg_result($resaco,0,'y96_prazo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y96_codigo=null) { 
      $this->atualizacampos();
     $sql = " update tiafprazo set ";
     $virgula = "";
     if(trim($this->y96_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y96_codigo"])){ 
       $sql  .= $virgula." y96_codigo = $this->y96_codigo ";
       $virgula = ",";
       if(trim($this->y96_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo do prazo nao Informado.";
         $this->erro_campo = "y96_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y96_codtiaf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y96_codtiaf"])){ 
       $sql  .= $virgula." y96_codtiaf = $this->y96_codtiaf ";
       $virgula = ",";
       if(trim($this->y96_codtiaf) == null ){ 
         $this->erro_sql = " Campo Código Tiaf nao Informado.";
         $this->erro_campo = "y96_codtiaf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y96_prazo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y96_prazo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y96_prazo_dia"] !="") ){ 
       $sql  .= $virgula." y96_prazo = '$this->y96_prazo' ";
       $virgula = ",";
       if(trim($this->y96_prazo) == null ){ 
         $this->erro_sql = " Campo Data do prazo nao Informado.";
         $this->erro_campo = "y96_prazo_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y96_prazo_dia"])){ 
         $sql  .= $virgula." y96_prazo = null ";
         $virgula = ",";
         if(trim($this->y96_prazo) == null ){ 
           $this->erro_sql = " Campo Data do prazo nao Informado.";
           $this->erro_campo = "y96_prazo_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($y96_codigo!=null){
       $sql .= " y96_codigo = $this->y96_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y96_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7353,'$this->y96_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y96_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1225,7353,'".AddSlashes(pg_result($resaco,$conresaco,'y96_codigo'))."','$this->y96_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y96_codtiaf"]))
           $resac = db_query("insert into db_acount values($acount,1225,7354,'".AddSlashes(pg_result($resaco,$conresaco,'y96_codtiaf'))."','$this->y96_codtiaf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y96_prazo"]))
           $resac = db_query("insert into db_acount values($acount,1225,7355,'".AddSlashes(pg_result($resaco,$conresaco,'y96_prazo'))."','$this->y96_prazo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prazo do Tiaf nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y96_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prazo do Tiaf nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y96_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y96_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y96_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y96_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7353,'$y96_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1225,7353,'','".AddSlashes(pg_result($resaco,$iresaco,'y96_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1225,7354,'','".AddSlashes(pg_result($resaco,$iresaco,'y96_codtiaf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1225,7355,'','".AddSlashes(pg_result($resaco,$iresaco,'y96_prazo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tiafprazo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y96_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y96_codigo = $y96_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prazo do Tiaf nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y96_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prazo do Tiaf nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y96_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y96_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tiafprazo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y96_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tiafprazo ";
     $sql .= "      inner join tiaf  on  tiaf.y90_codtiaf = tiafprazo.y96_codtiaf";
     $sql2 = "";
     if($dbwhere==""){
       if($y96_codigo!=null ){
         $sql2 .= " where tiafprazo.y96_codigo = $y96_codigo "; 
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
   function sql_query_file ( $y96_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tiafprazo ";
     $sql2 = "";
     if($dbwhere==""){
       if($y96_codigo!=null ){
         $sql2 .= " where tiafprazo.y96_codigo = $y96_codigo "; 
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
   function sql_queryproc ( $y96_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tiafprazo ";
     $sql .= "      inner join tiaf          on  tiaf.y90_codtiaf = tiafprazo.y96_codtiaf";
     $sql .= "      inner join tiafprazoproc on  tiafprazoproc.y97_codprazo = tiafprazo.y96_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($y96_codigo!=null ){
         $sql2 .= " where tiafprazo.y96_codigo = $y96_codigo "; 
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