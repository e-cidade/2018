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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_relatfiltros
class cl_db_relatfiltros { 
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
   var $db94_codigo = 0; 
   var $db94_codrel = 0; 
   var $db94_codcam = 0; 
   var $db94_valini = null; 
   var $db94_valfim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db94_codigo = int8 = Código do filtro 
                 db94_codrel = int8 = Código do relatório 
                 db94_codcam = int4 = Código 
                 db94_valini = varchar(40) = Valor inicial 
                 db94_valfim = varchar(40) = Valor final 
                 ";
   //funcao construtor da classe 
   function cl_db_relatfiltros() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_relatfiltros"); 
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
       $this->db94_codigo = ($this->db94_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db94_codigo"]:$this->db94_codigo);
       $this->db94_codrel = ($this->db94_codrel == ""?@$GLOBALS["HTTP_POST_VARS"]["db94_codrel"]:$this->db94_codrel);
       $this->db94_codcam = ($this->db94_codcam == ""?@$GLOBALS["HTTP_POST_VARS"]["db94_codcam"]:$this->db94_codcam);
       $this->db94_valini = ($this->db94_valini == ""?@$GLOBALS["HTTP_POST_VARS"]["db94_valini"]:$this->db94_valini);
       $this->db94_valfim = ($this->db94_valfim == ""?@$GLOBALS["HTTP_POST_VARS"]["db94_valfim"]:$this->db94_valfim);
     }else{
       $this->db94_codigo = ($this->db94_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db94_codigo"]:$this->db94_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($db94_codigo){ 
      $this->atualizacampos();
     if($this->db94_codrel == null ){ 
       $this->erro_sql = " Campo Código do relatório nao Informado.";
       $this->erro_campo = "db94_codrel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db94_codcam == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "db94_codcam";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db94_codigo == "" || $db94_codigo == null ){
       $result = db_query("select nextval('db_relatfiltros_db94_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_relatfiltros_db94_codigo_seq do campo: db94_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db94_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_relatfiltros_db94_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $db94_codigo)){
         $this->erro_sql = " Campo db94_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db94_codigo = $db94_codigo; 
       }
     }
     if(($this->db94_codigo == null) || ($this->db94_codigo == "") ){ 
       $this->erro_sql = " Campo db94_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_relatfiltros(
                                       db94_codigo 
                                      ,db94_codrel 
                                      ,db94_codcam 
                                      ,db94_valini 
                                      ,db94_valfim 
                       )
                values (
                                $this->db94_codigo 
                               ,$this->db94_codrel 
                               ,$this->db94_codcam 
                               ,'$this->db94_valini' 
                               ,'$this->db94_valfim' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Filtros para o relatório ($this->db94_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Filtros para o relatório já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Filtros para o relatório ($this->db94_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db94_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db94_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8287,'$this->db94_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1398,8287,'','".AddSlashes(pg_result($resaco,0,'db94_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1398,8288,'','".AddSlashes(pg_result($resaco,0,'db94_codrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1398,8289,'','".AddSlashes(pg_result($resaco,0,'db94_codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1398,8290,'','".AddSlashes(pg_result($resaco,0,'db94_valini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1398,8291,'','".AddSlashes(pg_result($resaco,0,'db94_valfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db94_codigo=null) { 
      $this->atualizacampos();
     $sql = " update db_relatfiltros set ";
     $virgula = "";
     if(trim($this->db94_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db94_codigo"])){ 
       $sql  .= $virgula." db94_codigo = $this->db94_codigo ";
       $virgula = ",";
       if(trim($this->db94_codigo) == null ){ 
         $this->erro_sql = " Campo Código do filtro nao Informado.";
         $this->erro_campo = "db94_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db94_codrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db94_codrel"])){ 
       $sql  .= $virgula." db94_codrel = $this->db94_codrel ";
       $virgula = ",";
       if(trim($this->db94_codrel) == null ){ 
         $this->erro_sql = " Campo Código do relatório nao Informado.";
         $this->erro_campo = "db94_codrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db94_codcam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db94_codcam"])){ 
       $sql  .= $virgula." db94_codcam = $this->db94_codcam ";
       $virgula = ",";
       if(trim($this->db94_codcam) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "db94_codcam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db94_valini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db94_valini"])){ 
       $sql  .= $virgula." db94_valini = '$this->db94_valini' ";
       $virgula = ",";
     }
     if(trim($this->db94_valfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db94_valfim"])){ 
       $sql  .= $virgula." db94_valfim = '$this->db94_valfim' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db94_codigo!=null){
       $sql .= " db94_codigo = $this->db94_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db94_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8287,'$this->db94_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db94_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1398,8287,'".AddSlashes(pg_result($resaco,$conresaco,'db94_codigo'))."','$this->db94_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db94_codrel"]))
           $resac = db_query("insert into db_acount values($acount,1398,8288,'".AddSlashes(pg_result($resaco,$conresaco,'db94_codrel'))."','$this->db94_codrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db94_codcam"]))
           $resac = db_query("insert into db_acount values($acount,1398,8289,'".AddSlashes(pg_result($resaco,$conresaco,'db94_codcam'))."','$this->db94_codcam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db94_valini"]))
           $resac = db_query("insert into db_acount values($acount,1398,8290,'".AddSlashes(pg_result($resaco,$conresaco,'db94_valini'))."','$this->db94_valini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db94_valfim"]))
           $resac = db_query("insert into db_acount values($acount,1398,8291,'".AddSlashes(pg_result($resaco,$conresaco,'db94_valfim'))."','$this->db94_valfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Filtros para o relatório nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db94_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Filtros para o relatório nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db94_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db94_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db94_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db94_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8287,'$db94_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1398,8287,'','".AddSlashes(pg_result($resaco,$iresaco,'db94_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1398,8288,'','".AddSlashes(pg_result($resaco,$iresaco,'db94_codrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1398,8289,'','".AddSlashes(pg_result($resaco,$iresaco,'db94_codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1398,8290,'','".AddSlashes(pg_result($resaco,$iresaco,'db94_valini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1398,8291,'','".AddSlashes(pg_result($resaco,$iresaco,'db94_valfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_relatfiltros
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db94_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db94_codigo = $db94_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Filtros para o relatório nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db94_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Filtros para o relatório nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db94_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db94_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_relatfiltros";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db94_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_relatfiltros ";
     $sql .= "      inner join db_syscampo  on  db_syscampo.codcam = db_relatfiltros.db94_codcam";
     $sql .= "      inner join db_relat  on  db_relat.db91_codrel = db_relatfiltros.db94_codrel";
     $sql2 = "";
     if($dbwhere==""){
       if($db94_codigo!=null ){
         $sql2 .= " where db_relatfiltros.db94_codigo = $db94_codigo "; 
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
   function sql_query_file ( $db94_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_relatfiltros ";
     $sql2 = "";
     if($dbwhere==""){
       if($db94_codigo!=null ){
         $sql2 .= " where db_relatfiltros.db94_codigo = $db94_codigo "; 
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