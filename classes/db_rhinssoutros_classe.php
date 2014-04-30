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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhinssoutros
class cl_rhinssoutros { 
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
   var $rh51_seqpes = 0; 
   var $rh51_basefo = 0; 
   var $rh51_descfo = 0; 
   var $rh51_b13fo = 0; 
   var $rh51_d13fo = 0; 
   var $rh51_ocorre = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh51_seqpes = int4 = Sequência 
                 rh51_basefo = float8 = Base INSS outra empresa 
                 rh51_descfo = float8 = Desconto INSS outra empresa 
                 rh51_b13fo = float8 = Base 13o sal INSS outra empresa 
                 rh51_d13fo = float8 = Desconto 13o sal INSS outra empresa 
                 rh51_ocorre = varchar(2) = Ocorrência 
                 ";
   //funcao construtor da classe 
   function cl_rhinssoutros() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhinssoutros"); 
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
       $this->rh51_seqpes = ($this->rh51_seqpes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh51_seqpes"]:$this->rh51_seqpes);
       $this->rh51_basefo = ($this->rh51_basefo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh51_basefo"]:$this->rh51_basefo);
       $this->rh51_descfo = ($this->rh51_descfo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh51_descfo"]:$this->rh51_descfo);
       $this->rh51_b13fo = ($this->rh51_b13fo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh51_b13fo"]:$this->rh51_b13fo);
       $this->rh51_d13fo = ($this->rh51_d13fo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh51_d13fo"]:$this->rh51_d13fo);
       $this->rh51_ocorre = ($this->rh51_ocorre == ""?@$GLOBALS["HTTP_POST_VARS"]["rh51_ocorre"]:$this->rh51_ocorre);
     }else{
       $this->rh51_seqpes = ($this->rh51_seqpes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh51_seqpes"]:$this->rh51_seqpes);
     }
   }
   // funcao para inclusao
   function incluir ($rh51_seqpes){ 
      $this->atualizacampos();
     if($this->rh51_basefo == null ){ 
       $this->rh51_basefo = "0";
     }
     if($this->rh51_descfo == null ){ 
       $this->rh51_descfo = "0";
     }
     if($this->rh51_b13fo == null ){ 
       $this->rh51_b13fo = "0";
     }
     if($this->rh51_d13fo == null ){ 
       $this->rh51_d13fo = "0";
     }
     if($this->rh51_ocorre == null ){ 
       $this->erro_sql = " Campo Ocorrência múltiplos vínculos nao Informado.";
       $this->erro_campo = "rh51_ocorre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->rh51_seqpes = $rh51_seqpes; 
     if(($this->rh51_seqpes == null) || ($this->rh51_seqpes == "") ){ 
       $this->erro_sql = " Campo rh51_seqpes nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhinssoutros(
                                       rh51_seqpes 
                                      ,rh51_basefo 
                                      ,rh51_descfo 
                                      ,rh51_b13fo 
                                      ,rh51_d13fo 
                                      ,rh51_ocorre 
                       )
                values (
                                $this->rh51_seqpes 
                               ,$this->rh51_basefo 
                               ,$this->rh51_descfo 
                               ,$this->rh51_b13fo 
                               ,$this->rh51_d13fo 
                               ,'$this->rh51_ocorre' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Inss outras empresas ($this->rh51_seqpes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Inss outras empresas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Inss outras empresas ($this->rh51_seqpes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh51_seqpes;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh51_seqpes));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8842,'$this->rh51_seqpes','I')");
       $resac = db_query("insert into db_acount values($acount,1508,8842,'','".AddSlashes(pg_result($resaco,0,'rh51_seqpes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1508,8843,'','".AddSlashes(pg_result($resaco,0,'rh51_basefo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1508,8844,'','".AddSlashes(pg_result($resaco,0,'rh51_descfo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1508,8845,'','".AddSlashes(pg_result($resaco,0,'rh51_b13fo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1508,8846,'','".AddSlashes(pg_result($resaco,0,'rh51_d13fo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1508,8871,'','".AddSlashes(pg_result($resaco,0,'rh51_ocorre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh51_seqpes=null) { 
      $this->atualizacampos();
     $sql = " update rhinssoutros set ";
     $virgula = "";
     if(trim($this->rh51_seqpes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh51_seqpes"])){ 
       $sql  .= $virgula." rh51_seqpes = $this->rh51_seqpes ";
       $virgula = ",";
       if(trim($this->rh51_seqpes) == null ){ 
         $this->erro_sql = " Campo Sequência nao Informado.";
         $this->erro_campo = "rh51_seqpes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh51_basefo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh51_basefo"])){ 
        if(trim($this->rh51_basefo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh51_basefo"])){ 
           $this->rh51_basefo = "0" ; 
        } 
       $sql  .= $virgula." rh51_basefo = $this->rh51_basefo ";
       $virgula = ",";
     }
     if(trim($this->rh51_descfo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh51_descfo"])){ 
        if(trim($this->rh51_descfo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh51_descfo"])){ 
           $this->rh51_descfo = "0" ; 
        } 
       $sql  .= $virgula." rh51_descfo = $this->rh51_descfo ";
       $virgula = ",";
     }
     if(trim($this->rh51_b13fo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh51_b13fo"])){ 
        if(trim($this->rh51_b13fo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh51_b13fo"])){ 
           $this->rh51_b13fo = "0" ; 
        } 
       $sql  .= $virgula." rh51_b13fo = $this->rh51_b13fo ";
       $virgula = ",";
     }
     if(trim($this->rh51_d13fo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh51_d13fo"])){ 
        if(trim($this->rh51_d13fo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh51_d13fo"])){ 
           $this->rh51_d13fo = "0" ; 
        } 
       $sql  .= $virgula." rh51_d13fo = $this->rh51_d13fo ";
       $virgula = ",";
     }
     if(trim($this->rh51_ocorre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh51_ocorre"])){ 
       $sql  .= $virgula." rh51_ocorre = '$this->rh51_ocorre' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh51_seqpes!=null){
       $sql .= " rh51_seqpes = $this->rh51_seqpes";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh51_seqpes));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8842,'$this->rh51_seqpes','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh51_seqpes"]))
           $resac = db_query("insert into db_acount values($acount,1508,8842,'".AddSlashes(pg_result($resaco,$conresaco,'rh51_seqpes'))."','$this->rh51_seqpes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh51_basefo"]))
           $resac = db_query("insert into db_acount values($acount,1508,8843,'".AddSlashes(pg_result($resaco,$conresaco,'rh51_basefo'))."','$this->rh51_basefo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh51_descfo"]))
           $resac = db_query("insert into db_acount values($acount,1508,8844,'".AddSlashes(pg_result($resaco,$conresaco,'rh51_descfo'))."','$this->rh51_descfo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh51_b13fo"]))
           $resac = db_query("insert into db_acount values($acount,1508,8845,'".AddSlashes(pg_result($resaco,$conresaco,'rh51_b13fo'))."','$this->rh51_b13fo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh51_d13fo"]))
           $resac = db_query("insert into db_acount values($acount,1508,8846,'".AddSlashes(pg_result($resaco,$conresaco,'rh51_d13fo'))."','$this->rh51_d13fo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh51_ocorre"]))
           $resac = db_query("insert into db_acount values($acount,1508,8871,'".AddSlashes(pg_result($resaco,$conresaco,'rh51_ocorre'))."','$this->rh51_ocorre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Inss outras empresas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh51_seqpes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Inss outras empresas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh51_seqpes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh51_seqpes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh51_seqpes=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh51_seqpes));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8842,'$rh51_seqpes','E')");
         $resac = db_query("insert into db_acount values($acount,1508,8842,'','".AddSlashes(pg_result($resaco,$iresaco,'rh51_seqpes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1508,8843,'','".AddSlashes(pg_result($resaco,$iresaco,'rh51_basefo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1508,8844,'','".AddSlashes(pg_result($resaco,$iresaco,'rh51_descfo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1508,8845,'','".AddSlashes(pg_result($resaco,$iresaco,'rh51_b13fo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1508,8846,'','".AddSlashes(pg_result($resaco,$iresaco,'rh51_d13fo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1508,8871,'','".AddSlashes(pg_result($resaco,$iresaco,'rh51_ocorre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhinssoutros
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh51_seqpes != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh51_seqpes = $rh51_seqpes ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Inss outras empresas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh51_seqpes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Inss outras empresas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh51_seqpes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh51_seqpes;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhinssoutros";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $rh51_seqpes=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhinssoutros ";
     $sql .= "      inner join rhpessoalmov  on  rhpessoalmov.rh02_seqpes = rhinssoutros.rh51_seqpes";
     $sql .= "      inner join rhregime  on  rhregime.rh30_codreg = rhpessoalmov.rh02_codreg
		                                    and  rhregime.rh30_instit = rhpessoalmov.rh02_instit ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh51_seqpes!=null ){
         $sql2 .= " where rhinssoutros.rh51_seqpes = $rh51_seqpes "; 
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
   function sql_query_file ( $rh51_seqpes=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhinssoutros ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh51_seqpes!=null ){
         $sql2 .= " where rhinssoutros.rh51_seqpes = $rh51_seqpes "; 
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
   function sql_query_retorno ( $rh51_seqpes=null,$campos="*",$ordem=null,$dbwhere="",$anonovo,$mesnovo){ 
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
     $sql .= " from rhinssoutros ";
     $sql .= "      inner join rhpessoalmov on rh51_seqpes=rh02_seqpes ";
     $sql .= "      left  join rhpessoal on rh01_regist=rh02_regist ";
     $sql .= "      left  join rhpessoalmov a on a.rh02_regist=rh01_regist and a.rh02_anousu=".$anonovo."
                                        and a.rh02_mesusu=".$mesnovo." 
																				and a.rh02_instit=".db_getsession("DB_instit")." ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh51_seqpes!=null ){
         $sql2 .= " where rhinssoutros.rh51_seqpes = $rh51_seqpes "; 
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