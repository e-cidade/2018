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
//CLASSE DA ENTIDADE matestoqueitemlanc
class cl_matestoqueitemlanc { 
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
   var $m95_codlanc = 0; 
   var $m95_id_usuario = 0; 
   var $m95_data_dia = null; 
   var $m95_data_mes = null; 
   var $m95_data_ano = null; 
   var $m95_data = null; 
   var $m95_verificado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m95_codlanc = int8 = Código sequencial do lançamento 
                 m95_id_usuario = int4 = Cod. Usuário 
                 m95_data = date = Data de entrada 
                 m95_verificado = bool = Verificado 
                 ";
   //funcao construtor da classe 
   function cl_matestoqueitemlanc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoqueitemlanc"); 
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
       $this->m95_codlanc = ($this->m95_codlanc == ""?@$GLOBALS["HTTP_POST_VARS"]["m95_codlanc"]:$this->m95_codlanc);
       $this->m95_id_usuario = ($this->m95_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["m95_id_usuario"]:$this->m95_id_usuario);
       if($this->m95_data == ""){
         $this->m95_data_dia = ($this->m95_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["m95_data_dia"]:$this->m95_data_dia);
         $this->m95_data_mes = ($this->m95_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["m95_data_mes"]:$this->m95_data_mes);
         $this->m95_data_ano = ($this->m95_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["m95_data_ano"]:$this->m95_data_ano);
         if($this->m95_data_dia != ""){
            $this->m95_data = $this->m95_data_ano."-".$this->m95_data_mes."-".$this->m95_data_dia;
         }
       }
       $this->m95_verificado = ($this->m95_verificado == "f"?@$GLOBALS["HTTP_POST_VARS"]["m95_verificado"]:$this->m95_verificado);
     }else{
       $this->m95_codlanc = ($this->m95_codlanc == ""?@$GLOBALS["HTTP_POST_VARS"]["m95_codlanc"]:$this->m95_codlanc);
     }
   }
   // funcao para inclusao
   function incluir ($m95_codlanc){ 
      $this->atualizacampos();
     if($this->m95_id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "m95_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m95_data == null ){ 
       $this->erro_sql = " Campo Data de entrada nao Informado.";
       $this->erro_campo = "m95_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m95_verificado == null ){ 
       $this->erro_sql = " Campo Verificado nao Informado.";
       $this->erro_campo = "m95_verificado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->m95_codlanc = $m95_codlanc; 
     if(($this->m95_codlanc == null) || ($this->m95_codlanc == "") ){ 
       $this->erro_sql = " Campo m95_codlanc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoqueitemlanc(
                                       m95_codlanc 
                                      ,m95_id_usuario 
                                      ,m95_data 
                                      ,m95_verificado 
                       )
                values (
                                $this->m95_codlanc 
                               ,$this->m95_id_usuario 
                               ,".($this->m95_data == "null" || $this->m95_data == ""?"null":"'".$this->m95_data."'")." 
                               ,'$this->m95_verificado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lançamento de itens para o almoxarifado ($this->m95_codlanc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lançamento de itens para o almoxarifado já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lançamento de itens para o almoxarifado ($this->m95_codlanc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m95_codlanc;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m95_codlanc));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9645,'$this->m95_codlanc','I')");
       $resac = db_query("insert into db_acount values($acount,1660,9645,'','".AddSlashes(pg_result($resaco,0,'m95_codlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1660,9646,'','".AddSlashes(pg_result($resaco,0,'m95_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1660,9647,'','".AddSlashes(pg_result($resaco,0,'m95_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1660,9648,'','".AddSlashes(pg_result($resaco,0,'m95_verificado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m95_codlanc=null) { 
      $this->atualizacampos();
     $sql = " update matestoqueitemlanc set ";
     $virgula = "";
     if(trim($this->m95_codlanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m95_codlanc"])){ 
       $sql  .= $virgula." m95_codlanc = $this->m95_codlanc ";
       $virgula = ",";
       if(trim($this->m95_codlanc) == null ){ 
         $this->erro_sql = " Campo Código sequencial do lançamento nao Informado.";
         $this->erro_campo = "m95_codlanc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m95_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m95_id_usuario"])){ 
       $sql  .= $virgula." m95_id_usuario = $this->m95_id_usuario ";
       $virgula = ",";
       if(trim($this->m95_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "m95_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m95_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m95_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["m95_data_dia"] !="") ){ 
       $sql  .= $virgula." m95_data = '$this->m95_data' ";
       $virgula = ",";
       if(trim($this->m95_data) == null ){ 
         $this->erro_sql = " Campo Data de entrada nao Informado.";
         $this->erro_campo = "m95_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["m95_data_dia"])){ 
         $sql  .= $virgula." m95_data = null ";
         $virgula = ",";
         if(trim($this->m95_data) == null ){ 
           $this->erro_sql = " Campo Data de entrada nao Informado.";
           $this->erro_campo = "m95_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->m95_verificado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m95_verificado"])){ 
       $sql  .= $virgula." m95_verificado = '$this->m95_verificado' ";
       $virgula = ",";
       if(trim($this->m95_verificado) == null ){ 
         $this->erro_sql = " Campo Verificado nao Informado.";
         $this->erro_campo = "m95_verificado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m95_codlanc!=null){
       $sql .= " m95_codlanc = $this->m95_codlanc";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m95_codlanc));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9645,'$this->m95_codlanc','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m95_codlanc"]))
           $resac = db_query("insert into db_acount values($acount,1660,9645,'".AddSlashes(pg_result($resaco,$conresaco,'m95_codlanc'))."','$this->m95_codlanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m95_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1660,9646,'".AddSlashes(pg_result($resaco,$conresaco,'m95_id_usuario'))."','$this->m95_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m95_data"]))
           $resac = db_query("insert into db_acount values($acount,1660,9647,'".AddSlashes(pg_result($resaco,$conresaco,'m95_data'))."','$this->m95_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m95_verificado"]))
           $resac = db_query("insert into db_acount values($acount,1660,9648,'".AddSlashes(pg_result($resaco,$conresaco,'m95_verificado'))."','$this->m95_verificado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamento de itens para o almoxarifado nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m95_codlanc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamento de itens para o almoxarifado nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m95_codlanc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m95_codlanc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m95_codlanc=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m95_codlanc));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9645,'$m95_codlanc','E')");
         $resac = db_query("insert into db_acount values($acount,1660,9645,'','".AddSlashes(pg_result($resaco,$iresaco,'m95_codlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1660,9646,'','".AddSlashes(pg_result($resaco,$iresaco,'m95_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1660,9647,'','".AddSlashes(pg_result($resaco,$iresaco,'m95_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1660,9648,'','".AddSlashes(pg_result($resaco,$iresaco,'m95_verificado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoqueitemlanc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m95_codlanc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m95_codlanc = $m95_codlanc ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamento de itens para o almoxarifado nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m95_codlanc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamento de itens para o almoxarifado nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m95_codlanc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m95_codlanc;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoqueitemlanc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m95_codlanc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueitemlanc ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matestoqueitemlanc.m95_id_usuario";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueitemlanc.m95_codlanc";
     $sql .= "      inner join matestoque  as a on   a.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql2 = "";
     if($dbwhere==""){
       if($m95_codlanc!=null ){
         $sql2 .= " where matestoqueitemlanc.m95_codlanc = $m95_codlanc "; 
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
   function sql_query_file ( $m95_codlanc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueitemlanc ";
     $sql2 = "";
     if($dbwhere==""){
       if($m95_codlanc!=null ){
         $sql2 .= " where matestoqueitemlanc.m95_codlanc = $m95_codlanc "; 
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