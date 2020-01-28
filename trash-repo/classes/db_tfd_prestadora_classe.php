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

//MODULO: TFD
//CLASSE DA ENTIDADE tfd_prestadora
class cl_tfd_prestadora { 
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
   var $tf25_i_codigo = 0; 
   var $tf25_i_cgm = 0; 
   var $tf25_c_cnes = null; 
   var $tf25_i_destino = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tf25_i_codigo = int4 = Código 
                 tf25_i_cgm = int4 = CGM 
                 tf25_c_cnes = varchar(10) = CNES 
                 tf25_i_destino = int4 = Destino 
                 ";
   //funcao construtor da classe 
   function cl_tfd_prestadora() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tfd_prestadora"); 
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
       $this->tf25_i_codigo = ($this->tf25_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf25_i_codigo"]:$this->tf25_i_codigo);
       $this->tf25_i_cgm = ($this->tf25_i_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["tf25_i_cgm"]:$this->tf25_i_cgm);
       $this->tf25_c_cnes = ($this->tf25_c_cnes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf25_c_cnes"]:$this->tf25_c_cnes);
       $this->tf25_i_destino = ($this->tf25_i_destino == ""?@$GLOBALS["HTTP_POST_VARS"]["tf25_i_destino"]:$this->tf25_i_destino);
     }else{
       $this->tf25_i_codigo = ($this->tf25_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf25_i_codigo"]:$this->tf25_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($tf25_i_codigo){ 
      $this->atualizacampos();
     if($this->tf25_i_cgm == null ){ 
       $this->erro_sql = " Campo CGM nao Informado.";
       $this->erro_campo = "tf25_i_cgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf25_c_cnes == null ){ 
       $this->erro_sql = " Campo CNES nao Informado.";
       $this->erro_campo = "tf25_c_cnes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf25_i_destino == null ){ 
       $this->erro_sql = " Campo Destino nao Informado.";
       $this->erro_campo = "tf25_i_destino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tf25_i_codigo == "" || $tf25_i_codigo == null ){
       $result = db_query("select nextval('tfd_prestadora_tf25_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tfd_prestadora_tf25_i_codigo_seq do campo: tf25_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tf25_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tfd_prestadora_tf25_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf25_i_codigo)){
         $this->erro_sql = " Campo tf25_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf25_i_codigo = $tf25_i_codigo; 
       }
     }
     if(($this->tf25_i_codigo == null) || ($this->tf25_i_codigo == "") ){ 
       $this->erro_sql = " Campo tf25_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tfd_prestadora(
                                       tf25_i_codigo 
                                      ,tf25_i_cgm 
                                      ,tf25_c_cnes 
                                      ,tf25_i_destino 
                       )
                values (
                                $this->tf25_i_codigo 
                               ,$this->tf25_i_cgm 
                               ,'$this->tf25_c_cnes' 
                               ,$this->tf25_i_destino 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tfd_prestadora ($this->tf25_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tfd_prestadora já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tfd_prestadora ($this->tf25_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf25_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tf25_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16444,'$this->tf25_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2881,16444,'','".AddSlashes(pg_result($resaco,0,'tf25_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2881,16446,'','".AddSlashes(pg_result($resaco,0,'tf25_i_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2881,16447,'','".AddSlashes(pg_result($resaco,0,'tf25_c_cnes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2881,16445,'','".AddSlashes(pg_result($resaco,0,'tf25_i_destino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tf25_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update tfd_prestadora set ";
     $virgula = "";
     if(trim($this->tf25_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf25_i_codigo"])){ 
       $sql  .= $virgula." tf25_i_codigo = $this->tf25_i_codigo ";
       $virgula = ",";
       if(trim($this->tf25_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "tf25_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf25_i_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf25_i_cgm"])){ 
       $sql  .= $virgula." tf25_i_cgm = $this->tf25_i_cgm ";
       $virgula = ",";
       if(trim($this->tf25_i_cgm) == null ){ 
         $this->erro_sql = " Campo CGM nao Informado.";
         $this->erro_campo = "tf25_i_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf25_c_cnes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf25_c_cnes"])){ 
       $sql  .= $virgula." tf25_c_cnes = '$this->tf25_c_cnes' ";
       $virgula = ",";
       if(trim($this->tf25_c_cnes) == null ){ 
         $this->erro_sql = " Campo CNES nao Informado.";
         $this->erro_campo = "tf25_c_cnes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf25_i_destino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf25_i_destino"])){ 
       $sql  .= $virgula." tf25_i_destino = $this->tf25_i_destino ";
       $virgula = ",";
       if(trim($this->tf25_i_destino) == null ){ 
         $this->erro_sql = " Campo Destino nao Informado.";
         $this->erro_campo = "tf25_i_destino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tf25_i_codigo!=null){
       $sql .= " tf25_i_codigo = $this->tf25_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tf25_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16444,'$this->tf25_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf25_i_codigo"]) || $this->tf25_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2881,16444,'".AddSlashes(pg_result($resaco,$conresaco,'tf25_i_codigo'))."','$this->tf25_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf25_i_cgm"]) || $this->tf25_i_cgm != "")
           $resac = db_query("insert into db_acount values($acount,2881,16446,'".AddSlashes(pg_result($resaco,$conresaco,'tf25_i_cgm'))."','$this->tf25_i_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf25_c_cnes"]) || $this->tf25_c_cnes != "")
           $resac = db_query("insert into db_acount values($acount,2881,16447,'".AddSlashes(pg_result($resaco,$conresaco,'tf25_c_cnes'))."','$this->tf25_c_cnes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf25_i_destino"]) || $this->tf25_i_destino != "")
           $resac = db_query("insert into db_acount values($acount,2881,16445,'".AddSlashes(pg_result($resaco,$conresaco,'tf25_i_destino'))."','$this->tf25_i_destino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_prestadora nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf25_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_prestadora nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf25_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf25_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tf25_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tf25_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16444,'$tf25_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2881,16444,'','".AddSlashes(pg_result($resaco,$iresaco,'tf25_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2881,16446,'','".AddSlashes(pg_result($resaco,$iresaco,'tf25_i_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2881,16447,'','".AddSlashes(pg_result($resaco,$iresaco,'tf25_c_cnes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2881,16445,'','".AddSlashes(pg_result($resaco,$iresaco,'tf25_i_destino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tfd_prestadora
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tf25_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tf25_i_codigo = $tf25_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_prestadora nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf25_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_prestadora nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf25_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf25_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tfd_prestadora";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $tf25_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_prestadora ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = tfd_prestadora.tf25_i_cgm";
     $sql .= "      inner join tfd_destino  on  tfd_destino.tf03_i_codigo = tfd_prestadora.tf25_i_destino";
     $sql .= "      inner join tfd_tipodistancia  on  tfd_tipodistancia.tf24_i_codigo = tfd_destino.tf03_i_tipodistancia";
     $sql2 = "";
     if($dbwhere==""){
       if($tf25_i_codigo!=null ){
         $sql2 .= " where tfd_prestadora.tf25_i_codigo = $tf25_i_codigo "; 
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
   function sql_query_file ( $tf25_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_prestadora ";
     $sql2 = "";
     if($dbwhere==""){
       if($tf25_i_codigo!=null ){
         $sql2 .= " where tfd_prestadora.tf25_i_codigo = $tf25_i_codigo "; 
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