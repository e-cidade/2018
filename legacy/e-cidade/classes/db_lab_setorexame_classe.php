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

//MODULO: laboratorio
//CLASSE DA ENTIDADE lab_setorexame
class cl_lab_setorexame { 
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
   var $la09_i_codigo = 0; 
   var $la09_i_labsetor = 0; 
   var $la09_i_exame = 0; 
   var $la09_i_ativo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la09_i_codigo = int4 = C�digo 
                 la09_i_labsetor = int4 = Setor 
                 la09_i_exame = int4 = Exame 
                 la09_i_ativo = int4 = Situa��o 
                 ";
   //funcao construtor da classe 
   function cl_lab_setorexame() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_setorexame"); 
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
       $this->la09_i_codigo = ($this->la09_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la09_i_codigo"]:$this->la09_i_codigo);
       $this->la09_i_labsetor = ($this->la09_i_labsetor == ""?@$GLOBALS["HTTP_POST_VARS"]["la09_i_labsetor"]:$this->la09_i_labsetor);
       $this->la09_i_exame = ($this->la09_i_exame == ""?@$GLOBALS["HTTP_POST_VARS"]["la09_i_exame"]:$this->la09_i_exame);
       $this->la09_i_ativo = ($this->la09_i_ativo == ""?@$GLOBALS["HTTP_POST_VARS"]["la09_i_ativo"]:$this->la09_i_ativo);
     }else{
       $this->la09_i_codigo = ($this->la09_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la09_i_codigo"]:$this->la09_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la09_i_codigo){ 
      $this->atualizacampos();
     if($this->la09_i_labsetor == null ){ 
       $this->erro_sql = " Campo Setor nao Informado.";
       $this->erro_campo = "la09_i_labsetor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la09_i_exame == null ){ 
       $this->erro_sql = " Campo Exame nao Informado.";
       $this->erro_campo = "la09_i_exame";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la09_i_ativo == null ){ 
       $this->erro_sql = " Campo Situa��o nao Informado.";
       $this->erro_campo = "la09_i_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la09_i_codigo == "" || $la09_i_codigo == null ){
       $result = db_query("select nextval('lab_setorexame_la09_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_setorexame_la09_i_codigo_seq do campo: la09_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la09_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_setorexame_la09_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la09_i_codigo)){
         $this->erro_sql = " Campo la09_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la09_i_codigo = $la09_i_codigo; 
       }
     }
     if(($this->la09_i_codigo == null) || ($this->la09_i_codigo == "") ){ 
       $this->erro_sql = " Campo la09_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_setorexame(
                                       la09_i_codigo 
                                      ,la09_i_labsetor 
                                      ,la09_i_exame 
                                      ,la09_i_ativo 
                       )
                values (
                                $this->la09_i_codigo 
                               ,$this->la09_i_labsetor 
                               ,$this->la09_i_exame 
                               ,$this->la09_i_ativo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "lab_setorexame ($this->la09_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "lab_setorexame j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "lab_setorexame ($this->la09_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la09_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->la09_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15749,'$this->la09_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2759,15749,'','".AddSlashes(pg_result($resaco,0,'la09_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2759,15750,'','".AddSlashes(pg_result($resaco,0,'la09_i_labsetor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2759,15751,'','".AddSlashes(pg_result($resaco,0,'la09_i_exame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2759,17968,'','".AddSlashes(pg_result($resaco,0,'la09_i_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la09_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lab_setorexame set ";
     $virgula = "";
     if(trim($this->la09_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la09_i_codigo"])){ 
       $sql  .= $virgula." la09_i_codigo = $this->la09_i_codigo ";
       $virgula = ",";
       if(trim($this->la09_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "la09_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la09_i_labsetor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la09_i_labsetor"])){ 
       $sql  .= $virgula." la09_i_labsetor = $this->la09_i_labsetor ";
       $virgula = ",";
       if(trim($this->la09_i_labsetor) == null ){ 
         $this->erro_sql = " Campo Setor nao Informado.";
         $this->erro_campo = "la09_i_labsetor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la09_i_exame)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la09_i_exame"])){ 
       $sql  .= $virgula." la09_i_exame = $this->la09_i_exame ";
       $virgula = ",";
       if(trim($this->la09_i_exame) == null ){ 
         $this->erro_sql = " Campo Exame nao Informado.";
         $this->erro_campo = "la09_i_exame";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la09_i_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la09_i_ativo"])){ 
       $sql  .= $virgula." la09_i_ativo = $this->la09_i_ativo ";
       $virgula = ",";
       if(trim($this->la09_i_ativo) == null ){ 
         $this->erro_sql = " Campo Situa��o nao Informado.";
         $this->erro_campo = "la09_i_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($la09_i_codigo!=null){
       $sql .= " la09_i_codigo = $this->la09_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->la09_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15749,'$this->la09_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la09_i_codigo"]) || $this->la09_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2759,15749,'".AddSlashes(pg_result($resaco,$conresaco,'la09_i_codigo'))."','$this->la09_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la09_i_labsetor"]) || $this->la09_i_labsetor != "")
           $resac = db_query("insert into db_acount values($acount,2759,15750,'".AddSlashes(pg_result($resaco,$conresaco,'la09_i_labsetor'))."','$this->la09_i_labsetor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la09_i_exame"]) || $this->la09_i_exame != "")
           $resac = db_query("insert into db_acount values($acount,2759,15751,'".AddSlashes(pg_result($resaco,$conresaco,'la09_i_exame'))."','$this->la09_i_exame',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la09_i_ativo"]) || $this->la09_i_ativo != "")
           $resac = db_query("insert into db_acount values($acount,2759,17968,'".AddSlashes(pg_result($resaco,$conresaco,'la09_i_ativo'))."','$this->la09_i_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_setorexame nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la09_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_setorexame nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la09_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la09_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la09_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($la09_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15749,'$la09_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2759,15749,'','".AddSlashes(pg_result($resaco,$iresaco,'la09_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2759,15750,'','".AddSlashes(pg_result($resaco,$iresaco,'la09_i_labsetor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2759,15751,'','".AddSlashes(pg_result($resaco,$iresaco,'la09_i_exame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2759,17968,'','".AddSlashes(pg_result($resaco,$iresaco,'la09_i_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lab_setorexame
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la09_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la09_i_codigo = $la09_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_setorexame nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la09_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_setorexame nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la09_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la09_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_setorexame";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la09_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_setorexame ";
     $sql .= "      inner join lab_exame  on  lab_exame.la08_i_codigo = lab_setorexame.la09_i_exame";
     $sql .= "      inner join lab_labsetor  on  lab_labsetor.la24_i_codigo = lab_setorexame.la09_i_labsetor";
     $sql .= "      inner join lab_laboratorio  on  lab_laboratorio.la02_i_codigo = lab_labsetor.la24_i_laboratorio";
     $sql .= "      inner join lab_labresp  on  lab_labresp.la06_i_codigo = lab_labsetor.la24_i_resp";
     $sql .= "      inner join lab_setor  on  lab_setor.la23_i_codigo = lab_labsetor.la24_i_setor";
     $sql2 = "";
     if($dbwhere==""){
       if($la09_i_codigo!=null ){
         $sql2 .= " where lab_setorexame.la09_i_codigo = $la09_i_codigo "; 
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
   function sql_query_file ( $la09_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_setorexame ";
     $sql2 = "";
     if($dbwhere==""){
       if($la09_i_codigo!=null ){
         $sql2 .= " where lab_setorexame.la09_i_codigo = $la09_i_codigo "; 
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
   function sql_query_exameproced ( $la09_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_setorexame ";
     $sql .= "      inner join lab_exame  on  lab_exame.la08_i_codigo = lab_setorexame.la09_i_exame";
     $sql .= "      inner join lab_exameproced  on  lab_exameproced.la53_i_exame = lab_exame.la08_i_codigo";
     $sql .= "      inner join sau_procedimento  on  sau_procedimento.sd63_i_codigo = lab_exameproced.la53_i_procedimento";
     $sql .= "      inner join lab_labsetor  on  lab_labsetor.la24_i_codigo = lab_setorexame.la09_i_labsetor";
     $sql .= "      inner join lab_laboratorio  on  lab_laboratorio.la02_i_codigo = lab_labsetor.la24_i_laboratorio";
     $sql .= "      inner join lab_setor  on  lab_setor.la23_i_codigo = lab_labsetor.la24_i_setor";
     $sql2 = "";
     if($dbwhere==""){
       if($la09_i_codigo!=null ){
         $sql2 .= " where lab_setorexame.la09_i_codigo = $la09_i_codigo "; 
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
  function sql_query_setorexame ($la09_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") { 

     $sql = "select ";
     if ($campos != "*") {
       
       $campos_sql = split("#", $campos);
       $virgula    = "";
       for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
         $sql     .= $virgula.$campos_sql[$i];
         $virgula  = ",";
       
       }
     
     } else {
       $sql .= $campos;
     }
     $sql .= " from lab_setorexame ";
     $sql .= "      inner join lab_exame  on  lab_exame.la08_i_codigo = lab_setorexame.la09_i_exame";
     $sql .= "      inner join lab_labsetor  on  lab_labsetor.la24_i_codigo = lab_setorexame.la09_i_labsetor";
     $sql .= "      inner join lab_laboratorio  on  lab_laboratorio.la02_i_codigo = lab_labsetor.la24_i_laboratorio";
     $sql .= "      inner join lab_setor  on  lab_setor.la23_i_codigo = lab_labsetor.la24_i_setor";
     $sql2 = "";
     if ($dbwhere == "") {
       
       if ($la09_i_codigo != null) {
         $sql2 .= " where lab_setorexame.la09_i_codigo = $la09_i_codigo "; 
       } 
     
     } elseif ($dbwhere != "") {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if ($ordem != null) {
     
       $sql        .= " order by ";
       $campos_sql  = split("#",$ordem);
       $virgula     = "";
       for ($i = 0; $i < sizeof($campos_sql); $i++) {
       
         $sql     .= $virgula.$campos_sql[$i];
         $virgula  = ",";
       
       }
     
     }
     return $sql;
  }

  
}
?>