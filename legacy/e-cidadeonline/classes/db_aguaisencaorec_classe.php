<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: agua
//CLASSE DA ENTIDADE aguaisencaorec
class cl_aguaisencaorec { 
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
   var $x26_codisencao = 0; 
   var $x26_codisencaorec = 0; 
   var $x26_percentual = 0; 
   var $x26_codconsumotipo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x26_codisencao = int4 = Codigo Isencao 
                 x26_codisencaorec = int4 = Codigo 
                 x26_percentual = float4 = Percentual 
                 x26_codconsumotipo = int4 = Consumo 
                 ";
   //funcao construtor da classe 
   function cl_aguaisencaorec() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguaisencaorec"); 
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
       $this->x26_codisencao = ($this->x26_codisencao == ""?@$GLOBALS["HTTP_POST_VARS"]["x26_codisencao"]:$this->x26_codisencao);
       $this->x26_codisencaorec = ($this->x26_codisencaorec == ""?@$GLOBALS["HTTP_POST_VARS"]["x26_codisencaorec"]:$this->x26_codisencaorec);
       $this->x26_percentual = ($this->x26_percentual == ""?@$GLOBALS["HTTP_POST_VARS"]["x26_percentual"]:$this->x26_percentual);
       $this->x26_codconsumotipo = ($this->x26_codconsumotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["x26_codconsumotipo"]:$this->x26_codconsumotipo);
     }else{
       $this->x26_codisencao = ($this->x26_codisencao == ""?@$GLOBALS["HTTP_POST_VARS"]["x26_codisencao"]:$this->x26_codisencao);
       $this->x26_codisencaorec = ($this->x26_codisencaorec == ""?@$GLOBALS["HTTP_POST_VARS"]["x26_codisencaorec"]:$this->x26_codisencaorec);
     }
   }
   // funcao para inclusao
   function incluir ($x26_codisencaorec){ 
      $this->atualizacampos();
     if($this->x26_percentual == null ){ 
       $this->erro_sql = " Campo Percentual nao Informado.";
       $this->erro_campo = "x26_percentual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x26_codconsumotipo == null ){ 
       $this->erro_sql = " Campo Consumo nao Informado.";
       $this->erro_campo = "x26_codconsumotipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x26_codisencaorec == "" || $x26_codisencaorec == null ){
       $result = db_query("select nextval('aguaisencaorec_x26_codisencaorec_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguaisencaorec_x26_codisencaorec_seq do campo: x26_codisencaorec"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x26_codisencaorec = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguaisencaorec_x26_codisencaorec_seq");
       if(($result != false) && (pg_result($result,0,0) < $x26_codisencaorec)){
         $this->erro_sql = " Campo x26_codisencaorec maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x26_codisencaorec = $x26_codisencaorec; 
       }
     }
     if(($this->x26_codisencaorec == null) || ($this->x26_codisencaorec == "") ){ 
       $this->erro_sql = " Campo x26_codisencaorec nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguaisencaorec(
                                       x26_codisencao 
                                      ,x26_codisencaorec 
                                      ,x26_percentual 
                                      ,x26_codconsumotipo 
                       )
                values (
                                $this->x26_codisencao 
                               ,$this->x26_codisencaorec 
                               ,$this->x26_percentual 
                               ,$this->x26_codconsumotipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguaisencaorec ($this->x26_codisencaorec) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguaisencaorec já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguaisencaorec ($this->x26_codisencaorec) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x26_codisencaorec;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x26_codisencaorec));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8456,'$this->x26_codisencaorec','I')");
       $resac = db_query("insert into db_acount values($acount,1448,8455,'','".AddSlashes(pg_result($resaco,0,'x26_codisencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1448,8456,'','".AddSlashes(pg_result($resaco,0,'x26_codisencaorec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1448,8457,'','".AddSlashes(pg_result($resaco,0,'x26_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1448,8462,'','".AddSlashes(pg_result($resaco,0,'x26_codconsumotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x26_codisencaorec=null) { 
      $this->atualizacampos();
     $sql = " update aguaisencaorec set ";
     $virgula = "";
     if(trim($this->x26_codisencao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x26_codisencao"])){ 
       $sql  .= $virgula." x26_codisencao = $this->x26_codisencao ";
       $virgula = ",";
       if(trim($this->x26_codisencao) == null ){ 
         $this->erro_sql = " Campo Codigo Isencao nao Informado.";
         $this->erro_campo = "x26_codisencao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x26_codisencaorec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x26_codisencaorec"])){ 
       $sql  .= $virgula." x26_codisencaorec = $this->x26_codisencaorec ";
       $virgula = ",";
       if(trim($this->x26_codisencaorec) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "x26_codisencaorec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x26_percentual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x26_percentual"])){ 
       $sql  .= $virgula." x26_percentual = $this->x26_percentual ";
       $virgula = ",";
       if(trim($this->x26_percentual) == null ){ 
         $this->erro_sql = " Campo Percentual nao Informado.";
         $this->erro_campo = "x26_percentual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x26_codconsumotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x26_codconsumotipo"])){ 
       $sql  .= $virgula." x26_codconsumotipo = $this->x26_codconsumotipo ";
       $virgula = ",";
       if(trim($this->x26_codconsumotipo) == null ){ 
         $this->erro_sql = " Campo Consumo nao Informado.";
         $this->erro_campo = "x26_codconsumotipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($x26_codisencaorec!=null){
       $sql .= " x26_codisencaorec = $this->x26_codisencaorec";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x26_codisencaorec));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8456,'$this->x26_codisencaorec','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x26_codisencao"]))
           $resac = db_query("insert into db_acount values($acount,1448,8455,'".AddSlashes(pg_result($resaco,$conresaco,'x26_codisencao'))."','$this->x26_codisencao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x26_codisencaorec"]))
           $resac = db_query("insert into db_acount values($acount,1448,8456,'".AddSlashes(pg_result($resaco,$conresaco,'x26_codisencaorec'))."','$this->x26_codisencaorec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x26_percentual"]))
           $resac = db_query("insert into db_acount values($acount,1448,8457,'".AddSlashes(pg_result($resaco,$conresaco,'x26_percentual'))."','$this->x26_percentual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x26_codconsumotipo"]))
           $resac = db_query("insert into db_acount values($acount,1448,8462,'".AddSlashes(pg_result($resaco,$conresaco,'x26_codconsumotipo'))."','$this->x26_codconsumotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguaisencaorec nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x26_codisencaorec;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguaisencaorec nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x26_codisencaorec;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x26_codisencaorec;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x26_codisencaorec=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x26_codisencaorec));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8456,'$x26_codisencaorec','E')");
         $resac = db_query("insert into db_acount values($acount,1448,8455,'','".AddSlashes(pg_result($resaco,$iresaco,'x26_codisencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1448,8456,'','".AddSlashes(pg_result($resaco,$iresaco,'x26_codisencaorec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1448,8457,'','".AddSlashes(pg_result($resaco,$iresaco,'x26_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1448,8462,'','".AddSlashes(pg_result($resaco,$iresaco,'x26_codconsumotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguaisencaorec
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x26_codisencaorec != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x26_codisencaorec = $x26_codisencaorec ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguaisencaorec nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x26_codisencaorec;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguaisencaorec nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x26_codisencaorec;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x26_codisencaorec;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguaisencaorec";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $x26_codisencaorec=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguaisencaorec ";
     $sql .= "      inner join aguaisencao  on  aguaisencao.x10_codisencao = aguaisencaorec.x26_codisencao";
     $sql .= "      inner join aguaconsumotipo  on  aguaconsumotipo.x25_codconsumotipo = aguaisencaorec.x26_codconsumotipo";
     $sql .= "      inner join aguabase  on  aguabase.x01_matric = aguaisencao.x10_matric";
     $sql .= "      inner join aguaisencaotipo  on  aguaisencaotipo.x29_codisencaotipo = aguaisencao.x10_codisencaotipo";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = aguaconsumotipo.x25_codhist";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = aguaconsumotipo.x25_receit";
     $sql2 = "";
     if($dbwhere==""){
       if($x26_codisencaorec!=null ){
         $sql2 .= " where aguaisencaorec.x26_codisencaorec = $x26_codisencaorec "; 
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
   function sql_query_file ( $x26_codisencaorec=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguaisencaorec ";
     $sql2 = "";
     if($dbwhere==""){
       if($x26_codisencaorec!=null ){
         $sql2 .= " where aguaisencaorec.x26_codisencaorec = $x26_codisencaorec "; 
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